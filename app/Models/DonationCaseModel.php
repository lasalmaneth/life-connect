<?php

namespace App\Models;

use App\Core\Model;

class DonationCaseModel {
    use Model;

    protected $table = 'donation_cases';
    protected $allowedColumns = [
        'donor_id',
        'death_declaration_id',
        'case_number',
        'donation_type',
        'legal_status',
        'overall_status',
        'bundle_status',
        'resolved_deceased_mode',
        'resolved_operational_track',
        'operational_items_json',
        'operational_time_limits_json',
        'kidney_decision',
        'kidney_decision_at',
        'body_cornea_decision',
        'guidance_message',
        'show_kidney_popup',
        'resolved_at'
    ];

    /**
     * Get death declaration for a donor
     */
    public function getDeathDeclaration($donorId)
    {
        return $this->queryJoin(
            [['table' => 'custodians c', 'on' => 'dd.declared_by_custodian_id = c.id', 'type' => 'JOIN']],
            ['dd.donor_id' => $donorId],
            'dd.*, c.name AS declared_by_name, c.phone AS declared_by_phone, c.email AS declared_by_email',
            'dd.created_at DESC',
            1,
            0,
            'death_declarations dd'
        )[0] ?? null;
    }

    /**
     * Create a death declaration
     */
    public function createDeathDeclaration($data)
    {
        $deathDateTime = $data['date_of_death'] . ' ' . $data['time_of_death'];
        $data['window_expires_at'] = date('Y-m-d H:i:s', strtotime($deathDateTime . ' +1 hour '));
        show($data);
        return $this->insert($data, 'death_declarations');
    }

    /**
     * Create a donation case
     */
    public function createDonationCase($data)
    {
        $year = date('Y');
        $count = $this->count(['case_number LIKE' => "DC-$year-%"], [], 'donation_cases');
        $data['case_number'] = sprintf("DC-%s-%03d", $year, $count + 1);
        return $this->insert($data, 'donation_cases');
    }

    /**
     * Get the currently active institution for a case + track
     */
    public function getCurrentInstitution($caseId, $track)
    {
        $cols = 'cis.*, 
                 CASE 
                    WHEN cis.institution_type = "MEDICAL_SCHOOL" THEN ms.school_name 
                    WHEN cis.institution_type = "HOSPITAL" THEN h.name 
                    ELSE "LifeConnect Central" 
                 END AS institution_name,
                 CASE 
                    WHEN cis.institution_type = "MEDICAL_SCHOOL" THEN ms.address 
                    WHEN cis.institution_type = "HOSPITAL" THEN h.address 
                    ELSE NULL 
                 END AS institution_address,
                 CASE 
                    WHEN cis.institution_type = "MEDICAL_SCHOOL" THEN ms.contact_person_phone 
                    WHEN cis.institution_type = "HOSPITAL" THEN h.contact_number 
                    ELSE NULL 
                 END AS contact_phone,
                 CASE 
                    WHEN cis.institution_type = "MEDICAL_SCHOOL" THEN ms.contact_person_email 
                    WHEN cis.institution_type = "HOSPITAL" THEN (SELECT email FROM users WHERE id = h.user_id) 
                    ELSE NULL 
                 END AS contact_email';

        return $this->queryJoin(
            [
                ['table' => 'medical_schools ms', 'on' => 'cis.institution_type = "MEDICAL_SCHOOL" AND cis.institution_id = ms.id', 'type' => 'LEFT'],
                ['table' => 'hospitals h', 'on' => 'cis.institution_type = "HOSPITAL" AND cis.institution_id = h.id', 'type' => 'LEFT']
            ],
            ['cis.donation_case_id' => $caseId, 'cis.track' => $track, 'cis.is_current' => 1],
            $cols,
            '',
            1,
            0,
            'case_institution_status cis'
        )[0] ?? null;
    }

    public function getCaseByDonor($donorId)
    {
        return $this->queryJoin(
            [['table' => 'death_declarations dd', 'on' => 'dc.death_declaration_id = dd.id', 'type' => 'JOIN']],
            ['dc.donor_id' => $donorId],
            'dc.*, dd.date_of_death, dd.time_of_death, dd.window_expires_at, dd.status AS death_status',
            'dc.created_at DESC',
            1,
            0,
            'donation_cases dc'
        )[0] ?? null;
    }

    /**
     * Get chronological timeline of all actions for a case
     */
    public function getTimeline($caseId)
    {
        $events = [];

        // Death declaration
        $ddQuery = "SELECT dd.created_at, c.name AS actor,
                           CONCAT('Death declared: ', dd.cause_of_death) AS description
                    FROM death_declarations dd
                    JOIN donation_cases dc ON dc.death_declaration_id = dd.id
                    JOIN custodians c ON dd.declared_by_custodian_id = c.id
                    WHERE dc.id = :case_id";
        $dd = $this->query($ddQuery, [':case_id' => $caseId]) ?: [];
        foreach ($dd as $e) {
            $events[] = ['time' => $e->created_at, 'actor' => $e->actor, 'event' => $e->description, 'type' => 'death'];
        }

        // Legal actions
        $laQuery = "SELECT cla.created_at, c.name AS actor,
                           CONCAT(cla.action_type, ' via ', cla.method) AS description
                    FROM custodian_legal_actions cla
                    JOIN custodians c ON cla.custodian_id = c.id
                    WHERE cla.donation_case_id = :case_id";
        $la = $this->query($laQuery, [':case_id' => $caseId]) ?: [];
        foreach ($la as $e) {
            $events[] = ['time' => $e->created_at, 'actor' => $e->actor, 'event' => $e->description, 'type' => 'legal'];
        }

        // Institution status changes
        $isQuery = "SELECT cis.created_at, cis.submission_date, cis.response_date,
                           cis.institution_status, cis.track, cis.attempt_order,
                           CASE
                             WHEN cis.institution_type = 'MEDICAL_SCHOOL' THEN ms.school_name
                             WHEN cis.institution_type = 'HOSPITAL' THEN h.name
                             ELSE 'LifeConnect Central'
                           END AS institution_name
                    FROM case_institution_status cis
                    LEFT JOIN medical_schools ms ON cis.institution_type = 'MEDICAL_SCHOOL' AND cis.institution_id = ms.id
                    LEFT JOIN hospitals h ON cis.institution_type = 'HOSPITAL' AND cis.institution_id = h.id
                    WHERE cis.donation_case_id = :case_id
                    ORDER BY cis.attempt_order";
        $is = $this->query($isQuery, [':case_id' => $caseId]) ?: [];
        foreach ($is as $e) {
            $events[] = [
                'time' => $e->created_at,
                'actor' => 'System',
                'event' => "Attempt #{$e->attempt_order}: {$e->institution_name} ({$e->track}) — {$e->institution_status}",
                'type' => 'institution'
            ];
            if ($e->submission_date) {
                $events[] = [
                    'time' => $e->submission_date,
                    'actor' => 'Custodian',
                    'event' => "Documents submitted to {$e->institution_name}",
                    'type' => 'submission'
                ];
            }
            if ($e->response_date) {
                $events[] = [
                    'time' => $e->response_date,
                    'actor' => $e->institution_name,
                    'event' => "Response: {$e->institution_status}",
                    'type' => 'response'
                ];
            }
        }

        // Document uploads
        $docQuery = "SELECT cd.uploaded_at AS created_at, cd.document_type
                     FROM custodian_documents cd
                     WHERE cd.donation_case_id = :case_id";
        $docs = $this->query($docQuery, [':case_id' => $caseId]) ?: [];
        foreach ($docs as $e) {
            $events[] = [
                'time' => $e->created_at,
                'actor' => 'Custodian',
                'event' => "Uploaded: {$e->document_type}",
                'type' => 'document'
            ];
        }

        // Sort by time
        usort($events, fn($a, $b) => strtotime($a['time']) - strtotime($b['time']));

        return $events;
    }

    /**
     * Get institutions available for a case
     */
    public function getAvailableInstitutions($caseId, $track, $targetType = null)
    {
        $attempted = $this->where(
            ['donation_case_id' => $caseId, 'track' => $track], 
            [], 
            'institution_id', 
            '', 
            'case_institution_status'
        ) ?: [];
        $attemptedIds = array_map(fn($r) => $r->institution_id, $attempted);

        if ($targetType === 'MEDICAL_SCHOOL') {
            $institutions = $this->queryJoin(
                [
                    ['table' => 'medical_schools ms', 'on' => 'bdc.medical_school_id = ms.id', 'type' => 'JOIN'],
                    ['table' => 'donation_cases dc', 'on' => 'dc.donor_id = bdc.donor_id', 'type' => 'JOIN']
                ],
                ['dc.id' => $caseId],
                'ms.id, ms.school_name, ms.university_affiliation, ms.address',
                '',
                100,
                0,
                'body_donation_consents bdc'
            );
        } else {
            $query = "SELECT h.id, h.name AS school_name, h.registration_number, h.address
                      FROM hospitals h
                      WHERE h.verification_status = 'APPROVED'";
            $institutions = $this->query($query) ?: [];
        }

        if (!empty($attemptedIds)) {
            $institutions = array_filter($institutions, fn($inst) => !in_array($inst->id, $attemptedIds));
            $institutions = array_values($institutions);
        }

        return $institutions;
    }

    /**
     * Update case status accurately
     */
    public function updateCaseStatus($caseId, $legalStatus = null, $overallStatus = null)
    {
        $data = [];
        if ($legalStatus !== null) $data['legal_status'] = $legalStatus;
        if ($overallStatus !== null) $data['overall_status'] = $overallStatus;
        
        if (empty($data)) return false;

        return $this->update($caseId, $data);
    }

    /**
     * Get all institution statuses for a case (history of attempts)
     */
    public function getInstitutionStatuses($caseId)
    {
        $cols = 'cis.*, 
                 CASE 
                    WHEN cis.institution_type = "MEDICAL_SCHOOL" THEN ms.school_name 
                    WHEN cis.institution_type = "HOSPITAL" THEN h.name 
                    ELSE "LifeConnect Central" 
                 END AS institution_name';

        return $this->queryJoin(
            [
                ['table' => 'medical_schools ms', 'on' => 'cis.institution_type = "MEDICAL_SCHOOL" AND cis.institution_id = ms.id', 'type' => 'LEFT'],
                ['table' => 'hospitals h', 'on' => 'cis.institution_type = "HOSPITAL" AND cis.institution_id = h.id', 'type' => 'LEFT']
            ],
            ['cis.donation_case_id' => $caseId],
            $cols,
            'cis.track, cis.attempt_order ASC',
            100,
            0,
            'case_institution_status cis'
        );
    }

    /**
     * Update the operational track for a case (used for clinical decisions)
     */
    public function updateTrack($caseId, $track)
    {
        return $this->update($caseId, ['resolved_operational_track' => $track]);
    }

    /**
     * Central clinical clock resolver for all institutional workflows
     */
    public function getClinicalWindowStatus($case, $deathDecl)
    {
        if (!$case || !$deathDecl) return null;

        // Use the resolver service to get item-specific limits
        $resolver = new \App\Services\DonationResolver();
        $timeOfDeath = $deathDecl->date_of_death . ' ' . $deathDecl->time_of_death;
        
        $donorId = $case->donor_id;
        $snapshot = $resolver->resolveAtDeath($donorId, $deathDecl->is_brain_dead, $timeOfDeath, $case->kidney_decision, $case->body_cornea_decision);
        
        $activeItems = $snapshot['items'];
        $expirations = $snapshot['time_limits'];
        $currentDeadline = $deathDecl->window_expires_at; // 48h default

        $track = $case->resolved_operational_track;
        if (str_contains($track, 'HOSPITAL_TISSUE') || str_contains($track, 'ORGAN')) {
            foreach ($activeItems as $id => $item) {
                if ($item['type'] === 'HOSPITAL_TISSUE' && isset($expirations[$id])) {
                    $currentDeadline = $expirations[$id];
                    break;
                }
            }
        }
        
        $now = time();
        $deadlineTs = strtotime($currentDeadline);
        return [
            'deadline' => $currentDeadline,
            'is_expired' => ($now > $deadlineTs),
            'seconds_remaining' => ($deadlineTs - $now)
        ];
    }
}
