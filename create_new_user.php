<?php
// Include config file
require __DIR__ . "/inc/bootstrap.php";

// Nasty, dirty globals, for brevity.
$username = "";
$password = "";
$password_confirm = "";
$username_err = "";
$password_err = "";
$password_confirm_err = "";

$method = $_SERVER["REQUEST_METHOD"];

if ($method == "POST") {
    // Trim the entries in the form so that any leading and tailing spaces are deleted.
    $new_username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $password_confirm = trim($_POST["password_confirm"]);

	// Define UserModel object for validation comparison.
	$users = new UserModel();

    // Regex match on the posted username against characters, numbers and underscores.
	// If it contains any other symbol, we set the global username_error to reflect the error.
	// Else we call getUserByUsername to see if the posted username exists.
	// If it does, we set the global username_error to reflect the error, as we require password uniqueness.
    if (empty($new_username)) {
        $username_err = "Please enter a username.";
	} else if(!preg_match("/^[A-Za-z0-9_]{3,20}$/", $new_username)) {
		$username_err = "Your username must consist of letters, numbers and underscores, and be at least 3, and at most 20 characters.";
    } else if ($users->getUserByUsername($new_username)->num_rows > 0) {
		$username_err = "User already exists. Please try another name.";
    } else {
        $username = $new_username;
    }

	// Minimum length of password, not unique so there's no need to check DB for other instances.
	// (Nor should we be able to do so).
    if (empty($password)) {
        $password_err = "Please enter a password.";
	} else if (strlen($password) < 8) {
		$password_err = "Password must be longer than 8 characters.";
	} else {
		$password = trim($_POST["password"]);
	}

    // Ensure password correctness by making sure both variables match.
	if (empty($password_confirm)) {
        $password_confirm_err = "Please enter your password.";
    } else if ($password != $password_confirm) {
		$password_confirm_err = "Password does not match.";
	}

    // Before attempting an INSERT SQL statement, we check if any of the info entered (or not entered) in our form fields is invalid
    if (empty($username_err) && empty($password_err) && empty($password_confirm_err)) {
        if ($users->createNewUser($username, password_hash($password, PASSWORD_DEFAULT), 0, time())) {
			header('location: login.php');
        } else {
            echo "Something went wrong. Try again!";
        }
    }

    // Close database link.
    $users->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="password_confirm" class="form-control <?php echo (!empty($password_confirm_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password_confirm; ?>">
                <span class="invalid-feedback"><?php echo $password_confirm_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-secondary ml-2" value="Reset">
            </div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>
</body>
</html>