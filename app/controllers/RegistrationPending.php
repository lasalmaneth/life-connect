<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\UserModel;

class RegistrationPending {
    use Controller;

    public function index() {
        if (!isset($_SESSION['submitted_username'])) {
            redirect('signup'); // Prevent direct access without a submitted registration context
            return;
        }

        $userModel = new UserModel();
        $statusRecord = $userModel->getStatusByIdentifier($_SESSION['submitted_username']);
        
        $data = [
            'status' => $statusRecord ? $statusRecord->status : 'PENDING',
            'review_message' => $statusRecord ? $statusRecord->review_message : '',
            'username' => $statusRecord ? $statusRecord->username : $_SESSION['submitted_username'],
            'email' => $statusRecord ? $statusRecord->email : 'Not available',
            'role' => $statusRecord ? $statusRecord->role : 'Donor'
        ];

        $this->view('registration/pending', $data);
    }
}
