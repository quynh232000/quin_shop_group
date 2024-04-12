<?php
include_once "lib/database.php";

class OrderAdmin
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function countOrder($status = "")
    {
        $statusParam = "";
        if ($status == "All" || $status == "") {
            $statusParam = "";
        } else {
            $statusParam = isset($status) ? " WHERE quingroup.order.status = '$status'" : "";
        }
        $query = "SELECT COUNT(*) as countPage FROM quingroup.order $statusParam;";
        return $this->db->selectOne($query);
    }

    public function selectAllOrder($status = "", $limit, $page)
    {
        $statusParam = "";
        if ($status == "All" || $status == "") {
            $statusParam = "";
        } else {
            $statusParam = isset($status) ? " WHERE o.status = '$status'" : "";
        }
        $paginate = " LIMIT $limit OFFSET " . ($page - 1) * $limit . " ";

        $query = "SELECT
                    o.id as order_id,
                    o.shop_id as shop_id,
                    o.status as order_status,
                    o.total as order_total,
                    o.user_id as buyer_id,
                    u.full_name as buyer_name,
                    u.avatar as buyer_avatar,
                    s.name as shop_name,
                    s.icon as shop_icon,
                    o.created_at as createdAt,
                    ap.name as ap_province,
                    ad.name as ad_district,
                    aw.name as aw_ward
                FROM
                    quingroup.order o
                JOIN 
                    shop s ON o.shop_id = s.id
                JOIN
                    user u ON o.user_id = u.id
                JOIN
                    delivery_address da ON o.delivery_address_id = da.id
                JOIN
                    address_province ap ON da.province = ap.matp
                JOIN
                    address_district ad ON da.district = ad.maqh
                JOIN
                    address_ward aw ON da.address_detail = aw.xaid
                $statusParam order by o.created_at desc $paginate";
        return $this->db->selectMany($query);
    }

    public function selectOrderDetail($order_id)
    {
        $query = "SELECT od.*, p.name, p.image_cover, p.category_id FROM
                    order_detail od
                    JOIN product p ON p.id = od.product_id
                    where od.order_id = '$order_id';";
        return $this->db->selectMany($query);
    }

    public function findIdOrder($id)
    {
        $query = " SELECT id FROM quingroup.order WHERE quingroup.order.id = '$id';";
        return $this->db->selectOne($query);
    }
}
