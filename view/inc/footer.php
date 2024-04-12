<!-- footer -->
<style>
.l-footer-child span{
    font-size: 1.4rem;
    line-height: 2rem;
}
.l-footer-child-info{
    line-height: 2rem;
}
.l-footer-child{
    cursor: pointer;
}
</style>
<footer>
    <div class="l-footer">
        <div class="l-footer-wrapper">
            <div class="l-footer-item">
                <div class="l-footer-title">
                    THÔNG TIN LIÊN HỆ
                </div>
                <div class="l-footer-child">
                    <i class="fa-solid fa-headset"></i>
                    <div class="l-footer-child-info">
                        <span>0358723520 / Call us 24/7</span>
                        <!-- <span>Call on Order/ Call us 24/7</span> -->
                    </div>
                </div>
                <div class="l-footer-child">
                    <i class="fa-solid fa-location-dot"></i>
                    <div class="l-footer-child-info">
                        Cv.Quang Trung, Q.12, HCM
                    </div>
                </div>
                <div class="l-footer-child">
                    <i class="fa-regular fa-envelope"></i>
                    <div class="l-footer-child-info">
                        quynh232000@gmail.com
                    </div>
                </div>
            </div>
            <div class="l-footer-item">
                <div class="l-footer-title">
                    CHÍNH SÁCH QUIN
                </div>
                <a href="#" class="l-footer-child">
                    Phương thức thanh toán
                </a>
                <a href="#" class="l-footer-child">
                    Chính sách giao hàng
                </a>
                <a href="#" class="l-footer-child">
                    Chính sách đổi trả
                </a>
                <a href="#" class="l-footer-child">
                    Điều khoản mua bán
                </a>

            </div>
            <div class="l-footer-item">
                <div class="l-footer-title">
                    VỀ CHÚNG TÔI
                </div>
                <a href="#" class="l-footer-child">
                    Câu chuyện của Quin
                </a>
                <a href="#" class="l-footer-child">
                    Tin tức
                </a>
                <a href="#" class="l-footer-child">
                    Tuyển dụng
                </a>
                <a href="#" class="l-footer-child">
                    Chương trình flash sale
                </a>

            </div>
            <div class="l-footer-item">
                <div class="l-footer-title">
                    THEO DÕI
                </div>
                <div class="l-list-community" >
                    <a href="https://www.facebook.com/quynh232000/" class="l-footer-child">
                        <i class="fa-brands fa-facebook"></i>
                        <span>Facebook Quinshop</span>
                    </a>
                    <a href="https://mr-quynh.com/" class="l-footer-child">
                        <i class="fa-brands fa-tiktok"></i>
                        <span>QuinShop</span>
                    </a>
                    <a href="https://mr-quynh.com/" class="l-footer-child">
                        <i class="fa-brands fa-youtube"></i>
                        <span>Youtube QuinShop</span>
                    </a>
                </div>

            </div>

        </div>
        <div class="footer-copy">
            Copyright © 2024 <a href="https://www.facebook.com/quynh232000/">Mr Quynh</a>
        </div>
    </div>
</footer>
</div>



<div id="snackbar"></div>

<script>
    const VND = new Intl.NumberFormat("vi-VN", {
        style: "currency",
        currency: "VND",
    });
    const prices = document.querySelectorAll(".fm-price")
    prices.forEach(item => {
        if (!isNaN(item.textContent)) {
            item.textContent = VND.format(item.textContent)

        }
    })
</script>


<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<script src="./src/js/main.js" type="module"></script>
<script src="./src/js/slider.js"></script>
<script src="./src/js/vinh.js"></script>
<!-- <script src="./src/js/chatbox.js"></script> -->

</body>

</html>