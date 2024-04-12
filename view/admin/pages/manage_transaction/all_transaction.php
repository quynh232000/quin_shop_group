<?php
$isProductsExist = ($countpages <= 0) ? false : true;
function checkNullWithValue($valueInput, $opacity, $color)
{
  return (isset($valueInput) && !empty($valueInput)) ? $valueInput : "<div style='opacity: $opacity; color: $color;'>no data...</div>";
}

?>

<div class="container-scroller">
  <!-- partial -->
  <div class="container-fluid page-body-wrapper">
    <!-- partial:../../partials/_settings-panel.html -->
    <!-- partial -->
    <!-- partial:../../partials/_sidebar.html -->
    <?php include_once "view/admin/component/side_bar.php"; ?>
    <!-- partial -->
    <div class="main-panel">
      <div class="content-wrapper">
        <div class="row">
          <?php
          if (isset($_GET["act"]) && $_GET["act"] = "mn_transaction") {
            echo "<h2 style='margin-bottom: 24px;'>Tất cả Lịch Sử Giao dịch</h2>";
          }
          ?>
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">

                <ul class="nav nav-tabs" id="myTab" role="tablist">
                  <li class="nav-item" role="presentation">
                    <button onclick="window.location.href = '?mod=admin&act=mn_transaction&p_method=COD&page=1'" class="nav-link <?= (isset($_GET["p_method"]) && $_GET["p_method"] == "COD") ? 'active" aria-selected="true' : '" aria-selected="false'  ?>" id="cod-tab" data-bs-toggle="tab" data-bs-target="#cod" type="button" role="tab" aria-controls="cod">COD <?= $_GET["p_method"] == "COD" ? "<strong>(" . $quantity . ")</strong>" : "" ?></button>
                  </li>
                  <li class="nav-item" role="presentation">
                    <button onclick="window.location.href = '?mod=admin&act=mn_transaction&p_method=Banking&page=1'" class="nav-link <?= (isset($_GET["p_method"]) && $_GET["p_method"] == "Banking") ? 'active" aria-selected="true' : '" aria-selected="false' ?>" aria-selected="true" id="bank-tab" data-bs-toggle="tab" data-bs-target="#bank" type="button" role="tab" aria-controls="bank">Banking <?= $_GET["p_method"] == "Banking" ? "<strong>(" . $quantity . ")</strong>" : "" ?></button>
                  </li>
                </ul>

                <div class="tab-content" id="myTabContent" style="border-radius: 0 16px 16px 16px; padding: 0;">

                  <!-- render issue  -->

                  <div class="tab-pane fade show active" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                    <div class="card-body">
                      <?php if ($_GET["p_method"] == "COD") {
                      ?>
                        <h4 class="card-title">COD</h4>
                        <p class="card-description">
                          Tất cả COD
                        </p>
                      <?php
                      } else {
                      ?>
                        <h4 class="card-title">Banking</h4>
                        <p class="card-description">
                          Tất cả Banking bao gồm thanh toán Momo
                        </p>
                      <?php
                      } ?>
                      <div class="table-responsive">
                        <!-- table -->
                        <table class="table table-bordered">
                          <thead>
                            <tr>
                              <th>No.</th>
                              <th>Order</th>
                              <th>Status</th>
                              <th>Total</th>
                              <th>Bank Code</th>
                              <th>Content</th>
                              <th>Created At</th>
                            </tr>
                          </thead>

                          <tbody>
                            <?php
                            function checkPMethod($cod, $ts)
                            {
                              if (isset($_GET["p_method"]) && $_GET["p_method"]) {
                                switch ($_GET["p_method"]) {
                                  case "COD":
                                    return $cod;
                                    break;
                                  case "Banking":
                                    return $ts;
                                    break;
                                }
                              }
                            }
                            foreach (checkPMethod($cod, $ts) as $value) {
                              extract($value);
                            ?>
                              <tr>
                                <td><?= !isset($_no) ? "..." : $_no ?></td>
                                <td><?= $order_id ?></td>
                                <td><?= checkNullWithValue($ts_status, "60%", "red") ?></td>
                                <td><?= checkNullWithValue(number_format($ts_total, 0, 0), "60%", "red") ?> VND</td>
                                <td><?= $ts_banK_code ?></td>
                                <td><?= checkNullWithValue($ts_content, "60%", "red") ?></td>
                                <td><?= $created ?></td>
                              </tr>
                            <?php } ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- pagination  -->
                <?php
                $pageHTML = "";
                foreach ($countpages as $value) {
                  extract($value);
                  if ($_GET["p_method"] == $name) {
                    $countPage = ceil(intval($count) / 5);
                  }
                }
                $page = isset($_GET["page"]) ? intval($_GET["page"]) : 1;
                $roleParam = isset($_GET["p_method"]) ? "&p_method=" . $_GET["p_method"] . "" : "";
                $previousPage = $page > 1 ? $page - 1 : false;
                $nextPage = $page + 1;

                $previous = '<li class="page-item ' . ((($page == 1) || (!$isProductsExist)) ? "disabled" : "") . '" style="' . ((($page == 1) || (!$isProductsExist)) ? "cursor: not-allowed;" : "") . '"> <!--  disabled active class when needs -->
                                                <a class="page-link" href="?mod=admin&act=mn_transaction' . $roleParam . '&page=' . $previousPage . '">Previous</a> <!-- add aria-disabled="true" when you want to disabled -->
                                            </li>';
                $next = '<li class="page-item ' . ((($page == $countPage) || (!$isProductsExist)) ? "disabled" : "") . '" style="' . ((($page == $countPage) || (!$isProductsExist)) ? "cursor: not-allowed;" : "") . '">
                                            <a class="page-link" href="?mod=admin&act=mn_transaction' . $roleParam . '&page=' . $nextPage . '">Next</a>
                                        </li>';
                function paginationHTML($i, $isActive, $role = "")
                {
                  $roleParam = !empty($role) ? "&p_method=$role" : "";
                  return '
                            <li class="page-item ' . ($isActive ? 'active' : '') . '">
                                <a class="page-link" href="?mod=admin&act=mn_transaction' . $roleParam . '&page=' . $i + 1 . '">' . $i + 1 . '</a>
                            </li>
                            ';
                }

                for ($i = 0; $i < $countPage; $i++) {
                  $isActive = isset($_GET["page"]) && $_GET["page"] == ($i + 1);
                  if (!isset($_GET["p_method"])) {
                    $pageHTML .= paginationHTML($i, $isActive);
                  } else if (isset($_GET["p_method"]) && $_GET["p_method"]) {
                    $pageHTML .= paginationHTML($i, $isActive, $_GET["p_method"]);
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
        <!-- content-wrapper ends -->
        <!-- partial:../../partials/_footer.html -->
        <?php include_once "view/admin/component/footer.php"; ?>
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>