<?php

include_once 'lib/phpmailer/src/PHPMailer.php';
include_once 'lib/phpmailer/src/SMTP.php';
include_once 'lib/phpmailer/src/Exception.php';
include_once "lib/database.php";
include_once "lib/database.php";
include_once "helpers/format.php";
include_once "helpers/tool.php";
include_once "model/entity.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

?>
<?php
class Adminlogin
{
    private $db;
    private $fm;
    private $tool;

    public function __construct()
    {
        $this->db = new Database();
        $this->fm = new Format();
        $this->tool = new Tool();
    }
    public function sync_data_cart($user_id)
    {
        $cart_session = isset($_SESSION['CART']) ? $_SESSION['CART'] : [];

        // if cart_session is empty , dont do anythhing;
        if (count($cart_session) == 0)
            return;

        $cart_new = [];
        foreach ($cart_session as $key => $value) {
            $cart_new[] = ['product_id' => $key, 'quantity' => $value['quantity']];
        }
        $cart_db = $this->db->select("SELECT product_id, quantity FROM cart WHERE user_id = '$user_id'")->fetchAll();

        // if cart doesnt exist in db => add all cart_session to db
        if (count($cart_db) == 0) {
            $valueQuery = '';
            foreach ($cart_new as $key => $value) {
                $product_id = $value['product_id'];
                $quantity = $value['quantity'];
                $valueQuery .= " ('$product_id', '$user_id', $quantity) ,";
            }
            $valueQuery = rtrim($valueQuery, ",");
            $this->db->insert("INSERT INTO cart ( product_id, user_id, quantity) values $valueQuery");
            return;
        }

        // if has cart_session and cart_ab => asyn db
        $value_insert_new = '';
        $array_update = [];
        foreach ($cart_new as $key1 => $value1) {
            $is_exist = false;
            $is_equal = false;
            foreach ($cart_db as $key2 => $value2) {
                if ($value1['product_id'] == $value2['product_id']) {
                    $is_exist = true;
                    if ($value1['quantity'] == $value2['quantity']) {
                        $is_equal = true;
                    }
                    break;
                }
            }
            if (($is_exist == true)) {
                if ($is_equal == false) {
                    $array_update[$value2['product_id']] = $value1['quantity'];
                }
            } else {
                $value_insert_new .= " ('" . $value1['product_id'] . "','" . $user_id . "','" . $value1['quantity'] . "') ,";
            }
        }
        // insert
        if (!empty($value_insert_new)) {
            $value_insert_new = rtrim($value_insert_new, ",");
            $this->db->insert("INSERT INTO cart (product_id , user_id , quantity) values $value_insert_new");
        }
        if (count($array_update) > 0) {
            foreach ($array_update as $key => $value) {
                $this->db->update("UPDATE cart set quantity = '$value' WHERE user_id ='$user_id' AND product_id = '$key'");
            }
        }
        // update
    }
    public function login_admin($email, $password = '', $redirect = "", $type = '')
    {
        // dang nhap voi google hoac fb====================================
        if ($type == 'withapp') {
            $query = "SELECT * FROM user WHERE email ='$email' LIMIT 1";
            $user = $this->db->select($query);
            $value = $user->fetch();
            if ($redirect == '?mod=admin&act=dashboard') {
                if ((!in_array($value['role'], ['Admin', 'AdminAll']))) {
                    return ["status" => false, "message" => "Bạn không có quyền truy cập vào trang quản trị!", "result" => [], "redirect" => "?mod=profile&act=login&redirect=admin"];
                }
            }
            self::sync_data_cart($value['id']);
            Session::set('isLogin', true);
            Session::set('id', $value['id']);
            Session::set('full_name', $value['full_name']);
            Session::set('email', $value['email']);
            Session::set('avatar', $value['avatar']);
            Session::set('role', $value['role']);
            Session::set('phone', $value['phone_number']);
            return ["status" => true, "message" => "Đăng nhập thành công!", "result" => [], "redirect" => $redirect];
        }
        // dang nhap thuong =================================================
        if (empty($password) || empty($email)) {
            $alert = "Vui lòng nhập đầy đủ thông tin!";
            return ["status" => false, "message" => $alert, "result" => [], "redirect" => ""];
        } else {
            $query = "SELECT * FROM user WHERE email ='$email' AND password = '$password' LIMIT 1";
            $user = $this->db->select($query);
            $value = $user->fetch();
            if ($value != false) {

                if ($redirect == '?mod=admin&act=dashboard') {
                    if ((!in_array($value['role'], ['Admin', 'AdminAll']))) {
                        return ["status" => false, "message" => "Bạn không có quyền truy cập vào trang quản trị!", "result" => [], "redirect" => "?mod=profile&act=login&redirect=admin"];
                    }
                }
                self::sync_data_cart($value['id']);
                Session::set('isLogin', true);
                Session::set('id', $value['id']);
                Session::set('full_name', $value['full_name']);
                Session::set('email', $value['email']);
                Session::set('avatar', $value['avatar']);
                Session::set('role', $value['role']);
                Session::set('phone', $value['phone_number']);
                return ["status" => true, "message" => "Đăng nhập thành công!", "result" => [], "redirect" => $redirect];
            } else {
                $alert = "Tên đăng nhập hoặc tài khoản không đúng!";
                return ["status" => false, "message" => $alert, "result" => [], "redirect" => ""];
            }
        }
    }
    public function register_admin($fullName, $email, $phone = '', $password = '', $confirmPassword = '', $avatar = '', $type = '', $redirect = '')
    {
        // register with google or facebook
        if ($type == 'withapp') {
            $id = $this->tool->GUID();
            $avatar = !empty($avatar) ? $avatar : "user_avatar.jpg";
            $query = "INSERT INTO user (id,full_name,email,avatar) VALUE
               ( '$id',
                 '$fullName',
                '$email',
                '$avatar')
            ";
        } else {
            $redirect = "./";
            // register nomarl
            if (empty($fullName)) {
                $alert = "Họ và tên không được để trống!";
                return ["status" => false, "message" => $alert, "result" => []];
            }
            if (empty($email)) {
                $alert = "Email không được để trống!";
                return ["status" => false, "message" => $alert, "result" => []];
            }


            if (empty($fullName)) {
                $alert = "Họ và tên không được để trống!";
                return ["status" => false, "message" => $alert, "result" => []];
            }
            if (empty($email)) {
                $alert = "Email không được để trống!";
                return ["status" => false, "message" => $alert, "result" => []];
            }

            if (strlen($email) < 10) {
                return ["status" => false, "message" => 'Email phải có tổi thiểu 10 kí tự!', "result" => []];
            }
            if (strlen($email) > 200) {
                return ["status" => false, "message" => 'Email quá dài!', "result" => []];
            }

            if (empty($password) || empty($confirmPassword)) {
                $alert = "Mật khẩu không được để trống!!";
                return ["status" => false, "message" => $alert, "result" => []];
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

                return ["status" => false, "message" => 'Email không đúng định dạng!', "result" => []];
            }

            // REGEX VINH

            if ($password != $confirmPassword) {
                $alert = "Mật khẩu không khớp!";
                return ["status" => false, "message" => $alert, "result" => []];
            }



            if (strlen($email) < 10) {
                return ["status" => false, "message" => 'Email phải có tổi thiểu 10 kí tự!', "result" => []];

            }
            if (strlen($email) > 200) {
                return ["status" => false, "message" => 'Email quá dài!', "result" => []];
            }

            if (empty($password) || empty($confirmPassword)) {
                $alert = "Mật khẩu không được để trống!!";
                return ["status" => false, "message" => $alert, "result" => []];

            }
            if (strlen($password) < 8) {
                return ["status" => false, "message" => 'Password phải có tổi thiểu 10 kí tự!', "result" => []];

            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

                return ["status" => false, "message" => 'Email không đúng định dạng!', "result" => []];
            }
            // REGEX VINH
            $regex_email = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
            $regex_name = '~^[\w+]{1,}( ?[\w+]){1,}$~';
            $regex_phone = '~^0[0-9]{9,10}$~';

            if (!preg_match($regex_email, $email)) {

                return ["status" => false, "message" => 'Email không đúng định dạng!', "result" => []];
            }
            // if (!preg_match($regex_name, $fullName)) {

            //     return ["status" => false, "message" => 'Họ tên không đúng định dạng!', "result" => []];
            // }
            if (!preg_match($regex_phone, $phone)) {

                return ["status" => false, "message" => 'Số điện thoại không đúng định dạng!', "result" => []];
            }
            // REGEX VINH

            if ($password != $confirmPassword) {
                $alert = "Mật khẩu không khớp!";
                return ["status" => false, "message" => $alert, "result" => []];

                if (strlen($email) < 10) {
                    return ["status" => false, "message" => 'Email phải có tổi thiểu 10 kí tự!', "result" => []];
                }
                if (strlen($email) > 100) {
                    return ["status" => false, "message" => 'Email quá dài!', "result" => []];
                }
            }



            $checkUser = $this->db->select("select * from user where email = '$email';");
            if (count($checkUser->fetchAll()) > 0) {
                return ["status" => false, "message" => "Email đã tồn tại! Vui lòng sử dụng email khác.", "result" => []];
            }
            $checkPhone = $this->db->select("select * from user where phone_number = '$phone';");
            if (count($checkPhone->fetchAll()) > 0) {
                return ["status" => false, "message" => "Số điện thoại đã tồn tại! Vui lòng sử dụng số điện thoại khác.", "result" => []];
            }
            $avatar = 'avatar_user.jpg';
            $id = $this->tool->GUID();
            $password = md5($password);
            $query = "INSERT INTO user (id,full_name,email,phone_number,password,avatar) VALUE
               ( '$id',
                 '$fullName',
                '$email',
                '$phone',
                '$password',
                '$avatar')
            ";
        }

        $this->db->insert($query);
        // MAIL VINH CAO
        $url_redirect = BASE_URL;
        $subject = "Chào mừng $fullName đã đến với Quin Shop";
        $content = <<<EOT
        <!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="UTF-8" />
                    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                    <title>Mail - shop</title>
                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
                    integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
                    crossorigin="anonymous" referrerpolicy="no-referrer" />
                    <style>
                        *{
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }
                .mail-order-success {
                    justify-content: center;
                    align-items: center;
                    display: flex;
                    min-height: 100vh;
                }
                .mail-order-success-wrapper {
                    max-width: 600px;
                    border-radius: 6px;
                    padding: 20px 30px;
                    min-height: 70vh;
                    box-shadow: 0 0 4px #ccc;
                }
                .mail-order-success-header {
                    background-color: #f8f8f8;
                    padding: 16px;
                    display: flex;
                    align-items: center;
                }
                img {
                    max-width: 100px;
                    max-height: 100px;
                }
                .mail-order-success-header-img {
                    display: flex;
                    flex: 1;
                    margin-right: 103px;
                }
                .mail-order-success-header-title {
                    font-size: 24px;
                    font-weight: 600;
                    color: #0080ff;
                }
                .mail-order-success-content-hello {
                    padding-bottom: 16px;
                    display: flex;
                }
                .mail-order-success-icon{
                    font-size: 4rem;
                    color:#0080ff;
                }
                button {
                    outline: none;
                    padding: 8px 12px;
                    border: none;
                    margin-top: 32px;
                    text-align: center;
                    background-color:#FFC200;
                    color: white;
                    border-radius: 8px;
                    cursor: pointer;
                }
                a.mail-order-success-signature {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    text-decoration: none;
                }
                .mail-order-success-content-title-one {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    font-size: 2rem;
                    color: #0080ff;
                }
                    </style>
                </head>
                <body>
                    <div class="mail-order-success">
                    <div class="mail-order-success-wrapper">
                        <div class="mail-order-success-header">
                        <div class="mail-order-success-header-img">
                            <img
                            src="https://i.imgur.com/A4ELGI1.png"
                            alt=""
                            />
                        </div>
                        <div class="mail-order-success-header-title">Tài khoản mới</div>
                        </div>
                        <div class="mail-order-success-content" style="padding: 32px 0;">
                        <div class="mail-order-success-content-hello">
                            <p class="mail-order-success-content-title">Xin Chào</p>
                            <span style="padding: 0 4px;">$fullName</span>
                        </div>
                        <div class="mail-order-success-content-title-one">
                        <p> Bạn đã tạo tài khoản mới trên QuinShop </p>
                        </div>
                        <div class="mail-order-success-icon" style="text-align: center; padding: 24px 0 0 0;">
                            <i class="fa-solid fa-cart-shopping"></i>
                        </div>
                        <div class="mail-order-success-content-title">
                            <p style="text-align: center; color: rgb(62 62 62); padding-top: 8px;" >Vui lòng nhấn để tiếp tục quá trình mua sắm </p>
                        </div>
                        <a class="mail-order-success-signature" href="$url_redirect">
                            <button>Mua sắm thôi nào!</button>
                        </a>
                        </div>
                        <hr>
                        <div class="mail-order-success-footer" style="color: #959494; padding-top: 16px;">
                            <p>Email này không thể nhận thư trả lời. Để biết thêm thông tin, hãy liên hệ với chúng tôi qua hotline: <span style="color: #0080ff;">0999999999</span></p>
                        </div>
                    </div>
                    </div>
                </body>
                </html>
        EOT;
        $this->send_mail($fullName, $email, $subject, $content);
        // $this->send_mail($fullName, $email, 'mailRegister');
        // MAIL VINH CAO

        Session::set('isLogin', true);
        Session::set('id', $id);
        Session::set('full_name', $fullName);
        Session::set('email', $email);
        Session::set('avatar', $avatar);
        Session::set('role', 'member');
        Session::set('phone', $phone);
        return ["status" => true, "message" => "Đăng kí thành công!", "result" => [], "redirect" => $redirect];
    }
    // login with app

    public function login_with_app($email, $full_name = '', $avatar = '', $redirect = '')
    {
        // kiem tra email co ton tai trong he thong k
        $check_email = $this->db->select("SELECT * FROM user WHERE email = '$email'")->fetchAll();
        if (count($check_email) > 0) {
            // da ton tai email trong db thi tien hanh dang nhap
            return self::login_admin($email, '', $redirect, 'withapp');
        } else {
            // chua ton tai thi tien hanh dang ki
            return self::register_admin($full_name, $email, '', '', '', $avatar, 'withapp', $redirect);
        }
    }

    // login with app
    public function updateProfile($fullName, $image, $phone, $address)
    {
        $isLogin = Session::get("isLogin");
        if ($isLogin != true) {
            return new Response(false, "false", "", "?mod=profile&act=login");
        }
        $userId = Session::get("id");
        $checkUser = $this->db->select("SELECT * FROM user WHERE id = '$userId'");
        if ($checkUser == false) {
            return new Response(false, "Thất bại!", "", "?mod=profile&act=login");
        }
        if (empty($fullName) || empty($address) || empty($phone)) {
            return new Response(false, "Không được để trống thông tin!", "", "");
        }
        // update user
        $queryUpdate = "";
        $fileResult = $this->tool->uploadFile($image, 'profile/');
        if ($fileResult) {
            $queryUpdate .= "u.avatar = '$fileResult',";
        }
        $queryUpdate .= "u.full_name = '$fullName',";
        $queryUpdate .= "u.phone_number = '$phone',";
        $queryUpdate .= "u.address = '$address',";
        $queryUpdate .= "u.updated_at = CURRENT_TIMESTAMP";
        $updateUser = $this->db->update("UPDATE user u
            SET $queryUpdate
            WHERE u.id = '$userId'
        ");
        if ($updateUser == false) {
            return new Response(false, "Cập nhật thông tin tài khoản thất bại", "", "", "");
        }
        Session::set('full_name', $fullName);
        Session::set('phone_number', $phone);
        Session::set('address', $address);
        if ($fileResult) {
            Session::set('avatar', $fileResult);
        }
        return new Response(true, "Cập nhật thông tin thành công", "", "", "");
    }
    public function sendCodePassEmail($email)
    {

        if (empty($email)) {
            return new Response(false, "Missing parammeter: Email", "", "?mod=profile&act=forgotpassword");
        }
        $resultUser = $this->db->select("SELECT * FROM user WHERE email =  '$email'");
        $checkEmail = $resultUser->fetchAll();


        if (empty($checkEmail)) {
            return new Response(false, "Email không tồn tại trong hệ thống", "", "?mod=profile&act=forgotpassword");
        }
        if (strtotime($checkEmail[0]['date_expired']) > time()) {
            $duration_min = round((strtotime($checkEmail[0]['date_expired']) - time())) / 60 % 60;
            $duration_sec = round((strtotime($checkEmail[0]['date_expired']) - time()) % 60);
            return new Response(false, "Vui lòng thử lại sau $duration_min phút $duration_sec giây. ", "", "?mod=profile&act=forgotpassword");
        }
        $checkEmail = $checkEmail[0];
        $id = $checkEmail['id'];
        // $cod 
        $idEncode = bin2hex(random_bytes(32));


        // insert database
        // $this->db->update("SET GLOBAL time_zone = '+7:00';");
        // $this->db->update("SET SQL_SAFE_UPDATES = 0;");
        $this->db->update("
        UPDATE user 
        set date_expired = DATE_ADD(current_timestamp(), INTERVAL 5 MINUTE) , code_verify = '$idEncode' 
        WHERE id = '$id'
        ");

        $url = BASE_URL . "?mod=profile&act=forgotpassword&token=$idEncode";
        $user_name = $checkEmail['full_name'];
        $user_email = $checkEmail['email'];
        $user_token_expired = $this->db->select("SELECT date_expired from user where email = '$user_email'")->fetchColumn();
        $subject = "Thay đổi mật khẩu cho tài khoản $user_email tại QUINSHOP";
        // $subject 
        $content = <<<EOT
                <!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="UTF-8" />
                    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                    <title>Mail - shop</title>
                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
                    integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
                    crossorigin="anonymous" referrerpolicy="no-referrer" />
                    <style>
                        *{
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                    }
                    .mail-order-success {
                        justify-content: center;
                        align-items: center;
                        display: flex;
                        min-height: 100vh;
                    }
                    .mail-order-success-wrapper {
                        max-width: 600px;
                        border-radius: 6px;
                        padding: 20px 30px;
                        min-height: 70vh;
                        box-shadow: 0 0 4px #ccc;
                    }
                    .mail-order-success-header {
                        background-color: #f8f8f8;
                        padding: 16px;
                        display: flex;
                        align-items: center;
                    }
                    img {
                        max-width: 100px;
                        max-height: 100px;
                    }
                    .mail-order-success-header-img {
                        display: flex;
                        flex: 1;
                        margin-right: 103px;
                    }
                    .mail-order-success-header-title {
                        font-size: 24px;
                        font-weight: 600;
                        color: #0080ff;
                    }
                    .mail-order-success-content-hello {
                        padding-bottom: 16px;
                        display: flex;
                    }
                    .mail-order-success-icon{
                        font-size: 4rem;
                        color:#0080ff;
                    }
                    button {
                        outline: none;
                        padding: 8px 12px;
                        border: none;
                        margin-top: 32px;
                        text-align: center;
                        background-color:#FFC200;
                        color: white;
                        border-radius: 8px;
                        cursor: pointer;
                    }
                    button:hover{
                        cursor: pointer;
                        background-color: #FBB117;
                    }
                    a.mail-order-success-signature {
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        cursor: pointer;
                        text-decoration: none;
                    }
                    a.mail-order-success-signature:hover {
                        cursor: pointer;
                    }

                    .mail-order-success-content-title-one {
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        font-size: 2rem;
                        color: #0080ff;
                    }
                        </style>
                    </head>
                    <body>
                        <div class="mail-order-success">
                        <div class="mail-order-success-wrapper">
                            <div class="mail-order-success-header">
                            <div class="mail-order-success-header-img">
                                <img
                                src="https://i.imgur.com/A4ELGI1.png"
                                alt=""
                                />
                            </div>
                            <div class="mail-order-success-header-title">Đổi mật khẩu</div>
                            </div>
                            <div class="mail-order-success-content" style="padding: 32px 0;">
                            <div class="mail-order-success-content-hello">
                                <p class="mail-order-success-content-title">Xin Chào</p>
                                <span style="padding: 0 4px;">$user_name</span>
                            </div>
                            
                            <div class="mail-order-success-icon" style="text-align: center; padding: 24px 0 0 0;">
                                <i class="fa-solid fa-key"></i>
                            </div>
                            <div class="mail-order-success-content-title">
                                <p style="text-align: center; color: rgb(62 62 62); padding-top: 8px;" >Đường dẫn chỉ có hiệu lực đến $user_token_expired.</p>
                                <p style="text-align: center; color: rgb(62 62 62); padding-top: 8px;" >Vui lòng nhấn vào nút bên dưới để thực hiện.</p>
                            </div>
                            <a class="mail-order-success-signature" href="$url">
                                <button>Đổi mật khẩu</button>
                            </a>
                            </div>
                            <hr>
                            <div class="mail-order-success-footer" style="color: #959494; padding-top: 16px;">
                                <p>Email này không thể nhận thư trả lời. Để biết thêm thông tin, hãy liên hệ với chúng tôi qua hotline: <span style="color: #0080ff;">0358723520</span></p>
                            </div>
                        </div>
                        </div>
                    </body>
                    </html>
        EOT;

        $this->send_mail($user_name, $user_email, $subject, $content);
        return new Response(true, "Send email successfully", "", "?mod=profile&act=forgotpassword&verifytoken=" . $idEncode);
    }
    function checkToken($token)
    {

        $checkUser = $this->db->select("SELECT * FROM user WHERE code_verify = '$token'")->fetchAll();
        if (count($checkUser) == 0) {
            return new Response(false, "Đường dẫn không hợp lệ!", "", "?mod=profile&act=forgotpassword");
        } else {
            if ((strtotime($checkUser[0]['date_expired'])) > time()) {
                return new Response(true, "success", $token, "");
            } else {
                return new Response(false, "Đường dẫn đã hết hạn! Vui lòng thử lại sau.", "", "?mod=profile&act=forgotpassword");
            }
        }
    }
    function checkCode($code, $token)
    {
        $checkTokenVerify = self::checkToken($token);
        if ($checkTokenVerify->status == false) {
            return $checkTokenVerify;
        }
        $userId = $checkTokenVerify->result;
        $userInfoSelect = $this->db->select("SELECT * FROM user where id = '$userId' ");
        $userInfo = $userInfoSelect->fetchAll();
        if (empty($userInfo)) {
            return new Response(false, "Người dùng không tồn tại", "", "");
        }

        $userInfo = $userInfo[0];
        // print_r($userInfo);
        // return;
        if ($userInfo['code_verify'] != $code) {
            return new Response(false, "Mã xác nhận không hợp lệ!", "", "");
        }
        $date_expired = $userInfo['date_expired'];
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $timeNow = date('Y-m-d h:i:s');
        // if($timeNow > strtotime($date_expired)){
        //     return new Response(false, "Mã xác thực đã hết hạn. Vui lòng thử lại!",  "", "");
        // }
        return new Response(true, "success", "", "");
    }
    public function changePassword($password, $token)
    {
        // $checkTokenVerify = self::checkToken($token);
        $checkTokenVerify = $this->db->select("select * from user where code_verify = '$token'")->fetchAll();
        if (count($checkTokenVerify) == 0) {
            return new Response(false, "Đường dẫn không hợp lệ. Vui lòng thử lại", "", "");
        }
        // return $checkTokenVerify;
        // change password
        $changePass = $this->db->update("UPDATE user set password = '$password'
        WHERE code_verify = '$token'
        ");

        return new Response(true, "success", "", "");
    }
    public function check_permission()
    {
        if (!(Session::get('isLogin'))) {
            header('location: ?mod=profile&act=login&redirect=admin');
        }
        $role = Session::get('role');
        if (!in_array($role, ['Admin', 'AdminAll'])) {
            header("Location: ?mod=profile&act=login&redirect=admin");
        }
    }
    // VINH CAO

    public function send_mail($receiver_name = '', $receiver_address = '', $subject = '', $content = '')
    {
        // Set the required parameters for making an SMTP connection

        $default_mail = "vinhcao.quingroup@gmail.com";
        $default_mail_password = "djnr okmm abhy eigf";
        $default_mail_name = "QUINGROUP";

        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->SMTPAuth = TRUE;
        $mail->Mailer = "smtp";
        $mail->SMTPSecure = "tls";
        $mail->Port = 587;
        $mail->SMTPDebug = false;
        $mail->Host = "smtp.gmail.com";
        $mail->CharSet = 'UTF-8';
        $mail->Username = $default_mail;
        $mail->Password = $default_mail_password;
        $mail->Subject = $subject;
        // Set the required parameters for email header and body

        $mail->isHTML(true);
        $mail->setFrom($mail->Username, $default_mail_name);
        $mail->addAddress($receiver_address, $receiver_name);
        $mail->addAddress($receiver_address, $receiver_name);

        $mail->msgHTML($content);


        if (!$mail->send()) {
            // echo "<h3>Có lỗi xảy ra</h3>";
            // echo "<pre>";
            // echo $mail->ErrorInfo;

            return new Response(false, "Có lỗi xảy ra khi gửi mail!", "", "");
        } else {
            // echo "<h3>Gửi mail thành công</h3>";
            return new Response(true, "Gửi mail thành công!", "", "");
        }
        ;
    }
    public function subject_content_mail($receiver_name = '', $receiver_address = '', $mail_type = '')
    {
        /*
        switch (mb_strtolower($mail_type)) {
            case 'mailshop':
                # code...
                return <<<EOT
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8" />
                <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                <title>Mail - shop</title>
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
                integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
                crossorigin="anonymous" referrerpolicy="no-referrer" />
                <!-- <link rel="stylesheet" href="style.css"> -->
                <style>
                    *{
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
            .mail-order-success {
                justify-content: center;
                align-items: center;
                display: flex;
                min-height: 100vh;
            }
            .mail-order-success-wrapper {
                max-width: 600px;
                border-radius: 6px;
                padding: 20px 30px;
                min-height: 70vh;
                box-shadow: 0 0 4px #ccc;
            }
            .mail-order-success-header {
                background-color: #f8f8f8;
                padding: 16px;
                display: flex;
                align-items: center;
            }
            img {
                max-width: 100px;
                max-height: 100px;
            }
            .mail-order-success-header-img {
                display: flex;
                flex: 1;
                margin-right: 103px;
            }
            .mail-order-success-header-title {
                font-size: 24px;
                font-weight: 600;
                color: #0080ff;
            }
            .mail-order-success-content-hello {
                padding-bottom: 16px;
                display: flex;
            }
            .mail-order-success-icon{
                font-size: 4rem;
                color:#0080ff;
            }
            button {
                outline: none;
                padding: 8px 12px;
                border: none;
                margin-top: 32px;
                text-align: center;
                background-color:#FFC200;
                color: white;
                border-radius: 8px;
                cursor: pointer;
            }
            a.mail-order-success-signature {
                display: flex;
                justify-content: center;
                align-items: center;
            }
            .mail-order-success-content-title-one {
                display: flex;
                justify-content: center;
                align-items: center;
                font-size: 2rem;
                color: #0080ff;
            }
                </style>
            </head>
            <body>
                <div class="mail-order-success">
                <div class="mail-order-success-wrapper">
                    <div class="mail-order-success-header">
                    <div class="mail-order-success-header-img">
                        <img
                        src="https://i.imgur.com/A4ELGI1.png"
                        alt=""
                        />
                    </div>
                    <div class="mail-order-success-header-title">Đơn hàng mới</div>
                    </div>
                    <div class="mail-order-success-content" style="padding: 32px 0;">
                    <div class="mail-order-success-content-hello">
                        <p class="mail-order-success-content-title">Xin Chào</p>
                        <span style="padding: 0 4px;">$receiver_name</span>
                    </div>
                    <div class="mail-order-success-content-title-one">
                    <p> Bạn có đơn hàng mới trên QuinShop </p>
                    </div>
                    <div class="mail-order-success-icon" style="text-align: center; padding: 24px 0 0 0;">
                        <i class="fa-solid fa-cart-shopping"></i>
                    </div>
                    <div class="mail-order-success-content-title">
                        <p style="text-align: center; color: rgb(62 62 62); padding-top: 8px;" >Vui lòng nhấn để xem chi tiết đơn hàng</p>
                    </div>
                    <a class="mail-order-success-signature">
                        <button>Xem chi tiết đơn hàng</button>
                    </a>
                    </div>
                    <hr>
                    <div class="mail-order-success-footer" style="color: #959494; padding-top: 16px;">
                        <p>Email này không thể nhận thư trả lời. Để biết thêm thông tin, hãy liên hệ với chúng tôi qua hotline: <span style="color: #0080ff;">0999999999</span></p>
                    </div>
                </div>
                </div>
            </body>
            </html>
            EOT;

            case 'mailresetps':
                # code...
                return <<<EOT
                    <!DOCTYPE html>
                    <html lang="en">
                    <head>
                        <meta charset="UTF-8" />
                        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                        <title>Mail-Password</title>
                        <!-- <link rel="stylesheet" href="style.css"> -->
                        <style>
                            *{
                        margin: 0;
                        padding: 0;
                        box-sizing: border-box;
                    }
                    .mail-password {
                        justify-content: center;
                        align-items: center;
                        display: flex;
                        min-height: 100vh;
                    }
                    .mail-password-wrapper {
                        max-width: 600px;
                        border-radius: 6px;
                        padding: 20px 30px;
                        min-height: 70vh;
                        box-shadow: 0 0 4px #ccc;
                    }
                    .mail-password-header {
                        background-color: #f8f8f8;
                        padding: 16px;
                        display: flex;
                        align-items: center;
                    }
                    img {
                        max-width: 100px;
                        max-height: 100px;
                    }
                    .mail-password-header-img {
                        display: flex;
                        flex: 1;
                        margin-right: 103px;
                    }
                    .mail-password-header-title {
                        font-size: 24px;
                        font-weight: 600;
                        color: #0080ff;
                    }
                    .mail-password-content-hello {
                        padding-bottom: 16px;
                        display: flex;
                    }
                        </style>
                    </head>
                    <body>
                        <div class="mail-password">
                        <div class="mail-password-wrapper">
                            <div class="mail-password-header">
                            <div class="mail-password-header-img">
                                <img
                                src="https://i.imgur.com/A4ELGI1.png"
                                alt=""
                                />
                            </div>
                            <div class="mail-password-header-title">Mã xác minh QuinShop</div>
                            </div>
                            <div class="mail-password-content" style="padding: 32px 0;">
                            <div class="mail-password-content-hello">
                                <p class="mail-password-content-title">Xin Chào</p>
                                <span style="padding: 0 4px;">$receiver_name</span>
                            </div>
                            <div class="mail-password-content-title-one">
                            <p> Mã xác minh bạn cần dùng để truy cập vào Tài khoản QuinShop của mình </p>
                                <span style="padding: 0 4px; color: #0080ff;" >($receiver_address)</span>
                            </div>
                            <h1 class="mail-password-code" style="text-align: center; padding: 24px 0;">170323</h1>
                            <div class="mail-password-content-title">
                                <p>Nếu bạn không yêu cầu mã này thì có thể là ai đó đang tìm cách truy cập vào Tài khoản QuinShop
                                <span style="padding: 0 4px; color: #0080ff;">($receiver_address)</span>
                                <b>Không chuyển tiếp hoặc cung cấp mã này cho bất kỳ ai.</b> </p>
                            </div>
                            <div class="mail-password-signature" style="padding-top: 24px;">
                                <p>Trân trọng!</p>
                                <p>Nhóm tài khoản QuinShop</p>
                            </div>
                            </div>
                            <hr>
                            <div class="mail-password-footer" style="color: #959494; padding-top: 16px;">
                                <p>Email này không thể nhận thư trả lời. Để biết thêm thông tin, hãy liên hệ với chúng tôi qua hotline: <span style="color: #0080ff;">0999999999</span></p>
                            </div>
                        </div>
                        </div>
                    </body>
                    </html>

                    EOT;

            case 'mailorder':
                # code...
                return <<<EOT
                    <!DOCTYPE html>
                    <html lang="en">
                    <head>
                        <meta charset="UTF-8" />
                        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                        <title>Mail - order - success</title>
                        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
                        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
                        crossorigin="anonymous" referrerpolicy="no-referrer" />
                        <!-- <link rel="stylesheet" href="style.css"> -->
                        <style>
                            *{
                        margin: 0;
                        padding: 0;
                        box-sizing: border-box;
                        
                    }
                    .mail-order-success {
                        justify-content: center;
                        align-items: center;
                        display: flex;
                        min-height: 100vh;
                    }
                    .mail-order-success-wrapper {
                        max-width: 600px;
                        border-radius: 6px;
                        padding: 20px 30px;
                        min-height: 70vh;
                        box-shadow: 0 0 4px #ccc;
                    }
                    .mail-order-success-header {
                        background-color: #f8f8f8;
                        padding: 16px;
                        display: flex;
                        align-items: center;
                    }
                    img {
                        max-width: 100px;
                        max-height: 100px;
                    }
                    .mail-order-success-header-img {
                        display: flex;
                        flex: 1;
                        margin-right: 103px;
                    }
                    .mail-order-success-header-title {
                        font-size: 24px;
                        font-weight: 600;
                        color: #0080ff;
                    }
                    .mail-order-success-content-hello {
                        padding-bottom: 16px;
                        display: flex;
                    }
                    .mail-order-success-icon{
                        font-size: 4rem;
                        color: rgb(0, 175, 0);
                    }
                    button {
                        outline: none;
                        padding: 8px 12px;
                        border: none;
                        margin-top: 24px;
                        text-align: center;
                        background-color: #0080ff;
                        color: white;
                        border-radius: 8px;
                        cursor: pointer;
                    }
                    a.mail-order-success-signature {
                        display: flex;
                        justify-content: center;
                        align-items: center;
                    }
                        </style>
                    </head>
                    <body>
                        <div class="mail-order-success">
                        <div class="mail-order-success-wrapper">
                            <div class="mail-order-success-header">
                            <div class="mail-order-success-header-img">
                                <img
                                src="https://i.imgur.com/A4ELGI1.png"
                                alt=""
                                />
                            </div>
                            <div class="mail-order-success-header-title">Xác nhận đơn hàng</div>
                            </div>
                            <div class="mail-order-success-content" style="padding: 32px 0;">
                            <div class="mail-order-success-content-hello">
                                <p class="mail-order-success-content-title">Xin Chào</p>
                                <span style="padding: 0 4px;">$receiver_name</span>
                            </div>
                            <div class="mail-order-success-content-title-one">
                            <p> Đơn hàng của bạn đặt tại QuinShop đã thành công </p>
                            </div>
                            <div class="mail-order-success-icon" style="text-align: center; padding: 24px 0 0 0;">
                                <i class="fa-solid fa-check"></i>
                            </div>
                            <div class="mail-order-success-content-title">
                                <p style="text-align: center; color: rgb(0, 175, 0);" >Chúng tôi sẽ sớm giao đơn hàng đến bạn trong 3 - 5 ngày</p>
                            </div>
                            <a class="mail-order-success-signature">
                                <button>Theo dõi đơn hàng</button>
                            </a>
                            </div>
                            <hr>
                            <div class="mail-order-success-footer" style="color: #959494; padding-top: 16px;">
                                <p>Email này không thể nhận thư trả lời. Để biết thêm thông tin, hãy liên hệ với chúng tôi qua hotline: <span style="color: #0080ff;">0999999999</span></p>
                            </div>
                        </div>
                        </div>
                    </body>
                    </html>

                    EOT;
            case 'mailregister':
                #code... 
                return <<<EOT
                <!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="UTF-8" />
                    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                    <title>Mail - shop</title>
                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
                    integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
                    crossorigin="anonymous" referrerpolicy="no-referrer" />
                    <style>
                        *{
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }
                .mail-order-success {
                    justify-content: center;
                    align-items: center;
                    display: flex;
                    min-height: 100vh;
                }
                .mail-order-success-wrapper {
                    max-width: 600px;
                    border-radius: 6px;
                    padding: 20px 30px;
                    min-height: 70vh;
                    box-shadow: 0 0 4px #ccc;
                }
                .mail-order-success-header {
                    background-color: #f8f8f8;
                    padding: 16px;
                    display: flex;
                    align-items: center;
                }
                img {
                    max-width: 100px;
                    max-height: 100px;
                }
                .mail-order-success-header-img {
                    display: flex;
                    flex: 1;
                    margin-right: 103px;
                }
                .mail-order-success-header-title {
                    font-size: 24px;
                    font-weight: 600;
                    color: #0080ff;
                }
                .mail-order-success-content-hello {
                    padding-bottom: 16px;
                    display: flex;
                }
                .mail-order-success-icon{
                    font-size: 4rem;
                    color:#0080ff;
                }
                button {
                    outline: none;
                    padding: 8px 12px;
                    border: none;
                    margin-top: 32px;
                    text-align: center;
                    background-color:#FFC200;
                    color: white;
                    border-radius: 8px;
                    cursor: pointer;
                }
                a.mail-order-success-signature {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                }
                .mail-order-success-content-title-one {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    font-size: 2rem;
                    color: #0080ff;
                }
                    </style>
                </head>
                <body>
                    <div class="mail-order-success">
                    <div class="mail-order-success-wrapper">
                        <div class="mail-order-success-header">
                        <div class="mail-order-success-header-img">
                            <img
                            src="https://i.imgur.com/A4ELGI1.png"
                            alt=""
                            />
                        </div>
                        <div class="mail-order-success-header-title">Tài khoản mới</div>
                        </div>
                        <div class="mail-order-success-content" style="padding: 32px 0;">
                        <div class="mail-order-success-content-hello">
                            <p class="mail-order-success-content-title">Xin Chào</p>
                            <span style="padding: 0 4px;">$receiver_name</span>
                        </div>
                        <div class="mail-order-success-content-title-one">
                        <p> Bạn đã tạo tài khoản mới trên QuinShop </p>
                        </div>
                        <div class="mail-order-success-icon" style="text-align: center; padding: 24px 0 0 0;">
                            <i class="fa-solid fa-cart-shopping"></i>
                        </div>
                        <div class="mail-order-success-content-title">
                            <p style="text-align: center; color: rgb(62 62 62); padding-top: 8px;" >Vui lòng nhấn để tiếp tục quá trình mua sắm </p>
                        </div>
                        <a class="mail-order-success-signature">
                            <button>Mua sắm thôi nào!</button>
                        </a>
                        </div>
                        <hr>
                        <div class="mail-order-success-footer" style="color: #959494; padding-top: 16px;">
                            <p>Email này không thể nhận thư trả lời. Để biết thêm thông tin, hãy liên hệ với chúng tôi qua hotline: <span style="color: #0080ff;">0999999999</span></p>
                        </div>
                    </div>
                    </div>
                </body>
                </html>
                EOT;
            default:
                # code...
                echo "Vui lòng cập nhật lại mail type.";
                break;
        }
        */
        /*
        switch ($mail_type) {
            case 'mailShop':
                # code...
                $mail->Subject = 'Shop bạn vừa có đơn hàng mới tại Quin Shop';
                break;
            case 'mailResetPS':
                # code...
                $mail->Subject = 'Bạn đã yêu cầu thay đổi mật khẩu tại Quin Shop';
                break;
            case 'mailOrder':
                # code...
                $mail->Subject = 'Bạn đã đặt hàng thành công tại Quin Shop';
                break;
            case 'mailRegister':
                # code...
                $mail->Subject = 'Bạn đã đăng ký tài khoản mới thành công tại Quin Shop';
                break;
            default:
                echo "Vui lòng cập nhật lại mail type.";
                # code...
                break;
        }
        */
    }

    public function validate_field_contents($email = '', $password = '', $repeat_password = '', $fullName = '')
    {
        /**
         * Email: 
         * Password:
         * Repeat password
         * First name, Last name
         */
        if (empty($fullName)) {
            $alert = "Họ và tên không được để trống!";
            return ["status" => false, "message" => $alert, "result" => []];
        }
        if (empty($email)) {
            $alert = "Email không được để trống!";
            return ["status" => false, "message" => $alert, "result" => []];
        }
        if (empty($password) || empty($confirmPassword)) {
            $alert = "Mật khẩu không được để trống!!";
            return ["status" => false, "message" => $alert, "result" => []];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

            return ["status" => false, "message" => 'Email không đúng định dạng!', "result" => []];
        }
        if ($password != $repeat_password) {
            $alert = "Mật khẩu không khớp!";
            return ["status" => false, "message" => $alert, "result" => []];
        }
        if (strlen($password) < 8) {
            return ["status" => false, "message" => 'Password phải có tổi thiểu 10 kí tự!', "result" => []];
        }
        if (strlen($email) > 10) {
            return ["status" => false, "message" => 'Email phải có tổi thiểu 10 kí tự!', "result" => []];
        }
        if (strlen($email) < 100) {
            return ["status" => false, "message" => 'Email quá dài!', "result" => []];
        }
        $checkUser = $this->db->select("select * from user where email = '$email';");
        if (count($checkUser->fetchAll()) > 0) {
            return ["status" => false, "message" => "Email đã tồn tại! Vui lòng sử dụng email khác.", "result" => []];
        }
    }


    // VINH CAO
    public function check_permistion()
    {
        if (!(Session::get('isLogin'))) {
            header('location: ?mod=profile&act=login&redirect=admin');
        }
        $role = Session::get('role');
        if (!in_array($role, ['Admin', 'AdminAll'])) {
            header("Location: ?mod=profile&act=login&redirect=admin");
        }
    }

    // call phone
    public function call_phone_changepass($phone)
    {

        // update database
        $check_phone = $this->db->select("SELECT * FROM user WHERE phone_number = '$phone'")->fetchAll();
        if (count($check_phone) == 0) {
            return new Response(false, 'Số điện thoại không tồn tại trong hệ thống. Vui lòng nhập lại!');
        }

        if (strtotime($check_phone[0]['date_expired']) > time()) {
            $duration_min = round((strtotime($check_phone[0]['date_expired']) - time())) / 60 % 60;
            $duration_sec = round((strtotime($check_phone[0]['date_expired']) - time()) % 60);
            return new Response(false, "Vui lòng thử lại sau $duration_min phút $duration_sec giây. ", "", "?mod=profile&act=forgotpassword");
        }
        $code = (string)(rand(1000, 9999));

        $this->db->update("
        UPDATE user 
        set date_expired = DATE_ADD(current_timestamp(), INTERVAL 2 MINUTE) , code_verify = '$code' 
        WHERE phone_number = '$phone'
        ");

        // update database

        $url = 'https://api.stringee.com/v1/call2/callout';
        $token = 'eyJjdHkiOiJzdHJpbmdlZS1hcGk7dj0xIiwidHlwIjoiSldUIiwiYWxnIjoiSFMyNTYifQ.eyJqdGkiOiJTSy4wLjRMZ0JVcWZXamhrNGl1STdoTnBWRUJ4T0lEYTRDYWJ6LTE3MTI2Nzg5MjkiLCJpc3MiOiJTSy4wLjRMZ0JVcWZXamhrNGl1STdoTnBWRUJ4T0lEYTRDYWJ6IiwiZXhwIjoxNzE1MjcwOTI5LCJyZXN0X2FwaSI6dHJ1ZX0.jg9LqERdx1JDk_ja5uQ8NJf5aNe8snBS03oemGQj55w'; // Replace with your JWT token
        $data = array(
            'from' => array('type' => 'external', 'number' => '842871057596', 'alias' => 'STRINGEE TESTER'),
            'to' => array(array('type' => 'external', 'number' => "84" . ltrim($phone, '0'), 'alias' => 'Mr Quynh')),
            'actions' => array(array('action' => 'talk', 'text' => 'Mã xác nhận của bạn là '.implode(" ",str_split($code))))
        );
        // implode(" ",str_split($code))
        $payload = json_encode($data);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Authorization: Bearer ' . $token,
                'Content-Type: application/json',
                'Content-Length: ' . strlen($payload)
            )
        );

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response == false) {
            return new Response(false, 'Lỗi: ' . curl_error($ch));
        } else {
            $token_phone = bin2hex($phone);
            return new Response(true, "Vui lòng nhập mã code để xác nhận!", '', '?mod=profile&act=changepass_phone&token=' . $token_phone);
        }
    }
    // call phone
    function check_code_phone($token, $code)
    {
        $phone = hex2bin($token);
        $check_phone = $this->db->select("SELECT * FROM user where phone_number = '$phone'")->fetchAll();
        if (count($check_phone) == 0) {
            return new Response(false, 'Đường dẫn không hợp lệ. Vui lòng thử lại sau!');
        }

        $check_code = $this->db->select("SELECT * FROM user WHERE code_verify = '$code'")->fetchAll();
        if (count($check_code) == 0) {
            return new Response(false, "Mã xác nhận không đúng. Vui lòng thử lại!");
        } else {
            if ((strtotime($check_code[0]['date_expired'])) > time()) {
                return new Response(true, "Thành công", $token, "");
            } else {
                return new Response(false, "Mã xác thực đã hết hạn. Vui lòng thử lại sau!", "", "?mod=profile&act=forgotpassword");
            }
        }

    }
   
    public function changePassword_phone($password, $token)
    {
        // $checkTokenVerify = self::checkToken($token);
        $phone = hex2bin($token);
        $checkTokenVerify = $this->db->select("select * from user where phone_number = '$phone'")->fetchAll();
        if (count($checkTokenVerify) == 0) {
            return new Response(false, "Đường dẫn không hợp lệ. Vui lòng thử lại", "", "");
        }
         $this->db->update("UPDATE user set password = '$password', date_expired = now(), code_verify = 'CHANGE PASSWORD'
        WHERE phone_number = '$phone'
        ");
        return new Response(true, "success", "", "");
    }


}

?>