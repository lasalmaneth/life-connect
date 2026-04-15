<?php
require_once __DIR__ . '/../app/core/config.php';
require_once __DIR__ . '/../app/core/Database.php';

class Debug {
    use App\Core\Database;
    public function check() {
        try {
            $res = $this->query("SHOW TABLES LIKE 'support_vouchers'");
            if ($res) {
                echo "TABLE_EXISTS\n";
                $cols = $this->query("DESCRIBE support_vouchers");
                print_r($cols);
            } else {
                echo "TABLE_MISSING\n";
            }
        } catch (Exception $e) {
            echo "ERROR: " . $e->getMessage() . "\n";
        }
    }
}
$d = new Debug();
$d->check();
?>
