<?php
require __DIR__ . "/inc/bootstrap.php";

// Initialise session
session_start();

// Redirect user to login-page if there are no active logins.
if(!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true){
	header("location: login.php");
	exit;
}

$password_new = "";
$password_new_confirm = "";
$password_new_error = "";
$password_new_confirm_error = "";
$password_change_success = "";

$method = $_SERVER["REQUEST_METHOD"];

if ($method == "POST") {
	$password_new = trim($_POST["password_new"]);
	$password_new_confirm = trim($_POST["password_new_confirm"]);

	// Minimum length of password, not unique so there's no need to check DB for other instances.
	// (Nor should we be able to do so).
	if (empty($password_new)) {
		$password_new_error = "Please enter a password.";
	} else if (strlen($password_new) < 8) {
		$password_new_error = "Password must be longer than 8 characters.";
	} else {
		$password_new = trim($_POST["password_new"]);
	}

	// Ensure password correctness by making sure both variables match.
	if (empty($password_new_confirm)) {
		$password_new_confirm_error = "Please enter your password.";
	} else if ($password_new != $password_new_confirm) {
		$password_new_confirm_error = "Password does not match.";
	}

	if (empty($password_new_error) && empty($password_new_confirm_error)) {
		$users = new UserModel();
		if ($users->updateUser($_SESSION["id"], password_hash($password_new, PASSWORD_DEFAULT))) {
			$password_change_success = "Password was successfully changed!";
		} else {
			echo "Something went wrong. Try again!";
		}

		$users->close();
	}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Sign Up</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<style>
        body {
            font: 14px sans-serif;
        }
        .topBar {
            margin-top: 5px;
            margin-right: 5px;
            text-align: right;
        }
        .field {
            margin-bottom: 15px;
        }
        .wrapper {
            width: 360px;
            padding: 20px;
        }
	</style>
</head>
<body>
<div class="topBar">
	<a href="user_page.php" class="btn btn-primary">Back</a>
</div>
<div class="container">
	<div class="row">
		<div class="col"></div>
		<div class="wrapper my-5 col-6">
			<h2>Reset Password</h2>
			<p>Please fill in a new password to update your settings.</p>

			<?php
			if(!empty($password_change_success)){
				echo '<div class="alert alert-success">' . $password_change_success . ' <a href="user_page.php">Click here</a> to go back to your page.</div>';
			}
			?>

			<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
				<div class="form-floating field">
					<input type="password" name="password_new" id="fieldPasswordNew" placeholder="New Password" class="form-control <?php echo (!empty($password_new_error)) ? 'is-invalid' : ''; ?>" value="<?php echo $password_new; ?>">
					<label for="fieldPasswordNew">New Password</label>
					<span class="invalid-feedback"><?php echo $password_new_error; ?></span>
				</div>
				<div class="form-floating field">
					<input type="password" name="password_new_confirm" id="fieldPasswordNewConfirm" placeholder="Confirm New Password" class="form-control <?php echo (!empty($password_new_confirm_error)) ? 'is-invalid' : ''; ?>" value="<?php echo $password_new_confirm; ?>">
					<label for="fieldPasswordNewConfirm">Confirm New Password</label>
					<span class="invalid-feedback"><?php echo $password_new_confirm_error; ?></span>
				</div>
				<div class="form-group field">
					<input type="submit" class="btn btn-primary" value="Submit">
					<input type="reset" class="btn btn-secondary ml-2" value="Reset">
				</div>
			</form>
		</div>
		<div class="col"></div>
	</div>
</div>
</body>
</html>
