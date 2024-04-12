<?php
// include_once "lib/database.php";
include_once 'model/adminlogin.php';
$classAdmin = new Adminlogin();
$classAdmin->check_permistion();
// session_destroy();
include_once 'model/admin/manage_category.php';
include_once 'model/admin/manage_product.php';
include_once 'model/admin/manage_order/manage_order.php';
include_once 'model/admin/manage_user/manage_user.php';
include_once 'model/admin/manage_user/user_detail.php';
include_once 'model/admin/dashboard.php';
include_once 'model/admin/manage_transaction.php';
include_once 'model/admin/manage_shop/manage_shop.php';
$categoryAdmin = new CategoryAdmin();
$productAdmin = new ProductAdmin();
$orderAdmin = new OrderAdmin();
$userAdmin = new UserAdmin();
$userDetailAdmin = new UserDetailAdmin();
$dashboardAdmin = new DashboardAdmin();
$transactionAdmin = new TransactionAdmin();
$shopAdmin = new ShopAdmin();
extract($_REQUEST);
if (isset($act) && $act) {
    function checkMethod($m, $methods)
    {
        if (in_array($m, $methods)) {
            return true;
        } else {
            return false;
        }
    }
    switch ($act) {
            //dashboard
        case "dashboard":
            $generalInfo = $dashboardAdmin->generalInfo();
            $newestOrder = $dashboardAdmin->queryOrder();
            $newestProduct = $dashboardAdmin->queryProduct();
            $newestUser = $dashboardAdmin->queryUser();
            include_once "view/admin/component/header.php";
            include_once "view/admin/component/dashboard.php";
            include_once "view/admin/component/scripts.php";
            break;

            //category
        case "mn_settings_cat":
            if (isset($_POST["submit"]) && $_POST["submit"]) {
                $id_parent = $_POST["id_parent"];
                $name_category = $_POST["name_category"];
                $icon = $_FILES["icon"];
                $type = $_POST["type"];
                $createCategory = $categoryAdmin->manageCategory($name_category, $icon, $id_parent, $type);
                if ($createCategory->status == false) {
                    $errMess = $createCategory->message;
                }
            }
            $arrayOfCategories = $categoryAdmin->getAllCate();
            include_once "view/admin/component/header.php";
            include_once "view/admin/pages/manage_category/settings_category.php";
            include_once "view/admin/component/scripts.php";
            break;
            // product
        case "mn_all_products":
            $status = "";
            $statusArr = ["New", "Activated", "Rejected"];
            if ((!isset($_GET["status"]) || in_array($_GET["status"], $statusArr))) {
                if (isset($_GET["status"])) {
                    $status = $_GET["status"];
                }
            } else {
                header("location: ?page=404");
            }

            $products = $productAdmin->getProducts($status, 0, 0);
            $maxPage = ceil(count($products) / 10);
            if ($maxPage == 0) $maxPage = 1;
            if (isset($_GET["page"]) && is_numeric($_GET["page"]) && $_GET["page"] <= $maxPage) {
                $page = $_GET["page"];
                $productPagination = $productAdmin->getProducts($status, 10, ($page));
            } else {
                header("location: ?page=404");
                exit;
            }

            if (isset($_POST["approve"]) && $_POST["approve"]) {
                $idProduct = $_POST["id_product"];
                $result = $productAdmin->updateProduct("Activated", $idProduct);
                if ($result->status == true) {
                    header("location: ?mod=admin&act=mn_all_products&status=Activated&page=1");
                }
            } else if (isset($_POST["reject"]) && $_POST["reject"] && isset($_POST["reason"])) {
                $idProduct = $_POST["id_product"];
                $reason = $_POST["reason"];
                $result = $productAdmin->updateProduct("Rejected", $idProduct, $reason);
                if ($result->status == true) {
                    header("location: ?mod=admin&act=mn_all_products&status=Rejected&page=1");
                }
            }
            include_once "view/admin/component/header.php";
            include_once "view/admin/pages/manage_product/all_products.php";
            include_once "view/admin/component/scripts.php";
            break;

            //order
        case "mn_all_order":
            $status = ["All", "New", "Processing", "Confirmed", "Completed", "Cancelled"];
            if (isset($_GET["status"]) && in_array($_GET["status"], $status)) {
                $countpages = $orderAdmin->countOrder($_GET["status"]);
                $maxPage = ceil($countpages["countPage"] / 5);
                if ($maxPage == 0) $maxPage = 1;
                if (isset($_GET["page"]) && is_numeric($_GET["page"]) && $_GET["page"] <= $maxPage) {
                    $orders = $orderAdmin->selectAllOrder($_GET["status"], 5, $_GET["page"]);
                } else {
                    header("location: ?mod=admin&act=mn_all_order&status=All&page=1");
                }
            } else {
                header("location: ?page=404");
            }
            include_once "view/admin/component/header.php";
            include_once "view/admin/pages/manage_order/all_order.php";
            include_once "view/admin/component/scripts.php";
            break;
        case "detail_order":
            if (isset($_GET["oid"]) && $_GET["oid"]) {
                $orderDetail = $orderAdmin->selectOrderDetail($_GET["oid"]);
            } else {
                $orderDetail = false;
            }

            if (isset($_GET["search"]) && $_GET["search"]) {
                $oid = $orderAdmin->findIdOrder(str_replace(' ', '', $_GET["search"]));
                header("location: ?mod=admin&act=detail_order&oid=" . $oid["id"] . "");
            }
            include_once "view/admin/component/header.php";
            include_once "view/admin/pages/manage_order/detail_order.php";
            include_once "view/admin/component/scripts.php";
            break;

            //transaction
        case "mn_transaction":
            $method = ["COD", "Banking"];
            if ($_GET["p_method"] && $_GET["p_method"]) {
                $checkOk = checkMethod($_GET["p_method"], $method);
                if ($checkOk) {
                    $countpages = $transactionAdmin->countPages();
                    $quantity = 0;
                    foreach ($countpages as $value) {
                        extract($value);
                        if ($_GET["p_method"] == $name) {
                            $maxPage = ceil(intval($count) / 5);
                            $quantity += $count;
                            if ($maxPage == 0) $maxPage = 1;
                        }
                    }
                    if (isset($_GET["page"]) && is_numeric($_GET["page"]) && $_GET["page"] <= $maxPage) {
                        $cod = $transactionAdmin->selectTransactionCOD(5, $_GET["page"]);
                        $ts = $transactionAdmin->selectTransactionBanking(5, $_GET["page"]);
                    } else {
                        header("location: ?page=404");
                    }
                    include_once "view/admin/component/header.php";
                    include_once "view/admin/pages/manage_transaction/all_transaction.php";
                    include_once "view/admin/component/scripts.php";
                } else {
                    header("location: ?page=404");
                }
            } else {
                header("location: ?page=404");
            }
            break;

            //user
        case "mn_all_user":
            $role = "";
            $roles = ["All", "Member", "Seller", "Admin", "AdminAll"];
            if (isset($_GET["role"]) && in_array($_GET["role"], $roles)) {
                $role = $_GET["role"];
                $countPageUser = $userAdmin->countPages($role);
                $maxPage = ceil($countPageUser["total"] / 5);
                if ($maxPage == 0) $maxPage = 1;
                if (isset($_GET["page"]) && ($_GET["page"] <= $maxPage && !empty($_GET["page"]))) {
                    $page = $_GET["page"];
                    $users = $userAdmin->selectUsers($role, 5, $page);
                } else {
                    header("location: ?page=404");
                    exit;
                }
            } else {
                header("location: ?page=404");
                exit;
            }

            include_once "view/admin/component/header.php";
            include_once "view/admin/pages/manage_user/all_user.php";
            include_once "view/admin/component/scripts.php";
            break;

        case "mn_user_detail":
            if (isset($_GET["uid"]) && $_GET["uid"]) {
                $user = $userDetailAdmin->getInfoUser($_GET["uid"]);
            } else {
                $user = false;
            }

            if (isset($_GET["search"]) && $_GET["search"]) {
                $uid = $userDetailAdmin->searchUserByEmail(str_replace(' ', '', $_GET["search"]));
                header("location: ?mod=admin&act=mn_user_detail&uid=" . $uid["id"] . "");
            }

            include_once "view/admin/component/header.php";
            include_once "view/admin/pages/manage_user/detail_user.php";
            include_once "view/admin/component/scripts.php";
            break;

            //shop
        case "mn_all_shop":
            $countpage = $shopAdmin->countPages();
            $maxPage = ceil($countpage["countPage"] / 5);
            if ($maxPage == 0) $maxPage = 1;
            if (isset($_GET["page"]) && is_numeric($_GET["page"]) && $_GET["page"] <= $maxPage) {
                $shop = $shopAdmin->selectAllShop(5, $_GET["page"]);
            } else {
                header("location: ?page=404");
            }
            include_once "view/admin/component/header.php";
            include_once "view/admin/pages/manage_shop/all_shop.php";
            include_once "view/admin/component/scripts.php";
            break;

        case "detail_shop":
            $status = ["new", "completed", "cancelled"];
            if (isset($_GET["status"]) && in_array($_GET["status"], $status)) {
                if (isset($_GET["sid"]) && $_GET["sid"]) {
                    $detailShop = $shopAdmin->findShop($_GET["sid"]);
                } else {
                    $detailShop = false;
                }
            } else {
                $detailShop = false;
            }
            if (isset($_GET["search"]) && $_GET["search"]) {
                $sid = $shopAdmin->searchShopByPhoneNumOrEmail(str_replace(' ', '', $_GET["search"]));
                header("location: ?mod=admin&act=detail_shop&status=new&sid=" . $sid["id"] . "");
            }
            include_once "view/admin/component/header.php";
            include_once "view/admin/pages/manage_shop/detail_shop.php";
            include_once "view/admin/component/scripts.php";
            break;
        default:
            header("location: ?page=404");
            break;
    }
} else {
    header("location: ?page=404");
}
