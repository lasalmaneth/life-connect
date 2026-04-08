<?php

namespace App\Models;

use App\Core\Database;

class SuccessStoryModel {
    use Database;

    protected $table = 'success_stories';

    public function getAllStories() {
        $query = "SELECT * FROM {$this->table} ORDER BY created_at DESC";
        return $this->query($query);
    }

    public function getStoryById($id) {
        $query = "SELECT * FROM {$this->table} WHERE story_id = :id";
        $result = $this->query($query, ['id' => $id]);
        return $result ? $result[0] : null;
    }

    public function saveStory($data, $id = null) {
        if ($id) {
            $query = "UPDATE {$this->table} SET 
                        title = :title, 
                        description = :description, 
                        hospital_registration_no = :hospital_registration_no, 
                        success_date = :success_date,
                        status = :status 
                      WHERE story_id = :story_id";
            $data['story_id'] = $id;
        } else {
            $query = "INSERT INTO {$this->table} (title, description, hospital_registration_no, success_date, status, created_at) 
                      VALUES (:title, :description, :hospital_registration_no, :success_date, :status, NOW())";
        }

        return $this->query($query, $data);
    }

    public function deleteStory($id) {
        $query = "DELETE FROM {$this->table} WHERE story_id = :id";
        return $this->query($query, ['id' => $id]);
    }

    public function updateStatus($id, $status) {
        $query = "UPDATE {$this->table} SET status = :status WHERE story_id = :id";
        return $this->query($query, ['status' => $status, 'id' => $id]);
    }

    public function getPendingCount() {
        $query = "SELECT COUNT(*) as count FROM {$this->table} WHERE status = 'Pending'";
        $result = $this->query($query);
        return $result[0]->count ?? 0;
    }

    public function getAllHospitals() {
        $query = "SELECT registration_number as registration_no, name as h_name FROM hospitals WHERE verification_status = 'APPROVED' ORDER BY name";
        return $this->query($query);
    }
}
