<?php
include_once 'lib/phpmailer/src/PHPMailer.php';
include_once 'lib/phpmailer/src/SMTP.php';
include_once 'lib/phpmailer/src/Exception.php';
include_once "lib/database.php";
include_once "helpers/tool.php";
include_once "helpers/format.php";
include_once "model/entity.php";
include_once "model/product.php";
include_once "model/shop.php";
include_once "lib/session.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

?>

<?php
class Cart
{
    private $db;
    private $tool;
    private $response;
    private $db_name;

    public function __construct()
    {
        $this->db = new Database();
        $this->tool = new Tool();
        $this->db_name = DB_NAME;
    }
    //======= new =======

    public function get_cart_user_db($user_id)
    {
        $products = $this->db->select("
        SELECT 
            cart.quantity as cart_quantity,cart.is_check,product.*
        FROM cart 
        INNER JOIN product 
        ON product.id = cart.product_id
        WHERE cart.user_id = '$user_id'
        ")->fetchAll();

        $data = [];
        $classShop = new Shop();
        foreach ($products as $key => $value) {
            $data[$value['id']]['product_info'] = $value;
            $data[$value['id']]['quantity'] = $value['cart_quantity'];
            $data[$value['id']]['check'] = $value['is_check'];
            $data[$value['id']]['shop_info'] = $classShop->get_shop_cart_by_product_id($value['id']);
        }
        return $data;
    }
    public function update_cart_user($type, $product_id, $quantity = "")
    {
        $isLogin = Session::get("isLogin");
        // is login
        if ($isLogin == true) {
            // is login============================== 
            $user_id = Session::get("id");
            $carts = $this->db->select("SELECT * from cart  as c WHERE c.user_id = '$user_id' AND c.product_id = '$product_id'")->fetchAll();
            $checkCart = count($carts) > 0 ? true : false;
            switch ($type) {
                case 'minus':
                    if ($checkCart == false) {
                        return new Response(false, "Thêm sản phẩm thất bại!", self::get_cart_user_db($user_id), "");
                    } else {
                        if ($quantity >= $carts[0]['quantity']) {
                            return new Response(false, 'Cập nhật giỏ hàng thất bại', self::get_cart_user_db($user_id));
                        }
                        $this->db->update("UPDATE cart set quantity = quantity - $quantity where user_id  = '$user_id' AND product_id = '$product_id'");
                        return new Response(true, "Cập nhật giỏ hàng thành công!", self::get_cart_user_db($user_id), "");
                    }
                case 'delete':
                    if ($checkCart == false) {
                        return new Response(false, "Xóa sản phẩm thất bại!", self::get_cart_user_db($user_id), "");
                    } else {
                        $this->db->delete("DELETE FROM cart where user_id  = '$user_id' AND product_id = '$product_id'");
                        return new Response(true, "Cập nhật giỏ hàng thành công!", self::get_cart_user_db($user_id), "");
                    }
                case 'check':
                    if ($checkCart == false) {
                        return new Response(false, "Check sản phẩm thất bại!", self::get_cart_user_db($user_id), "");
                    } else {
                        $this->db->delete("UPDATE cart SET is_check = '1' where user_id  = '$user_id' AND product_id = '$product_id'");
                        return new Response(true, "Cập nhật giỏ hàng thành công!", self::get_cart_user_db($user_id), "");
                    }
                case 'uncheck':
                    if ($checkCart == false) {
                        return new Response(false, "Uncheck sản phẩm thất bại!", self::get_cart_user_db($user_id), "");
                    } else {
                        $this->db->delete("UPDATE cart SET is_check = '0' where user_id  = '$user_id' AND product_id = '$product_id'");
                        return new Response(true, "Cập nhật giỏ hàng thành công!", self::get_cart_user_db($user_id), "");
                    }
                case 'plus':
                    $classProduct = new Product();
                    $remaining_product = $classProduct->get_remain_quantity($product_id);
                    if ($quantity > $remaining_product) {
                        return new Response(false, "Số lượng sản phẩm không đủ cho bạn mua",self::get_cart_user_db($user_id));
                    }
                    if ($checkCart == false) {
                        $this->db->insert("INSERT INTO cart (user_id, product_id, quantity) values ('$user_id', '$product_id','$quantity')");
                        return new Response(true, "Đã thêm sản phẩm vào giỏ hàng thành công!", self::get_cart_user_db($user_id), "");
                    } else {
                        $check_quantity =  $carts[0]['quantity'] + $quantity;
                        if($check_quantity > $remaining_product){
                            return new Response(false, "Số lượng sản phẩm không đủ cho bạn mua",self::get_cart_user_db($user_id));
                        }
                        $updateCart = $this->db->update("UPDATE cart set quantity = quantity + $quantity where user_id  = '$user_id' AND product_id = '$product_id'");
                        return new Response(true, "Đã thêm sản phẩm vào giỏ hàng thành công!", self::get_cart_user_db($user_id), "");
                    }
                default:
                    return new Response(false, "Thêm sản phẩm thất bại!", self::get_cart_user_db($user_id), "");
            }

            // is login==============================
        } else {
            // not login
            switch ($type) {
                case 'plus':
                    // unset($_SESSION['CART']);
                    $classProduct = new Product();
                    $classShop = new Shop();
                    $remaining_product = $classProduct->get_remain_quantity($product_id);
                   
                    if ($quantity > $remaining_product) {
                        return new Response(false, "Số lượng sản phẩm không đủ cho bạn mua",$_SESSION['CART']);
                    }
                    if (isset($_SESSION['CART'][$product_id])) {
                        $check_quantity =  $_SESSION['CART'][$product_id]['quantity'] + $quantity;
                        if($check_quantity > $remaining_product){
                            return new Response(false, "Số lượng sản phẩm không đủ cho bạn mua",$_SESSION['CART']);
                        }
                        $_SESSION['CART'][$product_id]['quantity'] += $quantity;
                    } else {
                        $_SESSION['CART'][$product_id]['quantity'] = $quantity;
                    }
                    $_SESSION['CART'][$product_id]['product_info'] = $classProduct->get_product_by_id($product_id);
                    $_SESSION['CART'][$product_id]['shop_info'] = $classShop->get_shop_cart_by_product_id($product_id);

                    $_SESSION['CART'][$product_id]['check'] = true;
                    return new Response(true, "Đã thêm sản phẩm vào giỏ hàng!", $_SESSION['CART']);
                case 'minus':
                    if ($_SESSION['CART'][$product_id]['quantity'] > 1) {
                        $_SESSION['CART'][$product_id]['quantity'] -= $quantity;
                    } else {
                        return new Response(false, "Số lượng không đúng!");
                    }
                    break;
                case "delete":
                    unset($_SESSION['CART'][$product_id]);
                    break;
                case "check":
                    $_SESSION['CART'][$product_id]['check'] = true;
                case "uncheck":
                    $_SESSION['CART'][$product_id]['check'] = false;
                default:
                    return new Response(false, "Something wrong!");
            }
            return new Response(true, "Cập nhật giỏ hàng thành công!", ($_SESSION['CART']));
        }
    }
    public function get_cart_user()
    {
        $isLogin = Session::get('isLogin');
        if ($isLogin == true) {
            $count = 0;
            $total = 0;
            $user_id = Session::get('id');

            $cart = $this->db->select("SELECT cart.*,product.price FROM cart 
            INNER JOIN product
            ON product.id = cart.product_id
            WHERE user_id ='$user_id'
            ")->fetchAll();

            foreach ($cart as $key => $value) {
                $count += $value['quantity'];
                $total += $value['quantity'] * $value['price'];
            }
            return new Response(true, "success", self::get_cart_user_db($user_id), '', ['total' => $total, 'count' => $count]);
        } else {
            //  not login
            $count = 0;
            $total = 0;
            $cart = isset($_SESSION['CART']) ? $_SESSION['CART'] : [];
            if (count($cart) > 0) {
                foreach ($cart as $key => $value) {
                    $count += $value['quantity'];
                    $total += $value['quantity'] * $value['product_info']['price'];
                }
            }
            return new Response(true, "success", $cart, '', ['total' => $total, 'count' => $count]);
        }
    }
    //======= new =======
    public function get_cart_user_buy($shop_uuid)
    {
        if (Session::get('isLogin') == false) {
            return new Response(false, "Vui lòng đăng nhập!");
        }
        $user_id = Session::get('id');
        $shop_id = $this->db->select("SELECT id from shop where uuid = '$shop_uuid'")->fetchColumn();
        if (empty($shop_id))
            return new Response(false, 'Không tồn tại sản phẩm nào trong giỏ hàng');
        $value = $this->db->select("SELECT product.name,product.image_cover,product.price,product.brand,product.origin,product.slug,cart.quantity
            FROM cart
            INNER JOIN product
            ON product.id = cart.product_id
            WHERE cart.user_id = '$user_id'
            AND cart.is_check = 1
            AND product.shop_id = '$shop_id'
        ")->fetchAll();
        $total = $this->db->select("SELECT sum(cart.quantity) as count, sum(product.price*cart.quantity) as total
            FROM cart
            INNER JOIN product
            ON product.id = cart.product_id
            WHERE cart.user_id = '$user_id'
            AND cart.is_check = 1
            AND product.shop_id = '$shop_id'
        ")->fetch();;
        return new Response(true, 'success', $value, "", ['total' => $total['total'], 'count' => $total['count'], 'shop_id' => $shop_id]);
    }
    public function check_out(
        $shop_uuid,
        $order_uuid,
        $sub_total,
        $total,
        $shipping_fee = 0,
        $delivery_address_id,
        $payment_method,
        $voucher_id = "",
        $note = "",
        $payment_status = ''
    ) {
        $isLogin = Session::get("isLogin");
        if ($isLogin != true) {
            return new Response(false, "Vui lòng đăng nhập", "", "");
        }
        $user_id = Session::get("id");
        $shop_id = $this->db->select("SELECT id from shop WHERE uuid = '$shop_uuid'")->fetchColumn();
        // create order
        if (empty($voucher_id)) {
            $this->db->insert("INSERT INTO $this->db_name.order ( uuid, shipping_fee, sub_total, total, note, payment_status, delivery_address_id,  payment_method, user_id, shop_id)
            VALUES ('$order_uuid','$shipping_fee','$sub_total','$total','$note','$payment_status','$delivery_address_id','$payment_method','$user_id','$shop_id')
        ");
        } else {
            $this->db->insert("INSERT INTO $this->db_name.order ( uuid, shipping_fee, sub_total, total, note, payment_status, delivery_address_id, voucher_id, payment_method, user_id, shop_id)
            VALUES ('$order_uuid','$shipping_fee','$sub_total','$total','$note','$payment_status','$delivery_address_id','$voucher_id','$payment_method','$user_id','$shop_id')
        ");
        }

        $order_id = $this->db->get_lastest_id();

        // create order detail
        $this->db->insert("INSERT INTO order_detail (order_id, price, quantity,product_id)
            (SELECT '$order_id', product.price, cart.quantity, product.id
            FROM cart
            INNER JOIN product
            ON cart.product_id = product.id
            WHERE product.shop_id = '$shop_id' 
            AND cart.user_id = '$user_id'
            AND cart.is_check = 1)
        ");

        //  update quantity product
        $this->db->update("UPDATE product 
            INNER JOIN cart
            ON cart.product_id = product.id
            SET product.quantity_sold = product.quantity_sold + cart.quantity
            WHERE product.id in (
                SELECT  product_id 
                FROM cart
                WHERE user_id = '$user_id'
                AND is_check = 1
                ) 
        ");
        // delete cart user
        $this->db->delete("DELETE cart FROM cart
        INNER JOIN product
        ON product.id = cart.product_id
            WHERE cart.user_id = '$user_id'
            AND cart.is_check = 1 AND product.shop_id = '$shop_id'
        ");

        // is voucher create or update
        if (!empty($voucher_id)) {
            $check_user_voucher = $this->db->select("SELECT count(*) FROM user_voucher WHERE user_id = '$user_id' AND voucher_id = '$voucher_id'")->fetchColumn() ?? 0;
            if ($check_user_voucher > 0) {
                $this->db->update("UPDATE user_voucher SET is_used = 1, use_at = CURRENT_TIMESTAMP() ,updated_at = CURRENT_TIMESTAMP() 
                    WHERE user_id ='$user_id' AND voucher_id = '$voucher_id'
                ");
            } else {
                $this->db->insert("INSERT INTO user_voucher (user_id,voucher_id,is_used,use_at)
                    VALUES ('$user_id','$voucher_id',1,CURRENT_TIMESTAMP())
                ");
            }
        }
        // notification
        $this->db->insert("INSERT INTO $this->db_name.notification 
            ($this->db_name.notification.type, $this->db_name.notification.message,
            $this->db_name.notification.data,$this->db_name.notification.user_id,$this->db_name.notification.shop_id)
            VALUES ('NEW_ORDER','Bạn đã đặt đơn hàng thành công! Mã đơn hàng $order_id đang chờ xác nhận.','$order_uuid','$user_id','$shop_id')
        ");
        // var_dump($order_uuid);
        // die();
        // MAIL VINH CAO
        /*
        $shop_uuid,
        $order_uuid,
        $sub_total,
        $total,
        $shipping_fee = 0,
        $delivery_address_id,
        $payment_method,
        $voucher_id = "",
        $note = "",
        $payment_status = ''
        */


        // $mail_data = $this->db->select("SELECT 
        //     Info_1.shop_name, Info_1.user_name, Info_1.user_email, Info_1.order_id, Info_2.shop_email 
        //     from 
        //         (
        //             select 
        //                 u.email shop_email, 
        //                 s.id shop_id,
        //                 u.id user_id 
        //             from 
        //                 shop s 
        //             inner join user u on u.id = s.user_id
        //         ) Info_2 
        //     INNER JOIN 
        //         (
        //             select 
        //                 o.uuid order_id, 
        //                 s.name shop_name, 
        //                 u.full_name user_name, 
        //                 u.email user_email, 
        //                 s.id shop_id, 
        //                 u.id user_id 
        //             from $this->db_name.order o 
        //             inner join shop s on s.id = o.shop_id 
        //             inner join user u on o.user_id = u.id 
        //             where o.id = '$order_id'
        //         ) Info_1
        //     ON Info_1.shop_id = Info_2.shop_id 
        //     AND Info_1.user_id = Info_2.user_id")->fetch();

        $mail_data = $this->db->select("SELECT o.uuid order_uuid, u1.email user_email,u1.full_name as user_name,s.name as shop_name, u2.email shop_email FROM
            $this->db_name.order o
            inner join user u1
            on o.user_id = u1.id
            inner join shop s 
            on s.id = o.shop_id
            inner join user u2
            on u2.id = s.user_id
            WHERE o.id = '$order_id'
        
        ")->fetch();



        $mail_user_name = $mail_data['user_name'];
        $mail_shop_name = $mail_data['shop_name'];
        $mail_user_email = $mail_data['user_email'];
        $mail_shop_email = $mail_data['shop_email'];


        $mail_order_uuid = $mail_data['order_uuid'];
        $url_redirect = BASE_URL;
        $mail_user_subject = "🥳🥳 $mail_user_name đã đặt đơn hàng thành công tại QUIN SHOP với mã đơn #$mail_order_uuid 🥳🥳";
        $mail_shop_subject = "🤑🤑 Shop $mail_shop_name có đơn hàng mới cần xác nhận tại QUIN SHOP `🤑🤑";

        $mail_user_content = <<<EOT
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
                    <span style="padding: 0 4px;">$mail_user_name</span>
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
                <a class="mail-order-success-signature" href="$url_redirect/?mod=profile&act=order_detail&order=$mail_order_uuid">
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

        $mail_shop_content = <<<EOT
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
                <div class="mail-order-success-header-title">Đơn hàng mới</div>
                </div>
                <div class="mail-order-success-content" style="padding: 32px 0;">
                <div class="mail-order-success-content-hello">
                    <p class="mail-order-success-content-title">Xin Chào</p>
                    <span style="padding: 0 4px;">$mail_shop_name</span>
                </div>
                <div class="mail-order-success-content-title-one">
                <p> Bạn có đơn hàng mới trên QuinShop với mã đơn $mail_order_uuid </p>
                </div>
                <div class="mail-order-success-icon" style="text-align: center; padding: 24px 0 0 0;">
                    <i class="fa-solid fa-cart-shopping"></i>
                </div>
                <div class="mail-order-success-content-title">
                    <p style="text-align: center; color: rgb(62 62 62); padding-top: 8px;" >Vui lòng nhấn nút bên dưới để xem chi tiết đơn hàng</p>
                </div>
                <a class="mail-order-success-signature" href="$url_redirect/?mod=seller&act=detailorder&uuid=$mail_order_uuid">
                    <button>Xem chi tiết đơn hàng</button>
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


        // Send mail to user
        $this->send_mail($mail_user_name, $mail_user_email, $mail_user_subject, $mail_user_content);
        // Send mail to shop
        $this->send_mail($mail_shop_name, $mail_shop_email, $mail_shop_subject, $mail_shop_content);
        // $this->send_mail($fullName, $email, 'mailRegister');


        // MAIL VINH CAO





        return new Response(true, 'Đặt hàng thành công!', ['order_uuid' => $order_uuid, 'order_id' => $order_id]);
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
        $mail->SMTPDebug = FALSE;
        $mail->Host = "smtp.gmail.com";
        $mail->CharSet = 'UTF-8';
        $mail->Username = $default_mail;
        $mail->Password = $default_mail_password;
        $mail->Subject = $subject;
        // Set the required parameters for email header and body

        $mail->isHTML(true);
        $mail->setFrom($mail->Username, $default_mail_name);
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
        };
    }
}



?>