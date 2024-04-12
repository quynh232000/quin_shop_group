<?php
// session_start();
include_once "lib/session.php";
include_once 'model/category.php';
include_once 'model/product.php';
include_once "model/category.php";
include_once "model/adminlogin.php";
include_once "model/cart.php";
include_once "model/order.php";
include_once "model/user.php";
include_once "model/address.php";
include_once "model/voucher.php";
include_once "model/notification.php";
include_once ('lib/loginfacebook/php-graph-sdk-5.x/src/Facebook/autoload.php');

$classCart = new Cart();
$classUser = new Adminlogin();
$cart_user = $classCart->get_cart_user();
$classAddress = new Address();
$classVoucher = new Voucher();
$classOrder = new Order();
$classNotify = new Notification();
extract($_REQUEST);
if (isset($act)) {
    switch ($act) {
        case 'profile':
            if (Session::get('isLogin') == false) {
                header("Location: ?mod=profile&act=login");
            }
            $viewTitle = 'Hồ sơ';
            if (isset($_POST['email']) && $_POST['email']) {
                $updateUser = $classUser->updateProfile($_POST["full_name"], $_FILES['avatar'], $_POST["phone_number"], $_POST["address"]);
                if (isset($updateUser)) {
                    if ($updateUser->status) {

                        echo '<div id="toast" mes-type="success" mes-title="Thành công!" mes-text="' . $updateUser->message . '"></div>';
                    } else {
                        echo '<div id="toast" mes-type="error" mes-title="Thành công!" mes-text="' . $updateUser->message . '"></div>';
                    }
                }
            }
            include_once 'view/inc/header.php';
            include_once 'view/inc/profilesidebar.php';
            include_once 'view/profile.php';
            include_once 'view/inc/footer.php';
            break;
        case 'login':
            $viewTitle = 'Đăng nhập';
            $class = new AdminLogin();
            $redirect = "";
            if (isset($_GET['redirect']) && $_GET['redirect'] == 'admin') {
                $redirect = "?mod=admin&act=dashboard";
            } elseif (isset($_GET['redirect']) && $_GET['redirect'] == 'seller') {
                $redirect = "?mod=seller&act=dashboard";

            } elseif (isset($_GET['redirect']) && $_GET['redirect'] == 'cart') {
                $redirect = "?mod=page&act=cart";

            } else {
                $redirect = "./";
            }
            // login with facebook

            $fb = new Facebook\Facebook(
                array(
                    'app_id' => FB_LOGIN_ID,
                    'app_secret' => FB_LOGIN_SECRECT,
                    'default_graph_version' => FB_LOGIN_VERSION,
                )
            );
            $helper = $fb->getRedirectLoginHelper();

            if (isset($_GET['code']) && isset($_GET['type']) && $_GET['type'] == 'facebook') {
                try {
                    $accessToken = $helper->getAccessToken();
                } catch (Facebook\Exceptions\FacebookResponseException $e) {
                    // When Graph returns an error
                    echo 'Graph returned an error: ' . $e->getMessage();
                    exit;
                } catch (Facebook\Exceptions\FacebookSDKException $e) {
                    // When validation fails or other local issues
                    echo 'Facebook SDK returned an error: ' . $e->getMessage();
                    exit;
                }
                if (!isset($accessToken)) {
                    if ($helper->getError()) {
                        header('HTTP/1.0 401 Unauthorized');
                        echo "Error: " . $helper->getError() . "\n";
                        echo "Error Code: " . $helper->getErrorCode() . "\n";
                        echo "Error Reason: " . $helper->getErrorReason() . "\n";
                        echo "Error Description: " . $helper->getErrorDescription() . "\n";
                    } else {
                        header('HTTP/1.0 400 Bad Request');
                        echo 'Bad request';
                    }
                    exit;
                }

                if (!$accessToken->isLongLived()) {
                    // Exchanges a short-lived access token for a long-lived one
                    try {
                        $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
                    } catch (Facebook\Exceptions\FacebookSDKException $e) {
                        echo "<p>Error getting long-lived access token: " . $e->getMessage() . "</p>\n\n";
                        exit;
                    }
                }
                # These will fall back to the default access token
                $resImg = $fb->get('/me/picture?type=large&redirect=false', $accessToken->getValue());
                $picture = $resImg->getDecodedBody();

                $response = $fb->get('/me?fields=id,name,email,picture', $accessToken);
                $user = $response->getGraphUser();

                $email = $user['email'];
                $full_name = $user['name'];
                $avatar = $picture['data']['url'];



                // The URL of the user's profile picture
                // get data from facebook and check in database
                $login_check = $class->login_with_app($email, $full_name, $avatar, $redirect);
            } else {
                $permissions = array('email'); // Optional permissions
                $loginUrl = $helper->getLoginUrl(BASE_URL . '?mod=profile&act=login&type=facebook', $permissions);
            }
            // login with facebook
            // login with google
            include_once 'lib/logingoogle/vendor/autoload.php';
            $client = new Google_Client();
            $client->setClientId(GOOGLE_APP_ID);
            $client->setClientSecret(GOOGLE_APP_SECRET);
            $client->setRedirectUri(GOOGLE_APP_CALLBACK_URL . '&type=google');
            $client->addScope('email');
            $client->addScope('profile');

            if (isset($_GET['code']) && isset($_GET['type']) && $_GET['type'] == 'google') {
                $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
                if (!isset($token['error'])) {
                    $client->setAccessToken($token['access_token']);
                    $_SESSION['access_token'] = $token['access_token'];

                    $google_oauth = new Google_Service_Oauth2($client);
                    $google_account_info = $google_oauth->userinfo->get();

                    $email = $google_account_info->getEmail();
                    $full_name = $google_account_info->getName();
                    $avatar = $google_account_info->getPicture();

                    $login_check = $class->login_with_app($email, $full_name, $avatar, $redirect);

                }
            } else {
                $auth_url = $client->createAuthUrl();
                $url_login_google = filter_var($auth_url, FILTER_SANITIZE_URL);

            }
            // login with google


            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                $email = $_POST['email'];
                $password = $_POST['password'] ? md5($_POST['password']) : "";
                $login_check = $class->login_admin($email, $password, $redirect);
            }
            include_once 'view/login.php';
            break;
        case 'register':
            $viewTitle = 'Đăng kí';
            $class = new Adminlogin();
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $checkRegister = $class->register_admin(
                    $_POST['full_name'],
                    $_POST['email'],
                    $_POST['phone'],
                    ($_POST['password']),
                    ($_POST['confirmpassword'])
                );
            }
            include_once 'view/register.php';
            break;


        case 'orderhistory':
            $viewTitle = 'Lịch sử đơn hàng';
            $classOrder = new Order();
            $urlFilter = "?mod=profile&act=orderhistory";
            $status = '';
            $search = "";
            $page = 1;
            $limit = 5;
            // get list order
            if (isset($_GET['status'])) {
                $status = $_GET['status'];
                $urlFilter .= '&status=' . $status;
            }
            if (isset($_GET['search'])) {
                $search = $_GET['search'];
                $urlFilter .= '&search=' . $search;
            }
            if (isset($_GET['page'])) {
                $page = $_GET['page'];
                $urlFilter .= '&page=' . $page;
            }
            $orders = $classOrder->get_list_user_order($status, $search, $page, $limit);
            if ($orders->status == true) {
                $list_order = $orders->result;
                $total = $orders->total;
            }
            // cancel ỏder
            if (isset($_POST['submit_delete']) && $_POST['submit_delete']) {
                $cancel_order = $classOrder->cancel_order_user($_POST['order_uuid']);
                if ($cancel_order->status) {
                    echo '<div id="toast" mes-type="error" mes-title="Thành công!" mes-text="' . $cancel_order->message . '"></div>';
                    echo ' <script>
                             setTimeout(function() {
                                 window.location.href="?mod=profile&act=order_detail&order=' . $_POST['order_uuid'] . '";
                             }, 2500);
                         </script>';
                }
            }

            include_once 'view/inc/header.php';
            include_once 'view/inc/profilesidebar.php';
            include_once 'view/orderhistory.php';

            include_once 'view/inc/footer.php';
            break;
        case 'sercurity':
            $viewTitle = 'Đổi mật khẩu';
            if (isset($_POST['submit-change-pass']) && $_POST['submit-change-pass']) {
                $oldpassword = $_POST['oldpassword'];
                $newpassword = $_POST['newpassword'];
                $confirmpassword = $_POST['confirmpassword'];
                if (($oldpassword) == '' || ($newpassword) == "" || ($confirmpassword) == "") {
                    echo '<div id="toast" mes-type="error" mes-title="Thất bại!" mes-text="Vui lòng nhập đầy đủ thông tin"></div>';
                } else {
                    $classUser = new User();
                    $checkPass = $classUser->checkPass(md5($oldpassword));
                    if ($checkPass->status == false) {
                        echo '<div id="toast" mes-type="error" mes-title="Thất bại!" mes-text="' . $checkPass->message . '"></div>';
                    } else {
                        if ($newpassword != $confirmpassword) {
                            echo '<div id="toast" mes-type="error" mes-title="Thất bại!" mes-text="Mật khẩu mới không khớp nhau!"></div>';
                        } else {
                            $changeNewPass = $classUser->changeNewPass(md5($newpassword));
                            if ($changeNewPass->status == false) {
                                echo '<div id="toast" mes-type="error" mes-title="Thất bại!" mes-text="' . $changeNewPass->message . '"></div>';
                            } else {
                                echo '<div id="toast" mes-type="success" mes-title="Thành công!" mes-text="Thay đổi mật khẩu thành công!"></div>';
                            }
                        }
                    }
                }
            }


            include_once 'view/inc/header.php';
            include_once 'view/inc/profilesidebar.php';
            include_once 'view/sercurity.php';
            include_once 'view/inc/footer.php';
            break;
        case 'forgotpassword':
            $active = 'default';
            if (isset($_POST['email']) && $_POST['email'] != "") {

                $email = $_POST['email'];

                $checkemail = $classUser->sendCodePassEmail($email);

                if ($checkemail->status == false) {
                    echo '<div id="toast" mes-type="error" mes-title="Thất bại!" mes-text="' . $checkemail->message . '"></div>';
                } else {
                    $active = 'waiting-check-email';

                    // header('location: ' . $checkemail->redirect);
                }
            }
            // /**VINH */

            // checktoken 
            if (isset($_GET['token']) && $_GET['token']) {
                $active = 'changepassword';
                $result_token = $classUser->checkToken($_GET['token']);
                if ($result_token->status) {
                } else {
                    $active = 'tokenerror';
                }
            }

            // submit password
            if (isset($_POST['password']) && $_POST['password']) {
                $active = 'changepassword';
                $pass = $_POST['password'];
                $passConfirm = $_POST['passwordconfirm'];
                if ($passConfirm == "") {
                    echo '<div id="toast" mes-type="error" mes-title="Thất bại!" mes-text="Vui lòng nhập đầy đủ thông tin!"></div>';
                } elseif ($pass != $passConfirm) {
                    echo '<div id="toast" mes-type="error" mes-title="Thất bại!" mes-text="Mật khẩu không khớp. Vui lòng nhập lại"></div>';
                } else {
                    $token = $_GET['token'];
                    $changePass = $classUser->changePassword(md5($pass), $token);
                    if ($changePass->status == false) {
                        echo '<div id="toast" mes-type="error" mes-title="Thất bại!" mes-text="' . $changePass->message . '"></div>';
                    } else {
                        echo '<div id="toast" mes-type="success" mes-title="Thành công!" mes-text="Thay đổi mật khẩu thành công!"></div>';

                        echo ' <script>
                            setTimeout(function() {
                                window.location.href="?mod=profile&act=login";
                            }, 2500);
                        </script>';
                    }
                }
            }


            include_once 'view/forgotpassword.php';
            break;
        case 'address':
            // get address info by id
            $id = $_GET['id'] ?? "";
            if (isset($type) && ($type == 'update') && isset($id) && $id) {
                $address = $classAddress->get_address_by_id($id);
                if ($address->status == true) {
                    $address_info = $address->result;
                }
            }
            // update address
            if (isset($type) && (($type == 'delete') || ($type == 'set_default')) && isset($id) && $id) {
                $update_address = $classAddress->update_address_user($type, $id);
            }
            $type = $_GET['type'] ?? "";
            if (isset($_POST['submit_address']) && $_POST['submit_address']) {
                $name_receiver = $_POST['name_receiver'] ?? "";
                $phone_number = $_POST['phone_number'] ?? "";
                $province = $_POST['province'] ?? "";
                $district = $_POST['district'] ?? "";
                $address_detail = $_POST['address_detail'] ?? "";
                $is_default = $_POST['is_default'] ?? "";
                $update_address = $classAddress->update_address_user($type, $id, $name_receiver, $phone_number, $province, $district, $address_detail, $is_default);
            }
            if (isset($update_address)) {
                if ($update_address->status) {
                    echo '<div id="toast" mes-type="success" mes-title="Thành công!" mes-text="' . $update_address->message . '"></div>';
                    $redirect = isset($_POST['shop']) ? "?mod=page&act=checkout&shop=" . $_POST['shop'] : "";
                    $redirect = isset($_POST['voucher']) ? $redirect . "&voucher=" . $_POST['voucher'] : $redirect;
                    if (empty($redirect)) {
                        echo ' <script>
                                setTimeout(function() {
                                    window.location.href="?mod=profile&act=address";
                                }, 2500);
                            </script>';

                    } else {
                        echo ' <script>
                                setTimeout(function() {
                                    window.location.href="' . $redirect . '";
                                }, 2500);
                            </script>';
                    }


                } else {
                    echo '<div id="toast" mes-type="error" mes-title="Thất bại!" mes-text="' . $update_address->message . '"></div>';
                }

            }
            // get all address
            $all_address = $classAddress->get_all_address_user();
            $viewTitle = "Quản lý địa chỉ";
            include_once 'view/inc/header.php';
            include_once 'view/inc/profilesidebar.php';
            include_once 'view/address.php';
            include_once 'view/inc/footer.php';
            break;
        case "voucher":
            $viewTitle = "Quản lý Voucher";
            $type = 'all';
            if (isset($_GET['type']) && $_GET['type'])
                $type = $_GET['type'];
            $all_voucher = $classVoucher->get_voucher_user($type);
            if (isset($_POST['btn_submit_search']) && $_POST['btn_submit_search']) {
                $all_voucher = $classVoucher->get_voucher_user($type, $_POST['search_voucher']);
            }
            include_once 'view/inc/header.php';
            include_once 'view/inc/profilesidebar.php';
            include_once 'view/voucher.php';
            include_once 'view/inc/footer.php';
            break;
        case "notification":
            $viewTitle = "Quản lý thông báo";
            $urlFilter = "?mod=profile&act=notification";
            $limit = 5;
            $page = 1;
            if (isset($_GET['page'])) {
                $page = $_GET['page'];
                $urlFilter .= '&page=' . $page;

            }
            $notifies = $classNotify->get_notification_user($page, $limit);
            $total = $notifies->total;
            // read notification
            $classNotify->read_notify_user();

            include_once 'view/inc/header.php';
            include_once 'view/inc/profilesidebar.php';
            include_once 'view/notification.php';
            include_once 'view/inc/footer.php';
            break;
        case "order_detail":
            if (isset($_GET['order']) && $_GET['order']) {
                $result = $classOrder->get_order_user_detail($_GET['order']);
                if ($result->status) {
                    $data = $result->result;
                } else {
                    header("Location: ?page=404");
                }
            } else {
                header("Location: ?page=404");
            }
            // cancel order
            if (isset($_POST['submit_delete']) && $_POST['submit_delete']) {
                $cancel_order = $classOrder->cancel_order_user($_POST['order_uuid']);
                if ($cancel_order->status) {
                    echo '<div id="toast" mes-type="error" mes-title="Thành công!" mes-text="' . $cancel_order->message . '"></div>';
                    echo ' <script>
                            setTimeout(function() {
                                window.location.href="?mod=profile&act=order_detail&order=' . $_POST['order_uuid'] . '";
                            }, 2500);
                        </script>';
                }
            }

            $viewTitle = "Chi tiết đơn hàng";
            include_once 'view/inc/header.php';
            include_once 'view/inc/profilesidebar.php';
            include_once 'view/order_detail.php';
            include_once 'view/inc/footer.php';
            break;
        case 'changepass_phone':
            $viewTitle = "Đổi mật khẩu";
            $active = 'default';
            // call phone
            if (isset($_POST['phone']) && $_POST['phone']) {
                $phone = $_POST['phone'];
                if (empty($phone)) {
                    echo '<div id="toast" mes-type="error" mes-title="Thất bại!" mes-text="Không được để trống thông tin!"></div>';
                } else {
                    $call_phone = $classUser->call_phone_changepass($phone);
                    if ($call_phone->status) {
                        echo ' <script>
                                window.location.href="' . $call_phone->redirect . '";
                            </script>';
                    } else {
                        echo '<div id="toast" mes-type="error" mes-title="Thất bại!" mes-text="' . $call_phone->message . '"></div>';
                    }
                }
            }
            if (isset($_GET['token']) && $_GET['token']) {
                $active = 'submitcode';
            }
            // submit code verify phone
            if (isset($_POST['submit_check_code_phone']) && $_POST['submit_check_code_phone']) {
                $result_check_code = $classUser->check_code_phone($_POST['token'], $_POST['code_phone']);
                if ($result_check_code->status) {

                    $active = 'changepassword';
                } else {
                    echo '<div id="toast" mes-type="error" mes-title="Thất bại!" mes-text="' . $result_check_code->message . '"></div>';
                }
            }


            // submit
            if (isset($_POST['password']) && $_POST['password']) {
                $active = 'changepassword';
                $pass = $_POST['password'];
                $passConfirm = $_POST['passwordconfirm'];
                if ($passConfirm == "") {
                    echo '<div id="toast" mes-type="error" mes-title="Thất bại!" mes-text="Vui lòng nhập đầy đủ thông tin!"></div>';
                } elseif ($pass != $passConfirm) {
                    echo '<div id="toast" mes-type="error" mes-title="Thất bại!" mes-text="Mật khẩu không khớp. Vui lòng nhập lại"></div>';
                } else {
                    $token = $_POST['token'];
                    $changePass = $classUser->changePassword_phone(md5($pass), $token);
                    if ($changePass->status == false) {
                        echo '<div id="toast" mes-type="error" mes-title="Thất bại!" mes-text="' . $changePass->message . '"></div>';
                    } else {
                        echo '<div id="toast" mes-type="success" mes-title="Thành công!" mes-text="Thay đổi mật khẩu thành công!"></div>';

                        echo ' <script>
                                setTimeout(function() {
                                    window.location.href="?mod=profile&act=login";
                                }, 2500);
                            </script>';
                    }
                }
            }

            include_once 'view/forgotpassword_phone.php';
            break;

        default:
            include_once 'view/inc/header.php';
            include_once 'view/error.php';
            include_once 'view/inc/footer.php';
    }
}
