<link rel="stylesheet" href="./src/css/shop.css">
<link rel="stylesheet" href="./src/css/tailwind/tailwind-all.min.css">

<!-- <script src="https://cdn.tailwindcss.com"></script> -->
<!-- main -->
<main class="collection">
    <div class="wrapper">
        <!-- shop info -->
        <div class="shop">
            <div class="shop-top">
                <div class="shop-group-info">
                    <div class="shop-info">
                        <div class="shop-body">
                            <div class="shop-info-left">
                                <div class="shop-img">
                                    <img src="./assest/upload/<?= $shop_info['icon'] ?>" alt="">
                                </div>
                                <div class="shop-name1">
                                    <?= $shop_info['name'] ?>
                                </div>
                            </div>
                            <div class="shop-info-right">
                                <div class="shop-name">
                                    <?= $shop_info['name'] ?>
                                </div>
                                <!-- <div class="shop-online">Online 4 minutes</div> -->
                            </div>
                        </div>
                        <div class="shop-info-btn-wrapper">
                            <div class="shop-btn" id="shop_follow_id">
                                <?php
                                if ($shop->check_follow_shop($shop_info['uuid'])) {
                                    echo '<div onclick="follow_shop(' . "'" . $shop_info['uuid'] . "'" . ', ' . "'unfollow'" . ')">
                                    <i class="fa-solid fa-minus"></i>
                                    Bỏ theo dõi
                                    </div>';
                                } else {
                                    echo '<div onclick="follow_shop(' . "'" . $shop_info['uuid'] . "'" . ', ' . "'follow'" . ')">
                                    <i class="fa-solid fa-plus"></i>
                                    Theo dõi
                                    </div>';
                                }

                                ?>

                            </div>
                            <div class="shop-btn" id="chat_shop">
                                <i class="fa-solid fa-comments"></i>
                                Nhắn tin
                            </div>
                        </div>
                    </div>
                </div>
                <div class="shop-group">
                    <div class="shop-item">
                        <i class="fa-solid fa-box-open"></i>
                        <div class="shop-item-title">Số mặt hàng:</div>
                        <div class="shop-item-info">
                            <?= $shop_product_count ?>
                        </div>
                    </div>
                    <div class="shop-item">
                        <i class="fa-solid fa-people-arrows"></i>
                        <div class="shop-item-title">Người theo dõi:</div>
                        <div class="shop-item-info" id="count_followers">
                            <?= $shop_followers ?>
                        </div>
                    </div>
                </div>
                <div class="shop-group">

                    <div class="shop-item">
                        <i style="color:gray" class="fa-regular fa-star"></i>
                        <div class="shop-item-title">Đánh giá:</div>
                        <div class="shop-item-info">
                            <?= round($shop_rating['stars'], 2) ?> (
                            <?= $shop_rating['votes'] ?> lượt đánh giá)
                        </div>
                    </div>
                    <div class="shop-item">
                        <i class="fa-solid fa-user-check"></i>
                        <div class="shop-item-title">Đã đăng ký bán hàng:</div>
                        <div class="shop-item-info">
                            <?= $tool->diffForHumans($shop_info['created_at']) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="shop-voucher">
            <div class="shop-voucher-title">SHOP VOUCHERS</div>
            <div class="shop-voucher-body">
                <?php
                $html_voucher = "";
                foreach ($shop_voucher as $voucher) {
                    $voucher_label = $voucher['label'];
                    $voucher_discount_amount = ($voucher['discount_amount']) / 1000;
                    $voucher_minimum_price = ($voucher['minimum_price']) / 1000;
                    $voucher_check = $shop->check_voucher_user($voucher['id']);
                    $voucher_id = $voucher['id'];
                    if ($voucher_check->status) {
                        if ($voucher_check->result['is_used']) {
                            $message = '<div class="shop-voucher-btn">Đã sử dụng</div>';
                        } else {
                            $message = '<div class="shop-voucher-btn">Đã lưu</div>';
                        }
                    } else {
                        $message = "<div class='shop-voucher-btn ' id='' onclick='save_voucher(this,$voucher_id)'>Lưu</div>";
                    }
                    $voucher_date_end = $voucher['date_end'];
                    $html_voucher = <<<EOT
                    <div class="shop-voucher-item">
                    <div class="shop-voucher-wrapper">
                        <div class="shop-voucher-boder">
                            <div class="shop-voucher-left">
                                <div class="shop-voucher-text1">$voucher_label</div> 
                                <div class="shop-voucher-text2">Đơn tối thiểu $voucher_minimum_price k</div>
                                <div class="shop-voucher-text3">Giảm tối đa $voucher_discount_amount k</div>
                                <div class="shop-voucher-text4">HSD: $voucher_date_end</div>
                            </div>
                            <div class="shop-voucher-right">
                                $message
                            </div>
                        </div>
                    </div>
                    </div>
                    EOT;
                    echo $html_voucher;
                }
                ?>


            </div>
        </div>
        <div class="recommend-product">
            <div class="new-product">
                <div class="wrapper">
                    <div class="new-product-wrapper">
                        <div class="new-product-top">
                            <div class="new-product-title">
                                SẢN PHẨM BÁN CHẠY
                            </div>
                            <a href="?mod=page&act=collection" class="new-product-more">
                                <!-- Xem thêm -->
                                <!-- <i class="fa-solid fa-chevron-right"></i> -->
                            </a>
                        </div>
                        <div class="new-product-body">
                            <div class="new-product-big">
                                <img src="./assest/images/new pro big.svg" alt="">
                            </div>
                            <!-- item -->
                            <?php
                            // $shop->test($shop_products);
                            // brand, name, stars, votes, price, percent_sale
                            // $shop_rating['stars'], $shop_rating['votes']
                            foreach ($shop_products as $product) {
                                $brand = $product['brand'];
                                $slug = $product['slug'];
                                // $name = substr($product['name'], 0, 50);
                                $id = $product['id'];
                                $name = $product['name'];
                                $img = $product['image_cover'];
                                $rating = $shop->get_rating_product($shop_info['id'], $product['id']);
                                $stars = $rating['stars'];
                                $html_stars = str_repeat('<i class="fa-solid fa-star"></i>', ceil($stars));
                                $html_no_stars = str_repeat('<i style="color:gray" class="fa-regular fa-star"></i>', 5);
                                $html_render_stars = $stars ? $html_stars : $html_no_stars;
                                // $shop->test($html_stars);
                                $votes = $rating['votes'] ? "(" . $rating['votes'] . ")" : "(0)";
                                $price = $product['price'];
                                $percent_sale = $product['percent_sale'];
                                $price_sale = $price * (100 - $percent_sale) / 100;
                                $price_format = number_format((float) $price, 0, ',', '.');
                                $price_sale_format = number_format((float) $price_sale, 0, ',', '.');
                                // $shop->test($price_format);
                            
                                $html_product = <<<EOT
                                <div class="product ">
                                    <div class="product-wrapper">
                                        <a href="?mod=page&act=detail&product=$slug" class="product-info">
                                            <div class="product-sale-label">
                                            <svg width="48" height="50" viewBox="0 0 48 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <g filter="url(#filter0_d_604_13229)">
                                                    <path d="M4.49011 0C3.66365 0 2.99416 0.677946 2.99416 1.51484V11.0288V26.9329C2.99416 30.7346 5.01545 34.2444 8.28604 36.116L20.4106 43.0512C22.6241 44.3163 25.3277 44.3163 27.5412 43.0512L39.6658 36.116C42.9363 34.2444 44.9576 30.7346 44.9576 26.9329V11.0288V1.51484C44.9576 0.677946 44.2882 0 43.4617 0H4.49011Z" fill="#F5C144"></path>
                                                </g>
                                                <defs>
                                                    <filter id="filter0_d_604_13229" x="-1.00584" y="0" width="49.9635" height="52" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                                                        <feFlood flood-opacity="0" result="BackgroundImageFix"></feFlood>
                                                        <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"></feColorMatrix>
                                                        <feOffset dy="4"></feOffset>
                                                        <feGaussianBlur stdDeviation="2"></feGaussianBlur>
                                                        <feComposite in2="hardAlpha" operator="out"></feComposite>
                                                        <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.25 0"></feColorMatrix>
                                                        <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_604_13229"></feBlend>
                                                        <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_604_13229" result="shape"></feBlend>
                                                    </filter>
                                                </defs>
                                            </svg>
                                            <span>-%$percent_sale</span>
                                        </div>
    
                                            <div class="product-img">
                                                <img src="./assest/upload/$img" alt="Ảnh về $name">
                                            </div>
                                            <div class="product-brand">
                                                $brand
                                            </div>
                                            <div class="product-name">
                                                $name
                                            </div>
                                            <div class="product-stars">
                                                $html_render_stars
                                                <span>$votes</span>
                                            </div>
                                            <div class="product-price">
                                                <div class="product-price-sale">đ$price_sale_format</div>
                                                <del class="product-price-old">đ$price_format</del>
                                            </div>
                                        </a>
                                        <div class="product-btn" onclick="update_cart_user('plus','$id',1)">
                                            <i class="fa-solid fa-cart-plus"></i>
                                            <span>Thêm giỏ hàng</span>
                                        </div>
                                    </div>
                                </div>
                                EOT;
                                echo $html_product;
                            }
                            ?>
                            <!-- item -->

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="c-banner">
            <div class="swiper  swipper-banner-collection">
                <div class="swiper-wrapper">
                    <div class="swiper-slide banner-mid-item ">
                        <img src="./assest/images/collections/c-banner.png" alt="">
                    </div>
                    <div class="swiper-slide banner-mid-item " style="height: 100%;">
                        <img src="./assest/images/collections/banner-group3.png" style="height: 100%;" alt="">
                    </div>

                </div>
                <!-- If we need pagination -->
                <div class="swiper-pagination"></div>

                <!-- If we need navigation buttons -->
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>

                <!-- If we need scrollbar -->
                <!-- <div class="swiper-scrollbar"></div> -->
            </div>
        </div>
        <!-- best seling -->
        <div class="shop-best-sale-product">
            <div class="wrapper">
                <div class="shop-product-wrapper">
                    <div class="new-product-top">
                        <div class="new-product-title">
                            SẢN PHẨM GIÁ SỐC
                        </div>
                        <a href="?mod=page&act=collection" class="new-product-more">
                            <!-- Xem thêm
                            <i class="fa-solid fa-chevron-right"></i> -->
                        </a>
                    </div>
                    <div class="shop-best-seling-body">
                        <!-- item -->
                        <?php
                        // $shop->test($shop_products);
                        // brand, name, stars, votes, price, percent_sale
                        // $shop_rating['stars'], $shop_rating['votes']
                        foreach ($shop_sale_products as $product) {
                            $slug = $product['slug'];

                            $brand = $product['brand'];
                            // $name = substr($product['name'], 0, 50);
                            $id = $product['id'];
                            $name = $product['name'];
                            $image_cover = $product['image_cover'];
                            $rating = $shop->get_rating_product($shop_info['id'], $product['id']);
                            $stars = $rating['stars'];
                            $html_stars = str_repeat('<i class="fa-solid fa-star"></i>', ceil($stars));
                            $html_no_stars = str_repeat('<i style="color:gray" class="fa-regular fa-star"></i>', 5);
                            $html_render_stars = $stars ? $html_stars : $html_no_stars;
                            // $shop->test($html_stars);
                            $votes = $rating['votes'] ? "(" . $rating['votes'] . ")" : "(0)";
                            /* <?= $stars ? $html_stars : $html_no_stars ?> */
                            $price_sale = $product['price'];
                            $percent_sale = $product['percent_sale'];
                            // price sale
                            $price_format = "";
                            $label_sale="";
                            if($percent_sale > 0){
                                $numb = $price_sale * (100 /(100 - $percent_sale)) /1000;
                                $price = round($numb,0)*1000 ;
                                // number_format((float) $price, 0, ',', '.');
                                $price_format =' 
                                <del class="product-price-old">đ'.number_format((float) $price, 0, ',', '.').'</del>
                            ';
                            $label_sale =' <div class="product-sale-label">
                                    <svg width="48" height="50" viewBox="0 0 48 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <g filter="url(#filter0_d_604_13229)">
                                            <path d="M4.49011 0C3.66365 0 2.99416 0.677946 2.99416 1.51484V11.0288V26.9329C2.99416 30.7346 5.01545 34.2444 8.28604 36.116L20.4106 43.0512C22.6241 44.3163 25.3277 44.3163 27.5412 43.0512L39.6658 36.116C42.9363 34.2444 44.9576 30.7346 44.9576 26.9329V11.0288V1.51484C44.9576 0.677946 44.2882 0 43.4617 0H4.49011Z" fill="#F5C144"></path>
                                        </g>
                                        <defs>
                                            <filter id="filter0_d_604_13229" x="-1.00584" y="0" width="49.9635" height="52" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                                                <feFlood flood-opacity="0" result="BackgroundImageFix"></feFlood>
                                                <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"></feColorMatrix>
                                                <feOffset dy="4"></feOffset>
                                                <feGaussianBlur stdDeviation="2"></feGaussianBlur>
                                                <feComposite in2="hardAlpha" operator="out"></feComposite>
                                                <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.25 0"></feColorMatrix>
                                                <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_604_13229"></feBlend>
                                                <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_604_13229" result="shape"></feBlend>
                                            </filter>
                                        </defs>
                                    </svg>
                                    <span>-%'.$percent_sale.'</span>
                                </div>';
                            }
                            // price sale
                            $price_sale_format = number_format((float) $price_sale, 0, ',', '.');

                            $html_product = <<<EOT
                            <!-- item -->
                                    <div class="product ">
                                    <div class="product-wrapper">
                                        <a href="?mod=page&act=detail&product=$slug" class="product-info">
                                           $label_sale
                                            <div class="product-img">
                                                <img src="./assest/upload/$image_cover" alt="$name">
                                            </div>
                                            <div class="product-brand">
                                                $brand
                                            </div>
                                            <div class="product-name">
                                                $name
                                            </div>
                                            <div class="product-stars">
                                                $html_render_stars
                                                <span>$votes</span>
                                            </div>
                                            
                                            <div class="product-price">
                                            <div class="product-price-sale">đ$price_sale_format</div>
                                            $price_format
                                            </div>
                                        </a>
                                        <div class="product-btn" onclick="update_cart_user('plus','$id',1)">
                                            <i class="fa-solid fa-cart-plus"></i>
                                            <span>Thêm giỏ hàng</span>
                                        </div>
                                    </div>
                                </div>
                            <!-- item -->
                        EOT;
                            echo $html_product;
                        }
                        ?>
                        <!-- item -->


                    </div>
                </div>
            </div>
        </div>
        <div class="c-body">
            <div class="g-left">
                <div class="g-left-top">
                    <div class="g-left-top-title">
                        <i class="fa-solid fa-bars"></i>
                        <p class="lg lg-allCategory">DANH MỤC</p>
                    </div>
                    <form action="controller/page.php" id="filterCategory" method="get">
                        <?php
                        // print_r(count($shop_category_menus));
                        // die();
                        function showCateMenus($shop_category_menus, $parent_id=0)
                        {
                            $menu_tmp = [];
                            // --
                            foreach ($shop_category_menus as $category) {
                                if ($category['parent_id'] == $parent_id) {
                                    $menu_tmp[] = $category;
                                }
                            }
                            // --
                            if ($menu_tmp) {
                                echo '<div class="g-left-top-body g-left-top-body-checkbox grid gap-6 mb-6 md:grid-cols-1">';
                                foreach ($menu_tmp as $menu) {
                                    // $... 
                                    $name = $menu['name'];
                                    $id = $menu['category_id'];
                                    // render
                                    $html = <<<EOT
                                    <label class="cate_input text-2xl" for="input_cate_$id" id-cate="$id"><input class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800" id="input_cate_$id" type="checkbox" name="category[]" value="$id"/> $name</label>
                                    EOT;
                                    echo $html;
                                    showCateMenus($shop_category_menus, $menu['category_id']);
                                }
                                echo "</div>";
                            }
                        }
                        showCateMenus($shop_category_menus);
                        ?>
                    </form>

                </div>


            </div>
            <div class="g-right">
                <div class="g-nav">
                    <div class="g-nav-left">
                        <div class="c-nav-hhiden">
                            <i class="fa-solid fa-bars"></i>
                        </div>
                        <div class="g-nav-title ">Lọc theo</div>


                        <!-- Sort by brand  -->
                        <div class=" g-nav-item_down">
                            <button id="dropdownCheckboxButton" onclick="toggleDropdown('#dropdown3')"
                                data-dropdown-toggle="dropdownDefaultCheckbox" class="s_btn_sort " type="button">
                                <div class="brand_sort">Thương hiệu</div><svg class="w-2.5 h-2.5 ms-3"
                                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 10 6">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 4 4 4-4" />
                                </svg>
                            </button>

                            <!-- Dropdown menu -->
                            <div id="dropdown3"
                                class="radio dropdown absolute right-0 z-10 hidden w-48 bg-white divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-700 dark:divide-gray-600">
                                <ul class=" space-y-3 text-sm text-gray-700 dark:text-gray-200"
                                    aria-labelledby="dropdownCheckboxButton" id="type_radio_2">
                                    <?php
                                    foreach ($shop_brands as $key => $brand) {

                                        $brand = $brand['brand'];
                                        $html_brand = <<<EOT
                                        <li>
                                        <div class="flex items-center p-2">
                                            <input id="checkbox-item-$key" type="radio" name="input_type_3" value="$brand" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                            <label for="checkbox-item-$key" class="ms-2 text-2xl font-medium text-gray-900 dark:text-gray-300">$brand</label>
                                        </div>
                                        </li>
                                        EOT;
                                        echo $html_brand;
                                    }

                                    ?>
                                </ul>
                            </div>
                        </div>
                        <!-- Sort by type  -->
                        <div class=" g-nav-item_down">
                            <button id="dropdownCheckboxButton" onclick="toggleDropdown('#dropdown1')"
                                data-dropdown-toggle="dropdownDefaultCheckbox" class="s_btn_sort" type="button">Sắp xếp:
                                <div class="sort_sort px-2"> Theo loại</div><svg class="w-2.5 h-2.5 ms-3"
                                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 10 6">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 4 4 4-4" />
                                </svg>
                            </button>

                            <!-- Dropdown menu -->
                            <div id="dropdown1"
                                class="radio dropdown absolute right-0 z-10 hidden w-100 bg-white divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-700 dark:divide-gray-600">
                                <ul class=" space-y-3 text-sm text-gray-700 dark:text-gray-200"
                                    aria-labelledby="dropdownCheckboxButton" id="type_radio_3">
                                    <li>
                                        <div class="flex items-center p-2">
                                            <input id="checkbox-item-c" type="radio" name="input_type_1" value="New"
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                            <label for="checkbox-item-c"
                                                class="ms-2 text-2xl font-medium text-gray-900 dark:text-gray-300">Mới
                                                nhất</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="flex items-center p-2">
                                            <input id="checkbox-item-d" type="radio" name="input_type_1"
                                                value="Flash Sale"
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                            <label for="checkbox-item-d"
                                                class="ms-2 text-2xl font-medium text-gray-900 dark:text-gray-300">Flash
                                                Sale</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="flex items-center p-2">
                                            <input id="checkbox-item-f" type="radio" name="input_type_1" value="Hot"
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                            <label for="checkbox-item-f"
                                                class="ms-2 text-2xl font-medium text-gray-900 dark:text-gray-300">Hot</label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>



                    </div>
                    <div class="g-nav-right">
                        <div class="g-nav-page">

                        </div>
                        <div class="g-nav-btn-group">
                            <button class="g-nav-btn chevrons_page disabled" type="previous">
                                <i class="fa-solid fa-angle-left"></i>
                            </button>
                            <div class="flex list_number">
                                <?php
                                // $shop->test($shop_products_all->total);
                                for ($i = 0; $i < ceil($shop_products_all->total / 8); $i++) {
                                    $index = $i + 1;
                                    $active = $index == 1 ? 'active' : '';
                                    echo "<div class='g-nav-btn pagination_number $active'  data='$index'>$index</div>";
                                }

                                ?>
                            </div>
                            <button class="g-nav-btn chevrons_page" type="next">
                                <i class="fa-solid fa-angle-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- list product -->
                <div class="g-list-product">
                    <!-- item -->
                    <?php
                    if (count($shop_products_all->result) > 0) {
                        foreach ($shop_products_all->result as $product) {
                            $slug = $product['slug'];

                            $brand = $product['brand'];
                            // $name = substr($product['name'], 0, 50);
                            $id = $product['id'];
                            $name = $product['name'];
                            $image_cover = $product['image_cover'];
                            $rating = $shop->get_rating_product($shop_info['id'], $product['id']);
                            $stars = $rating['stars'];
                            $html_stars = str_repeat('<i class="fa-solid fa-star"></i>', ceil($stars));
                            $html_no_stars = str_repeat('<i style="color:gray" class="fa-regular fa-star"></i>', 5);
                            $html_render_stars = $stars ? $html_stars : $html_no_stars;
                            // $shop->test($html_stars);
                            $votes = $rating['votes'] ? "(" . $rating['votes'] . ")" : "(0)";
                            /* <?= $stars ? $html_stars : $html_no_stars ?> */
                            $price_sale = $product['price'];
                            $percent_sale = $product['percent_sale'];
                            // price sale
                            $price_format = "";
                            $label_sale="";
                            if($percent_sale > 0){
                                $numb = $price_sale * (100 /(100 - $percent_sale)) /1000;
                                $price = round($numb,0)*1000 ;
                                $price_format =' 
                                <del class="product-price-old">đ'.number_format((float) $price, 0, ',', '.').'</del>
                            ';
                            $label_sale =' <div class="product-sale-label">
                                    <svg width="48" height="50" viewBox="0 0 48 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <g filter="url(#filter0_d_604_13229)">
                                            <path d="M4.49011 0C3.66365 0 2.99416 0.677946 2.99416 1.51484V11.0288V26.9329C2.99416 30.7346 5.01545 34.2444 8.28604 36.116L20.4106 43.0512C22.6241 44.3163 25.3277 44.3163 27.5412 43.0512L39.6658 36.116C42.9363 34.2444 44.9576 30.7346 44.9576 26.9329V11.0288V1.51484C44.9576 0.677946 44.2882 0 43.4617 0H4.49011Z" fill="#F5C144"></path>
                                        </g>
                                        <defs>
                                            <filter id="filter0_d_604_13229" x="-1.00584" y="0" width="49.9635" height="52" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                                                <feFlood flood-opacity="0" result="BackgroundImageFix"></feFlood>
                                                <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"></feColorMatrix>
                                                <feOffset dy="4"></feOffset>
                                                <feGaussianBlur stdDeviation="2"></feGaussianBlur>
                                                <feComposite in2="hardAlpha" operator="out"></feComposite>
                                                <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.25 0"></feColorMatrix>
                                                <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_604_13229"></feBlend>
                                                <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_604_13229" result="shape"></feBlend>
                                            </filter>
                                        </defs>
                                    </svg>
                                    <span>-%'.$percent_sale.'</span>
                                </div>';
                            }
                            // price sale
                            $price_sale_format = number_format((float) $price_sale, 0, ',', '.');

                            $html_product = <<<EOT
                            <!-- item -->
                                    <div class="product ">
                                    <div class="product-wrapper">
                                        <a href="?mod=page&act=detail&product=$slug" class="product-info">
                                           $label_sale
                                            <div class="product-img">
                                                <img src="./assest/upload/$image_cover" alt="$name">
                                            </div>
                                            <div class="product-brand">
                                                $brand
                                            </div>
                                            <div class="product-name">
                                                $name
                                            </div>
                                            <div class="product-stars">
                                                $html_render_stars
                                                <span>$votes</span>
                                            </div>
                                            
                                            <div class="product-price">
                                            <div class="product-price-sale">đ$price_sale_format</div>
                                            $price_format
                                            </div>
                                        </a>
                                        <div class="product-btn" onclick="update_cart_user('plus','$id',1)">
                                            <i class="fa-solid fa-cart-plus"></i>
                                            <span>Thêm giỏ hàng</span>
                                        </div>
                                    </div>
                                </div>
                            <!-- item -->
                        EOT;
                            echo $html_product;
                        }
                    } else {
                        echo "<div>Không có sản phẩm nào!</div>";
                    }
                    ?>
                </div>
            </div>
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
<!-- chat box -->
<div id="chat-icon">
    <i class="fa-brands fa-facebook-messenger"></i>
</div>
<dialog class="chat-modal">
    <div class="chat-modal" id="chat-modal">
        <!-- <img class="modal-image"
                            src="https://scontent.fsgn2-9.fna.fbcdn.net/v/t39.30808-6/433285128_816273073867027_9017406828954417140_n.jpg?_nc_cat=103&ccb=1-7&_nc_sid=5f2048&_nc_ohc=oss3x6zS6ckAX-rMq0t&_nc_ht=scontent.fsgn2-9.fna&oh=00_AfDxYDFGcei1I7uG1NaJ-wCTefgkgL0MxXLqio2Gkh3ixg&oe=660A786D"
                            alt=""> -->
    </div>
</dialog>
<!--  -->
<div id="chat-box" class="hidden  active">
    <div class="box-head">
        <div class="avatar">
            <div class="avt">
                <img class="avt-image" src="assest/upload/<?= $shop_info['icon'] ?>" alt="">
                <div class="avt-point"></div>
            </div>
            <div class="">
                <div class="avt-name">
                    <?= $shop_info['name'] ?>
                </div>
            </div>
        </div>
        <div class="group-right">
            <i class="fa-solid fa-x" id="close-btn"></i>
        </div>
    </div>
    <div class="box-body" id="chat-box-content">
        <!-- <?php
        if (isset($get_messages) && count($get_messages) > 0) {
            foreach ($get_messages as $key => $value) {
                $listImg = json_decode($value['message_media']);
                $htmlImg = '';
                foreach ($listImg as $key => $img) {
                    $htmlImg .= '<img class="chat-item-image" src="assest/upload/' . $img . '"/>';
                }
                echo '<div class="' . ($value['sender_type'] == 'user' ? 'chat-send' : 'chat-give') . ' chat-message-body">
                            <div class="chat-content ">
                                ' . $value['message_text'] . '
                            </div>
                            <div class="chat_list_img">
                            ' . $htmlImg . '
                            </div>
                        </div>';
            }
        } else {
            echo '<div class="no_mesage">Bạn có thắc mắc gì hãy hỏi đáp cho chúng tôi!</div>';
        }
        ?> -->
    </div>
    <div class="chat_list_preview" id="chat_list_preview">
        <!-- <div class="chat_preview_img">
            <img src="http://localhost/quin_group/assest/upload/7342CC04-B10E-44C7-8B63-7FE413707643.webp" alt="">
        </div>
        <div class="chat_preview_img">
            <img src="http://localhost/quin_group/assest/upload/7342CC04-B10E-44C7-8B63-7FE413707643.webp" alt="">
        </div>
        <div class="chat_preview_img">
            <img src="http://localhost/quin_group/assest/upload/7342CC04-B10E-44C7-8B63-7FE413707643.webp" alt="">
        </div>
        <div class="chat_preview_img">
            <img src="http://localhost/quin_group/assest/upload/7342CC04-B10E-44C7-8B63-7FE413707643.webp" alt="">
        </div>
        <div class="chat_preview_img">
            <img src="http://localhost/quin_group/assest/upload/7342CC04-B10E-44C7-8B63-7FE413707643.webp" alt="">
        </div>
        <div class="chat_preview_img">
            <img src="http://localhost/quin_group/assest/upload/7342CC04-B10E-44C7-8B63-7FE413707643.webp" alt="">
        </div> -->
    </div>
    <form id="form_chat-box" enctype="multipart/form-data"  class="box-input"  >
        <label for="chat_img" class="chat_icon_img">
            <i class="fa-regular fa-images"></i>
        </label>
        <input type="file" hidden multiple name="media[]" id="chat_img">
        <input type="text" hidden multiple name="shop_id" id="chat_shop_id" value="<?=$shop_info['id'] ?>">
        <input class="input-chat" id="chat-input" name="message_text" type="text" placeholder="Gửi tin nhắn..">
        <button type="submit" class="icon-send" id="btn-send">
            <i class="fa-solid fa-paper-plane"></i>
        </button>
    </form>
    <div class="scroll-bottom hidden" id="scroll-btn">
        <i class="fa-solid fa-arrow-down"></i>
    </div>
</div>
<!-- chat box -->
<script src="./src/js/chatbox.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<script src="./src/js/main.js" type="module"></script>
<!-- <script src="./src/js/slider.js"></script> -->

</body>

</html>