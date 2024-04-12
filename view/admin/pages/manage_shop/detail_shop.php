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
                        if (isset($_GET["act"]) && $_GET["act"] = "detail_shop") {
                            if (isset($_GET["sid"]) && $_GET["sid"]) {
                                $id = $_GET["sid"];
                                echo "<h2 style='margin-bottom: 24px;'>Chi Tiết Shop, ID: $id</h2>";
                            } else {
                                echo "<h2 style='margin-bottom: 24px;'>Chi Tiết Shop</h2>";
                            }
                        }
                        ?>
                        <!-- input id or name or email or phone will search out the user after that header to that uid -->
                        <div class="col-lg-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <form action="" method="GET" class="input-group flex-nowrap">
                                        <input type="text" hidden name="mod" value="admin">
                                        <input type="text" hidden name="act" value="detail_shop">
                                        <input type="text" class="form-control" name="search" placeholder="search shop by email" aria-label="Username" aria-describedby="addon-wrapping">
                                        <button type="submit" class="input-group-text" id="addon-wrapping"><i class="mdi mdi-account-search"></i></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php
                    if ($detailShop != false) {
                        extract($detailShop);
                    ?>
                        <div id="content-user-detail" class="row">
                            <div class="col-lg-3 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <img src="assest/upload/<?= $s_icon ?>" style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; margin-bottom: 16px;" class="img-thumbnail" alt="...">
                                        <div class="form-group">
                                            <h2 for="exampleInputEmail3">Thông tin shop</h2>
                                        </div>
                                        <div style="margin-bottom: 16px;">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail3">Email:</label>
                                                        <strong><?= checkNullWithValue($u_email, "60%", "red") ?></strong>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail3">Phone number:</label>
                                                        <strong><?= checkNullWithValue($phone_number, "60%", "red") ?></strong>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail3">Shop Name:</label>
                                                        <strong><?= checkNullWithValue($s_name, "60%", "red") ?></strong>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail3">Created Date:</label>
                                                        <strong><?= $created ?></strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-body">
                                                <div id="printSum"></div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-9 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <div id="content-user-super-detail">

                                            <div class="btn-group" style="margin-bottom: 16px;">
                                                <button type="button" class="btn btn-primary">Status</button>
                                                <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" id="dropdownMenuSplitButton1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuSplitButton1">
                                                    <h3 class="dropdown-header bg-secondary">Tùy chọn trạng thái đơn hàng</h3>
                                                    <a class="dropdown-item" href="?mod=admin&act=detail_shop&status=new&sid=<?= $_GET["sid"] ?>">New</a>
                                                    <a class="dropdown-item" href="?mod=admin&act=detail_shop&status=completed&sid=<?= $_GET["sid"] ?>">Completed</a>
                                                    <a class="dropdown-item" href="?mod=admin&act=detail_shop&status=cancelled&sid=<?= $_GET["sid"] ?>">Cancelled</a>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item" target="_blank" href="?mod=admin&act=detail_order">Chi tiết đơn hàng</a>
                                                </div>
                                            </div>

                                            <ul class="nav nav-tabs" id="myTab" role="tablist">

                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link active" view-mode="7d" aria-selected="true" aria-selected="false" aria-selected="true" id="7d-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile">7d</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link" view-mode="30d" aria-selected="true" aria-selected="false" aria-selected="true" id="30d-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact">30d</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link" view-mode="12M" aria-selected="true" aria-selected="false" aria-selected="true" id="month-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact">12M</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link" view-mode="All" aria-selected="true" aria-selected="false" id="all-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home">All</button>
                                                </li>
                                            </ul>

                                            <div class="tab-content" id="myTabContent" style="border-radius: 0 16px 16px 16px; padding: 0;">
                                                <!-- render issue  -->

                                                <div class="tab-pane fade show active" id="7d" role="tabpanel" aria-labelledby="7d-tab">
                                                    <div class="card-body">
                                                        <?php
                                                        if ($_GET["status"] == "new") {
                                                            echo '<h5 class="card-title">Thống kê đơn hàng <strong class="text-primary">New</strong></h5>';
                                                        } else if ($_GET["status"] == "completed") {
                                                            echo '<h5 class="card-title">Thống kê đơn hàng <strong class="text-primary">Completed</strong></h5>';
                                                        } else {
                                                            echo '<h5 class="card-title">Thống kê đơn hàng <strong class="text-primary">Cancelled</strong></h5>';
                                                        }
                                                        ?>
                                                        <!-- get data by week month and year -->
                                                        <div class="col-lg-12 grid-margin stretch-card">
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <canvas id="barChart"></canvas>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <div class="table-responsive">
                                                                    <table class="table table-bordered">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>STT</th>
                                                                                <th>Ngày đặt</th>
                                                                                <th>Số lượng</th>
                                                                                <th>Doanh thu</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody id="content-revenue"></tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    <?php } else {
                        echo "<h2 style='color: #6B778C; text-align: center;'>Not found...</h2>";
                    } ?>
                    <!-- content-wrapper ends -->
                    <?php include_once "view/admin/component/footer.php"; ?>
                    <!-- partial -->
                </div>
                <!-- main-panel ends -->
            </div>
            <!-- page-body-wrapper ends -->
        </div>
    </div>
</div>