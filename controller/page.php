<?php


include_once 'model/product.php';
include_once 'model/entity.php';
include_once 'model/category.php';
include_once 'model/comment.php';
include_once 'model/user.php';
include_once 'model/shop.php';
include_once 'model/like_product.php';
include_once 'model/product_review.php';
include_once 'helpers/format.php';
include_once "model/cart.php";
include_once "model/address.php";
include_once "model/voucher.php";
include_once "model/order.php";
include_once "model/payment.php";
include_once "helpers/tool.php";
include_once "model/conversation.php";

include_once "model/traffic.php";




$cate = new Category();
$product = new Product();
$classCart = new Cart();
$classComment = new Comment();
$cart_user = $classCart->get_cart_user();
$classProductReview = new ProductReview();
$classAddress = new Address();
$classVoucher = new Voucher();
$classPayment = new Payment();
$classOrder = new Order();
$tool = new Tool();
$traffic = new Traffic();
$traffic->set_traffic();


extract($_REQUEST);
if (isset($_GET['act']) && $_GET['act']) {
    switch ($_GET['act']) {
        case 'home':
            $allCategory = $cate->get_category_home();
            if ($allCategory == false) {
                $allCategory = array();
            }
            $randomCate = $cate->get_cate_random();
            $megaPro = $product->filterProduct("by_type", 'Flash Sale');
            $newPro = $product->filterProduct("by_type", "New", 8);
            $salePro = $product->filterProduct("by_type", 'Hot');
            $bestPro = $product->filterProduct("best_selling", "", 10);

            $suggestionPro = $product->filterProduct("suggestion", '', 10);

            if (count($suggestionPro->result) == 0) {

                $suggestionPro = $product->filterProduct("best_selling", "", 10);
            }
            include_once 'view/inc/header.php';
            include_once 'view/home.php';
            include_once 'view/inc/footer.php';
            break;
        case 'collection':
            $allCategory = $cate->getAllCate();
            // $allCategory = $cate->get_category_home();
            $page = 1;
            $limit =12;
            $category = "";
            $min_price = "";
            $max_price = "";
            $type = "";
            if (isset($_GET['category']) && $_GET['category']) {
                $category = $_GET['category'];
            }
            if (isset($_GET['min_price']) && $_GET['min_price'] && isset($_GET['max_price']) && $_GET['max_price']) {
                $min_price = $_GET['min_price'];
                $max_price = $_GET['max_price'];
            }
            if (isset($_GET['type']) && $_GET['type']) {
                $type = $_GET['type'];
            }

            if (isset($_GET['page']) && $_GET['page']) {
                $page = $_GET['page'];
            }
            $collectionPro = $product->filter_product_collection($category, $min_price, $max_price, $type, $limit, $page);
            if (isset($_GET['category'])) {
                $infoCate = $cate->getInfoCate($_GET['category']);
                if (isset($infoCate))
                    $viewTitle = $infoCate['name'];

            } else {
                $viewTitle = 'Xem tất cả';
            }

            include_once 'view/inc/header.php';
            include_once 'view/collection.php';
            include_once 'view/inc/footer.php';
            break;
        case 'detail':
            $classFormat = new Format();
            $classProduct = new Product();
            $classShop = new Shop();
            $classLike = new LikeProduct();

            if (!(isset($_GET['product']) && $_GET['product'])) {
                header("Location: ?page=404");
            }

            $slug = $_GET['product'];

            $kq_san_pham = $classProduct->get_product_detail($slug);

            if ($kq_san_pham->status == false) {
                header("location: ?page=404");
            }
            $san_pham = $kq_san_pham->result;

            // Xử lý review sản phẩm
            $page = 1;
            $limit = 3;
            if (isset($_GET['page']) && $_GET['page']) {
                $page = $_GET['page'];
            }
            $kq_danhsach_danhgia = $classProductReview->get_review_product_detail($san_pham['product']['id'], $page, $limit);

            if ($kq_danhsach_danhgia->status == true) {
                $danhsach_danhgia = $kq_danhsach_danhgia->result['reviews'];
                $is_review = $kq_danhsach_danhgia->result['allow_review'];
            }
            // submit form review
            if (isset($_POST['reviewsubmit']) && $_POST['reviewsubmit']) {
                $level = $_POST['level'];
                $content = $_POST['content'];
                $id_review = isset($_POST['id_review']) ? $_POST['id_review'] : '';

                $kq_sumit_review = $classProductReview->create_review($level, $content, $san_pham['product']['id'], $id_review);
                if ($kq_sumit_review->status == false) {
                    echo '<div id="toast" mes-type="error" mes-title="Thất bại!" mes-text="' . $kq_sumit_review->message . '."></div>';
                } else {
                    echo '<div id="toast" mes-type="success" mes-title="Thành công!" mes-text="' . $kq_sumit_review->message . '."></div>';
                    echo ' <script>
                            setTimeout(function() {
                                window.location.href="?mod=page&act=detail&product=' . $slug . '";
                            }, 2000);
                        </script>';
                }
            }

            // lấy thông tin shop theo id product
            $kq_thong_tin_shop = $classShop->get_shop_by_id_product($san_pham['product']['id']);
            if ($kq_thong_tin_shop->status == true) {
                $thong_tin_shop = $kq_thong_tin_shop->result;
            }

            // =================================================
            // thêm vào danh sách sản phẩm gơi ý
            if (isset($_SESSION['suggestion_ids']) && $_SESSION['suggestion_ids']) {
                if (!in_array($san_pham['product']['category_id'], $_SESSION['suggestion_ids'])) {
                    $_SESSION['suggestion_ids'][] = $san_pham['product']['category_id'];
                }
            } else {
                $_SESSION['suggestion_ids'][] = $san_pham['product']['category_id'];
            }
            // Lây danh sách sản phẩm gợi ý
            $productSuggestion = $classProduct->filterProduct("suggestion", '', 8);
            if (count($productSuggestion->result) == 0) {
                $productSuggestion = $classProduct->filterProduct("best_selling", "", 8);
            }


            $viewTitle = $san_pham['product']['name'];
            include_once 'view/inc/header.php';
            include_once 'view/detail.php';
            include_once 'view/inc/footer.php';
            break;
        case 'cart':
            $viewTitle = 'Giỏ hàng';
            $data = [];
            foreach ($cart_user->result as $key => $value) {
                $data[$value['shop_info']['id']][] = $value;
            }
            include_once 'view/inc/header.php';
            include_once 'view/cart.php';
            include_once 'view/inc/footer.php';
            break;
        case 'checkout':
            $viewTitle = "Tiến hành đặt hàng";

            echo '<link rel="stylesheet" href="./src/css/base.css">';
            if (Session::get('isLogin') == false) {
                header("Location: ?mod=profile&act=login&redirect=cart");
            } else {
                $currentPath = "";
                $currentPath .= isset($shop) ? '&shop=' . $shop : "";
                $currentPath .= isset($voucher) ? '&voucher=' . $voucher : "";
                $get_address = $classAddress->get_address_user_default();
                if ($get_address->status == false) {
                    header("Location: ?mod=profile&act=address&modal=show&type=create" . $currentPath);
                }
                $address_info = $get_address->result;
                // get list product info
                if (isset($shop) && $shop) {
                    $get_cart_buy = $classCart->get_cart_user_buy($shop);
                    if ($get_cart_buy->status == true) {
                        $products = $get_cart_buy;
                        if (count($products->result) == 0) {
                            header("Location: ?mod=page&act=cart");
                        }
                    }
                    $get_ship = $classAddress->get_ship_by_area($address_info['area'], $shop);
                }
                if (isset($voucher) && $voucher) {
                    $voucher_info = $classVoucher->check_code_voucher($voucher, $products->total['shop_id']);
                }
                // payment
                // init code====================================================================================
                $order_uuid = $tool->GUID();
                $vnp_TxnRef = rand(1, 10000);

                $total_price = $products->total['total'];
                if (isset($voucher_info) && $voucher_info->status) {
                    $total_price -= $voucher_info->result['discount_amount'];
                }
                if (isset($get_ship) && $get_ship > 0) {
                    $total_price += $get_ship;
                }
                if (isset($_POST['submit_payment']) && $_POST['submit_payment']) {
                    $data['vnp_returnurl'] = BASE_URL . "?mod=page&act=checkout" . $currentPath . "&order_uuid=" . $order_uuid;

                    $data['amount'] = $total_price;
                    $data['bank_code'] = $_POST['bank_code'] ?? "NCB";
                    $data['order_uuid'] = $order_uuid;
                    $data['vnp_TxnRef'] = $vnp_TxnRef;

                    $check_payment = ($classPayment->payment_vnp($data));
                    if ($check_payment->status) {
                        header("Location: " . $check_payment->result);
                    }
                }
                // result payment and checkout===================================================================
                if (isset($_GET['vnp_SecureHash']) && $_GET['vnp_SecureHash']) {
                    $vnp_SecureHash = $_GET['vnp_SecureHash'];
                    $inputData = array();
                    foreach ($_GET as $key => $value) {
                        if (substr($key, 0, 4) == "vnp_") {
                            $inputData[$key] = $value;
                        }
                    }
                    $total_price = $products->total['total'];
                    if (isset($voucher_info) && $voucher_info->status) {
                        $total_price -= $voucher_info->result['discount_amount'];
                    }
                    if (isset($get_ship) && $get_ship > 0) {
                        $total_price += $get_ship;
                    }
                    $data = [];
                    $data['order_uuid'] = $_GET['order_uuid'];
                    $data['shop_uuid'] = $shop;
                    $data['total'] = $total_price;
                    $data['sub_total'] = $products->total['total'];
                    $data['delivery_address_id'] = $address_info['id'];

                    if (isset($get_ship) && $get_ship > 0) {
                        $data['shipping_fee'] = $get_ship;
                    }
                    if (isset($voucher_info) && $voucher_info->status) {
                        $data['voucher_id'] = $voucher_info->result['id'];
                    }
                    unset($inputData['vnp_SecureHash']);
                    ksort($inputData);

                    $payment_checkcout = $classPayment->result_payment_vnpay($inputData, $vnp_SecureHash, $data);
                    echo ' <script>
                        setTimeout(function() {
                            window.location.href="?mod=page&act=checkout_success&order_uuid=' . $_GET['order_uuid'] . '";
                        }, 5000);
                    </script>';
                }

                if (isset($_POST['nameReceiver']) && !empty($_POST['nameReceiver'])) {
                    $nameReceiver = $_POST['nameReceiver'];
                    $city = $_POST['city'];
                    $province = $_POST['province'];
                    $addressDetail = $_POST['addressDetail'];
                    $phone = $_POST['phone'];
                    $note = $_POST['note'];
                    $subtotal = $_POST['subTotal'];
                    $total = $_POST['total'];
                    $fee = $_POST['fee'];
                    $valueCheckout = $classCart->checkout($nameReceiver, $city, $province, $addressDetail, $phone, $note, $subtotal, $total, $fee);
                    if (isset($valueCheckout)) {
                        if ($valueCheckout->status == false) {
                            echo '<div id="toast" mes-type="error" mes-title="Thất bại!" mes-text="' . $valueCheckout->message . '."></div>';
                        } else {
                            echo '<div id="toast" mes-type="error" mes-title="Thất bại!" mes-text="' . $payment_checkcout->message . '."></div>';
                        }
                    }
                }
                // checkout COD btn==============================================================================
                if (isset($_POST['btn_submit_checkout']) && $_POST['btn_submit_checkout']) {
                    $shop_uuid = $shop;
                    // $order_uuid = $order_uuid;
                    $sub_total = $products->total['total'];
                    $total = $total_price;
                    $shipping_fee = isset($get_ship) ? $get_ship : 0;
                    $delivery_address_id = $address_info['id'];
                    $payment_method = "COD";
                    $voucher_id = (isset($voucher_info) && $voucher_info->status) ? $voucher_info->result['id'] : "";
                    $note = isset($_POST['note']) ? $_POST['note'] : "";
                    $payment_status = 0;
                    $result_checkout_cod = $classCart->check_out(
                        $shop_uuid,
                        $order_uuid,
                        $sub_total,
                        $total,
                        $shipping_fee,
                        $delivery_address_id,
                        $payment_method,
                        $voucher_id,
                        $note,
                        $payment_status
                    );
                    if ($result_checkout_cod->status) {
                        echo '<div id="modal_waiting">
                                        <div aria-label="Orange and tan hamster running in a metal wheel" role="img" class="wheel-and-hamster">
                                        <div class="wheel"></div>
                                        <div class="hamster">
                                            <div class="hamster__body">
                                                <div class="hamster__head">
                                                    <div class="hamster__ear"></div>
                                                    <div class="hamster__eye"></div>
                                                    <div class="hamster__nose"></div>
                                                </div>
                                                <div class="hamster__limb hamster__limb--fr"></div>
                                                <div class="hamster__limb hamster__limb--fl"></div>
                                                <div class="hamster__limb hamster__limb--br"></div>
                                                <div class="hamster__limb hamster__limb--bl"></div>
                                                <div class="hamster__tail"></div>
                                            </div>
                                        </div>
                                        <div></div>
                                        </div>
                                        <div class="processing">Đang xử lý</div>
                                </div>';
                        echo ' <script>
                                setTimeout(function() {
                                    window.location.href="?mod=page&act=checkout_success&order_uuid=' . $result_checkout_cod->result['order_uuid'] . '";
                                }, 5000);
                            </script>';
                    } else {
                        echo '<div id="toast" mes-type="error" mes-title="Thất bại!" mes-text="' . $result_checkout_cod->message . '."></div>';
                    }
                }
                // checkout with momo============================================================================


                if (isset($_POST['payment_momo']) && $_POST['payment_momo']) {

                    $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
                    $partnerCode = 'MOMOBKUN20180529';
                    $accessKey = 'klm05TvNBzhg7h7j';
                    $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
                    $orderInfo = "Thanh toán MOMO";
                    $amount = $total_price;
                    $orderId = random_int(00, 99999);
                    $redirectUrl = BASE_URL . "?mod=page&act=checkout" . $currentPath . "&order_uuid=" . $order_uuid;
                    $ipnUrl = BASE_URL . "?mod=page&act=checkout" . $currentPath . "&order_uuid=" . $order_uuid;
                    $extraData = "";

    
                    $requestId = time() . "";
                    $requestType = "payWithATM";
                    // $requestType = "captureWallet";
                    //before sign HMAC SHA256 signature
                    $rawHash = "accessKey=" . $accessKey .
                        "&amount=" . $amount .
                        "&extraData=" . $extraData .
                        "&ipnUrl=" . $ipnUrl .
                        "&orderId=" . $orderId .
                        "&orderInfo=" . $orderInfo .
                        "&partnerCode=" . $partnerCode .
                        "&redirectUrl=" . $redirectUrl .
                        "&requestId=" . $requestId .
                        "&requestType=" . $requestType;

                    $signature = hash_hmac("sha256", $rawHash, $secretKey);
                    $data = array(
                        'partnerCode' => $partnerCode,
                        'partnerName' => "Test",
                        "storeId" => "MomoTestStore",
                        'requestId' => $requestId,
                        'amount' => $amount,
                        'orderId' => $orderId,
                        'orderInfo' => $orderInfo,
                        'redirectUrl' => $redirectUrl,
                        'ipnUrl' => $ipnUrl,
                        'lang' => 'vi',
                        'extraData' => $extraData,
                        'requestType' => $requestType,
                        'signature' => $signature
                    );
                    $result = $classPayment->payment_momo($endpoint, json_encode($data));
    
                    // print_r($result);
                    // return;
                    $jsonResult = json_decode($result, true);  // decode json

                    //Just a example, please check more in there
                    if($jsonResult['resultCode'] ==22){
                         echo '<div id="toast" mes-type="error" mes-title="Thất bại!" mes-text="' . $jsonResult['message'] . '."></div>';
                    }else{
                        
                    header('Location: ' . $jsonResult['payUrl']);
                    }
                }
                // get result momo
                if (isset($_GET['resultCode']) && $_GET['resultCode'] == 0) {

                    $data = [];
                    $data['order_uuid'] = $_GET['order_uuid'];
                    $data['shop_uuid'] = $shop;
                    $data['total'] = $total_price;
                    $data['sub_total'] = $products->total['total'];
                    $data['delivery_address_id'] = $address_info['id'];

                    if (isset($get_ship) && $get_ship > 0) {
                        $data['shipping_fee'] = $get_ship;
                    }
                    if (isset($voucher_info) && $voucher_info->status) {
                        $data['voucher_id'] = $voucher_info->result['id'];
                    }

                    $result_momo = $classPayment->result_payment_momo($_GET, $data);
                    if ($result_momo->status) {
                        echo ' <script>
                            setTimeout(function() {
                                window.location.href="?mod=page&act=checkout_success&order_uuid=' . $_GET['order_uuid'] . '";
                            }, 5000);
                        </script>';
                    } else {
                        echo '<div id="toast" mes-type="error" mes-title="Thất bại!" mes-text="' . $result_momo->message . '."></div>';
                    }

                }
                // checkout with momo============================================================================


                include_once 'view/inc/header.php';
                include_once 'view/checkout.php';
                include_once 'view/inc/footer.php';
            }
            break;
        case 'shop':
            //  code
            $tool = new Format();
            $shop = new Shop();
            if (isset($_GET['uuid']) && $_GET['uuid']) {
                $shop_info = $shop->get_info_shop($_GET['uuid']);
                if ($shop_info->status) {
                    $shop_info = $shop_info->result;
                    $shop_brands = $shop->get_brands_shop($shop_info['uuid'])->result;
                    $shop_products = $shop->get_products_shop($shop_info['id']);
                    $shop_sale_products = $shop->get_products_shop($shop_info['id'], true);
                    $shop_categories = $shop->get_categories_shop($shop_info['id']);
                    $shop_product_count = $shop->get_product_count_shop($shop_info['id']);
                    $shop_followers = count($shop->get_followers_shop($shop_info['id']));
                    $shop_rating = $shop->get_rating_shop($shop_info['id']);
                    $shop_voucher = $shop->get_voucher_shop($shop_info['id']);
                    $shop_category_menus = $shop->get_category_menus_shop($shop_info['id']);
                    $shop_products_all = $shop->get_products_shop_response($shop_info['id']);
                    $shop_filtered_products = $shop->get_filtered_products_shop($shop_info['id']);
                } else {
                    header('location:?page=404');
                }
            } else {
                header('location:?page=404');
            }
            // get message




            include_once 'view/inc/header.php';
            include_once 'view/shop.php';
            include_once 'view/inc/footer.php';
            break;
        case "checkout_success":
            if (isset($_GET['order_uuid']) && $_GET['order_uuid']) {
                $check_order_uuid = $classOrder->check_order_uuid($order_uuid, $status);
                if ($check_order_uuid == false) {
                    header("Location: ?page=404");
                }
            } else {
                header("Location: ?page=404");
            }
            $viewTitle = "Đặt hàng thành công!";
            include_once 'view/inc/header.php';
            include_once 'view/checkout_success.php';
            include_once 'view/inc/footer.php';
            break;
        default:
            header("Location: ?page=404");
    }
}
