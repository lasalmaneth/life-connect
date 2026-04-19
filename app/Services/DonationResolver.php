<?php

namespace App\Services;

use App\Core\Database;

class DonationResolver
{
    use Database;

    const TRACK_NONE = 'NONE';
    const TRACK_KIDNEY_INFO_ONLY = 'KIDNEY_INFO_ONLY';
    const TRACK_KIDNEY_DECISION_REQUIRED = 'KIDNEY_DECISION_REQUIRED';
    const TRACK_HOSPITAL_TISSUE = 'HOSPITAL_TISSUE';
    const TRACK_BODY_ONLY = 'BODY_ONLY';
    const TRACK_BODY_CORNEA_DECISION_REQUIRED = 'BODY_CORNEA_DECISION_REQUIRED';
    const TRACK_BODY_CORNEA_SPLIT = 'BODY_CORNEA_SPLIT';
    const TRACK_NO_ACTIONABLE_ITEMS = 'NO_ACTIONABLE_ITEMS';
    const TRACK_DECISION_REQUIRED = 'DECISION_REQUIRED';

    /**
     * Resolve the clinical state based on active consents and death declaration.
     */
    public function resolveAtDeath($donorId, $isBrainDead, $timeOfDeath, $kidneyDecision = 'PENDING', $bodyCorneaDecision = 'PENDING')
    {
        $deathTs = is_numeric($timeOfDeath) ? $timeOfDeath : strtotime($timeOfDeath);
        $now = time();
        $hoursSinceDeath = ($now - $deathTs) / 3600;

        // 1. Fetch Real Database Records (Active Consents)
        $organQuery = "SELECT dp.organ_id, o.name as item_name, dp.pledge_date, dp.status 
                       FROM donor_pledges dp 
                       JOIN organs o ON dp.organ_id = o.id 
                       WHERE dp.donor_id = :did AND dp.status IN ('APPROVED', 'UPLOADED', 'IN_PROGRESS')
                       AND dp.organ_id != 10";
        $organPledges = $this->query($organQuery, [':did' => $donorId]) ?: [];

        $bodyQuery = "SELECT bdc.id, ms.school_name as item_name, bdc.consent_date, bdc.status 
                      FROM body_donation_consents bdc
                      JOIN medical_schools ms ON bdc.medical_school_id = ms.id
                      WHERE bdc.donor_id = :did AND bdc.status = 'ACTIVE'";
        $bodyConsents = $this->query($bodyQuery, [':did' => $donorId]) ?: [];

        // Flags
        $hasKidney = false;
        $hasCornea = false;
        $hasOther = false;
        $hasBody = !empty($bodyConsents);
        
        foreach ($organPledges as $p) {
            // IDs 1 and 9 are Kidney in this schema
            if (in_array($p->organ_id, [1, 9])) $hasKidney = true;
            // ID 4 is Cornea
            elseif ($p->organ_id == 4) $hasCornea = true;
            // Any other organ (ID 2 Liver, etc.) is 'Other'
            else $hasOther = true;
        }

        $items = [];
        $timeLimits = [];

        // Clinical Resolution for Items Hub
        if ($hasKidney) {
            $canKidney = ($isBrainDead == 1);
            $items['9'] = [
                'name' => 'Kidney',
                'type' => 'KIDNEY',
                'is_actionable' => $canKidney,
                'status' => $canKidney ? 'available' : 'unavailable',
                'reason' => $canKidney ? null : 'KIDNEY_REQUIRES_BRAIN_DEATH'
            ];
            $timeLimits['9'] = date('Y-m-d H:i:s', $deathTs + (48 * 3600)); // Default 48h for kidney window
        }

        if ($hasCornea) {
            $isExpired = ($hoursSinceDeath > 8);
            $items['4'] = [
                'name' => 'Cornea',
                'type' => 'HOSPITAL_TISSUE',
                'is_actionable' => !$isExpired,
                'status' => $isExpired ? 'expired' : 'available',
                'reason' => $isExpired ? 'EXPIRED_8H_WINDOW' : null
            ];
            $timeLimits['4'] = date('Y-m-d H:i:s', $deathTs + (8 * 3600));
        }

        foreach ($organPledges as $p) {
            if ($p->organ_id == 9 || $p->organ_id == 4) continue;
            $isExpired = ($hoursSinceDeath > 20); // Tissues/Organs generally 20h
            $items[$p->organ_id] = [
                'name' => $p->item_name,
                'type' => 'HOSPITAL_TISSUE',
                'is_actionable' => !$isExpired,
                'status' => $isExpired ? 'expired' : 'available',
                'reason' => $isExpired ? 'EXPIRED_20H_WINDOW' : null
            ];
            $timeLimits[$p->organ_id] = date('Y-m-d H:i:s', $deathTs + (20 * 3600));
        }

        if ($hasBody) {
            $isExpired = ($hoursSinceDeath > 48);
            foreach ($bodyConsents as $bc) {
                $items['BODY_' . $bc->id] = [
                    'name' => 'Whole Body Donation',
                    'type' => 'BODY',
                    'is_actionable' => !$isExpired,
                    'status' => $isExpired ? 'expired' : 'available',
                    'reason' => $isExpired ? 'EXPIRED_48H_WINDOW' : null
                ];
                $timeLimits['BODY_' . $bc->id] = date('Y-m-d H:i:s', $deathTs + (48 * 3600));
            }
        }

        // 2. Resolve Main Mode
        if ($hasBody && $hasCornea) $mode = 'BODY_PLUS_CORNEA';
        elseif ($hasBody) $mode = 'BODY_ONLY';
        elseif ($hasKidney && ($hasCornea || $hasOther || $hasBody)) $mode = 'KIDNEY_PLUS_OTHERS';
        elseif ($hasKidney) $mode = 'KIDNEY_ONLY';
        elseif ($hasCornea && $hasOther) $mode = 'ORGANS_PLUS_CORNEA';
        elseif ($hasCornea || $hasOther) $mode = 'ORGAN_ONLY';
        else $mode = 'NONE';

        // 3. Resolve Operational Track
        $track = self::TRACK_NONE;
        
        if ($mode === 'NONE') {
            $track = self::TRACK_NO_ACTIONABLE_ITEMS;
        } elseif ($mode === 'KIDNEY_ONLY') {
            $track = ($isBrainDead == 1) ? self::TRACK_KIDNEY_INFO_ONLY : self::TRACK_NO_ACTIONABLE_ITEMS;
        } elseif ($mode === 'KIDNEY_PLUS_OTHERS') {
            if ($isBrainDead == 1) {
                // If kidney decision is still pending, we need choices
                if ($kidneyDecision === 'PENDING') {
                    $track = self::TRACK_DECISION_REQUIRED;
                } else {
                    $track = self::TRACK_HOSPITAL_TISSUE; // Proceed with others
                }
            } else {
                $track = self::TRACK_HOSPITAL_TISSUE; // No kidney possible, others remain
            }
        } elseif ($mode === 'BODY_ONLY') {
            $track = self::TRACK_BODY_ONLY;
        } elseif ($mode === 'BODY_PLUS_CORNEA') {
            if ($bodyCorneaDecision === 'PENDING') {
                $track = self::TRACK_BODY_CORNEA_DECISION_REQUIRED;
            } else {
                // Split logic handled by flags in the view
                $track = self::TRACK_BODY_CORNEA_SPLIT;
            }
        } elseif ($mode === 'ORGANS_PLUS_CORNEA' || $mode === 'ORGAN_ONLY') {
            $track = self::TRACK_HOSPITAL_TISSUE;
        }

        return [
            'resolved_deceased_mode' => $mode,
            'resolved_operational_track' => $track,
            'operational_items_json' => json_encode($items),
            'operational_time_limits_json' => json_encode($timeLimits),
            'kidney_decision' => $kidneyDecision,
            'body_cornea_decision' => $bodyCorneaDecision,
            'resolved_at' => date('Y-m-d H:i:s'),
            'hours_since_death' => $hoursSinceDeath,
            'has_kidney' => $hasKidney,
            'has_cornea' => $hasCornea,
            'has_other' => $hasOther,
            'has_body' => $hasBody,
            'is_brain_dead' => $isBrainDead
        ];
    }
}
