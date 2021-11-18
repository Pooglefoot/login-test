<?php
// Initialize the session
session_start();

// Redirect user to login-page if there are no active logins.
if(!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true){
	header("location: login.php");
	exit;
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Welcome</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <style>
            body {
                font: 20px sans-serif;
                text-align: center;
            }
            .topBar {
                margin-top: 5px;
                margin-right: 5px;
                text-align: right;
            }
        </style>
    </head>
    <body>
        <div class="topBar">
            <?php if ($_SESSION["is_admin"]) { ?>
                <a href="reset_password.php" class="btn btn-success">See list of users</a>
            <?php } ?>
            <a href="reset_password.php" class="btn btn-warning">Reset Password</a>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
        <h1 class="my-5">Welcome <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b></h1>
    </body>
</html>