<?php
$isProductsExist = ($countpage["countPage"] <= 0) ? false : true;
function checkNullWithValue($valueInput, $opacity, $color)
{
    return (isset($valueInput) && !empty($valueInput)) ? $valueInput : "<div style='opacity: $opacity; color: $color;'>no data...</div>";
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
                    if (isset($_GET["act"]) && $_GET["act"] = "mn_all_shop") {
                        echo "<h2 style='margin-bottom: 24px;'>Tất cả Shop</h2>";
                    }
                    ?>
                    <div class="col-lg-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">

                                <div class="tab-pane fade show active" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                                    <div class="card-body">
                                        <h4 class="card-title">All Shop <strong>(<?= $countpage["countPage"] ?>)</strong></h4>
                                        <p class="card-description">
                                            Tất cả shop
                                        </p>
                                        <div class="table-responsive">
                                            <!-- table -->
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Email</th>
                                                        <th>Phone Number</th>
                                                        <th>Address</th>
                                                        <th>Created At</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    <?php
                                                    foreach ($shop as $value) {
                                                        extract($value);
                                                    ?>
                                                        <tr>
                                                            <td>
                                                                <a href="?mod=admin&act=detail_shop&status=new&sid=<?= $s_id ?>" style="display: flex; flex-direction: column; gap: 16px; text-decoration: none;">
                                                                    <?= checkNullWithValue($s_name, "60%", "red") ?>
                                                                    <img src="assest/upload/<?= $s_icon ?>" alt="">
                                                                </a>
                                                            </td>
                                                            <td><?= checkNullWithValue($u_email, "60%", "red") ?></td>
                                                            <td><?= checkNullWithValue($phone_number, "60%", "red") ?></td>
                                                            <td><?= checkNullWithValue(("$aw_ward - $ad_district - $ap_province"), "60%", "red") ?></td>
                                                            <td><?= $created ?></td>
                                                        </tr>
                                                    <?php
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- </div> -->

                                <!-- pagination  -->
                                <?php
                                $pageHTML = "";
                                $countPage = ceil(intval($countpage["countPage"]) / 5);
                                $page = isset($_GET["page"]) ? intval($_GET["page"]) : 1;
                                $roleParam = isset($_GET["xnxx"]) ? "&xnxx=" . $_GET["xnxx"] . "" : "";
                                $previousPage = $page > 1 ? $page - 1 : false;
                                $nextPage = $page + 1;

                                $previous = '<li class="page-item ' . ((($page == 1) || (!$isProductsExist)) ? "disabled" : "") . '" style="' . ((($page == 1) || (!$isProductsExist)) ? "cursor: not-allowed;" : "") . '"> <!--  disabled active class when needs -->
                                                <a class="page-link" href="?mod=admin&act=mn_all_shop' . $roleParam . '&page=' . $previousPage . '">Previous</a> <!-- add aria-disabled="true" when you want to disabled -->
                                            </li>';
                                $next = '<li class="page-item ' . ((($page == $countPage) || (!$isProductsExist)) ? "disabled" : "") . '" style="' . ((($page == $countPage) || (!$isProductsExist)) ? "cursor: not-allowed;" : "") . '">
                                            <a class="page-link" href="?mod=admin&act=mn_all_shop' . $roleParam . '&page=' . $nextPage . '">Next</a>
                                        </li>';
                                function paginationHTML($i, $isActive, $role = "")
                                {
                                    $roleParam = !empty($role) ? "&xnxx=$role" : "";
                                    return '
                            <li class="page-item ' . ($isActive ? 'active' : '') . '">
                                <a class="page-link" href="?mod=admin&act=mn_all_shop' . $roleParam . '&page=' . $i + 1 . '">' . $i + 1 . '</a>
                            </li>
                            ';
                                }

                                for ($i = 0; $i < $countPage; $i++) {
                                    $isActive = isset($_GET["page"]) && $_GET["page"] == ($i + 1);
                                    if (!isset($_GET["xnxx"])) {
                                        $pageHTML .= paginationHTML($i, $isActive);
                                    } else if (isset($_GET["xnxx"]) && $_GET["xnxx"]) {
                                        $pageHTML .= paginationHTML($i, $isActive, $_GET["xnxx"]);
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