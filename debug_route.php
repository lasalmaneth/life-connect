<?php
define('ROOT', 'http://localhost/life-connect');
require_once 'app/Core/init.php';

$url = 'donor/withdraw-consent';
$existsRoutes = $GLOBALS['router'] ?? [];

echo "Requested URL: $url\n";
echo "Routes in router: " . count($existsRoutes) . "\n";

if (isset($existsRoutes[$url])) {
    $routeData = $existsRoutes[$url];
    echo "Match Found!\n";
    echo "Controller Class: App\\Controllers\\" . $routeData['class'] . "\n";
    echo "Method: " . $routeData['function'] . "\n";
    
    $className = "App\\Controllers\\" . $routeData['class'];
    if (class_exists($className)) {
        echo "Class exists.\n";
        $controller = new $className;
        if (method_exists($controller, $routeData['function'])) {
            echo "Method exists.\n";
        } else {
            echo "ERROR: Method " . $routeData['function'] . " does NOT exist in $className\n";
        }
    } else {
        echo "ERROR: Class $className does NOT exist.\n";
    }
} else {
    echo "ERROR: Route '$url' NOT found in registry.\n";
}
