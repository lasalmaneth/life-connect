<?php

namespace App\Models;

use App\Core\Database;

class CustodianModel {
    use Database;

    protected $table = 'custodians';

    // ─────────────────────────────────────────────────
    // CUSTODIAN IDENTITY
    // ─────────────────────────────────────────────────

    /**
     * Get custodian record for logged-in user
     */
    public function getCustodianByUserId($userId)
    {
        $query = "SELECT c.*, d.first_name AS donor_first_name, d.last_name AS donor_last_name,
                         d.nic_number AS donor_nic
                  FROM custodians c
                  JOIN donors d ON c.donor_id = d.id
                  WHERE c.user_id = :user_id";
        $result = $this->query($query, [':user_id' => $userId]);
        return $result ? $result[0] : null;
    }

    /**
     * Get the full donor profile for a custodian
     */
    public function getDonorForCustodian($custodianId)
    {
        $query = "SELECT d.*, u.email AS user_email, u.phone AS user_phone
                  FROM custodians c
                  JOIN donors d ON c.donor_id = d.id
                  JOIN users u ON d.user_id = u.id
                  WHERE c.id = :custodian_id";
        $result = $this->query($query, [':custodian_id' => $custodianId]);
        return $result ? $result[0] : null;
    }

    /**
     * Get the other custodian for the same donor
     */
    public function getCoCustodian($donorId, $excludeCustodianId)
    {
        $query = "SELECT * FROM custodians
                  WHERE donor_id = :donor_id AND id != :exclude_id";
        $result = $this->query($query, [
            ':donor_id' => $donorId,
            ':exclude_id' => $excludeCustodianId
        ]);
        return $result ? $result[0] : null;
    }

    /**
     * Get both custodians for a donor
     */
    public function getCustodiansByDonor($donorId)
    {
        $query = "SELECT * FROM custodians
                  WHERE donor_id = :donor_id
                  ORDER BY custodian_number ASC";
        return $this->query($query, [':donor_id' => $donorId]) ?: [];
    }

    /**
     * Update custodian contact information
     */
    public function updateCustodianContact($custodianId, $data)
    {
        $query = "UPDATE custodians SET
                  phone = :phone,
                  email = :email,
                  address = :address
                  WHERE id = :id";
        return $this->query($query, [
            ':phone' => $data['phone'] ?? null,
            ':email' => $data['email'] ?? null,
            ':address' => $data['address'] ?? null,
            ':id' => $custodianId
        ]);
    }

    // ─────────────────────────────────────────────────
    // CONSENT RESOLUTION
    // ─────────────────────────────────────────────────

    /**
     * Resolve the active donation type from the donor's consents.
     * Rules:
     *   - If body donation consent exists → BODY (or BODY_AND_CORNEA if eye was also pledged)
     *   - If only organ pledges → ORGAN
     *   - Body donation is the "last active" if registered after organ pledges
     */
    public function resolveActiveConsent($donorId)
    {
        // Check for body donation consent
        $bodyQuery = "SELECT bdc.*, ms.school_name, ms.university_affiliation, ms.address AS school_address
                      FROM body_donation_consents bdc
                      LEFT JOIN medical_schools ms ON bdc.medical_school_id = ms.id
                      WHERE bdc.donor_id = :donor_id
                      ORDER BY bdc.consent_date DESC";
        $bodyConsents = $this->query($bodyQuery, [':donor_id' => $donorId]) ?: [];

        // Check for organ pledges
        $organQuery = "SELECT dp.*, o.name AS organ_name
                       FROM donor_pledges dp
                       JOIN organs o ON dp.organ_id = o.id
                       WHERE dp.donor_id = :donor_id AND dp.status != 'WITHDRAWN'";
        $organPledges = $this->query($organQuery, [':donor_id' => $donorId]) ?: [];

        // Check if cornea/eye was pledged
        $hasCornea = false;
        foreach ($organPledges as $pledge) {
            if (strtolower($pledge->organ_name) === 'cornea' || strtolower($pledge->organ_name) === 'eye') {
                $hasCornea = true;
                break;
            }
        }

        // Determine type based on donor's pledge_type
        $donorQuery = "SELECT pledge_type FROM donors WHERE id = :donor_id";
        $donor = $this->query($donorQuery, [':donor_id' => $donorId]);
        $pledgeType = $donor ? $donor[0]->pledge_type : 'NONE';

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
        $query = "SELECT * FROM next_of_kin WHERE donor_id = :donor_id";
        return $this->query($query, [':donor_id' => $donorId]) ?: [];
    }

    // ─────────────────────────────────────────────────
    // DEATH DECLARATION
    // ─────────────────────────────────────────────────

    /**
     * Create a death declaration and start the donation window
     */
    public function createDeathDeclaration($data)
    {
        // Calculate 48-hour window
        $deathDateTime = $data['date_of_death'] . ' ' . $data['time_of_death'];
        $windowExpires = date('Y-m-d H:i:s', strtotime($deathDateTime . ' +48 hours'));

        $query = "INSERT INTO death_declarations (
                    donor_id, declared_by_custodian_id, date_of_death, time_of_death,
                    place_of_death, cause_of_death, additional_notes, window_expires_at
                  ) VALUES (
                    :donor_id, :custodian_id, :date_of_death, :time_of_death,
                    :place_of_death, :cause_of_death, :additional_notes, :window_expires_at
                  )";
        return $this->insert($query, [
            ':donor_id' => $data['donor_id'],
            ':custodian_id' => $data['custodian_id'],
            ':date_of_death' => $data['date_of_death'],
            ':time_of_death' => $data['time_of_death'],
            ':place_of_death' => $data['place_of_death'],
            ':cause_of_death' => $data['cause_of_death'],
            ':additional_notes' => $data['additional_notes'] ?? null,
            ':window_expires_at' => $windowExpires
        ]);
    }

    /**
     * Get death declaration for a donor
     */
    public function getDeathDeclaration($donorId)
    {
        $query = "SELECT dd.*, c.name AS declared_by_name
                  FROM death_declarations dd
                  JOIN custodians c ON dd.declared_by_custodian_id = c.id
                  WHERE dd.donor_id = :donor_id
                  ORDER BY dd.created_at DESC LIMIT 1";
        $result = $this->query($query, [':donor_id' => $donorId]);
        return $result ? $result[0] : null;
    }

    // ─────────────────────────────────────────────────
    // DONATION CASE
    // ─────────────────────────────────────────────────

    /**
     * Create a donation case (auto-generates case number)
     */
    public function createDonationCase($data)
    {
        // Generate case number: DC-YYYY-NNN
        $year = date('Y');
        $countQuery = "SELECT COUNT(*) AS cnt FROM donation_cases WHERE case_number LIKE :prefix";
        $countResult = $this->query($countQuery, [':prefix' => "DC-$year-%"]);
        $next = ($countResult ? $countResult[0]->cnt : 0) + 1;
        $caseNumber = sprintf("DC-%s-%03d", $year, $next);

        $query = "INSERT INTO donation_cases (
                    donor_id, death_declaration_id, case_number, donation_type
                  ) VALUES (
                    :donor_id, :death_declaration_id, :case_number, :donation_type
                  )";
        return $this->insert($query, [
            ':donor_id' => $data['donor_id'],
            ':death_declaration_id' => $data['death_declaration_id'],
            ':case_number' => $caseNumber,
            ':donation_type' => $data['donation_type']
        ]);
    }

    /**
     * Get the donation case for a donor
     */
    public function getDonationCase($donorId)
    {
        $query = "SELECT dc.*, dd.date_of_death, dd.time_of_death, dd.window_expires_at, dd.status AS death_status
                  FROM donation_cases dc
                  JOIN death_declarations dd ON dc.death_declaration_id = dd.id
                  WHERE dc.donor_id = :donor_id
                  ORDER BY dc.created_at DESC LIMIT 1";
        $result = $this->query($query, [':donor_id' => $donorId]);
        return $result ? $result[0] : null;
    }

    /**
     * Get case by ID
     */
    public function getDonationCaseById($caseId)
    {
        $query = "SELECT * FROM donation_cases WHERE id = :id";
        $result = $this->query($query, [':id' => $caseId]);
        return $result ? $result[0] : null;
    }

    /**
     * Update case status
     */
    public function updateCaseStatus($caseId, $legalStatus = null, $overallStatus = null)
    {
        $sets = [];
        $params = [':id' => $caseId];

        if ($legalStatus !== null) {
            $sets[] = "legal_status = :legal_status";
            $params[':legal_status'] = $legalStatus;
        }
        if ($overallStatus !== null) {
            $sets[] = "overall_status = :overall_status";
            $params[':overall_status'] = $overallStatus;
        }
        if (empty($sets)) return false;

        $query = "UPDATE donation_cases SET " . implode(', ', $sets) . " WHERE id = :id";
        return $this->query($query, $params);
    }

    // ─────────────────────────────────────────────────
    // LEGAL ACTIONS
    // ─────────────────────────────────────────────────

    /**
     * Submit a legal action (confirm or object)
     */
    public function submitLegalAction($data)
    {
        $query = "INSERT INTO custodian_legal_actions (
                    donation_case_id, custodian_id, action_type, method,
                    reason_category, reason_text, remarks,
                    signed_document_path, co_signed_document_path
                  ) VALUES (
                    :case_id, :custodian_id, :action_type, :method,
                    :reason_category, :reason_text, :remarks,
                    :signed_doc, :co_signed_doc
                  )";
        $result = $this->insert($query, [
            ':case_id' => $data['donation_case_id'],
            ':custodian_id' => $data['custodian_id'],
            ':action_type' => $data['action_type'],
            ':method' => $data['method'],
            ':reason_category' => $data['reason_category'] ?? null,
            ':reason_text' => $data['reason_text'] ?? null,
            ':remarks' => $data['remarks'] ?? null,
            ':signed_doc' => $data['signed_document_path'] ?? null,
            ':co_signed_doc' => $data['co_signed_document_path'] ?? null
        ]);

        // Update case legal_status
        if ($result) {
            $newStatus = ($data['action_type'] === 'CONFIRM') ? 'CONFIRMED' : 'OBJECTED';
            $this->updateCaseStatus($data['donation_case_id'], $newStatus, null);
        }

        return $result;
    }

    /**
     * Get legal action for a case
     */
    public function getLegalAction($caseId)
    {
        $query = "SELECT cla.*, c.name AS custodian_name
                  FROM custodian_legal_actions cla
                  JOIN custodians c ON cla.custodian_id = c.id
                  WHERE cla.donation_case_id = :case_id
                  ORDER BY cla.created_at DESC LIMIT 1";
        $result = $this->query($query, [':case_id' => $caseId]);
        return $result ? $result[0] : null;
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
        $attemptedQuery = "SELECT institution_id FROM case_institution_status
                           WHERE donation_case_id = :case_id AND track = :track";
        $attempted = $this->query($attemptedQuery, [':case_id' => $caseId, ':track' => $track]) ?: [];
        $attemptedIds = array_map(fn($r) => $r->institution_id, $attempted);

        if ($track === 'BODY' || $track === 'CORNEA') {
            // Get from body_donation_consents → medical_schools
            $query = "SELECT ms.id, ms.school_name, ms.university_affiliation, ms.address
                      FROM body_donation_consents bdc
                      JOIN medical_schools ms ON bdc.medical_school_id = ms.id
                      JOIN donation_cases dc ON dc.donor_id = bdc.donor_id
                      WHERE dc.id = :case_id";
            $institutions = $this->query($query, [':case_id' => $caseId]) ?: [];
        } else {
            // ORGAN: get from donor_pledges → hospitals (via organ_requests or direct mapping)
            // For now, get all active hospitals since donor_pledges don't link to specific hospitals
            $query = "SELECT h.id, h.name AS school_name, h.registration_no, h.address
                      FROM hospitals h
                      WHERE h.status = 'ACTIVE'";
            $institutions = $this->query($query) ?: [];
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
        // Check if there's already a current institution being reviewed
        $checkQuery = "SELECT id FROM case_institution_status
                       WHERE donation_case_id = :case_id
                       AND track = :track
                       AND is_current = 1
                       AND institution_status IN ('PENDING','UNDER_REVIEW')";
        $existing = $this->query($checkQuery, [':case_id' => $caseId, ':track' => $track]);

        if ($existing && count($existing) > 0) {
            return false; // Another institution is currently active
        }

        // Check if any institution was already accepted
        $acceptedQuery = "SELECT id FROM case_institution_status
                          WHERE donation_case_id = :case_id
                          AND track = :track
                          AND institution_status = 'ACCEPTED'";
        $accepted = $this->query($acceptedQuery, [':case_id' => $caseId, ':track' => $track]);

        if ($accepted && count($accepted) > 0) {
            return false; // Already accepted by an institution
        }

        // Get next attempt order
        $orderQuery = "SELECT COALESCE(MAX(attempt_order), 0) + 1 AS next_order
                       FROM case_institution_status
                       WHERE donation_case_id = :case_id AND track = :track";
        $orderResult = $this->query($orderQuery, [':case_id' => $caseId, ':track' => $track]);
        $nextOrder = $orderResult ? $orderResult[0]->next_order : 1;

        $query = "INSERT INTO case_institution_status (
                    donation_case_id, institution_type, institution_id, track,
                    attempt_order, is_current, custodian_action
                  ) VALUES (
                    :case_id, :inst_type, :inst_id, :track,
                    :attempt_order, 1, 'NOT_CONTACTED'
                  )";
        return $this->insert($query, [
            ':case_id' => $caseId,
            ':inst_type' => $institutionType,
            ':inst_id' => $institutionId,
            ':track' => $track,
            ':attempt_order' => $nextOrder
        ]);
    }

    /**
     * Get the currently active institution for a case + track
     */
    public function getCurrentInstitution($caseId, $track)
    {
        $query = "SELECT cis.*,
                    CASE
                      WHEN cis.institution_type = 'MEDICAL_SCHOOL' THEN ms.school_name
                      WHEN cis.institution_type = 'HOSPITAL' THEN h.name
                      ELSE 'Akshidhana Sangamaya'
                    END AS institution_name,
                    CASE
                      WHEN cis.institution_type = 'MEDICAL_SCHOOL' THEN ms.address
                      WHEN cis.institution_type = 'HOSPITAL' THEN h.address
                      ELSE NULL
                    END AS institution_address
                  FROM case_institution_status cis
                  LEFT JOIN medical_schools ms ON cis.institution_type = 'MEDICAL_SCHOOL' AND cis.institution_id = ms.id
                  LEFT JOIN hospitals h ON cis.institution_type = 'HOSPITAL' AND cis.institution_id = h.id
                  WHERE cis.donation_case_id = :case_id
                  AND cis.track = :track
                  AND cis.is_current = 1";
        $result = $this->query($query, [':case_id' => $caseId, ':track' => $track]);
        return $result ? $result[0] : null;
    }

    /**
     * Submit documents to the current institution (lock docs, mark as submitted)
     */
    public function submitToInstitution($caseInstitutionStatusId)
    {
        // Update institution status
        $query = "UPDATE case_institution_status SET
                    custodian_action = 'SUBMITTED',
                    institution_status = 'UNDER_REVIEW',
                    submission_date = NOW()
                  WHERE id = :id AND is_current = 1";
        $this->query($query, [':id' => $caseInstitutionStatusId]);

        // Lock all documents for this attempt
        $docQuery = "UPDATE custodian_documents SET
                       status = 'SUBMITTED'
                     WHERE case_institution_status_id = :status_id AND status = 'UPLOADED'";
        $this->query($docQuery, [':status_id' => $caseInstitutionStatusId]);

        return true;
    }

    /**
     * Handle institution response (accept or reject)
     * On reject: is_current → 0, unlock next selection
     * On accept: is_current stays 1, case is complete for this track
     */
    public function handleInstitutionResponse($statusId, $response, $message = null)
    {
        $query = "UPDATE case_institution_status SET
                    institution_status = :response,
                    rejection_message = :message,
                    response_date = NOW(),
                    is_current = :keep_current
                  WHERE id = :id";
        $this->query($query, [
            ':response' => $response,
            ':message' => $message,
            ':keep_current' => ($response === 'ACCEPTED') ? 1 : 0,
            ':id' => $statusId
        ]);

        // If accepted, update overall case status
        if ($response === 'ACCEPTED') {
            $caseQuery = "SELECT donation_case_id FROM case_institution_status WHERE id = :id";
            $result = $this->query($caseQuery, [':id' => $statusId]);
            if ($result) {
                $this->updateCaseStatus($result[0]->donation_case_id, null, 'COMPLETED');
            }
        }

        return true;
    }

    /**
     * Get all institution statuses for a case (history of attempts)
     */
    public function getInstitutionStatuses($caseId)
    {
        $query = "SELECT cis.*,
                    CASE
                      WHEN cis.institution_type = 'MEDICAL_SCHOOL' THEN ms.school_name
                      WHEN cis.institution_type = 'HOSPITAL' THEN h.name
                      ELSE 'Akshidhana Sangamaya'
                    END AS institution_name
                  FROM case_institution_status cis
                  LEFT JOIN medical_schools ms ON cis.institution_type = 'MEDICAL_SCHOOL' AND cis.institution_id = ms.id
                  LEFT JOIN hospitals h ON cis.institution_type = 'HOSPITAL' AND cis.institution_id = h.id
                  WHERE cis.donation_case_id = :case_id
                  ORDER BY cis.track, cis.attempt_order ASC";
        return $this->query($query, [':case_id' => $caseId]) ?: [];
    }

    // ─────────────────────────────────────────────────
    // DOCUMENT MANAGEMENT
    // ─────────────────────────────────────────────────

    /**
     * Upload a document
     */
    public function uploadDocument($data)
    {
        $query = "INSERT INTO custodian_documents (
                    donation_case_id, case_institution_status_id, document_type, file_path
                  ) VALUES (
                    :case_id, :status_id, :doc_type, :file_path
                  )";
        return $this->insert($query, [
            ':case_id' => $data['donation_case_id'],
            ':status_id' => $data['case_institution_status_id'],
            ':doc_type' => $data['document_type'],
            ':file_path' => $data['file_path']
        ]);
    }

    /**
     * Get documents for a specific institution attempt
     */
    public function getDocuments($caseInstitutionStatusId)
    {
        $query = "SELECT * FROM custodian_documents
                  WHERE case_institution_status_id = :status_id
                  ORDER BY uploaded_at ASC";
        return $this->query($query, [':status_id' => $caseInstitutionStatusId]) ?: [];
    }

    /**
     * Get all documents for a case
     */
    public function getAllDocuments($caseId)
    {
        $query = "SELECT cd.*, cis.institution_type, cis.institution_id, cis.track
                  FROM custodian_documents cd
                  JOIN case_institution_status cis ON cd.case_institution_status_id = cis.id
                  WHERE cd.donation_case_id = :case_id
                  ORDER BY cd.uploaded_at ASC";
        return $this->query($query, [':case_id' => $caseId]) ?: [];
    }

    // ─────────────────────────────────────────────────
    // CADAVER DATA SHEET
    // ─────────────────────────────────────────────────

    /**
     * Save or update cadaver data sheet
     */
    public function saveCadaverSheet($data)
    {
        // Check if one already exists for this institution status
        $existing = $this->getCadaverSheet($data['case_institution_status_id']);

        if ($existing) {
            $query = "UPDATE cadaver_data_sheets SET
                        form_data = :form_data,
                        status = :status
                      WHERE id = :id";
            return $this->query($query, [
                ':form_data' => json_encode($data['form_data']),
                ':status' => $data['status'] ?? 'DRAFT',
                ':id' => $existing->id
            ]);
        } else {
            $query = "INSERT INTO cadaver_data_sheets (
                        donation_case_id, case_institution_status_id, form_data, status
                      ) VALUES (
                        :case_id, :status_id, :form_data, :status
                      )";
            return $this->insert($query, [
                ':case_id' => $data['donation_case_id'],
                ':status_id' => $data['case_institution_status_id'],
                ':form_data' => json_encode($data['form_data']),
                ':status' => $data['status'] ?? 'DRAFT'
            ]);
        }
    }

    /**
     * Get cadaver data sheet for a specific institution attempt
     */
    public function getCadaverSheet($caseInstitutionStatusId)
    {
        $query = "SELECT * FROM cadaver_data_sheets
                  WHERE case_institution_status_id = :status_id";
        $result = $this->query($query, [':status_id' => $caseInstitutionStatusId]);
        if ($result && $result[0]) {
            $result[0]->form_data = json_decode($result[0]->form_data, true);
        }
        return $result ? $result[0] : null;
    }

    // ─────────────────────────────────────────────────
    // TIMELINE / AUDIT
    // ─────────────────────────────────────────────────

    /**
     * Get chronological timeline of all actions for a case
     */
    public function getTimeline($caseId)
    {
        $events = [];

        // Death declaration
        $ddQuery = "SELECT dd.created_at, c.name AS actor,
                           CONCAT('Death declared: ', dd.cause_of_death) AS description
                    FROM death_declarations dd
                    JOIN donation_cases dc ON dc.death_declaration_id = dd.id
                    JOIN custodians c ON dd.declared_by_custodian_id = c.id
                    WHERE dc.id = :case_id";
        $dd = $this->query($ddQuery, [':case_id' => $caseId]) ?: [];
        foreach ($dd as $e) {
            $events[] = ['time' => $e->created_at, 'actor' => $e->actor, 'event' => $e->description, 'type' => 'death'];
        }

        // Legal actions
        $laQuery = "SELECT cla.created_at, c.name AS actor,
                           CONCAT(cla.action_type, ' via ', cla.method) AS description
                    FROM custodian_legal_actions cla
                    JOIN custodians c ON cla.custodian_id = c.id
                    WHERE cla.donation_case_id = :case_id";
        $la = $this->query($laQuery, [':case_id' => $caseId]) ?: [];
        foreach ($la as $e) {
            $events[] = ['time' => $e->created_at, 'actor' => $e->actor, 'event' => $e->description, 'type' => 'legal'];
        }

        // Institution status changes
        $isQuery = "SELECT cis.created_at, cis.submission_date, cis.response_date,
                           cis.institution_status, cis.track, cis.attempt_order,
                           CASE
                             WHEN cis.institution_type = 'MEDICAL_SCHOOL' THEN ms.school_name
                             WHEN cis.institution_type = 'HOSPITAL' THEN h.name
                             ELSE 'Akshidhana Sangamaya'
                           END AS institution_name
                    FROM case_institution_status cis
                    LEFT JOIN medical_schools ms ON cis.institution_type = 'MEDICAL_SCHOOL' AND cis.institution_id = ms.id
                    LEFT JOIN hospitals h ON cis.institution_type = 'HOSPITAL' AND cis.institution_id = h.id
                    WHERE cis.donation_case_id = :case_id
                    ORDER BY cis.attempt_order";
        $is = $this->query($isQuery, [':case_id' => $caseId]) ?: [];
        foreach ($is as $e) {
            $events[] = [
                'time' => $e->created_at,
                'actor' => 'System',
                'event' => "Attempt #{$e->attempt_order}: {$e->institution_name} ({$e->track}) — {$e->institution_status}",
                'type' => 'institution'
            ];
            if ($e->submission_date) {
                $events[] = [
                    'time' => $e->submission_date,
                    'actor' => 'Custodian',
                    'event' => "Documents submitted to {$e->institution_name}",
                    'type' => 'submission'
                ];
            }
            if ($e->response_date) {
                $events[] = [
                    'time' => $e->response_date,
                    'actor' => $e->institution_name,
                    'event' => "Response: {$e->institution_status}",
                    'type' => 'response'
                ];
            }
        }

        // Document uploads
        $docQuery = "SELECT cd.uploaded_at AS created_at, cd.document_type
                     FROM custodian_documents cd
                     WHERE cd.donation_case_id = :case_id";
        $docs = $this->query($docQuery, [':case_id' => $caseId]) ?: [];
        foreach ($docs as $e) {
            $events[] = [
                'time' => $e->created_at,
                'actor' => 'Custodian',
                'event' => "Uploaded: {$e->document_type}",
                'type' => 'document'
            ];
        }

        // Sort by time
        usort($events, fn($a, $b) => strtotime($a['time']) - strtotime($b['time']));

        return $events;
    }
}
