<?php
// Static data for matching records
$matchingData = array(
    array(
        'match_id' => 'MAT001',
        'donor_name' => 'John Smith',
        'donor_id' => 'DON001',
        'blood_type' => 'O+',
        'organ_request_id' => 'REQ001',
        'hospital_name' => 'City General Hospital',
        'hospital_location' => '123 Main St, New York, NY',
        'hospital_contact' => '(555) 123-4567',
        'match_date' => '2024-01-15',
        'status' => 'Pending'
    ),
    array(
        'match_id' => 'MAT002',
        'donor_name' => 'Sarah Johnson',
        'donor_id' => 'DON002',
        'blood_type' => 'A-',
        'organ_request_id' => 'REQ002',
        'hospital_name' => 'Memorial Medical Center',
        'hospital_location' => '456 Oak Ave, Los Angeles, CA',
        'hospital_contact' => '(555) 234-5678',
        'match_date' => '2024-01-18',
        'status' => 'In Progress'
    ),
    array(
        'match_id' => 'MAT003',
        'donor_name' => 'Michael Brown',
        'donor_id' => 'DON003',
        'blood_type' => 'B+',
        'organ_request_id' => 'REQ003',
        'hospital_name' => 'Unity Health Center',
        'hospital_location' => '789 Pine Rd, Chicago, IL',
        'hospital_contact' => '(555) 345-6789',
        'match_date' => '2024-01-20',
        'status' => 'Completed'
    ),
    array(
        'match_id' => 'MAT004',
        'donor_name' => 'Emily Davis',
        'donor_id' => 'DON004',
        'blood_type' => 'AB+',
        'organ_request_id' => 'REQ004',
        'hospital_name' => 'Hope Regional Hospital',
        'hospital_location' => '321 Elm St, Houston, TX',
        'hospital_contact' => '(555) 456-7890',
        'match_date' => '2024-01-22',
        'status' => 'Cancelled'
    ),
    array(
        'match_id' => 'MAT005',
        'donor_name' => 'Robert Wilson',
        'donor_id' => 'DON005',
        'blood_type' => 'O-',
        'organ_request_id' => 'REQ005',
        'hospital_name' => 'LifeCare Medical',
        'hospital_location' => '654 Maple Dr, Phoenix, AZ',
        'hospital_contact' => '(555) 567-8901',
        'match_date' => '2024-01-25',
        'status' => 'Pending'
    )
);

foreach ($matchingData as $match) {
    $statusClass = '';
    switch ($match['status']) {
        case 'Pending':
            $statusClass = 'status-pending';
            break;
        case 'In Progress':
            $statusClass = 'status-in-progress';
            break;
        case 'Completed':
            $statusClass = 'status-completed';
            break;
        case 'Cancelled':
            $statusClass = 'status-cancelled';
            break;
    }
    
    echo "
    <div class='table-row' data-match-id='".$match['match_id']."' style='display: grid; grid-template-columns: 1.5fr 1.2fr 1.5fr 1.2fr 120px 100px; gap: 1rem; padding: 1.2rem 1.5rem; align-items: center; border-bottom: 1px solid #f1f5f9; transition: all 0.2s;'>
        <div class='table-cell' style='font-weight: 600; color: #1e293b;'>".$match['donor_name']."</div>
        <div class='table-cell' style='color: #64748b; font-family: monospace;'>".$match['organ_request_id']."</div>
        <div class='table-cell' style='color: #475569;'>".$match['hospital_name']."</div>
        <div class='table-cell' style='color: #64748b; font-size: 0.9rem;'>".date('M d, Y', strtotime($match['match_date']))."</div>
        <div class='table-cell' style='text-align: center;'>
            <span class='status-badge ".$statusClass."' style='padding: 6px 12px; border-radius: 50px; font-weight: 600; font-size: 0.75rem; text-transform: uppercase;'>".$match['status']."</span>
        </div>
        <div class='table-cell' style='text-align: right;'>
            <button class='btn btn-secondary btn-sm' onclick='viewMatchingDetails(\"".$match['match_id']."\")' style='padding: 6px 14px; border-radius: 8px; font-weight: 600; font-size: 0.8rem; border: 1px solid #e2e8f0; background: white; color: #475569; transition: all 0.2s;'>
                <i class='fa-solid fa-eye' style='margin-right: 6px; opacity: 0.7;'></i> View
            </button>
        </div>
    </div>
    ";
}
?>