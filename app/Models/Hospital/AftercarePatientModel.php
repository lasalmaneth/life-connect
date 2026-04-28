<?php

namespace App\Models\Hospital;

use App\Core\Model;
use App\Models\UserModel;

class AftercarePatientModel
{
    use Model;

    protected $table = 'aftercare_patients';
    private string $recipientTable = 'recipient_patient';

    private function aftercareHasColumn(string $column): bool
    {
        static $cache = [];
        if (array_key_exists($column, $cache)) {
            return (bool) $cache[$column];
        }

        $res = $this->query("SHOW COLUMNS FROM {$this->table} LIKE :col", [':col' => $column]);
        $cache[$column] = !empty($res);
        return (bool) $cache[$column];
    }

    private function aftercareColumnIsNullable(string $column): bool
    {
        static $cache = [];
        $key = $column . ':nullable';
        if (array_key_exists($key, $cache)) {
            return (bool) $cache[$key];
        }

        $res = $this->query("SHOW COLUMNS FROM {$this->table} LIKE :col", [':col' => $column]);
        if (empty($res)) {
            $cache[$key] = false;
            return false;
        }

        $nullFlag = strtoupper((string) ($res[0]->Null ?? ''));
        $cache[$key] = ($nullFlag === 'YES');
        return (bool) $cache[$key];
    }

    private function usersRoleSupportsAftercare(): bool
    {
        static $cache = null;
        if ($cache !== null) {
            return (bool) $cache;
        }

        $res = $this->query("SHOW COLUMNS FROM users LIKE 'role'");
        $type = $res ? (string) ($res[0]->Type ?? '') : '';
        $cache = (stripos($type, 'AFTERCARE_PATIENT') !== false || stripos($type, 'RECIPIENT_PATIENT') !== false);
        return (bool) $cache;
    }

    public function getByRegistrationNumber(string $registrationNumber)
    {
        $registrationNumber = trim($registrationNumber);
        if ($registrationNumber === '')
            return false;

        $res = $this->query(
            "SELECT registration_number FROM {$this->table}
             WHERE registration_number = :rn
             LIMIT 1",
            [':rn' => $registrationNumber]
        );

        return $res ? $res[0] : false;
    }

    public function getByNic(string $nic)
    {
        $nic = trim($nic);
        if ($nic === '')
            return false;

        $res = $this->query(
            "SELECT nic FROM {$this->table}
             WHERE nic = :nic
             LIMIT 1",
            [':nic' => $nic]
        );

        return $res ? $res[0] : false;
    }

    public function createRecipientAccount(array $data): string
    {
        $fullName = trim((string) ($data['full_name'] ?? ''));
        $nic = trim((string) ($data['nic'] ?? ''));
        $hospitalReg = trim((string) ($data['hospital_registration_no'] ?? ''));
        $requestedReg = trim((string) ($data['registration_number'] ?? ''));

        if ($fullName === '' || $nic === '' || $hospitalReg === '') {
            throw new \InvalidArgumentException('Full name, NIC, and Hospital Registration are required.');
        }

        // Check NIC only in aftercare_patients (source of truth)
        $existing = $this->query(
            "SELECT id FROM {$this->table} WHERE nic = :nic LIMIT 1",
            [':nic' => $nic]
        );
        if ($existing) {
            throw new \RuntimeException("A patient with NIC '$nic' is already registered.");
        }

        // Generate or validate registration number
        if ($requestedReg !== '') {
            $taken = $this->query(
                "SELECT id FROM {$this->table} WHERE registration_number = :rn LIMIT 1",
                [':rn' => $requestedReg]
            );
            if ($taken) {
                throw new \RuntimeException("Registration number '$requestedReg' is already in use.");
            }
            $regNumber = $requestedReg;
        } else {
            $regNumber = $this->generateNextRegistrationNumber((int) date('Y'));
        }

        $userModel = new UserModel();
        $passwordHash = password_hash($nic, PASSWORD_DEFAULT);
        $userId = null;

        try {
            // 1. Create login account
            $userId = (int) $userModel->insert([
                'username' => $regNumber,
                'password_hash' => $passwordHash,
                'role' => 'RECIPIENT_PATIENT',
                'status' => 'ACTIVE',
                'must_change_credentials' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            if (!$userId) {
                throw new \RuntimeException('Failed to create login account.');
            }

            // 2. Create aftercare_patients row
            $this->query(
                "INSERT INTO {$this->table}
                    (user_id, registration_number, nic, full_name, patient_type, hospital_registration_no, status)
                 VALUES
                    (:uid, :rn, :nic, :name, 'RECIPIENT', :hosp, 'PENDING')",
                [
                    ':uid' => $userId,
                    ':rn' => $regNumber,
                    ':nic' => $nic,
                    ':name' => $fullName,
                    ':hosp' => $hospitalReg,
                ]
            );

            // 3. Create recipient_patient row
            $this->insertRecipientPatientRow($userId, $regNumber, $nic, $fullName, $hospitalReg, $data);

            return $regNumber;

        } catch (\Throwable $e) {
            // Rollback everything
            $this->query("DELETE FROM {$this->recipientTable} WHERE registration_number = :rn", [':rn' => $regNumber]);
            $this->query("DELETE FROM {$this->table} WHERE registration_number = :rn", [':rn' => $regNumber]);
            if ($userId) {
                $this->query("DELETE FROM users WHERE id = :id", [':id' => $userId]);
            }
            throw new \RuntimeException('Registration failed: ' . $e->getMessage());
        }
    }

    public function updateStatus(int $userId, string $status): bool
    {
        $this->query(
            "UPDATE {$this->table} SET status = :status WHERE user_id = :uid LIMIT 1",
            [':status' => $status, ':uid' => $userId]
        );
        return true;
    }

    private function generateNextRegistrationNumber(int $year): string
    {
        $prefix = 'REG-' . $year . '-';

        // Use numeric MAX from BOTH tables to avoid alphabetical sort bugs
        $res = $this->query(
            "SELECT GREATEST(
                COALESCE((
                    SELECT MAX(CAST(SUBSTRING_INDEX(registration_number, '-', -1) AS UNSIGNED))
                    FROM {$this->table}
                    WHERE registration_number LIKE :p
                ), 0),
                COALESCE((
                    SELECT MAX(CAST(SUBSTRING_INDEX(username, '-', -1) AS UNSIGNED))
                    FROM users
                    WHERE username LIKE :p
                ), 0)
            ) AS max_seq",
            [':p' => $prefix . '%']
        );

        $nextSeq = 1;
        if ($res && isset($res[0]->max_seq) && (int) $res[0]->max_seq > 0) {
            $nextSeq = (int) $res[0]->max_seq + 1;
        }

        return sprintf('REG-%d-%04d', $year, $nextSeq);
    }

    private function insertRecipientPatientRow(int $userId, string $registrationNumber, string $nic, string $fullName, string $hospitalReg, array $data): void
    {
        $this->query(
            "INSERT INTO {$this->recipientTable} (
                user_id,
                registration_number,
                nic,
                full_name,
                hospital_registration_no,
                age,
                weight,
                gender,
                blood_group,
                contact_details,
                medical_details,
                surgery_type,
                surgery_date,

                status,
                type,
                disabilities,
                created_at
            ) VALUES (
                :uid,
                :rn,
                :nic,
                :full_name,
                :hosp,
                :age,
                :weight,
                :gender,
                :blood_group,
                :contact_details,
                :medical_details,
                :surgery_type,
                :surgery_date,
                'PENDING',
                :type,
                :disabilities,
                NOW()
            )",
            [
                ':uid' => $userId,
                ':rn' => $registrationNumber,
                ':nic' => $nic,
                ':full_name' => $fullName,
                ':hosp' => $hospitalReg,
                ':age' => $data['age'] ?? null,
                ':weight' => $data['weight'] ?? null,
                ':gender' => $data['gender'] ?? null,
                ':blood_group' => $data['blood_group'] ?? null,
                ':contact_details' => $data['contact_details'] ?? null,
                ':medical_details' => $data['medical_details'] ?? null,
                ':disabilities' => $data['disabilities'] ?? null,
                ':surgery_type' => $data['surgery_type'] ?? null,
                ':surgery_date' => $data['surgery_date'] ?? null,
                ':type' => $data['type'] ?? null,
            ]
        );
    }

    /**
     * Save donor patient details into aftercare_patients (no login intended).
     * If donor already exists by NIC, updates their profile fields.
     */
    public function upsertDonorPatient(array $data, ?int $userId = null): string
    {
        $fullName = trim((string) ($data['full_name'] ?? ''));
        $nic = trim((string) ($data['nic'] ?? ''));
        $hospitalReg = trim((string) ($data['hospital_registration_no'] ?? ''));

        if ($fullName === '' || $nic === '' || $hospitalReg === '') {
            throw new \InvalidArgumentException('Missing required fields');
        }

        $existing = $this->getByNic($nic);
        if ($existing) {
            $updateFields = [
                'full_name' => $fullName,
                'hospital_registration_no' => $hospitalReg,
                'nic' => $nic,
            ];
            $sql = "UPDATE {$this->table} SET full_name = :full_name, hospital_registration_no = :hosp, updated_at = NOW()";
            $params = [
                ':full_name' => $fullName,
                ':hosp' => $hospitalReg,
                ':nic' => $nic,
            ];

            if ($userId !== null) {
                $sql .= ", user_id = :uid";
                $params[':uid'] = $userId;
            }

            $sql .= " WHERE nic = :nic LIMIT 1";

            $this->query($sql, $params);
            return (string) ($existing->registration_number ?? '');
        }

        $year = (int) date('Y');
        $attempts = 0;
        while ($attempts < 10) {
            $attempts++;
            $registrationNumber = $this->generateNextRegistrationNumber($year);


            try {
                $this->query(
                    "INSERT INTO {$this->table} (
                        registration_number,
                        user_id,
                        nic,
                        full_name,
                        patient_type,
                        hospital_registration_no,
                        status,
                        created_at
                    ) VALUES (
                        :rn,
                        :uid,
                        :nic,
                        :full_name,
                        'DONOR',
                        :hosp,
                        'ACTIVE',
                        NOW()
                    )",
                    [
                        ':rn' => $registrationNumber,
                        ':uid' => $userId,
                        ':nic' => $nic,
                        ':full_name' => $fullName,
                        ':hosp' => $hospitalReg,
                    ]
                );
                return $registrationNumber;
            } catch (\PDOException $e) {
                $code = (string) $e->getCode();
                if ($code === '23000') {
                    continue;
                }
                throw $e;
            }
        }

        throw new \RuntimeException('Could not create donor record in aftercare_patients.');
    }

    public function getRecipientsByHospital(string $hospitalReg)
    {
        return $this->query(
            "SELECT 
                a.nic, 
                a.full_name, 
                a.registration_number, 
                a.status,
                r.surgery_type, 
                r.surgery_date, 
                r.contact_details, 
                r.medical_details,
                r.weight,
                r.age,
                r.blood_group,
                r.type,
                r.disabilities
             FROM {$this->table} a
             JOIN {$this->recipientTable} r ON a.registration_number = r.registration_number
             WHERE a.hospital_registration_no = :hosp AND a.patient_type = 'RECIPIENT' 
             ORDER BY a.created_at DESC",
            [':hosp' => $hospitalReg]
        );
    }
}
