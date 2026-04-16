<?php

namespace App\Models;

use App\Core\Model;

class HomeModel {
    use Model;

    public function getHomepageStats()
    {
        $stats = new \stdClass();

        // 1. Registered Donors (Approved only)
        $stats->donor_count = $this->count(['verification_status' => 'APPROVED'], [], 'donors');

        // 2. Lives Saved (Successful Transplants via recipient_patient table)
        // We use the count of surgical entries which indicates a life impacted by donation
        $stats->lives_saved = $this->count([], [], 'recipient_patient');

        // 3. Partner Hospitals (Approved only)
        $stats->hospital_count = $this->count(['verification_status' => 'APPROVED'], [], 'hospitals');

        // 4. Medical Schools (Approved only)
        $stats->medical_school_count = $this->count(['verification_status' => 'APPROVED'], [], 'medical_schools');

        return $stats;
    }
}
