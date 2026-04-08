<?php

namespace App\Models;

use App\Core\Database;

class UpcomingAppointmentModel
{
    use Database;

    protected $table = 'upcoming_appointments';

    /**
     * Get all upcoming tests/investigations for a specific donor
     */
    public function getAppointmentsByDonorId($donorId)
    {
        $query = "SELECT * FROM {$this->table} WHERE donor_id = :donor_id ORDER BY test_date ASC";
        return $this->query($query, [':donor_id' => $donorId]) ?: [];
    }

    /**
     * Get only pending tests for the calendar highlight
     */
    public function getApprovedAppointmentsByDonorId($donorId)
    {
        $query = "SELECT * FROM {$this->table} WHERE donor_id = :donor_id AND status = 'Pending' ORDER BY test_date ASC";
        return $this->query($query, [':donor_id' => $donorId]) ?: [];
    }

    /**
     * Update the result status of a test
     */
    public function updateResult($id, $status, $notes = null)
    {
        $query = "UPDATE {$this->table} SET status = :status, notes = :notes WHERE id = :id";
        return $this->query($query, [
            ':status' => $status,
            ':notes'  => $notes,
            ':id'     => $id
        ]);
    }

    /**
     * Approve an appointment (only if still Pending)
     */
    public function approveAppointment($id)
    {
        $query = "UPDATE {$this->table} SET status = 'Approved' WHERE id = :id AND status = 'Pending'";
        return $this->query($query, [':id' => $id]);
    }

    /**
     * Reject an appointment with a reason (only if still Pending)
     */
    public function rejectAppointment($id, $reason)
    {
        $query = "UPDATE {$this->table} SET status = 'Rejected', notes = :reason WHERE id = :id AND status = 'Pending'";
        return $this->query($query, [':reason' => $reason, ':id' => $id]);
    }

    /**
     * Get only future (upcoming) appointments for a donor
     */
    public function getUpcomingByDonorId($donorId)
    {
        $query = "SELECT * FROM {$this->table} WHERE donor_id = :donor_id AND test_date >= CURDATE() ORDER BY test_date ASC";
        return $this->query($query, [':donor_id' => $donorId]) ?: [];
    }

    /**
     * Get a single test record by ID
     */
    public function getAppointmentById($id)
    {
        $query = "SELECT * FROM {$this->table} WHERE id = :id";
        $res = $this->query($query, [':id' => $id]);
        return $res ? $res[0] : null;
    }
}
