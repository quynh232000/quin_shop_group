<?php
$isProductsExist = ($countpages["countPage"] <= 0) ? false : true;
function checkNullWithValue($valueInput, $opacity, $color)
{
  return (isset($valueInput) && !empty($valueInput)) ? $valueInput : "<div style='opacity: $opacity; color: $color;'>no data...</div>";
}

function rememberStateOfStatus($order_status)
{
  $order_status = strtolower($order_status);
  switch ($order_status) {
    case "new":
      return array("badge" => "badge-danger", "progress" => "bg-danger", "percent" => "0%");
    case "processing":
      return array("badge" => "badge-dark", "progress" => "bg-dark", "percent" => "25%");
    case "confirmed":
      return array("badge" => "badge-warning", "progress" => "bg-warning", "percent" => "50%");
    case "on_delivery":
      return array("badge" => "badge-primary", "progress" => "bg-primary", "percent" => "75%");
    case "completed":
      return array("badge" => "badge-success", "progress" => "bg-success", "percent" => "100%");
    case "cancelled":
      return array("badge" => "badge-danger", "progress" => "bg-danger", "percent" => "0%");
  }
}
?>
<div class="container-scroller">
  <!-- partial -->
  <div class="container-fluid page-body-wrapper">
    <!-- partial -->
    <?php include_once "view/admin/component/side_bar.php"; ?>
    <!-- partial -->
    <div class="main-panel">
      <div class="content-wrapper">
        <div class="row">
          <?php
          if (isset($_GET["act"]) && $_GET["act"] = "mn_all_order") {
            echo "<h2 style='margin-bottom: 24px;'>Tất cả Order</h2>";
          }
          ?>
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">

                <ul class="nav nav-tabs" id="myTab" role="tablist">
                  <li class="nav-item" role="presentation">
                    <button onclick="window.location.href = '?mod=admin&act=mn_all_order&status=All&page=1'" class="nav-link <?= (isset($_GET["status"]) && $_GET["status"] == "All") ? 'active" aria-selected="true' : '" aria-selected="false'  ?>" id="All-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home">All <?= $_GET["status"] == "All" ? "<strong>(" . $countpages["countPage"] . ")</strong>" : "" ?></button>
                  </li>
                  <li class="nav-item" role="presentation">
                    <button onclick="window.location.href = '?mod=admin&act=mn_all_order&status=New&page=1'" class="nav-link <?= (isset($_GET["status"]) && $_GET["status"] == "New") ? 'active" aria-selected="true' : '" aria-selected="false' ?>" aria-selected="true" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile">New <?= $_GET["status"] == "New" ? "<strong>(" . $countpages["countPage"] . ")</strong>" : "" ?></button>
                  </li>
                  <li class="nav-item" role="presentation">
                    <button onclick="window.location.href = '?mod=admin&act=mn_all_order&status=Processing&page=1'" class="nav-link <?= (isset($_GET["status"]) && $_GET["status"] == "Processing") ? 'active" aria-selected="true' : '" aria-selected="false' ?>" aria-selected="true" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact">Processing <?= $_GET["status"] == "Processing" ? "<strong>(" . $countpages["countPage"] . ")</strong>" : "" ?></button>
                  </li>
                  <li class="nav-item" role="presentation">
                    <button onclick="window.location.href = '?mod=admin&act=mn_all_order&status=Confirmed&page=1'" class="nav-link <?= (isset($_GET["status"]) && $_GET["status"] == "Confirmed") ? 'active" aria-selected="true' : '" aria-selected="false' ?>" aria-selected="true" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact">Confirmed <?= $_GET["status"] == "Confirmed" ? "<strong>(" . $countpages["countPage"] . ")</strong>" : "" ?></button>
                  </li>
                  <li class="nav-item" role="presentation">
                    <button onclick="window.location.href = '?mod=admin&act=mn_all_order&status=Completed&page=1'" class="nav-link <?= (isset($_GET["status"]) && $_GET["status"] == "Completed") ? 'active" aria-selected="true' : '" aria-selected="false' ?>" aria-selected="true" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact">Completed <?= $_GET["status"] == "Completed" ? "<strong>(" . $countpages["countPage"] . ")</strong>" : "" ?></button>
                  </li>
                  <li class="nav-item" role="presentation">
                    <button onclick="window.location.href = '?mod=admin&act=mn_all_order&status=Cancelled&page=1'" class="nav-link <?= (isset($_GET["status"]) && $_GET["status"] == "Cancelled") ? 'active" aria-selected="true' : '" aria-selected="false' ?>" aria-selected="true" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact">Cancelled <?= $_GET["status"] == "Cancelled" ? "<strong>(" . $countpages["countPage"] . ")</strong>" : "" ?></button>
                  </li>
                </ul>

                <div class="tab-content" id="nav-tabContent">
                  <div class="tab-pane fade show active" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                    <h4 class="card-title">All Users</h4>
                    <p class="card-description">
                      Tất cả users
                    </p>
                    <div class="table-responsive">
                      <!-- table -->
                      <table class="table table-bordered">
                        <thead>
                          <tr>
                            <th>Order ID</th>
                            <th>Shop Owner</th>
                            <th>Status</th>
                            <th>Progress</th>
                            <th>Total</th>
                            <th>Buyer</th>
                            <th>Delivery to</th>
                            <th>Created</th>
                          </tr>
                        </thead>

                        <tbody>
                          <?php
                          if ($orders && $orders != null) {
                            foreach ($orders as $value) {
                              extract($value);
                          ?>
                              <tr>
                                <td><a style="text-decoration: none;" href="?mod=admin&act=detail_order&oid=<?= $order_id ?>"><?= $order_id ?></a></td>
                                <td>
                                  <a style="text-decoration: none;" href="?mod=admin&act=detail_shop&status=new&sid=<?= $shop_id ?>">
                                    <div class="d-flex flex-column gap-3"><?= $shop_name ?>
                                      <img src="assest/upload/<?= $shop_icon ?>" alt="">
                                    </div>
                                  </a>
                                </td>
                                <td>
                                  <div class="badge <?= rememberStateOfStatus($order_status)["badge"] ?>"><?= $order_status ?></div>
                                </td>
                                <td>
                                  <div class="progress">
                                    <div class="progress-bar <?= rememberStateOfStatus($order_status)["progress"] ?>" role="progressbar" style="width: <?= rememberStateOfStatus($order_status)["percent"] ?>" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                  </div>
                                </td>
                                <td><?= checkNullWithValue(number_format($order_total, 0, 0), "60%", "red") ?></td>
                                <td>
                                  <a style="text-decoration: none;" href="?mod=admin&act=mn_user_detail&uid=<?= $buyer_id ?>">
                                    <div class="d-flex flex-column gap-2 py-2"><?= $buyer_name ?>
                                      <img style="cursor: pointer;" class="shop-owner" src="assest/upload/<?= $buyer_avatar ?>" alt="">
                                    </div>
                                  </a>
                                </td>

                                <td><?= $aw_ward . " - " . $ad_district . " - " . $ap_province ?></td>
                                <td><?= $createdAt ?></td>

                              </tr>
                          <?php }
                          } else {
                            echo "<h3 style='color: red;'>No record...</h3>";
                          } ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>

                <!-- pagination  -->
                <?php
                $pageHTML = "";
                $countPage = ceil(intval($countpages["countPage"]) / 5);
                $page = isset($_GET["page"]) ? intval($_GET["page"]) : 1;
                $roleParam = isset($_GET["status"]) ? "&status=" . $_GET["status"] . "" : "";
                $previousPage = $page > 1 ? $page - 1 : false;
                $nextPage = $page + 1;

                $previous = '<li class="page-item ' . ((($page == 1) || (!$isProductsExist)) ? "disabled" : "") . '" style="' . ((($page == 1) || (!$isProductsExist)) ? "cursor: not-allowed;" : "") . '"> <!--  disabled active class when needs -->
                                                <a class="page-link" href="?mod=admin&act=mn_all_order' . $roleParam . '&page=' . $previousPage . '">Previous</a> <!-- add aria-disabled="true" when you want to disabled -->
                                            </li>';
                $next = '<li class="page-item ' . ((($page == $countPage) || (!$isProductsExist)) ? "disabled" : "") . '" style="' . ((($page == $countPage) || (!$isProductsExist)) ? "cursor: not-allowed;" : "") . '">
                                            <a class="page-link" href="?mod=admin&act=mn_all_order' . $roleParam . '&page=' . $nextPage . '">Next</a>
                                        </li>';
                function paginationHTML($i, $isActive, $role = "")
                {
                  $roleParam = !empty($role) ? "&status=$role" : "";
                  return '
                            <li class="page-item ' . ($isActive ? 'active' : '') . '">
                                <a class="page-link" href="?mod=admin&act=mn_all_order' . $roleParam . '&page=' . $i + 1 . '">' . $i + 1 . '</a>
                            </li>
                            ';
                }

                for ($i = 0; $i < $countPage; $i++) {
                  $isActive = isset($_GET["page"]) && $_GET["page"] == ($i + 1);
                  if (!isset($_GET["status"])) {
                    $pageHTML .= paginationHTML($i, $isActive);
                  } else if (isset($_GET["status"]) && $_GET["status"]) {
                    $pageHTML .= paginationHTML($i, $isActive, $_GET["status"]);
                  }
                }

                $paginationFinal = $previous . $pageHTML . $next;

                ?>

                <nav aria-label="..." style="margin-top: 24px;">
                  <ul class="pagination">
                    <?= $paginationFinal ?>
                  </ul>
                </nav>
                <!-- pagination  -->

              </div>
            </div>
          </div>

        </div>
      </div>
      <!-- content-wrapper ends -->
      <?php include_once "view/admin/component/footer.php" ?>
      <!-- partial -->
    </div>
    <!-- main-panel ends -->
  </div>
  <!-- page-body-wrapper ends -->
</div>