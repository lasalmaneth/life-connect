<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Session check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Hospital') {
    header('Location: /life-connect/login');
    exit();
}

// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'life-connect';

// Get hospital information from session/database
$hospital_id = $_SESSION['user_id'] ?? 1;
$hospital_registration = 'HOSP001'; // Will be loaded from database based on user_id
$hospital_name = 'LifeConnect Hospital'; // Will be loaded from database
$hospital_email = 'admin@lifeconnect.lk'; // Will be loaded from database

// Initialize variables
$pdo = null;
$connection_status = "error";
$connection_message = "Database not connected";
$use_sample_data = true; // Set to true to use sample data instead of database

try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    // Test connection with a simple query
    $stmt = $pdo->query("SELECT 1 as test");
    $test_result = $stmt->fetch()['test'];
    
    // Connection successful
    $connection_status = "success";
    $connection_message = "Database connected successfully!";
    $use_sample_data = false; // Use real database data
    
    // Test if success_stories table exists and has the right structure
    try {
        $stmt = $pdo->query("DESCRIBE success_stories");
        $columns = $stmt->fetchAll();
        $has_hospital_registration = false;
        foreach ($columns as $column) {
            if ($column['Field'] === 'hospital_registration_no') {
                $has_hospital_registration = true;
                break;
            }
        }
        
        if (!$has_hospital_registration) {
            error_log("success_stories table missing hospital_registration_no column, recreating table");
            // Drop and recreate the table with correct structure
            $pdo->exec("DROP TABLE IF EXISTS success_stories");
            $createStoriesTable = "
            CREATE TABLE success_stories (
                story_id INT AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(255) NOT NULL,
                description TEXT NOT NULL,
                success_date DATE NOT NULL,
                hospital_registration_no VARCHAR(20) NOT NULL,
                status VARCHAR(20) DEFAULT 'Pending',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )";
            $pdo->exec($createStoriesTable);
            error_log("success_stories table recreated with correct structure");
        }
        
        // Check recipients table structure
        $stmt = $pdo->query("DESCRIBE recipients");
        $recipient_columns = $stmt->fetchAll();
        $recipient_has_hospital_registration = false;
        foreach ($recipient_columns as $column) {
            if ($column['Field'] === 'hospital_registration_no') {
                $recipient_has_hospital_registration = true;
                break;
            }
        }
        
        if (!$recipient_has_hospital_registration) {
            error_log("recipients table missing hospital_registration_no column, recreating table");
            // Drop and recreate the table with correct structure
            $pdo->exec("DROP TABLE IF EXISTS recipients");
            $createRecipientsTable = "
            CREATE TABLE recipients (
                recipient_id INT AUTO_INCREMENT PRIMARY KEY,
                nic VARCHAR(20) NOT NULL,
                name VARCHAR(255) NOT NULL,
                organ_received VARCHAR(50) NOT NULL,
                surgery_date DATE NOT NULL,
                treatment_notes TEXT,
                hospital_registration_no VARCHAR(20) NOT NULL,
                status VARCHAR(20) DEFAULT 'Active',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )";
            $pdo->exec($createRecipientsTable);
            error_log("recipients table recreated with correct structure");
        }
    } catch (PDOException $e) {
        error_log("Error checking success_stories table structure: " . $e->getMessage());
    }
    
    // Create tables if they don't exist
    try {
        // Create organ_request table
        $createOrganTable = "
        CREATE TABLE IF NOT EXISTS organ_request (
            request_id INT AUTO_INCREMENT PRIMARY KEY,
            registration_no VARCHAR(20) NOT NULL,
            organ_type VARCHAR(50) NOT NULL,
            urgency VARCHAR(20) NOT NULL,
            notes TEXT,
            status VARCHAR(20) DEFAULT 'Pending',
            request_date DATE NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
        $pdo->exec($createOrganTable);
        
        // Create recipients table
        $createRecipientsTable = "
        CREATE TABLE IF NOT EXISTS recipients (
            recipient_id INT AUTO_INCREMENT PRIMARY KEY,
            nic VARCHAR(20) NOT NULL,
            name VARCHAR(255) NOT NULL,
            organ_received VARCHAR(50) NOT NULL,
            surgery_date DATE NOT NULL,
            treatment_notes TEXT,
            hospital_registration_no VARCHAR(20) NOT NULL,
            status VARCHAR(20) DEFAULT 'Active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
        $pdo->exec($createRecipientsTable);
        
        // Create success_stories table
        $createStoriesTable = "
        CREATE TABLE IF NOT EXISTS success_stories (
            story_id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            description TEXT NOT NULL,
            success_date DATE NOT NULL,
            hospital_registration_no VARCHAR(20) NOT NULL,
            status VARCHAR(20) DEFAULT 'Pending',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
        $pdo->exec($createStoriesTable);
        
    } catch (PDOException $e) {
        // Table creation failed, but connection is still good
    }
    
} catch (PDOException $e) {
    // Connection failed - use sample data
    $connection_status = "error";
    $connection_message = "Database connection failed: " . $e->getMessage() . " - Using sample data";
    $use_sample_data = true;
}

// Set session variables
$_SESSION['hospital_id'] = $hospital_id;
$_SESSION['hospital_registration'] = $hospital_registration;
$_SESSION['hospital_name'] = $hospital_name;
$_SESSION['hospital_email'] = $hospital_email;

// Get hospital details from database
$hospital_details = [
    'name' => $hospital_name,
    'registration' => $hospital_registration,
    'email' => $hospital_email,
    'role' => 'Medical Coordinator',
    'last_login' => date('Y-m-d H:i:s'),
    'status' => 'Active'
];

// Try to get additional details from database if connected
if ($pdo && !$use_sample_data) {
    try {
        // Create hospital table if it doesn't exist
        $createHospitalTable = "
        CREATE TABLE IF NOT EXISTS hospital (
            hospital_id INT AUTO_INCREMENT PRIMARY KEY,
            registration_no VARCHAR(20) UNIQUE NOT NULL,
            hospital_name VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            address TEXT,
            phone VARCHAR(20),
            status VARCHAR(20) DEFAULT 'Active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
        $pdo->exec($createHospitalTable);
        
        // Check if hospital record exists, if not create it
        $stmt = $pdo->prepare("SELECT * FROM hospital WHERE registration_no = ?");
        $stmt->execute([$hospital_registration]);
        $db_hospital = $stmt->fetch();
        
        if ($db_hospital) {
            $hospital_details = [
                'name' => $db_hospital['hospital_name'],
                'registration' => $db_hospital['registration_no'],
                'email' => $db_hospital['email'],
                'address' => $db_hospital['address'] ?? 'Not specified',
                'phone' => $db_hospital['phone'] ?? 'Not specified',
                'role' => 'Medical Coordinator',
                'last_login' => date('Y-m-d H:i:s'),
                'status' => $db_hospital['status']
            ];
        } else {
            // Insert hospital record
            $stmt = $pdo->prepare("INSERT INTO hospital (registration_no, hospital_name, email, status) VALUES (?, ?, ?, 'Active')");
            $stmt->execute([$hospital_registration, $hospital_name, $hospital_email]);
        }
    } catch (PDOException $e) {
        // Use default details if database query fails
    }
}

// Sample Data (used when database is not available)
$sample_organ_requests = [
    [
        'request_id' => 1,
        'organ_type' => 'Heart',
        'urgency' => 'Urgent',
        'notes' => 'Patient requires immediate heart transplant',
        'status' => 'Pending',
        'request_date' => '2024-01-15'
    ],
    [
        'request_id' => 2,
        'organ_type' => 'Kidney',
        'urgency' => 'High',
        'notes' => 'Kidney failure, dialysis dependent',
        'status' => 'Approved',
        'request_date' => '2024-01-20'
    ],
    [
        'request_id' => 3,
        'organ_type' => 'Liver',
        'urgency' => 'Medium',
        'notes' => 'Liver cirrhosis, stable condition',
        'status' => 'Pending',
        'request_date' => '2024-01-25'
    ],
    [
        'request_id' => 4,
        'organ_type' => 'Cornea',
        'urgency' => 'Low',
        'notes' => 'Corneal damage, vision impairment',
        'status' => 'Approved',
        'request_date' => '2024-01-30'
    ]
];

$sample_recipients = [
    [
        'recipient_id' => 1,
        'nic' => '123456789V',
        'name' => 'John Smith',
        'organ_received' => 'Heart',
        'surgery_date' => '2024-01-10',
        'treatment_notes' => 'Patient recovering well, no complications',
        'status' => 'Active',
        'created_at' => '2024-01-10'
    ],
    [
        'recipient_id' => 2,
        'nic' => '987654321V',
        'name' => 'Jane Doe',
        'organ_received' => 'Kidney',
        'surgery_date' => '2024-01-15',
        'treatment_notes' => 'Regular follow-up required',
        'status' => 'Follow-up',
        'created_at' => '2024-01-15'
    ],
    [
        'recipient_id' => 3,
        'nic' => '456789123V',
        'name' => 'Bob Johnson',
        'organ_received' => 'Liver',
        'surgery_date' => '2024-01-20',
        'treatment_notes' => 'Excellent recovery progress',
        'status' => 'Recovered',
        'created_at' => '2024-01-20'
    ],
    [
        'recipient_id' => 4,
        'nic' => '789123456V',
        'name' => 'Alice Brown',
        'organ_received' => 'Cornea',
        'surgery_date' => '2024-01-25',
        'treatment_notes' => 'Vision improving steadily',
        'status' => 'Active',
        'created_at' => '2024-01-25'
    ]
];

$sample_success_stories = [
    [
        'story_id' => 1,
        'title' => 'Successful Heart Transplant at LifeConnect Hospital',
        'description' => 'A 45-year-old patient received a life-saving heart transplant and is now living a healthy, active life. The surgery was performed by our expert cardiac team.',
        'success_date' => '2024-01-10',
        'status' => 'Approved',
        'created_at' => '2024-01-10'
    ],
    [
        'story_id' => 2,
        'title' => 'Kidney Transplant Success Story',
        'description' => 'A young patient received a kidney from a living donor and has made a full recovery. The patient is now back to normal activities.',
        'success_date' => '2024-01-15',
        'status' => 'Approved',
        'created_at' => '2024-01-15'
    ],
    [
        'story_id' => 3,
        'title' => 'Liver Transplant Miracle',
        'description' => 'A complex liver transplant procedure was successful, saving the patient\'s life. The patient is now in excellent health.',
        'success_date' => '2024-01-20',
        'status' => 'Pending',
        'created_at' => '2024-01-20'
    ],
    [
        'story_id' => 4,
        'title' => 'Corneal Transplant Success',
        'description' => 'A patient regained full vision after a successful corneal transplant. The procedure was performed with precision and care.',
        'success_date' => '2024-01-25',
        'status' => 'Approved',
        'created_at' => '2024-01-25'
    ]
];

$sample_aftercare_appointments = [
    [
        'appointment_id' => 1,
        'patient_id' => 'PAT001',
        'patient_name' => 'John Smith',
        'appointment_date' => '2024-02-15 10:00:00',
        'appointment_type' => 'Follow-up',
        'description' => 'Regular post-transplant checkup',
        'status' => 'Scheduled'
    ],
    [
        'appointment_id' => 2,
        'patient_id' => 'PAT002',
        'patient_name' => 'Jane Doe',
        'appointment_date' => '2024-02-20 14:00:00',
        'appointment_type' => 'Check-up',
        'description' => 'Kidney function assessment',
        'status' => 'Scheduled'
    ],
    [
        'appointment_id' => 3,
        'patient_id' => 'PAT003',
        'patient_name' => 'Bob Johnson',
        'appointment_date' => '2024-02-25 09:00:00',
        'appointment_type' => 'Follow-up',
        'description' => 'Liver function monitoring',
        'status' => 'Scheduled'
    ],
    [
        'appointment_id' => 4,
        'patient_id' => 'PAT004',
        'patient_name' => 'Alice Brown',
        'appointment_date' => '2024-02-28 11:00:00',
        'appointment_type' => 'Consultation',
        'description' => 'Vision assessment',
        'status' => 'Scheduled'
    ]
];

// Database Functions
function getOrganRequests($pdo, $hospital_registration = 'HOSP001', $use_sample_data = false, $sample_data = []) {
    if ($use_sample_data) {
        return $sample_data;
    }
    
    if (!$pdo) return [];
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM organ_request WHERE registration_no = ? ORDER BY request_date DESC");
        $stmt->execute([$hospital_registration]);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}

function getRecipients($pdo, $hospital_registration = 'HOSP001', $use_sample_data = false, $sample_data = []) {
    if ($use_sample_data) {
        return $sample_data;
    }
    
    if (!$pdo) {
        error_log("getRecipients: PDO connection is null");
        return [];
    }
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM recipients WHERE hospital_registration_no = ? ORDER BY created_at DESC");
        $stmt->execute([$hospital_registration]);
        $result = $stmt->fetchAll();
        error_log("getRecipients: Retrieved " . count($result) . " recipients");
        return $result;
    } catch (PDOException $e) {
        error_log("getRecipients: PDO Exception - " . $e->getMessage());
        return [];
    }
}

function getSuccessStories($pdo, $hospital_registration = 'HOSP001', $use_sample_data = false, $sample_data = []) {
    if ($use_sample_data) {
        return $sample_data;
    }
    
    if (!$pdo) {
        error_log("getSuccessStories: PDO connection is null");
        return [];
    }
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM success_stories WHERE hospital_registration_no = ? ORDER BY created_at DESC");
        $stmt->execute([$hospital_registration]);
        $result = $stmt->fetchAll();
        error_log("getSuccessStories: Retrieved " . count($result) . " success stories");
        return $result;
    } catch (PDOException $e) {
        error_log("getSuccessStories: PDO Exception - " . $e->getMessage());
        return [];
    }
}

function getAftercareAppointments($pdo, $hospital_registration = 'HOSP001', $use_sample_data = false, $sample_data = []) {
    if ($use_sample_data) {
        return $sample_data;
    }
    
    if (!$pdo) return [];
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM aftercare_appointments WHERE hospital_registration_no = ? ORDER BY appointment_date ASC");
        $stmt->execute([$hospital_registration]);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}

function addOrganRequest($pdo, $data, $use_sample_data = false) {
    if ($use_sample_data) {
        // Simulate adding to sample data
        return true;
    }
    
    if (!$pdo) {
        error_log("addOrganRequest: PDO connection is null");
        return false;
    }
    
    try {
        $stmt = $pdo->prepare("INSERT INTO organ_request (registration_no, organ_type, urgency, notes, status, request_date) VALUES (?, ?, ?, ?, 'Pending', CURDATE())");
        $result = $stmt->execute([
            $data['registration_no'],
            $data['organ_type'],
            $data['urgency'],
            $data['notes']
        ]);
        
        if ($result) {
            error_log("addOrganRequest: Successfully inserted organ request");
        } else {
            error_log("addOrganRequest: Failed to execute statement");
        }
        
        return $result;
    } catch (PDOException $e) {
        error_log("addOrganRequest: PDO Exception - " . $e->getMessage());
        return false;
    }
}

function updateOrganRequest($pdo, $data, $use_sample_data = false) {
    if ($use_sample_data) {
        return true;
    }
    
    if (!$pdo) {
        error_log("updateOrganRequest: PDO connection is null");
        return false;
    }
    
    try {
        $stmt = $pdo->prepare("UPDATE organ_request SET organ_type = ?, urgency = ?, notes = ?, updated_at = NOW() WHERE request_id = ?");
        $result = $stmt->execute([
            $data['organ_type'],
            $data['urgency'],
            $data['notes'],
            $data['request_id']
        ]);
        
        if ($result) {
            error_log("updateOrganRequest: Successfully updated organ request");
        }
        
        return $result;
    } catch (PDOException $e) {
        error_log("updateOrganRequest: PDO Exception - " . $e->getMessage());
        return false;
    }
}

function deleteOrganRequest($pdo, $request_id, $use_sample_data = false) {
    if ($use_sample_data) {
        return true;
    }
    
    if (!$pdo) {
        error_log("deleteOrganRequest: PDO connection is null");
        return false;
    }
    
    try {
        $stmt = $pdo->prepare("DELETE FROM organ_request WHERE request_id = ?");
        $result = $stmt->execute([$request_id]);
        
        if ($result) {
            error_log("deleteOrganRequest: Successfully deleted organ request");
        }
        
        return $result;
    } catch (PDOException $e) {
        error_log("deleteOrganRequest: PDO Exception - " . $e->getMessage());
        return false;
    }
}

function addRecipient($pdo, $data, $use_sample_data = false) {
    if ($use_sample_data) {
        // Simulate adding to sample data
        return true;
    }
    
    if (!$pdo) {
        error_log("addRecipient: PDO connection is null");
        return false;
    }
    
    try {
        $stmt = $pdo->prepare("INSERT INTO recipients (nic, name, organ_received, surgery_date, treatment_notes, status, hospital_registration_no) VALUES (?, ?, ?, ?, ?, 'Active', ?)");
        $result = $stmt->execute([
            $data['nic'],
            $data['name'],
            $data['organ_received'],
            $data['surgery_date'],
            $data['treatment_notes'],
            $data['hospital_registration_no']
        ]);
        
        if ($result) {
            error_log("addRecipient: Successfully inserted recipient");
        } else {
            error_log("addRecipient: Failed to execute statement");
        }
        
        return $result;
    } catch (PDOException $e) {
        error_log("addRecipient: PDO Exception - " . $e->getMessage());
        return false;
    }
}

function updateRecipient($pdo, $data, $use_sample_data = false) {
    if ($use_sample_data) {
        // Simulate updating sample data
        return true;
    }
    
    if (!$pdo) {
        error_log("updateRecipient: PDO connection is null");
        return false;
    }
    
    try {
        $stmt = $pdo->prepare("UPDATE recipients SET nic = ?, name = ?, organ_received = ?, surgery_date = ?, treatment_notes = ?, status = ?, updated_at = NOW() WHERE recipient_id = ?");
        $result = $stmt->execute([
            $data['nic'],
            $data['name'],
            $data['organ_received'],
            $data['surgery_date'],
            $data['treatment_notes'],
            $data['status'],
            $data['recipient_id']
        ]);
        
        if ($result) {
            error_log("updateRecipient: Successfully updated recipient");
        } else {
            error_log("updateRecipient: Failed to execute statement");
        }
        
        return $result;
    } catch (PDOException $e) {
        error_log("updateRecipient: PDO Exception - " . $e->getMessage());
        return false;
    }
}

function deleteRecipient($pdo, $recipient_id, $use_sample_data = false) {
    if ($use_sample_data) {
        // Simulate deleting from sample data
        return true;
    }
    
    if (!$pdo) {
        error_log("deleteRecipient: PDO connection is null");
        return false;
    }
    
    try {
        $stmt = $pdo->prepare("DELETE FROM recipients WHERE recipient_id = ?");
        $result = $stmt->execute([$recipient_id]);
        
        if ($result) {
            error_log("deleteRecipient: Successfully deleted recipient");
        } else {
            error_log("deleteRecipient: Failed to execute statement");
        }
        
        return $result;
    } catch (PDOException $e) {
        error_log("deleteRecipient: PDO Exception - " . $e->getMessage());
        return false;
    }
}

function addSuccessStory($pdo, $data, $use_sample_data = false) {
    if ($use_sample_data) {
        // Simulate adding to sample data
        return true;
    }
    
    if (!$pdo) {
        error_log("addSuccessStory: PDO connection is null");
        return false;
    }
    
    try {
        $stmt = $pdo->prepare("INSERT INTO success_stories (title, description, success_date, hospital_registration_no, status) VALUES (?, ?, ?, ?, 'Pending')");
        $result = $stmt->execute([
            $data['title'],
            $data['description'],
            $data['success_date'],
            $data['hospital_registration_no']
        ]);
        
        if ($result) {
            error_log("addSuccessStory: Successfully inserted success story");
        } else {
            error_log("addSuccessStory: Failed to execute statement");
        }
        
        return $result;
    } catch (PDOException $e) {
        error_log("addSuccessStory: PDO Exception - " . $e->getMessage());
        return false;
    }
}

function updateSuccessStory($pdo, $data, $use_sample_data = false) {
    if ($use_sample_data) {
        // Simulate updating sample data
        return true;
    }
    
    if (!$pdo) return false;
    
    try {
        $stmt = $pdo->prepare("UPDATE success_stories SET title = ?, description = ?, success_date = ?, status = ?, updated_at = NOW() WHERE story_id = ?");
        return $stmt->execute([
            $data['title'],
            $data['description'],
            $data['success_date'],
            $data['status'],
            $data['story_id']
        ]);
    } catch (PDOException $e) {
        return false;
    }
}

function deleteSuccessStory($pdo, $story_id, $use_sample_data = false) {
    if ($use_sample_data) {
        // Simulate deleting from sample data
        return true;
    }
    
    if (!$pdo) return false;
    
    try {
        $stmt = $pdo->prepare("DELETE FROM success_stories WHERE story_id = ?");
        return $stmt->execute([$story_id]);
    } catch (PDOException $e) {
        return false;
    }
}

// Get data for display
$organ_requests = getOrganRequests($pdo, $hospital_registration, $use_sample_data, $sample_organ_requests);
$recipients = getRecipients($pdo, $hospital_registration, $use_sample_data, $sample_recipients);
$success_stories = getSuccessStories($pdo, $hospital_registration, $use_sample_data, $sample_success_stories);
$aftercare_appointments = getAftercareAppointments($pdo, $hospital_registration, $use_sample_data, $sample_aftercare_appointments);

// Calculate stats
$stats = [
    'total_organ_requests' => count($organ_requests),
    'pending_requests' => count(array_filter($organ_requests, function($req) { return $req['status'] === 'Pending'; })),
    'approved_requests' => count(array_filter($organ_requests, function($req) { return $req['status'] === 'Approved'; })),
    'total_recipients' => count($recipients),
    'active_recipients' => count(array_filter($recipients, function($rec) { return $rec['status'] === 'Active'; })),
    'recovered_recipients' => count(array_filter($recipients, function($rec) { return $rec['status'] === 'Recovered'; })),
    'total_success_stories' => count($success_stories),
    'approved_stories' => count(array_filter($success_stories, function($story) { return $story['status'] === 'Approved'; })),
    'total_appointments' => count($aftercare_appointments),
    'scheduled_appointments' => count(array_filter($aftercare_appointments, function($apt) { return $apt['status'] === 'Scheduled'; }))
];

// Stats calculation completed

// Handle export requests
if (isset($_GET['action']) && $_GET['action'] === 'export_recipients' && isset($_GET['format'])) {
    $format = $_GET['format'];
    $filename = 'recipient_records_' . date('Y-m-d_His');
    
    if ($format === 'csv') {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '.csv"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['Patient NIC', 'Patient Name', 'Organ Received', 'Surgery Date', 'Treatment Notes', 'Status']);
        
        foreach ($recipients as $recipient) {
            fputcsv($output, [
                $recipient['nic'],
                $recipient['name'],
                ucfirst($recipient['organ_received']),
                date('d/m/Y', strtotime($recipient['surgery_date'])),
                $recipient['treatment_notes'] ?? '',
                $recipient['status']
            ]);
        }
        
        fclose($output);
        exit();
        
    } elseif ($format === 'xlsx') {
        // For Excel, we'll create a simple XML-based xlsx format
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '.xlsx"');
        
        // Simple CSV that Excel can read as xlsx
        $output = fopen('php://output', 'w');
        fputcsv($output, ['Patient NIC', 'Patient Name', 'Organ Received', 'Surgery Date', 'Treatment Notes', 'Status']);
        
        foreach ($recipients as $recipient) {
            fputcsv($output, [
                $recipient['nic'],
                $recipient['name'],
                ucfirst($recipient['organ_received']),
                date('d/m/Y', strtotime($recipient['surgery_date'])),
                $recipient['treatment_notes'] ?? '',
                $recipient['status']
            ]);
        }
        
        fclose($output);
        exit();
        
    } elseif ($format === 'pdf') {
        // Generate HTML for PDF (can be printed to PDF by browser)
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Recipient Records Report</title>
            <style>
                body { font-family: Arial, sans-serif; padding: 20px; }
                .watermark{position:fixed; inset:0; z-index:0; display:flex; align-items:center; justify-content:center; pointer-events:none;}
                .watermark img{width:520px; max-width:85%; opacity:.06;}
                .watermark .wm-text{position:absolute; font-size:64px; font-weight:700; color:#9ca3af; opacity:.10; transform:rotate(-25deg); text-align:center; letter-spacing:2px;}
                .content{position:relative; z-index:1;}
                .report-header{display:flex; align-items:center; gap:14px; margin-bottom:16px;}
                .report-header img{height:52px; width:auto;}
                h1 { color: #005baa; text-align: center; }
                .info { text-align: center; color: #666; margin-bottom: 30px; }
                table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                th { background: #005baa; color: white; padding: 12px; text-align: left; }
                td { padding: 10px; border-bottom: 1px solid #ddd; }
                tr:hover { background: #f5f5f5; }
                .status { padding: 4px 12px; border-radius: 12px; font-size: 11px; font-weight: bold; }
                .status-active { background: #dcfce7; color: #166534; }
                @media print {
                    body { margin: 0; }
                    button { display: none; }
                }
            </style>
        </head>
        <body>
            <div class="watermark">
                <img src="<?php echo ROOT; ?>/public/assets/images/logo.png" alt="LifeConnect">
                <div class="wm-text">LifeConnect Sri Lanka</div>
            </div>

            <div class="content">
            <div class="report-header">
                <img src="<?php echo ROOT; ?>/public/assets/images/logo.png" alt="LifeConnect">
                <div>
                    <div style="font-size:18px; font-weight:700; color:#111827; line-height:1.2;">LifeConnect</div>
                    <div style="font-size:12px; color:#6b7280;">Sri Lanka</div>
                </div>
            </div>

            <h1>Recipient Patient Records Report</h1>
            <div class="info">
                <p><strong>Hospital:</strong> <?php echo htmlspecialchars($hospital_name); ?></p>
                <p><strong>Generated:</strong> <?php echo date('d/m/Y H:i:s'); ?></p>
                <p><strong>Total Records:</strong> <?php echo count($recipients); ?></p>
            </div>
            <button onclick="window.print()" style="padding: 10px 20px; background: #005baa; color: white; border: none; border-radius: 4px; cursor: pointer; margin-bottom: 20px;">Print / Save as PDF</button>
            <table>
                <thead>
                    <tr>
                        <th>Patient NIC</th>
                        <th>Patient Name</th>
                        <th>Organ Received</th>
                        <th>Surgery Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recipients as $recipient): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($recipient['nic']); ?></td>
                        <td><?php echo htmlspecialchars($recipient['name']); ?></td>
                        <td><?php echo htmlspecialchars(ucfirst($recipient['organ_received'])); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($recipient['surgery_date'])); ?></td>
                        <td><span class="status status-active"><?php echo htmlspecialchars($recipient['status']); ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            </div>
        </body>
        </html>
        <?php
        exit();
    }
}

// Handle success messages from URL parameters - now using JavaScript notifications
if (isset($_GET['success'])) {
    // Success messages are now handled by JavaScript notifications
    // No need to set PHP variables since we use the notification system
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add_organ_request':
                $data = [
                    'registration_no' => $hospital_registration,
                    'organ_type' => $_POST['organ_type'],
                    'urgency' => $_POST['urgency'],
                    'notes' => $_POST['notes']
                ];
                
                if (addOrganRequest($pdo, $data, $use_sample_data)) {
                    // Redirect to refresh page and show success message via notification
                    header("Location: " . $_SERVER['PHP_SELF'] . "?success=organ_request_added");
                    exit();
                } else {
                    // Redirect with error message
                    header("Location: " . $_SERVER['PHP_SELF'] . "?error=organ_request_failed");
                    exit();
                }
                break;
                
            case 'update_organ_request':
                $data = [
                    'request_id' => $_POST['request_id'],
                    'organ_type' => $_POST['organ_type'],
                    'urgency' => $_POST['urgency'],
                    'notes' => $_POST['notes']
                ];
                
                if (updateOrganRequest($pdo, $data, $use_sample_data)) {
                    header("Location: " . $_SERVER['PHP_SELF'] . "?success=organ_request_updated");
                    exit();
                } else {
                    header("Location: " . $_SERVER['PHP_SELF'] . "?error=organ_request_update_failed");
                    exit();
                }
                break;
                
            case 'delete_organ_request':
                $request_id = $_POST['request_id'];
                if (deleteOrganRequest($pdo, $request_id, $use_sample_data)) {
                    header("Location: " . $_SERVER['PHP_SELF'] . "?success=organ_request_deleted");
                    exit();
                } else {
                    header("Location: " . $_SERVER['PHP_SELF'] . "?error=organ_request_delete_failed");
                    exit();
                }
                break;
                
            case 'add_recipient':
                error_log("add_recipient: Form submitted");
                error_log("add_recipient: use_sample_data = " . ($use_sample_data ? 'true' : 'false'));
                error_log("add_recipient: pdo = " . ($pdo ? 'connected' : 'null'));
                
                $data = [
                    'nic' => $_POST['nic'],
                    'name' => $_POST['name'],
                    'organ_received' => $_POST['organ_received'],
                    'surgery_date' => $_POST['surgery_date'],
                    'treatment_notes' => $_POST['treatment_notes'],
                    'hospital_registration_no' => $hospital_registration
                ];
                
                error_log("add_recipient: Data = " . json_encode($data));
                
                if (addRecipient($pdo, $data, $use_sample_data)) {
                    error_log("add_recipient: Success - redirecting");
                    // Redirect to refresh page and show success message via notification
                    header("Location: " . $_SERVER['PHP_SELF'] . "?success=recipient_added");
                    exit();
                } else {
                    error_log("add_recipient: Failed - redirecting with error");
                    // Redirect with error message
                    header("Location: " . $_SERVER['PHP_SELF'] . "?error=recipient_failed");
                    exit();
                }
                break;
                
            case 'update_recipient':
                $data = [
                    'recipient_id' => $_POST['recipient_id'],
                    'nic' => $_POST['nic'],
                    'name' => $_POST['name'],
                    'organ_received' => $_POST['organ_received'],
                    'surgery_date' => $_POST['surgery_date'],
                    'treatment_notes' => $_POST['treatment_notes'],
                    'status' => $_POST['status']
                ];
                if (updateRecipient($pdo, $data, $use_sample_data)) {
                    // Redirect to refresh page and show success message via notification
                    header("Location: " . $_SERVER['PHP_SELF'] . "?success=recipient_updated");
                    exit();
                } else {
                    // Redirect with error message
                    header("Location: " . $_SERVER['PHP_SELF'] . "?error=recipient_update_failed");
                    exit();
                }
                break;
                
            case 'delete_recipient':
                $recipient_id = $_POST['recipient_id'];
                if (deleteRecipient($pdo, $recipient_id, $use_sample_data)) {
                    // Redirect to refresh page and show success message via notification
                    header("Location: " . $_SERVER['PHP_SELF'] . "?success=recipient_deleted");
                    exit();
                } else {
                    // Redirect with error message
                    header("Location: " . $_SERVER['PHP_SELF'] . "?error=recipient_delete_failed");
                    exit();
                }
                break;
                
            case 'add_success_story':
                error_log("add_success_story: Form submitted");
                error_log("add_success_story: use_sample_data = " . ($use_sample_data ? 'true' : 'false'));
                error_log("add_success_story: pdo = " . ($pdo ? 'connected' : 'null'));
                
                $data = [
                    'title' => $_POST['title'],
                    'description' => $_POST['description'],
                    'success_date' => $_POST['success_date'],
                    'hospital_registration_no' => $hospital_registration
                ];
                
                error_log("add_success_story: Data = " . json_encode($data));
                
                if (addSuccessStory($pdo, $data, $use_sample_data)) {
                    error_log("add_success_story: Success - redirecting");
                    // Redirect to refresh page and show success message
                    header("Location: " . $_SERVER['PHP_SELF'] . "?success=story_added");
                    exit();
                } else {
                    error_log("add_success_story: Failed - redirecting with error");
                    // Redirect with error message
                    header("Location: " . $_SERVER['PHP_SELF'] . "?error=story_failed");
                    exit();
                }
                break;
                
            case 'update_success_story':
                $data = [
                    'story_id' => $_POST['story_id'],
                    'title' => $_POST['title'],
                    'description' => $_POST['description'],
                    'success_date' => $_POST['success_date'],
                    'status' => $_POST['status']
                ];
                if (updateSuccessStory($pdo, $data, $use_sample_data)) {
                    $success_message = "Success story updated successfully!";
                    header("Location: " . $_SERVER['PHP_SELF'] . "?success=story_updated");
                    exit();
                } else {
                    $error_message = "Failed to update success story!";
                }
                break;
                
            case 'delete_success_story':
                $story_id = $_POST['story_id'];
                if (deleteSuccessStory($pdo, $story_id, $use_sample_data)) {
                    $success_message = "Success story deleted successfully!";
                    header("Location: " . $_SERVER['PHP_SELF'] . "?success=story_deleted");
                    exit();
                } else {
                    $error_message = "Failed to delete success story!";
                }
                break;
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="<?php echo ROOT ?? '/life-connect'; ?>/public/assets/css/hospital/hospital.css">
    <title>Hospital Management - LifeConnect</title>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <a href="<?php echo ROOT ?? '/life-connect'; ?>" style="text-decoration:none; display:flex; align-items:center; gap:10px;">
                    <img src="<?php echo ROOT ?? '/life-connect'; ?>/public/assets/images/logo.png" alt="LifeConnect" style="height:40px; width: auto;">
                    <div>
                        <strong style="display:block; font-size:1.1rem; color:#003b6e; line-height:1.2;">LifeConnect</strong>
                        <p style="margin:0; font-size:.68rem; color:#6b7280; padding-top:2px;">Hospital Portal</p>
                    </div>
                </a>
            </div>
            <div class="user-info" onclick="toggleUserDropdown()">
                <div class="user-avatar"><?php echo strtoupper(substr($hospital_details['name'], 0, 1)); ?></div>
                <div class="user-details">
                    <div style="font-weight: 600; font-size: 0.9rem;"><?php echo htmlspecialchars($hospital_details['name']); ?></div>
                    <div style="font-size: 0.8rem; opacity: 0.8;"><?php echo htmlspecialchars($hospital_details['role']); ?></div>
                    <div style="font-size: 0.7rem; opacity: 0.6;">ID: <?php echo htmlspecialchars($hospital_details['registration']); ?></div>
                </div>
                <div class="user-actions">
                    <button class="btn-logout" onclick="logout()" title="Logout">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16,17 21,12 16,7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                    </button>
                </div>
                
                <!-- User Details Dropdown -->
                <div class="user-dropdown" id="user-dropdown">
                    <div class="dropdown-header">
                        <div class="user-avatar-large"><?php echo strtoupper(substr($hospital_details['name'], 0, 1)); ?></div>
                        <div>
                            <div class="user-name"><?php echo htmlspecialchars($hospital_details['name']); ?></div>
                            <div class="user-role"><?php echo htmlspecialchars($hospital_details['role']); ?></div>
                        </div>
                    </div>
                    <div class="dropdown-content">
                        <div class="detail-item">
                            <span class="detail-label">Hospital ID:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($hospital_details['registration']); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Email:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($hospital_details['email']); ?></span>
                        </div>
                        <?php if (isset($hospital_details['address'])): ?>
                        <div class="detail-item">
                            <span class="detail-label">Address:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($hospital_details['address']); ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if (isset($hospital_details['phone'])): ?>
                        <div class="detail-item">
                            <span class="detail-label">Phone:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($hospital_details['phone']); ?></span>
                        </div>
                        <?php endif; ?>
                        <div class="detail-item">
                            <span class="detail-label">Status:</span>
                            <span class="detail-value status-active"><?php echo htmlspecialchars($hospital_details['status']); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Last Login:</span>
                            <span class="detail-value"><?php echo date('M d, Y H:i', strtotime($hospital_details['last_login'])); ?></span>
                        </div>
                    </div>
                    <div class="dropdown-footer">
                        <button class="btn btn-secondary btn-small" onclick="editProfile()">Edit Profile</button>
                        <button class="btn btn-danger btn-small" onclick="logout()">Logout</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="container">
        <div class="main-content">
            <div class="sidebar">
                <div class="sidebar-header">
                    <h3>Hospital Portal</h3>
                    <p>Clinical coordination</p>
                </div>

                <div class="menu-section">
                    <div class="menu-section-title">Sections</div>
                    <div class="menu-item active" onclick="showContent('overview')">
                        <span class="icon"><i class="fas fa-chart-line"></i></span>
                        <span>Overview</span>
                    </div>
                    <div class="menu-item" onclick="showContent('organ-requests')">
                        <span class="icon"><i class="fas fa-heart"></i></span>
                        <span>Organ Requests</span>
                    </div>
                    <div class="menu-item" onclick="showContent('eligibility')">
                        <span class="icon"><i class="fas fa-check-circle"></i></span>
                        <span>Update Eligibility</span>
                    </div>
                    <div class="menu-item" onclick="showContent('recipients')">
                        <span class="icon"><i class="fas fa-user"></i></span>
                        <span>Recipient Patients</span>
                    </div>
                    <div class="menu-item" onclick="showContent('stories')">
                        <span class="icon"><i class="fas fa-star"></i></span>
                        <span>Success Stories</span>
                    </div>
                </div>

                <div class="menu-section menu-section--footer">
                    <a href="<?php echo ROOT; ?>/logout" class="menu-item menu-item--danger" style="text-decoration: none; display: block;">
                        <span class="icon"><i class="fas fa-right-from-bracket"></i></span>
                        <span>Logout</span>
                    </a>
                </div>
            </div>

            <div class="content-area" id="content-area">
                <div id="overview" class="content-section">
                    <div class="content-header">
                        <h2>Hospital Overview</h2>
                        <p>Monitor organ requests, donor eligibility, and recipient management.</p>
                    </div>
                    <div class="content-body">
                        <div class="stats-grid">
                            <div class="stat-card">
                                <div class="stat-number"><?php echo $stats['total_organ_requests']; ?></div>
                                <div class="stat-label">Total Organ Requests</div>
                                <div class="stat-change neutral"><?php echo $stats['pending_requests']; ?> pending</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number"><?php echo $stats['total_recipients']; ?></div>
                                <div class="stat-label">Total Recipients</div>
                                <div class="stat-change positive"><?php echo $stats['active_recipients']; ?> active</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number"><?php echo $stats['total_success_stories']; ?></div>
                                <div class="stat-label">Success Stories</div>
                                <div class="stat-change positive"><?php echo $stats['approved_stories']; ?> approved</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number"><?php echo $stats['total_appointments']; ?></div>
                                <div class="stat-label">Aftercare Appointments</div>
                                <div class="stat-change positive"><?php echo $stats['scheduled_appointments']; ?> scheduled</div>
                            </div>
                        </div>


                        <div class="feature-grid">
                            <div class="feature-card" onclick="showContent('organ-requests')" style="cursor: pointer;">
                                <div class="feature-icon">
                                    <img src="/life-connect-main/public/assets/images/organ-requests.svg" alt="Organ Requests">
                                </div>
                                <h3>Manage Organ Requests</h3>
                                <p>Create, edit, and delete organ requests with urgency levels for patient matching.</p>
                            </div>
                            <div class="feature-card" onclick="showContent('eligibility')" style="cursor: pointer;">
                                <div class="feature-icon">
                                    <img src="/life-connect-main/public/assets/images/search-donors.svg" alt="Search Donors">
                                </div>
                                <h3>Search Donors</h3>
                                <p>Filter donors by organ type, blood group, age, and location for optimal matching.</p>
                            </div>
                            <div class="feature-card" onclick="showContent('eligibility')" style="cursor: pointer;">
                                <div class="feature-icon">
                                    <img src="/life-connect-main/public/assets/images/update-eligibility.svg" alt="Update Eligibility">
                                </div>
                                <h3>Update Eligibility</h3>
                                <p>Update donor eligibility status after medical evaluations and screening.</p>
                            </div>
                            <div class="feature-card" onclick="showContent('stories')" style="cursor: pointer;">
                                <div class="feature-icon">
                                    <img src="/life-connect-main/public/assets/images/tribute.jpg" alt="Success Stories" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                                </div>
                                <h3>Success Stories</h3>
                                <p>Add and manage inspiring success stories with photos and media uploads.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="organ-requests" class="content-section" style="display: none;">
                    <div class="content-header">
                        <h2>Organ Requests Management</h2>
                        <p>Create, edit, and delete organ requests with urgency selection.</p>
                    </div>
                    <div class="content-body">
                        <div class="action-section">
                            <h3>Request Actions</h3>
                            <div class="action-buttons">
                                <button class="btn btn-primary" onclick="openRequestModal()">Add New Request</button>
                            </div>
                        </div>

                        <!-- Organ Request Options with Images -->
                        <div class="organ-request-options">
                            <h3 style="text-align: center; margin-bottom: 2rem; color: #2c3e50; font-size: 1.5rem;">Organ Request Types</h3>
                            <div class="organ-options-grid">
                                <div class="organ-option-card" onclick="selectOrganType('kidney')">
                                    <div class="option-image-container">
                                        <img src="https://images.unsplash.com/photo-1559757148-5c350d0d3c56?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=400&q=80" 
                                             alt="Kidney Transplant" 
                                             style="width: 100%; height: 150px; object-fit: cover; border-radius: 12px;">
                                    </div>
                                    <div class="option-content">
                                        <h4>Kidney Transplant</h4>
                                        <p>Request kidney transplant for patients with end-stage renal disease.</p>
                                        <div class="option-badge">Most Common</div>
                                    </div>
                                </div>
                                
                                <div class="organ-option-card" onclick="selectOrganType('liver')">
                                    <div class="option-image-container">
                                        <img src="/life-connect-main/public/assets/images/liver-transplant.jpg" 
                                             alt="Liver Transplant" 
                                             style="width: 100%; height: 150px; object-fit: cover; border-radius: 12px;">
                                    </div>
                                    <div class="option-content">
                                        <h4>Liver Transplant</h4>
                                        <p>Request liver transplant for patients with liver failure or cirrhosis.</p>
                                        <div class="option-badge">Complex Surgery</div>
                                    </div>
                                </div>
                                
                                <div class="organ-option-card" onclick="selectOrganType('heart')">
                                    <div class="option-image-container">
                                        <img src="https://images.unsplash.com/photo-1582750433449-648ed127bb54?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=400&q=80" 
                                             alt="Heart Transplant" 
                                             style="width: 100%; height: 150px; object-fit: cover; border-radius: 12px;">
                                    </div>
                                    <div class="option-content">
                                        <h4>Heart Transplant</h4>
                                        <p>Request heart transplant for patients with end-stage heart failure.</p>
                                        <div class="option-badge">Critical Priority</div>
                                    </div>
                                </div>
                                
                                <div class="organ-option-card" onclick="selectOrganType('lung')">
                                    <div class="option-image-container">
                                        <img src="https://images.unsplash.com/photo-1530026405186-ed1f139313f8?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" 
                                             alt="Lung Transplant" 
                                             style="width: 100%; height: 150px; object-fit: cover; border-radius: 12px;">
                                    </div>
                                    <div class="option-content">
                                        <h4>Lung Transplant</h4>
                                        <p>Request lung transplant for patients with severe respiratory failure.</p>
                                        <div class="option-badge">High Risk</div>
                                    </div>
                                </div>
                                
                                <div class="organ-option-card" onclick="selectOrganType('skin')">
                                    <div class="option-image-container">
                                        <img src="https://images.unsplash.com/photo-1576091160550-2173dba999ef?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" 
                                             alt="Skin Graft" 
                                             style="width: 100%; height: 150px; object-fit: cover; border-radius: 12px;">
                                    </div>
                                    <div class="option-content">
                                        <h4>Skin Graft</h4>
                                        <p>Request skin graft for burn victims and reconstructive surgery.</p>
                                        <div class="option-badge">Reconstructive</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="search-bar">
                            <span class="search-icon">🔍</span>
                            <input type="text" class="search-input" placeholder="Search by organ type or patient ID...">
                        </div>

                        <div class="filter-section">
                            <select class="filter-select">
                                <option value="">All Organs</option>
                                <option value="kidney">Kidney</option>
                                <option value="liver">Liver</option>
                                <option value="heart">Heart</option>
                            </select>
                            <select class="filter-select">
                                <option value="">All Urgency</option>
                                <option value="urgent">Urgent</option>
                                <option value="high">High</option>
                                <option value="medium">Medium</option>
                                <option value="low">Low</option>
                            </select>
                        </div>

                        <div class="data-table">
                            <div class="table-header">
                                <h4>Organ Requests</h4>
                            </div>
                            <div class="table-content" id="organ-requests-table">
                                <div class="table-row" style="font-weight: 600; background: var(--gray-bg-color);">
                                    <div class="table-cell">Organ Type</div>
                                    <div class="table-cell">Urgency</div>
                                    <div class="table-cell">Notes</div>
                                    <div class="table-cell">Created Date</div>
                                    <div class="table-cell">Actions</div>
                                </div>

                                <?php if (empty($organ_requests)): ?>
                                <div class="table-row">
                                    <div class="table-cell" colspan="5" style="text-align: center; padding: 2rem; color: #666;">
                                        No organ requests found. Click "Add New Request" to create one.
                                    </div>
                                </div>
                                <?php else: ?>
                                    <?php foreach ($organ_requests as $request): ?>
                                    <div class="table-row">
                                        <div class="table-cell name" data-label="Organ Type"><?php echo htmlspecialchars(ucfirst($request['organ_type'])); ?></div>
                                        <div class="table-cell" data-label="Urgency">
                                            <span class="status-badge <?php 
                                                echo $request['urgency'] === 'urgent' ? 'status-danger' : 
                                                     ($request['urgency'] === 'high' ? 'status-active' : 'status-pending'); 
                                            ?>"><?php echo htmlspecialchars(ucfirst($request['urgency'])); ?></span>
                                        </div>
                                        <div class="table-cell" data-label="Notes"><?php echo htmlspecialchars($request['notes'] ?? 'No notes'); ?></div>
                                        <div class="table-cell" data-label="Created Date"><?php echo htmlspecialchars(date('Y-m-d', strtotime($request['request_date'] ?? $request['created_at']))); ?></div>
                                        <div class="table-cell" data-label="Actions">
                                            <button class="btn btn-secondary btn-small" onclick="editRequest(<?php echo $request['request_id']; ?>)">Edit</button>
                                            <button class="btn btn-danger btn-small" onclick="deleteRequest(<?php echo $request['request_id']; ?>)">Delete</button>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>


                <div id="eligibility" class="content-section" style="display: none;">
                    <div class="content-header">
                        <h2>Update Donor Eligibility</h2>
                        <p>Update donor eligibility status after medical evaluations and screening.</p>
                    </div>
                    <div class="content-body">
                        <div class="search-bar">
                            <span class="search-icon">🔍</span>
                            <input type="text" class="search-input" placeholder="Search by donor NIC or name...">
                        </div>

                        <div class="data-table">
                            <div class="table-header">
                                <h4>Pending Eligibility Reviews</h4>
                            </div>
                            <div class="table-content">
                                <div class="table-row" style="font-weight: 600; background: var(--gray-bg-color);">
                                    <div class="table-cell">Donor Details</div>
                                    <div class="table-cell">Organ Type</div>
                                    <div class="table-cell">Test Date</div>
                                    <div class="table-cell">Current Status</div>
                                    <div class="table-cell">Actions</div>
                                </div>

                                <div class="table-row">
                                    <div class="table-cell name" data-label="Donor Details">NIC 2001XXXXXXX - S. Fernando</div>
                                    <div class="table-cell" data-label="Organ Type">Kidney</div>
                                    <div class="table-cell" data-label="Test Date">2025-10-10</div>
                                    <div class="table-cell" data-label="Current Status"><span class="status-badge status-pending">Under Review</span></div>
                                    <div class="table-cell" data-label="Actions">
                                        <button class="btn btn-success btn-small" onclick="approveEligibility()">Approve</button>
                                        <button class="btn btn-danger btn-small" onclick="rejectEligibility()">Reject</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="recipients" class="content-section" style="display: none;">
                    <div class="content-header">
                        <h2>Recipient Patient Management</h2>
                        <p>Add, update, and view recipient patient records and treatment logs.</p>
                    </div>
                    <div class="content-body">
                        <div class="action-section">
                            <h3>Patient Actions</h3>
                            <div class="action-buttons">
                                <button class="btn btn-primary" onclick="openRecipientModal()">Add Recipient</button>
                                <button class="btn btn-secondary" onclick="exportRecipients()">Export Records</button>
                            </div>
                        </div>

                        <div class="search-bar">
                            <span class="search-icon">🔍</span>
                            <input type="text" class="search-input" placeholder="Search by patient ID or name...">
                        </div>

                        <div class="data-table">
                            <div class="table-header">
                                <h4>Recipient Patients</h4>
                            </div>
                            <div class="table-content" id="recipients-table">
                                <div class="table-row" style="font-weight: 600; background: var(--gray-bg-color);">
                                    <div class="table-cell">Patient NIC</div>
                                    <div class="table-cell">Patient Name</div>
                                    <div class="table-cell">Organ Received</div>
                                    <div class="table-cell">Surgery Date</div>
                                    <div class="table-cell">Status</div>
                                    <div class="table-cell">Actions</div>
                                </div>

                                <?php if (empty($recipients)): ?>
                                <div class="table-row">
                                    <div class="table-cell" colspan="6" style="text-align: center; padding: 2rem; color: #666;">
                                        No recipient patients found. Click "Add Recipient" to create one.
                                    </div>
                                </div>
                                <?php else: ?>
                                    <?php foreach ($recipients as $recipient): ?>
                                    <div class="table-row">
                                        <div class="table-cell name" data-label="Patient NIC"><?php echo htmlspecialchars($recipient['nic']); ?></div>
                                        <div class="table-cell" data-label="Patient Name"><?php echo htmlspecialchars($recipient['name']); ?></div>
                                        <div class="table-cell" data-label="Organ Received"><?php echo htmlspecialchars(ucfirst($recipient['organ_received'])); ?></div>
                                        <div class="table-cell" data-label="Surgery Date"><?php echo htmlspecialchars(date('d/m/Y', strtotime($recipient['surgery_date']))); ?></div>
                                        <div class="table-cell" data-label="Status">
                                            <span class="status-badge <?php 
                                                echo $recipient['status'] === 'Active' ? 'status-active' : 
                                                     ($recipient['status'] === 'Recovered' ? 'status-success' : 'status-pending'); 
                                            ?>"><?php echo htmlspecialchars($recipient['status']); ?></span>
                                        </div>
                                        <div class="table-cell" data-label="Actions">
                                            <button class="btn btn-secondary btn-small" onclick="editRecipient(<?php echo $recipient['recipient_id']; ?>)" style="margin-right: 0.5rem;">Edit</button><button class="btn btn-danger btn-small" onclick="deleteRecipient(<?php echo $recipient['recipient_id']; ?>)">Delete</button>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="stories" class="content-section" style="display: none;">
                    <div class="content-header">
                        <h2>Success Stories Management</h2>
                        <p>Add and manage success stories with photos and media uploads.</p>
                    </div>
                    <div class="content-body">
                        <div class="action-section">
                            <h3>Story Actions</h3>
                            <div class="action-buttons">
                                <button class="btn btn-primary" onclick="openStoryModal()">Add Success Story</button>
                            </div>
                        </div>

                        <div class="data-table">
                            <div class="table-header">
                                <h4>Success Stories</h4>
                            </div>
                            <div class="table-content">
                                <div class="table-row" style="font-weight: 600; background: var(--gray-bg-color);">
                                    <div class="table-cell">Story Title</div>
                                    <div class="table-cell">Description</div>
                                    <div class="table-cell">Date</div>
                                    <div class="table-cell">Status</div>
                                    <div class="table-cell">Actions</div>
                                </div>

                                <div class="table-row">
                                    <div class="table-cell name" data-label="Story Title">A Life Saved - Kidney Transplant Success</div>
                                    <div class="table-cell" data-label="Description">Kidney transplant is successful</div>
                                    <div class="table-cell" data-label="Date">2025-09-15</div>
                                    <div class="table-cell" data-label="Status"><span class="status-badge status-pending">Pending Review</span></div>
                                    <div class="table-cell" data-label="Actions">
                                        <button class="btn btn-secondary btn-small" onclick="editStory()">Edit</button>
                                        <button class="btn btn-danger btn-small" onclick="deleteStory()">Delete</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Organ Request Modal -->
    <div class="modal" id="request-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add Organ Request</h3>
                <button class="modal-close" onclick="closeRequestModal()">×</button>
            </div>
            <div>
                <div class="form-group">
                    <label class="form-label">Organ Type</label>
                    <select class="form-select" id="organ-type">
                        <option value="">Select Organ</option>
                        <option value="kidney">Kidney</option>
                        <option value="liver">Liver</option>
                        <option value="heart">Heart</option>
                        <option value="lung">Lung</option>
                        <option value="skin">Skin</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Urgency Level</label>
                    <select class="form-select" id="urgency-level">
                        <option value="">Select Urgency</option>
                        <option value="urgent">Urgent</option>
                        <option value="high">High</option>
                        <option value="medium">Medium</option>
                        <option value="low">Low</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Additional Notes</label>
                    <textarea class="form-textarea" id="request-notes" placeholder="Any additional information..."></textarea>
                </div>
                <button class="btn btn-primary" onclick="saveRequest()">Save Request</button>
            </div>
        </div>
    </div>

    <!-- Recipient Modal -->
    <div class="modal" id="recipient-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add Recipient Patient</h3>
                <button class="modal-close" onclick="closeRecipientModal()">×</button>
            </div>
            <div>
                <div class="form-group">
                    <label class="form-label">Patient NIC</label>
                    <input type="text" class="form-input" id="recipient-nic" placeholder="1999XXXXXXX">
                </div>
                <div class="form-group">
                    <label class="form-label">Patient Name</label>
                    <input type="text" class="form-input" id="recipient-name" placeholder="Full name">
                </div>
                <div class="form-group">
                    <label class="form-label">Organ Received</label>
                    <select class="form-select" id="recipient-organ">
                        <option value="">Select Organ</option>
                        <option value="kidney">Kidney</option>
                        <option value="liver">Liver</option>
                        <option value="heart">Heart</option>
                        <option value="lung">Lung</option>
                        <option value="skin">Skin</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Surgery Date</label>
                    <input type="date" class="form-input" id="surgery-date">
                </div>
                <div class="form-group">
                    <label class="form-label">Treatment Notes</label>
                    <textarea class="form-textarea" id="treatment-notes" placeholder="Post-surgery treatment details..."></textarea>
                </div>
                <button class="btn btn-primary" onclick="saveRecipient()">Save Recipient</button>
            </div>
        </div>
    </div>

    <!-- Export Modal -->
    <div class="modal" id="export-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Export Recipient Records</h3>
                <button class="modal-close" onclick="closeExportModal()">×</button>
            </div>
            <div>
                <div class="form-group">
                    <label class="form-label">Select Export Format</label>
                    <select class="form-select" id="export-format">
                        <option value="">Choose format...</option>
                        <option value="xlsx">Excel (.xlsx) - For data analysis</option>
                        <option value="csv">CSV (.csv) - For database imports</option>
                        <option value="pdf">PDF (.pdf) - For printing & documentation</option>
                    </select>
                </div>
                <div class="form-group">
                    <p style="color: #666; font-size: 0.9rem; margin: 0;">
                        📊 This will export all recipient patient records including NIC, name, organ received, surgery date, and treatment notes.
                    </p>
                </div>
                <button class="btn btn-primary" onclick="downloadExport()">Download Export</button>
            </div>
        </div>
    </div>

    <!-- Story Modal -->
    <div class="modal" id="story-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add Success Story</h3>
                <button class="modal-close" onclick="closeStoryModal()">×</button>
            </div>
            <div>
                <div class="form-group">
                    <label class="form-label">Story Title</label>
                    <input type="text" class="form-input" id="story-title" placeholder="Enter story title">
                </div>
                <div class="form-group">
                    <label class="form-label">Story Description</label>
                    <textarea class="form-textarea" id="story-description" placeholder="Describe the success story..."></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Date of Success</label>
                    <input type="date" class="form-input" id="success-date">
                </div>
                <button class="btn btn-primary" onclick="saveStory()">Save Story</button>
            </div>
        </div>
    </div>

    <script>
        function showContent(id) {
            // Hide all content sections
            document.querySelectorAll('.content-section').forEach(s => s.style.display = 'none');
            const target = document.getElementById(id);
            if (target) target.style.display = '';
            
            // Update active menu item
            document.querySelectorAll('.menu-item').forEach(i => i.classList.remove('active'));
            const item = Array.from(document.querySelectorAll('.menu-item')).find(mi => mi.getAttribute('onclick')?.includes(id));
            if (item) item.classList.add('active');
            
            // Load data for specific sections
            if (id === 'recipients') {
                loadRecipients();
            } else if (id === 'organ-requests') {
                loadOrganRequests();
            } else if (id === 'stories') {
                loadStories();
            }
        }

        // Organ Request Functions
        function openRequestModal() { 
            document.getElementById('request-modal').classList.add('show'); 
        }
        function closeRequestModal() { 
            document.getElementById('request-modal').classList.remove('show'); 
            // Reset form fields
            document.getElementById('organ-type').value = '';
            document.getElementById('urgency-level').value = '';
            document.getElementById('request-notes').value = '';
        }
        
        // Organ Type Selection Function
        function selectOrganType(organType) {
            // Remove selected class from all cards
            document.querySelectorAll('.organ-option-card').forEach(card => {
                card.classList.remove('selected');
            });
            
            // Add selected class to clicked card
            event.currentTarget.classList.add('selected');
            
            // Set the organ type in the modal form
            const organSelect = document.getElementById('organ-type');
            if (organSelect) {
                organSelect.value = organType;
            }
            
            // Show notification
            showServerMessage(`Selected organ type: ${organType.charAt(0).toUpperCase() + organType.slice(1)}`, 'success');
            
            // Open the request modal
            openRequestModal();
        }
        function saveRequest() { 
            const organ = document.getElementById('organ-type').value;
            const urgency = document.getElementById('urgency-level').value;
            const notes = document.getElementById('request-notes').value;
            
            if (!organ || !urgency) {
                showServerMessage('Please select both organ type and urgency level', 'error');
                return;
            }
            
            // Close modal before submitting
            closeRequestModal();
            
            // Submit form to same page
            const form = document.createElement('form');
            form.method = 'POST';
            form.style.display = 'none';
            
            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = 'add_organ_request';
            form.appendChild(actionInput);
            
            const organInput = document.createElement('input');
            organInput.type = 'hidden';
            organInput.name = 'organ_type';
            organInput.value = organ;
            form.appendChild(organInput);
            
            const urgencyInput = document.createElement('input');
            urgencyInput.type = 'hidden';
            urgencyInput.name = 'urgency';
            urgencyInput.value = urgency;
            form.appendChild(urgencyInput);
            
            const notesInput = document.createElement('input');
            notesInput.type = 'hidden';
            notesInput.name = 'notes';
            notesInput.value = notes;
            form.appendChild(notesInput);
            
            document.body.appendChild(form);
            form.submit();
        }
        function editRequest(requestId) { 
            // Get request data and populate edit form
            const requests = <?php echo json_encode($organ_requests); ?>;
            const request = requests.find(r => r.request_id == requestId);
            
            if (request) {
                // Update modal header
                document.querySelector('#request-modal .modal-header h3').textContent = 'Edit Organ Request';
                
                // Populate form fields
                document.getElementById('organ-type').value = request.organ_type;
                document.getElementById('urgency-level').value = request.urgency;
                document.getElementById('request-notes').value = request.notes || '';
                
                // Change the save button to update button
                const saveButton = document.querySelector('#request-modal button[onclick="saveRequest()"]');
                if (saveButton) {
                    saveButton.textContent = 'Update Request';
                    saveButton.setAttribute('onclick', 'updateRequest(' + requestId + ')');
                }
                
                // Show the modal
                document.getElementById('request-modal').classList.add('show');
            }
        }
        
        function updateRequest(requestId) {
            const organ = document.getElementById('organ-type').value;
            const urgency = document.getElementById('urgency-level').value;
            const notes = document.getElementById('request-notes').value;
            
            if (!organ || !urgency) {
                showServerMessage('Please select both organ type and urgency level', 'error');
                return;
            }
            
            // Close modal before submitting
            closeRequestModal();
            
            // Reset modal for next use
            document.querySelector('#request-modal .modal-header h3').textContent = 'Add Organ Request';
            const saveButton = document.querySelector('#request-modal button[onclick*="updateRequest"]');
            if (saveButton) {
                saveButton.textContent = 'Save Request';
                saveButton.setAttribute('onclick', 'saveRequest()');
            }
            
            // Submit form to same page
            const form = document.createElement('form');
            form.method = 'POST';
            form.style.display = 'none';
            
            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = 'update_organ_request';
            form.appendChild(actionInput);
            
            const requestIdInput = document.createElement('input');
            requestIdInput.type = 'hidden';
            requestIdInput.name = 'request_id';
            requestIdInput.value = requestId;
            form.appendChild(requestIdInput);
            
            const organInput = document.createElement('input');
            organInput.type = 'hidden';
            organInput.name = 'organ_type';
            organInput.value = organ;
            form.appendChild(organInput);
            
            const urgencyInput = document.createElement('input');
            urgencyInput.type = 'hidden';
            urgencyInput.name = 'urgency';
            urgencyInput.value = urgency;
            form.appendChild(urgencyInput);
            
            const notesInput = document.createElement('input');
            notesInput.type = 'hidden';
            notesInput.name = 'notes';
            notesInput.value = notes;
            form.appendChild(notesInput);
            
            document.body.appendChild(form);
            form.submit();
        }
        function deleteRequest(requestId) { 
            if (confirm('Are you sure you want to delete this organ request?')) {
                // Submit form to same page
                const form = document.createElement('form');
                form.method = 'POST';
                form.style.display = 'none';
                
                const actionInput = document.createElement('input');
                actionInput.type = 'hidden';
                actionInput.name = 'action';
                actionInput.value = 'delete_organ_request';
                form.appendChild(actionInput);
                
                const requestIdInput = document.createElement('input');
                requestIdInput.type = 'hidden';
                requestIdInput.name = 'request_id';
                requestIdInput.value = requestId;
                form.appendChild(requestIdInput);
                
                document.body.appendChild(form);
                form.submit();
            }
        }
        
        function loadOrganRequests() {
            // Use PHP data directly
            const requests = <?php echo json_encode($organ_requests); ?>;
            console.log('Loading organ requests:', requests);
            if (requests && requests.length > 0) {
                updateOrganRequestsTable(requests);
            }
        }
        
        function updateOrganRequestsTable(requests) {
            const tableContent = document.querySelector('#organ-requests-table');
            if (!tableContent) return;
            
            // Clear existing rows (except header)
            const existingRows = tableContent.querySelectorAll('.table-row:not(:first-child)');
            existingRows.forEach(row => row.remove());
            
            if (requests.length === 0) {
                const row = document.createElement('div');
                row.className = 'table-row';
                row.innerHTML = `
                    <div class="table-cell" colspan="5" style="text-align: center; padding: 2rem; color: #666;">
                        No organ requests found. Click "Add New Request" to create one.
                    </div>
                `;
                tableContent.appendChild(row);
                return;
            }
            
            // Add new rows
            requests.forEach(request => {
                const row = document.createElement('div');
                row.className = 'table-row';
                
                const urgencyClass = request.urgency === 'urgent' ? 'status-danger' : 
                                    (request.urgency === 'high' ? 'status-active' : 'status-pending');
                const urgencyText = request.urgency.charAt(0).toUpperCase() + request.urgency.slice(1);
                const organType = request.organ_type.charAt(0).toUpperCase() + request.organ_type.slice(1);
                const requestDate = new Date(request.request_date || request.created_at).toLocaleDateString('en-GB');
                
                row.innerHTML = `
                    <div class="table-cell name" data-label="Organ Type">${organType}</div>
                    <div class="table-cell" data-label="Urgency">
                        <span class="status-badge ${urgencyClass}">${urgencyText}</span>
                    </div>
                    <div class="table-cell" data-label="Notes">${request.notes || 'No notes'}</div>
                    <div class="table-cell" data-label="Created Date">${requestDate}</div>
                    <div class="table-cell" data-label="Actions">
                        <button class="btn btn-secondary btn-small" onclick="editRequest(${request.request_id})">Edit</button>
                        <button class="btn btn-danger btn-small" onclick="deleteRequest(${request.request_id})">Delete</button>
                    </div>
                `;
                tableContent.appendChild(row);
            });
        }


        // Eligibility Functions
        function approveEligibility() { showServerMessage('localhost: Donor eligibility approved and updated in database', 'success'); }
        function rejectEligibility() { showServerMessage('localhost: Donor eligibility rejected and status updated', 'error'); }

        // Recipient Functions
        function openRecipientModal() { document.getElementById('recipient-modal').classList.add('show'); }
        function closeRecipientModal() { 
            document.getElementById('recipient-modal').classList.remove('show');
            // Reset modal to add mode
            document.querySelector('#recipient-modal .modal-header h3').textContent = 'Add Recipient Patient';
            document.getElementById('recipient-nic').value = '';
            document.getElementById('recipient-name').value = '';
            document.getElementById('recipient-organ').value = '';
            document.getElementById('surgery-date').value = '';
            document.getElementById('treatment-notes').value = '';
            
            // Reset button
            const saveButton = document.querySelector('#recipient-modal button[onclick*="updateRecipient"]');
            if (saveButton) {
                saveButton.textContent = 'Save Recipient';
                saveButton.setAttribute('onclick', 'saveRecipient()');
            }
        }
        function saveRecipient() { 
            const nic = document.getElementById('recipient-nic').value;
            const name = document.getElementById('recipient-name').value;
            const organ = document.getElementById('recipient-organ').value;
            const date = document.getElementById('surgery-date').value;
            const notes = document.getElementById('treatment-notes').value;
            
            if (!nic || !name || !organ || !date) {
                showServerMessage('localhost: Error - Please fill all required fields', 'error');
                return;
            }
            
            // Submit form to same page
            const form = document.createElement('form');
            form.method = 'POST';
            form.style.display = 'none';
            
            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = 'add_recipient';
            form.appendChild(actionInput);
            
            const nicInput = document.createElement('input');
            nicInput.type = 'hidden';
            nicInput.name = 'nic';
            nicInput.value = nic;
            form.appendChild(nicInput);
            
            const nameInput = document.createElement('input');
            nameInput.type = 'hidden';
            nameInput.name = 'name';
            nameInput.value = name;
            form.appendChild(nameInput);
            
            const organInput = document.createElement('input');
            organInput.type = 'hidden';
            organInput.name = 'organ_received';
            organInput.value = organ;
            form.appendChild(organInput);
            
            const dateInput = document.createElement('input');
            dateInput.type = 'hidden';
            dateInput.name = 'surgery_date';
            dateInput.value = date;
            form.appendChild(dateInput);
            
            const notesInput = document.createElement('input');
            notesInput.type = 'hidden';
            notesInput.name = 'treatment_notes';
            notesInput.value = notes;
            form.appendChild(notesInput);
            
            document.body.appendChild(form);
            form.submit();
        }
        function editRecipient(recipientId) { 
            // Get recipient data and populate edit form
            const recipients = <?php echo json_encode($recipients); ?>;
            const recipient = recipients.find(r => r.recipient_id == recipientId);
            
            if (recipient) {
                // Update modal header
                document.querySelector('#recipient-modal .modal-header h3').textContent = 'Edit Recipient Patient';
                
                // Populate form fields
                document.getElementById('recipient-nic').value = recipient.nic;
                document.getElementById('recipient-name').value = recipient.name;
                document.getElementById('recipient-organ').value = recipient.organ_received;
                document.getElementById('surgery-date').value = recipient.surgery_date;
                document.getElementById('treatment-notes').value = recipient.treatment_notes;
                
                // Change the save button to update button
                const saveButton = document.querySelector('#recipient-modal button[onclick="saveRecipient()"]');
                if (saveButton) {
                    saveButton.textContent = 'Update Recipient';
                    saveButton.setAttribute('onclick', 'updateRecipient(' + recipientId + ')');
                }
                
                // Show the modal
                document.getElementById('recipient-modal').classList.add('show');
            }
        }
        function updateRecipient(recipientId) {
            const nic = document.getElementById('recipient-nic').value;
            const name = document.getElementById('recipient-name').value;
            const organ = document.getElementById('recipient-organ').value;
            const surgery_date = document.getElementById('surgery-date').value;
            const notes = document.getElementById('treatment-notes').value;
            
            if (!nic || !name || !organ || !surgery_date) {
                showServerMessage('localhost: Error - Please fill all required fields', 'error');
                return;
            }
            
            // Submit form to same page
            const form = document.createElement('form');
            form.method = 'POST';
            form.style.display = 'none';
            
            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = 'update_recipient';
            form.appendChild(actionInput);
            
            const recipientIdInput = document.createElement('input');
            recipientIdInput.type = 'hidden';
            recipientIdInput.name = 'recipient_id';
            recipientIdInput.value = recipientId;
            form.appendChild(recipientIdInput);
            
            const nicInput = document.createElement('input');
            nicInput.type = 'hidden';
            nicInput.name = 'nic';
            nicInput.value = nic;
            form.appendChild(nicInput);
            
            const nameInput = document.createElement('input');
            nameInput.type = 'hidden';
            nameInput.name = 'name';
            nameInput.value = name;
            form.appendChild(nameInput);
            
            const organInput = document.createElement('input');
            organInput.type = 'hidden';
            organInput.name = 'organ_received';
            organInput.value = organ;
            form.appendChild(organInput);
            
            const dateInput = document.createElement('input');
            dateInput.type = 'hidden';
            dateInput.name = 'surgery_date';
            dateInput.value = surgery_date;
            form.appendChild(dateInput);
            
            const notesInput = document.createElement('input');
            notesInput.type = 'hidden';
            notesInput.name = 'treatment_notes';
            notesInput.value = notes;
            form.appendChild(notesInput);
            
            const statusInput = document.createElement('input');
            statusInput.type = 'hidden';
            statusInput.name = 'status';
            statusInput.value = 'Active'; // Default status
            form.appendChild(statusInput);
            
            document.body.appendChild(form);
            form.submit();
        }
        
        function deleteRecipient(recipientId) {
            if (confirm('Are you sure you want to delete this recipient?')) {
                // Submit form to same page
                const form = document.createElement('form');
                form.method = 'POST';
                form.style.display = 'none';
                
                const actionInput = document.createElement('input');
                actionInput.type = 'hidden';
                actionInput.name = 'action';
                actionInput.value = 'delete_recipient';
                form.appendChild(actionInput);
                
                const recipientIdInput = document.createElement('input');
                recipientIdInput.type = 'hidden';
                recipientIdInput.name = 'recipient_id';
                recipientIdInput.value = recipientId;
                form.appendChild(recipientIdInput);
                
                document.body.appendChild(form);
                form.submit();
            }
        }
        function viewTreatmentLog() { showServerMessage('localhost: Loading treatment log from database', 'success'); }
        function exportRecipients() { 
            document.getElementById('export-modal').classList.add('show'); 
        }
        
        function closeExportModal() {
            document.getElementById('export-modal').classList.remove('show');
            document.getElementById('export-format').value = '';
        }
        
        function downloadExport() {
            const format = document.getElementById('export-format').value;
            
            if (!format) {
                showServerMessage('Please select an export format', 'error');
                return;
            }
            
            // Close modal
            closeExportModal();
            
            // Show loading message
            showServerMessage('Preparing export file...', 'info');
            
            // Create download link
            const url = window.location.pathname + '?action=export_recipients&format=' + format;
            window.location.href = url;
            
            // Show success message after a delay
            setTimeout(() => {
                showServerMessage('Export file downloaded successfully!', 'success');
            }, 1000);
        }
        
        function loadRecipients() {
            // Use PHP data directly
            const recipients = <?php echo json_encode($recipients); ?>;
            // Load recipients data
            updateRecipientsTable(recipients);
        }
        
        function updateRecipientsTable(recipients) {
            const tableContent = document.querySelector('#recipients-table');
            if (!tableContent) return;
            
            // Clear existing rows (except header)
            const existingRows = tableContent.querySelectorAll('.table-row:not(:first-child)');
            existingRows.forEach(row => row.remove());
            
            if (recipients.length === 0) {
                const row = document.createElement('div');
                row.className = 'table-row';
                row.innerHTML = `
                    <div class="table-cell" colspan="6" style="text-align: center; padding: 2rem; color: #666;">
                        No recipient patients found. Click "Add Recipient" to create one.
                    </div>
                `;
                tableContent.appendChild(row);
                return;
            }
            
            // Add new rows
            recipients.forEach(recipient => {
                const row = document.createElement('div');
                row.className = 'table-row';
                
                const statusClass = recipient.status === 'Active' ? 'status-active' : 
                                   (recipient.status === 'Recovered' ? 'status-success' : 'status-pending');
                const surgeryDate = new Date(recipient.surgery_date).toLocaleDateString('en-GB');
                const organReceived = recipient.organ_received.charAt(0).toUpperCase() + recipient.organ_received.slice(1);
                
                row.innerHTML = `
                    <div class="table-cell name" data-label="Patient NIC">${recipient.nic}</div>
                    <div class="table-cell" data-label="Patient Name">${recipient.name}</div>
                    <div class="table-cell" data-label="Organ Received">${organReceived}</div>
                    <div class="table-cell" data-label="Surgery Date">${surgeryDate}</div>
                    <div class="table-cell" data-label="Status">
                        <span class="status-badge ${statusClass}">${recipient.status}</span>
                    </div>
                    <div class="table-cell" data-label="Actions">
                        <button class="btn btn-secondary btn-small" onclick="editRecipient(${recipient.recipient_id})" style="margin-right: 0.5rem;">Edit</button><button class="btn btn-danger btn-small" onclick="deleteRecipient(${recipient.recipient_id})">Delete</button>
                    </div>
                `;
                tableContent.appendChild(row);
            });
        }

        // Story Functions
        function openStoryModal() { document.getElementById('story-modal').classList.add('show'); }
        function closeStoryModal() { 
            document.getElementById('story-modal').classList.remove('show');
            // Reset modal to add mode
            document.querySelector('#story-modal .modal-header h3').textContent = 'Add Success Story';
            document.getElementById('story-title').value = '';
            document.getElementById('story-description').value = '';
            document.getElementById('success-date').value = '';
            
            // Reset button
            const saveButton = document.querySelector('#story-modal button[onclick*="updateStory"]');
            if (saveButton) {
                saveButton.textContent = 'Save Story';
                saveButton.setAttribute('onclick', 'saveStory()');
            }
        }
        function saveStory() { 
            const title = document.getElementById('story-title').value;
            const description = document.getElementById('story-description').value;
            const date = document.getElementById('success-date').value;
            
            if (!title || !description || !date) {
                showServerMessage('localhost: Error - Please fill all required fields', 'error');
                return;
            }
            
            // Submit form to same page
            const form = document.createElement('form');
            form.method = 'POST';
            form.style.display = 'none';
            
            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = 'add_success_story';
            form.appendChild(actionInput);
            
            const titleInput = document.createElement('input');
            titleInput.type = 'hidden';
            titleInput.name = 'title';
            titleInput.value = title;
            form.appendChild(titleInput);
            
            const descriptionInput = document.createElement('input');
            descriptionInput.type = 'hidden';
            descriptionInput.name = 'description';
            descriptionInput.value = description;
            form.appendChild(descriptionInput);
            
            const dateInput = document.createElement('input');
            dateInput.type = 'hidden';
            dateInput.name = 'success_date';
            dateInput.value = date;
            form.appendChild(dateInput);
            
            document.body.appendChild(form);
            form.submit();
        }
        function editStory(storyId) { 
            // Get story data and populate edit form
            const stories = <?php echo json_encode($success_stories); ?>;
            const story = stories.find(s => s.story_id == storyId);
            
            if (story) {
                // Update modal header
                document.querySelector('#story-modal .modal-header h3').textContent = 'Edit Success Story';
                
                // Populate form fields
                document.getElementById('story-title').value = story.title;
                document.getElementById('story-description').value = story.description;
                document.getElementById('success-date').value = story.success_date;
                
                // Change the save button to update button
                const saveButton = document.querySelector('#story-modal button[onclick="saveStory()"]');
                if (saveButton) {
                    saveButton.textContent = 'Update Story';
                    saveButton.setAttribute('onclick', 'updateStory(' + storyId + ')');
                }
                
                // Show the modal
                document.getElementById('story-modal').classList.add('show');
            }
        }
        
        function updateStory(storyId) {
            const title = document.getElementById('story-title').value;
            const description = document.getElementById('story-description').value;
            const success_date = document.getElementById('success-date').value;
            
            if (!title || !description || !success_date) {
                showServerMessage('localhost: Error - Please fill all required fields', 'error');
                return;
            }
            
            // Submit form to same page
            const form = document.createElement('form');
            form.method = 'POST';
            form.style.display = 'none';
            
            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = 'update_success_story';
            form.appendChild(actionInput);
            
            const storyIdInput = document.createElement('input');
            storyIdInput.type = 'hidden';
            storyIdInput.name = 'story_id';
            storyIdInput.value = storyId;
            form.appendChild(storyIdInput);
            
            const titleInput = document.createElement('input');
            titleInput.type = 'hidden';
            titleInput.name = 'title';
            titleInput.value = title;
            form.appendChild(titleInput);
            
            const descriptionInput = document.createElement('input');
            descriptionInput.type = 'hidden';
            descriptionInput.name = 'description';
            descriptionInput.value = description;
            form.appendChild(descriptionInput);
            
            const dateInput = document.createElement('input');
            dateInput.type = 'hidden';
            dateInput.name = 'success_date';
            dateInput.value = success_date;
            form.appendChild(dateInput);
            
            const statusInput = document.createElement('input');
            statusInput.type = 'hidden';
            statusInput.name = 'status';
            statusInput.value = 'Pending'; // Default status
            form.appendChild(statusInput);
            
            document.body.appendChild(form);
            form.submit();
        }
        
        function deleteStory(storyId) {
            if (confirm('Are you sure you want to delete this success story?')) {
                // Submit form to same page
                const form = document.createElement('form');
                form.method = 'POST';
                form.style.display = 'none';
                
                const actionInput = document.createElement('input');
                actionInput.type = 'hidden';
                actionInput.name = 'action';
                actionInput.value = 'delete_success_story';
                form.appendChild(actionInput);
                
                const storyIdInput = document.createElement('input');
                storyIdInput.type = 'hidden';
                storyIdInput.name = 'story_id';
                storyIdInput.value = storyId;
                form.appendChild(storyIdInput);
                
                document.body.appendChild(form);
                form.submit();
            }
        }
        
        function loadStories() {
            // Use PHP data directly
            const stories = <?php echo json_encode($success_stories); ?>;
            // Load stories data
            updateStoriesTable(stories);
        }
        
        function updateStoriesTable(stories) {
            const tableContent = document.querySelector('#stories .table-content');
            if (!tableContent) return;
            
            // Clear existing rows (except header)
            const existingRows = tableContent.querySelectorAll('.table-row:not(:first-child)');
            existingRows.forEach(row => row.remove());
            
            // Add new rows
            stories.forEach(story => {
                const row = document.createElement('div');
                row.className = 'table-row';
                row.innerHTML = `
                    <div class="table-cell name" data-label="Story Title">${story.title}</div>
                    <div class="table-cell" data-label="Description">${story.description.substring(0, 100)}${story.description.length > 100 ? '...' : ''}</div>
                    <div class="table-cell" data-label="Date">${new Date(story.success_date).toLocaleDateString('en-GB')}</div>
                    <div class="table-cell" data-label="Status" style="text-align:center;">
                        <span class="status-badge ${story.status === 'Approved' ? 'status-success' : story.status === 'Pending' ? 'status-pending' : 'status-danger'}">${story.status}</span>
                    </div>
                    <div class="table-cell" data-label="Actions">
                        <div class="table-actions">
                            <button class="btn btn-secondary btn-small" onclick="editStory(${story.story_id})">Edit</button>
                            <button class="btn btn-danger btn-small" onclick="deleteStory(${story.story_id})">Delete</button>
                        </div>
                    </div>
                `;
                tableContent.appendChild(row);
            });
        }

        function showServerMessage(message, type) {
            // Remove any existing notifications to prevent stacking
            const existingNotifications = document.querySelectorAll('.server-notification');
            existingNotifications.forEach(notification => notification.remove());
            
            const n = document.createElement('div');
            n.className = 'server-notification';
            n.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${type === 'success' ? 'linear-gradient(135deg, #10b981, #059669)' : 
                           type === 'error' ? 'linear-gradient(135deg, #ef4444, #dc2626)' : 
                           type === 'info' ? 'linear-gradient(135deg, #3b82f6, #2563eb)' : 
                           'linear-gradient(135deg, #f59e0b, #d97706)'};
                color: white;
                padding: 16px 24px;
                border-radius: 12px;
                box-shadow: 0 10px 25px rgba(0,0,0,0.2), 0 4px 12px rgba(0,0,0,0.1);
                z-index: 10000;
                font-weight: 600;
                font-size: 14px;
                max-width: 350px;
                word-wrap: break-word;
                transform: translateX(120%);
                transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                border: 1px solid rgba(255,255,255,0.2);
                backdrop-filter: blur(10px);
                cursor: pointer;
            `;
            
            // Add close button
            n.innerHTML = `
                <div style="display: flex; align-items: center; gap: 12px; position: relative;">
                    <div style="display: flex; align-items: center; gap: 8px; flex: 1;">
                        <span style="font-size: 18px; filter: drop-shadow(0 1px 2px rgba(0,0,0,0.3));">
                            ${type === 'success' ? '✅' : type === 'error' ? '❌' : type === 'info' ? 'ℹ️' : '⚠️'}
                        </span>
                        <span style="text-shadow: 0 1px 2px rgba(0,0,0,0.3);">${message}</span>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" 
                            style="background: rgba(255,255,255,0.2); border: none; color: white; 
                                   border-radius: 50%; width: 24px; height: 24px; cursor: pointer; 
                                   display: flex; align-items: center; justify-content: center; 
                                   font-size: 12px; font-weight: bold; transition: background 0.2s;">
                        ×
                    </button>
                </div>
            `;
            
            document.body.appendChild(n);
            
            // Animate in
            requestAnimationFrame(() => {
                n.style.transform = 'translateX(0)';
                n.style.opacity = '1';
            });
            
            // Auto-hide after 3 seconds
            setTimeout(() => { 
                n.style.transform = 'translateX(120%)';
                n.style.opacity = '0';
                setTimeout(() => n.remove(), 400);
            }, 3000);
            
            // Add hover effect
            n.addEventListener('mouseenter', () => {
                n.style.transform = 'translateX(0) scale(1.02)';
                n.style.boxShadow = '0 15px 35px rgba(0,0,0,0.3), 0 6px 16px rgba(0,0,0,0.15)';
            });
            
            n.addEventListener('mouseleave', () => {
                n.style.transform = 'translateX(0) scale(1)';
                n.style.boxShadow = '0 10px 25px rgba(0,0,0,0.2), 0 4px 12px rgba(0,0,0,0.1)';
            });
        }

        function notify(message, type) {
            showServerMessage(message, type);
        }

        // User dropdown functions
        function toggleUserDropdown() {
            const dropdown = document.getElementById('user-dropdown');
            dropdown.classList.toggle('show');
        }

        function editProfile() {
            showServerMessage('localhost: Opening profile edit form', 'info');
            // Close dropdown
            document.getElementById('user-dropdown').classList.remove('show');
        }

        function logout() {
            if (confirm('Are you sure you want to logout?')) {
                showServerMessage('localhost: Logging out...', 'info');
                // Close dropdown
                document.getElementById('user-dropdown').classList.remove('show');
                
                // Simulate logout process
                setTimeout(() => {
                    // In a real application, this would redirect to login page
                    showServerMessage('localhost: Logged out successfully. Redirecting to login...', 'success');
                    // For now, just show a message
                    // window.location.href = '/life-connect/app/views/login.view.php';
                }, 1000);
            }
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const userInfo = document.querySelector('.user-info');
            const dropdown = document.getElementById('user-dropdown');
            
            if (!userInfo.contains(event.target)) {
                dropdown.classList.remove('show');
            }
        });

        // Show notifications based on URL parameters
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const success = urlParams.get('success');
            const error = urlParams.get('error');
            
            if (success) {
                let message = '';
                let type = 'success';
                
                switch (success) {
                    case 'organ_request_added':
                        message = 'Organ request added successfully!';
                        break;
                    case 'organ_request_updated':
                        message = 'Organ request updated successfully!';
                        break;
                    case 'organ_request_deleted':
                        message = 'Organ request deleted successfully!';
                        break;
                    case 'recipient_added':
                        message = 'Recipient added successfully!';
                        break;
                    case 'recipient_updated':
                        message = 'Recipient updated successfully!';
                        break;
                    case 'recipient_deleted':
                        message = 'Recipient deleted successfully!';
                        break;
                    case 'story_added':
                        message = 'Success story added successfully! Stats will be updated.';
                        break;
                    case 'story_updated':
                        message = 'Success story updated successfully!';
                        break;
                    case 'story_deleted':
                        message = 'Success story deleted successfully!';
                        break;
                }
                
                if (message) {
                    showServerMessage(message, type);
                }
            }
            
            if (error) {
                let message = '';
                let type = 'error';
                
                switch (error) {
                    case 'organ_request_failed':
                        message = 'Failed to add organ request!';
                        break;
                    case 'organ_request_update_failed':
                        message = 'Failed to update organ request!';
                        break;
                    case 'organ_request_delete_failed':
                        message = 'Failed to delete organ request!';
                        break;
                    case 'recipient_failed':
                        message = 'Failed to add recipient!';
                        break;
                    case 'recipient_update_failed':
                        message = 'Failed to update recipient!';
                        break;
                    case 'recipient_delete_failed':
                        message = 'Failed to delete recipient!';
                        break;
                    case 'story_failed':
                        message = 'Failed to add success story!';
                        break;
                }
                
                if (message) {
                    showServerMessage(message, type);
                }
            }
            
            // Clean URL to remove parameters
            if (success || error) {
                const newUrl = window.location.pathname;
                window.history.replaceState({}, document.title, newUrl);
            }
        });

        // Initialize
        showContent('overview');
        
        // Load initial data
        document.addEventListener('DOMContentLoaded', function() {
            loadOrganRequests();
            loadRecipients();
            loadStories();
        });
        
        // Function to refresh all data
        function refreshAllData() {
            // Reload the page to get fresh data from database
            window.location.reload();
        }
        
    </script>
    
    <!-- Footer -->
    <footer class="hospital-footer" style="background: linear-gradient(135deg, #005baa 0%, #003b6e 100%) !important; color: white !important; text-align: center; padding: 20px; margin-top: auto; box-shadow: 0 -4px 20px rgba(0, 91, 170, 0.2); width: 100%; flex-shrink: 0;">
        <p style="margin: 0; font-size: 14px; color: white !important;">Copyright © 2025 Ministry of Health - LifeConnect Sri Lanka</p>
    </footer>
</body>
</html>
