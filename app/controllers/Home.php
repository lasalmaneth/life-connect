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
}