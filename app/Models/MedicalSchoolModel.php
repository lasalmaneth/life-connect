<?php

namespace App\Models;

use App\Core\Database;

class MedicalSchoolModel {
    use Database;

    protected $table = 'medical_schools';

    public function registerMedicalSchool($userId, $schoolData, $contactData)
    {
        $query = "INSERT INTO medical_schools (
            user_id, school_name, university_affiliation, ugc_accreditation_number,
            address, district, contact_person_name, contact_person_email, contact_person_phone, 
            verification_status
        ) VALUES (
            :user_id, :name, :university, :ugc_number,
            :address, :district, :contact_name, :contact_email, :contact_phone, 
            'PENDING'
        )";
        
        $params = [
            ':user_id' => $userId,
            ':name' => $schoolData['name'],
            ':university' => $schoolData['university'],
            ':ugc_number' => $schoolData['ugc_number'],
            ':address' => $schoolData['address'],
            ':district' => $schoolData['district'] ?? null,
            ':contact_name' => $contactData['name'],
            ':contact_email' => $contactData['email'] ?? null,
            ':contact_phone' => $contactData['phone']
        ];
        
        return $this->insert($query, $params);
    }

    public function ugcNumberExists($ugcNumber)
    {
        $query = "SELECT COUNT(*) as count FROM medical_schools 
                  WHERE ugc_accreditation_number = :ugc_number";
        $result = $this->query($query, [':ugc_number' => $ugcNumber]);
        return $result && $result[0]->count > 0;
    }

    public function getAllApprovedMedicalSchools()
    {
        $query = "SELECT id, school_name, district, address 
                  FROM medical_schools 
                  WHERE verification_status = 'APPROVED'
                  ORDER BY school_name ASC";
        $result = $this->query($query);
        return $result ? $result : [];
    }

    public function getSchoolByUserId($userId)
    {
        $query = "SELECT * FROM medical_schools WHERE user_id = :user_id LIMIT 1";
        $result = $this->query($query, [':user_id' => $userId]);
        return ($result && count($result) > 0) ? $result[0] : false;
    }

    public function getAnatomicalInventory($schoolId)
    {
        $query = "SELECT 
                    cis.id as cis_id, 
                    dc.case_number, 
                    d.id as donor_id,
                    d.first_name, 
                    d.last_name, 
                    d.nic_number,
                    cis.final_exam_at as acceptance_date,
                    cis.current_condition,
                    cis.assigned_department,
                    (SELECT COUNT(*) FROM body_usage_logs WHERE donor_id = d.id AND medical_school_id = :school_id) as usage_count
                  FROM case_institution_status cis
                  JOIN donation_cases dc ON cis.donation_case_id = dc.id
                  JOIN donors d ON dc.donor_id = d.id
                  WHERE cis.institution_id = :school_id 
                  AND cis.institution_type = 'MEDICAL_SCHOOL'
                  AND cis.final_exam_status = 'ACCEPTED'
                  ORDER BY cis.final_exam_at DESC";
        return $this->query($query, [':school_id' => $schoolId]) ?: [];
    }

    public function getInventoryStats($schoolId)
    {
        $inventory = $this->getAnatomicalInventory($schoolId);
        $stats = [
            'total' => count($inventory),
            'pristine' => 0,
            'utilized' => 0
        ];

        foreach ($inventory as $item) {
            if ($item->usage_count > 0) {
                $stats['utilized']++;
            } else {
                $stats['pristine']++;
            }
        }
        return $stats;
    }

    public function getDashboardStats($schoolId)
    {
        // 1. Total Pre-Death Consents
        $totalConsents = $this->query("SELECT COUNT(*) as c FROM body_donation_consents WHERE medical_school_id = :s", [':s' => $schoolId])[0]->c ?? 0;
        
        // 2. Active Valid Consents
        $activeConsents = $this->query("SELECT COUNT(*) as c FROM body_donation_consents bdc JOIN donors d ON bdc.donor_id = d.id WHERE bdc.medical_school_id = :s AND d.consent_status = 'GIVEN'", [':s' => $schoolId])[0]->c ?? 0;
        
        // 3. Pending Submission Requests (Stage C)
        $pendingRequests = $this->query("SELECT COUNT(*) as c FROM case_institution_status WHERE institution_id = :s AND institution_type = 'MEDICAL_SCHOOL' AND request_status = 'PENDING' AND custodian_action = 'SUBMITTED'", [':s' => $schoolId])[0]->c ?? 0;

        // 4. Pending Document Review (Stage E/F)
        $pendingDocs = $this->query("SELECT COUNT(*) as c FROM case_institution_status WHERE institution_id = :s AND institution_type = 'MEDICAL_SCHOOL' AND document_status = 'PENDING_REVIEW' AND request_status = 'ACCEPTED'", [':s' => $schoolId])[0]->c ?? 0;

        // 5. Awaiting Final Examination (Stage G)
        $pendingExams = $this->query("SELECT COUNT(*) as c FROM case_institution_status WHERE institution_id = :s AND institution_type = 'MEDICAL_SCHOOL' AND final_exam_status = 'AWAITING' AND document_status = 'ACCEPTED'", [':s' => $schoolId])[0]->c ?? 0;

        // 6. Inventory Status
        $invStats = $this->getInventoryStats($schoolId);

        return [
            'total_consents' => $totalConsents,
            'active_consents' => $activeConsents,
            'pending_requests' => $pendingRequests,
            'pending_docs' => $pendingDocs,
            'pending_exams' => $pendingExams,
            'active_submissions' => $pendingDocs, // Compatibility for topbar
            'pristine_bodies' => $invStats['pristine'],
            'total_inventory' => $invStats['total']
        ];
    }

    /**
     * Fetch urgent actionable alerts for the dashboard
     */
    public function getUrgentAlerts($schoolId)
    {
        $alerts = [];

        // 1. Critical Deadline Check (48h Window)
        $query48h = "SELECT dc.id, dc.case_number, d.first_name, d.last_name, dd.date_of_death, dd.time_of_death, 
                           cis.request_status, cis.id as cis_id
                    FROM case_institution_status cis
                    JOIN donation_cases dc ON cis.donation_case_id = dc.id
                    JOIN donors d ON dc.donor_id = d.id
                    JOIN death_declarations dd ON dc.death_declaration_id = dd.id
                    WHERE cis.institution_id = :s AND cis.institution_type = 'MEDICAL_SCHOOL'
                    AND cis.request_status IN ('PENDING', 'UNDER_REVIEW')
                    AND TIMESTAMPDIFF(HOUR, CONCAT(dd.date_of_death, ' ', dd.time_of_death), NOW()) > 24
                    AND cis.track = 'BODY'
                    LIMIT 5";
        $lateRequests = $this->query($query48h, [':s' => $schoolId]) ?: [];
        foreach($lateRequests as $r) {
            $alerts[] = [
                'type' => 'DEADLINE',
                'priority' => 'HIGH',
                'title' => 'Critical Intake Window',
                'msg' => "Case #{$r->case_number} ({$r->first_name}) is approaching the 48h legal limit.",
                'link' => ROOT . "/medical-school/submission-requests?cis_id=" . $r->cis_id
            ];
        }

        // 2. Pending Physical Exams
        $exams = $this->getFinalExaminations($schoolId, 'AWAITING');
        if (!empty($exams)) {
            $top = $exams[0];
            $alerts[] = [
                'type' => 'EXAM',
                'priority' => 'MEDIUM',
                'title' => 'Awaiting Final Exam',
                'msg' => "Case #{$top->case_number} ({$top->first_name}) is physically accepted and awaiting exam.",
                'link' => ROOT . "/medical-school/final-examinations"
            ];
        }

        // 3. Document Reviews Pending
        $docs = $this->getBodySubmissions($schoolId, 'PENDING');
        if (!empty($docs)) {
            $top = $docs[0];
            $alerts[] = [
                'type' => 'DOCS',
                'priority' => 'LOW',
                'title' => 'Document Verification',
                'msg' => "Bundle for #{$top->first_name} is ready for verification and review.",
                'link' => ROOT . "/medical-school/submissions"
            ];
        }

        return $alerts;
    }

    public function getPreDeathConsents($schoolId, $status = 'ALL')
    {
        $where = "";
        $params = [':school_id' => $schoolId];

        if ($status === 'GIVEN') {
            $where .= " AND d.consent_status = 'GIVEN'";
        } elseif ($status === 'PENDING') {
            $where .= " AND d.consent_status = 'PENDING'";
        } elseif ($status === 'WITHDRAWN') {
            $where .= " AND d.consent_status = 'WITHDRAWN'";
        } elseif ($status === 'FLAGGED') {
            $where .= " AND d.consent_status = 'FLAGGED'";
        } else {
            // ALL / Default
            $where .= " AND d.consent_status IN ('PENDING', 'GIVEN', 'WITHDRAWN', 'FLAGGED')";
        }

        $query = "SELECT 
                    d.id, 
                    d.first_name, 
                    d.last_name, 
                    d.nic_number, 
                    d.consent_status,
                    d.verification_status,
                    bdc.consent_date,
                    bdc.withdrawal_reason,
                    bdc.withdrawal_date,
                    bdc.flag_reason,
                    bdc.flagged_at,
                    bdc.witness1_name,
                    bdc.witness2_name,
                    dp.signed_form_path
                  FROM body_donation_consents bdc
                  JOIN donors d ON bdc.donor_id = d.id
                  LEFT JOIN donor_pledges dp ON d.id = dp.donor_id AND dp.organ_id = 9
                  WHERE bdc.medical_school_id = :school_id $where
                  ORDER BY bdc.consent_date DESC";
        
        $result = $this->query($query, array_merge([':school_id' => $schoolId], $params));
        return $result ?: [];
    }

    public function updateConsentStatus($donorId, $status, $flagData = [])
    {
        // Add new columns dynamically if needed or just use consent_status
        $query = "UPDATE donors SET consent_status = :status";
        $params = [
            ':status' => $status,
            ':donor_id' => $donorId
        ];
        
        if ($status === 'FLAGGED' && !empty($flagData)) {
            // Check if column exists, if not we will just skip to avoid errors initially since pure PHP was requested without framework migrations
            // But ideally we'd update flag_reason_category and flag_reason_text in the donors table if they exist.
            // As a fallback for this pure html/css/js task, we just set the status.
            // (If the db schema is rigidly set, we assume they can just alter it later, we'll try to update).
            // A safer approach: update what we can. 
            // In a raw setup without migrations, I'll attempt the update, if it fails, I'll catch and do a simpler update.
            try {
                $query = "UPDATE donors SET consent_status = :status, flag_reason_category = :cat, flag_reason = :txt WHERE id = :donor_id";
                $params[':cat'] = $flagData['flag_reason_category'];
                $params[':txt'] = $flagData['flag_reason_text'];
                $this->query($query, $params);
                return true;
            } catch (\Exception $e) {
                // simple fallback if columns missing
                $query = "UPDATE donors SET consent_status = :status WHERE id = :donor_id";
                return $this->query($query, [':status' => $status, ':donor_id' => $donorId]);
            }
        }
        
        $query .= " WHERE id = :donor_id";
        return $this->query($query, $params);
    }

    public function getWithdrawnConsents($schoolId)
    {
        $query = "SELECT 
                    d.id,
                    d.first_name,
                    d.last_name,
                    d.nic_number,
                    d.date_of_birth,
                    d.gender,
                    d.consent_status,
                    bdc.consent_date,
                    bdc.withdrawal_reason,
                    bdc.withdrawal_date
                  FROM body_donation_consents bdc
                  JOIN donors d ON bdc.donor_id = d.id
                  WHERE bdc.medical_school_id = :school_id
                  AND d.consent_status = 'WITHDRAWN'
                  ORDER BY bdc.withdrawal_date DESC";
        
        $result = $this->query($query, [':school_id' => $schoolId]);
        return $result ? $result : [];
    }

    public function getDonorDetails($schoolId, $donorId, $context = 'CONSENT')
    {
        $query = "SELECT d.*, 
                         COALESCE(NULLIF(u.email, ''), NULLIF(c.email, '')) as email, 
                         COALESCE(NULLIF(u.phone, ''), NULLIF(c.phone, '')) as phone, 
                         bdc.consent_date, bdc.witness1_name, bdc.witness2_name, bdc.flag_reason, bdc.withdrawal_reason, bdc.withdrawal_date,
                         dp.signed_form_path
                  FROM donors d
                  LEFT JOIN users u ON d.user_id = u.id
                  JOIN body_donation_consents bdc ON d.id = bdc.donor_id
                  LEFT JOIN donor_pledges dp ON d.id = dp.donor_id AND dp.organ_id = 9
                  LEFT JOIN custodians c ON d.id = c.donor_id AND (c.custodian_number = 1 OR c.custodian_number IS NULL)
                  WHERE d.id = :donor_id AND bdc.medical_school_id = :school_id LIMIT 1";
        $result = $this->query($query, [':donor_id' => $donorId, ':school_id' => $schoolId]);
        return ($result && count($result) > 0) ? $result[0] : false;
    }

    public function flagConsent($schoolId, $donorId, $reason, $userId)
    {
        $query = "UPDATE body_donation_consents 
                  SET flag_reason = :reason, flagged_at = NOW(), flagged_by = :user_id 
                  WHERE donor_id = :donor_id AND medical_school_id = :school_id";
        $this->query($query, [':reason' => $reason, ':user_id' => $userId, ':donor_id' => $donorId, ':school_id' => $schoolId]);
        $this->query("UPDATE donors SET consent_status = 'FLAGGED' WHERE id = :donor_id", [':donor_id' => $donorId]);
    }

    public function getSubmissionRequests($schoolId, $filter = 'PENDING')
    {
        $statusCondition = "cis.request_status IN ('PENDING', 'UNDER_REVIEW')";
        if ($filter === 'REJECTED') {
            $statusCondition = "cis.request_status = 'REJECTED'";
        } elseif ($filter === 'ACCEPTED') {
            $statusCondition = "cis.request_status = 'ACCEPTED'";
        } elseif ($filter === 'ALL') {
            $statusCondition = "cis.request_status IN ('PENDING', 'UNDER_REVIEW', 'ACCEPTED', 'REJECTED')";
        }
        
        $query = "SELECT 
                    dc.id as case_id, 
                    dc.case_number,
                    d.first_name, 
                    d.last_name, 
                    d.nic_number, 
                    dd.date_of_death,
                    cis.id as cis_id, 
                    cis.request_status, 
                    COALESCE(cis.submission_date, cis.created_at) as request_at
                  FROM case_institution_status cis
                  JOIN donation_cases dc ON cis.donation_case_id = dc.id
                  JOIN donors d ON dc.donor_id = d.id
                  JOIN death_declarations dd ON dc.death_declaration_id = dd.id
                  WHERE cis.institution_id = :school_id 
                  AND cis.institution_type = 'MEDICAL_SCHOOL' 
                  AND cis.track = 'BODY'
                  AND {$statusCondition}
                  ORDER BY COALESCE(cis.submission_date, cis.created_at) DESC";
        $result = $this->query($query, [':school_id' => $schoolId]);
        return $result ? $result : [];
    }

    public function getSubmissionRequestDetails($schoolId, $cisId)
    {
        $query = "SELECT cis.*, cis.id as cis_id, dc.case_number, d.id as donor_id,
                         d.first_name, d.last_name, d.date_of_birth, d.gender, d.nic_number, d.nationality, d.blood_group,
                         dd.date_of_death,
                         c.name as custodian_name, c.relationship as custodian_rel, c.phone as custodian_phone, 
                         c.email as custodian_email, c.nic_number as custodian_nic
                  FROM case_institution_status cis
                  JOIN donation_cases dc ON cis.donation_case_id = dc.id
                  JOIN donors d ON dc.donor_id = d.id
                  JOIN death_declarations dd ON dc.death_declaration_id = dd.id
                  LEFT JOIN custodians c ON d.id = c.donor_id AND (c.custodian_number = 1 OR c.custodian_number IS NULL)
                  WHERE cis.id = :cis_id AND cis.institution_id = :school_id LIMIT 1";
        $result = $this->query($query, [':cis_id' => $cisId, ':school_id' => $schoolId]);
        return ($result && count($result) > 0) ? $result[0] : false;
    }

    public function getCustodiansForDonor($donorId)
    {
        $query = "SELECT c.id, c.name, c.relationship, c.custodian_number, c.nic_number,
                         COALESCE(NULLIF(c.phone, ''), NULLIF(u.phone, '')) as phone, 
                         COALESCE(NULLIF(c.email, ''), NULLIF(u.email, '')) as email
                  FROM custodians c 
                  LEFT JOIN users u ON c.user_id = u.id
                  WHERE c.donor_id = :donor_id 
                  ORDER BY c.custodian_number ASC";
        return $this->query($query, [':donor_id' => $donorId]) ?: [];
    }

    public function updateRequestStatus($schoolId, $cisId, $status, $reason, $userId)
    {
        // 1. Update the targeted request
        $query = "UPDATE case_institution_status 
                  SET request_status = :status, request_action_reason = :reason, 
                      request_action_at = NOW(), request_action_by = :user_id";
        
        $params = [
            ':status' => $status, 
            ':reason' => $reason, 
            ':user_id' => $userId, 
            ':cis_id' => $cisId, 
            ':school_id' => $schoolId
        ];

        if ($status === 'REJECTED') {
            $query .= ", institution_status = 'REJECTED', rejection_message = :rejection_message";
            $params[':rejection_message'] = $reason;
        } elseif ($status === 'ACCEPTED') {
            $query .= ", institution_status = 'ACCEPTED'";
        }
        
        $query .= " WHERE id = :cis_id AND institution_id = :school_id";
        
        $this->query($query, $params);

        // 2. If accepted, invalidate all other pending requests for the same case and track
        if ($status === 'ACCEPTED') {
            // Get case_id and track for the accepted request
            $infoQuery = "SELECT donation_case_id, track FROM case_institution_status WHERE id = :id";
            $info = $this->query($infoQuery, [':id' => $cisId]);
            
            if ($info && count($info) > 0) {
                $caseId = $info[0]->donation_case_id;
                $track = $info[0]->track;
                
                $invalidateQuery = "UPDATE case_institution_status 
                                  SET request_status = 'INVALID', 
                                      request_action_reason = 'Accepted by another institution',
                                      request_action_at = NOW()
                                  WHERE donation_case_id = :case_id 
                                  AND track = :track 
                                  AND id != :accepted_id
                                  AND request_status IN ('PENDING', 'UNDER_REVIEW')";
                $this->query($invalidateQuery, [
                    ':case_id' => $caseId, 
                    ':track' => $track, 
                    ':accepted_id' => $cisId
                ]);

                // Also update the overall case status to reflect submission progress
                $this->query("UPDATE donation_cases SET overall_status = 'IN_PROGRESS' WHERE id = :id", [':id' => $caseId]);
            }
        }
    }

    public function getCustodianDeclines($schoolId)
    {
        $query = "SELECT dc.id as case_id, d.first_name, d.last_name, cis.id as cis_id, cis.custodian_decline_date
                  FROM case_institution_status cis
                  JOIN donation_cases dc ON cis.donation_case_id = dc.id
                  JOIN donors d ON dc.donor_id = d.id
                  WHERE cis.institution_id = :school_id AND cis.custodian_decline_date IS NOT NULL
                  ORDER BY cis.custodian_decline_date DESC";
        $result = $this->query($query, [':school_id' => $schoolId]);
        return $result ? $result : [];
    }

    public function getCustodianDeclineDetails($schoolId, $cisId)
    {
        return $this->getSubmissionRequestDetails($schoolId, $cisId);
    }

    public function getBodySubmissions($schoolId, $filter = 'ALL')
    {
        $statusCondition = "cis.document_status != 'NOT_STARTED'";
        if ($filter === 'PENDING') {
            $statusCondition = "cis.document_status = 'PENDING_REVIEW'";
        } elseif ($filter === 'ACCEPTED') {
            $statusCondition = "cis.document_status = 'ACCEPTED'";
        } elseif ($filter === 'REJECTED') {
            $statusCondition = "cis.document_status IN ('REJECTED', 'NEED_MORE_DOCS')";
        }

        $query = "SELECT dc.id as case_id, d.first_name, d.last_name, d.nic_number, cis.id as cis_id, cis.document_status, cis.document_action_at
                  FROM case_institution_status cis
                  JOIN donation_cases dc ON cis.donation_case_id = dc.id
                  JOIN donors d ON dc.donor_id = d.id
                  WHERE cis.institution_id = :school_id 
                  AND cis.request_status = 'ACCEPTED' 
                  AND {$statusCondition}
                  ORDER BY cis.document_action_at DESC";
        $result = $this->query($query, [':school_id' => $schoolId]);
        return $result ? $result : [];
    }

    public function getSubmissionDetails($schoolId, $cisId)
    {
        return $this->getSubmissionRequestDetails($schoolId, $cisId);
    }

    public function getSubmissionDocuments($cisId)
    {
        $query = "SELECT * FROM custodian_documents WHERE case_institution_status_id = :cis_id ORDER BY uploaded_at DESC";
        $result = $this->query($query, [':cis_id' => $cisId]);
        return $result ? $result : [];
    }

    public function updateDocumentStatus($schoolId, $cisId, $status, $reason, $userId, $extra = [])
    {
        $query = "UPDATE case_institution_status 
                  SET document_status = :status, 
                      rejection_reason_code = :reason_code,
                      rejection_reason_text = :reason_text,
                      missing_documents_json = :missing_json,
                      handover_date = :handover_date,
                      handover_time = :handover_time,
                      handover_message = :handover_msg,
                      document_action_at = NOW(), 
                      document_action_by = :user_id
                  WHERE id = :cis_id AND institution_id = :school_id";
        
        $this->query($query, [
            ':status' => $status, 
            ':reason_code' => $extra['reason_code'] ?? null,
            ':reason_text' => $reason, 
            ':missing_json' => $extra['missing_json'] ?? null,
            ':handover_date' => $extra['handover_date'] ?? null,
            ':handover_time' => $extra['handover_time'] ?? null,
            ':handover_msg' => $extra['handover_msg'] ?? null,
            ':user_id' => $userId, 
            ':cis_id' => $cisId, 
            ':school_id' => $schoolId
        ]);

        if ($status === 'REJECTED') {
            $this->query("UPDATE donation_cases dc 
                          JOIN case_institution_status cis ON dc.id = cis.donation_case_id 
                          SET dc.bundle_status = 'PENDING' 
                          WHERE cis.id = :cis_id", [':cis_id' => $cisId]);
        }
    }

    public function getFinalExaminations($schoolId, $status = 'ALL')
    {
        $where = "WHERE cis.institution_id = :school_id AND cis.document_status = 'ACCEPTED'";
        $params = [':school_id' => $schoolId];

        if ($status !== 'ALL') {
            $where .= " AND cis.final_exam_status = :status";
            $params[':status'] = $status;
        }

        $query = "SELECT dc.id as case_id, d.first_name, d.last_name, cis.id as cis_id, cis.final_exam_status, cis.final_exam_at, dc.case_number
                  FROM case_institution_status cis
                  JOIN donation_cases dc ON cis.donation_case_id = dc.id
                  JOIN donors d ON dc.donor_id = d.id
                  $where
                  ORDER BY cis.final_exam_at DESC";
        $result = $this->query($query, $params);
        return $result ? $result : [];
    }

    public function getFinalExaminationDetails($schoolId, $cisId)
    {
        return $this->getSubmissionRequestDetails($schoolId, $cisId);
    }

    public function updateFinalExamStatus($schoolId, $cisId, $status, $reason, $notes, $userId)
    {
        $query = "UPDATE case_institution_status 
                  SET final_exam_status = :status, final_exam_reason = :reason, final_exam_notes = :notes,
                      final_exam_at = NOW(), final_exam_by = :user_id
                  WHERE id = :cis_id AND institution_id = :school_id";
        $this->query($query, [
            ':status' => $status, ':reason' => $reason, ':notes' => $notes, 
            ':user_id' => $userId, ':cis_id' => $cisId, ':school_id' => $schoolId
        ]);
        
        if($status === 'ACCEPTED') {
            // Formally accept the institution status
            $this->query("UPDATE case_institution_status SET institution_status = 'ACCEPTED' WHERE id = :cis_id", [':cis_id' => $cisId]);
            
            // AUTOMATED CERTIFICATE ISSUANCE
            // 1. Get Case Details
            $caseRec = $this->query("SELECT donation_case_id FROM case_institution_status WHERE id = :id", [':id' => $cisId])[0] ?? null;
            if ($caseRec) {
                $caseId = $caseRec->donation_case_id;
                
                // --- SYNC STATUS TO SUCCESSFUL ---
                $this->query("UPDATE donation_cases SET overall_status = 'SUCCESSFUL' WHERE id = :case_id", [':case_id' => $caseId]);

                // 2. Check if certificate already exists to avoid duplicates
                $exists = $this->query("SELECT id FROM donation_certificates WHERE donation_case_id = :id", [':id' => $caseId]);
                
                if (!$exists) {
                    $certNum = "CERT-" . date('Y') . "-" . str_pad($caseId, 4, '0', STR_PAD_LEFT);
                    $this->query("INSERT INTO donation_certificates (donation_case_id, case_institution_request_id, certificate_number, file_path, issued_by_name)
                                  VALUES (:case_id, :cis_id, :cert_num, :path, :issuer)", [
                                    ':case_id' => $caseId,
                                    ':cis_id' => $cisId,
                                    ':cert_num' => $certNum,
                                    ':path' => 'pending', // Will be rendered via view dynamically
                                    ':issuer' => 'Faculty of Medicine'
                                  ]);
                }
            }
        }
    }

    public function getStories($schoolId)
    {
        $storyModel = new SuccessStoryModel();
        return $storyModel->getStoriesByInstitution($schoolId, 'MEDICAL_SCHOOL');
    }

    public function getAppreciationLetters($schoolId)
    {
        $query = "SELECT al.*, bul.usage_type, d.first_name, d.last_name, d.id as donor_id
                  FROM appreciation_letters al
                  JOIN body_usage_logs bul ON al.usage_log_id = bul.id
                  JOIN donors d ON bul.donor_id = d.id
                  WHERE bul.medical_school_id = :school_id
                  ORDER BY al.issued_at DESC";
        return $this->query($query, [':school_id' => $schoolId]) ?: [];
    }

    public function getDonationCertificates($schoolId)
    {
        $query = "SELECT dc.*, d.first_name, d.last_name, cis.final_exam_at
                  FROM donation_certificates dc
                  JOIN case_institution_status cis ON dc.case_institution_request_id = cis.id
                  JOIN donation_cases d_case ON dc.donation_case_id = d_case.id
                  JOIN donors d ON d_case.donor_id = d.id
                  WHERE cis.institution_id = :school_id
                  ORDER BY dc.issued_at DESC";
        return $this->query($query, [':school_id' => $schoolId]) ?: [];
    }

    public function getAnatomicalCaseInfo($schoolId, $id)
    {
        $query = "SELECT cis.*, cis.id as cis_id, dc.case_number, dc.donor_id, d.first_name, d.last_name, d.nic_number,
                         (SELECT COUNT(*) FROM body_usage_logs WHERE donor_id = dc.donor_id AND medical_school_id = :school_id) as usage_count
                  FROM case_institution_status cis
                  JOIN donation_cases dc ON cis.donation_case_id = dc.id
                  JOIN donors d ON dc.donor_id = d.id
                  WHERE cis.id = :id AND cis.institution_id = :school_id LIMIT 1";
        $result = $this->query($query, [':id' => $id, ':school_id' => $schoolId]);
        return ($result && count($result) > 0) ? $result[0] : false;
    }

    public function getUsageLogs($schoolId, $cisId = null)
    {
        $where = "WHERE bul.medical_school_id = :school_id";
        $params = [':school_id' => $schoolId];

        if ($cisId) {
            $where .= " AND cis.id = :cis_id";
            $params[':cis_id'] = $cisId;
        }

        $query = "SELECT bul.*, d.first_name, d.last_name, al.id as letter_id, al.issued_at as letter_issued_at, 
                         dc.case_number, cis.id as cis_id
                  FROM body_usage_logs bul
                  JOIN donors d ON bul.donor_id = d.id
                  JOIN case_institution_status cis ON (bul.donor_id = d.id AND cis.institution_id = bul.medical_school_id)
                  JOIN donation_cases dc ON cis.donation_case_id = dc.id
                  LEFT JOIN appreciation_letters al ON al.usage_log_id = bul.id
                  {$where}
                  GROUP BY bul.id
                  ORDER BY bul.usage_date DESC";
        return $this->query($query, $params) ?: [];
    }

    public function recordUsage($data)
    {
        $query = "INSERT INTO body_usage_logs (
            donor_id, medical_school_id, usage_type, description, 
            usage_date, usage_department, subject_area, handled_by, duration, other_notes
        ) VALUES (
            :donor_id, :school_id, :usage_type, :description, 
            :usage_date, :usage_department, :subject_area, :handled_by, :duration, :other_notes
        )";
        return $this->query($query, $data);
    }

    public function issueAppreciationLetter($usageId, $schoolId, $issuerId)
    {
        // 1. Verify log belongs to school
        $check = $this->query("SELECT id FROM body_usage_logs WHERE id = :id AND medical_school_id = :s", [':id' => $usageId, ':s' => $schoolId]);
        if (!$check) return false;

        // 2. Check if already issued
        $exists = $this->query("SELECT id FROM appreciation_letters WHERE usage_log_id = :id", [':id' => $usageId]);
        if ($exists) return $exists[0]->id;

        // 3. Generate Ref: BD-YYYY-XXX
        $countRes = $this->query("SELECT COUNT(*) as c FROM appreciation_letters");
        $count = $countRes[0]->c ?? 0;
        $ref = "BD-" . date('Y') . "-" . str_pad($count + 1, 3, '0', STR_PAD_LEFT);
        
        $query = "INSERT INTO appreciation_letters (usage_log_id, ref_number, issued_by_id, status)
                  VALUES (:usage_id, :ref, :issuer_id, 'ISSUED')";
        
        $this->query($query, [
            ':usage_id' => $usageId,
            ':ref' => $ref,
            ':issuer_id' => $issuerId
        ]);
        
        return true;
    }

    public function getAppreciationLetter($id)
    {
        $query = "SELECT al.*, bul.usage_date, bul.usage_department, bul.usage_type as purpose,
                         d.first_name, d.last_name, d.nic_number,
                         ms.school_name, ms.address as school_address,
                         cu.name as custodian_name, cu.address as custodian_address
                  FROM appreciation_letters al
                  JOIN body_usage_logs bul ON al.usage_log_id = bul.id
                  JOIN donors d ON bul.donor_id = d.id
                  JOIN medical_schools ms ON bul.medical_school_id = ms.id
                  LEFT JOIN custodians cu ON d.id = cu.donor_id
                  WHERE al.id = :id";
        $res = $this->query($query, [':id' => $id]);
        return $res ? $res[0] : null;
    }

    public function getDonationCertificateById($id)
    {
        $query = "SELECT dc.*, d.first_name, d.last_name, cis.final_exam_at, 
                         CASE 
                            WHEN cis.institution_type = 'MEDICAL_SCHOOL' THEN ms.school_name 
                            WHEN cis.institution_type = 'HOSPITAL' THEN h.name 
                            ELSE 'Recognition Institute'
                         END AS school_name,
                         CASE 
                            WHEN cis.institution_type = 'MEDICAL_SCHOOL' THEN ms.address
                            WHEN cis.institution_type = 'HOSPITAL' THEN h.address 
                            ELSE ''
                         END AS school_address
                  FROM donation_certificates dc
                  JOIN case_institution_status cis ON dc.case_institution_request_id = cis.id
                  JOIN donation_cases d_case ON dc.donation_case_id = d_case.id
                  JOIN donors d ON d_case.donor_id = d.id
                  LEFT JOIN medical_schools ms ON (cis.institution_id = ms.id AND cis.institution_type = 'MEDICAL_SCHOOL')
                  LEFT JOIN hospitals h ON (cis.institution_id = h.id AND cis.institution_type = 'HOSPITAL')
                  WHERE dc.id = :id";
        $res = $this->query($query, [':id' => $id]);
        return $res ? $res[0] : null;
    }

    public function getArchivedRecords($schoolId)
    {
        // For now, archived records are utilized ones that have an appreciation letter
        $query = "SELECT d.id, d.first_name, d.last_name, d.nic_number, al.ref_number, al.issued_at
                  FROM donors d
                  JOIN body_usage_logs bul ON d.id = bul.donor_id
                  JOIN appreciation_letters al ON bul.id = al.usage_log_id
                  WHERE bul.medical_school_id = :school_id
                  ORDER BY al.issued_at DESC";
        return $this->query($query, [':school_id' => $schoolId]) ?: [];
    }

    public function getCertificateByCisId($cisId)
    {
        $query = "SELECT id, certificate_number 
                  FROM donation_certificates 
                  WHERE case_institution_request_id = :cis_id LIMIT 1";
        $result = $this->query($query, [':cis_id' => $cisId]);
        return $result ? $result[0] : null;
    }

    public function resetDonorUsage($donorId, $schoolId)
    {
        // 1. Get usage log IDs
        $logs = $this->query("SELECT id FROM body_usage_logs WHERE donor_id = :d AND medical_school_id = :s", [':d' => $donorId, ':s' => $schoolId]);
        if (!$logs) return true;
        
        $logIds = array_map(fn($l) => $l->id, $logs);
        $idsStr = implode(',', $logIds);
        
        // 2. Delete letters
        $this->query("DELETE FROM appreciation_letters WHERE usage_log_id IN ($idsStr)");
        
        // 3. Delete logs
        $this->query("DELETE FROM body_usage_logs WHERE id IN ($idsStr)");
        
        return true;
    }
}
