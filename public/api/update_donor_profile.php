<?php
// Hospital-side donor profile update (blood group + HLA typing).
// Kept outside controllers as requested.

// Ensure includes in app/Core/init.php resolve correctly.
@chdir(__DIR__ . '/..');

require_once __DIR__ . '/../../app/Core/init.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json; charset=UTF-8');

if (!isset($_SESSION['user_id']) || strtoupper((string)($_SESSION['role'] ?? '')) !== 'HOSPITAL') {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$donorId = (int)($_POST['id_or_nic'] ?? 0);
if ($donorId <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid patient identifier']);
    exit;
}

$bloodGroup = strtoupper(trim((string)($_POST['blood_group'] ?? '')));
$allowedBloodGroups = ['', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
if (!in_array($bloodGroup, $allowedBloodGroups, true)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid blood group']);
    exit;
}

$fields = [
    'hla_a1' => trim((string)($_POST['hla_a1'] ?? '')),
    'hla_a2' => trim((string)($_POST['hla_a2'] ?? '')),
    'hla_b1' => trim((string)($_POST['hla_b1'] ?? '')),
    'hla_b2' => trim((string)($_POST['hla_b2'] ?? '')),
    'hla_dr1' => trim((string)($_POST['hla_dr1'] ?? '')),
    'hla_dr2' => trim((string)($_POST['hla_dr2'] ?? '')),
];

$allowedA = ['', 'A*01', 'A*02', 'A*03', 'A*11', 'A*24', 'A*33', 'A*68'];
$allowedB = ['', 'B*07', 'B*08', 'B*15', 'B*35', 'B*38', 'B*44', 'B*51', 'B*52', 'B*57', 'B*58'];
$allowedDR = ['', 'DRB1*01', 'DRB1*03', 'DRB1*04', 'DRB1*07', 'DRB1*11', 'DRB1*13', 'DRB1*14', 'DRB1*15'];

if (!in_array($fields['hla_a1'], $allowedA, true) || !in_array($fields['hla_a2'], $allowedA, true)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid HLA A typing']);
    exit;
}

if (!in_array($fields['hla_b1'], $allowedB, true) || !in_array($fields['hla_b2'], $allowedB, true)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid HLA B typing']);
    exit;
}

if (!in_array($fields['hla_dr1'], $allowedDR, true) || !in_array($fields['hla_dr2'], $allowedDR, true)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid HLA DR typing']);
    exit;
}

try {
    $hospitalModel = new \App\Models\HospitalModel();
    $hospital = $hospitalModel->getHospitalByUserId((int)$_SESSION['user_id']);
    $hospitalId = $hospital ? (int)($hospital->id ?? 0) : 0;

    if ($hospitalId <= 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Hospital account not found']);
        exit;
    }

    // Authorization: donor must be visible to this hospital via existing search logic.
    $visible = false;
    $donors = $hospitalModel->getEligibleDonors($hospitalId);
    foreach (($donors ?: []) as $d) {
        if ((int)($d->id ?? 0) === $donorId) {
            $visible = true;
            break;
        }
    }

    if (!$visible) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Forbidden']);
        exit;
    }

    $params = [
        ':id' => $donorId,
        ':bg' => ($bloodGroup !== '' ? $bloodGroup : null),
        ':a1' => ($fields['hla_a1'] !== '' ? $fields['hla_a1'] : null),
        ':a2' => ($fields['hla_a2'] !== '' ? $fields['hla_a2'] : null),
        ':b1' => ($fields['hla_b1'] !== '' ? $fields['hla_b1'] : null),
        ':b2' => ($fields['hla_b2'] !== '' ? $fields['hla_b2'] : null),
        ':dr1' => ($fields['hla_dr1'] !== '' ? $fields['hla_dr1'] : null),
        ':dr2' => ($fields['hla_dr2'] !== '' ? $fields['hla_dr2'] : null),
    ];

    $hospitalModel->query(
        "UPDATE donors
         SET blood_group = :bg,
             hla_a1 = :a1,
             hla_a2 = :a2,
             hla_b1 = :b1,
             hla_b2 = :b2,
             hla_dr1 = :dr1,
             hla_dr2 = :dr2
         WHERE id = :id
         LIMIT 1",
        $params
    );

    echo json_encode([
        'success' => true,
        'message' => 'Donor profile updated',
        'data' => [
            'donor_id' => $donorId,
            'blood_group' => $params[':bg'],
            'hla_a1' => $params[':a1'],
            'hla_a2' => $params[':a2'],
            'hla_b1' => $params[':b1'],
            'hla_b2' => $params[':b2'],
            'hla_dr1' => $params[':dr1'],
            'hla_dr2' => $params[':dr2'],
        ],
    ]);
    exit;
} catch (\Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
    exit;
}
