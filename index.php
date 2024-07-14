<?php
session_start();
include('pos/admin/config/config.php');

// login 
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = sha1(md5($_POST['password'])); // enkripsi ganda untuk meningkatkan keamanan
    
    // Persiapan statement untuk memeriksa tabel admin
    $stmt_admin = $mysqli->prepare("SELECT admin_id FROM rpos_admin WHERE admin_email = ? AND admin_password = ?");
    $stmt_admin->bind_param('ss', $email, $password);
    $stmt_admin->execute();
    $stmt_admin->bind_result($admin_id);
    $stmt_admin->fetch();
    $stmt_admin->close();

    // Persiapan statement untuk memeriksa tabel staf
    $stmt_staff = $mysqli->prepare("SELECT staff_id FROM rpos_staff WHERE staff_email = ? AND staff_password = ?");
    $stmt_staff->bind_param('ss', $email, $password);
    $stmt_staff->execute();
    $stmt_staff->bind_result($staff_id);
    $stmt_staff->fetch();
    $stmt_staff->close();



    if ($admin_id || $staff_id || $customer_id) {
        // Jika login berhasil di salah satu tabel
        if ($admin_id) {
            $_SESSION['admin_id'] = $admin_id;
            header("location:pos/admin/dashboard.php");

        } else if ($staff_id) {
            $_SESSION['staff_id'] = $staff_id;
            header("location:pos/cashier/dashboard.php");

        } 
    } else {
        $err = "Kredensial Autentikasi Salah";
    }
}


// Sertakan bagian head
require_once('partials/_head.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS System</title>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600|Roboto:300,400,700" rel="stylesheet">
    <style>
        html, body {
            height: 100%;
            width: 100%;
            margin: 0;
            padding: 0;
            font-family: 'Roboto', sans-serif;
        }

        .container {
            display: flex;
            min-height: 100vh;
            width: 100%;
            margin: 0;
            padding: 0;
        }

        .left {
            flex: 1;
            background: url('assets/image/mieAyamAfi2.png') no-repeat center center;
            background-size: cover;
        }

        .right {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 2rem;
            background: rgba(255, 255, 255, 0.9);
        }

        .title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #c92424;
            margin-bottom: 1rem;
            text-align: center;
        }

        .form-container {
            width: 100%;
            max-width: 400px;
            padding: 1rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
        }

        .form-group input {
            width: 100%;
            padding: 0.75rem;
            font-size: 1rem;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .custom-control {
            margin-top: 1rem;
        }

        .buttons {
            margin-top: 1.5rem;
            /* display: flex; */
            gap: 1rem;
        }

        .buttons button,
        .buttons a {
            flex: 1;
            color: #fff;
            background: #c92424;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            font-weight: 600;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            transition: background 0.3s, transform 0.3s;
        }

        .buttons button:hover,
        .buttons a:hover {
            background: #71d18b;
            transform: scale(1.05);
        }

        .buttons button:focus,
        .buttons a:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(113, 209, 139, 0.5);
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .left {
                min-height: 300px;
                flex: none;
                width: 100%;
            }

            .right {
                flex: none;
                width: 100%;
                padding: 1rem;
            }

            .title {
                font-size: 2rem;
            }

            .form-container {
                max-width: 100%;
            }

            .buttons {
                flex-direction: column;
                gap: 0.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="left"></div>
        <div class="right">
            <div class="title">
                POS Management System
            </div>

            <div class="form-container">
                <form method="POST" role="form" action="index.php">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input class="form-control" required name="email" placeholder="Email" type="email">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input class="form-control" required name="password" placeholder="Password" type="password">
                    </div>

                    <div class="custom-control custom-control-alternative custom-checkbox">
                        <input class="custom-control-input" id="customCheckLogin" type="checkbox">
                        <label class="custom-control-label" for="customCheckLogin">
                            <span class="text-muted">Remember Me</span>
                        </label>
                    </div>
                    <div class="buttons">
                        <button type="submit" name="login" class="btn btn-primary my-4">Masuk</button>
                        <a href="create_accuunt.php" class="btn btn-success">Buat Akun</a>
                    </div>
                    <?php if (isset($err)) {
                        echo "<p class='text-danger'>$err</p>";
                    } ?>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
