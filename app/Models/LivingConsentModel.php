<?php

namespace App\Models;

use App\Core\Database;

class LivingConsentModel {
    use Database;

    protected $table = 'living_donor_consents';

    /**
     * Create a new living donation consent record
     */
    public function createConsent($data)
    {
        $query = "INSERT INTO living_donor_consents (
            donor_pledge_id, height, weight, previous_surgeries, smoking_alcohol_status,
            blood_compatibility, tissue_typing, medical_clearance_status,
            emergency_contact_name, emergency_relationship, emergency_phone
        ) VALUES (
            :donor_pledge_id, :height, :weight, :previous_surgeries, :smoking_alcohol_status,
            :blood_compatibility, :tissue_typing, :medical_clearance_status,
            :emergency_contact_name, :emergency_relationship, :emergency_phone
        )";

        return $this->insert($query, [
            ':donor_pledge_id'        => $data['donor_pledge_id'],
            ':height'                 => $data['height'] ?? null,
            ':weight'                 => $data['weight'] ?? null,
            ':previous_surgeries'      => $data['previous_surgeries'] ?? null,
            ':smoking_alcohol_status'  => $data['smoking_alcohol_status'] ?? null,
            ':blood_compatibility'    => $data['blood_compatibility'] ?? null,
            ':tissue_typing'          => $data['tissue_typing'] ?? null,
            ':medical_clearance_status' => $data['medical_clearance_status'] ?? 'Pending',
            ':emergency_contact_name'  => $data['emergency_contact_name'] ?? null,
            ':emergency_relationship'  => $data['emergency_relationship'] ?? null,
            ':emergency_phone'         => $data['emergency_phone'] ?? null
        ]);
    }

    /**
     * Get living consent by pledge ID
     */
    public function getConsentByPledgeId($pledgeId)
    {
        $query = "SELECT * FROM living_donor_consents WHERE donor_pledge_id = :pledge_id";
        $result = $this->query($query, [':pledge_id' => $pledgeId]);
        return $result ? $result[0] : null;
    }

    /**
     * Update living consent
     */
    public function updateConsent($pledgeId, $data)
    {
        $query = "UPDATE living_donor_consents SET 
            height = :height,
            weight = :weight,
            previous_surgeries = :previous_surgeries,
            smoking_alcohol_status = :smoking_alcohol_status,
            blood_compatibility = :blood_compatibility,
            tissue_typing = :tissue_typing,
            medical_clearance_status = :medical_clearance_status,
            emergency_contact_name = :emergency_contact_name,
            emergency_relationship = :emergency_relationship,
            emergency_phone = :emergency_phone
            WHERE donor_pledge_id = :pledge_id";

        return $this->query($query, [
            ':height'                 => $data['height'] ?? null,
            ':weight'                 => $data['weight'] ?? null,
            ':previous_surgeries'      => $data['previous_surgeries'] ?? null,
            ':smoking_alcohol_status'  => $data['smoking_alcohol_status'] ?? null,
            ':is_recipient_known'     => $data['is_recipient_known'] ?? 'No',
            ':recipient_name'         => $data['recipient_name'] ?? null,
            ':recipient_relationship' => $data['recipient_relationship'] ?? null,
            ':recipient_hospital'     => $data['recipient_hospital'] ?? null,
            ':blood_compatibility'    => $data['blood_compatibility'] ?? null,
            ':tissue_typing'          => $data['tissue_typing'] ?? null,
            ':medical_clearance_status' => $data['medical_clearance_status'] ?? 'Pending',
            ':emergency_contact_name'  => $data['emergency_contact_name'] ?? null,
            ':emergency_relationship'  => $data['emergency_relationship'] ?? null,
            ':emergency_phone'         => $data['emergency_phone'] ?? null,
            ':pledge_id'              => $pledgeId
        ]);
    }
}
