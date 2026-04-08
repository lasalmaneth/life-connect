<?php

namespace App\Models;

use App\Core\Database;

class BodyDonationModel {
    use Database;

    protected $table = 'body_donation_consents';

    /**
     * Create a new body donation consent record
     */
    public function createConsent($donorId, $data) {
        $params = [
            ':donor_id' => $donorId,
            ':status'   => $data['status'] ?? 'ACTIVE',
            ':w1_name'  => $data['witness1_name'] ?? '',
            ':w1_nic'   => $data['witness1_nic'] ?? '',
            ':w1_phone' => $data['witness1_phone'] ?? '',
            ':w1_address'=> $data['witness1_address'] ?? '',
            ':w2_name'  => $data['witness2_name'] ?? '',
            ':w2_nic'   => $data['witness2_nic'] ?? '',
            ':w2_phone' => $data['witness2_phone'] ?? '',
            ':w2_address'=> $data['witness2_address'] ?? '',
            ':school_id'=> !empty($data['medical_school_id']) ? $data['medical_school_id'] : null
        ];

        // We always insert a new record to maintain history
        $query = "INSERT INTO body_donation_consents (
                  donor_id, status,
                  witness1_name, witness1_nic, witness1_phone, witness1_address,
                  witness2_name, witness2_nic, witness2_phone, witness2_address,
                  medical_school_id
                  ) VALUES (
                  :donor_id, :status,
                  :w1_name, :w1_nic, :w1_phone, :w1_address,
                  :w2_name, :w2_nic, :w2_phone, :w2_address,
                  :school_id
                  )";
        return $this->insert($query, $params);
    }

    public function withdrawConsent($donorId) {
        $query = "UPDATE body_donation_consents SET status = 'WITHDRAWN' WHERE donor_id = :donor_id AND status = 'ACTIVE'";
        return $this->query($query, [':donor_id' => $donorId]);
    }


    /**
     * Get consent record by donor ID
     */
    public function getConsentByDonorId($donorId) {
        $query = "SELECT * FROM body_donation_consents WHERE donor_id = :donor_id AND status = 'ACTIVE' ORDER BY consent_date DESC LIMIT 1";
        $result = $this->query($query, [':donor_id' => $donorId]);
        return $result ? $result[0] : null;
    }

    public function getConsentHistoryByDonorId($donorId) {
        $query = "SELECT * FROM body_donation_consents WHERE donor_id = :donor_id ORDER BY consent_date DESC";
        return $this->query($query, [':donor_id' => $donorId]);
    }

}
