<?php
class TransactionAdmin
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function countPages()
    {
        $query = "SELECT 'Banking' AS name, count(*) AS count
        FROM transaction
        UNION
        SELECT 'COD' AS category, count(*) AS count
        FROM quingroup.order
        WHERE payment_method = 'COD' AND status = 'Completed';";
        return $this->db->selectMany($query);
    }

    public function selectTransactionCOD($limit, $page)
    {
        $paginate = ($page - 1) * $limit;
        $query = "SELECT o.id as order_id,
        o.total as ts_total,
        o.note as ts_content,
        o.payment_method as ts_banK_code,
        o.status as ts_status,
        o.created_at as created
        FROM quingroup.order o
        WHERE payment_method = 'COD' AND status = 'Completed' limit $limit offset $paginate;";
        return $this->db->selectMany($query);
    }

    public function selectTransactionBanking($limit, $page)
    {
        $paginate = ($page - 1) * $limit;
        $query = "SELECT ts.id as ts_id,
        ts.transaction_no as _no,
        ts.order_id as order_id,
        ts.amount as ts_total,
        ts.content as ts_content,
        ts.bank_code as ts_banK_code,
        ts.status as ts_status,
        ts.created_at as created
        FROM quingroup.transaction ts 
        INNER JOIN quingroup.order o ON o.id = ts.order_id 
        INNER JOIN quingroup.user u ON u.id = o.user_id limit $limit offset $paginate;";
        return $this->db->selectMany($query);
    }
}
