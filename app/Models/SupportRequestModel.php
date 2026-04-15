<?php

namespace App\Models;

use App\Core\Model;

class SupportRequestModel {
    use Model;

    protected $table = 'support_requests';

    /**
     * Fetch requests by status
     */
    public function getRequestsByStatus($status) {
        $query = "SELECT * FROM $this->table WHERE status = :status ORDER BY created_at DESC";
        $results = $this->query($query, [':status' => strtoupper($status)]);
        return $results ? $results : [];
    }

    /**
     * Fetch all support requests from the database
     */
    public function getAllRequests() {
        $query = "SELECT * FROM $this->table ORDER BY created_at DESC";
        $results = $this->query($query);
        return $results ? $results : [];
    }

    /**
     * Get statistics for the dashboard
     */
    public function getStats() {
        $stats = [
            'total' => 0,
            'pending' => 0,
            'approved' => 0,
            'rejected' => 0
        ];

        $res = $this->query("SELECT status, COUNT(*) as count FROM $this->table GROUP BY status");
        if ($res) {
            foreach ($res as $row) {
                $status = strtolower($row->status);
                if (isset($stats[$status])) {
                    $stats[$status] = $row->count;
                }
                $stats['total'] += $row->count;
            }
        }

        return $stats;
    }

    /**
     * Update the status of a request
     */
    public function updateStatus($id, $status, $reviewer) {
        $query = "UPDATE $this->table SET 
                  status = :status, 
                  reviewed_by = :reviewer, 
                  reviewed_date = CURDATE() 
                  WHERE id = :id";
        
        return $this->query($query, [
            ':id' => $id,
            ':status' => strtoupper($status),
            ':reviewer' => $reviewer
        ]);
    }
}
