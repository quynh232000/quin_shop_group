<!-- main -->
<main class="cart">
    <div class="wrapper">
        <div class="detail-tab">
            <a href="#" class="detail-tab-item">
                <span>Trang chủ</span>
                <i class="fa-solid fa-angle-right"></i>
            </a>
            <div class="detail-tab-item detail-tab-item-name">
                <span>Giỏ hàng</span>
            </div>
        </div>

        <div class="cart-body">
            <div class="cart-nav">
                <div class="cart-item">
                    <div class="cart-info">
                        <div class="cart-checkbox">
                            <!-- <input checked type="checkbox"> -->
                        </div>
                        <div class="cart-title">Tất cả (
                            <?= $cart_user->status ? ($cart_user->total['count']) : 0 ?> Sản phẩm)
                        </div>
                    </div>
                    <div class="cart-price">
                        <div class="cart-title">Giá</div>
                    </div>
                    <div class="cart-quantity">
                        <div class="cart-title">Số lượng</div>
                    </div>
                    <div class="cart-subtotal">
                        <div class="cart-title">Tổng tiền</div>
                    </div>
                    <div class="cart-action">
                        <div class="cart-title"><i class="fa-solid fa-trash-can"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="cart-list-shop" id="cart-list-shop">
            <!-- shop  -->
            <?php
            foreach ($data as $key_shop => $cart_shop) {
                $total = 0;
                $count = 0;
                ?>
                <div class="cart-body group_shop group_shop-<?=$key_shop?>" data-class=".group_shop-<?=$key_shop?>">
                    <div class="cart-group">
                        <div class="cart-shop">
                            <div class="cart-checkbox">
                            </div>
                            <a href="?mod=page&act=shop&uuid=<?= $cart_shop[0]['shop_info']['uuid'] ?>"
                                class="cart-shop-info">
                                <div class="cart-shop-img">
                                    <img src="./assest/upload/<?= $cart_shop[0]['shop_info']['icon'] ?>" alt="">
                                </div>
                                <div class="cart-shop-name">
                                    <?= strtoupper($cart_shop[0]['shop_info']['name']) ?>
                                </div>
                            </a>
                        </div>
                        <div class="cart-product">
                            <?php
                            foreach ($cart_shop as $key => $value) {
                                if($value['check']){
                                    $total += $value['product_info']['price'] * $value['quantity'];
                                    $count += $value['quantity'];
                                }
                                ?>
                                <div class="cart-item" idpro="<?= $value['product_info']['id'] ?>"
                                    checkpro="<?= $value['product_info']['origin']; ?>" countpro="<?= $value['quantity'] ?>"
                                    pricepro="<?= $value['product_info']['price'] ?>">
                                    <div class="cart-info">
                                        <div class="cart-checkbox">
                                            <input class="item-cart-checkbox" <?= (($value['check'] == true) ? "checked" : "") ?>
                                                type="checkbox"
                                                onchange="update_cart_user('<?= $value['check'] ? 'uncheck' : 'check' ?>', '<?= $value['product_info']['id'] ?>', 1, true)">
                                        </div>
                                        <div class="cart-item-pro">
                                            <div class="cart-item-img">
                                                <img src="./assest/upload/<?= $value['product_info']['image_cover'] ?>" alt="">
                                            </div>
                                            <div class="cart-info-right">
                                                <a href="?mod=page&act=detail&product=<?= $value['product_info']['slug']; ?>"
                                                    class="cart-item-name">
                                                    <?= $value['product_info']['name']; ?>
                                                </a>
                                                <div class="cart-item-note">
                                                    <?= $value['product_info']['brand']; ?> -
                                                    <?= $value['product_info']['origin']; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="cart-price">
                                        <div class="cart-item-price fm-price">
                                            <?= $value['product_info']['price']; ?>
                                        </div>
                                    </div>
                                    <div class="cart-quantity">

                                        <div class="cart-item-count">
                                            <div class="cart-count-btn " type-btn="minus"
                                                onclick="update_cart_user('minus', '<?= $value['product_info']['id'] ?>', 1, true)">
                                                <i class="fa-solid fa-minus"></i>
                                            </div>
                                            <input type="text" class="cart-count-input" readonly
                                                value="<?= $value['quantity'] ?>">
                                            <div class="cart-count-btn "
                                                onclick="update_cart_user('plus', '<?= $value['product_info']['id'] ?>', 1, true)">
                                                <i class="fa-solid fa-plus"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="cart-subtotal">
                                        <div class="cart-item-subtotal cart-subtotal1 fm-price"
                                            data-subtotal="<?= ($value['product_info']['price'] * $value['quantity']) ?>">
                                            <?= ($value['product_info']['price'] * $value['quantity']) ?>
                                        </div>
                                    </div>
                                    <div class="cart-action">
                                        <div class="cart-item-action-icon">
                                            <i class="fa-regular fa-heart"></i>
                                        </div>
                                        <div class="cart-item-action-icon "
                                            onclick="update_cart_user('delete', '<?= $value['product_info']['id'] ?>', 1, true)">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </div>
                                    </div>
                                </div>
                            <?php }
                            ?>
                        </div>
                        <?php
                        if (Session::get("isLogin")) { ?>
                            <div class="cart_voucher">
                                <div class="cart_voucher_top">
                                    <svg fill="var(--bg-yellow)" viewBox="0 -2 23 22"
                                        class="shopee-svg-icon lGPe96 icon-voucher-line">
                                        <g filter="url(#voucher-filter0_d)">
                                            <mask id="a" fill="#fff">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M1 2h18v2.32a1.5 1.5 0 000 2.75v.65a1.5 1.5 0 000 2.75v.65a1.5 1.5 0 000 2.75V16H1v-2.12a1.5 1.5 0 000-2.75v-.65a1.5 1.5 0 000-2.75v-.65a1.5 1.5 0 000-2.75V2z">
                                                </path>
                                            </mask>
                                            <path
                                                d="M19 2h1V1h-1v1zM1 2V1H0v1h1zm18 2.32l.4.92.6-.26v-.66h-1zm0 2.75h1v-.65l-.6-.26-.4.91zm0 .65l.4.92.6-.26v-.66h-1zm0 2.75h1v-.65l-.6-.26-.4.91zm0 .65l.4.92.6-.26v-.66h-1zm0 2.75h1v-.65l-.6-.26-.4.91zM19 16v1h1v-1h-1zM1 16H0v1h1v-1zm0-2.12l-.4-.92-.6.26v.66h1zm0-2.75H0v.65l.6.26.4-.91zm0-.65l-.4-.92-.6.26v.66h1zm0-2.75H0v.65l.6.26.4-.91zm0-.65l-.4-.92-.6.26v.66h1zm0-2.75H0v.65l.6.26.4-.91zM19 1H1v2h18V1zm1 3.32V2h-2v2.32h2zm-.9 1.38c0-.2.12-.38.3-.46l-.8-1.83a2.5 2.5 0 00-1.5 2.29h2zm.3.46a.5.5 0 01-.3-.46h-2c0 1.03.62 1.9 1.5 2.3l.8-1.84zm.6 1.56v-.65h-2v.65h2zm-.9 1.38c0-.2.12-.38.3-.46l-.8-1.83a2.5 2.5 0 00-1.5 2.29h2zm.3.46a.5.5 0 01-.3-.46h-2c0 1.03.62 1.9 1.5 2.3l.8-1.84zm.6 1.56v-.65h-2v.65h2zm-.9 1.38c0-.2.12-.38.3-.46l-.8-1.83a2.5 2.5 0 00-1.5 2.29h2zm.3.46a.5.5 0 01-.3-.46h-2c0 1.03.62 1.9 1.5 2.3l.8-1.84zM20 16v-2.13h-2V16h2zM1 17h18v-2H1v2zm-1-3.12V16h2v-2.12H0zm1.4.91a2.5 2.5 0 001.5-2.29h-2a.5.5 0 01-.3.46l.8 1.83zm1.5-2.29a2.5 2.5 0 00-1.5-2.3l-.8 1.84c.18.08.3.26.3.46h2zM0 10.48v.65h2v-.65H0zM.9 9.1a.5.5 0 01-.3.46l.8 1.83A2.5 2.5 0 002.9 9.1h-2zm-.3-.46c.18.08.3.26.3.46h2a2.5 2.5 0 00-1.5-2.3L.6 8.65zM0 7.08v.65h2v-.65H0zM.9 5.7a.5.5 0 01-.3.46l.8 1.83A2.5 2.5 0 002.9 5.7h-2zm-.3-.46c.18.08.3.26.3.46h2a2.5 2.5 0 00-1.5-2.3L.6 5.25zM0 2v2.33h2V2H0z"
                                                mask="url(#a)"></path>
                                        </g>
                                        <path clip-rule="evenodd"
                                            d="M6.49 14.18h.86v-1.6h-.86v1.6zM6.49 11.18h.86v-1.6h-.86v1.6zM6.49 8.18h.86v-1.6h-.86v1.6zM6.49 5.18h.86v-1.6h-.86v1.6z">
                                        </path>
                                        <defs>
                                            <filter id="voucher-filter0_d" x="0" y="1" width="20" height="16"
                                                filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                                                <feFlood flood-opacity="0" result="BackgroundImageFix"></feFlood>
                                                <feColorMatrix in="SourceAlpha"
                                                    values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"></feColorMatrix>
                                                <feOffset></feOffset>
                                                <feGaussianBlur stdDeviation=".5"></feGaussianBlur>
                                                <feColorMatrix values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.09 0">
                                                </feColorMatrix>
                                                <feBlend in2="BackgroundImageFix" result="effect1_dropShadow"></feBlend>
                                                <feBlend in="SourceGraphic" in2="effect1_dropShadow" result="shape"></feBlend>
                                            </filter>
                                        </defs>
                                    </svg>
                                    <span class="cart_voucher_add" onclick="show_voucher_cart(this)"
                                        data-togle="cart_show_voucher-<?= $key_shop ?>">Thêm mã giảm giá
                                        của shop</span>
                                </div>
                                <div class="cart_show_voucher cart_show_voucher-<?= $key_shop ?>">
                                    <form onsubmit="submit_voucher_code(event,this,'<?=$cart_shop[0]['shop_info']['id']?>')"  class="cart_voucher_input">
                                        <input class="cart_voucher_input_code" type="text" name="code"
                                            placeholder="Nhập mã giảm (mã chỉ áp dụng 1)">
                                        <button type="submit" class="cart_btn_apply">Áp dụng</button>
                                    </form>
                                    <div class="cart_result_check-code"></div>
                                    <div class="shop-voucher">
                                        <div class="cart-voucher-title">Mã giảm giá bạn đã lưu</div>
                                        <div class="shop-voucher-body">
                                            <?php
                                            $vouchers = $classVoucher->get_voucher_user('can_use_by_shop', "", $cart_shop[0]['shop_info']['id']);
                                            if (($vouchers->status == true) && count($vouchers->result) > 0) {
                                                foreach ($vouchers->result as $key => $voucher) { ?>
                                                    <div class="shop-voucher-item" style="width:50%">
                                                        <div class="shop-voucher-wrapper">
                                                            <div class="shop-voucher-boder">
                                                                <div class="shop-voucher-left">
                                                                    <div class="shop-voucher-text1">
                                                                        <?= $voucher['label'] ?>
                                                                    </div>
                                                                    <div class="shop-voucher-text2">Đơn hàng tối thiểu:
                                                                        <?= $voucher['minimum_price'] ?>
                                                                    </div>
                                                                    <div class="shop-voucher-text3">Giảm tối đa:
                                                                        <?= $voucher['discount_amount'] ?>
                                                                    </div>
                                                                    <div class="shop-voucher-text1">Mã voucher:
                                                                        <?= $voucher['code'] ?>
                                                                    </div>
                                                                    <div class="shop-voucher-text4">HSD:
                                                                        <?= explode(" ", $voucher['date_end'])[0] ?>
                                                                    </div>
                                                                </div>
                                                                <div class="shop-voucher-right">
                                                                    <button class="shop-voucher-btn " onclick="aply_voucher(this,'<?=$voucher['code']?>')" >Áp dụng</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php }
                                            } else {
                                                echo '<div class="no-product">Bạn chưa lưu voucher nào</div>';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php }
                        ?>
                        <div class="cart-bottom">
                            <div class="cart-bottom-total">
                                <div class="cart-bottom-left">
                                    <div class="cart-checkbox">
                                    </div>
                                    <span>Tổng (
                                        <?= $count ?> Sản phẩm)
                                    </span>
                                </div>
                                <div class="cart-bottom-right">
                                    <div class="cart_right-item">
                                        <div class="cart-bottom-right-title">
                                            Tạm tính:
                                        </div>
                                        <div class="cart-bottom-right-total fm-price cart_subtotal_price" cart-total="<?= $total ?>">
                                            <?= $total ?>
                                        </div>
                                    </div>
                                    <div class="cart_right-item_voucher">
                                        
                                    </div>
                                    <div class="cart_right-item total">
                                        <div class="cart-bottom-right-title total">
                                            Tổng tiền:
                                        </div>
                                        <div class="cart-bottom-right-total fm-price cart_total_price" cart-total="<?= $total ?>">
                                            <?= $total ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <form class="cart-bottom-btn form-submit-buy">
                                <input type="text" hidden name="mod" value="<?= $mod ?>">
                                <input type="text" hidden name="act" value="checkout">
                                <input type="text" hidden name="shop" value="<?= $cart_shop[0]['shop_info']['uuid'] ?>">
                                
                                <button type="submit" href="?mod=page&act=checkout" class="cart-btn cart-btn-buy">Mua
                                    hàng</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php }

            ?>







        </div>


        <div class="more-info more-info-edit">
            <div class="more-info-item">
                <div class="more-infor-wrapper">
                    <div class="more-info-img"><img src="./assest/images/xe 1.svg" alt=""></div>
                    <div class="more-info-text1">Giao hàng miễn phí</div>
                    <div class="more-info-text2">Miễn phí vận chuyển các tỉnh</div>
                </div>
            </div>
            <div class="more-info-item">
                <div class="more-infor-wrapper">
                    <div class="more-info-img"><img src="./assest/images/save 1.svg" alt=""></div>
                    <div class="more-info-text1">Mua hàng tiết kiệm</div>
                    <div class="more-info-text2">Chương trình Flash Sale mỗi ngày</div>
                </div>
            </div>
            <div class="more-info-item">
                <div class="more-infor-wrapper">
                    <div class="more-info-img"><img src="./assest/images/online.svg"></div>
                    <div class="more-info-text1">Hỗ trợ 24/7</div>
                    <div class="more-info-text2">Hỗ trợ giải đáp thắc mắc 24/7</div>
                </div>
            </div>
            <div class="more-info-item">
                <div class="more-infor-wrapper">
                    <div class="more-info-img"><img src="./assest/images/money.svg" alt=""></div>
                    <div class="more-info-text1">Chính sách hoàn tiền đơn hàng</div>
                    <div class="more-info-text2">Hoàn trả đơn hàng trong 7 ngày</div>
                </div>
            </div>
            <div class="more-info-item">
                <div class="more-infor-wrapper">
                    <div class="more-info-img"><img src="./assest/images/member.svg" alt=""></div>
                    <div class="more-info-text1">Chính sách thành viên</div>
                    <div class="more-info-text2">Deal Sale cho khách hàng thành viên</div>
                </div>
            </div>
        </div>
    </div>
</main>