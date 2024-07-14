<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Start your development with a Dashboard for Bootstrap 4.">
    <meta name="author" content="MartDevelopers Inc">
    <title>Restaurant Point Of Sale</title>
    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="../admin/assets/img/icons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../admin/assets/img/icons/simbolmieayam.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../admin/assets/img/icons/simbolmieayam.png">
    <link rel="manifest" href="../admin/assets/img/icons/site.webmanifest">
    <link rel="mask-icon" href="../admin/assets/img/icons/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <link href="assets/css/bootstrap.css" rel="stylesheet" id="bootstrap-css">
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/jquery.js"></script>
    <style>
        body {
            margin-top: 20px;
        }
    </style>
</head>

<?php
$order_code = $_GET['order_code'];
$ret = "SELECT * FROM rpos_orders WHERE order_code = '$order_code'";
$stmt = $mysqli->prepare($ret);
$stmt->execute();
$res = $stmt->get_result();

$orders = [];
$total_amount = 0;
while ($order = $res->fetch_object()) {
    $orders[] = $order;
    $total_amount += ($order->prod_price * $order->prod_qty);
}

$tax_rate = 0.14; // 14% tax rate
$tax_amount = $total_amount * $tax_rate;
$grand_total = $total_amount + $tax_amount;
?>

<body>
    <div class="container">
        <div class="row">
            <div id="Receipt" class="well col-xs-10 col-sm-10 col-md-6 col-xs-offset-1 col-sm-offset-1 col-md-offset-3">
                <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <address>
                            <strong>Mie Ayam Bangka "AFI"</strong>
                            <br>
                            Jl. Harapan Indah Raya FB No. 59, RT.004/RW.017
                            <br>
                            08.00 - 15.00
                            <br>
                            (+62) 337-337-3069
                        </address>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6 text-right">
                        <p>
                            <em>Tanggal: <?php echo date('d/M/Y g:i', strtotime($orders[0]->created_at)); ?></em>
                        </p>
                        <p>
                            <em class="text-success">Kuitansi #: <?php echo $orders[0]->order_code; ?></em>
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="text-center">
                        <h2>Kuitansi</h2>
                    </div>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Kuantitas</th>
                                <th class="text-center">Harga satuan</th>
                                <th class="text-center">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order) : ?>
                                <tr>
                                    <td class="col-md-9"><em><?php echo $order->prod_name; ?></em></td>
                                    <td class="col-md-1" style="text-align: center"><?php echo $order->prod_qty; ?></td>
                                    <td class="col-md-1 text-center">Rp<?php echo $order->prod_price; ?></td>
                                    <td class="col-md-1 text-center">Rp<?php echo ($order->prod_price * $order->prod_qty); ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <tr>
                                <td>   </td>
                                <td>   </td>
                                <td class="text-right">
                                    <p><strong>Subtotal: </strong></p>
                                    <p><strong>Pajak (14%): </strong></p>
                                </td>
                                <td class="text-center">
                                    <p><strong>Rp<?php echo $total_amount; ?></strong></p>
                                    <p><strong>Rp<?php echo number_format($tax_amount, 2); ?></strong></p>
                                </td>
                            </tr>
                            <tr>
                                <td>   </td>
                                <td>   </td>
                                <td class="text-right">
                                    <h4><strong>Total: </strong></h4>
                                </td>
                                <td class="text-center text-danger">
                                    <h4><strong>Rp<?php echo number_format($grand_total, 2); ?></strong></h4>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="well col-xs-10 col-sm-10 col-md-6 col-xs-offset-1 col-sm-offset-1 col-md-offset-3">
                <button id="print" onclick="printContent('Receipt');" class="btn btn-success btn-lg text-justify btn-block">
                    Cetak <span class="fas fa-print"></span>
                </button>
            </div>
        </div>
    </div>
</body>

</html>
<script>
    function printContent(el) {
        var restorepage = $('body').html();
        var printcontent = $('#' + el).clone();
        $('body').empty().html(printcontent);
        window.print();
        $('body').html(restorepage);
    }
</script>
