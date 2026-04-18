<?php

namespace App\Models;

use App\Core\Model;

class AftercarePatientModel
{
    use Model;

    protected $table = 'aftercare_patients';
    private string $recipientTable = 'recipient_patient';

    private function aftercareHasColumn(string $column): bool
    {
        static $cache = [];
        if (array_key_exists($column, $cache)) {
            return (bool)$cache[$column];
        }

        $res = $this->query("SHOW COLUMNS FROM {$this->table} LIKE :col", [':col' => $column]);
        $cache[$column] = !empty($res);
        return (bool)$cache[$column];
    }

    private function aftercareColumnIsNullable(string $column): bool
    {
        static $cache = [];
        $key = $column . ':nullable';
        if (array_key_exists($key, $cache)) {
            return (bool)$cache[$key];
        }

        $res = $this->query("SHOW COLUMNS FROM {$this->table} LIKE :col", [':col' => $column]);
        if (empty($res)) {
            $cache[$key] = false;
            return false;
        }

        $nullFlag = strtoupper((string)($res[0]->Null ?? ''));
        $cache[$key] = ($nullFlag === 'YES');
        return (bool)$cache[$key];
    }

    private function usersRoleSupportsAftercare(): bool
    {
        static $cache = null;
        if ($cache !== null) {
            return (bool)$cache;
        }

        $res = $this->query("SHOW COLUMNS FROM users LIKE 'role'");
        $type = $res ? (string)($res[0]->Type ?? '') : '';
        $cache = (stripos($type, 'AFTERCARE_PATIENT') !== false || stripos($type, 'RECIPIENT_PATIENT') !== false);
        return (bool)$cache;
    }

    public function getByRegistrationNumber(string $registrationNumber)
    {
        $registrationNumber = trim($registrationNumber);
        if ($registrationNumber === '') return false;

        $res = $this->query(
            "SELECT * FROM {$this->table} WHERE registration_number = :rn LIMIT 1",
            [':rn' => $registrationNumber]
        );

        return $res ? $res[0] : false;
    }

    public function getByNic(string $nic)
    {
        $nic = trim($nic);
        if ($nic === '') return false;

        $res = $this->query(
            "SELECT * FROM {$this->table} WHERE nic = :nic LIMIT 1",
            [':nic' => $nic]
        );

        return $res ? $res[0] : false;
    }

    public function createRecipientAccount(array $data): string
    {
        $fullName = trim((string)($data['full_name'] ?? ''));
        $nic = trim((string)($data['nic'] ?? ''));
        $hospitalReg = trim((string)($data['hospital_registration_no'] ?? ''));
        $requestedReg = trim((string)($data['registration_number'] ?? ''));

        if ($fullName === '' || $nic === '' || $hospitalReg === '') {
            throw new \InvalidArgumentException('Missing required fields');
        }

        if (!$this->usersRoleSupportsAftercare()) {
            throw new \RuntimeException("Database schema is missing users.role value AFTERCARE_PATIENT. Please apply the latest main.sql / run the migration.");
        }

        $existing = $this->getByNic($nic);
        if ($existing) {
            throw new \RuntimeException('A recipient aftercare account already exists for this NIC.');
        }

        $userModel = new UserModel();
        $passwordHash = password_hash($nic, PASSWORD_DEFAULT);

        $create = function (string $registrationNumber) use ($userModel, $passwordHash, $nic, $fullName, $hospitalReg, $data): string {
            if ($userModel->usernameExists($registrationNumber)) {
                throw new \RuntimeException('That registration number is already in use.');
            }

            $userId = $userModel->insert([
                'username' => $registrationNumber,
                'password_hash' => $passwordHash,
                'role' => 'RECIPIENT_PATIENT',
                'status' => 'PENDING',
                'must_change_credentials' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            if (!$userId) {
                throw new \RuntimeException('Failed to create user account.');
            }

            $userIdInt = (int)$userId;

            try {
                $aftercareRow = [
                    'registration_number' => $registrationNumber,
                    'nic' => $nic,
                    'full_name' => $fullName,
                    'patient_type' => 'RECIPIENT',
                    'hospital_registration_no' => $hospitalReg,
                    'status' => 'PENDING',
                ];

                if ($this->aftercareHasColumn('user_id')) {
                    $aftercareRow['user_id'] = $userIdInt;
                }


                $aftercareId = $this->insert($aftercareRow, $this->table);
                if (!$aftercareId) {
                    throw new \RuntimeException('Failed to create aftercare patient profile.');
                }

                $this->insertRecipientPatientRow($registrationNumber, $nic, $fullName, $hospitalReg, $data);

                return $registrationNumber;
            } catch (\Throwable $e) {
                // Best-effort cleanup
                $this->query(
                    "DELETE FROM {$this->table} WHERE registration_number = :rn LIMIT 1",
                    [':rn' => $registrationNumber]
                );
                $userModel->query(
                    "DELETE FROM users WHERE id = :id LIMIT 1",
                    [':id' => $userIdInt]
                );

                if ($e instanceof \PDOException && (string)$e->getCode() === '23000') {
                    throw $e;
                }
                throw $e;
            }
        };

        $year = (int)date('Y');

        if ($requestedReg !== '') {
            if (!preg_match('/^REG-\d{4}-\d{4}$/', $requestedReg)) {
                throw new \RuntimeException('Invalid registration number format. Use REG-YYYY-0001.');
            }
            if ($this->getByRegistrationNumber($requestedReg)) {
                throw new \RuntimeException('That registration number is already in use.');
            }

            try {
                return $create($requestedReg);
            } catch (\PDOException $e) {
                if ((string)$e->getCode() === '23000') {
                    throw new \RuntimeException('That registration number or NIC is already in use.');
                }
                throw $e;
            }
        }

        $attempts = 0;
        while ($attempts < 10) {
            $attempts++;
            $registrationNumber = $this->generateNextRegistrationNumber($year);

            // Avoid collisions with existing users usernames
            if ($userModel->usernameExists($registrationNumber)) {
                continue;
            }

            try {
                return $create($registrationNumber);
            } catch (\PDOException $e) {
                if ((string)$e->getCode() === '23000') {
                    continue;
                }
                throw $e;
            }
        }

        throw new \RuntimeException('Could not generate a unique registration number. Please try again.');
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

        $res = $this->query(
            "SELECT registration_number
             FROM {$this->table}
             WHERE registration_number LIKE :p
             ORDER BY registration_number DESC
             LIMIT 1",
            [':p' => $prefix . '%']
        );

        $nextSeq = 1;

        if ($res && !empty($res[0]->registration_number)) {
            $last = (string)$res[0]->registration_number;
            $parts = explode('-', $last);
            $lastSeq = (int)end($parts);
            if ($lastSeq > 0) $nextSeq = $lastSeq + 1;
        }

        return sprintf('REG-%d-%04d', $year, $nextSeq);
    }

    private function insertRecipientPatientRow(string $registrationNumber, string $nic, string $fullName, string $hospitalReg, array $data): void
    {
        $this->query(
            "INSERT INTO {$this->recipientTable} (
                registration_number,
                nic,
                full_name,
                hospital_registration_no,
                age,
                gender,
                blood_group,
                contact_details,
                medical_details,
                surgery_type,
                surgery_date,
                status,
                created_at
            ) VALUES (
                :rn,
                :nic,
                :full_name,
                :hosp,
                :age,
                :gender,
                :blood_group,
                :contact_details,
                :medical_details,
                :surgery_type,
                :surgery_date,
                'PENDING',
                NOW()
            )",
            [
                ':rn' => $registrationNumber,
                ':nic' => $nic,
                ':full_name' => $fullName,
                ':hosp' => $hospitalReg,
                ':age' => $data['age'] ?? null,
                ':gender' => $data['gender'] ?? null,
                ':blood_group' => $data['blood_group'] ?? null,
                ':contact_details' => $data['contact_details'] ?? null,
                ':medical_details' => $data['medical_details'] ?? null,
                ':surgery_type' => $data['surgery_type'] ?? null,
                ':surgery_date' => $data['surgery_date'] ?? null,
            ]
        );
    }

    /**
     * Save donor patient details into aftercare_patients (no login intended).
     * If donor already exists by NIC, updates their profile fields.
     */
    public function upsertDonorPatient(array $data, ?int $userId = null): string
    {
        $fullName = trim((string)($data['full_name'] ?? ''));
        $nic = trim((string)($data['nic'] ?? ''));
        $hospitalReg = trim((string)($data['hospital_registration_no'] ?? ''));

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
            return (string)($existing->registration_number ?? '');
        }

        $year = (int)date('Y');
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
                $code = (string)$e->getCode();
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
                r.medical_details 
             FROM {$this->table} a
             JOIN {$this->recipientTable} r ON a.registration_number = r.registration_number
             WHERE a.hospital_registration_no = :hosp AND a.patient_type = 'RECIPIENT' 
             ORDER BY a.created_at DESC",
            [':hosp' => $hospitalReg]
        );
    }
}
