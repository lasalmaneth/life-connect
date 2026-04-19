<?php
// Dev-only helper: reset OPcache for the web SAPI.
// Delete this file after use.

$ip = $_SERVER['REMOTE_ADDR'] ?? '';
if (!in_array($ip, ['127.0.0.1', '::1'], true)) {
    http_response_code(403);
    header('Content-Type: text/plain; charset=UTF-8');
    echo "Forbidden\n";
    exit;
}

header('Content-Type: text/plain; charset=UTF-8');

echo "REMOTE_ADDR: {$ip}\n";

if (!function_exists('opcache_reset')) {
    echo "OPcache not enabled for this SAPI (opcache_reset() missing).\n";
    exit;
}

$ok = @opcache_reset();
echo $ok ? "OPcache reset: OK\n" : "OPcache reset: FAILED\n";
