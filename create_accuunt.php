<?php
session_start();
include ('pos/admin/config/config.php');

// Register
if (isset($_POST['register'])) {
    //Prevent Posting Blank Values
    if (empty($_POST["staff_number"]) || empty($_POST["staff_name"]) || empty($_POST['staff_email']) || empty($_POST['staff_password'])) {
        $err = "Blank Values Not Accepted";
    } else {
        $staff_name = $_POST['staff_name'];
        $staff_number = $_POST['staff_number'];
        $staff_email = $_POST['staff_email'];
        $staff_password = sha1(md5($_POST['staff_password'])); // Double encrypt to increase security
        $staff_id = $_POST['staff_id']; // Assuming this is provided from a code generator

        // Insert captured information into the database table
        $postQuery = "INSERT INTO rpos_staff (staff_id, staff_name, staff_number, staff_email, staff_password) VALUES (?, ?, ?, ?, ?)";
        $postStmt = $mysqli->prepare($postQuery);
        // Bind parameters
        $rc = $postStmt->bind_param('sssss', $staff_id, $staff_name, $staff_number, $staff_email, $staff_password);
        $postStmt->execute();
        // Declare a variable which will be passed to alert function
        if ($postStmt) {
            $success = "Customer Account Created" && header("refresh:1; url=index.php");
          
        } else {
            $err = "Please Try Again Or Try Later";
            echo "<script type='text/javascript'>alert('$error');</script>";
        }
        $postStmt->close();
    }
}

// Include the head section and code generator
require_once ('pos/admin/partials/_head.php');
require_once ('pos/admin/config/code-generator.php');
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
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            display: flex;
            width: 100%;
            height: 100%;
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
            text-align: center;
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
            /* padding: 1rem;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background: #fff; */
        }

        .form-group {
            margin-bottom: 1rem;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
        }

        .form-group input {
            width: 100%;
            padding: 0.5rem;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .buttons {
            margin-top: 2rem;
            /* display: flex; */
            justify-content: space-between;
            gap: 1rem;
        }

        .buttons button,
        .buttons a {
            flex: 1;
            color: #fff;
            background: #c92424;
            padding: 0.75rem 1rem;
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            text-transform: uppercase;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            transition: background 0.3s, transform 0.3s, box-shadow 0.3s;
            text-align: center;
            display: inline-block;
        }

        .buttons button:hover,
        .buttons a:hover {
            background: #71d18b;
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
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
                padding: rem;
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
                <form method="POST" role="form">
                    <h2>Register</h2>

                    <?php if (isset($msg)) {
                        echo "<p class='text-success'>$msg</p>";
                    } ?>
                    <?php if (isset($err)) {
                        echo "<p class='text-danger'>$err</p>";
                    } ?>

                    <div class="form-group mb-3">
                        <div class="input-group input-group-alternative">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                            </div>
                            <input class="form-control" required name="staff_name" placeholder="Full Name" type="text">
                            <input class="form-control" value="<?php echo $cus_id; ?>" required name="staff_id" type="hidden">
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <div class="input-group input-group-alternative">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                            </div>
                            <input class="form-control" required name="staff_number" placeholder="Phone Number" type="text">
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <div class="input-group input-group-alternative">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                            </div>
                            <input class="form-control" required name="staff_email" placeholder="Email" type="email">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group input-group-alternative">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                            </div>
                            <input class="form-control" required name="staff_password" placeholder="Password" type="password">
                        </div>
                    </div>
                    <div class="buttons">
                        <button type="submit" name="register" class="btn btn-primary my-4">Buat Akun</button>
                        <a href="index.php" class="btn btn-success pull-right">Masuk</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
