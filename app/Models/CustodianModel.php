<?php

namespace App\Models;

use App\Core\Database;
use App\Core\Model;

class CustodianModel {
    use Model;

    protected $table = 'custodians';
    protected $allowedColumns = [
        'user_id',
        'donor_id',
        'custodian_number',
        'name',
        'relationship',
        'phone',
        'email',
        'address'
    ];

    // ─────────────────────────────────────────────────
    // CUSTODIAN IDENTITY
    // ─────────────────────────────────────────────────

    /**
     * Get custodian record for logged-in user
     */
    public function getCustodianByUserId($userId)
    {
        return $this->queryJoin(
            [['table' => 'donors d', 'on' => 'c.donor_id = d.id', 'type' => 'JOIN']],
            ['c.user_id' => $userId],
            'c.*, d.first_name AS donor_first_name, d.last_name AS donor_last_name, d.nic_number AS donor_nic',
            '',
            1,
            0,
            'custodians c'
        )[0] ?? null;
    }

    /**
     * Get the full donor profile for a custodian
     */
    public function getDonorForCustodian($custodianId)
    {
        return $this->queryJoin(
            [
                ['table' => 'donors d', 'on' => 'c.donor_id = d.id', 'type' => 'JOIN'],
                ['table' => 'users u', 'on' => 'd.user_id = u.id', 'type' => 'LEFT']
            ],
            ['c.id' => $custodianId],
            'd.*, u.email AS user_email, u.phone AS user_phone',
            '',
            1,
            0,
            'custodians c'
        )[0] ?? null;
    }

    /**
     * Get the other custodian for the same donor
     */
    public function getCoCustodian($donorId, $excludeCustodianId)
    {
        return $this->first(['donor_id' => $donorId], ['id' => $excludeCustodianId]);
    }

    /**
     * Get both custodians for a donor
     */
    public function getCustodiansByDonor($donorId)
    {
        return $this->where(['donor_id' => $donorId], [], '*', 'custodian_number ASC');
    }

    /**
     * Update custodian contact information
     */
    public function updateCustodianContact($custodianId, $data)
    {
        return $this->update($custodianId, $data);
    }

    // ─────────────────────────────────────────────────
    // CONSENT RESOLUTION
    // ─────────────────────────────────────────────────

    public function resolveActiveConsent($donorId)
    {
        // Use queryJoin for body consents
        $bodyConsents = $this->queryJoin(
            [['table' => 'medical_schools ms', 'on' => 'bdc.medical_school_id = ms.id', 'type' => 'LEFT']],
            ['bdc.donor_id' => $donorId],
            'bdc.*, ms.school_name, ms.university_affiliation, ms.address AS school_address',
            'bdc.consent_date DESC',
            100,
            0,
            'body_donation_consents bdc'
        ) ?: [];

        // Use queryJoin for organ pledges
        $organPledges = $this->queryJoin(
            [['table' => 'organs o', 'on' => 'dp.organ_id = o.id', 'type' => 'JOIN']],
            ['dp.donor_id' => $donorId, 'dp.status !=' => 'WITHDRAWN'],
            'dp.*, o.name AS organ_name',
            '',
            100,
            0,
            'donor_pledges dp'
        ) ?: [];

        $hasCornea = false;
        foreach ($organPledges as $pledge) {
            $name = strtolower($pledge->organ_name ?? '');
            if ($name === 'cornea' || $name === 'eye') {
                $hasCornea = true;
                break;
            }
        }

        $donor = $this->first(['id' => $donorId], [], 'pledge_type', '', 'donors');
        $pledgeType = $donor ? $donor->pledge_type : 'NONE';

        $result = [
            'donation_type' => 'NONE',
            'body_consents' => $bodyConsents,
            'organ_pledges' => $organPledges,
            'has_cornea' => $hasCornea,
            'pledge_type' => $pledgeType
        ];

        if ($pledgeType === 'DECEASED_BODY') {
            $result['donation_type'] = $hasCornea ? 'BODY_AND_CORNEA' : 'BODY';
        } elseif ($pledgeType === 'DECEASED_ORGAN') {
            $result['donation_type'] = 'ORGAN';
        }

        return $result;
    }

    /**
     * Get next of kin for a donor
     */
    public function getNextOfKin($donorId)
    {
        return $this->where(['donor_id' => $donorId], [], '*', '', 'next_of_kin');
    }

    /**
     * Submit a legal action (confirm or object)
     */
    public function submitLegalAction($data)
    {
        $result = $this->insert($data, 'custodian_legal_actions');

        if ($result) {
            $newStatus = ($data['action_type'] === 'CONFIRM') ? 'CONFIRMED' : 'OBJECTED';
            $this->update($data['donation_case_id'], ['legal_status' => $newStatus], 'id', 'donation_cases');
        }

        return $result;
    }

    /**
     * Get legal action for a case
     */
    public function getLegalAction($caseId)
    {
        return $this->queryJoin(
            [['table' => 'custodians c', 'on' => 'cla.custodian_id = c.id', 'type' => 'JOIN']],
            ['cla.donation_case_id' => $caseId],
            'cla.*, c.name AS custodian_name',
            'cla.created_at DESC',
            1,
            0,
            'custodian_legal_actions cla'
        )[0] ?? null;
    }

    // ─────────────────────────────────────────────────
    // SEQUENTIAL INSTITUTION MANAGEMENT
    // ─────────────────────────────────────────────────

    /**
     * Get institutions available for a case (not yet rejected, not currently active)
     */
    public function getAvailableInstitutions($caseId, $track)
    {
        // Get already-attempted institution IDs
        $attempted = $this->where(
            ['donation_case_id' => $caseId, 'track' => $track], 
            [], 
            'institution_id', 
            '', 
            'case_institution_status'
        ) ?: [];
        $attemptedIds = array_map(fn($r) => $r->institution_id, $attempted);

        if ($track === 'BODY' || $track === 'CORNEA') {
            // Strict Rule: ONLY fetch medical schools that the donor explicitly consented to
            $institutions = $this->queryJoin(
                [
                    ['table' => 'medical_schools ms', 'on' => 'bdc.medical_school_id = ms.id', 'type' => 'JOIN'],
                    ['table' => 'donation_cases dc', 'on' => 'dc.donor_id = bdc.donor_id', 'type' => 'JOIN']
                ],
                ['dc.id' => $caseId],
                'ms.id, ms.school_name, ms.university_affiliation, ms.address',
                '',
                100,
                0,
                'body_donation_consents bdc'
            );
        } else {
            // ORGAN: get from donor_pledges -> hospitals (via organ_requests or direct mapping)
            // For now, get all active hospitals approved by the system.
            $institutions = $this->where(['verification_status' => 'APPROVED'], [], 'id, name AS school_name, registration_number, address', '', 'hospitals') ?: [];
        }

        // Filter out already attempted
        if (!empty($attemptedIds)) {
            $institutions = array_filter($institutions, fn($inst) => !in_array($inst->id, $attemptedIds));
            $institutions = array_values($institutions);
        }

        return $institutions;
    }

    /**
     * Select an institution for the current attempt (one at a time)
     * Returns false if another institution is currently under review
     */
    public function selectInstitution($caseId, $institutionId, $institutionType, $track)
    {
        // STRICT BACKEND LOGIC: Only allow universities the donor explicitly consented to
        $availableInstitutions = $this->getAvailableInstitutions($caseId, $track);
        $isConsented = false;
        foreach ($availableInstitutions as $inst) {
            if ($inst->id == $institutionId) {
                $isConsented = true;
                break;
            }
        }

        if (!$isConsented) return false;

        // Check if there's already a current institution being reviewed
        $existingCount = $this->count(
            [
                'donation_case_id' => $caseId,
                'track' => $track,
                'is_current' => 1,
                'institution_status IN' => "('PENDING','UNDER_REVIEW')"
            ],
            [],
            'case_institution_status'
        );

        if ($existingCount > 0) return false;

        // Check if any institution was already accepted
        $acceptedCount = $this->count(
            ['donation_case_id' => $caseId, 'track' => $track, 'institution_status' => 'ACCEPTED'],
            [],
            'case_institution_status'
        );

        if ($acceptedCount > 0) return false;

        // Get next attempt order
        $nextOrder = ($this->max('attempt_order', ['donation_case_id' => $caseId, 'track' => $track], 'case_institution_status') ?? 0) + 1;

        // Clear previous active claims
        $this->updateWhere(['is_current' => 0], ['donation_case_id' => $caseId, 'track' => $track], 'case_institution_status');

        return $this->insert([
            'donation_case_id' => $caseId,
            'institution_type' => $institutionType,
            'institution_id'   => $institutionId,
            'track'            => $track,
            'attempt_order'    => $nextOrder,
            'is_current'       => 1,
            'custodian_action' => 'SUBMITTED'
        ], 'case_institution_status');
    }


    /**
     * Submit documents to the current institution (lock docs, mark as submitted)
     */
    public function submitToInstitution($caseInstitutionStatusId)
    {
        // Update institution status
        $this->update($caseInstitutionStatusId, [
            'custodian_action' => 'SUBMITTED',
            'institution_status' => 'UNDER_REVIEW',
            'submission_date' => date('Y-m-d H:i:s')
        ], 'id', 'case_institution_status');

        // Lock all documents for this attempt
        return $this->updateWhere(
            ['status' => 'SUBMITTED'],
            ['case_institution_status_id' => $caseInstitutionStatusId, 'status' => 'UPLOADED'],
            'custodian_documents'
        );
    }

    /**
     * Handle institution response (accept or reject)
     * On reject: is_current → 0, unlock next selection
     * On accept: is_current stays 1, case is complete for this track
     */
    public function handleInstitutionResponse($statusId, $response, $message = null)
    {
        $this->update($statusId, [
            'institution_status' => $response,
            'rejection_message' => $message,
            'response_date' => date('Y-m-d H:i:s'),
            'is_current' => ($response === 'ACCEPTED') ? 1 : 0
        ], 'id', 'case_institution_status');

        // If accepted, update overall case status to COMPLETED
        if ($response === 'ACCEPTED') {
            $cis = $this->first(['id' => $statusId], [], 'donation_case_id', '', 'case_institution_status');
            if ($cis) {
                $this->updateCaseStatus($cis->donation_case_id, null, 'COMPLETED');
            }
        }

        return true;
    }


    // ─────────────────────────────────────────────────
    // DOCUMENT MANAGEMENT
    // ─────────────────────────────────────────────────

    /**
     * Upload a document
     */
    public function uploadDocument($data)
    {
        return $this->insert($data, 'custodian_documents');
    }

    public function getDocuments($caseInstitutionStatusId)
    {
        return $this->where(['case_institution_status_id' => $caseInstitutionStatusId], [], '*', 'uploaded_at ASC', 'custodian_documents');
    }

    /**
     * Get all documents for a case
     */
    public function getAllDocuments($caseId)
    {
        return $this->queryJoin(
            [['table' => 'case_institution_status cis', 'on' => 'cd.case_institution_status_id = cis.id', 'type' => 'JOIN']],
            ['cd.donation_case_id' => $caseId],
            'cd.*, cis.institution_type, cis.institution_id, cis.track',
            'cd.uploaded_at ASC',
            100,
            0,
            'custodian_documents cd'
        );
    }

    // ─────────────────────────────────────────────────
    // CADAVER DATA SHEET
    // ─────────────────────────────────────────────────

    /**
     * Save or update cadaver data sheet
     */
    public function saveCadaverSheet($data)
    {
        $existing = $this->getCadaverSheet($data['case_institution_status_id']);
        $formDataJson = json_encode($data['form_data']);

        if ($existing) {
            return $this->update($existing->id, [
                'form_data' => $formDataJson,
                'status'    => $data['status'] ?? 'DRAFT'
            ], 'id', 'cadaver_data_sheets');
        } else {
            return $this->insert([
                'donation_case_id'           => $data['donation_case_id'],
                'case_institution_status_id' => $data['case_institution_status_id'],
                'form_data'                  => $formDataJson,
                'status'                     => $data['status'] ?? 'DRAFT'
            ], 'cadaver_data_sheets');
        }
    }

    /**
     * Get cadaver data sheet for a specific institution attempt
     */
    public function getCadaverSheet($caseInstitutionStatusId)
    {
        $res = $this->first(['case_institution_status_id' => $caseInstitutionStatusId], [], '*', '', 'cadaver_data_sheets');
        if ($res && !empty($res->form_data)) {
            $res->form_data = json_decode($res->form_data, true);
        }
        return $res;
    }

    // ─────────────────────────────────────────────────
    // TIMELINE / AUDIT
    // ─────────────────────────────────────────────────

    // --- DOCUMENT BUNDLE METHODS ---------------------------------------

    public function getSwornStatement($caseId) {
        return $this->first(['donation_case_id' => $caseId], [], '*', '', 'sworn_statements');
    }

    public function saveSwornStatement($caseId, $formData) {
        $existing = $this->getSwornStatement($caseId);
        $json = json_encode($formData);
        if ($existing) {
            $this->updateWhere(['form_data' => $json], ['donation_case_id' => $caseId], 'sworn_statements');
        } else {
            $this->insert(['donation_case_id' => $caseId, 'form_data' => $json], 'sworn_statements');
        }
    }

    public function getCadaverDataSheet($caseId) {
        return $this->first(['donation_case_id' => $caseId], [], '*', '', 'cadaver_data_sheets');
    }

    public function saveCadaverDataSheet($caseId, $formData) {
        $existing = $this->getCadaverDataSheet($caseId);
        $json = json_encode($formData);
        
        if ($existing) {
            $this->updateWhere(['form_data' => $json], ['donation_case_id' => $caseId], 'cadaver_data_sheets');
        } else {
            $cis = $this->first(['donation_case_id' => $caseId], [], 'id', 'is_current DESC, id DESC', 'case_institution_status');
            $cisId = $cis ? $cis->id : 0;
            $this->insert(['donation_case_id' => $caseId, 'case_institution_status_id' => $cisId, 'form_data' => $json], 'cadaver_data_sheets');
        }
    }

    public function submitBundle($caseId, $checklistJson = null) {
        $this->update($caseId, ['bundle_status' => 'SUBMITTED'], 'id', 'donation_cases');
        
        $this->updateWhere(
            [
                'document_status' => 'PENDING_REVIEW', 
                'document_action_at' => date('Y-m-d H:i:s'),
                'submitted_checklist_json' => $checklistJson
            ],
            [
                'donation_case_id' => $caseId, 
                'is_current' => 1, 
                'institution_status' => 'ACCEPTED'
            ],
            'case_institution_status'
        );
    }

    /**
     * Get the result of the document review for banners
     */
    public function getDocumentReviewStatus($donationCaseId)
    {
        $res = $this->queryJoin(
            [['table' => 'medical_schools ms', 'on' => 'cis.institution_id = ms.id', 'type' => 'LEFT']],
            ['cis.donation_case_id' => $donationCaseId, 'cis.is_current' => 1],
            'cis.*, CASE WHEN cis.institution_type = "MEDICAL_SCHOOL" THEN ms.school_name ELSE "Institution" END AS institution_name',
            '',
            1,
            0,
            'case_institution_status cis'
        );
        return $res ? $res[0] : null;
    }

    /**
     * Get the custodian ID who declared the death (The Leader)
     */
    public function getLeaderId($caseId)
    {
        $res = $this->queryJoin(
            [['table' => 'death_declarations dd', 'on' => 'donation_cases.death_declaration_id = dd.id', 'type' => 'JOIN']],
            ['donation_cases.id' => $caseId],
            'dd.declared_by_custodian_id',
            '',
            1
        );
        return $res ? (int)$res[0]->declared_by_custodian_id : null;
    }

    /**
     * Check if a custodian is the leader for a case
     */
    public function isLeader($custodianId, $caseId)
    {
        $leaderId = $this->getLeaderId($caseId);
        if ($leaderId === null) return true; // If no death declared yet, anyone is leader
        return (int)$custodianId === $leaderId;
    }

    /**
     * Get issued certificates for a donation case
     */
    public function getDonationCertificates($caseId)
    {
        return $this->queryJoin(
            [
                ['table' => 'case_institution_status cis', 'on' => 'donation_certificates.case_institution_request_id = cis.id', 'type' => 'JOIN'],
                ['table' => 'medical_schools ms', 'on' => 'cis.institution_id = ms.id AND cis.institution_type = "MEDICAL_SCHOOL"', 'type' => 'LEFT'],
                ['table' => 'hospitals h', 'on' => 'cis.institution_id = h.id AND cis.institution_type = "HOSPITAL"', 'type' => 'LEFT']
            ],
            ['donation_certificates.donation_case_id' => $caseId],
            'donation_certificates.*, CASE WHEN cis.institution_type = "MEDICAL_SCHOOL" THEN ms.school_name WHEN cis.institution_type = "HOSPITAL" THEN h.name ELSE "Unknown Institution" END AS institution_name',
            '',
            100,
            0,
            'donation_certificates'
        ) ?: [];
    }

    /**
     * Get issued appreciation letters for a donation case
     */
    public function getAppreciationLetters($caseId)
    {
        // Letters are issued from body usage records
        return $this->queryJoin(
            [
                ['table' => 'body_usage_logs bul', 'on' => 'appreciation_letters.usage_log_id = bul.id', 'type' => 'JOIN'],
                ['table' => 'medical_schools ms', 'on' => 'bul.medical_school_id = ms.id', 'type' => 'JOIN'],
                ['table' => 'donation_cases dc', 'on' => 'bul.donor_id = dc.donor_id', 'type' => 'JOIN']
            ],
            ['dc.id' => $caseId],
            'appreciation_letters.*, ms.school_name AS institution_name, bul.usage_type',
            'appreciation_letters.issued_at DESC',
            100,
            0,
            'appreciation_letters'
        ) ?: [];
    }

    /**
     * Get archived or completed cases for a donor
     */
    public function getArchivedCases($donorId)
    {
        return $this->queryJoin(
            [['table' => 'death_declarations dd', 'on' => 'donation_cases.death_declaration_id = dd.id', 'type' => 'JOIN']],
            [
                'donation_cases.donor_id' => $donorId,
                'donation_cases.overall_status IN' => "('COMPLETED', 'CANCELLED', 'ARCHIVED')"
            ],
            'donation_cases.*, dd.date_of_death, dd.cause_of_death',
            'donation_cases.created_at DESC'
        ) ?: [];
    }
}


