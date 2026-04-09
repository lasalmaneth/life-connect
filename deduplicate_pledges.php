<?php
require_once 'app/core/config.php';
require_once 'app/core/Database.php';

class T { use \App\Core\Database; }
$db = new T();

echo "Starting deduplication of donor_pledges...\n";

// 1. Find all (donor_id, organ_id) pairs that have more than 1 non-withdrawn record
$query = "SELECT donor_id, organ_id, COUNT(*) as count 
          FROM donor_pledges 
          WHERE status != 'WITHDRAWN' 
          GROUP BY donor_id, organ_id 
          HAVING count > 1";

$duplicates = $db->query($query);

if (!$duplicates) {
    echo "No active duplicates found.\n";
    exit;
}

echo "Found " . count($duplicates) . " (donor, organ) pairs with duplicates.\n";

$deletedCount = 0;

foreach ($duplicates as $dup) {
    $dId = $dup->donor_id;
    $oId = $dup->organ_id;
    
    echo "Processing Donor: $dId, Organ: $oId (Count: {$dup->count})\n";
    
    // Get all records for this pair, ordered by preference:
    // 1. Successfully uploaded (has path)
    // 2. Most recent date
    $recordsQuery = "SELECT id, signed_form_path, pledge_date, status 
                      FROM donor_pledges 
                      WHERE donor_id = :d AND organ_id = :o AND status != 'WITHDRAWN' 
                      ORDER BY (signed_form_path IS NOT NULL AND signed_form_path != '') DESC, pledge_date DESC";
    
    $records = $db->query($recordsQuery, [':d' => $dId, ':o' => $oId]);
    
    if (count($records) > 1) {
        $keepId = $records[0]->id;
        echo "  Keeping ID: $keepId\n";
        
        // Prepare IDs to delete
        $toDelete = [];
        for ($i = 1; $i < count($records); $i++) {
            $toDelete[] = $records[$i]->id;
        }
        
        if (!empty($toDelete)) {
            $deleteIds = implode(',', $toDelete);
            echo "  Deleting IDs: $deleteIds\n";
            
            // Perform deletion
            $db->query("DELETE FROM donor_pledges WHERE id IN ($deleteIds)");
            $deletedCount += count($toDelete);
        }
    }
}

echo "\nCleanup Complete. Total records deleted: $deletedCount\n";
