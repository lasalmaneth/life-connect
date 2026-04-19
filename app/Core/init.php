<?php

// PSR-4 Autoloader
spl_autoload_register(function ($classname) {

    if (strpos($classname, 'App\\Core\\') === 0) {
        $path = "../app/Core/" . str_replace('App\\Core\\', '', $classname) . ".php";
    } elseif (strpos($classname, 'App\\Models\\') === 0) {
        $path = "../app/Models/" . str_replace('App\\Models\\', '', $classname) . ".php";
    } elseif (strpos($classname, 'App\\Controllers\\') === 0) {
        // Note: controllers folder is lower-case in this repo
        $path = "../app/controllers/" . str_replace('App\\Controllers\\', '', $classname) . ".php";
    } elseif (strpos($classname, 'App\\Services\\') === 0) {
        $path = "../app/Services/" . str_replace('App\\Services\\', '', $classname) . ".php";
    } else {
        // Fallback for current messy structure during transition
        $parts = explode('\\', $classname);
        $name = end($parts);
        $path = "../app/Models/" . ucfirst($name) . ".php";
    }

    // Convert namespace separators to directory separators (required for sub-namespaces like Controllers\\admin)
    $path = str_replace('\\', '/', $path);

    if (file_exists($path)) {
        require $path;
    }
});

require 'config.php';
require 'functions.php';
require 'Database.php';
require 'Model.php';
require 'Controller.php';
require 'App.php';
require 'route.php';
