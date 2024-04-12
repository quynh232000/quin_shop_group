<?php
include_once "../config/config.php";
include_once "../lib/database.php";
include_once '../lib/phpmailer/src/PHPMailer.php';
include_once '../lib/phpmailer/src/SMTP.php';
include_once '../lib/phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;


$db = new Database();

$list_user = $db->select("SELECT 
u.full_name, u.email, v.code, v.discount_amount, 
v.quantity - (select count(*) as total_voucher_used from user u
inner join user_voucher uv on u.id = uv.user_id
inner join voucher v on v.id = uv.voucher_id
where uv.is_used = 1) as quantity
from 
user u
inner join 
user_voucher uv on u.id = uv.user_id
inner join 
voucher v on v.id = uv.voucher_id
where 
uv.is_used = 0 
AND
timestampdiff(day, current_timestamp(), date_end) = 3
AND
quantity > 0")->fetchAll();

// send mail
function content($full_name,$voucher_id,$discount_amount,$quantity,$url = BASE_URL) {
    $price = number_format($discount_amount, 0, ',', '.');
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
                                <div class="mail-order-success-header-title">Thông báo Voucher </div>
                                </div>
                                <div class="mail-order-success-content" style="padding: 32px 0;">
                                <div class="mail-order-success-content-hello">
                                    <p class="mail-order-success-content-title">Xin Chào</p>
                                    <span style="padding: 0 4px;">$full_name</span>
                                </div>
                                
                                <div class="mail-order-success-icon" style="text-align: center; padding: 24px 0 0 0;">
                                    <i class="fa-solid fa-key"></i>
                                </div>
                                <div class="mail-order-success-content-title">
                                    <p style="text-align: center; color: rgb(62 62 62); padding-top: 8px;" >Voucher <strong>$voucher_id</strong> (Có giá trị $price VND ) của bạn sẽ hết hiệu lực trong 3 ngày tới</p>
                                    <p style="text-align: center; color: rgb(62 62 62); padding-top: 8px;" >Chỉ còn lại $quantity vouchers. Hãy nhanh tau mua sắm ngay nào!</p>
                                </div>
                                <a class="mail-order-success-signature" href="$url">
                                    <button>Mua sắm ngay</button>
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
    
}
if($list_user && count(($list_user))>0){
    $default_mail = "vinhcao.quingroup@gmail.com";
    $default_mail_password = "djnr okmm abhy eigf";
    $default_mail_name = "QUINGROUP";
    
    foreach ($list_user as $key => $value) {
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
        $mail->Subject = 'Thông báo sử dụng voucher tại QuinShop';
        
        $mail->isHTML(true);
        $mail->setFrom($mail->Username, $default_mail_name);
        $mail->addAddress($value['email'], $value['full_name']);
        $mail->msgHTML(content($value['full_name'],$value['code'],$value['discount_amount'],$value['quantity']));
        
        $mail->send();
    }
    echo "Đã gửi mail thành công cho ".count($list_user)." user!";
}else{
    echo 'Không có mail nào để gửi!';
}

