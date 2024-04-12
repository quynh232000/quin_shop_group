<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Star Admin2 </title>
  <!-- admin  -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <!-- admin end  -->

  <!-- modal  -->
  <link rel="stylesheet" href="view/admin/component/treeview/style.css">
  <!-- end modal  -->

  <!-- snackbar  -->
  <link rel="stylesheet" href="view/admin/component/snackbar/snackbar.css">
  <link rel="stylesheet" href="src/css/admin/style.css">
  <!-- end snackbar  -->

  <!-- user detail  -->
  <link rel="stylesheet" href="src/css/admin/user_detail.css">

  <!-- skeleton loading  -->
  <link rel="stylesheet" href="view/admin/component/skeleton-loading/style.css">
  <!-- end skeleton loading  -->

  <!-- table expandable -->
  <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"> -->
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  <!-- end table expandable -->

  <!-- plugins:css -->
  <link rel="stylesheet" href="view/admin/vendors/feather/feather.css">
  <link rel="stylesheet" href="view/admin/vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="view/admin/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="view/admin/vendors/typicons/typicons.css">
  <link rel="stylesheet" href="view/admin/vendors/simple-line-icons/css/simple-line-icons.css">
  <link rel="stylesheet" href="view/admin/vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <link rel="stylesheet" href="view/admin/vendors/datatables.net-bs4/dataTables.bootstrap4.css">
  <link rel="stylesheet" href="view/admin/js/select.dataTables.min.css">
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="view/admin/css/vertical-layout-light/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="view/admin/images/favicon.png" />
</head>

<body>

  <!-- partial:partials/_navbar.html -->
  <nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
      <div class="me-3">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
          <span class="icon-menu"></span>
        </button>
      </div>
      <div>
        <a class="navbar-brand brand-logo" href="?mod=admin&act=dashboard">
          <img src="assest/images/UNIDI_LOGO-FINAL 2.svg" alt="logo" />
        </a>
        <a class="navbar-brand brand-logo-mini" href="?mod=admin&act=dashboard">
          <img src="assest/images/UNIDI_LOGO-FINAL 2.svg" alt="logo" />
        </a>
      </div>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-top">
      <ul class="navbar-nav">
        <li class="nav-item font-weight-semibold d-none d-lg-block ms-0">
          <h1 class="welcome-text">Xin chào, <span class="text-black fw-bold"><?= Session::get('full_name') ?></span></h1>
          <h5>Báo cáo tổng quát</h5>
        </li>
      </ul>

      <ul class="navbar-nav ms-auto">

        <li class="nav-item dropdown d-none d-lg-block user-dropdown">
          <a class="nav-link" id="UserDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
            <img class="img-xs rounded-circle" src="<?= 'assest/upload/' . Session::get('avatar') ?>" alt="Profile image"> </a>
          <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
            <div class="dropdown-header text-center">
              <img class="img-md rounded-circle" style="width:32px;height:32px" src="<?= 'assest/upload/' . Session::get('avatar') ?>" alt="Profile image">
              <p class="mb-1 mt-3 font-weight-semibold"><?= Session::get('full_name') ?></p>
              <p class="fw-light text-muted mb-0"><?= Session::get('email') ?></p>
            </div>
            <a href="?mod=profile&act=profile" class="dropdown-item"><i class="dropdown-item-icon mdi mdi-account-outline text-primary me-2"></i> My Profile <span class="badge badge-pill badge-danger">1</span></a>
            <a class="dropdown-item"><i class="dropdown-item-icon mdi mdi-power text-primary me-2"></i>Sign Out</a>
          </div>
        </li>

      </ul>
      <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-bs-toggle="offcanvas">
        <span class="mdi mdi-menu"></span>
      </button>
    </div>
  </nav>