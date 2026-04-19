<?php 

namespace App\Controllers;

use App\Core\Controller;

class Home {
    use Controller;

    public function index(){
        $homeModel = new \App\Models\HomeModel();
        $data['stats'] = $homeModel->getHomepageStats();
        
        $this->view('home', $data);
    }

    public function contactAjax() {
        // Obsolete (kept for compatibility)
    }

    public function submitContact() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('home');
            return;
        }

        $model = new \App\Models\ContactModel();
        
        $data = [
            'full_name' => trim($_POST['full_name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'subject' => trim($_POST['subject'] ?? 'Homepage Contact Form'),
            'message' => trim($_POST['message'] ?? '')
        ];

        $errors = [];
        if (empty($data['full_name'])) $errors[] = "Name is required";
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required";
        if (empty($data['message'])) $errors[] = "Message is required";

        if (empty($errors)) {
            $model->insertMessage($data);
            $_SESSION['contact_success'] = "Your message has been sent successfully!";
            redirect('home');
        } else {
            $_SESSION['contact_errors'] = $errors;
            $_SESSION['contact_data'] = $data;
            redirect('home');
        }
    }
}