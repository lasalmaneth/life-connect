<?php

namespace App\Models;

use App\Core\Database;

class HomeModel {
    use Database;

    public function getHomepageStats()
    {
        $stats = new \stdClass();

        // 1. Registered Donors (Approved only)
        $q1 = "SELECT COUNT(*) as count FROM donors WHERE verification_status = 'APPROVED'";
        $res1 = $this->query($q1);
        $stats->donor_count = $res1 ? $res1[0]->count : 0;

        // 2. Lives Saved (Successful Transplants via recipients table)
        // We use the count of surgical entries which indicates a life impacted by donation
        $q2 = "SELECT COUNT(*) as count FROM recipients";
        $res2 = $this->query($q2);
        $stats->lives_saved = $res2 ? $res2[0]->count : 0;

        // 3. Partner Hospitals (Approved only)
        $q3 = "SELECT COUNT(*) as count FROM hospitals WHERE verification_status = 'APPROVED'";
        $res3 = $this->query($q3);
        $stats->hospital_count = $res3 ? $res3[0]->count : 0;

        // 4. Medical Schools (Approved only)
        $q4 = "SELECT COUNT(*) as count FROM medical_schools WHERE verification_status = 'APPROVED'";
        $res4 = $this->query($q4);
        $stats->medical_school_count = $res4 ? $res4[0]->count : 0;

        return $stats;
    }
}
