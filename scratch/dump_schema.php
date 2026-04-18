<?php

// Define constants that might be missing if we don't go through index.php
define('ROOT', 'http://localhost/life-connect');

// Mocking the relative path for init.php
// init.php requires config.php etc in the same dir.
require_once __DIR__ . '/../app/Core/config.php';
require_once __DIR__ . '/../app/Core/Database.php';

class SchemaDumper {
    use App\Core\Database;

    public function connect()
    {
        $string = "mysql:host=" . DBHOST . ";dbname=" . DBNAME;
        $con = new \PDO($string, DBUSER, DBPASS);
        $con->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return $con;
    }

    public function dumpSchema($table) {
        echo "Schema for $table:\n";
        try {
            $res = $this->query("DESCRIBE $table");
            if ($res) {
                foreach ($res as $row) {
                    echo "  - {$row->Field} ({$row->Type})\n";
                }
            } else {
                echo "  (No columns found or table doesn't exist)\n";
            }
        } catch (Exception $e) {
            echo "  Error: " . $e->getMessage() . "\n";
        }
        echo "\n";
    }

    public function listTables() {
        echo "Tables in database:\n";
        try {
            $res = $this->query("SHOW TABLES");
            if ($res) {
                foreach ($res as $row) {
                    $vars = get_object_vars($row);
                    echo "  - " . reset($vars) . "\n";
                }
            }
        } catch (Exception $e) {
            echo "  Error: " . $e->getMessage() . "\n";
        }
        echo "\n";
    }
}

$dumper = new SchemaDumper();
$dumper->listTables();
$dumper->dumpSchema('aftercare_patients');
$dumper->dumpSchema('recipient_patients');
$dumper->dumpSchema('recipient_patient');
