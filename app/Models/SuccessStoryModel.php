<?php

namespace App\Models;

use App\Core\Database;

class SuccessStoryModel {
    use Database;

    protected $table = 'success_stories';

    public function getStoriesByInstitution($id, $type) {
        $query = "SELECT * FROM {$this->table} 
                   WHERE institution_id = :id AND institution_type = :type 
                   ORDER BY created_at DESC";
        return $this->query($query, ['id' => $id, 'type' => $type]) ?: [];
    }

    public function getApprovedStories($limit = 3) {
        $limit = (int)$limit;
        $query = "SELECT * FROM {$this->table} 
                  WHERE status = 'Approved' 
                  ORDER BY success_date DESC 
                  LIMIT $limit";
        return $this->query($query) ?: [];
    }

    public function getAllStories() {
        $query = "SELECT s.*, 
                         u.username,
                         u.role as user_role,
                         COALESCE(h.name, CONCAT(d.first_name, ' ', d.last_name), u.username) as submitted_by_name
                  FROM {$this->table} s
                  LEFT JOIN users u ON s.user_id = u.id
                  LEFT JOIN hospitals h ON u.id = h.user_id
                  LEFT JOIN donors d ON u.id = d.user_id
                  ORDER BY s.created_at DESC";
        return $this->query($query) ?: [];
    }

    public function getStoryById($id) {
        $query = "SELECT s.*, 
                         u.username,
                         u.role as user_role,
                         COALESCE(h.name, CONCAT(d.first_name, ' ', d.last_name), u.username) as submitted_by_name
                  FROM {$this->table} s
                  LEFT JOIN users u ON s.user_id = u.id
                  LEFT JOIN hospitals h ON u.id = h.user_id
                  LEFT JOIN donors d ON u.id = d.user_id
                  WHERE s.story_id = :id";
        $result = $this->query($query, ['id' => $id]);
        return $result ? $result[0] : null;
    }

    public function saveStory($data, $id = null) {
        if ($id) {
            $query = "UPDATE {$this->table} SET 
                        title = :title, 
                        description = :description, 
                        story_type = :story_type,
                        author_name = :author_name,
                        donors_count = :donors_count,
                        students_helped = :students_helped,
                        success_date = :success_date,
                        status = :status,
                        review_message = :review_message
                      WHERE story_id = :story_id";
            $data['story_id'] = $id;
        } else {
            $query = "INSERT INTO {$this->table} (
                        institution_id, institution_type, title, description, 
                        story_type, author_name, donors_count, students_helped, 
                        success_date, status, created_at
                      ) VALUES (
                        :institution_id, :institution_type, :title, :description, 
                        :story_type, :author_name, :donors_count, :students_helped, 
                        :success_date, :status, NOW()
                      )";
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
