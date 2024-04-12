<?php
include_once "lib/database.php";
include_once "helpers/tool.php";
include_once "model/entity.php";
include_once "model/cart.php";
include_once "model/transaction.php";
include_once "lib/session.php";
?>
<?php
class Payment
{
    private $db;
    private $tool;
    private $response;

    public function __construct()
    {
        $this->db = new Database();
        $this->tool = new Tool();
    }
    public function payment_vnp($data)
    {
        $vnp_Url = VNP_URL;
        $vnp_Returnurl = $data['vnp_returnurl'];
        $vnp_TmnCode = "RIIFM9FX";
        $vnp_HashSecret = VNP_HASHSECRET;
        $startTime = date("YmdHis");
        $expire = date('YmdHis', strtotime('+15 minutes', strtotime($startTime)));

        $vnp_TxnRef = $data['vnp_TxnRef'];
        $vnp_Amount = $data['amount'];
        $vnp_Locale = 'vn';
        $vnp_BankCode = $data['bank_code'];
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
        $order_uuid = $data['order_uuid'];

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount * 100,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => "Thanh toan GD:" . $vnp_TxnRef . '-' . $order_uuid,
            "vnp_OrderType" => "other",
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
            "vnp_ExpireDate" => $expire
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }
        // return $inputData;
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);//  
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        $returnData = array(
            'code' => '00'
            ,
            'message' => 'success'
            ,
            'data' => $vnp_Url
        );
        return new Response(true, 'sucess', $vnp_Url);

    }
    // check result payment vnp
    public function result_payment_vnpay($inputData, $vnp_SecureHash, $data)
    {
        $vnp_HashSecret = VNP_HASHSECRET;
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
        if ($secureHash == $vnp_SecureHash) {
            if ($_GET['vnp_ResponseCode'] == '00') {
                $classCart = new Cart();
                $shipping_fee = isset($data['shipping_fee']) ? $data['shipping_fee'] : 0;
                $result_checkout = $classCart->check_out(
                    $data['shop_uuid'],
                    $data['order_uuid'],
                    $data['sub_total'],
                    $data['total'],
                    $shipping_fee,
                    $data['delivery_address_id'],
                    'Banking',
                    $data['voucher_id'] ?? "",
                    '',
                    '1'
                );
                if ($result_checkout->status) {
                    $classTransaction = new Transaction();
                    $order_id = $result_checkout->result['order_id'];
                    $classTransaction->create_transaction($inputData['vnp_TransactionNo'], $order_id, $inputData['vnp_Amount'] / 100, $inputData['vnp_BankCode'], $inputData['vnp_OrderInfo']);
                }
                return new Response(true, 'Đặt hàng thành công!', ['order_uuid' => $data['order_uuid']]);
            } else {
                return new Response(false, "Giao dịch không thành công!");
            }
        } else {
            return new Response(false, "Giao dịch không thành công! Chữ kí không hợp lệ.");
        }
    }


    // with momo
    public function payment_momo($url, $data)
    {

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data)
            )
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        //execute post
        $result = curl_exec($ch);
        //close connection
        curl_close($ch);
        return $result;

    }
    public function result_payment_momo($GET, $data)
    {

        $currentPath = "";
        $currentPath .= isset($GET['shop']) ? '&shop=' . $GET['shop'] : "";
        $currentPath .= isset($GET['voucher']) ? '&voucher=' . $GET['voucher'] : "";
        $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
        $accessKey = 'klm05TvNBzhg7h7j';
        $requestType = "payWithATM";
        $order_uuid = $GET["order_uuid"];
        $redirectUrl = BASE_URL . "?mod=page&act=checkout" . $currentPath .
            "&order_uuid=" . $order_uuid;
        $ipnUrl = BASE_URL . "?mod=page&act=checkout" . $currentPath .
            "&order_uuid=" . $order_uuid;
        $orderId = $GET["orderId"];

        $partnerCode = $GET["partnerCode"];
        $orderInfo = $GET["orderInfo"];
        $amount = $GET["amount"];
        $requestId = $GET["requestId"];
        $extraData = $GET["extraData"];
        $m2signature = $GET["signature"];

        $message = $GET["message"];
        $transId = $GET["transId"];
        $orderType = $GET['orderType'];
        // return $currentPath;

        //Checksum
        // $rawHash = "accessKey=" . $accessKey .
        //     "&amount=" . $amount .
        //     "&extraData=" . $extraData .
        //     "&ipnUrl=" . $ipnUrl .
        //     "&orderId=" . $orderId .
        //     "&orderInfo=" . $orderInfo .
        //     "&partnerCode=" . $partnerCode .
        //     "&redirectUrl=" . $redirectUrl .
        //     "&requestId=" . $requestId .
        //     "&requestType=" . $requestType;

        // $signature = hash_hmac("sha256", $rawHash, $secretKey);

        // if ($m2signature == $signature) {
        //     $result = '<h1 class="alert alert-success"><strong>Payment status: </strong>Success</h1>';

        // } else {
        //     $result = '<h1 class="alert alert-danger">This transaction could be hacked, please check your signature and returned signature</h1>';
        // }


        $classCart = new Cart();
        $shipping_fee = isset($data['shipping_fee']) ? $data['shipping_fee'] : 0;
        $result_checkout = $classCart->check_out(
            $data['shop_uuid'],
            $data['order_uuid'],
            $data['sub_total'],
            $data['total'],
            $shipping_fee,
            $data['delivery_address_id'],
            'Banking',
            $data['voucher_id'] ?? "",
            '',
            '1'
        );
        if ($result_checkout->status) {
            $classTransaction = new Transaction();
            $order_id = $result_checkout->result['order_id'];
            $classTransaction->create_transaction($transId, 
                $order_id, 
                $amount, 
                strtoupper($orderType), 
                $orderInfo.": ".$order_uuid);
        }
        return new Response(true, 'Đặt hàng thành công!', ['order_uuid' => $data['order_uuid']]);



    }


}


?>