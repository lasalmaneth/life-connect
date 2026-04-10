<?php

namespace App\Core;

class App {
    
    private $controller = 'App\\Controllers\\Home';
    private $method = 'index';

    private function splitURL(){
        $URL = $_GET['url'] ?? 'home';
        $URL = trim($URL, '/');
        return empty($URL) ? 'home' : $URL;
    }
    
    public function loadController(){
        $existsRoutes = $GLOBALS['router'] ?? [];
        $URL = $this->splitURL();
        
        if (isset($existsRoutes[$URL])) {
            $routeData = $existsRoutes[$URL];
            $className = "App\\Controllers\\" . $routeData['class'];
            $methodName = $routeData['function'];

            if (class_exists($className)) {
                $this->controller = $className;
                $this->method = $methodName;
            } else {
                $this->controller = "App\\Controllers\\_404";
                $this->method = 'index';
            }
        } else {
            $this->controller = "App\\Controllers\\_404";
            $this->method = 'index';
        }

        // The autoloader should handle the class loading here
        if (class_exists($this->controller)) {
            $controller = new $this->controller;
            
            if (method_exists($controller, $this->method)) {
                call_user_func_array([$controller, $this->method], []);
            } else {
                $this->show404();
            }
        } else {
            $this->show404();
        }
    }

    private function show404() {
        $controller = new \App\Controllers\_404();
        $controller->index();
    }
}