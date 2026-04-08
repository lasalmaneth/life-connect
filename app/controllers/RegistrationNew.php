<?php 

namespace App\Controllers;
use App\Core\Controller;

class RegistrationNew {

    use Controller;
    public function index(){

        $this->view('registration/registration');

    }
 }
