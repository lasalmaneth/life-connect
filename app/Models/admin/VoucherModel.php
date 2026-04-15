<?php

namespace App\Models\admin;

use App\Core\Model;

class VoucherModel {
    use Model;

    protected $table = 'support_vouchers';

    /**
     * Create a new digital voucher for a patient
     */
    public function createVoucher($requestId, $patientNic, $amount) {
        $voucherCode = $this->generateUniqueCode();
        $issuedDate = date('Y-m-d');
        $expiryDate = date('Y-m-d', strtotime('+30 days'));

        $query = "INSERT INTO $this->table (request_id, patient_nic, voucher_code, amount, issued_date, expiry_date, status) 
                  VALUES (:request_id, :patient_nic, :voucher_code, :amount, :issued_date, :expiry_date, 'ACTIVE')";
        
        $this->query($query, [
            ':request_id' => $requestId,
            ':patient_nic' => $patientNic,
            ':voucher_code' => $voucherCode,
            ':amount' => $amount,
            ':issued_date' => $issuedDate,
            ':expiry_date' => $expiryDate
        ]);

        return $voucherCode;
    }

    /**
     * Get all issued vouchers with request details
     */
    public function getAllVouchers() {
        $query = "SELECT v.*, r.patient_name, r.reason 
                  FROM $this->table v 
                  JOIN support_requests r ON v.request_id = r.id 
                  ORDER BY v.created_at DESC";
        $results = $this->query($query);
        return $results ? $results : [];
    }

    /**
     * Generate a unique voucher code formatted as LC-SUPPORT-[YEAR]-[RANDOM]
     */
    private function generateUniqueCode() {
        $prefix = "LC-SUP-" . date('Y');
        $unique = false;
        $code = "";

        while (!$unique) {
            $random = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));
            $code = "$prefix-$random";
            
            // Check uniqueness
            $check = $this->query("SELECT id FROM $this->table WHERE voucher_code = :code", [':code' => $code]);
            if (!$check) {
                $unique = true;
            }
        }

        return $code;
    }

    /**
     * Get voucher summary stats
     */
    public function getStats() {
        $stats = [
            'active' => 0,
            'used' => 0,
            'expired' => 0,
            'total_disbursed' => 0
        ];

        $res = $this->query("SELECT status, COUNT(*) as count, SUM(amount) as total_amt FROM $this->table GROUP BY status");
        if ($res) {
            foreach ($res as $row) {
                $status = strtolower($row->status);
                if (isset($stats[$status])) {
                    $stats[$status] = $row->count;
                }
                $stats['total_disbursed'] += $row->total_amt;
            }
        }

        return $stats;
    }
}
