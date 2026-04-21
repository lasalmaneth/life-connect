<?php
try {
    $db = new PDO('mysql:host=localhost;dbname=life-connect', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->exec("ALTER TABLE admins ADD COLUMN address TEXT NULL AFTER contact_number");
    echo "Column added successfully.";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
        echo "Column already exists.";
    } else {
        echo "Error: " . $e->getMessage();
    }
}
