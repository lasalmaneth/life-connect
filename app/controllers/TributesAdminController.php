<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\SuccessStoryModel;
use Exception;

class TributesAdminController {
    use Controller;

    public function getHospitals() {
        header('Content-Type: application/json');
        try {
            $model = new SuccessStoryModel();
            $result = $model->getAllHospitals();
            echo json_encode(['success' => true, 'hospitals' => $result]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function getStories() {
        header('Content-Type: application/json');
        try {
            $model = new SuccessStoryModel();
            $result = $model->getAllStories();
            echo json_encode(['success' => true, 'stories' => $result]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function getTributeDetails() {
        header('Content-Type: application/json');
        try {
            $id = $_GET['story_id'] ?? null;
            $model = new SuccessStoryModel();
            $result = $model->getStoryById($id);
            if ($result) {
                echo json_encode(['success' => true, 'story' => $result]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Story not found']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function deleteTribute() {
        header('Content-Type: application/json');
        try {
            $id = $_POST['story_id'] ?? null;
            $model = new SuccessStoryModel();
            $model->deleteStory($id);
            echo json_encode(['success' => true, 'message' => 'Success story deleted successfully']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function saveStory() {
        header('Content-Type: application/json');
        try {
            $story_id = $_POST['story_id'] ?? null;
            
            $data = [
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'hospital_registration_no' => $_POST['hospital_registration_no'],
                'success_date' => $_POST['success_date'],
                'status' => $_POST['status'] ?? 'Pending'
            ];

            $model = new SuccessStoryModel();
            $model->saveStory($data, $story_id);
            
            echo json_encode(['success' => true, 'message' => 'Success story saved successfully']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function updateStatus() {
        header('Content-Type: application/json');
        try {
            $story_id = $_POST['story_id'] ?? null;
            $status = $_POST['status'] ?? null;
            
            if (!$story_id || !$status) {
                throw new Exception("Missing story ID or status");
            }

            $model = new SuccessStoryModel();
            $model->updateStatus($story_id, $status);

            echo json_encode(['success' => true, 'message' => 'Status updated to ' . $status]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function getPendingCount() {
        header('Content-Type: application/json');
        try {
            $model = new SuccessStoryModel();
            $count = $model->getPendingCount();
            echo json_encode(['success' => true, 'count' => $count]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}