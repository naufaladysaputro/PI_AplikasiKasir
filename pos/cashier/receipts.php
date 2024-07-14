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
        <div style="background-image: url(../admin/assets/img/theme/restro00.jpg); background-size: cover;" class="header  pb-8 pt-5 pt-md-8">
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
                Pesanan yang Telah Dibayar
            </div>
            <div class="table-responsive">
                <table class="table align-items-center table-flush">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-success" scope="col">Kode</th>
                            <th class="text-success" scope="col">Produk</th>
                            <th scope="col">Harga Satuan</th>
                            <th class="text-success" scope="col">Jumlah</th> <!-- Ubah nama kolom di sini -->
                            <th scope="col">Total Harga</th>
                            <th class="text-success" scope="col">Tanggal</th>
                            <th scope="col">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $ret = "SELECT * FROM  rpos_orders WHERE order_status = 'Paid' ORDER BY `rpos_orders`.`created_at` DESC";
                        $stmt = $mysqli->prepare($ret);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        while ($order = $res->fetch_object()) {
                            $total = ($order->prod_price * $order->prod_qty);
                        ?>
                            <tr>
                                <th class="text-success" scope="row"><?php echo $order->order_code; ?></th>
                                <td class="text-success"><?php echo $order->prod_name; ?></td>
                                <td>Rp <?php echo $order->prod_price; ?></td>
                                <td class="text-success"><?php echo $order->prod_qty; ?></td> <!-- Ubah nama kolom di sini -->
                                <td>Rp <?php echo $total; ?></td>
                                <td><?php echo date('d/M/Y g:i', strtotime($order->created_at)); ?></td>
                                <td>
                                    <a target="_blank" href="print_receipt.php?order_code=<?php echo $order->order_code; ?>">
                                        <button class="btn btn-sm btn-primary">
                                            <i class="fas fa-print"></i>
                                            Cetak Kuitansi
                                        </button>
                                    </a>
                                </td>
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
<!-- For more projects: Visit NetGO+  -->
</html>