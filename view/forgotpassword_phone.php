<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" type="image/x-icon" href="./assest/images/logo-no-text.png">
  <title>QUIN -Quên mật khẩu</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="./src/css/register.css" />
  <link rel="stylesheet" href="./src/css/base.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
  <script src="https://code.jquery.com/jquery-2.2.4.min.js"
    integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>

  <script src="./src/js/define.js"></script>
</head>

<body>
  <!-- forgot-bg -->
  <div class="register ">
    <div class="wrapper">
      <form class="form-login" action="?mod=profile&act=changepass_phone<?=isset($_GET['token']) ? '&token='.$_GET['token'] :""?>" method="POST">
        <h1>Quên mật khẩu!</h1>
        <!-- invalidate -->
        <!-- form input email -->
        <?php
        if ($active == 'default') { ?>
          <div class="subscribe">
            <p>Số điện thoại của bạn</p>
            <input placeholder="Nhập số điện thoại.." class="subscribe-input"
              value="<?= isset($_POST['phone']) ? $_POST['phone'] : "" ?>" name="phone" id="phone_number" type="tel">

            <br>
            <button type="submit" class="submit-btn">XÁC NHẬN</button>
          </div>
          <!-- <script>
            const phoneInput = document.getElementById('phone_number');
            phoneInput.addEventListener('keydown', function (event) {
              if (event.key === 'Backspace') {
                let value = event.target.value;

                const cursorPosition = phoneInput.selectionStart;

                if (
                  cursorPosition === 0 ||
                  value.charAt(cursorPosition - 1) === '_'
                ) {
                  value =
                    value.slice(0, cursorPosition - 1) +
                    value.slice(cursorPosition);
                  event.target.value = value;

                  event.preventDefault();
                }
              }
            });

            phoneInput.addEventListener('input', function (event) {
              let value = event.target.value;

              value = value.replace(/\D/g, '');

              if (value.length > 0 && value.charAt(0) !== '0') {
                value = '' + value.slice(1);
                console.log('number must start with number 0');
              }

              if (value.length > 10) {
                value = value.slice(0, 10);
              }

              // Format the value with underscores
              if (value.length >= 7) {
                value =
                  value.slice(0, 4) +
                  '_' +
                  value.slice(4, 7) +
                  '_' +
                  value.slice(7);
              } else if (value.length >= 4) {
                value = value.slice(0, 4) + '_' + value.slice(4);
              }

              event.target.value = value;
            });
          </script> -->
          <?php
        }
        ?>




        <!-- form verify code -->
        <?php
        if ($active == 'submitcode') { ?>
          <div class=" ve-form ">
            <div class="ve-title">OTP - Mã xác thực</div>
            <p class="ve-message">Vui lòng nhập mã code của bạn
            </p>
            <div class="ve-inputs" style="width:60%">
              <input id="input1" name="code_phone" style="width:100%" placeholder="Mã code...." type="number">
              <input id="input1" name="token" hidden value="<?= isset($_GET['token']) ? $_GET['token'] : "" ?>">

            </div>
            <!-- <button class="ve-action">Xác thực</button> -->
            <input type="submit" name="submit_check_code_phone" value="Xác thực" class="ve-action">
          </div>
          <div class="time-count">
            <div class="time-count-title">Mã xác nhận sẽ hết hạn trong:</div>
            <div class="time-count-body">60s</div>

          </div>
          <?php
          echo '<script>countTime(60)</script>';
        }

        ?>


        <!-- form change password -->
        <?php
        if ($active == 'changepassword') { ?>
          <div class=" ve-form ">
            <div class="ve-title">Thay đổi mật khẩu</div>
            </p>
            <div class="change-pass-body">
              <input required="" class="input" type="password" name="password" placeholder="Mật khẩu mới">
              <input required="" class="input" type="password" name="passwordconfirm" placeholder="Xác nhận lại mật khẩu">
              <input id="input1" name="token" hidden value="<?= isset($_GET['token']) ? $_GET['token'] : "" ?>">
            </div>
            <button type="submit" class="ve-action">Xác nhận</button>
          </div>
        <?php }
        ?>
        <!-- error form -->
        <?php
        if ($active == 'tokenerror') { ?>
          <div class=" ve-form ">
            <div class="ve-title"><?= $result_token->message ?></div>

          </div>
        <?php }
        ?>
        <!-- error form -->
        <?php
        if ($active == 'waiting-check-email') { ?>
          <div class=" ve-form ">
            <div class="ve-title">Vui lòng xác nhận email để thay đổi password</div>

          </div>
        <?php }
        ?>


        <div class="form-change">
          Bạn đã có tài khoản?
          <span><a href="?mod=profile&act=login">Đăng nhập</a></span>
        </div>
        <div class="l-back-home">
          <a href="./" class="home-btn">Trang chủ</a>
        </div>
      </form>
    </div>
  </div>



  <script src="./src/js/main.js" type="module"></script>
</body>

</html>