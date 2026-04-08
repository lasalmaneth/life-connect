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
            address, district, contact_person_name, contact_person_phone, 
            verification_status
        ) VALUES (
            :user_id, :name, :university, :ugc_number,
            :address, :district, :contact_name, :contact_phone, 
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

    public function getDashboardStats($schoolId)
    {
        // Pre-Death Consents
        $prePending = $this->query("SELECT COUNT(*) as c FROM body_donation_consents bdc JOIN donors d ON bdc.donor_id = d.id WHERE bdc.medical_school_id = :s AND d.pledge_type IN ('LIVING', 'DECEASED_BODY') AND d.consent_status = 'PENDING'", [':s' => $schoolId])[0]->c ?? 0;
        $preAccepted = $this->query("SELECT COUNT(*) as c FROM body_donation_consents bdc JOIN donors d ON bdc.donor_id = d.id WHERE bdc.medical_school_id = :s AND d.pledge_type IN ('LIVING', 'DECEASED_BODY') AND d.consent_status = 'GIVEN'", [':s' => $schoolId])[0]->c ?? 0;
        $preWithdrawn = $this->query("SELECT COUNT(*) as c FROM body_donation_consents bdc JOIN donors d ON bdc.donor_id = d.id WHERE bdc.medical_school_id = :s AND d.consent_status = 'WITHDRAWN'", [':s' => $schoolId])[0]->c ?? 0;
        $preRejected = 0; // Not fully modeled in schema yet, hardcoding 0

        // Post-Death Submissions
        $postPending = $this->query("SELECT COUNT(*) as c FROM body_donation_consents bdc JOIN donors d ON bdc.donor_id = d.id LEFT JOIN body_usage_logs bul ON d.id = bul.donor_id AND bul.medical_school_id = :s WHERE bdc.medical_school_id = :s AND d.pledge_type = 'DECEASED_BODY' AND d.consent_status = 'GIVEN' AND bul.id IS NULL", [':s' => $schoolId])[0]->c ?? 0;
        
        $bodiesAccepted = $this->query("SELECT COUNT(*) as c FROM body_usage_logs WHERE medical_school_id = :s", [':s' => $schoolId])[0]->c ?? 0; // All time accepted
        $inUse = $this->query("SELECT COUNT(*) as c FROM body_usage_logs WHERE medical_school_id = :s AND status = 'IN_USE'", [':s' => $schoolId])[0]->c ?? 0;
        $totalDonations = $bodiesAccepted;

        return [
            'pre_pending' => $prePending,
            'pre_accepted' => $preAccepted,
            'pre_withdrawn' => $preWithdrawn,
            'pre_rejected' => $preRejected,
            'post_pending' => $postPending,
            'bodies_accepted' => $bodiesAccepted,
            'in_use' => $inUse,
            'total_donations' => $totalDonations
        ];
    }

    public function getPreDeathConsents($schoolId)
    {
        $query = "SELECT d.id, d.first_name, d.last_name, d.nic_number, d.verification_status, d.consent_status, bdc.consent_date, bdc.id as consent_id
                  FROM body_donation_consents bdc
                  JOIN donors d ON bdc.donor_id = d.id
                  WHERE bdc.medical_school_id = :school_id
                  AND d.pledge_type IN ('LIVING', 'DECEASED_BODY')
                  AND d.consent_status IN ('PENDING', 'GIVEN')
                  ORDER BY bdc.consent_date DESC";
        
        $result = $this->query($query, [':school_id' => $schoolId]);
        return $result ? $result : [];
    }

    public function getWithdrawnConsents($schoolId)
    {
        $query = "SELECT d.id, d.first_name, d.last_name, d.nic_number, d.opt_out_reason, bdc.consent_date
                  FROM body_donation_consents bdc
                  JOIN donors d ON bdc.donor_id = d.id
                  WHERE bdc.medical_school_id = :school_id
                  AND d.consent_status = 'WITHDRAWN'
                  ORDER BY bdc.consent_date DESC";
                  
        $result = $this->query($query, [':school_id' => $schoolId]);
        return $result ? $result : [];
    }

    public function getPostDeathSubmissions($schoolId)
    {
        // For post-death, the donor must have a passed date of death OR be marked as DECEASED_BODY
        // And they shouldn't already be in the body_usage_logs as accepted
        $query = "SELECT d.id, d.first_name, d.last_name, d.nic_number, d.consent_status,
                         nok.name as custodian_name, bdc.consent_date
                  FROM body_donation_consents bdc
                  JOIN donors d ON bdc.donor_id = d.id
                  LEFT JOIN next_of_kin nok ON d.id = nok.donor_id
                  LEFT JOIN body_usage_logs bul ON d.id = bul.donor_id AND bul.medical_school_id = :school_id
                  WHERE bdc.medical_school_id = :school_id
                  AND d.pledge_type = 'DECEASED_BODY'
                  AND d.consent_status = 'GIVEN'
                  AND bul.id IS NULL
                  ORDER BY bdc.consent_date DESC";
                  
        $result = $this->query($query, [':school_id' => $schoolId]);
        return $result ? $result : [];
    }

    public function getAcceptedBodies($schoolId)
    {
        // Donors who have an active usage log
        $query = "SELECT d.id, d.first_name, d.last_name, d.nic_number, bul.usage_date, bul.status
                  FROM body_usage_logs bul
                  JOIN donors d ON bul.donor_id = d.id
                  WHERE bul.medical_school_id = :school_id
                  AND bul.status = 'IN_USE'
                  ORDER BY bul.usage_date DESC";
                  
        $result = $this->query($query, [':school_id' => $schoolId]);
        return $result ? $result : [];
    }

    public function getActiveLabUsageMatrix($schoolId)
    {
        $query = "SELECT usage_type, COUNT(*) as count 
                  FROM body_usage_logs 
                  WHERE medical_school_id = :school_id 
                  AND status = 'IN_USE' 
                  GROUP BY usage_type
                  ORDER BY count DESC";
        $result = $this->query($query, [':school_id' => $schoolId]);
        return $result ? $result : [];
    }

    public function getIntakeQuotaMetrics($schoolId)
    {
        // Define a static quota of 20 bodies per semester logic for now.
        $query = "SELECT COUNT(*) as current_intake 
                  FROM body_usage_logs 
                  WHERE medical_school_id = :school_id
                  AND created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)";
        
        $intake = $this->query($query, [':school_id' => $schoolId])[0]->current_intake ?? 0;
        $quota = 20; // Simulated constant semantic requirement
        
        return [
            'intake' => $intake,
            'quota' => $quota,
            'remaining' => max(0, $quota - $intake)
        ];
    }

    public function getArchivedRecords($schoolId)
    {
        // Disposed bodies or archived records
        $query = "SELECT d.id, d.first_name, d.last_name, d.nic_number, bul.disposal_date as archive_date, bul.disposal_method as reason
                  FROM body_usage_logs bul
                  JOIN donors d ON bul.donor_id = d.id
                  WHERE bul.medical_school_id = :school_id
                  AND bul.status = 'DISPOSED'
                  ORDER BY bul.disposal_date DESC";
                  
        $result = $this->query($query, [':school_id' => $schoolId]);
        return $result ? $result : [];
    }
    
    public function getDonorDetailsById($donorId, $schoolId)
    {
        $query = "SELECT 
                    d.*,
                    u.email,
                    u.phone,
                    bdc.consent_date,
                    bdc.witness1_name,
                    bdc.witness2_name,
                    nok.name as nok_name,
                    nok.relationship as nok_relationship,
                    nok.contact_number as nok_phone
                  FROM donors d
                  JOIN users u ON d.user_id = u.id
                  JOIN body_donation_consents bdc ON d.id = bdc.donor_id
                  LEFT JOIN next_of_kin nok ON d.id = nok.donor_id
                  WHERE d.id = :donor_id AND bdc.medical_school_id = :school_id
                  LIMIT 1";
                  
        $result = $this->query($query, [
            ':donor_id' => $donorId,
            ':school_id' => $schoolId
        ]);
        
        return ($result && count($result) > 0) ? $result[0] : false;
    }

    public function addUsageLog($data)
    {
        $keys = array_keys($data);
        $query = "INSERT INTO body_usage_logs (" . implode(",", $keys) . ") VALUES (:" . implode(",:", $keys) . ")";
        
        // Prepare data with colons
        $params = [];
        foreach ($data as $key => $value) {
            $params[':' . $key] = $value;
        }
        
        return $this->query($query, $params);
    }

    public function getUsageLogs($donorId, $schoolId)
    {
        $query = "SELECT * FROM body_usage_logs WHERE donor_id = :donor_id AND medical_school_id = :school_id ORDER BY created_at DESC";
        return $this->query($query, [
            ':donor_id' => $donorId,
            ':school_id' => $schoolId
        ]);
    }

    public function updateUsageLog($id, $data)
    {
        $fields = [];
        $params = [':id' => $id];
        
        foreach ($data as $key => $value) {
            $fields[] = "$key = :$key";
            $params[":$key"] = $value;
        }
        
        $query = "UPDATE body_usage_logs SET " . implode(", ", $fields) . " WHERE id = :id";
        return $this->query($query, $params);
    }
}
