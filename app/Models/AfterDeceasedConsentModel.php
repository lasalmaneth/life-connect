<?php

namespace App\Models;

use App\Core\Database;

class AfterDeceasedConsentModel {
    use Database;

    protected $table = 'after_death_consents';

    /**
     * Create or Update a deceased consent record
     */
    public function saveConsent($data)
    {
        $donorId = $data['donor_id'];
        
        // Use REPLACE to handle create/update in one go if donor_id has a unique constraint,
        // but since we don't have a unique index on donor_id yet (it's MUL), we check first.
        $existing = $this->query("SELECT id FROM after_death_consents WHERE donor_id = :id", [':id' => $donorId]);
        
        if ($existing) {
            $query = "UPDATE after_death_consents SET 
                        suitability_any = :suit,
                        is_restricted = :restr,
                        religion = :rel,
                        special_instructions = :inst,
                        preferred_hospital_id = :hosp,
                        witness_name = :wname,
                        witness_nic = :wnic
                      WHERE donor_id = :donor_id";
        } else {
            $query = "INSERT INTO after_death_consents (
                        donor_id, suitability_any, is_restricted, religion, special_instructions,
                        preferred_hospital_id, witness_name, witness_nic
                      ) VALUES (
                        :donor_id, :suit, :restr, :rel, :inst, :hosp, :wname, :nic
                      )";
        }

        $params = [
            ':donor_id' => $donorId,
            ':suit' => $data['suitability_any'] ?? 1,
            ':restr' => $data['is_restricted'] ?? 0,
            ':rel' => $data['religion'] ?? null,
            ':inst' => $data['special_instructions'] ?? null,
            ':hosp' => $data['preferred_hospital_id'] ?? null,
            ':wname' => $data['witness_name'] ?? null,
        ];

        if ($existing) {
            $params[':wnic'] = $data['witness_nic'] ?? null;
        } else {
            $params[':nic'] = $data['witness_nic'] ?? null;
        }

        return $this->query($query, $params);
    }

    /**
     * Get consent record by donor ID
     */
    public function getConsentByDonorId($donorId)
    {
        $query = "SELECT * FROM after_death_consents WHERE donor_id = :id";
        $res = $this->query($query, [':id' => $donorId]);
        return $res ? $res[0] : null;
    }
}
