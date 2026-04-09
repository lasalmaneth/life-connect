<?php

namespace App\Models;

use App\Core\Model;

class AftercarePatientModel
{
    use Model;

    protected $table = 'aftercare_patients';
    private string $recipientTable = 'recipient_patient';

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

        $existing = $this->getByNic($nic);
        if ($existing) {
            throw new \RuntimeException('A recipient aftercare account already exists for this NIC.');
        }

        $passwordHash = password_hash($nic, PASSWORD_DEFAULT);

        $year = (int)date('Y');
        $attempts = 0;

        if ($requestedReg !== '') {
            // Basic format validation: REG-YYYY-0001
            if (!preg_match('/^REG-\d{4}-\d{4}$/', $requestedReg)) {
                throw new \RuntimeException('Invalid registration number format. Use REG-YYYY-0001.');
            }

            if ($this->getByRegistrationNumber($requestedReg)) {
                throw new \RuntimeException('That registration number is already in use.');
            }

            try {
                $this->query(
                    "INSERT INTO {$this->table} (
                        registration_number,
                        nic,
                        full_name,
                        patient_type,
                        hospital_registration_no,
                        password_hash,
                        must_change_password,
                        age,
                        gender,
                        blood_group,
                        contact_details,
                        medical_details,
                        status,
                        created_at
                    ) VALUES (
                        :rn,
                        :nic,
                        :full_name,
                        'RECIPIENT',
                        :hosp,
                        :ph,
                        1,
                        :age,
                        :gender,
                        :blood_group,
                        :contact_details,
                        :medical_details,
                        'ACTIVE',
                        NOW()
                    )",
                    [
                        ':rn' => $requestedReg,
                        ':nic' => $nic,
                        ':full_name' => $fullName,
                        ':hosp' => $hospitalReg,
                        ':ph' => $passwordHash,
                        ':age' => $data['age'] ?? null,
                        ':gender' => $data['gender'] ?? null,
                        ':blood_group' => $data['blood_group'] ?? null,
                        ':contact_details' => $data['contact_details'] ?? null,
                        ':medical_details' => $data['medical_details'] ?? null,
                    ]
                );

                try {
                    $this->insertRecipientPatientRow($requestedReg, $nic, $fullName, $hospitalReg, $data);
                } catch (\PDOException $e2) {
                    // Roll back the aftercare row if we can't record recipient details
                    $this->query(
                        "DELETE FROM {$this->table} WHERE registration_number = :rn AND nic = :nic LIMIT 1",
                        [':rn' => $requestedReg, ':nic' => $nic]
                    );
                    $code2 = (string)$e2->getCode();
                    if ($code2 === '23000') {
                        throw new \RuntimeException('Recipient details already exist for this NIC/registration number.');
                    }
                    throw $e2;
                }

                return $requestedReg;
            } catch (\PDOException $e) {
                $code = (string)$e->getCode();
                if ($code === '23000') {
                    throw new \RuntimeException('That registration number or NIC is already in use.');
                }
                throw $e;
            }
        }

        while ($attempts < 10) {
            $attempts++;
            $registrationNumber = $this->generateNextRegistrationNumber($year);

            try {
                $this->query(
                    "INSERT INTO {$this->table} (
                        registration_number,
                        nic,
                        full_name,
                        patient_type,
                        hospital_registration_no,
                        password_hash,
                        must_change_password,
                        age,
                        gender,
                        blood_group,
                        contact_details,
                        medical_details,
                        status,
                        created_at
                    ) VALUES (
                        :rn,
                        :nic,
                        :full_name,
                        'RECIPIENT',
                        :hosp,
                        :ph,
                        1,
                        :age,
                        :gender,
                        :blood_group,
                        :contact_details,
                        :medical_details,
                        'ACTIVE',
                        NOW()
                    )",
                    [
                        ':rn' => $registrationNumber,
                        ':nic' => $nic,
                        ':full_name' => $fullName,
                        ':hosp' => $hospitalReg,
                        ':ph' => $passwordHash,
                        ':age' => $data['age'] ?? null,
                        ':gender' => $data['gender'] ?? null,
                        ':blood_group' => $data['blood_group'] ?? null,
                        ':contact_details' => $data['contact_details'] ?? null,
                        ':medical_details' => $data['medical_details'] ?? null,
                    ]
                );

                try {
                    $this->insertRecipientPatientRow($registrationNumber, $nic, $fullName, $hospitalReg, $data);
                } catch (\PDOException $e2) {
                    // Roll back the aftercare row if we can't record recipient details
                    $this->query(
                        "DELETE FROM {$this->table} WHERE registration_number = :rn AND nic = :nic LIMIT 1",
                        [':rn' => $registrationNumber, ':nic' => $nic]
                    );
                    $code2 = (string)$e2->getCode();
                    if ($code2 === '23000') {
                        throw new \RuntimeException('Recipient details already exist for this NIC/registration number.');
                    }
                    throw $e2;
                }

                return $registrationNumber;
            } catch (\PDOException $e) {
                // If unique constraint fails (registration_number or nic), retry.
                // Otherwise rethrow.
                $code = (string)$e->getCode();
                if ($code === '23000') {
                    continue;
                }
                throw $e;
            }
        }

        throw new \RuntimeException('Could not generate a unique registration number. Please try again.');
    }

    public function updatePassword(int $patientId, string $newPasswordHash): bool
    {
        $patientId = (int)$patientId;
        if ($patientId <= 0) return false;

        $this->query(
            "UPDATE {$this->table}
             SET password_hash = :ph, must_change_password = 0, updated_at = NOW()
             WHERE id = :id LIMIT 1",
            [':ph' => $newPasswordHash, ':id' => $patientId]
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
                'ACTIVE',
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
            ]
        );
    }

    /**
     * Save donor patient details into aftercare_patients (no login intended).
     * If donor already exists by NIC, updates their profile fields.
     */
    public function upsertDonorPatient(array $data): string
    {
        $fullName = trim((string)($data['full_name'] ?? ''));
        $nic = trim((string)($data['nic'] ?? ''));
        $hospitalReg = trim((string)($data['hospital_registration_no'] ?? ''));

        if ($fullName === '' || $nic === '' || $hospitalReg === '') {
            throw new \InvalidArgumentException('Missing required fields');
        }

        $existing = $this->getByNic($nic);
        if ($existing) {
            $this->query(
                "UPDATE {$this->table}
                 SET full_name = :full_name,
                     hospital_registration_no = :hosp,
                     age = :age,
                     gender = :gender,
                     blood_group = :blood_group,
                     contact_details = :contact_details,
                     medical_details = :medical_details,
                     updated_at = NOW()
                 WHERE nic = :nic
                 LIMIT 1",
                [
                    ':full_name' => $fullName,
                    ':hosp' => $hospitalReg,
                    ':age' => $data['age'] ?? null,
                    ':gender' => $data['gender'] ?? null,
                    ':blood_group' => $data['blood_group'] ?? null,
                    ':contact_details' => $data['contact_details'] ?? null,
                    ':medical_details' => $data['medical_details'] ?? null,
                    ':nic' => $nic,
                ]
            );
            return (string)($existing->registration_number ?? '');
        }

        $year = (int)date('Y');
        $attempts = 0;
        while ($attempts < 10) {
            $attempts++;
            $registrationNumber = $this->generateNextRegistrationNumber($year);

            // Random password (not shared) to avoid unintended logins
            $passwordHash = password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT);

            try {
                $this->query(
                    "INSERT INTO {$this->table} (
                        registration_number,
                        nic,
                        full_name,
                        patient_type,
                        hospital_registration_no,
                        password_hash,
                        must_change_password,
                        age,
                        gender,
                        blood_group,
                        contact_details,
                        medical_details,
                        status,
                        created_at
                    ) VALUES (
                        :rn,
                        :nic,
                        :full_name,
                        'DONOR',
                        :hosp,
                        :ph,
                        0,
                        :age,
                        :gender,
                        :blood_group,
                        :contact_details,
                        :medical_details,
                        'ACTIVE',
                        NOW()
                    )",
                    [
                        ':rn' => $registrationNumber,
                        ':nic' => $nic,
                        ':full_name' => $fullName,
                        ':hosp' => $hospitalReg,
                        ':ph' => $passwordHash,
                        ':age' => $data['age'] ?? null,
                        ':gender' => $data['gender'] ?? null,
                        ':blood_group' => $data['blood_group'] ?? null,
                        ':contact_details' => $data['contact_details'] ?? null,
                        ':medical_details' => $data['medical_details'] ?? null,
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
}
