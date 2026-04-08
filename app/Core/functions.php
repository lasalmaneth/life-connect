<?php

function show($stuff){
    echo "<pre>";
    print_r($stuff);
    echo "</pre>";
}

function esc($str){
    return htmlspecialchars($str);
}

function redirect($path){
    header("Location:".ROOT."/".$path);
    die;
}

/**
 * Get the current full URL path for navigation active states
 */
function current_url() {
    $url = $_GET['url'] ?? 'home';
    return ROOT . '/' . filter_var(trim($url, '/'), FILTER_SANITIZE_URL);
}

/**
 * Global Routing Helper
 */
$GLOBALS['router'] = [];

function route($url, $classData) {
    if (strpos($classData, '@') === false) {
        $class = $classData;
        $function = 'index';
    } else {
        $parts = explode('@', $classData);
        $class = $parts[0];
        $function = $parts[1];
    }
    
    $GLOBALS['router'][$url] = [
        'class' => $class,
        'function' => $function
    ];
}
/**
 * Encryption Helpers
 */
function encrypt($data) {
    if (!$data) return "";
    $key = hash("sha256", ENC_KEY);
    $ivLength = openssl_cipher_iv_length("aes-256-cbc");
    $iv = openssl_random_pseudo_bytes($ivLength);
    $encrypted = openssl_encrypt($data, "aes-256-cbc", $key, 0, $iv);
    return base64_encode($encrypted . "::" . $iv);
}

function decrypt($data) {
    if (!$data) return "";
    $key = hash("sha256", ENC_KEY);
    $decoded = base64_decode($data);
    if ($decoded === false) return false;
    
    $parts = explode("::", $decoded, 2);
    if (count($parts) === 2) {
        return openssl_decrypt($parts[0], "aes-256-cbc", $key, 0, $parts[1]);
    }
    return false;
}

/**
 * Legal Compliance Helpers
 */
function is_legal_age($dob, $minAge = 21) {
    if (empty($dob)) return false;
    try {
        $dateOfBirth = new DateTime($dob);
        $today = new DateTime();
        $age = $today->diff($dateOfBirth)->y;
        return $age >= $minAge;
    } catch(\Exception $e) {
        return false;
    }
}
