<?php
// define("DB_HOST","localhost:3300");

define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASS", "123456");
define("BASE_URL", "http://localhost/quin_group_complete/");

// define("BASE_URL", "https://quin.mr-quynh.com/");
// define("DB_HOST", "localhost");
// define("DB_USER", "quingroup");
// define("DB_PASS", "Quingroup123.");

define("DB_NAME", "quingroup");



// vnpay
define("VNP_RETURN_URL", BASE_URL."?mod=page&act=payment_result");
define("VNP_URL", "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html");
define("VNP_HASHSECRET", "YETJQVOMBAKTQRBNBOQVCXFOQGDVJJPA");


// google authen
define('GOOGLE_APP_ID', '808076794515-dba64hag7kqmj4trcuequ126lq84e7cs.apps.googleusercontent.com');
define('GOOGLE_APP_SECRET', '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');
define('GOOGLE_APP_CALLBACK_URL', BASE_URL.'?mod=profile&act=login');
// faceboock login
define("FB_LOGIN_ID", "431990316050680");
define("FB_LOGIN_SECRECT", "8422facbed18f6bfbef71fdc63cbae08");
define("FB_LOGIN_VERSION", "v19.0");

