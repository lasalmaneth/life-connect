<?php

namespace App\Models;

use App\Core\Model;

class HospitalModel {
    use Model;

    protected $table = 'hospitals'; // Updated to match new schema

    public function registerHospital($userId, $hospitalData, $cmoData)
    {
        $query = "INSERT INTO hospitals (
            user_id, registration_number, transplant_id, name, address, contact_number, district, facility_type, 
            cmo_name, cmo_nic, medical_license_number, verification_status
        ) VALUES (
            :user_id, :reg_no, :transplant_id, :name, :address, :contact_number, :district, :type, 
            :cmo_name, :cmo_nic, :license, 'PENDING'
        )";
        
        $params = [
            ':user_id' => $userId,
            ':reg_no' => $hospitalData['registration_number'],
            ':transplant_id' => $hospitalData['transplant_id'] ?? null,
            ':name' => $hospitalData['name'],
            ':address' => $hospitalData['address'],
            ':contact_number' => $hospitalData['contact_number'] ?? null,
            ':district' => $hospitalData['district'],
            ':type' => $hospitalData['type'],
            ':cmo_name' => $cmoData['name'],
            ':cmo_nic' => $cmoData['nic'],
            ':license' => $cmoData['license_number']
        ];
        
        $this->query($query, $params);
        return true;
    }

    public function hospitalRegNoExists($regNo)
    {
        $query = "SELECT COUNT(*) as count FROM hospitals WHERE registration_number = :reg_no";
        $result = $this->query($query, [':reg_no' => $regNo]);
        return $result && $result[0]->count > 0;
    }

    public function getAllHospitals()
    {
        $query = "SELECT * FROM hospitals WHERE verification_status = 'APPROVED' ORDER BY name ASC";
        return $this->query($query);
    }

    public function getHospitalByUserId($userId)
    {
        $query = "SELECT h.*, u.email AS user_email, u.phone AS user_phone
                  FROM hospitals h
                  LEFT JOIN users u ON u.id = h.user_id
                  WHERE h.user_id = :user_id
                  LIMIT 1";
        $results = $this->query($query, [':user_id' => $userId]);
        return $results ? $results[0] : false;
    }

    private function donorPledgeHasColumn($column)
    {
        try {
            $res = $this->query("SHOW COLUMNS FROM donor_pledges LIKE :col", [':col' => $column]);
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
                    o.name AS organ_name
                  FROM donor_pledges dp
                  JOIN donors d ON dp.donor_id = d.id
                  JOIN organs o ON dp.organ_id = o.id
                                                                        WHERE UPPER(TRIM(dp.status)) IN ('PENDING','UPLOADED','APPROVED','IN_PROGRESS')
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
                  SET name = :name, address = :address, contact_number = :contact_number
                  WHERE registration_number = :reg_no";
        
        $params = [
            ':name' => $data['name'],
            ':address' => $data['address'] ?? '',
            ':contact_number' => $data['phone'] ?? null,
            ':reg_no' => $data['registration']
        ];
        
        return $this->query($query, $params);
    }

    // Organ Requests
    public function getAvailableOrgans()
    {
        // Hospital organ request UI should not include full-body donation options
        $query = "SELECT id, name, description, is_available
                  FROM organs
                  WHERE is_available = 1
                    AND LOWER(TRIM(name)) NOT LIKE 'full body%'
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
        $setParts = [
            "priority_level = :urgency",
            "recipient_age = :recipient_age",
            "blood_group = :blood_group",
            "gender = :gender",
            "hla_typing = :hla_typing",
            "transplant_reason = :transplant_reason",
        ];

        $params = [
            ':id' => $requestId,
            ':urgency' => $this->mapUrgencyToPriorityLevel($data['urgency'] ?? ''),
            ':recipient_age' => $data['recipient_age'] ?? null,
            ':blood_group' => $data['blood_group'] ?? null,
            ':gender' => $data['gender'] ?? null,
            ':hla_typing' => $data['hla_typing'] ?? null,
            ':transplant_reason' => $data['transplant_reason'] ?? null,
        ];

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

    // Recipients
    public function getRecipients($regNo)
    {
        $query = "SELECT * FROM recipients WHERE hospital_registration_no = :reg_no ORDER BY created_at DESC";
        return $this->query($query, [':reg_no' => $regNo]);
    }

    public function addRecipient($data)
    {
        $query = "INSERT INTO recipients (nic, name, organ_received, surgery_date, treatment_notes, status, hospital_registration_no) 
                  VALUES (:nic, :name, :organ_received, :surgery_date, :treatment_notes, 'Active', :reg_no)";
        $this->query($query, [
            ':nic' => $data['nic'],
            ':name' => $data['name'],
            ':organ_received' => $data['organ_received'],
            ':surgery_date' => $data['surgery_date'],
            ':treatment_notes' => $data['treatment_notes'],
            ':reg_no' => $data['hospital_registration_no']
        ]);
        return true;
    }

    public function updateRecipient($data)
    {
        $query = "UPDATE recipients SET nic = :nic, name = :name, organ_received = :organ_received, 
                  surgery_date = :surgery_date, treatment_notes = :treatment_notes, status = :status, updated_at = NOW() 
                  WHERE recipient_id = :id";
        $this->query($query, [
            ':nic' => $data['nic'],
            ':name' => $data['name'],
            ':organ_received' => $data['organ_received'],
            ':surgery_date' => $data['surgery_date'],
            ':treatment_notes' => $data['treatment_notes'],
            ':status' => $data['status'],
            ':id' => $data['recipient_id']
        ]);
        return true;
    }

    public function deleteRecipient($id)
    {
        $query = "DELETE FROM recipients WHERE recipient_id = :id";
        $this->query($query, [':id' => $id]);
        return true;
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
        $query = "INSERT INTO success_stories (title, description, success_date, user_id, status) 
                  VALUES (:title, :description, :success_date, 
                      (SELECT user_id FROM hospitals WHERE registration_number = :reg_no LIMIT 1), 
                      'Pending')";
        $this->query($query, [
            ':title' => $data['title'],
            ':description' => $data['description'],
            ':success_date' => $data['success_date'],
            ':reg_no' => $data['hospital_registration_no']
        ]);
        return true;
    }

    public function updateSuccessStory($data)
    {
        $query = "UPDATE success_stories SET title = :title, description = :description, 
                  success_date = :success_date, status = :status, updated_at = NOW() 
                  WHERE story_id = :id";
        $this->query($query, [
            ':title' => $data['title'],
            ':description' => $data['description'],
            ':success_date' => $data['success_date'],
            ':status' => $data['status'],
            ':id' => $data['story_id']
        ]);
        return true;
    }

    public function deleteSuccessStory($id)
    {
        $query = "DELETE FROM success_stories WHERE story_id = :id";
        $this->query($query, [':id' => $id]);
        return true;
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
        $query = "SELECT * FROM aftercare_appointments
                  WHERE hospital_registration_no = :reg_no
                    AND status = 'Requested'
                  ORDER BY appointment_date ASC";
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
                  COALESCE(ua.donor_id, 'N/A') as patient_id
                  FROM upcoming_appointments ua
                  LEFT JOIN donors d ON ua.donor_id = d.id
                  WHERE ua.hospital_registration_no = :reg_no
                  ORDER BY ua.test_date DESC";
        
        $results = $this->query($query, [':reg_no' => $regNo]);
        return $results;
    }

    public function addLabReport($data)
    {
        $query = "INSERT INTO upcoming_appointments (donor_id, hospital_registration_no, test_type, test_date, status, notes)
                  VALUES (:donor_id, :reg_no, :test_type, :test_date, :status, :notes)";
        
        $params = [
            ':donor_id' => $data['donor_id'] ?? null,
            ':reg_no' => $data['hospital_registration_no'],
            ':test_type' => $data['test_type'],
            ':test_date' => $data['test_date'],
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
                      test_date = :test_date, 
                      status = :status, 
                      notes = :notes
                  WHERE id = :id";
        
        $params = [
            ':id' => $reportId,
            ':donor_id' => $data['donor_id'] ?? null,
            ':test_type' => $data['test_type'],
            ':test_date' => $data['test_date'],
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

    public function updateLabReportStatus($reportId, $status)
    {
        $query = "UPDATE upcoming_appointments SET status = :status WHERE id = :id";
        $this->query($query, [':id' => $reportId, ':status' => $status]);
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
        $query = "UPDATE upcoming_appointments
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

                $query = "SELECT 
                                        d.id,
                                        d.nic_number,
                                        d.first_name,
                                        d.last_name,
                                        d.blood_group,
                                        GROUP_CONCAT(DISTINCT CONCAT(o.id, ':', o.name) ORDER BY o.id SEPARATOR '||') AS pledged_organs
                                    FROM donors d
                                    JOIN donor_pledges dp ON dp.donor_id = d.id
                                    JOIN organs o ON o.id = dp.organ_id
                                    WHERE UPPER(TRIM(d.verification_status)) IN ('APPROVED','PENDING')
                                        AND UPPER(TRIM(dp.status)) != 'WITHDRAWN'
                                        AND $whereHospital
                                        $whereSearch
                                    GROUP BY d.id
                                    ORDER BY d.first_name, d.last_name
                                    LIMIT 50";

                $results = $this->query($query, $params) ?: [];
                if (!empty($results)) return $results;

                // Fallback: if this hospital has no linked pledges yet (common in seeded DBs),
                // return available donors so the hospital can schedule appointments.
                $fallbackParams = [];
                $fallbackWhereSearch = '';
                if ($searchQuery !== '') {
                        $fallbackWhereSearch = " AND (d.nic_number LIKE :search OR d.first_name LIKE :search OR d.last_name LIKE :search)";
                        $fallbackParams[':search'] = '%' . $searchQuery . '%';
                }

                $fallbackQuery = "SELECT
                                        d.id,
                                        d.nic_number,
                                        d.first_name,
                                        d.last_name,
                                        d.blood_group,
                                        GROUP_CONCAT(DISTINCT CONCAT(o.id, ':', o.name) ORDER BY o.id SEPARATOR '||') AS pledged_organs
                                    FROM donors d
                                    LEFT JOIN donor_pledges dp ON dp.donor_id = d.id AND UPPER(TRIM(dp.status)) != 'WITHDRAWN'
                                    LEFT JOIN organs o ON o.id = dp.organ_id
                                    WHERE UPPER(TRIM(d.verification_status)) IN ('APPROVED','PENDING')
                                        $fallbackWhereSearch
                                    GROUP BY d.id
                                    ORDER BY d.first_name, d.last_name
                                    LIMIT 50";

                return $this->query($fallbackQuery, $fallbackParams) ?: [];
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
        $patientType = strtoupper(trim((string)($data['patient_type'] ?? 'DONOR')));
        if (!in_array($patientType, ['DONOR', 'RECIPIENT'], true)) $patientType = 'DONOR';

        // If recipient support columns are missing, attempt to add them (dev-friendly).
        if ($patientType === 'RECIPIENT') {
            $this->ensureTestResultsRecipientSchema();
        }

        $hasRecipientSupport = $this->testResultsHasRecipientSupport();

        if ($hasRecipientSupport) {
            $query = "INSERT INTO test_results (patient_type, donor_id, recipient_id, test_name, result_value, document_path, test_date, verified_by_hospital_id)
                      VALUES (:patient_type, :donor_id, :recipient_id, :test_name, :result_value, :document_path, :test_date, :verified_by_hospital_id)";
        } else {
            // Backwards-compatible schema (donor only)
            $query = "INSERT INTO test_results (donor_id, test_name, result_value, document_path, test_date, verified_by_hospital_id)
                      VALUES (:donor_id, :test_name, :result_value, :document_path, :test_date, :verified_by_hospital_id)";
        }

        $params = [
            ':donor_id' => !empty($data['donor_id']) ? (int)$data['donor_id'] : null,
            ':patient_type' => (string)$patientType,
            ':recipient_id' => !empty($data['recipient_id']) ? (int)$data['recipient_id'] : null,
            ':test_name' => (string)($data['test_name'] ?? ''),
            ':result_value' => $data['result_value'] ?? null,
            ':document_path' => $data['document_path'] ?? null,
            ':test_date' => (string)($data['test_date'] ?? ''),
            ':verified_by_hospital_id' => !empty($data['verified_by_hospital_id']) ? (int)$data['verified_by_hospital_id'] : null,
        ];


        if ($params[':test_name'] === '' || $params[':test_date'] === '') return false;

        if ($hasRecipientSupport) {
            if ($patientType === 'DONOR' && (int)($params[':donor_id'] ?? 0) <= 0) return false;
            if ($patientType === 'RECIPIENT' && (int)($params[':recipient_id'] ?? 0) <= 0) return false;
        } else {
            // Old schema: donor only
            if ((int)($params[':donor_id'] ?? 0) <= 0) return false;
        }

        // Only pass bound params used by the chosen query
        if (!$hasRecipientSupport) {
            unset($params[':patient_type'], $params[':recipient_id']);
            // donor_id cannot be null in old schema
            $params[':donor_id'] = (int)($data['donor_id'] ?? 0);
        }

        $this->query($query, $params);
        return true;
    }

    private function testResultsHasRecipientSupport(): bool
    {
        try {
            $pt = $this->query("SHOW COLUMNS FROM test_results LIKE 'patient_type'");
            $rid = $this->query("SHOW COLUMNS FROM test_results LIKE 'recipient_id'");
            // donor_id nullability isn't strictly checked here; we avoid inserting recipient rows without support.
            return !empty($pt) && !empty($rid);
        } catch (\Throwable $e) {
            return false;
        }
    }

    private function ensureTestResultsRecipientSchema(): void
    {
        try {
            if ($this->testResultsHasRecipientSupport()) return;

            $con = $this->connect();
            // Add columns if missing
            $con->exec("ALTER TABLE test_results ADD COLUMN patient_type VARCHAR(20) NOT NULL DEFAULT 'DONOR'");
        } catch (\Throwable $e) {
            // ignore
        }

        try {
            $con = $this->connect();
            $con->exec("ALTER TABLE test_results ADD COLUMN recipient_id INT(11) NULL DEFAULT NULL");
        } catch (\Throwable $e) {
            // ignore
        }

        try {
            // Allow donor_id to be NULL for recipient records
            $con = $this->connect();
            $con->exec("ALTER TABLE test_results MODIFY donor_id INT(11) NULL");
        } catch (\Throwable $e) {
            // ignore
        }
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
                  WHERE tr.verified_by_hospital_id = :hospital_id
                  ORDER BY tr.test_date DESC, tr.id DESC
                  LIMIT $limit";

        return $this->query($query, [':hospital_id' => $hospitalId]) ?: [];
    }
}
