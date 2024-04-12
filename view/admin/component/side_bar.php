<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">

    <li class="nav-item">
      <a class="nav-link" href="?mod=admin&act=dashboard">
        <i class="menu-icon mdi mdi-floor-plan"></i>
        <span class="menu-title">Dashboard</span>
      </a>
    </li>

    <li class="nav-item nav-category">Quản lí danh mục</li>
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
        <i class="mdi mdi-grid-large menu-icon"></i>
        <span class="menu-title">Danh mục</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="ui-basic">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="?mod=admin&act=mn_settings_cat">Cài đặt danh mục</a></li>
        </ul>
      </div>
    </li>

    <li class="nav-item nav-category">Quản lí đơn hàng</li>
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#order-elements" aria-expanded="false" aria-controls="order-elements">
        <i class="menu-icon mdi mdi-card-text-outline"></i>
        <span class="menu-title">Đơn hàng</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="order-elements">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"><a class="nav-link" href="?mod=admin&act=mn_all_order&status=All&page=1">Tất cả đơn hàng</a></li>
          <li class="nav-item"><a class="nav-link" href="?mod=admin&act=detail_order">Chi tiết đơn hàng</a></li>
        </ul>
      </div>
    </li>

    <li class="nav-item nav-category">Quản lí sản phẩm</li>
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#product-elements" aria-expanded="false" aria-controls="product-elements">
        <i class="menu-icon mdi mdi mdi-barcode"></i>
        <span class="menu-title">Sản phẩm</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="product-elements">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"><a class="nav-link" href="?mod=admin&act=mn_all_products&page=1">Tất cả sản phẩm</a></li>
        </ul>
      </div>
    </li>

    <li class="nav-item nav-category">Lịch sử giao dịch</li>
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#history-elements" aria-expanded="false" aria-controls="history-elements">
        <i class="menu-icon mdi mdi-account-circle-outline"></i>
        <span class="menu-title">Xem Lịch Sử</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="history-elements">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"><a class="nav-link" href="?mod=admin&act=mn_transaction&p_method=COD&page=1">Tất cả lịch sử</a></li>
        </ul>
      </div>
    </li>

    <li class="nav-item nav-category">Quản lí User</li>
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#user-elements" aria-expanded="false" aria-controls="user-elements">
        <i class="menu-icon mdi mdi-account-circle-outline"></i>
        <span class="menu-title">User</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="user-elements">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"><a class="nav-link" href="?mod=admin&act=mn_all_user&role=All&page=1">Tất cả user</a></li>
          <li class="nav-item"><a class="nav-link" href="?mod=admin&act=mn_user_detail">Chi tiết User</a></li>
        </ul>
      </div>
    </li>

    <li class="nav-item nav-category">Quản lí Shop</li>
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#shop-elements" aria-expanded="false" aria-controls="shop-elements">
        <i class="menu-icon mdi mdi-shopping"></i>
        <span class="menu-title">Shop</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="shop-elements">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"><a class="nav-link" href="?mod=admin&act=mn_all_shop&page=1">Tất cả Shop</a></li>
          <li class="nav-item"><a class="nav-link" href="?mod=admin&act=detail_shop">Chi tiết Shop</a></li>
        </ul>
      </div>
    </li>
  </ul>
</nav>