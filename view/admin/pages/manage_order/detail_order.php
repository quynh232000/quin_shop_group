<?php
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
                    <div class="row">
                        <?php
                        if (isset($_GET["act"]) && $_GET["act"] = "detail_order") {
                            if (isset($_GET["oid"]) && $_GET["oid"]) {
                                $id = $_GET["oid"];
                                echo "<h2 style='margin-bottom: 24px;'>Chi Tiết Order, ID: $id</h2>";
                            } else {
                                echo "<h2 style='margin-bottom: 24px;'>Chi Tiết Order</h2>";
                            }
                        }
                        ?>
                        <!-- input id or name or email or phone will search out the user after that header to that uid -->
                        <div class="col-lg-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <form action="" method="GET" class="input-group flex-nowrap">
                                        <input type="text" hidden name="mod" value="admin">
                                        <input type="text" hidden name="act" value="detail_order">
                                        <input type="text" class="form-control" name="search" placeholder="search order by order ID" aria-label="Username" aria-describedby="addon-wrapping">
                                        <button type="submit" class="input-group-text" id="addon-wrapping"><i class="mdi mdi-account-search"></i></button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Product ID</th>
                                                    <th>Product Name</th>
                                                    <th>Quantity</th>
                                                    <th>Price</th>
                                                    <th>Created</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if ($orderDetail != false) {
                                                    foreach ($orderDetail as $value) {
                                                        extract($value);
                                                ?>
                                                        <tr>
                                                            <td><?= $id ?></td>
                                                            <td>
                                                                <div style="display: flex; flex-direction: column; gap: 16px;">
                                                                    <?= $name ?>
                                                                    <img src="./assest/upload/<?= $image_cover ?>" alt="">
                                                                </div>
                                                            </td>
                                                            <td><?= $quantity ?></td>
                                                            <td><?= number_format($price, 0, 0) ?></td>
                                                            <td><?= $created_at ?></td>
                                                        </tr>
                                                <?php }
                                                } else {
                                                    echo "<h2 style='color: #6B778C; text-align: center;'>Not found...</h2>";
                                                } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- content-wrapper ends -->
                    <?php include_once "view/admin/component/footer.php"; ?>
                    <!-- partial -->
                </div>
                <!-- main-panel ends -->
            </div>
            <!-- page-body-wrapper ends -->
        </div>