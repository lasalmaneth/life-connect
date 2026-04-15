<?php

namespace App\Models\admin;

use App\Core\Model;

class FinancialDonationModel
{
    use Model;

    protected $table = 'donations';

    public function getTotalDonations()
    {
        $result = $this->query("SELECT SUM(amount) AS total FROM $this->table");
        return $result ? $result[0]->total : 0;
    }

    public function getHighestContributor()
    {
        $result = $this->query(
            "SELECT CONCAT(dn.first_name, ' ', dn.last_name) AS full_name, SUM(d.amount) AS total_amount
            FROM donors dn
            JOIN users u ON dn.user_id = u.id
            JOIN donations d ON d.user_id = u.id
            GROUP BY dn.id
            ORDER BY total_amount DESC
            LIMIT 1"
        );
        return $result ? $result[0] : null;
    }

    public function getFinancialKPIs()
    {
        $kpis = [];

        $res = $this->query("SELECT COUNT(DISTINCT user_id) AS total FROM donations WHERE status IN ('SUCCESS', 'COMPLETED')");
        $kpis['total_contributors'] = $res ? $res[0]->total : 0;

        $res = $this->query("SELECT COUNT(DISTINCT user_id) AS total FROM donations WHERE status IN ('SUCCESS', 'COMPLETED') AND MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())");
        $kpis['this_month_contributors'] = $res ? $res[0]->total : 0;

        $res = $this->query("SELECT SUM(amount) AS total FROM donations WHERE status IN ('SUCCESS', 'COMPLETED')");
        $kpis['total_amount'] = $res ? $res[0]->total : 0;

        $res = $this->query("SELECT SUM(amount) AS total FROM donations WHERE status IN ('SUCCESS', 'COMPLETED') AND MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())");
        $kpis['this_month'] = $res ? $res[0]->total : 0;

        // Failed transactions stats
        $res = $this->query("SELECT COUNT(*) AS total FROM donations WHERE status = 'FAILED'");
        $kpis['failed_transactions'] = $res ? $res[0]->total : 0;

        $res = $this->query("SELECT COUNT(*) AS total FROM donations WHERE status = 'FAILED' AND MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())");
        $kpis['failed_this_month'] = $res ? $res[0]->total : 0;

        // Retention Rate calculation (Calendar approach)
        $res = $this->query("SELECT COUNT(DISTINCT user_id) AS total FROM donations WHERE status IN ('SUCCESS', 'COMPLETED') AND YEAR(created_at) = YEAR(CURRENT_DATE()) - 1");
        $baseDonors = $res ? $res[0]->total : 0;

        if ($baseDonors > 0) {
            $res = $this->query(trim("
                SELECT COUNT(DISTINCT d1.user_id) AS total
                FROM donations d1
                JOIN donations d2 ON d1.user_id = d2.user_id
                WHERE YEAR(d1.created_at) = YEAR(CURRENT_DATE()) - 1
                AND YEAR(d2.created_at) = YEAR(CURRENT_DATE())
                AND d1.status IN ('SUCCESS', 'COMPLETED')
                AND d2.status IN ('SUCCESS', 'COMPLETED')
            "));
            $retainedDonors = $res ? $res[0]->total : 0;
            $kpis['retention_rate'] = round(($retainedDonors / $baseDonors) * 100);
        } else {
            $kpis['retention_rate'] = 0;
        }

        $res = $this->query("SELECT SUM(amount) AS total FROM donations WHERE status IN ('SUCCESS', 'COMPLETED') AND MONTH(created_at) = MONTH(CURRENT_DATE() - INTERVAL 1 MONTH) AND YEAR(created_at) = YEAR(CURRENT_DATE() - INTERVAL 1 MONTH)");
        $kpis['prev_month'] = $res ? $res[0]->total : 0;

        $res = $this->query("SELECT SUM(amount) AS total FROM donations WHERE status IN ('SUCCESS', 'COMPLETED') AND QUARTER(created_at) = QUARTER(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())");
        $kpis['this_quarter'] = $res ? $res[0]->total : 0;

        $res = $this->query("SELECT SUM(amount) AS total FROM donations WHERE status IN ('SUCCESS', 'COMPLETED') AND YEAR(created_at) = YEAR(CURRENT_DATE() - INTERVAL 1 QUARTER) AND QUARTER(created_at) = QUARTER(CURRENT_DATE() - INTERVAL 1 QUARTER)");
        $kpis['prev_quarter'] = $res ? $res[0]->total : 0;

        $res = $this->query("SELECT SUM(amount) AS total FROM donations WHERE status IN ('SUCCESS', 'COMPLETED') AND YEAR(created_at) = YEAR(CURRENT_DATE())");
        $kpis['this_year'] = $res ? $res[0]->total : 0;

        // Fetch monthly trend for the last 6 months to power the line chart
        $trendRes = $this->query(trim("
            SELECT
                DATE_FORMAT(created_at, '%b %Y') AS month_label,
                YEAR(created_at) AS y,
                MONTH(created_at) AS m,
                SUM(amount) AS total
            FROM donations
            WHERE status IN ('SUCCESS', 'COMPLETED')
            AND created_at >= DATE_FORMAT(CURRENT_DATE() - INTERVAL 5 MONTH, '%Y-%m-01')
            GROUP BY y, m, month_label
            ORDER BY y ASC, m ASC
        "));
        $kpis['monthly_trend'] = is_array($trendRes) ? $trendRes : [];

        // Fetch recent 3 transactions
        $recentRes = $this->query(trim("
            SELECT d.id,
                   COALESCE(CONCAT(dn.first_name, ' ', dn.last_name), u.username) AS donor_name,
                   d.amount, d.created_at AS date, d.status
            FROM donations d
            JOIN users u ON u.id = d.user_id
            LEFT JOIN donors dn ON dn.user_id = u.id
            ORDER BY d.created_at DESC
            LIMIT 3
        "));
        $kpis['recent_transactions'] = is_array($recentRes) ? $recentRes : [];

        return $kpis;
    }

    public function getDonationsPastMonth()
    {
        $result = $this->query(
            "SELECT COUNT(*) AS total FROM $this->table WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH)"
        );
        return $result ? $result[0]->total : 0;
    }

    public function getDonationsPast3Months()
    {
        $result = $this->query(
            "SELECT COUNT(*) AS total FROM $this->table WHERE created_at >= DATE_SUB(NOW(), INTERVAL 3 MONTH)"
        );
        return $result ? $result[0]->total : 0;
    }

    public function getDonationsThisYear()
    {
        $result = $this->query(
            "SELECT COUNT(*) AS total FROM $this->table WHERE YEAR(created_at) = YEAR(CURDATE())"
        );
        return $result ? $result[0]->total : 0;
    }

    public function getAllDonations()
    {
        $query = "SELECT
                    d.id,
                    COALESCE(CONCAT(dn.first_name, ' ', dn.last_name), u.username) AS full_name,
                    u.email,
                    d.amount,
                    d.created_at AS date,
                    d.note,
                    d.transaction_id,
                    COALESCE(d.status, 'PENDING') AS status
                  FROM donations d
                  JOIN users u ON u.id = d.user_id
                  LEFT JOIN donors dn ON dn.user_id = u.id
                  ORDER BY d.created_at DESC";
        $result = $this->query($query);
        return $result ? $result : [];
    }

    public function getDonationsByDonorId($donorId)
    {
        $query = "SELECT * FROM donations WHERE user_id = :donor_id ORDER BY created_at DESC";
        return $this->query($query, [':donor_id' => $donorId]);
    }

    public function getDonationById($id)
    {
        $query = "SELECT * FROM donations WHERE id = :id LIMIT 1";
        $res = $this->query($query, [':id' => $id]);
        return $res ? $res[0] : null;
    }

    public function createDonation($data)
    {
        $query = "INSERT INTO donations (user_id, amount, note, status, created_at)
                  VALUES (:donor_id, :amount, :note, :status, NOW())";

        $this->query($query, [
            ':donor_id' => $data['donor_id'],
            ':amount' => $data['amount'],
            ':note' => $data['note'] ?? null,
            ':status' => $data['status'] ?? 'SUCCESS'
        ]);

        return true;
    }

    /**
     * Get Total Cumulative Amount Donated by a user
     */
    public function getTotalDonatedAmount($userId)
    {
        $query = "SELECT SUM(amount) as total FROM donations WHERE user_id = :uid AND status = 'SUCCESS'";
        $res = $this->query($query, [':uid' => $userId]);
        return $res ? (float) ($res[0]->total ?? 0) : 0.0;
    }
}