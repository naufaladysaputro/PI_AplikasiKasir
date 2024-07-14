<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();
require_once('partials/_head.php');

// Handle form submission for date filter
$dari_tgl = "";
$sampai_tgl = "";
if (isset($_POST['filter'])) {
    $dari_tgl = $_POST['dari_tgl'];
    $sampai_tgl = $_POST['sampai_tgl'];
}
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
                            Laporan Pembayaran
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
                                        <th class="text-success" scope="col">Kode Pembayaran</th>
                                        <th scope="col">Cara Pembayaran</th>
                                        <th class="text-success" scope="col">Kode Pemesanan</th>
                                        <th scope="col">Jumlah yang dibayar</th>
                                        <th class="text-success" scope="col">Tanggal Dibayar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Adjust query based on filter dates
                                    if (!empty($dari_tgl) && !empty($sampai_tgl)) {
                                        $ret = "SELECT * FROM rpos_payments WHERE DATE(created_at) BETWEEN ? AND ? ORDER BY created_at DESC";
                                        $stmt = $mysqli->prepare($ret);
                                        $stmt->bind_param('ss', $dari_tgl, $sampai_tgl);
                                    } else {
                                        $ret = "SELECT * FROM rpos_payments ORDER BY created_at DESC";
                                        $stmt = $mysqli->prepare($ret);
                                    }

                                    $stmt->execute();
                                    $res = $stmt->get_result();
                                    while ($payment = $res->fetch_object()) {
                                    ?>
                                        <tr>
                                            <th class="text-success" scope="row">
                                                <?php echo $payment->pay_code; ?>
                                            </th>
                                            <th scope="row">
                                                <?php echo $payment->pay_method; ?>
                                            </th>
                                            <td class="text-success">
                                                <?php echo $payment->order_code; ?>
                                            </td>
                                            <td>
                                                Rp <?php echo $payment->pay_amt; ?>
                                            </td>
                                            <td class="text-success">
                                                <?php echo date('d/M/Y g:i', strtotime($payment->created_at)) ?>
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

</html>
