<?php

namespace App\Models;

use App\Core\Model;

class  MedicalSchoolModel {
    use Model;

    protected $table = 'medical_schools';

    protected $allowedColumns = [
        'user_id',
        'school_name',
        'university_affiliation',
        'ugc_accreditation_number',
        'address',
        'district',
        'contact_person_name',
        'contact_person_email',
        'contact_person_phone',
        'verification_status'
    ];

    public function registerMedicalSchool($userId, $schoolData, $contactData)
    {
        $data = [
            'user_id' => $userId,
            'school_name' => $schoolData['name'],
            'university_affiliation' => $schoolData['university'],
            'ugc_accreditation_number' => $schoolData['ugc_number'],
            'address' => $schoolData['address'],
            'district' => $schoolData['district'] ?? null,
            'contact_person_name' => $contactData['name'],
            'contact_person_email' => $contactData['email'] ?? null,
            'contact_person_phone' => $contactData['phone'],
            'verification_status' => 'PENDING'
        ];
        
        return $this->insert($data);
    }

    public function ugcNumberExists($ugcNumber)
    {
        return $this->count(['ugc_accreditation_number' => $ugcNumber]) > 0;
    }

    public function getAllApprovedMedicalSchools()
    {
        return $this->where(['verification_status' => 'APPROVED'], [], ['id', 'school_name', 'district', 'address'], "school_name ASC") ?: [];
    }

    public function getSchoolByUserId($userId)
    {
        return $this->first(['user_id' => $userId]);
    }

    public function getAnatomicalInventory($schoolId)
    {
        return $this->queryJoin(
            [
                ['table' => 'donation_cases dc', 'on' => 'cis.donation_case_id = dc.id'],
                ['table' => 'donors d', 'on' => 'dc.donor_id = d.id']
            ],
            [
                'cis.institution_id' => $schoolId,
                'cis.institution_type' => 'MEDICAL_SCHOOL',
                'cis.final_exam_status' => 'ACCEPTED'
            ],
            "cis.id as cis_id, dc.case_number, d.id as donor_id, d.first_name, d.last_name, d.nic_number, cis.final_exam_at as acceptance_date, cis.current_condition, cis.assigned_department, (SELECT COUNT(*) FROM body_usage_logs WHERE donor_id = d.id AND medical_school_id = :school_id) as usage_count",
            "cis.final_exam_at DESC",
            100, 0, "case_institution_status cis", ['school_id' => $schoolId]
        ) ?: [];
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
        $totalConsents = $this->queryJoin(
            [['table' => 'donors d', 'on' => 'bdc.donor_id = d.id']],
            ['bdc.medical_school_id' => $schoolId, 'd.verification_status' => 'APPROVED'],
            "COUNT(*) as c", "", 1, 0, "body_donation_consents bdc"
        )[0]->c ?? 0;
        
        $activeConsents = $this->queryJoin(
            [['table' => 'donors d', 'on' => 'bdc.donor_id = d.id']],
            ['bdc.medical_school_id' => $schoolId, 'd.consent_status' => 'GIVEN', 'd.verification_status' => 'APPROVED'],
            "COUNT(*) as c", "", 1, 0, "body_donation_consents bdc"
        )[0]->c ?? 0;
        
        $pendingRequests = $this->count([
            'institution_id' => $schoolId, 
            'institution_type' => 'MEDICAL_SCHOOL', 
            'request_status' => 'PENDING', 
            'custodian_action' => 'SUBMITTED'
        ], [], "case_institution_status");

        $pendingDocs = $this->count([
            'institution_id' => $schoolId, 
            'institution_type' => 'MEDICAL_SCHOOL', 
            'document_status' => 'PENDING_REVIEW', 
            'request_status' => 'ACCEPTED'
        ], [], "case_institution_status");

        $pendingExams = $this->count([
            'institution_id' => $schoolId, 
            'institution_type' => 'MEDICAL_SCHOOL', 
            'final_exam_status' => 'AWAITING', 
            'document_status' => 'ACCEPTED'
        ], [], "case_institution_status");

        $invStats = $this->getInventoryStats($schoolId);

        return [
            'total_consents' => $totalConsents,
            'active_consents' => $activeConsents,
            'pending_requests' => $pendingRequests,
            'pending_docs' => $pendingDocs,
            'pending_exams' => $pendingExams,
            'active_submissions' => $pendingDocs,
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
        $lateRequests = $this->queryJoin(
            [
                ['table' => 'donation_cases dc', 'on' => 'cis.donation_case_id = dc.id'],
                ['table' => 'donors d', 'on' => 'dc.donor_id = d.id'],
                ['table' => 'death_declarations dd', 'on' => 'dc.death_declaration_id = dd.id']
            ],
            [
                'cis.institution_id' => $schoolId,
                'cis.institution_type' => 'MEDICAL_SCHOOL',
                "cis.request_status IN ('PENDING', 'UNDER_REVIEW')",
                "TIMESTAMPDIFF(HOUR, CONCAT(dd.date_of_death, ' ', dd.time_of_death), NOW()) > 24",
                'cis.track' => 'BODY'
            ],
            "dc.id, dc.case_number, d.first_name, d.last_name, dd.date_of_death, dd.time_of_death, cis.request_status, cis.id as cis_id",
            "", 5, 0, "case_institution_status cis"
        ) ?: [];

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

        $statusMap = ['ACTIVE' => 'GIVEN', 'WITHDRAWN' => 'WITHDRAWN'];
        $where = [
            'bdc.medical_school_id' => $schoolId,
            'd.verification_status' => 'APPROVED'
        ];

        if (isset($statusMap[$status])) {
            $where['d.consent_status'] = $statusMap[$status];
        } else {
            $where[] = "d.consent_status IN ('GIVEN', 'WITHDRAWN')";
        }

        return $this->queryJoin(
            [
                ['table' => 'donors d', 'on' => 'bdc.donor_id = d.id'],
                ['table' => 'donor_pledges dp', 'on' => 'd.id = dp.donor_id AND dp.organ_id = 10', 'type' => 'LEFT JOIN']
            ],
            $where,
            "d.id, d.first_name, d.last_name, d.nic_number, d.consent_status, d.verification_status, bdc.consent_date, bdc.withdrawal_reason, bdc.withdrawal_date, bdc.flag_reason, bdc.flagged_at, bdc.witness1_name, bdc.witness2_name, dp.signed_form_path",
            "bdc.consent_date DESC",
            50, 0, "body_donation_consents bdc"
        ) ?: [];
    }

    public function updateConsentStatus($donorId, $status, $flagData = [])
    {
        $updateData = ['consent_status' => $status];
        if ($status === 'FLAGGED' && !empty($flagData)) {
            $updateData['flag_reason_category'] = $flagData['flag_reason_category'] ?? null;
            $updateData['flag_reason'] = $flagData['flag_reason_text'] ?? null;
        }

        return $this->updateWhere($updateData, ['id' => $donorId], "donors");
    }

    public function getWithdrawnConsents($schoolId)
    {
        return $this->queryJoin(
            [['table' => 'donors d', 'on' => 'bdc.donor_id = d.id']],
            ['bdc.medical_school_id' => $schoolId, 'd.consent_status' => 'WITHDRAWN'],
            "d.id, d.first_name, d.last_name, d.nic_number, d.date_of_birth, d.gender, d.consent_status, bdc.consent_date, bdc.withdrawal_reason, bdc.withdrawal_date",
            "bdc.withdrawal_date DESC",
            50, 0, "body_donation_consents bdc"
        ) ?: [];
    }

    public function getDonorDetails($schoolId, $donorId, $context = 'CONSENT')
    {
        return $this->queryJoin(
            [
                ['table' => 'users u', 'on' => 'd.user_id = u.id', 'type' => 'LEFT JOIN'],
                ['table' => 'body_donation_consents bdc', 'on' => 'd.id = bdc.donor_id'],
                ['table' => 'donor_pledges dp', 'on' => 'd.id = dp.donor_id AND dp.organ_id = 10', 'type' => 'LEFT JOIN'],
                ['table' => 'custodians c', 'on' => 'd.id = c.donor_id AND (c.custodian_number = 1 OR c.custodian_number IS NULL)', 'type' => 'LEFT JOIN']
            ],
            ['d.id' => $donorId, 'bdc.medical_school_id' => $schoolId],
            "d.*, COALESCE(NULLIF(u.email, ''), NULLIF(c.email, '')) as email, COALESCE(NULLIF(u.phone, ''), NULLIF(c.phone, '')) as phone, bdc.consent_date, bdc.witness1_name, bdc.witness2_name, bdc.flag_reason, bdc.withdrawal_reason, bdc.withdrawal_date, dp.signed_form_path",
            "", 1, 0, "donors d"
        )[0] ?? false;
    }

    public function flagConsent($schoolId, $donorId, $reason, $userId)
    {
        $this->updateWhere(
            ['flag_reason' => $reason, 'flagged_at' => date('Y-m-d H:i:s'), 'flagged_by' => $userId],
            ['donor_id' => $donorId, 'medical_school_id' => $schoolId],
            "body_donation_consents"
        );
        $this->updateWhere(['consent_status' => 'FLAGGED'], ['id' => $donorId], "donors");
    }

    public function getSubmissionRequests($schoolId, $filter = 'PENDING')
    {
        $statusConditions = [
            'REJECTED' => "cis.request_status = 'REJECTED'",
            'ACCEPTED' => "cis.request_status = 'ACCEPTED'",
            'ALL' => "cis.request_status IN ('PENDING', 'UNDER_REVIEW', 'ACCEPTED', 'REJECTED')"
        ];
        $statusCondition = $statusConditions[$filter] ?? "cis.request_status IN ('PENDING', 'UNDER_REVIEW')";

        return $this->queryJoin(
            [
                ['table' => 'donation_cases dc', 'on' => 'cis.donation_case_id = dc.id'],
                ['table' => 'donors d', 'on' => 'dc.donor_id = d.id'],
                ['table' => 'death_declarations dd', 'on' => 'dc.death_declaration_id = dd.id']
            ],
            [
                'cis.institution_id' => $schoolId,
                'cis.institution_type' => 'MEDICAL_SCHOOL',
                'cis.track' => 'BODY',
                'cis.is_current' => 1,
                $statusCondition
            ],
            "dc.id as case_id, dc.case_number, dc.donor_id as donor_id, dc.resolved_operational_track, d.first_name, d.last_name, d.nic_number, dd.date_of_death, dd.time_of_death, cis.id as cis_id, cis.request_status, COALESCE(cis.submission_date, cis.created_at) as request_at",
            "COALESCE(cis.submission_date, cis.created_at) DESC",
            50, 0, "case_institution_status cis"
        ) ?: [];
    }

    public function getSubmissionRequestDetails($schoolId, $cisId)
    {
        return $this->queryJoin(
            [
                ['table' => 'donation_cases dc', 'on' => 'cis.donation_case_id = dc.id'],
                ['table' => 'donors d', 'on' => 'dc.donor_id = d.id'],
                ['table' => 'death_declarations dd', 'on' => 'dc.death_declaration_id = dd.id'],
                ['table' => 'custodians c', 'on' => 'd.id = c.donor_id AND (c.custodian_number = 1 OR c.custodian_number IS NULL)', 'type' => 'LEFT JOIN']
            ],
            ['cis.id' => $cisId, 'cis.institution_id' => $schoolId],
            "cis.*, cis.id as cis_id, dc.case_number, d.id as donor_id, d.first_name, d.last_name, d.date_of_birth, d.gender, d.nic_number, d.nationality, d.blood_group, dd.date_of_death, c.name as custodian_name, c.relationship as custodian_rel, c.phone as custodian_phone, c.email as custodian_email, c.nic_number as custodian_nic",
            "", 1, 0, "case_institution_status cis"
        )[0] ?? false;
    }

    public function getCustodiansForDonor($donorId)
    {
        return $this->queryJoin(
            [['table' => 'users u', 'on' => 'c.user_id = u.id', 'type' => 'LEFT JOIN']],
            ['c.donor_id' => $donorId],
            "c.id, c.name, c.relationship, c.custodian_number, c.nic_number, COALESCE(NULLIF(c.phone, ''), NULLIF(u.phone, '')) as phone, COALESCE(NULLIF(c.email, ''), NULLIF(u.email, '')) as email",
            "c.custodian_number ASC",
            10, 0, "custodians c"
        ) ?: [];
    }

    public function updateRequestStatus($schoolId, $cisId, $status, $reason, $userId)
    {
        $data = ['request_status' => $status, 'request_action_reason' => $reason, 'request_action_at' => date('Y-m-d H:i:s'), 'request_action_by' => $userId];
        if ($status === 'REJECTED') {
            $data['institution_status'] = 'REJECTED';
            $data['rejection_message'] = $reason;
        } elseif ($status === 'ACCEPTED') {
            $data['institution_status'] = 'ACCEPTED';
        }
        
        $this->updateWhere($data, ['id' => $cisId, 'institution_id' => $schoolId], "case_institution_status");

        if ($status === 'ACCEPTED') {
            $info = $this->first(['id' => $cisId], [], "donation_case_id, track", "", "case_institution_status");
            if ($info) {
                $this->updateWhere(
                    ['request_status' => 'INVALID', 'request_action_reason' => 'Accepted by another institution', 'request_action_at' => date('Y-m-d H:i:s')],
                    ['donation_case_id' => $info->donation_case_id, 'track' => $info->track, "id != $cisId", "request_status IN ('PENDING', 'UNDER_REVIEW')"],
                    "case_institution_status"
                );
                $this->updateWhere(['overall_status' => 'IN_PROGRESS'], ['id' => $info->donation_case_id], "donation_cases");
            }
        }
    }

    public function getCustodianDeclines($schoolId)
    {
        return $this->queryJoin(
            [
                ['table' => 'donation_cases dc', 'on' => 'cis.donation_case_id = dc.id'],
                ['table' => 'donors d', 'on' => 'dc.donor_id = d.id']
            ],
            ['cis.institution_id' => $schoolId, "cis.custodian_decline_date IS NOT NULL"],
            "dc.id as case_id, d.first_name, d.last_name, cis.id as cis_id, cis.custodian_decline_date",
            "cis.custodian_decline_date DESC",
            50, 0, "case_institution_status cis"
        ) ?: [];
    }

    public function getCustodianDeclineDetails($schoolId, $cisId)
    {
        return $this->getSubmissionRequestDetails($schoolId, $cisId);
    }

    public function getBodySubmissions($schoolId, $filter = 'ALL')
    {
        $statusConditions = [
            'PENDING' => "cis.document_status = 'PENDING_REVIEW'",
            'ACCEPTED' => "cis.document_status = 'ACCEPTED'",
            'REJECTED' => "cis.document_status IN ('REJECTED', 'NEED_MORE_DOCS')"
        ];
        $statusCondition = $statusConditions[$filter] ?? "cis.document_status != 'NOT_STARTED'";

        return $this->queryJoin(
            [
                ['table' => 'donation_cases dc', 'on' => 'cis.donation_case_id = dc.id'],
                ['table' => 'donors d', 'on' => 'dc.donor_id = d.id']
            ],
            [
                'cis.institution_id' => $schoolId,
                'cis.request_status' => 'ACCEPTED',
                $statusCondition
            ],
            "dc.id as case_id, dc.donor_id as donor_id, d.first_name, d.last_name, d.nic_number, cis.id as cis_id, cis.document_status, cis.document_action_at",
            "cis.document_action_at DESC",
            50, 0, "case_institution_status cis"
        ) ?: [];
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
        $data = [
            'document_status' => $status, 
            'rejection_reason_code' => $extra['reason_code'] ?? null,
            'rejection_reason_text' => $reason, 
            'missing_documents_json' => $extra['missing_json'] ?? null,
            'handover_date' => $extra['handover_date'] ?? null,
            'handover_time' => $extra['handover_time'] ?? null,
            'handover_message' => $extra['handover_msg'] ?? null,
            'document_action_at' => date('Y-m-d H:i:s'), 
            'document_action_by' => $userId
        ];
        
        $this->updateWhere($data, ['id' => $cisId, 'institution_id' => $schoolId], "case_institution_status");

        if ($status === 'REJECTED') {
            $this->updateWhere(['bundle_status' => 'PENDING'], ['id' => "(SELECT donation_case_id FROM case_institution_status WHERE id = $cisId)"], "donation_cases");
            // Wait, updateWhere doesn't support subqueries in keys well. Let's use simple logic.
            $cis = $this->first(['id' => $cisId], [], 'donation_case_id', '', 'case_institution_status');
            if ($cis) {
                $this->update($cis->donation_case_id, ['bundle_status' => 'PENDING'], 'id', 'donation_cases');
            }
        }
    }

    public function getFinalExaminations($schoolId, $status = 'ALL')
    {
        $where = ['cis.institution_id' => $schoolId, 'cis.document_status' => 'ACCEPTED'];
        if ($status !== 'ALL') {
            $where['cis.final_exam_status'] = $status;
        }

        return $this->queryJoin(
            [
                ['table' => 'donation_cases dc', 'on' => 'cis.donation_case_id = dc.id'],
                ['table' => 'donors d', 'on' => 'dc.donor_id = d.id']
            ],
            $where,
            "dc.id as case_id, d.first_name, d.last_name, d.nic_number, cis.id as cis_id, cis.final_exam_status, cis.final_exam_at, dc.case_number",
            "cis.final_exam_at DESC",
            50, 0, "case_institution_status cis"
        ) ?: [];
    }

    public function getFinalExaminationDetails($schoolId, $cisId)
    {
        return $this->getSubmissionRequestDetails($schoolId, $cisId);
    }

    public function updateFinalExamStatus($schoolId, $cisId, $status, $reason, $notes, $userId)
    {
        $this->updateWhere([
            'final_exam_status' => $status, 
            'final_exam_reason' => $reason, 
            'final_exam_notes' => $notes,
            'final_exam_at' => date('Y-m-d H:i:s'), 
            'final_exam_by' => $userId
        ], ['id' => $cisId, 'institution_id' => $schoolId], "case_institution_status");
        
        if($status === 'ACCEPTED') {
            $this->updateWhere(['institution_status' => 'ACCEPTED'], ['id' => $cisId], "case_institution_status");
            
            $caseRec = $this->first(['id' => $cisId], [], "donation_case_id", "", "case_institution_status");
            if ($caseRec) {
                $caseId = $caseRec->donation_case_id;
                $this->update($caseId, ['overall_status' => 'SUCCESSFUL'], 'id', 'donation_cases');
                
                $exists = $this->count(['donation_case_id' => $caseId], [], 'donation_certificates');
                if (!$exists) {
                    $certNum = "CERT-" . date('Y') . "-" . str_pad($caseId, 4, '0', STR_PAD_LEFT);
                    $this->insert([
                        'donation_case_id' => $caseId,
                        'case_institution_request_id' => $cisId,
                        'certificate_number' => $certNum,
                        'file_path' => 'pending',
                        'issued_by_name' => 'Faculty of Medicine'
                    ], 'donation_certificates');
                }
            }
        }
    }

    public function getStories($schoolId)
    {
        $storyModel = new \App\Models\SuccessStoryModel();
        return $storyModel->getStoriesByInstitution($schoolId, 'MEDICAL_SCHOOL');
    }

    public function getAppreciationLetters($schoolId)
    {
        return $this->queryJoin(
            [
                ['table' => 'body_usage_logs bul', 'on' => 'al.usage_log_id = bul.id'],
                ['table' => 'donors d', 'on' => 'bul.donor_id = d.id']
            ],
            ['bul.medical_school_id' => $schoolId],
            "al.*, bul.usage_type, d.first_name, d.last_name, d.id as donor_id",
            "al.issued_at DESC",
            50, 0, "appreciation_letters al"
        ) ?: [];
    }

    public function getDonationCertificates($schoolId)
    {
        return $this->queryJoin(
            [
                ['table' => 'case_institution_status cis', 'on' => 'dc.case_institution_request_id = cis.id'],
                ['table' => 'donation_cases d_case', 'on' => 'dc.donation_case_id = d_case.id'],
                ['table' => 'donors d', 'on' => 'd_case.donor_id = d.id']
            ],
            ['cis.institution_id' => $schoolId],
            "dc.*, d.first_name, d.last_name, cis.final_exam_at",
            "dc.issued_at DESC",
            50, 0, "donation_certificates dc"
        ) ?: [];
    }

    public function getAnatomicalCaseInfo($schoolId, $id)
    {
        return $this->queryJoin(
            [
                ['table' => 'donation_cases dc', 'on' => 'cis.donation_case_id = dc.id'],
                ['table' => 'donors d', 'on' => 'dc.donor_id = d.id']
            ],
            ['cis.id' => $id, 'cis.institution_id' => $schoolId],
            "cis.*, cis.id as cis_id, dc.case_number, dc.donor_id, d.first_name, d.last_name, d.nic_number, (SELECT COUNT(*) FROM body_usage_logs WHERE donor_id = dc.donor_id AND medical_school_id = :school_id) as usage_count",
            "", 1, 0, "case_institution_status cis", ['school_id' => $schoolId]
        )[0] ?? false;
    }

    public function getUsageLogs($schoolId, $cisId = null)
    {
        $where = ['bul.medical_school_id' => $schoolId];
        if ($cisId) $where['cis.id'] = $cisId;

        return $this->queryJoin(
            [
                ['table' => 'donors d', 'on' => 'bul.donor_id = d.id'],
                ['table' => 'case_institution_status cis', 'on' => '(bul.donor_id = d.id AND cis.institution_id = bul.medical_school_id)'],
                ['table' => 'donation_cases dc', 'on' => 'cis.donation_case_id = dc.id'],
                ['table' => 'appreciation_letters al', 'on' => 'al.usage_log_id = bul.id', 'type' => 'LEFT JOIN']
            ],
            $where,
            "bul.*, d.first_name, d.last_name, al.id as letter_id, al.issued_at as letter_issued_at, dc.case_number, cis.id as cis_id",
            "bul.usage_date DESC",
            50, 0, "body_usage_logs bul"
        ) ?: [];
    }

    public function recordUsage($data)
    {
        $keys = array_keys($data);
        $query = "insert into body_usage_logs (". implode(",",$keys).") values (:" . implode(",:", $keys) . ")";
        return $this->DatabaseInsert($query, $data);
    }

    public function issueAppreciationLetter($usageId, $schoolId, $issuerId)
    {
        // Prevent duplicate letters for the same usage log
        $exists = $this->first(['usage_log_id' => $usageId], [], "id", "", "appreciation_letters");
        if ($exists) return $exists->id;

        // Use a guaranteed unique reference format (APP-Year-UsageID)
        $ref = "APP-" . date('Y') . "-" . str_pad($usageId, 5, '0', STR_PAD_LEFT);
        
        return $this->insert([
            'usage_log_id' => $usageId,
            'ref_number'   => $ref,
            'issued_by_id' => $issuerId,
            'status'       => 'ISSUED',
            'issued_at'    => date('Y-m-d H:i:s')
        ], 'appreciation_letters');
    }

    public function getAppreciationLetter($id)
    {
        return $this->queryJoin(
            [
                ['table' => 'body_usage_logs bul', 'on' => 'al.usage_log_id = bul.id'],
                ['table' => 'donors d', 'on' => 'bul.donor_id = d.id'],
                ['table' => 'medical_schools ms', 'on' => 'bul.medical_school_id = ms.id'],
                ['table' => 'custodians cu', 'on' => 'd.id = cu.donor_id', 'type' => 'LEFT JOIN']
            ],
            ['al.id' => $id],
            "al.*, bul.usage_date, bul.usage_department, bul.usage_type as purpose, d.first_name, d.last_name, d.nic_number, ms.school_name, ms.address as school_address, cu.name as custodian_name, cu.address as custodian_address",
            "", 1, 0, "appreciation_letters al"
        )[0] ?? null;
    }

    public function getDonationCertificateById($id)
    {
        return $this->queryJoin(
            [
                ['table' => 'case_institution_status cis', 'on' => 'dc.case_institution_request_id = cis.id'],
                ['table' => 'donation_cases d_case', 'on' => 'dc.donation_case_id = d_case.id'],
                ['table' => 'donors d', 'on' => 'd_case.donor_id = d.id'],
                ['table' => 'medical_schools ms', 'on' => '(cis.institution_id = ms.id AND cis.institution_type = \'MEDICAL_SCHOOL\')', 'type' => 'LEFT JOIN'],
                ['table' => 'hospitals h', 'on' => '(cis.institution_id = h.id AND cis.institution_type = \'HOSPITAL\')', 'type' => 'LEFT JOIN']
            ],
            ['dc.id' => $id],
            "dc.*, d.first_name, d.last_name, cis.final_exam_at, CASE WHEN cis.institution_type = 'MEDICAL_SCHOOL' THEN ms.school_name WHEN cis.institution_type = 'HOSPITAL' THEN h.name ELSE 'Recognition Institute' END AS school_name, CASE WHEN cis.institution_type = 'MEDICAL_SCHOOL' THEN ms.address WHEN cis.institution_type = 'HOSPITAL' THEN h.address ELSE '' END AS school_address",
            "", 1, 0, "donation_certificates dc"
        )[0] ?? null;
    }

    public function getArchivedRecords($schoolId)
    {
        return $this->queryJoin(
            [
                ['table' => 'body_usage_logs bul', 'on' => 'd.id = bul.donor_id'],
                ['table' => 'appreciation_letters al', 'on' => 'bul.id = al.usage_log_id']
            ],
            ['bul.medical_school_id' => $schoolId],
            "d.id, d.first_name, d.last_name, d.nic_number, al.ref_number, al.issued_at",
            "al.issued_at DESC",
            50, 0, "donors d"
        ) ?: [];
    }

    public function getCertificateByCisId($cisId)
    {
        return $this->first(['case_institution_request_id' => $cisId], [], "id, certificate_number", "", "donation_certificates") ?: null;
    }

    public function resetDonorUsage($donorId, $schoolId)
    {
        $logs = $this->query("SELECT id FROM body_usage_logs WHERE donor_id = :d AND medical_school_id = :s", [':d' => $donorId, ':s' => $schoolId]);
        if (!$logs) return true;
        
        $logIds = array_map(fn($l) => $l->id, $logs);
        $idsStr = implode(',', $logIds);
        
        $this->query("DELETE FROM appreciation_letters WHERE usage_log_id IN ($idsStr)");
        $this->query("DELETE FROM body_usage_logs WHERE id IN ($idsStr)");
        
        return true;
    }
}
