<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();
require_once('partials/_head.php');
?>

<body>
    <!-- Sidenav -->
    <?php
    require_once('partials/_sidebar.php');
    ?>
    <!-- Main content -->
    <div class="main-content">
        <!-- Top navbar -->
        <?php
        require_once('partials/_topnav.php');
        ?>
        <!-- Header -->
        <div style="background-image: url(../admin/assets/img/theme/restro00.jpg); background-size: cover;" class="header pb-8 pt-5 pt-md-8">
            <span class="mask bg-gradient-dark opacity-8"></span>
            <div class="container-fluid">
                <div class="header-body">
                </div>
            </div>
        </div>
        <!-- Page content -->
        <div class="container-fluid mt--8">
            <!-- Table -->
            <div class="row">
                <div class="col">
                    <div class="card shadow">
                        <div class="card-header border-0">
                            Catatan Pesanan
                        </div>

                        <div class="card-header border-0">
                            <form method="post">
                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <label for="dari_tgl">Dari tanggal</label>
                                        <input type="date" id="dari_tgl" name="dari_tgl" class="form-control" required>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="sampai_tgl">Sampai tanggal</label>
                                        <input type="date" id="sampai_tgl" name="sampai_tgl" class="form-control" required>
                                    </div>
                                    <div class="form-group col-md-2 align-self-end">
                                        <button type="submit" name="filter" class="btn btn-primary">Filter</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="table-responsive">
                            <table class="table align-items-center table-flush">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-success" scope="col">Kode</th>
                                        <th class="text-success" scope="col">Produk</th>
                                        <th scope="col">Harga Satuan</th>
                                        <th class="text-success" scope="col">Jumlah</th>
                                        <th scope="col">Total Harga</th>
                                        <th scope="col">Status</th>
                                        <th class="text-success" scope="col">Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (isset($_POST['filter'])) {
                                        $dari_tgl = $_POST['dari_tgl'];
                                        $sampai_tgl = $_POST['sampai_tgl'];

                                        $ret = "SELECT * FROM rpos_orders WHERE created_at BETWEEN ? AND ? ORDER BY created_at DESC";
                                        $stmt = $mysqli->prepare($ret);
                                        $stmt->bind_param('ss', $dari_tgl, $sampai_tgl);
                                    } else {
                                        $ret = "SELECT * FROM rpos_orders ORDER BY created_at DESC";
                                        $stmt = $mysqli->prepare($ret);
                                    }

                                    $stmt->execute();
                                    $res = $stmt->get_result();
                                    while ($order = $res->fetch_object()) {
                                        // Konversi nilai ke float untuk memastikan perhitungan yang benar
                                        $prod_price = floatval($order->prod_price);
                                        $prod_qty = floatval($order->prod_qty);

                                        // Hitung total
                                        $total = $prod_price * $prod_qty;
                                    ?>
                                        <tr>
                                            <th class="text-success" scope="row"><?php echo $order->order_code; ?></th>
                                            <td class="text-success"><?php echo $order->prod_name; ?></td>
                                            <td>Rp. <?php echo number_format($prod_price, 2, ',', '.'); ?></td>
                                            <td class="text-success"><?php echo $prod_qty; ?></td>
                                            <td>Rp. <?php echo number_format($total, 2, ',', '.'); ?></td>
                                            <td><?php if ($order->order_status == '') {
                                                    echo "<span class='badge badge-danger'>Not Paid</span>";
                                                } else {
                                                    echo "<span class='badge badge-success'>$order->order_status</span>";
                                                } ?></td>
                                            <td class="text-success"><?php echo date('d/M/Y g:i', strtotime($order->created_at)); ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <?php
            require_once('partials/_footer.php');
            ?>
        </div>
    </div>
    <!-- Argon Scripts -->
    <?php
    require_once('partials/_scripts.php');
    ?>
</body>

</html>
