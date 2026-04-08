<?php 

namespace App\Controllers;

use App\Core\Controller;

class HomePages {
    use Controller;

    public function education(){
        $this->view('home_pages/education');
    }

    public function legal(){
        $this->view('home_pages/legal');
    }

    public function liveDonation(){
        $this->view('home_pages/live-donation');
    }

    public function deceasedDonation(){
        $this->view('home_pages/deceased-donation');
    }

    public function ourStory(){
        $this->view('home_pages/our-story');
    }

    public function reachUs(){
        $this->view('home_pages/reach-us');
    }

    public function religion(){
        $this->view('home_pages/religion');
    }
}
