<div class="container-scroller">
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
        <!-- partial -->
        <?php include_once "view/admin/component/side_bar.php"; ?>
        <!-- partial -->
        <div class="main-panel">
            <div class="content-wrapper">
                <?php
                if (isset($_GET["act"]) && $_GET["act"] = "mn_settings_cat") {
                   
                    echo "<h2 style='margin-bottom: 24px;'>Thiết Lập Category</h2>";
                }
                ?>
                <?php include_once "view/admin/component/treeview/treeViewUI.php"; ?>
            </div>
            <!-- content-wrapper ends -->
            <?php include_once "view/admin/component/footer.php" ?>
            <!-- partial -->
        </div>
        <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
</div>