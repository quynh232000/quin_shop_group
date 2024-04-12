<?php

class DashboardAdmin
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function generalInfo()
    {
        $dayLimit = " created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
        $query = "SELECT
                    (SELECT COUNT(*) FROM traffic) as traffic_count,
                    (SELECT COUNT(*) FROM quingroup.user) as count_user,
                    (SELECT COUNT(*) FROM quingroup.user WHERE role = 'Seller') as count_seller,
                    (SELECT COUNT(*) FROM quingroup.order WHERE status = 'Completed') as new_completed_order,
                    (SELECT COUNT(*) FROM quingroup.order WHERE status = 'Cancelled') as new_cancelled_order,
                    (SELECT SUM(total) FROM quingroup.order WHERE status NOT LIKE 'Cancelled') as revenue_all,
                    (SELECT COUNT(*) FROM quingroup.product) as all_products;";
        return $this->db->selectOne($query);
    }

    public function selectMinAndMaxDate()
    {
        $query = "SELECT 
                        DATE_FORMAT(MIN(created_at), '%Y-%m') AS min_created_at,
                        DATE_FORMAT(MAX(created_at), '%Y-%m') AS max_created_at
                    FROM quingroup.order;";
        return $this->db->selectOne($query);
    }

    public function dataChart()
    {
        $query = "SELECT DATE_FORMAT(quingroup.order.created_at, '%Y-%m') AS order_date, SUM(total) revenue_order
                    FROM quingroup.order
                    INNER JOIN quingroup.user ON quingroup.order.user_id = quingroup.user.id
                    WHERE quingroup.order.status != 'Cancelled' AND quingroup.order.created_at BETWEEN (SELECT MIN(created_at) FROM quingroup.order) AND NOW()
                    GROUP BY order_date
                    ORDER BY order_date DESC;";
        return $this->db->selectMany($query);
    }

    public function jsonDataChart()
    {
        $minAndMAxDate = self::selectMinAndMaxDate();
        $data = self::dataChart();
        if ($data != false) {
            echo json_encode(["status" => true, "result" => $data, "min_max_date" => $minAndMAxDate], JSON_PRETTY_PRINT);
        } else {
            echo json_encode(["status" => false, "message" => "call api failure"], JSON_PRETTY_PRINT);
        }
    }

    public function queryOrder()
    {
        $queryOrder = "SELECT id, created_at, total, payment_method
                        FROM quingroup.order
                        WHERE DATE(created_at) = CURDATE()
                        ORDER BY created_at DESC
                        LIMIT 5;";
        return $this->db->selectMany($queryOrder);
    }

    public function queryProduct()
    {
        $queryProduct = "SELECT id, created_at, name, price, status, brand, origin
                        FROM quingroup.product
                        WHERE DATE(created_at) = CURDATE()
                        ORDER BY created_at DESC
                        LIMIT 5;";
        return $this->db->selectMany($queryProduct);
    }

    public function queryUser()
    {
        $queryUser = "SELECT id, created_at, full_name, email, phone_number, avatar
                        FROM quingroup.user
                        WHERE DATE(created_at) = CURDATE()
                        ORDER BY created_at DESC
                        LIMIT 5;";
        return $this->db->selectMany($queryUser);
    }
}
