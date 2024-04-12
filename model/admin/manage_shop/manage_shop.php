<?php

class ShopAdmin
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function selectAllShop($limit, $page)
    {
        $offset = ($page - 1) * $limit;
        $paginate = " LIMIT $limit OFFSET $offset";
        $query = "SELECT 
                    s.id as s_id, 
                    s.name as s_name, 
                    s.icon as s_icon, 
                    s.phone_number as phone_number, 
                    s.created_at as created,
                    ap.name as ap_province,
                    ad.name as ad_district,
                    aw.name as aw_ward,
                    u.email as u_email,
                    u.role as u_role
                FROM 
                    quingroup.shop s
                INNER JOIN
                    quingroup.user u ON s.user_id = u.id
                INNER JOIN
                    address_province ap ON s.province = ap.matp
                INNER JOIN
                    address_district ad ON s.district = ad.maqh
                INNER JOIN
                    address_ward aw ON s.address_detail = aw.xaid
                WHERE 
                    s.is_deleted = 0 $paginate;";
        return $this->db->selectMany($query);
    }

    public function countPages()
    {
        $query = "SELECT COUNT(*) as countPage FROM quingroup.shop;";
        return $this->db->selectOne($query);
    }

    public function findShop($id)
    {
        $query = " SELECT s.id as s_id, s.name as s_name, s.icon as s_icon, s.phone_number as phone_number, s.created_at as created, u.email as u_email
        FROM quingroup.shop s INNER JOIN quingroup.user u ON s.user_id = u.id WHERE s.id = '$id';";
        return $this->db->selectOne($query);
    }

    public function searchShopByPhoneNumOrEmail($search)
    {
        $query = " SELECT s.id 
                    FROM quingroup.shop s 
                    INNER JOIN quingroup.user u ON u.id = s.user_id
                    WHERE u.role = 'Seller' AND u.email = '$search';";
        return $this->db->selectOne($query);
    }
}
