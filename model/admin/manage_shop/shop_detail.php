<?php

class ShopDetailAdmin
{
    private $db;
    public function __construct()
    {
        $this->db = new Database();
    }

    public function getInputParam()
    {
        $entityBody = file_get_contents('php://input');
        return json_decode($entityBody);
    }

    public function selectMinAndMaxDate()
    {
        $query = "SELECT 
                        DATE_FORMAT(MIN(created_at), '%Y-%m') AS min_created_at,
                        DATE_FORMAT(MAX(created_at), '%Y-%m') AS max_created_at
                    FROM quingroup.order;";
        return $this->db->selectOne($query);
    }

    public function getStatistic($sid, $status, $viewMode)
    {
        $dateFormat = "";
        $viewModeParam = "";
        switch ($viewMode) {
            case "7d":
                $viewModeParam .= " DATE_SUB(NOW(), INTERVAL 7 DAY) ";
                $dateFormat .= " DATE(quingroup.order.created_at) ";
                break;
            case "30d":
                $viewModeParam .= " DATE_SUB(NOW(), INTERVAL 30 DAY) ";
                $dateFormat .= " DATE(quingroup.order.created_at) ";
                break;
            case "12M":
                $viewModeParam .= " DATE_SUB(NOW(), INTERVAL 12 MONTH) ";
                $dateFormat .= " DATE_FORMAT(quingroup.order.created_at, '%Y-%m') ";
                break;
            case "All":
                $viewModeParam .= " (SELECT MIN(created_at) FROM quingroup.order) ";
                $dateFormat .= " DATE_FORMAT(quingroup.order.created_at, '%Y-%m') ";
                break;
        }

        $query = "SELECT $dateFormat AS order_date, COUNT(*) AS order_count, SUM(quingroup.order.total) as total_revenue
                    FROM quingroup.shop
                    INNER JOIN quingroup.order ON quingroup.order.shop_id = quingroup.shop.id
                    WHERE quingroup.order.created_at BETWEEN $viewModeParam AND NOW()
                    AND quingroup.shop.id = '$sid'
                    AND quingroup.order.status = '$status'
                    GROUP BY order_date
                    ORDER BY order_date DESC;";
        return $this->db->selectMany($query);
    }

    public function getDataStatistic()
    {
        $minAndMAxDate = self::selectMinAndMaxDate();
        $dataBody = self::getInputParam();
        if (isset($dataBody->status) && $dataBody->status) {
            if ((isset($dataBody->sid) && $dataBody->sid) && (isset($dataBody->viewMode) && $dataBody->viewMode)) {
                $value = self::getStatistic($dataBody->sid, $dataBody->status, $dataBody->viewMode);
                if (!empty($value)) {
                    echo json_encode(["status" => true, "result" => $value, "type" => $dataBody->viewMode, "min_max_date" => $minAndMAxDate], JSON_PRETTY_PRINT);
                } else {
                    echo json_encode(["status" => true, "result" => [], "type" => $dataBody->viewMode], JSON_PRETTY_PRINT);
                }
            }
        }
    }
}
