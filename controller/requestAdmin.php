<?php
include_once 'model/admin/manage_order/manage_order.php';
include_once 'model/admin/manage_user/manage_user.php';
include_once 'model/admin/manage_user/user_detail.php';
include_once 'model/admin/manage_shop/shop_detail.php';
include_once 'model/entity.php';
include_once 'model/admin/dashboard.php';
$orderAdmin = new OrderAdmin();
$userAdmin = new UserAdmin();
$userDetailAdmin = new UserDetailAdmin();
$shopDetailAdmin = new ShopDetailAdmin();
$dashboardAdmin = new DashboardAdmin();
extract($_REQUEST);
if (isset($status) && $status) {
    switch (strtolower($status)) {
        case "all":
        case "new":
        case "processing":
        case "confirmed":
        case "completed":
        case "cancelled":
            // $orderAdmin->getOrders();
            break;
        default:
            // $orderAdmin->getOrders();
            break;
    }
} else if (isset($act) && $act) {
    switch ($act) {
        case "order-detail":
            // $orderAdmin->orderDetail();
            break;
        case "edit-user":
            $userAdmin->doingWithUser();
            break;
        case "update-user":
            $value = $userAdmin->updateUser();
            if ($value->status == false) {
                echo json_encode(["status" => false, "message" => "You do not have permission"], JSON_PRETTY_PRINT);
            }
            break;
        case "user_detail":
            $data = $userDetailAdmin->getDataStatistic();
            break;
        case "shop_detail":
            $data = $shopDetailAdmin->getDataStatistic();
            break;
        case "dashboard":
            $dashboardAdmin->jsonDataChart();
            break;
        default:
            header("location: ?page=404");
            break;
    }
}
