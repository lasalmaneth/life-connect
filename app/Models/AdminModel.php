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
        
        $res = $this->query("SELECT COUNT(*) as count FROM users WHERE (status = 'SUSPENDED' OR status = 'suspended') AND created_at >= NOW() - INTERVAL 1 MONTH");
        $stats['suspendedThisMonth'] = $res[0]->count ?? 0;
        
        $res = $this->query("SELECT COUNT(*) as count FROM donors WHERE created_at >= NOW() - INTERVAL 1 MONTH");
        $stats['donorsThisMonth'] = $res[0]->count ?? 0;

        $res = $this->query("SELECT COUNT(*) as count FROM donors WHERE category_id = 3 AND created_at >= NOW() - INTERVAL 1 MONTH");
        $stats['liveDonorsThisMonth'] = $res[0]->count ?? 0;

        $res = $this->query("SELECT COUNT(*) as count FROM users WHERE (status = 'ACTIVE' OR status = 'active') AND created_at >= NOW() - INTERVAL 1 MONTH");
        $stats['activeThisMonth'] = $res[0]->count ?? 0;

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

        $stats['activities'] = $this->getActivityLogs(3);

        return $stats;
    }

    public function getActivityLogs($limit = 3) {
        $activities = [];
        
        // 1. Fetch Latest Registrations (User activity)
        $registrations = $this->query("SELECT 'USER' as category, username as title, role as detail, status, created_at as date 
                                     FROM users 
                                     ORDER BY created_at DESC LIMIT 5");
        
        if ($registrations) {
            foreach ($registrations as $reg) {
                $status = strtoupper($reg->status);
                $icon = ($status === 'ACTIVE') ? 'circle-check' : 'circle-plus';
                
                $activities[] = [
                    'type' => $icon,
                    'category' => 'success',
                    'title' => 'New User Registered',
                    'detail' => $reg->title . ' joined as ' . strtolower($reg->detail),
                    'status' => $status,
                    'date' => $reg->date
                ];
            }
        }

        // 2. Fetch Latest Notifications (Admin activity)
        $logs = $this->query("SELECT 'ADMIN' as category, n.type, u.username as recipient, n.title, n.created_at as date 
                             FROM notifications n 
                             JOIN users u ON n.user_id = u.id 
                             ORDER BY n.created_at DESC LIMIT 5");
        
        if ($logs) {
            foreach ($logs as $log) {
                $category = 'info';
                $icon = 'bell';
                $title = $log->title;
                $detail = 'Sent to ' . $log->recipient;

                if (str_contains($title, '[ADMIN_ACTION]')) {
                    $title = str_replace('[ADMIN_ACTION] ', '', $title);
                    
                    // Default Info
                    $icon = 'circle-info';
                    $category = 'info';
                    $detail = 'Administrative Review: Profile record updated';

                    if (stripos($title, 'ACTIVE') !== false || stripos($title, 'Approved') !== false || stripos($title, 'Activated') !== false) {
                        $category = 'success';
                        $icon = 'circle-check';
                        $detail = 'Administrative Audit: Account status promoted to ACTIVE';
                    } elseif (stripos($title, 'Rejected') !== false || stripos($title, 'Denied') !== false) {
                        $category = 'error';
                        $icon = 'circle-xmark';
                        $detail = 'Administrative Audit: Account registration REJECTED';
                    } elseif (stripos($title, 'Suspended') !== false) {
                        $category = 'error';
                        $icon = 'circle-xmark';
                        $detail = 'Administrative Audit: Account access SUSPENDED';
                    } elseif (stripos($title, 'PENDING') !== false) {
                        $category = 'info';
                        $icon = 'circle-info';
                        $detail = 'Administrative Review: Account returned to PENDING';
                    }
                } elseif (stripos($title, 'Approved') !== false || stripos($title, 'ACTIVE') !== false) {
                    $category = 'success';
                    $icon = 'circle-check';
                } elseif (stripos($title, 'Rejected') !== false || stripos($title, 'Denied') !== false) {
                    $category = 'error';
                    $icon = 'circle-xmark';
                } elseif (stripos($title, 'Suspended') !== false) {
                    $category = 'error';
                    $icon = 'circle-xmark';
                } else {
                    $category = 'info';
                    $icon = 'circle-info';
                }

                $activities[] = [
                    'type' => $icon,
                    'category' => $category,
                    'title' => $title,
                    'detail' => $detail,
                    'date' => $log->date
                ];
            }
        }

        // Sort by date DESC
        usort($activities, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });

        // Slice to limit
        return array_slice($activities, 0, $limit);
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

    public function updateUserStatus($userId, $status, $message = null) {
        $query = "UPDATE users SET status = :status, review_message = :message WHERE id = :id";
        return $this->query($query, ['status' => $status, 'id' => $userId, 'message' => $message]);
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
            $donor = $this->query("SELECT * FROM donors WHERE user_id = :id", ['id' => $id]);
            if (!empty($donor)) $details = (array) $donor[0];
        } elseif ($role === 'FINANCIAL_DONOR') {
            $donor = $this->query("SELECT * FROM donors WHERE user_id = :id", ['id' => $id]);
            if (!empty($donor)) $details = (array) $donor[0];
        } elseif ($role === 'HOSPITAL') {
            $hosp = $this->query("SELECT *, name as first_name, '' as last_name, registration_number as nic FROM hospitals WHERE user_id = :id", ['id' => $id]);
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
        } elseif (in_array($role, ['ADMIN', 'U_ADMIN', 'F_ADMIN', 'AC_ADMIN', 'D_ADMIN'])) {
            $admin = $this->query("SELECT * FROM admins WHERE user_id = :id", ['id' => $id]);
            if (!empty($admin)) $details = (array) $admin[0];
        }

        $user->first_name = $details['first_name'] ?? '';
        $user->last_name = $details['last_name'] ?? '';
        $user->nic = $details['nic'] ?? $details['nic_number'] ?? '';
        $user->gender = $details['gender'] ?? '';
        $user->dob = $details['dob'] ?? $details['date_of_birth'] ?? '';
        $user->active_roles = $details['active_roles'] ?? $details['active_role'] ?? '[]';
        $user->address = $details['address'] ?? '';
        $user->district = $details['district'] ?? '';
        $user->ds_division = $details['ds_division'] ?? $details['divisional_secretariat'] ?? '';
        $user->gn_division = $details['gn_division'] ?? $details['grama_niladhari_division'] ?? '';
        
        // Hospital Specific Fields
        $user->transplant_id = $details['transplant_id'] ?? '';
        $user->facility_type = $details['facility_type'] ?? '';
        $user->cmo_name = $details['cmo_name'] ?? '';
        $user->cmo_nic = $details['cmo_nic'] ?? '';
        $user->medical_license_number = $details['medical_license_number'] ?? '';
        $user->hospital_contact_number = $details['contact_number'] ?? '';

        // Admin Specific Fields
        $user->staff_id = $details['staff_id'] ?? '';
        $user->designation = $details['designation'] ?? '';
        $user->admin_contact = $details['contact_number'] ?? '';

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
        } elseif (in_array($role, ['ADMIN', 'U_ADMIN', 'F_ADMIN', 'AC_ADMIN', 'D_ADMIN'])) {
            $this->query("UPDATE admins SET first_name = :fn, last_name = :ln, staff_id = :sid, designation = :des, contact_number = :con WHERE user_id = :id", [
                'fn' => $data['first_name'] ?? '',
                'ln' => $data['last_name'] ?? '',
                'sid' => $data['staff_id'] ?? '',
                'des' => $data['designation'] ?? '',
                'con' => $data['contact_number'] ?? '',
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

    public function bulkUpdateUserStatus($userIds, $status, $message = null) {
        if (empty($userIds)) return false;
        
        $status = strtoupper($status);
        if (!$message) {
            $message = ($status === 'ACTIVE') 
                ? "Account reactivated: Following administrative review, your access has been restored and all issues have been resolved." 
                : "Account suspended for administrative review.";
        }

        $placeholders = implode(',', array_fill(0, count($userIds), '?'));
        $query = "UPDATE users SET status = ?, review_message = ? WHERE id IN ($placeholders)";
        
        $params = array_merge([$status, $message], $userIds);
        return $this->query($query, $params);
    }
}
