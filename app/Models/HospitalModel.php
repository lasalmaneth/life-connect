<?php

namespace App\Models;

use App\Core\Model;

class HospitalModel {
    use Model;

    protected $table = 'hospitals';

    protected $allowedColumns = [
        'user_id',
        'registration_number',
        'transplant_id',
        'name',
        'address',
        'contact_number',
        'district',
        'facility_type',
        'cmo_name',
        'cmo_nic',
        'medical_license_number',
        'verification_status'
    ];

    public function registerHospital($userId, $hospitalData, $cmoData)
    {
        return $this->insert([
            'user_id' => $userId,
            'registration_number' => $hospitalData['registration_number'],
            'transplant_id' => $hospitalData['transplant_id'] ?? null,
            'name' => $hospitalData['name'],
            'address' => $hospitalData['address'],
            'contact_number' => $hospitalData['contact_number'] ?? null,
            'district' => $hospitalData['district'],
            'facility_type' => $hospitalData['type'],
            'cmo_name' => $cmoData['name'],
            'cmo_nic' => $cmoData['nic'],
            'medical_license_number' => $cmoData['license_number'],
            'verification_status' => 'PENDING'
        ]);
    }

    public function hospitalRegNoExists($regNo)
    {
        return $this->count(['registration_number' => $regNo]) > 0;
    }

    public function getAllHospitals()
    {
        return $this->where(['verification_status' => 'APPROVED'], [], '*', 'name ASC');
    }

    public function getHospitalByUserId($userId)
    {
        $joins = [
            ['table' => 'users u', 'on' => 'u.id = hospitals.user_id', 'type' => 'LEFT']
        ];
        $res = $this->queryJoin($joins, ['hospitals.user_id' => $userId], 'hospitals.*, u.email AS user_email, u.phone AS user_phone', '', 1);
        return $res ? $res[0] : false;
    }

    private function donorPledgeHasColumn($column)
    {
        $column = (string)$column;
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $column)) return false;

        try {
            // Use a literal for SHOW ... LIKE to avoid driver limitations with placeholders in SHOW statements.
            $res = $this->query("SHOW COLUMNS FROM donor_pledges LIKE '{$column}'");
            return !empty($res);
        } catch (\Throwable $e) {
            return false;
        }
    }

    private function getDonorPledgesHospitalColumn()
    {
        if ($this->donorPledgeHasColumn('preferred_hospital_id')) return 'preferred_hospital_id';
        if ($this->donorPledgeHasColumn('hospital_id')) return 'hospital_id';
        return null;
    }

    private function getDonorPledgesHospitalColumns(): array
    {
        $cols = [];
        if ($this->donorPledgeHasColumn('preferred_hospital_id')) $cols[] = 'preferred_hospital_id';
        if ($this->donorPledgeHasColumn('hospital_id')) $cols[] = 'hospital_id';
        return $cols;
    }

    private function donorPledgeHospitalWhereSql(string $alias = ''): ?string
    {
        $cols = $this->getDonorPledgesHospitalColumns();
        if (empty($cols)) return null;

        $pfx = $alias !== '' ? rtrim($alias, '.') . '.' : '';
        if (count($cols) === 1) {
            return $pfx . $cols[0] . ' = :hid';
        }

        // Mixed schemas/data: treat either column as the assignment source.
        return '(' . $pfx . $cols[0] . ' = :hid OR ' . $pfx . $cols[1] . ' = :hid)';
    }

    public function getApprovedPledgesForEligibility($hospitalId)
    {
        $hospitalId = (int)$hospitalId;
        if ($hospitalId <= 0) return [];

        $where = $this->donorPledgeHospitalWhereSql('dp');
        if (!$where) return [];

                                // Donors that selected this hospital and are ready for hospital-side eligibility screening.
                                // Include relevant in-flight states so the hospital can still see assigned pledges.
                $query = "SELECT 
                    dp.id AS pledge_id,
                    dp.donor_id,
                    dp.organ_id,
                    dp.pledge_date,
                    dp.status,
                    d.nic_number,
                    d.first_name,
                    d.last_name,
                    o.name AS organ_name,
                    d.user_id,
                    (SELECT status FROM consent_withdrawals 
                     WHERE donor_id = dp.donor_id AND (organ_id = dp.organ_id OR (dp.organ_id = 9 AND organ_id = 9))
                     ORDER BY created_at DESC LIMIT 1) as withdrawal_status
                  FROM donor_pledges dp
                  JOIN donors d ON dp.donor_id = d.id
                  JOIN organs o ON dp.organ_id = o.id
                  WHERE UPPER(TRIM(dp.status)) IN ('APPROVED', 'IN_PROGRESS', 'WITHDRAWN', 'COMPLETED')
                    AND NOT EXISTS (
                        SELECT 1 FROM consent_withdrawals cw 
                        WHERE cw.donor_id = dp.donor_id 
                        AND (cw.organ_id = dp.organ_id OR (dp.organ_id = 9 AND cw.organ_id = 9))
                        AND cw.status = 'PENDING_UPLOAD'
                    )
                    AND $where
                  ORDER BY dp.pledge_date DESC";

                return $this->query($query, [':hid' => $hospitalId]) ?: [];
    }

    public function getHospitalIdByRegistrationNo($regNo)
    {
        $regNo = trim((string)$regNo);
        if ($regNo === '') return 0;

        $res = $this->query(
            "SELECT id FROM hospitals WHERE registration_number = :reg_no LIMIT 1",
            [':reg_no' => $regNo]
        );

        return (int)($res[0]->id ?? 0);
    }

    public function getPledgeDetails($pledgeId)
    {
        $pledgeId = (int)$pledgeId;
        if ($pledgeId <= 0) return false;

        $query = "SELECT 
                    dp.*, 
                    d.first_name, d.last_name, d.nic_number, d.date_of_birth, d.gender, d.nationality,
                    u.email, u.phone,
                    o.name AS organ_name
                  FROM donor_pledges dp
                  JOIN donors d ON dp.donor_id = d.id
                  JOIN users u ON d.user_id = u.id
                  JOIN organs o ON dp.organ_id = o.id
                  WHERE dp.id = :pid LIMIT 1";

        $res = $this->query($query, [':pid' => $pledgeId]);
        return $res ? $res[0] : false;
    }

    private function pledgeBelongsToHospital($pledgeId, $hospitalId)
    {
        $pledgeId = (int)$pledgeId;
        $hospitalId = (int)$hospitalId;
        if ($pledgeId <= 0 || $hospitalId <= 0) return false;

        $where = $this->donorPledgeHospitalWhereSql();
        if (!$where) return false;

        $res = $this->query(
            "SELECT id, status FROM donor_pledges WHERE id = :pid AND $where LIMIT 1",
            [':pid' => $pledgeId, ':hid' => $hospitalId]
        );

        return $res ? $res[0] : false;
    }

    private function setPledgeStatusForHospital($pledgeId, $hospitalId, $newStatus, $onlyIfStatusIn = [])
    {
        $newStatus = strtoupper(trim((string)$newStatus));
        if ($newStatus === '') return false;

        $pledge = $this->pledgeBelongsToHospital($pledgeId, $hospitalId);
        if (!$pledge) return false;

        $current = strtoupper(trim((string)($pledge->status ?? '')));
        if (!empty($onlyIfStatusIn)) {
            $allowed = array_map(fn($s) => strtoupper(trim((string)$s)), (array)$onlyIfStatusIn);
            if (!in_array($current, $allowed, true)) return false;
        }

        $where = $this->donorPledgeHospitalWhereSql();
        if (!$where) return false;

        $this->query(
            "UPDATE donor_pledges SET status = :status WHERE id = :pid AND $where",
            [':status' => $newStatus, ':pid' => (int)$pledgeId, ':hid' => (int)$hospitalId]
        );

        $verify = $this->query(
            "SELECT status FROM donor_pledges WHERE id = :pid AND $where LIMIT 1",
            [':pid' => (int)$pledgeId, ':hid' => (int)$hospitalId]
        );

        return (bool)($verify && strtoupper(trim((string)($verify[0]->status ?? ''))) === $newStatus);
    }

    public function approveEligibilityPledge($pledgeId, $hospitalId)
    {
        // UPLOADED -> APPROVED (eligible)
        return $this->setPledgeStatusForHospital($pledgeId, $hospitalId, 'APPROVED', ['UPLOADED']);
    }

    public function rejectEligibilityPledge($pledgeId, $hospitalId)
    {
        // UPLOADED -> WITHDRAWN (used here as a terminal disqualification state)
        return $this->setPledgeStatusForHospital($pledgeId, $hospitalId, 'WITHDRAWN', ['UPLOADED']);
    }

    public function updateHospitalProfile($data)
    {
        $query = "UPDATE hospitals 
                  SET name = :name, address = :address, contact_number = :contact_number, district = :district, facility_type = :facility_type, cmo_name = :cmo_name, cmo_nic = :cmo_nic, medical_license_number = :medical_license_number
                  WHERE registration_number = :reg_no";
        $params = [
            ':name' => $data['name'],
            ':address' => $data['address'] ?? '',
            ':contact_number' => $data['phone'] ?? null,
            ':district' => $data['district'] ?? '',
            ':facility_type' => $data['facility_type'] ?? '',
            ':cmo_name' => $data['cmo_name'] ?? '',
            ':cmo_nic' => $data['cmo_nic'] ?? '',
            ':medical_license_number' => $data['medical_license_number'] ?? '',
            ':reg_no' => $data['registration']
        ];
        return $this->query($query, $params);
    }

    // Organ Requests
    public function getAvailableOrgans()
    {
        // Hospital organ request UI should not include full-body donation options or post-death kidneys
        $query = "SELECT id, name, description, is_available
                  FROM organs
                  WHERE is_available = 1
                    AND LOWER(TRIM(name)) NOT LIKE 'full body%'
                    AND LOWER(TRIM(name)) NOT LIKE 'kidney(after death)%'
                  ORDER BY id ASC";
        return $this->query($query) ?: [];
    }

    private function organExistsById($organId)
    {
        $result = $this->query(
            "SELECT id FROM organs WHERE id = :id LIMIT 1",
            [':id' => $organId]
        );

        return (bool)($result && isset($result[0]->id));
    }

    public function getOrganRequests($regNo)
    {
        $query = "SELECT r.*, o.name as organ_name 
                  FROM organ_requests r 
                  JOIN organs o ON r.organ_id = o.id 
                  WHERE r.hospital_id = (SELECT id FROM hospitals WHERE registration_number = :reg_no) 
                  ORDER BY r.created_at DESC";
        return $this->query($query, [':reg_no' => $regNo]);
    }

    public function getSurgeryMatches($regNo)
    {
        $query = "SELECT m.*, 
                         d.first_name as donor_first_name, d.last_name as donor_last_name, 
                         o.name as organ_name,
                         r.status as request_status,
                         dp.status as pledge_status
                  FROM donor_patient_match m
                  JOIN donor_pledges dp ON m.donor_pledge_id = dp.id
                  JOIN donors d ON dp.donor_id = d.id
                  JOIN organ_requests r ON m.request_id = r.id
                  JOIN organs o ON r.organ_id = o.id
                  WHERE r.hospital_id = (SELECT id FROM hospitals WHERE registration_number = :reg_no)
                  AND (m.donor_status = 'PENDING' OR m.donor_status = 'ACCEPTED')
                  ORDER BY m.surgery_date DESC";
        return $this->query($query, [':reg_no' => $regNo]) ?: [];
    }

    public function getSurgeryMatchDetails($matchId)
    {
        $query = "SELECT m.*, 
                         d.first_name as donor_first_name, d.last_name as donor_last_name, d.nic_number as donor_nic,
                         d.blood_group as donor_blood_group, d.gender as donor_gender,
                         dp.pledge_date, dp.status as pledge_status,
                         o.name as organ_name,
                         r.blood_group as recipient_blood_group, r.recipient_age, r.gender as recipient_gender,
                         r.priority_level, r.created_at as request_date, r.transplant_reason,
                         h.name as hospital_name
                  FROM donor_patient_match m
                  JOIN donor_pledges dp ON m.donor_pledge_id = dp.id
                  JOIN donors d ON dp.donor_id = d.id
                  JOIN organ_requests r ON m.request_id = r.id
                  JOIN organs o ON r.organ_id = o.id
                  JOIN hospitals h ON r.hospital_id = h.id
                  WHERE m.match_id = :mid LIMIT 1";
        $res = $this->query($query, [':mid' => $matchId]);
        return $res ? $res[0] : null;
    }

    public function updateSurgeryMatchStatus($matchId, $status, $reason = null)
    {
        $hStat = ($status === 'APPROVED') ? 'ACCEPTED' : (($status === 'REJECTED') ? 'REJECTED' : 'PENDING');
        
        $query = "UPDATE donor_patient_match 
                  SET hospital_reject_reason = :reason, 
                      hospital_match_status = :h_status,
                      match_date = NOW()
                  WHERE match_id = :mid";
        return $this->query($query, [
            ':reason' => $reason,
            ':h_status' => $hStat,
            ':mid' => $matchId
        ]);
    }

    public function markSurgeryAsCompleted($matchId)
    {
        // 1. Get the pledge_id from the match
        $match = $this->query("SELECT donor_pledge_id FROM donor_patient_match WHERE match_id = :mid", [':mid' => $matchId]);
        if (!$match) return false;
        $pledgeId = $match[0]->donor_pledge_id;

        // 2. Update donor_pledges status to COMPLETED
        $this->query("UPDATE donor_pledges SET status = 'COMPLETED' WHERE id = :pid", [':pid' => $pledgeId]);
        
        // 3. Update the match record status so it moves out of IN_PROGRESS
        $this->query("UPDATE donor_patient_match SET donor_status = 'COMPLETED' WHERE match_id = :mid", [':mid' => $matchId]);

        return true;
    }

    public function addOrganRequest($data)
    {
        $organId = null;

        // Prefer organ_id if provided (matches the user's organs table IDs)
        if (isset($data['organ_id']) && $data['organ_id'] !== '') {
            $candidate = filter_var($data['organ_id'], FILTER_VALIDATE_INT);
            if ($candidate !== false && $candidate > 0 && $this->organExistsById($candidate)) {
                $organId = (int)$candidate;
            } else {
                return false;
            }
        } else {
            // Backwards compatibility: accept organ_type (name)
            $organName = $this->normalizeOrganName($data['organ_type'] ?? '');
            if ($organName === '') {
                return false;
            }

            $organId = $this->getOrCreateOrganId($organName);
            if (!$organId) {
                return false;
            }
        }

        $hasSplitHla = $this->organRequestsHasColumn('hla_a1')
            && $this->organRequestsHasColumn('hla_a2')
            && $this->organRequestsHasColumn('hla_b1')
            && $this->organRequestsHasColumn('hla_b2')
            && $this->organRequestsHasColumn('hla_dr1')
            && $this->organRequestsHasColumn('hla_dr2');

        if ($hasSplitHla) {
            $query = "INSERT INTO organ_requests (
                          hospital_id,
                          organ_id,
                          priority_level,
                          status,
                          recipient_age,
                          blood_group,
                          gender,
                          hla_a1,
                          hla_a2,
                          hla_b1,
                          hla_b2,
                          hla_dr1,
                          hla_dr2,
                          transplant_reason
                      )
                      VALUES (
                          (SELECT id FROM hospitals WHERE registration_number = :reg_no),
                          :organ_id,
                          :urgency,
                          'PENDING',
                          :recipient_age,
                          :blood_group,
                          :gender,
                          :hla_a1,
                          :hla_a2,
                          :hla_b1,
                          :hla_b2,
                          :hla_dr1,
                          :hla_dr2,
                          :transplant_reason
                      )";

            $this->query($query, [
                ':reg_no' => $data['registration_no'],
                ':organ_id' => $organId,
                ':urgency' => $this->mapUrgencyToPriorityLevel($data['urgency'] ?? ''),
                ':recipient_age' => $data['recipient_age'] ?? null,
                ':blood_group' => $data['blood_group'] ?? null,
                ':gender' => $data['gender'] ?? null,
                ':hla_a1' => $data['hla_a1'] ?? null,
                ':hla_a2' => $data['hla_a2'] ?? null,
                ':hla_b1' => $data['hla_b1'] ?? null,
                ':hla_b2' => $data['hla_b2'] ?? null,
                ':hla_dr1' => $data['hla_dr1'] ?? null,
                ':hla_dr2' => $data['hla_dr2'] ?? null,
                ':transplant_reason' => $data['transplant_reason'] ?? null
            ]);
        } else {
            // Legacy schema: store packed HLA value in a single column.
            $query = "INSERT INTO organ_requests (
                          hospital_id,
                          organ_id,
                          priority_level,
                          status,
                          recipient_age,
                          blood_group,
                          gender,
                          hla_typing,
                          transplant_reason
                      )
                      VALUES (
                          (SELECT id FROM hospitals WHERE registration_number = :reg_no),
                          :organ_id,
                          :urgency,
                          'PENDING',
                          :recipient_age,
                          :blood_group,
                          :gender,
                          :hla_typing,
                          :transplant_reason
                      )";

            $this->query($query, [
                ':reg_no' => $data['registration_no'],
                ':organ_id' => $organId,
                ':urgency' => $this->mapUrgencyToPriorityLevel($data['urgency'] ?? ''),
                ':recipient_age' => $data['recipient_age'] ?? null,
                ':blood_group' => $data['blood_group'] ?? null,
                ':gender' => $data['gender'] ?? null,
                ':hla_typing' => $data['hla_typing'] ?? null,
                ':transplant_reason' => $data['transplant_reason'] ?? null
            ]);
        }

        return true;
    }

    private function mapUrgencyToPriorityLevel($urgency)
    {
        $value = strtolower(trim((string)$urgency));

        // New UI categories
        if ($value === 'emergency') return 'CRITICAL';
        if ($value === 'high') return 'URGENT';
        if ($value === 'medium') return 'NORMAL';
        if ($value === 'low') return 'NORMAL';

        // Backwards compatibility (older labels)
        if ($value === 'critical') return 'CRITICAL';
        if ($value === 'urgent') return 'URGENT';
        if ($value === 'normal') return 'NORMAL';

        // Already enum-style
        $upper = strtoupper($value);
        if (in_array($upper, ['NORMAL', 'URGENT', 'CRITICAL'], true)) {
            return $upper;
        }

        return 'NORMAL';
    }

    private function normalizeOrganName($organType)
    {
        $name = trim((string)$organType);
        $name = str_replace(['_', '-'], ' ', $name);
        $name = preg_replace('/\s+/', ' ', $name);
        return $name;
    }

    private function getOrCreateOrganId($organName)
    {
        // Case-insensitive match so we don't create duplicate rows like "kidney" vs "Kidney"
        $result = $this->query(
            "SELECT id FROM organs WHERE LOWER(name) = LOWER(:name) LIMIT 1",
            [':name' => $organName]
        );
        if ($result && isset($result[0]->id)) {
            return (int)$result[0]->id;
        }

        // Create on demand (organs table may be empty on fresh installs)
        $this->query(
            "INSERT IGNORE INTO organs (name, is_available) VALUES (:name, 1)",
            [':name' => $organName]
        );

        $result = $this->query(
            "SELECT id FROM organs WHERE LOWER(name) = LOWER(:name) LIMIT 1",
            [':name' => $organName]
        );
        if ($result && isset($result[0]->id)) {
            return (int)$result[0]->id;
        }

        return null;
    }

    public function updateOrganRequest($requestId, $data)
    {
        $hasSplitHla = $this->organRequestsHasColumn('hla_a1')
            && $this->organRequestsHasColumn('hla_a2')
            && $this->organRequestsHasColumn('hla_b1')
            && $this->organRequestsHasColumn('hla_b2')
            && $this->organRequestsHasColumn('hla_dr1')
            && $this->organRequestsHasColumn('hla_dr2');

        $setParts = [
            "priority_level = :urgency",
            "recipient_age = :recipient_age",
            "blood_group = :blood_group",
            "gender = :gender",
            "transplant_reason = :transplant_reason",
        ];

        $params = [
            ':id' => $requestId,
            ':urgency' => $this->mapUrgencyToPriorityLevel($data['urgency'] ?? ''),
            ':recipient_age' => $data['recipient_age'] ?? null,
            ':blood_group' => $data['blood_group'] ?? null,
            ':gender' => $data['gender'] ?? null,
            ':transplant_reason' => $data['transplant_reason'] ?? null,
        ];

        if ($hasSplitHla) {
            $setParts[] = "hla_a1 = :hla_a1";
            $setParts[] = "hla_a2 = :hla_a2";
            $setParts[] = "hla_b1 = :hla_b1";
            $setParts[] = "hla_b2 = :hla_b2";
            $setParts[] = "hla_dr1 = :hla_dr1";
            $setParts[] = "hla_dr2 = :hla_dr2";

            $params[':hla_a1'] = $data['hla_a1'] ?? null;
            $params[':hla_a2'] = $data['hla_a2'] ?? null;
            $params[':hla_b1'] = $data['hla_b1'] ?? null;
            $params[':hla_b2'] = $data['hla_b2'] ?? null;
            $params[':hla_dr1'] = $data['hla_dr1'] ?? null;
            $params[':hla_dr2'] = $data['hla_dr2'] ?? null;
        } else {
            // Legacy schema: store packed HLA value in a single column.
            $setParts[] = "hla_typing = :hla_typing";
            $params[':hla_typing'] = $data['hla_typing'] ?? null;
        }

        // Support both column names (older/newer schema)
        if ($this->organRequestsHasColumn('edited_reason')) {
            $setParts[] = "edited_reason = :edited_reason";
            $params[':edited_reason'] = $data['edited_reason'] ?? ($data['urgency_reason'] ?? null);
        } elseif ($this->organRequestsHasColumn('urgency_reason')) {
            $setParts[] = "urgency_reason = :urgency_reason";
            $params[':urgency_reason'] = $data['edited_reason'] ?? ($data['urgency_reason'] ?? null);
        }

        $query = "UPDATE organ_requests SET " . implode(', ', $setParts) . " WHERE id = :id";
        return $this->query($query, $params);
    }

    private function organRequestsHasColumn($columnName)
    {
        $result = $this->query(
            "SELECT COUNT(*) AS c FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'organ_requests' AND COLUMN_NAME = :col",
            [':col' => $columnName]
        );

        $count = 0;
        if ($result && isset($result[0]->c)) {
            $count = (int)$result[0]->c;
        } elseif ($result && isset($result[0]->C)) {
            $count = (int)$result[0]->C;
        }

        return $count > 0;
    }

    public function deleteOrganRequest($id)
    {
        $query = "DELETE FROM organ_requests WHERE id = :id";
        return $this->query($query, [':id' => $id]);
    }

    // Success Stories
    public function getSuccessStories($regNo)
    {
        $query = "SELECT s.* 
                  FROM success_stories s
                  JOIN hospitals h ON h.user_id = s.user_id
                  WHERE h.registration_number = :reg_no
                  ORDER BY s.created_at DESC";
        return $this->query($query, [':reg_no' => $regNo]);
    }

    public function addSuccessStory($data)
    {
        // Get the actual hospital ID for institution_id
        $hRes = $this->query("SELECT id, user_id FROM hospitals WHERE registration_number = :reg_no LIMIT 1", [':reg_no' => $data['hospital_registration_no']]);
        $hospitalId = $hRes ? (int)$hRes[0]->id : null;
        $userId = $hRes ? (int)$hRes[0]->user_id : null;

        $query = "INSERT INTO success_stories (
                    title, description, story_type, author_name, donors_count, students_helped, 
                    success_date, user_id, institution_id, institution_type, status, created_at
                  ) 
                  VALUES (
                    :title, :description, :story_type, :author_name, :donors_count, :students_helped, 
                    :success_date, :user_id, :institution_id, 'HOSPITAL', 'Pending', NOW()
                  )";
        
        $params = [
            ':title' => $data['title'],
            ':description' => $data['description'],
            ':story_type' => $data['story_type'] ?? 'CASE',
            ':author_name' => $data['author_name'] ?? null,
            ':donors_count' => (int)($data['donors_count'] ?? 0),
            ':students_helped' => (int)($data['students_helped'] ?? 0),
            ':success_date' => $data['success_date'],
            ':user_id' => $userId,
            ':institution_id' => $hospitalId
        ];

        return (bool)$this->query($query, $params);
    }

    public function updateSuccessStory($data)
    {
        $query = "UPDATE success_stories 
                  SET title = :title, 
                      description = :description, 
                      story_type = :story_type,
                      author_name = :author_name,
                      donors_count = :donors_count,
                      students_helped = :students_helped,
                      success_date = :success_date, 
                      status = :status, 
                      updated_at = NOW() 
                  WHERE story_id = :id";
        
        $params = [
            ':title' => $data['title'],
            ':description' => $data['description'],
            ':story_type' => $data['story_type'] ?? 'CASE',
            ':author_name' => $data['author_name'] ?? null,
            ':donors_count' => (int)($data['donors_count'] ?? 0),
            ':students_helped' => (int)($data['students_helped'] ?? 0),
            ':success_date' => $data['success_date'],
            ':status' => $data['status'],
            ':id' => $data['story_id']
        ];
        
        return (bool)$this->query($query, $params);
    }

    public function deleteSuccessStory($id)
    {
        $query = "DELETE FROM success_stories WHERE story_id = :id";
        return (bool)$this->query($query, [':id' => $id]);
    }

    // Aftercare Appointments
    public function getAftercareAppointments($regNo)
    {
        $this->ensureAftercareAppointmentsSchema();
        $query = "SELECT * FROM aftercare_appointments WHERE hospital_registration_no = :reg_no ORDER BY appointment_date ASC";
        return $this->query($query, [':reg_no' => $regNo]);
    }

    /**
     * Ensure aftercare_appointments supports request/decision workflow.
     * Adds status enum value 'Requested' (if missing) and adds rejection_reason column.
     */
    public function ensureAftercareAppointmentsSchema(): void
    {
        // Add rejection_reason column if missing
        try {
            $col = $this->query("SHOW COLUMNS FROM aftercare_appointments LIKE 'rejection_reason'");
            if (empty($col)) {
                $con = $this->connect();
                $con->exec("ALTER TABLE aftercare_appointments ADD COLUMN rejection_reason TEXT NULL DEFAULT NULL");
            }
        } catch (\Throwable $e) {
            // ignore
        }

        // Ensure status enum contains 'Requested'
        try {
            $statusCol = $this->query("SHOW COLUMNS FROM aftercare_appointments LIKE 'status'");
            if (empty($statusCol) || empty($statusCol[0]->Type)) return;
            $type = (string)$statusCol[0]->Type;
            if (stripos($type, 'enum(') === false) return;

            preg_match_all("/'([^']*)'/", $type, $m);
            $vals = $m[1] ?? [];
            if (empty($vals)) return;
            if (in_array('Requested', $vals, true)) return;

            // Preserve existing values; add Requested first.
            $newVals = array_values(array_unique(array_merge(['Requested'], $vals)));
            $enumSql = "ENUM('" . implode("','", array_map(fn($v) => str_replace("'", "\\'", (string)$v), $newVals)) . "')";
            $con = $this->connect();
            $con->exec("ALTER TABLE aftercare_appointments MODIFY status $enumSql DEFAULT 'Scheduled'");
        } catch (\Throwable $e) {
            // ignore
        }
    }

    public function getAftercareAppointmentRequests($regNo)
    {
        $this->ensureAftercareAppointmentsSchema();
        $query = "SELECT aa.*, 
                         COALESCE(rp.full_name, CONCAT(d.first_name, ' ', d.last_name), 'Patient') as patient_name,
                         COALESCE(rp.nic, d.nic_number, 'N/A') as nic
                  FROM aftercare_appointments aa
                  LEFT JOIN recipient_patient rp ON aa.user_id = rp.user_id
                  LEFT JOIN donors d ON aa.user_id = d.user_id
                  WHERE aa.hospital_registration_no = :reg_no
                    AND aa.status = 'Requested'
                  ORDER BY aa.appointment_date ASC";
        return $this->query($query, [':reg_no' => $regNo]) ?: [];
    }

    public function acceptAftercareAppointment($appointmentId, $regNo)
    {
        $this->ensureAftercareAppointmentsSchema();
        $appointmentId = (int)$appointmentId;
        $regNo = trim((string)$regNo);
        if ($appointmentId <= 0 || $regNo === '') return false;

        $this->query(
            "UPDATE aftercare_appointments
             SET status = 'Scheduled', rejection_reason = NULL
             WHERE appointment_id = :id AND hospital_registration_no = :reg_no",
            [':id' => $appointmentId, ':reg_no' => $regNo]
        );
        return true;
    }

    public function rejectAftercareAppointment($appointmentId, $regNo, $reason)
    {
        $this->ensureAftercareAppointmentsSchema();
        $appointmentId = (int)$appointmentId;
        $regNo = trim((string)$regNo);
        $reason = trim((string)$reason);
        if ($appointmentId <= 0 || $regNo === '' || $reason === '') return false;

        $this->query(
            "UPDATE aftercare_appointments
             SET status = 'Cancelled', rejection_reason = :reason
             WHERE appointment_id = :id AND hospital_registration_no = :reg_no",
            [':id' => $appointmentId, ':reg_no' => $regNo, ':reason' => $reason]
        );
        return true;
    }

    public function notifyDonorByNic($nic, $title, $message, $type = 'INFO')
    {
        $nic = trim((string)$nic);
        if ($nic === '') return false;

        $res = $this->query("SELECT user_id FROM donors WHERE nic_number = :nic LIMIT 1", [':nic' => $nic]);
        if (!$res) return false;
        $userId = (int)$res[0]->user_id;
        return $this->createNotification($userId, $title, $message, $type);
    }

    // Aftercare Support Requests
    public function getAftercareSupportRequests($regNo, $limit = 50)
    {
        $limit = (int)$limit;
        if ($limit <= 0) $limit = 50;

        $query = "SELECT *
                  FROM support_requests
                  WHERE hospital_registration_no = :reg_no
                  ORDER BY created_at DESC, submitted_date DESC, id DESC
                  LIMIT $limit";

        return $this->query($query, [':reg_no' => $regNo]) ?: [];
    }

    public function approveSupportRequest($requestId, $regNo, $reviewedBy = 'Hospital')
    {
        $requestId = (int)$requestId;
        $regNo = trim((string)$regNo);
        if ($requestId <= 0 || $regNo === '') return false;

        $query = "UPDATE support_requests
                  SET status = 'APPROVED',
                      reviewed_date = CURDATE(),
                      reviewed_by = :reviewed_by
                  WHERE id = :id
                    AND hospital_registration_no = :reg_no";

        $this->query($query, [
            ':id' => $requestId,
            ':reg_no' => $regNo,
            ':reviewed_by' => (string)$reviewedBy,
        ]);
        return true;
    }

    public function rejectSupportRequest($requestId, $regNo, $reason, $reviewedBy = 'Hospital')
    {
        $requestId = (int)$requestId;
        $regNo = trim((string)$regNo);
        $reason = trim((string)$reason);
        if ($requestId <= 0 || $regNo === '' || $reason === '') return false;

        // Persist the rejection reason in the description using a marker so the patient can see it.
        $reviewBlock = "[Hospital Review]\nStatus: REJECTED\nReason: " . $reason;

        $query = "UPDATE support_requests
                  SET status = 'REJECTED',
                      reviewed_date = CURDATE(),
                      reviewed_by = :reviewed_by,
                      description = CASE
                        WHEN description IS NULL OR description = '' THEN :review_block
                        ELSE CONCAT(description, '\n\n', :review_block)
                      END
                  WHERE id = :id
                    AND hospital_registration_no = :reg_no";

        $this->query($query, [
            ':id' => $requestId,
            ':reg_no' => $regNo,
            ':reviewed_by' => (string)$reviewedBy,
            ':review_block' => $reviewBlock,
        ]);

        return true;
    }

    // Upcoming Appointments (using upcoming_appointments table)
    public function getLabReports($regNo)
    {
        $query = "SELECT ua.id as appointment_id, ua.*, 
                  COALESCE(CONCAT(d.first_name, ' ', d.last_name), 'N/A') as donor_name,
                  COALESCE(d.nic_number, 'N/A') as donor_nic,
                  COALESCE(ua.donor_id, 'N/A') as patient_id,
                  d.rejected_reason
                  FROM upcoming_appointments ua
                  LEFT JOIN donors d ON ua.donor_id = d.id
                  WHERE ua.hospital_registration_no = :reg_no
                  ORDER BY ua.test_date DESC";
        
        $results = $this->query($query, [':reg_no' => $regNo]);
        return $results;
    }

    public function addLabReport($data)
    {
        $query = "INSERT INTO upcoming_appointments (donor_id, hospital_registration_no, test_type, description, test_date, scheduled_date_1, scheduled_date_2, scheduled_date_3, status, notes)
                  VALUES (:donor_id, :reg_no, :test_type, :description, :test_date, :sd1, :sd2, :sd3, :status, :notes)";
        
        $params = [
            ':donor_id' => $data['donor_id'] ?? null,
            ':reg_no' => $data['hospital_registration_no'],
            ':test_type' => $data['test_type'],
            ':description' => $data['description'] ?? null,
            ':test_date' => $data['test_date'],
            ':sd1' => $data['scheduled_date_1'] ?? null,
            ':sd2' => $data['scheduled_date_2'] ?? null,
            ':sd3' => $data['scheduled_date_3'] ?? null,
            ':status' => $data['result_status'],
            ':notes' => $data['result_notes'] ?? ''
        ];
        
        try {
            $this->query($query, $params);
            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function updateLabReport($reportId, $data)
    {
        $query = "UPDATE upcoming_appointments 
                  SET donor_id = :donor_id, 
                      test_type = :test_type, 
                      description = :description,
                      test_date = :test_date, 
                      scheduled_date_1 = :sd1,
                      scheduled_date_2 = :sd2,
                      scheduled_date_3 = :sd3,
                      status = :status, 
                      notes = :notes
                  WHERE id = :id";
        
        $params = [
            ':id' => $reportId,
            ':donor_id' => $data['donor_id'] ?? null,
            ':test_type' => $data['test_type'],
            ':description' => $data['description'] ?? null,
            ':test_date' => $data['test_date'],
            ':sd1' => $data['scheduled_date_1'] ?? null,
            ':sd2' => $data['scheduled_date_2'] ?? null,
            ':sd3' => $data['scheduled_date_3'] ?? null,
            ':status' => $data['result_status'],
            ':notes' => $data['result_notes'] ?? ''
        ];
        
        $this->query($query, $params);
        return true;
    }

    public function deleteLabReport($reportId)
    {
        $query = "DELETE FROM upcoming_appointments WHERE id = :id";
        $this->query($query, [':id' => $reportId]);
        return true;
    }

    public function updateLabReportStatus($reportId, $status, $reason = null)
    {
        $status = strtoupper(trim((string)$status));
        $query = "UPDATE upcoming_appointments SET status = :status WHERE id = :id";
        $this->query($query, [':id' => $reportId, ':status' => $status]);

        // If the status is ACCEPTED (Approved) or REJECTED, update the donor's eligibility in the donors table
        if ($status === 'ACCEPTED' || $status === 'REJECTED') {
            $apt = $this->query("SELECT donor_id FROM upcoming_appointments WHERE id = :id LIMIT 1", [':id' => $reportId]);
            if ($apt && isset($apt[0]->donor_id)) {
                $donorId = $apt[0]->donor_id;
                $eligible = ($status === 'ACCEPTED') ? 'Yes' : 'No';
                
                // Update donors table
                $this->query(
                    "UPDATE donors SET eligible_to_donate = :eligible, rejected_reason = :reason WHERE id = :id",
                    [':eligible' => $eligible, ':reason' => $reason, ':id' => $donorId]
                );
            }
        }
        
        return true;
    }

    public function getLabReportById($reportId)
    {
        $reportId = (int)$reportId;
        if ($reportId <= 0) return null;
        $res = $this->query("SELECT * FROM upcoming_appointments WHERE id = :id LIMIT 1", [':id' => $reportId]);
        return $res ? $res[0] : null;
    }

    public function softDeleteLabReport($reportId)
    {
        $reportId = (int)$reportId;
        if ($reportId <= 0) return false;

        // Keep record for audit/history and for showing in Hospital Test Results UI.
        // Append a marker in notes so staff can see it later.
        $query = "UPDATE test_results
                  SET status = 'Deleted',
                      notes = CONCAT(COALESCE(notes,''), '\n[Deleted by hospital on ', NOW(), ']')
                  WHERE id = :id";
        $this->query($query, [':id' => $reportId]);
        return true;
    }

    public function getOrganNameById($organId)
    {
        $organId = (int)$organId;
        if ($organId <= 0) return null;
        $res = $this->query("SELECT name FROM organs WHERE id = :id LIMIT 1", [':id' => $organId]);
        return $res ? (string)$res[0]->name : null;
    }

    /**
     * Donors who selected this hospital as their preferred hospital for at least one pledge.
     * Returns donor basic fields + a pipe-separated organ list for UI.
     */
    public function searchDonorsForHospital($hospitalId, $searchQuery = '')
    {
        $hospitalId = (int)$hospitalId;
        if ($hospitalId <= 0) return [];

        $whereHospital = $this->donorPledgeHospitalWhereSql('dp');
        if (!$whereHospital) return [];

        $searchQuery = trim((string)$searchQuery);
        $params = [':hid' => $hospitalId];

        $whereSearch = '';
        if ($searchQuery !== '') {
            $whereSearch = " AND (d.nic_number LIKE :search OR d.first_name LIKE :search OR d.last_name LIKE :search)";
            $params[':search'] = '%' . $searchQuery . '%';
        }

        try {
            // Include ALL donors that have an approved pledge for this hospital.
            // This allows scheduling 'Initial Screening' for new pledges who don't have prior appointments.
            $query = "SELECT
                        DISTINCT d.id,
                        u.username,
                        d.nic_number,
                        d.first_name,
                        d.last_name,
                        d.blood_group,
                                                d.hla_a1,
                                                d.hla_a2,
                                                d.hla_b1,
                                                d.hla_b2,
                                                d.hla_dr1,
                                                d.hla_dr2,
                        GROUP_CONCAT(DISTINCT CONCAT(o.id, ':', o.name) ORDER BY o.id SEPARATOR '||') AS pledged_organs
                      FROM donors d
                      JOIN users u ON u.id = d.user_id
                      JOIN donor_pledges dp ON d.id = dp.donor_id
                      JOIN organs o ON dp.organ_id = o.id
                      WHERE UPPER(TRIM(dp.status)) IN ('APPROVED', 'IN_PROGRESS', 'COMPLETED')
                        AND $whereHospital
                        AND UPPER(TRIM(d.verification_status)) = 'APPROVED'
                        $whereSearch
                      GROUP BY d.id
                      ORDER BY d.first_name, d.last_name
                      LIMIT 100";

            return $this->query($query, $params) ?: [];
        } catch (\Exception $e) {
            return [];
        }
    }

    public function createNotification($userId, $title, $message, $type = 'INFO')
    {
        $userId = (int)$userId;
        if ($userId <= 0) return false;

        $query = "INSERT INTO notifications (user_id, title, message, type) VALUES (:user_id, :title, :message, :type)";
        return $this->query($query, [
            ':user_id' => $userId,
            ':title' => (string)$title,
            ':message' => (string)$message,
            ':type' => (string)$type,
        ]);
    }

    public function notifyDonor($donorId, $title, $message, $type = 'INFO')
    {
        $donorId = (int)$donorId;
        if ($donorId <= 0) return false;

        $res = $this->query("SELECT user_id FROM donors WHERE id = :id LIMIT 1", [':id' => $donorId]);
        if (!$res) return false;
        $userId = (int)$res[0]->user_id;
        return $this->createNotification($userId, $title, $message, $type);
    }

    public function addTestResult($data)
    {
        $query = "INSERT INTO test_results (donor_id, test_name, result_value, document_path, test_date, verified_by_hospital_id)
                  VALUES (:donor_id, :test_name, :result_value, :document_path, :test_date, :verified_by_hospital_id)";

        $params = [
            ':donor_id' => !empty($data['donor_id']) ? (int)$data['donor_id'] : 0,
            ':test_name' => (string)($data['test_name'] ?? ''),
            ':result_value' => $data['result_value'] ?? null,
            ':document_path' => $data['document_path'] ?? null,
            ':test_date' => (string)($data['test_date'] ?? ''),
            ':verified_by_hospital_id' => !empty($data['verified_by_hospital_id']) ? (int)$data['verified_by_hospital_id'] : null,
        ];

        if ($params[':donor_id'] <= 0 || $params[':test_name'] === '' || $params[':test_date'] === '') return false;

        $this->query($query, $params);
        return true;
    }

    public function getTestResultsByHospitalId($hospitalId, $limit = 200)
    {
        $hospitalId = (int)$hospitalId;
        $limit = (int)$limit;
        if ($limit <= 0) $limit = 200;

        $query = "SELECT tr.*, 
                         d.nic_number AS donor_nic,
                         CONCAT(d.first_name, ' ', d.last_name) AS donor_name
                  FROM test_results tr
                  JOIN donors d ON d.id = tr.donor_id
                  WHERE tr.verified_by_hospital_id = :hospital_id && tr.status != 'Deleted'
                  ORDER BY tr.test_date DESC, tr.id DESC
                  LIMIT $limit";

        return $this->query($query, [':hospital_id' => $hospitalId]) ?: [];
    }

    /**
     * Automated Recognition Trigger for Hospitals
     * To be called when an organ retrieval is marked as SUCCESSFUL.
     */
    public function issueDonationCertificate($cisId, $hospitalId)
    {
        // 1. Get Case Details
        $caseRec = $this->query("SELECT cis.donation_case_id, h.name as institution_name FROM case_institution_status cis 
                                 JOIN hospitals h ON cis.institution_id = h.id
                                 WHERE cis.id = :id AND cis.institution_id = :h_id", 
                                 [':id' => $cisId, ':h_id' => $hospitalId])[0] ?? null;
        
        if (!$caseRec) return false;
        
        $caseId = $caseRec->donation_case_id;

        // Prevent duplicate certificates
        $exists = $this->first(['case_institution_request_id' => $cisId], [], "id", "", "donation_certificates");
        if ($exists) return $exists->id;

        // 2. Sync Statuses (Already handled in updateDeceasedFinalFlowStatus but making sure)
        $this->update($caseId, ['overall_status' => 'SUCCESSFUL'], 'id', 'donation_cases');
        $this->update($cisId, ['institution_status' => 'ACCEPTED'], 'id', 'case_institution_status');

        // 3. Issue Certificate
        $certNum = "CERT-H-" . date('Y') . "-" . str_pad($cisId, 5, '0', STR_PAD_LEFT);
        return $this->insert([
            'donation_case_id' => $caseId,
            'case_institution_request_id' => $cisId,
            'certificate_number' => $certNum,
            'file_path' => 'generated_system',
            'issued_by_name' => $caseRec->institution_name ?? 'Donation Hospital'
        ], "donation_certificates");
    }

    /**
     * Issue Appreciation Letter for Organ Donation
     */
    public function issueAppreciationLetter($cisId, $hospitalId, $issuerId)
    {
        // 1. Get Case Details
        $caseRec = $this->query("SELECT cis.donation_case_id, h.name as institution_name FROM case_institution_status cis 
                                 JOIN hospitals h ON cis.institution_id = h.id
                                 WHERE cis.id = :id AND cis.institution_id = :h_id", 
                                 [':id' => $cisId, ':h_id' => $hospitalId])[0] ?? null;
        
        if (!$caseRec) return false;

        $caseId = $caseRec->donation_case_id;

        // Prevent duplicate letters
        $exists = $this->first(['case_institution_request_id' => $cisId], [], "id", "", "appreciation_letters");
        if ($exists) return $exists->id;

        // 2. Issue Letter (Linked directly to CIS for Hospitals)
        $refNum = "APP-H-" . date('Y') . "-" . str_pad($cisId, 5, '0', STR_PAD_LEFT);
        return $this->insert([
            'usage_log_id' => null, // Not a med school usage log
            'case_institution_request_id' => $cisId,
            'ref_number' => $refNum,
            'issued_at' => date('Y-m-d H:i:s'),
            'issued_by_id' => $issuerId,
            'status' => 'ISSUED'
        ], "appreciation_letters");
    }

    // --- Deceased Organ Management (Case Institution Status Integration) ---

    public function getDeceasedRequests($hospitalId, $filter = 'PENDING')
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
                'cis.institution_id' => $hospitalId,
                'cis.institution_type' => 'HOSPITAL',
                'cis.track' => 'ORGAN',
                $statusCondition
            ],
            "dc.id as case_id, dc.case_number, d.first_name, d.last_name, d.nic_number, dd.date_of_death, cis.id as cis_id, cis.request_status, (SELECT GROUP_CONCAT(DISTINCT o.name SEPARATOR ', ') FROM donor_pledges dp2 JOIN organs o ON dp2.organ_id = o.id WHERE dp2.donor_id = d.id AND dp2.status != 'WITHDRAWN') as requested_organs, COALESCE(cis.submission_date, cis.created_at) as request_at",
            "COALESCE(cis.submission_date, cis.created_at) DESC",
            50, 0, "case_institution_status cis"
        ) ?: [];
    }

    public function getDeceasedRequestDetails($hospitalId, $cisId)
    {
        return $this->queryJoin(
            [
                ['table' => 'donation_cases dc', 'on' => 'cis.donation_case_id = dc.id'],
                ['table' => 'donors d', 'on' => 'dc.donor_id = d.id'],
                ['table' => 'death_declarations dd', 'on' => 'dc.death_declaration_id = dd.id'],
                ['table' => 'custodians c', 'on' => 'd.id = c.donor_id AND (c.custodian_number = 1 OR c.custodian_number IS NULL)', 'type' => 'LEFT JOIN']
            ],
            ['cis.id' => $cisId, 'cis.institution_id' => $hospitalId, 'cis.institution_type' => 'HOSPITAL'],
            "cis.*, cis.id as cis_id, dc.case_number, d.id as donor_id, d.first_name, d.last_name, d.date_of_birth, d.gender, d.nic_number, d.nationality, d.blood_group, dd.date_of_death, c.name as custodian_name, c.relationship as custodian_rel, c.phone as custodian_phone, c.email as custodian_email, c.nic_number as custodian_nic",
            "", 1, 0, "case_institution_status cis"
        )[0] ?? false;
    }

    public function updateDeceasedRequestStatus($hospitalId, $cisId, $status, $reason, $userId)
    {
        $data = [
            'request_status' => $status, 
            'request_action_reason' => $reason, 
            'request_action_at' => date('Y-m-d H:i:s'), 
            'request_action_by' => $userId
        ];
        
        if ($status === 'REJECTED') {
            $data['institution_status'] = 'REJECTED';
            $data['rejection_message'] = $reason;
        } elseif ($status === 'ACCEPTED') {
            $data['institution_status'] = 'ACCEPTED';
        }
        
        $this->updateWhere($data, ['id' => $cisId, 'institution_id' => $hospitalId, 'institution_type' => 'HOSPITAL'], "case_institution_status");

        if ($status === 'ACCEPTED') {
            $info = $this->first(['id' => $cisId], [], "donation_case_id, track", "", "case_institution_status");
            if ($info) {
                // For organ donation, multiple hospitals might be involved initially, but once one accepts, others might be invalidated or it depends on specific logic.
                // For now, follow the medical school logic.
                $this->updateWhere(
                    ['request_status' => 'INVALID', 'request_action_reason' => 'Accepted by another hospital', 'request_action_at' => date('Y-m-d H:i:s')],
                    ['donation_case_id' => $info->donation_case_id, 'track' => $info->track, "id != $cisId", "request_status IN ('PENDING', 'UNDER_REVIEW')"],
                    "case_institution_status"
                );
                $this->updateWhere(['overall_status' => 'IN_PROGRESS'], ['id' => $info->donation_case_id], "donation_cases");
            }
        }
        return true;
    }

    public function getDeceasedSubmissions($hospitalId, $filter = 'ALL')
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
                'cis.institution_id' => $hospitalId,
                'cis.institution_type' => 'HOSPITAL',
                'cis.request_status' => 'ACCEPTED',
                $statusCondition
            ],
            "dc.id as case_id, d.first_name, d.last_name, d.nic_number, cis.id as cis_id, cis.document_status, cis.document_action_at, (SELECT GROUP_CONCAT(DISTINCT o.name SEPARATOR ', ') FROM donor_pledges dp2 JOIN organs o ON dp2.organ_id = o.id WHERE dp2.donor_id = d.id AND dp2.status != 'WITHDRAWN') as requested_organs",
            "cis.document_action_at DESC",
            50, 0, "case_institution_status cis"
        ) ?: [];
    }

    public function getDeceasedFinalFlow($hospitalId, $status = 'ALL')
    {
        $where = [
            'cis.institution_id' => $hospitalId, 
            'cis.institution_type' => 'HOSPITAL',
            'cis.document_status' => 'ACCEPTED'
        ];
        if ($status !== 'ALL') {
            $where['cis.final_exam_status'] = $status;
        }

        return $this->queryJoin(
            [
                ['table' => 'donation_cases dc', 'on' => 'cis.donation_case_id = dc.id'],
                ['table' => 'donors d', 'on' => 'dc.donor_id = d.id']
            ],
            $where,
            "dc.id as case_id, d.first_name, d.last_name, d.nic_number, cis.id as cis_id, cis.final_exam_status, cis.final_exam_at, dc.case_number, (SELECT GROUP_CONCAT(DISTINCT o.name SEPARATOR ', ') FROM donor_pledges dp2 JOIN organs o ON dp2.organ_id = o.id WHERE dp2.donor_id = d.id AND dp2.status != 'WITHDRAWN') as requested_organs",
            "cis.final_exam_at DESC",
            50, 0, "case_institution_status cis"
        ) ?: [];
    }

    public function updateDeceasedFinalFlowStatus($hospitalId, $cisId, $status, $reason, $notes, $userId)
    {
        $this->updateWhere([
            'final_exam_status' => $status, 
            'final_exam_reason' => $reason, 
            'final_exam_notes' => $notes,
            'final_exam_at' => date('Y-m-d H:i:s'), 
            'final_exam_by' => $userId
        ], ['id' => $cisId, 'institution_id' => $hospitalId, 'institution_type' => 'HOSPITAL'], "case_institution_status");
        
        if($status === 'ACCEPTED') {
            $this->updateWhere(['institution_status' => 'ACCEPTED'], ['id' => $cisId], "case_institution_status");
            
            $caseRec = $this->first(['id' => $cisId], [], "donation_case_id", "", "case_institution_status");
            if ($caseRec) {
                $caseId = $caseRec->donation_case_id;
                $this->update($caseId, ['overall_status' => 'SUCCESSFUL'], 'id', 'donation_cases');
                
                // Issue BOTH Certificate and Appreciation Letter immediately for Hospitals
                $this->issueDonationCertificate($cisId, $hospitalId);
                $this->issueAppreciationLetter($cisId, $hospitalId, $userId);
            }
        }
        return true;
    }

    public function getDonationCertificatesForHospital($hospitalId)
    {
        return $this->queryJoin(
            [
                ['table' => 'case_institution_status cis', 'on' => 'dc.case_institution_request_id = cis.id'],
                ['table' => 'donation_cases d_case', 'on' => 'dc.donation_case_id = d_case.id'],
                ['table' => 'donors d', 'on' => 'd_case.donor_id = d.id']
            ],
            ['cis.institution_id' => $hospitalId, 'cis.institution_type' => 'HOSPITAL'],
            "dc.*, d.first_name, d.last_name, cis.final_exam_at",
            "dc.issued_at DESC",
            50, 0, "donation_certificates dc"
        ) ?: [];
    }
    public function getEligibleDonors($hospitalId)
    {
        $hospitalId = (int)$hospitalId;
        if ($hospitalId <= 0) return [];

        $whereHospital = $this->donorPledgeHospitalWhereSql('dp');
        if (!$whereHospital) return [];

        // Fetch donors who have an APPROVED pledge and a signed form for this hospital's area.
        // We also fetch the latest appointment info if exist
        $query = "SELECT d.id, d.first_name, d.last_name, d.nic_number, d.blood_group, d.date_of_birth, d.gender,
                         GROUP_CONCAT(DISTINCT o.name SEPARATOR ', ') as organs,
                         GROUP_CONCAT(DISTINCT CONCAT(o.id, ':', o.name) ORDER BY o.id SEPARATOR '||') AS pledged_organs,
                         -- Fetch ALL matching clinical tests for the current workflow
                         GROUP_CONCAT(DISTINCT CONCAT(ua.test_type, '::', ua.status) SEPARATOR '||') as clinical_tests_list,
                         (SELECT status FROM upcoming_appointments 
                          WHERE donor_id = d.id 
                          AND test_type LIKE '% - %'
                          ORDER BY created_at DESC LIMIT 1) as latest_appointment_status,
                         (SELECT test_type FROM upcoming_appointments 
                          WHERE donor_id = d.id 
                          AND test_type LIKE '% - %'
                          ORDER BY created_at DESC LIMIT 1) as latest_test_type,
                         (SELECT scheduled_date_1 FROM upcoming_appointments 
                          WHERE donor_id = d.id 
                          AND test_type LIKE '% - %'
                          ORDER BY created_at DESC LIMIT 1) as latest_date_1,
                         (SELECT scheduled_date_2 FROM upcoming_appointments 
                          WHERE donor_id = d.id 
                          AND test_type LIKE '% - %'
                          ORDER BY created_at DESC LIMIT 1) as latest_date_2,
                         (SELECT scheduled_date_3 FROM upcoming_appointments 
                          WHERE donor_id = d.id 
                          AND test_type LIKE '% - %'
                          ORDER BY created_at DESC LIMIT 1) as latest_date_3,
                         (SELECT Tests_done FROM upcoming_appointments 
                          WHERE donor_id = d.id 
                          AND test_type LIKE '% - %'
                          ORDER BY created_at DESC LIMIT 1) as latest_tests_done
                  FROM donors d
                  JOIN upcoming_appointments ua ON ua.donor_id = d.id
                  JOIN donor_pledges dp ON d.id = dp.donor_id
                  JOIN organs o ON dp.organ_id = o.id
                  WHERE UPPER(TRIM(dp.status)) = 'APPROVED'
                    AND UPPER(TRIM(ua.status)) IN ('ACCEPTED', 'SCHEDULED', 'APPROVED', 'COMPLETED', 'SUCCESS')
                    AND ua.hospital_registration_no = (SELECT registration_number FROM hospitals WHERE id = :hid)
                    AND $whereHospital
                    AND NOT EXISTS (
                        SELECT 1 FROM consent_withdrawals cw 
                        WHERE cw.donor_id = dp.donor_id 
                        AND (cw.organ_id = dp.organ_id OR (dp.organ_id = 9 AND cw.organ_id = 9))
                        AND cw.status = 'PENDING_UPLOAD'
                    )
                  GROUP BY d.id
                  ORDER BY d.first_name ASC";

        return $this->query($query, [':hid' => $hospitalId]) ?: [];
    }
}

