<?php
require '../app/Core/config.php';
require '../app/Core/Database.php';

class MasterSeeder {
    use App\Core\Database;

    public function seed() {
        echo "<style>body { font-family: sans-serif; line-height: 1.6; padding: 20px; background: #f4f7f6; } .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px; border-left: 5px solid #2563eb; } .alert { background: #dcfce7; color: #166534; padding: 15px; border-radius: 6px; margin-bottom: 20px; } </style>";
        echo "<h1>Final Master Seeder: 100% Real Backend Data</h1>";

        // Scenario 1: Kidney Active (Deceased Intent) + Liver Successful (Living Outcome)
        $this->createScenario(
            'liver_kidney@test.com', 'Scenario', 'Liver Kidney', '111111111V',
            [ '2025-01-01' => [1] ], // ONLY Kidney (ID 1)
            [ '2020-01-01' => [1, 'WITHDRAWN'] ], // Body Colombo (Withdrawn)
            true // WITH LIVER SUCCESS (Living Outcome)
        );

        // Scenario 2: Cornea & Organs (Organ Winner) + History
        $this->createScenario(
            'cornea_organs@test.com', 'Scenario', 'Cornea Organs', '222222222V',
            [ '2025-01-01' => [1, 5], '2023-01-01' => [5] ], // Kidney, Cornea
            [ '2019-01-01' => [3, 'ACTIVE'] ] // Body Kelaniya (ID 3)
        );

        // Scenario 3: Body Kelaniya (Body Winner) + History
        $this->createScenario(
            'kelaniya_multi@test.com', 'Scenario', 'Kelaniya Multi', '333333333V',
            [ '2021-01-01' => [1, 2] ], // Old Organs
            [ '2025-05-01' => [3, 'ACTIVE'], '2024-05-01' => [1, 'ACTIVE'] ] // Kelaniya, Colombo
        );

        // Scenario 4: Split Path (Body Kelaniya + Cornea) + Deep History
        $this->createScenario(
            'split_deep@test.com', 'Scenario', 'Split Deep', '444444444V',
            [ '2023-01-01' => [5], '2018-01-01' => [1] ], // Cornea, Old Kidney
            [ '2025-06-01' => [3, 'ACTIVE'], '2020-01-01' => [1, 'WITHDRAWN'] ] // Kelaniya, Colombo
        );

        // Scenario 5: Ultimate Testing Account (Kidney + Cornea + Others) -> No Body
        $this->createScenario(
            'test_kidney_all@test.com', 'Testing', 'Kidney Plus', '999999999V',
            [ '2025-01-01' => [9, 4, 7] ] // Kidney After Death (9), Cornea (4), Heart Valves (7)
        );

        echo "<div class='alert'><b>Database Seeded Successfully!</b> All personas are now live.</div>";
        echo "<h2>Custodian Credentials (Password: password123)</h2>";
        $this->printCard('liver_kidney@test.com', 'Organ Dominant (Liver, Kidney Active)');
        $this->printCard('cornea_organs@test.com', 'Organ Dominant (Cornea & Organs Active)');
        $this->printCard('kelaniya_multi@test.com', 'Body Dominant (Kelaniya, Colombo Active)');
        $this->printCard('split_deep@test.com', 'Split Dominant (Kelaniya + Cornea Active)');
        $this->printCard('test_kidney_all@test.com', 'Ultimate Test: Kidney + Cornea + Others (NOT DEAD YET)');
    }

    private function createScenario($email, $fn, $ln, $nic, $organs = [], $bodies = [], $withLivingSuccess = false) {
        // ... (User, Donor, Custodian inserts remain same)
        $stmt = $this->query("SELECT id FROM users WHERE email = :email", [':email' => $email]);
        $uid = $stmt[0]->id ?? null;
        $passwordHash = password_hash('password123', PASSWORD_DEFAULT);
        $username = strstr($email, '@', true);

        if ($uid) {
            $this->query("UPDATE users SET username = :u, password_hash = :p, status = 'ACTIVE' WHERE id = :uid",
                [':u' => $username, ':p' => $passwordHash, ':uid' => $uid]);
        } else {
            $this->query("INSERT INTO users (username, email, password_hash, role, status) VALUES (:u, :e, :p, 'CUSTODIAN', 'ACTIVE')", 
                [':u' => $username, ':e' => $email, ':p' => $passwordHash]);
            $uid = $this->getLastInsertId();
        }

        $this->query("DELETE FROM donors WHERE nic_number = :nic", [':nic' => $nic]);
        $this->query("INSERT INTO donors (user_id, first_name, last_name, nic_number, gender, date_of_birth, pledge_type) VALUES (:uid, :fn, :ln, :nic, 'Male', '1980-01-01', 'BODY_ONLY')",
            [':uid' => $uid, ':fn' => $fn, ':ln' => $ln, ':nic' => $nic]);
        $did = (int)$this->query("SELECT id FROM donors WHERE nic_number = :nic", [':nic' => $nic])[0]->id;

        $this->query("DELETE FROM custodians WHERE user_id = :uid", [':uid' => $uid]);
        $this->query("INSERT INTO custodians (user_id, donor_id, name, relationship, phone, email) VALUES (:uid, :did, :n, 'Sibling', '0771234567', :e)",
            [':uid' => $uid, ':did' => $did, ':n' => $fn.' '.$ln, ':e' => $email]);

        $this->query("DELETE FROM donor_pledges WHERE donor_id = :did", [':did' => $did]);
        $this->query("DELETE FROM body_donation_consents WHERE donor_id = :did", [':did' => $did]);
        $this->query("DELETE FROM donation_medical_history WHERE donor_id = :did", [':did' => $did]);

        foreach ($organs as $date => $ids) {
            foreach ($ids as $oid) {
                $this->query("INSERT INTO donor_pledges (donor_id, organ_id, pledge_date, status, signed_form_path) VALUES (:did, :oid, :dt, 'APPROVED', 'dummy_organ_form.pdf')",
                    [':did' => $did, ':oid' => $oid, ':dt' => $date]);
            }
        }

        foreach ($bodies as $date => $data) {
            $sid = $data[0]; $status = $data[1];
            $this->query("INSERT INTO body_donation_consents (donor_id, medical_school_id, consent_date, status) VALUES (:did, :sid, :dt, :s)",
                [':did' => $did, ':sid' => $sid, ':dt' => $date, ':s' => $status]);
            $this->query("INSERT INTO donor_pledges (donor_id, organ_id, pledge_date, status, signed_form_path) VALUES (:did, 10, :dt, 'APPROVED', 'dummy_body_form.pdf')",
                [':did' => $did, ':dt' => $date]);
        }

        if ($withLivingSuccess) {
            $this->query("INSERT INTO donation_medical_history (donor_id, donated_organ, hospital_id, donation_date, recovery_status) VALUES (:did, 'Liver Portion', 1, '2024-02-12', 'COMPLETED')", [':did' => $did]);
        }
    }

    private function getLastInsertId() { return $this->query("SELECT LAST_INSERT_ID() as id")[0]->id; }
    private function printCard($email, $desc) { echo "<div class='card'><b>$desc</b><br>Email: $email<br>Password: password123</div>"; }
}

$s = new MasterSeeder(); $s->seed();
