<?php

namespace App\Models;

use App\Core\Database;

class AdminModel {
    use Database;

    public function getDashboardStats() {
        $stats = [];
        
        // Total Users
        $res = $this->query("SELECT COUNT(*) as count FROM users");
        $stats['totalUsers'] = $res[0]->count ?? 0;

        // Count by Status
        $res = $this->query("SELECT status, COUNT(*) as count FROM users GROUP BY status");
        foreach($res as $row) {
            $stats['status_' . $row->status] = $row->count;
        }

        // Count by Role
        $res = $this->query("SELECT role, COUNT(*) as count FROM users GROUP BY role");
        foreach($res as $row) {
            $stats['role_' . $row->role] = $row->count;
        }

        // Pending Verifications (Donors + Hospitals + Med Schools)
        $res = $this->query("SELECT COUNT(*) as count FROM donors WHERE verification_status = 'PENDING'");
        $stats['pendingDonors'] = $res[0]->count ?? 0;

        $res = $this->query("SELECT COUNT(*) as count FROM hospitals WHERE verification_status = 'PENDING'");
        $stats['pendingHospitals'] = $res[0]->count ?? 0;

        $res = $this->query("SELECT COUNT(*) as count FROM medical_schools WHERE verification_status = 'PENDING'");
        $stats['pendingMedSchools'] = $res[0]->count ?? 0;

        // Total Donations
        $res = $this->query("SELECT SUM(amount) as total FROM donations WHERE status = 'SUCCESS'");
        $stats['totalDonations'] = $res[0]->total ?? 0;

        // Count by Donor Category
        $res = $this->query("SELECT c.category_name, COUNT(*) as count 
                             FROM donors d 
                             JOIN donor_categories c ON d.category_id = c.id 
                             GROUP BY c.category_name");
        if ($res) {
            foreach($res as $row) {
                $stats['donor_cat_' . $row->category_name] = $row->count;
            }
        }

        // Count by Pledge Type for Just Donors
        $res = $this->query("SELECT pledge_type, COUNT(*) as count 
                             FROM donors 
                             WHERE category_id = 3 
                             GROUP BY pledge_type");
        if ($res) {
            foreach($res as $row) {
                if ($row->pledge_type !== 'NONE') {
                    $stats['pledge_' . $row->pledge_type] = $row->count;
                }
            }
        }

        // Month-to-date counts for stat change UI
        $res = $this->query("SELECT COUNT(*) as count FROM users WHERE created_at >= NOW() - INTERVAL 1 MONTH");
        $stats['usersThisMonth'] = $res[0]->count ?? 0;
        
        $res = $this->query("SELECT COUNT(*) as count FROM users WHERE (status = 'PENDING' OR status = 'pending') AND created_at >= NOW() - INTERVAL 1 MONTH");
        $stats['pendingThisMonth'] = $res[0]->count ?? 0;
        
        $res = $this->query("SELECT COUNT(*) as count FROM donors WHERE created_at >= NOW() - INTERVAL 1 MONTH");
        $stats['donorsThisMonth'] = $res[0]->count ?? 0;

        $res = $this->query("SELECT COUNT(*) as count FROM donors WHERE category_id = 3 AND created_at >= NOW() - INTERVAL 1 MONTH");
        $stats['liveDonorsThisMonth'] = $res[0]->count ?? 0;

        try {
            $res = $this->query("SELECT COUNT(*) as count FROM patients WHERE created_at >= NOW() - INTERVAL 1 MONTH");
            $stats['patientsThisMonth'] = $res[0]->count ?? 0;
        } catch (\Exception $e) {
            $stats['patientsThisMonth'] = 0;
        }

        try {
            $res = $this->query("SELECT COUNT(*) as count FROM hospitals WHERE created_at >= NOW() - INTERVAL 1 MONTH");
            $stats['hospitalsThisMonth'] = $res[0]->count ?? 0;
        } catch (\Exception $e) {
            $stats['hospitalsThisMonth'] = 0;
        }

        // Weekly Registration Activity (Last 7 days including today)
        $weeklyData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $dayName = date('D', strtotime($date));
            $weeklyData[$date] = ['day' => $dayName, 'count' => 0];
        }

        $resWeb = $this->query("SELECT DATE(created_at) as date, COUNT(*) as count 
                             FROM users 
                             WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 DAY) 
                             GROUP BY DATE(created_at)");
        
        $currentWeeklyTotal = 0;
        $peakDayValue = 0;
        if ($resWeb) {
            foreach ($resWeb as $row) {
                if (isset($weeklyData[$row->date])) {
                    $weeklyData[$row->date]['count'] = (int)$row->count;
                    $currentWeeklyTotal += (int)$row->count;
                    if ((int)$row->count > $peakDayValue) $peakDayValue = (int)$row->count;
                }
            }
        }
        $stats['weekly_chart_data'] = array_values($weeklyData);
        $stats['weekly_total'] = $currentWeeklyTotal;
        $stats['weekly_average'] = round($currentWeeklyTotal / 7, 1);
        $stats['peak_day_value'] = $peakDayValue;

        // Growth Percentage (vs previous 7 days)
        $resPrev = $this->query("SELECT COUNT(*) as count 
                             FROM users 
                             WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 13 DAY) 
                             AND created_at < DATE_SUB(CURDATE(), INTERVAL 6 DAY)");
        $prevWeeklyTotal = $resPrev[0]->count ?? 0;
        
        if ($prevWeeklyTotal > 0) {
            $growth = (($currentWeeklyTotal - $prevWeeklyTotal) / $prevWeeklyTotal) * 100;
            $stats['weekly_growth'] = round($growth, 1);
        } else {
            $stats['weekly_growth'] = $currentWeeklyTotal > 0 ? 100 : 0;
        }

        return $stats;
    }

    public function getUsers($searchTerm = '', $role = '', $status = '') {
        $params = [];
        $query = "SELECT u.id, u.username, u.email, u.role, u.status, u.created_at 
                  FROM users u 
                  WHERE 1=1";

        if ($searchTerm) {
            $query .= " AND (u.username LIKE :search OR u.email LIKE :search)";
            $params['search'] = "%$searchTerm%";
        }

        if ($role) {
            $query .= " AND u.role = :role";
            $params['role'] = $role;
        }

        if ($status) {
            $query .= " AND u.status = :status";
            $params['status'] = $status;
        }

        $query .= " ORDER BY u.created_at DESC";

        return $this->query($query, $params);
    }

    public function updateUserStatus($userId, $status) {
        $query = "UPDATE users SET status = :status WHERE id = :id";
        return $this->query($query, ['status' => $status, 'id' => $userId]);
    }

    public function getPendingDocuments() {
        $docs = [];
        
        // Donors
        $res = $this->query("SELECT 'DONOR' as entity_type, d.id, d.user_id, d.first_name, d.last_name, d.nic_number as doc_id, 'NIC' as type, d.verification_status as status, d.created_at as date 
                             FROM donors d WHERE d.verification_status = 'PENDING'");
        
        if ($res) {
            foreach ($res as $row) {
                if ($row->entity_type === 'DONOR' && !empty($row->doc_id)) {
                    $decrypted = decrypt($row->doc_id);
                    if ($decrypted !== false) {
                        $row->doc_id = $decrypted;
                    }
                }
            }
            $docs = array_merge($docs, $res);
        }

        // Hospitals
        $res = $this->query("SELECT 'HOSPITAL' as entity_type, h.id, h.user_id, h.name as first_name, '' as last_name, h.registration_number as doc_id, 'LICENSE' as type, h.verification_status as status, NOW() as date 
                             FROM hospitals h WHERE h.verification_status = 'PENDING'");
        if ($res) $docs = array_merge($docs, $res);

        // Medical Schools
        $res = $this->query("SELECT 'MEDICAL_SCHOOL' as entity_type, m.id, m.user_id, m.school_name as first_name, '' as last_name, m.ugc_accreditation_number as doc_id, 'UGC' as type, m.verification_status as status, NOW() as date 
                             FROM medical_schools m WHERE m.verification_status = 'PENDING'");
        if ($res) $docs = array_merge($docs, $res);

        return $docs;
    }

    public function updateEntityVerification($entityType, $id, $status) {
        $table = match($entityType) {
            'DONOR' => 'donors',
            'HOSPITAL' => 'hospitals',
            'MEDICAL_SCHOOL' => 'medical_schools',
            default => null
        };

        if (!$table) return false;

        $query = "UPDATE $table SET verification_status = :status WHERE id = :id";
        return $this->query($query, ['status' => $status, 'id' => $id]);
    }

    public function sendNotification($userId, $title, $message, $type = 'GENERAL') {
        $query = "INSERT INTO notifications (user_id, title, message, type) VALUES (:user_id, :title, :message, :type)";
        return $this->query($query, ['user_id' => $userId, 'title' => $title, 'message' => $message, 'type' => $type]);
    }

    public function getNotifications($limit = 50) {
        return $this->query("SELECT n.*, u.username as recipient FROM notifications n JOIN users u ON n.user_id = u.id ORDER BY n.created_at DESC LIMIT $limit");
    }

    public function getUserById($id) {
        $res = $this->query("SELECT id, username, email, phone, role, status, created_at FROM users WHERE id = :id", ['id' => $id]);
        return $res[0] ?? null;
    }

    public function getDetailedUserById($id, $role) {
        $user = $this->getUserById($id);
        if (!$user) return null;

        $details = [];
        $role = strtoupper($role);

        if ($role === 'DONOR') {
            $donor = $this->query("SELECT first_name, last_name, nic_number as nic, gender, date_of_birth as dob FROM donors WHERE user_id = :id", ['id' => $id]);
            if (!empty($donor)) $details = (array) $donor[0];
        } elseif ($role === 'FINANCIAL_DONOR') {
            $donor = $this->query("SELECT first_name, last_name, nic_number as nic, gender, date_of_birth as dob FROM donors WHERE user_id = :id", ['id' => $id]);
            if (!empty($donor)) $details = (array) $donor[0];
        } elseif ($role === 'HOSPITAL') {
            $hosp = $this->query("SELECT name as first_name, '' as last_name, registration_number as nic FROM hospitals WHERE user_id = :id", ['id' => $id]);
            if (!empty($hosp)) $details = (array) $hosp[0];
        } elseif ($role === 'MEDICAL_SCHOOL') {
            $med = $this->query("SELECT name as first_name, '' as last_name, ugc_number as nic FROM medical_schools WHERE user_id = :id", ['id' => $id]);
            if (!empty($med)) $details = (array) $med[0];
        } elseif ($role === 'PATIENT') {
            try {
                $pat = $this->query("SELECT first_name, last_name, nic, gender FROM patients WHERE user_id = :id", ['id' => $id]);
                if (!empty($pat)) $details = (array) $pat[0];
            } catch (Exception $e) {
                // Ignore if patient table schema is incomplete
            }
        }

        $user->first_name = $details['first_name'] ?? '';
        $user->last_name = $details['last_name'] ?? '';
        $user->nic = $details['nic'] ?? '';
        $user->gender = $details['gender'] ?? '';
        $user->dob = $details['dob'] ?? '';

        return $user;
    }

    public function updateUserDetails($id, $role, $data) {
        // Update users table phone
        if (isset($data['phone'])) {
            $this->query("UPDATE users SET phone = :phone WHERE id = :id", ['phone' => $data['phone'], 'id' => $id]);
        }

        $role = strtoupper($role);
        // Update role tables first name and last name
        if ($role === 'DONOR') {
            $this->query("UPDATE donors SET first_name = :fn, last_name = :ln WHERE user_id = :id", [
                'fn' => $data['first_name'] ?? '',
                'ln' => $data['last_name'] ?? '',
                'id' => $id
            ]);
        } elseif ($role === 'FINANCIAL_DONOR') {
            $this->query("UPDATE donors SET first_name = :fn, last_name = :ln WHERE user_id = :id", [
                'fn' => $data['first_name'] ?? '',
                'ln' => $data['last_name'] ?? '',
                'id' => $id
            ]);
        } elseif ($role === 'HOSPITAL') {
            $this->query("UPDATE hospitals SET name = :nm WHERE user_id = :id", [
                'nm' => $data['first_name'] ?? '',
                'id' => $id
            ]);
        } elseif ($role === 'MEDICAL_SCHOOL') {
            $this->query("UPDATE medical_schools SET name = :nm WHERE user_id = :id", [
                'nm' => $data['first_name'] ?? '',
                'id' => $id
            ]);
        } elseif ($role === 'PATIENT') {
            $this->query("UPDATE patients SET first_name = :fn, last_name = :ln WHERE user_id = :id", [
                'fn' => $data['first_name'] ?? '',
                'ln' => $data['last_name'] ?? '',
                'id' => $id
            ]);
        }

        return true;
    }

    public function updateUser($id, $data) {
        $fields = [];
        $params = ['id' => $id];
        
        foreach ($data as $key => $value) {
            $fields[] = "$key = :$key";
            $params[$key] = $value;
        }
        
        if (empty($fields)) return false;
        
        $query = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = :id";
        return $this->query($query, $params);
    }

    public function bulkUpdateUserStatus($userIds, $status) {
        if (empty($userIds)) return false;
        
        // PDO doesn't handle array binding in IN clause easily, so we build it manually
        $placeholders = implode(',', array_fill(0, count($userIds), '?'));
        $query = "UPDATE users SET status = ? WHERE id IN ($placeholders)";
        
        $params = array_merge([$status], $userIds);
        return $this->query($query, $params);
    }
    public function bulkUpdateEntityVerification($entities, $status) {
        if (empty($entities)) return false;
        
        // Group IDs by entity type to minimize queries
        $grouped = [];
        foreach ($entities as $entity) {
            $type = $entity['entity_type'];
            $id = $entity['id'];
            if (!isset($grouped[$type])) $grouped[$type] = [];
            $grouped[$type][] = $id;
        }

        foreach ($grouped as $type => $ids) {
            $table = match($type) {
                'DONOR' => 'donors',
                'HOSPITAL' => 'hospitals',
                'MEDICAL_SCHOOL' => 'medical_schools',
                default => null
            };

            if ($table) {
                $placeholders = implode(',', array_fill(0, count($ids), '?'));
                $query = "UPDATE $table SET verification_status = ? WHERE id IN ($placeholders)";
                $params = array_merge([$status], $ids);
                $this->query($query, $params);
            }
        }
        return true;
    }
}
