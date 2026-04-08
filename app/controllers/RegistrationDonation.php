<?php 

namespace App\Controllers;
use App\Core\Controller;

class RegistrationDonation {

    use Controller;
    public function index(){

        if (!isset($_SESSION['donor_registration'])) {
            redirect('registration/donor');
            return;
        }

        $data = $_SESSION['donor_registration'] ?? null;
        $this->view('registration/donation');

    }
 }
