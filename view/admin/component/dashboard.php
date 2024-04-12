<div class="container-scroller">
  <!-- partial -->
  <div class="container-fluid page-body-wrapper">
    <!-- partial -->
    <?php include_once "view/admin/component/side_bar.php"; ?>
    <!-- partial -->
    <div class="main-panel">
      <div class="content-wrapper">
        <div class="row">
          <div class="col-sm-12">
            <div class="home-tab">

              <div class="tab-content tab-content-basic">
                <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="statistics-details d-flex align-items-center justify-content-between">

                        <div>
                          <p class="statistics-title">Tổng lưu lượng truy cập</p>
                          <h3 class="rate-percentage"><?= $generalInfo["traffic_count"] ?></h3>
                        </div>
                        <div>
                          <p class="statistics-title">Tổng số User</p>
                          <h3 class="rate-percentage"><?= $generalInfo["count_user"] ?></h3>
                        </div>
                        <div>
                          <p class="statistics-title">Tổng sản phẩm lưu hành</p>
                          <h3 class="rate-percentage"><?= $generalInfo["all_products"] ?></h3>
                        </div>
                        <div class="d-none d-md-block">
                          <p class="statistics-title">Tổng số Seller</p>
                          <h3 class="rate-percentage"><?= $generalInfo["count_seller"] ?></h3>
                        </div>
                        <div class="d-none d-md-block">
                          <p class="statistics-title">Tổng đơn hàng thành công</p>
                          <h3 class="rate-percentage"><?= $generalInfo["new_completed_order"] ?></h3>
                        </div>
                        <div class="d-none d-md-block">
                          <p class="statistics-title">Tổng đơn hàng bị hủy</p>
                          <h3 class="rate-percentage"><?= $generalInfo["new_cancelled_order"] ?></h3>
                        </div>

                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-10 d-flex flex-column">
                      <div class="row flex-grow">
                        <div class="col-12 col-lg-12 col-lg-12 grid-margin stretch-card">
                          <div class="card card-rounded">
                            <div class="card-body">
                              <div class="d-sm-flex justify-content-between align-items-start">
                                <div>
                                  <h4 class="card-title card-title-dash">Báo cáo doanh thu tất cả</h4>
                                </div>
                                <div id="performance-line-legend"></div>
                              </div>
                              <div class="chartjs-wrapper mt-5">
                                <canvas id="performaneLine"></canvas>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-2 d-flex flex-column">
                      <div class="row flex-grow">
                        <div class="col-md-6 col-lg-12 grid-margin stretch-card">
                          <div class="card bg-primary card-rounded">
                            <div class="card-body pb-0">
                              <h4 class="card-title card-title-dash text-white mb-4">Tổng Doanh Thu tất cả</h4>
                              <div class="row">
                                <div class="">
                                  <p class="status-summary-ight-white mb-1">Doanh thu tạm tính</p>
                                  <h2 class="text-info"><?= number_format($generalInfo["revenue_all"], 0, 0) ?> VND</h2>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">

                      <div class="col-lg-12 d-flex flex-column">
                        <div class="row flex-grow">
                          <div class="col-12 grid-margin stretch-card">
                            <div class="card card-rounded">
                              <div class="card-body">
                                <div class="d-sm-flex justify-content-between align-items-start">
                                  <div>
                                    <h4 class="card-title card-title-dash">Đơn hàng mới</h4>
                                    <p class="card-subtitle card-subtitle-dash">Bạn có đơn hàng mới trong hôm nay</p>
                                  </div>
                                  <div>
                                    <a href="?mod=admin&act=mn_all_order&status=New&page=1" class="btn btn-primary btn-lg text-white mb-0 me-0" type="button">See more</a>
                                  </div>
                                </div>
                                <div class="table-responsive  mt-1">
                                  <table class="table select-table">
                                    <thead>

                                      <tr>
                                        <th>STT</th>
                                        <th>payment_method</th>
                                        <th>Total</th>
                                        <th>Created</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <?php
                                        if ($newestOrder != false) {
                                          foreach ($newestOrder as $key => $value) {
                                            extract($value);
                                            ?>
                                            <tr>
                                            <td><?= $key + 1 ?></td>
                                            <td>
                                              <h6><?= $payment_method ?></h6>
                                            </td>
                                            <td>
                                              <h6><?= number_format($total, 0, 0) ?></h6>
                                            </td>
                                            <td>
                                              <h6><?= $created_at ?></h6>
                                            </td>
                                            </tr>
                                        <?php }
                                        } else {
                                          echo "<td><h6 style='color: red;'>No record...</h6></td>";
                                        } ?>
                                    </tbody>
                                  </table>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="col-lg-12 d-flex flex-column">
                        <div class="row flex-grow">
                          <div class="col-12 grid-margin stretch-card">
                            <div class="card card-rounded">
                              <div class="card-body">
                                <div class="d-sm-flex justify-content-between align-items-start">
                                  <div>
                                    <h4 class="card-title card-title-dash">Sản phẩm cần duyệt</h4>
                                    <p class="card-subtitle card-subtitle-dash">Bạn có sản phẩm cần duyệt trong hôm nay</p>
                                  </div>
                                  <div>
                                    <a href="?mod=admin&act=mn_all_products&status=New&page=1" class="btn btn-primary btn-lg text-white mb-0 me-0" type="button">See more</a>
                                  </div>
                                </div>
                                <div class="table-responsive  mt-1">
                                  <table class="table select-table">
                                    <thead>
                                      <tr>
                                        <th>STT</th>
                                        <th>Name</th>
                                        <th>Price</th>
                                        <th>Status</th>
                                        <th>Brand</th>
                                        <th>Origin</th>
                                        <th>Created</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <?php
                                        if ($newestProduct != false) {
                                          foreach ($newestProduct as $key => $value) {
                                            extract($value);
                                            ?>
                                            <tr>
                                            <td><?= $key + 1 ?></td>
                                            <td>
                                              <h6><?= $name ?></h6>
                                            </td>
                                            <td>
                                              <h6><?= $price ?></h6>
                                            </td>
                                            <td>
                                              <h6><?= $status ?></h6>
                                            </td>
                                            <td>
                                              <h6><?= $brand ?></h6>
                                            </td>
                                            <td>
                                              <h6><?= $origin ?></h6>
                                            </td>
                                            <td>
                                              <h6><?= $created_at ?></h6>
                                            </td>
                                            </tr>
                                        <?php }
                                        } else {
                                          echo "<td><h6 style='color: red;'>No record...</h6></td>";
                                        } ?>
                                    </tbody>
                                  </table>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="col-lg-12 d-flex flex-column">
                        <div class="row flex-grow">
                          <div class="col-12 grid-margin stretch-card">
                            <div class="card card-rounded">
                              <div class="card-body">
                                <div class="d-sm-flex justify-content-between align-items-start">
                                  <div>
                                    <h4 class="card-title card-title-dash">User mới</h4>
                                    <p class="card-subtitle card-subtitle-dash">Bạn có User trong hôm nay</p>
                                  </div>
                                  <div>
                                    <a href="?mod=admin&act=mn_all_user&role=All&page=1" class="btn btn-primary btn-lg text-white mb-0 me-0" type="button">See more</a>
                                  </div>
                                </div>
                                <div class="table-responsive  mt-1">
                                  <table class="table select-table">
                                    <thead>
                                      <tr>
                                        <th>STT</th>
                                        <th>full name</th>
                                        <th>email</th>
                                        <th>phone number</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <?php
                                        if ($newestUser != false) {
                                          foreach ($newestUser as $key => $value) {
                                            extract($value);
                                            ?>
                                            <tr>
                                            <td><?= $key + 1 ?></td>
                                            <td>
                                              <h6><?= $full_name ?></h6>
                                            </td>
                                            <td>
                                              <h6><?= $email ?></h6>
                                            </td>
                                            <td>
                                              <h6><?= $phone_number ?></h6>
                                            </td>
                                            <td>
                                              <h6><?= $created_at ?></h6>
                                            </td>
                                            </tr>
                                        <?php }
                                        } else {
                                          echo "<td><h6 style='color: red;'>No record...</h6></td>";
                                        } ?>
                                    </tbody>
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
  <!-- container-scroller -->