<?php

namespace App\Models;

use App\Core\Database;
use App\Core\Model;

class CustodianModel
{
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
        'address',
        'age'
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


    /**
     * Unified Registry of all donor intents and successful outcomes
     */
    public function getConsentRegistry($donorId)
    {
        // 1. Organ Pledges (Include Active and Withdrawn)
        $organPledges = $this->queryJoin(
            [['table' => 'organs o', 'on' => 'dp.organ_id = o.id', 'type' => 'JOIN']],
            ['dp.donor_id' => $donorId],
            'dp.*, o.name AS item_name',
            'dp.pledge_date DESC',
            100,
            0,
            'donor_pledges dp'
        ) ?: [];

        // 2. Body Consents (Include Active and Withdrawn)
        $bodyConsents = $this->queryJoin(
            [['table' => 'medical_schools ms', 'on' => 'bdc.medical_school_id = ms.id', 'type' => 'LEFT']],
            ['bdc.donor_id' => $donorId],
            'bdc.*, ms.school_name AS item_name',
            'bdc.consent_date DESC',
            100,
            0,
            'body_donation_consents bdc'
        ) ?: [];

        // 3. Donation Outcomes (Medical History)
        $outcomes = $this->getDonationOutcomes($donorId) ?: [];

        // 4. Donor Verification Status
        $donor = $this->first(['id' => $donorId], [], 'verification_status', '', 'donors');
        $isDonorApproved = ($donor && $donor->verification_status === 'APPROVED');

        // 5. Align with Source-of-Truth Mode
        $maxOrganTs = 0;
        foreach ($organPledges as $p) {
            if ($p->status === 'WITHDRAWN')
                continue;
            $ts = strtotime($p->pledge_date);
            if ($ts > $maxOrganTs)
                $maxOrganTs = $ts;
        }

        $maxBodyTs = 0;
        foreach ($bodyConsents as $b) {
            if ($b->status === 'WITHDRAWN')
                continue;
            $ts = strtotime($b->consent_date);
            if ($ts > $maxBodyTs)
                $maxBodyTs = $ts;
        }

        $timeline = [];

        foreach ($organPledges as $p) {
            if ($p->organ_id == 10)
                continue;

            // Strictly exclude living donation pledges from the main registry
            if (in_array($p->organ_id, [2, 3]))
                continue;

            $pTs = strtotime($p->pledge_date);
            $isCornea = ($p->organ_id == 4);
            $isSuperseded = ($p->status !== 'WITHDRAWN' && !$isCornea && $maxBodyTs > $pTs);

            // Categorization
            $category = "After-Death Case";
            if ($p->organ_id == 9) {
                $category = "Brain-Dead Case";
            }

            $currentStatus = $p->status === 'WITHDRAWN' ? 'WITHDRAWN' : ($isSuperseded ? 'SUPERSEDED' : ($isDonorApproved ? 'ACTIVE' : 'PENDING VERIFICATION'));

            $timeline[] = (object) [
                'id' => $p->id,
                'organ_id' => $p->organ_id,
                'type' => 'ORGAN_PLEDGE',
                'item_name' => htmlspecialchars($p->item_name),
                'date' => $p->pledge_date,
                'status' => $currentStatus,
                'category' => $category,
                'signed_form_path' => $p->signed_form_path,
                'withdrawal_pdf_path' => $p->withdrawal_pdf_path,
                'raw_status' => $p->status,
                'is_outcome' => false,
                'is_deceased_intent' => true
            ];
        }

        foreach ($bodyConsents as $b) {
            $bTs = strtotime($b->consent_date);
            $isSuperseded = ($b->status !== 'WITHDRAWN' && $maxOrganTs > $bTs);

            $uName = $b->item_name ?? 'Medical School';
            $currentStatus = $b->status === 'WITHDRAWN' ? 'WITHDRAWN' : ($isSuperseded ? 'SUPERSEDED' : ($isDonorApproved ? 'ACTIVE' : 'PENDING VERIFICATION'));

            $timeline[] = (object) [
                'id' => $b->id,
                'organ_id' => 10,
                'type' => 'BODY_CONSENT',
                'item_name' => "Whole Body Donation",
                'holding_entity' => $uName,
                'date' => $b->consent_date,
                'status' => $currentStatus,
                'category' => "Medical School Path",
                'signed_form_path' => $b->signed_form_path,
                'withdrawal_pdf_path' => $b->withdrawal_pdf_path,
                'raw_status' => $b->status,
                'is_outcome' => false,
                'is_deceased_intent' => true
            ];
        }

        foreach ($outcomes as $o) {
            $timeline[] = (object) [
                'id' => $o->history_id ?? 0,
                'type' => 'LIVING_DONATION_OUTCOME',
                'item_name' => $o->donated_organ ?? $o->item_name ?? 'Unknown',
                'date' => $o->donation_date,
                'status' => 'INFORMATION ONLY',
                'category' => 'Living Donation Success',
                'hospital' => $o->hospital_name ?? 'Local Medical Facility',
                'is_outcome' => true,
                'is_deceased_intent' => false
            ];
        }

        // --- FINAL REFINEMENT & DEDUPLICATION ---
        $refined = [];
        $seenIntents = []; // name -> type

        usort($timeline, fn($a, $b) => strtotime($b->date) - strtotime($a->date));

        foreach ($timeline as $t) {
            // Clean name for display and comparison
            $cleanName = trim(str_replace('(After death)', '', $t->item_name));
            $t->item_name = $cleanName;

            // Only deduplicate deceased intents (Pledges/Consents), outcomes stay unique
            if (!$t->is_outcome) {
                $key = $cleanName . '|' . $t->type;
                if (isset($seenIntents[$key]))
                    continue;
                $seenIntents[$key] = true;
            }

            $refined[] = $t;
        }

        return $refined;
    }

    /**
     * API Wrapper for record details
     */
    public function getRegistryRecordDetails($type, $id)
    {
        return $this->getRegistryItemDetails($type, $id);
    }

    /**
     * Get full details for a specific consent including witness info
     */
    /**
     * Fetch high-fidelity details for a registry item (Witnesses, Forms, Dates)
     */
    public function getRegistryItemDetails($type, $id)
    {
        $id = (int) $id;
        $details = [
            'type' => $type,
            'witnesses' => [],
            'custodians' => [],
            'form_path' => null,
            'dates' => [],
            'description' => null,
            'is_deceased_intent' => true
        ];

        if ($type === 'ORGAN_PLEDGE') {
            $pledge = $this->queryJoin(
                [['table' => 'organs o', 'on' => 'dp.organ_id = o.id', 'type' => 'JOIN']],
                ['dp.id' => $id],
                'dp.*, o.name AS organ_name',
                '',
                1,
                0,
                'donor_pledges dp'
            );
            if ($pledge) {
                $p = $pledge[0];
                $details['item_name'] = $p->organ_name;
                $details['status'] = $p->status;
                $details['form_path'] = ($p->status === 'WITHDRAWN') ? $p->withdrawal_pdf_path : $p->signed_form_path;
                $details['dates'] = ['Pledged On' => $p->pledge_date];
                if ($p->withdrawal_date)
                    $details['dates']['Withdrawn On'] = $p->withdrawal_date;

                if (in_array($p->organ_id, [2, 3]))
                    $details['is_deceased_intent'] = false;

                // Fetch witnesses from dedicated table for this specific organ
                $details['witnesses'] = $this->query("SELECT name, nic_number, contact_number as phone, address FROM witnesses WHERE donor_id = :did AND organ_id = :oid", [':did' => $p->donor_id, ':oid' => $p->organ_id]) ?: [];

                // Fetch custodians
                $details['custodians'] = $this->query("SELECT name, relationship, phone, email FROM custodians WHERE donor_id = :did ORDER BY custodian_number ASC", [':did' => $p->donor_id]) ?: [];
            }
        } elseif ($type === 'BODY_CONSENT') {
            $consent = $this->queryJoin(
                [['table' => 'medical_schools ms', 'on' => 'bdc.medical_school_id = ms.id', 'type' => 'LEFT']],
                ['bdc.id' => $id],
                'bdc.*, ms.school_name',
                '',
                1,
                0,
                'body_donation_consents bdc'
            );
            if ($consent) {
                $c = $consent[0];
                $details['item_name'] = 'Whole Body Donation';
                $details['status'] = $c->status;

                $details['form_path'] = ($c->status === 'WITHDRAWN') ? $c->withdrawal_pdf_path : $c->signed_form_path;
                $details['dates'] = ['Consent Given' => $c->consent_date];
                if ($c->withdrawal_date)
                    $details['dates']['Withdrawn On'] = $c->withdrawal_date;
                $details['description'] = "Designated Institution: " . ($c->school_name ?? 'Unspecified Medical School');

                // Witnesses are stored inline for Body Consent
                $details['witnesses'] = [];
                if (!empty(trim($c->witness1_name))) {
                    $details['witnesses'][] = (object) ['name' => $c->witness1_name, 'nic_number' => $c->witness1_nic, 'phone' => $c->witness1_phone, 'address' => $c->witness1_address];
                }
                if (!empty(trim($c->witness2_name))) {
                    $details['witnesses'][] = (object) ['name' => $c->witness2_name, 'nic_number' => $c->witness2_nic, 'phone' => $c->witness2_phone, 'address' => $c->witness2_address];
                }

                // Fetch custodians
                $details['custodians'] = $this->query("SELECT name, relationship, phone, email FROM custodians WHERE donor_id = :did ORDER BY custodian_number ASC", [':did' => $c->donor_id]) ?: [];
            }
        } elseif ($type === 'SUCCESSFUL_DONATION') {
            $outcome = $this->queryJoin(
                [['table' => 'hospitals h', 'on' => 'dmh.hospital_id = h.id', 'type' => 'LEFT']],
                ['dmh.history_id' => $id],
                'dmh.*, h.name AS hospital_name',
                '',
                1,
                0,
                'donation_medical_history dmh'
            );
            if ($outcome) {
                $o = $outcome[0];
                $details['item_name'] = $o->donated_organ;
                $details['status'] = 'COMPLETED';
                $details['dates'] = ['Donated On' => $o->donation_date];
                $details['description'] = "Recovery performed at " . ($o->hospital_name ?: 'Unknown Facility') . ". " . $o->doctor_notes;
            }
        }

        return $details;
    }


    /**
     * Get donation outcomes (medical history) for a donor
     */
    public function getDonationOutcomes($donorId)
    {
        $query = "SELECT dmh.*, h.name as hospital_name 
                  FROM donation_medical_history dmh
                  LEFT JOIN hospitals h ON dmh.hospital_id = h.id
                  WHERE dmh.donor_id = :did
                  ORDER BY dmh.donation_date DESC";
        return $this->query($query, [':did' => $donorId]) ?: [];
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

        if (str_contains($track, 'BODY') || str_contains($track, 'CORNEA')) {
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
    public function selectInstitution($caseId, $institutionId, $institutionType, $track, $selectedItems = null)
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

        if (!$isConsented)
            return false;

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

        if ($existingCount > 0)
            return false;

        // Also prevent if an institution for this track is already ACCEPTED
        $acceptedCount = $this->count(
            [
                'donation_case_id' => $caseId,
                'track' => $track,
                'is_current' => 1,
                'institution_status' => 'ACCEPTED'
            ],
            [],
            'case_institution_status'
        );

        if ($acceptedCount > 0)
            return false;

        // --- SEQUENCE ENFORCEMENT ---
        // Rule: If track is BODY and operational track is SPLIT, Cornea must be DONE, EXPIRED, or SKIPPED
        $activeCase = $this->first(['id' => $caseId], [], '*', '', 'donation_cases');
        if ($activeCase && $track === 'BODY' && $activeCase->resolved_operational_track === 'BODY_CORNEA_SPLIT') {
            $items = json_decode($activeCase->operational_items_json, true);
            $limits = json_decode($activeCase->operational_time_limits_json, true);
            $corneaId = 4;

            $corneaStatus = $items[$corneaId]['status'] ?? 'none';
            $corneaExpired = isset($limits[$corneaId]) && time() > strtotime($limits[$corneaId]);

            if ($corneaStatus === 'available' && !$corneaExpired) {
                // Cornea is still actionable. Block Body.
                return false;
            }
        }

        // Get next attempt order
        $nextOrder = ($this->max('attempt_order', ['donation_case_id' => $caseId, 'track' => $track], 'case_institution_status') ?? 0) + 1;

        // Clear previous active claims
        $this->updateWhere(['is_current' => 0], ['donation_case_id' => $caseId, 'track' => $track], 'case_institution_status');

        // Clean and format selected items
        $selectedItemsList = [];
        if (!empty($selectedItems)) {
            $selectedItemsList = array_map('intval', explode(',', $selectedItems));
        }

        // Apply item skipping logic for the track
        if ($activeCase && !empty($selectedItemsList) && $institutionType === 'HOSPITAL') {
            $items = json_decode($activeCase->operational_items_json, true) ?? [];
            foreach ($items as $itemId => &$itemData) {
                // If it's a hospital track item
                if ($itemId != 9 && $itemId != 1 && $itemId != 10 && !str_starts_with($itemId, 'BODY_')) {
                    if ($itemData['status'] === 'available' || $itemData['status'] === 'requested') {
                        if (in_array((int) $itemId, $selectedItemsList)) {
                            $itemData['status'] = 'requested';
                        } else {
                            $itemData['status'] = 'skipped';
                        }
                    }
                }
            }
            $this->update($activeCase->id, ['operational_items_json' => json_encode($items)], 'id', 'donation_cases');
        }

        // Map specific tracks to base ENUM values (BODY, ORGAN, CORNEA)
        $dbTrack = $track;
        if (str_contains($track, 'BODY'))
            $dbTrack = 'BODY';
        elseif (str_contains($track, 'ORGAN'))
            $dbTrack = 'ORGAN';
        elseif (str_contains($track, 'CORNEA'))
            $dbTrack = 'CORNEA';

        return $this->insert([
            'donation_case_id' => $caseId,
            'institution_type' => $institutionType,
            'institution_id' => $institutionId,
            'track' => $dbTrack,
            'attempt_order' => $nextOrder,
            'is_current' => 1,
            'custodian_action' => 'SUBMITTED',
            'included_items_json' => !empty($selectedItemsList) ? json_encode($selectedItemsList) : null
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

        // Fetch the request record to get case and track
        $cis = $this->first(['id' => $statusId], [], '*', '', 'case_institution_status');
        if (!$cis)
            return false;

        // --- REVERSION LOGIC FOR REJECTED/WITHDRAWN REQUESTS ---
        if ($response === 'REJECTED' || $response === 'WITHDRAWN') {
            $activeCase = $this->first(['id' => $cis->donation_case_id], [], '*', '', 'donation_cases');
            if ($activeCase) {
                $items = json_decode($activeCase->operational_items_json, true) ?? [];
                $track = $cis->track;

                foreach ($items as $itemId => &$itemData) {
                    // Check if item belongs to this track (Hospital Tissues or Body)
                    $isMatch = false;
                    if ($track === 'HOSPITAL_TISSUE' && ($itemData['type'] ?? '') === 'HOSPITAL_TISSUE')
                        $isMatch = true;
                    if ($track === 'BODY' && ($itemData['type'] ?? '') === 'BODY')
                        $isMatch = true;

                    if ($isMatch) {
                        // Revert requested/skipped items back to available
                        if ($itemData['status'] === 'requested' || $itemData['status'] === 'skipped') {
                            $itemData['status'] = 'available';
                        }
                    }
                }

                $this->update($activeCase->id, ['operational_items_json' => json_encode($items)], 'id', 'donation_cases');
            }
        }

        // If accepted, update overall case status to COMPLETED
        if ($response === 'ACCEPTED') {
            $this->updateCaseStatus($cis->donation_case_id, null, 'COMPLETED');
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
     * Save OR Update Cadaver Data Sheet
     */
    public function saveCadaverSheet($caseId, $formData, $status = 'DRAFT')
    {
        $existing = $this->getCadaverSheet($caseId);
        $formDataJson = json_encode($formData);

        if ($existing) {
            return $this->update($existing->id, [
                'form_data' => $formDataJson,
                'status' => $status
            ], 'id', 'cadaver_data_sheets');
        } else {
            // Try to link to a current institution request if it exists
            $cis = $this->first(['donation_case_id' => $caseId], [], 'id', 'is_current DESC, id DESC', 'case_institution_status');
            $cisId = $cis ? $cis->id : 0;

            return $this->insert([
                'donation_case_id' => $caseId,
                'case_institution_status_id' => $cisId,
                'form_data' => $formDataJson,
                'status' => $status
            ], 'cadaver_data_sheets');
        }
    }

    /**
     * Get cadaver data sheet for a case
     */
    public function getCadaverSheet($caseId)
    {
        $res = $this->first(['donation_case_id' => $caseId], [], '*', 'id DESC', 'cadaver_data_sheets');
        if ($res && !empty($res->form_data)) {
            $res->form_data = json_decode($res->form_data, true);
        }
        return $res;
    }

    // ─────────────────────────────────────────────────
    // TIMELINE / AUDIT
    // ─────────────────────────────────────────────────

    // --- DOCUMENT BUNDLE METHODS ---------------------------------------

    public function getSwornStatement($caseId)
    {
        return $this->first(['donation_case_id' => $caseId], [], '*', '', 'sworn_statements');
    }

    public function saveSwornStatement($caseId, $formData)
    {
        $existing = $this->getSwornStatement($caseId);
        $json = json_encode($formData);
        if ($existing) {
            $this->updateWhere(['form_data' => $json], ['donation_case_id' => $caseId], 'sworn_statements');
        } else {
            $this->insert(['donation_case_id' => $caseId, 'form_data' => $json], 'sworn_statements');
        }
    }


    public function submitBundle($caseId, $checklistJson = null)
    {
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
            [['table' => 'death_declarations dd', 'on' => 'dc.death_declaration_id = dd.id', 'type' => 'JOIN']],
            ['dc.id' => $caseId],
            'dd.declared_by_custodian_id',
            '',
            1,
            0,
            'donation_cases dc'
        );
        return $res ? (int) $res[0]->declared_by_custodian_id : null;
    }

    /**
     * Check if a custodian is the leader for a case
     */
    public function isLeader($custodianId, $caseId)
    {
        $leaderId = $this->getLeaderId($caseId);
        if ($leaderId === null)
            return true; // If no death declared yet, anyone is leader
        return (int) $custodianId === $leaderId;
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
        // Letters are issued from body usage records or organ retrieval logs
        $case = $this->first(['id' => $caseId], [], 'donor_id', '', 'donation_cases');
        if (!$case)
            return [];

        return $this->queryJoin(
            [
                ['table' => 'body_usage_logs bul', 'on' => 'appreciation_letters.usage_log_id = bul.id', 'type' => 'JOIN'],
                ['table' => 'medical_schools ms', 'on' => 'bul.medical_school_id = ms.id', 'type' => 'LEFT'],
                ['table' => 'hospitals h', 'on' => 'bul.medical_school_id = h.id', 'type' => 'LEFT']
            ],
            ['bul.donor_id' => $case->donor_id],
            'appreciation_letters.*, 
             CASE WHEN bul.usage_type = "Organ Retrieval" THEN h.name ELSE COALESCE(ms.school_name, h.name, "Host Institution") END AS institution_name, 
             bul.usage_type',
            'appreciation_letters.issued_at DESC, appreciation_letters.id DESC',
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
            [['table' => 'death_declarations dd', 'on' => 'dc.death_declaration_id = dd.id', 'type' => 'JOIN']],
            [
                'dc.donor_id' => $donorId,
                'dc.overall_status IN' => "('COMPLETED', 'CANCELLED', 'ARCHIVED')"
            ],
            'dc.*, dd.date_of_death, dd.cause_of_death',
            'dc.created_at DESC',
            100,
            0,
            'donation_cases dc'
        ) ?: [];
    }
}


