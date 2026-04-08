<?php

namespace App\Models;

use App\Core\Database;

class FinancialDonorModel {
    use Database;

    protected $table = 'financial_donors';

    public function createFinancialDonor($userId, $data)
    {
        $query = "INSERT INTO financial_donors (user_id, full_name, nic_number, donation_frequency) 
                  VALUES (:user_id, :full_name, :nic, :frequency)";
        
        $fullName = $data['full_name'] ?? (($data['first_name'] ?? '') . ' ' . ($data['last_name'] ?? ''));

        return $this->insert($query, [
            ':user_id' => $userId,
            ':full_name' => trim($fullName),
            ':nic' => $data['nic'] ?? null,
            ':frequency' => $data['donation_frequency'] ?? 'ONETIME'
        ]);
    }

    public function getFinancialDonorByUserId($userId)
    {
        $query = "SELECT fd.*, fd.id as donor_id, u.email, u.phone as contact_number, u.created_at as registration_date 
                  FROM financial_donors fd
                  JOIN users u ON fd.user_id = u.id
                  WHERE fd.user_id = :user_id";
        $result = $this->query($query, [':user_id' => $userId]);
        return $result ? (array)$result[0] : null;
    }

    public function updateDonorProfile($donorId, $updateData)
    {
        $query = "UPDATE financial_donors SET 
                  full_name = :full_name
                  WHERE id = :id";
        
        $params = [
            ':full_name' => $updateData['full_name'] ?? '',
            ':id' => $donorId
        ];

        return $this->query($query, $params);
    }

    public function getAllDistricts()
    {
        return [
            'Ampara', 'Anuradhapura', 'Badulla', 'Batticaloa', 'Colombo', 'Galle', 'Gampaha', 
            'Hambantota', 'Jaffna', 'Kalutara', 'Kandy', 'Kegalle', 'Kilinochchi', 'Kurunegala', 
            'Mannar', 'Matale', 'Matara', 'Monaragala', 'Mullaitivu', 'Nuwara Eliya', 'Polonnaruwa', 
            'Puttalam', 'Ratnapura', 'Trincomalee', 'Vavuniya'
        ];
    }
}
