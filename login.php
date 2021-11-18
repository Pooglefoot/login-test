<?php
// Include config file
require __DIR__ . "/inc/bootstrap.php";

$username = "";
$username_error = "";
$password_error = "";
$login_error = "";

// Initialise  session.
session_start([
	// Site doesn't do much, cookie lifetime of 5 mins.
	"cookie_lifetime" => 300,
]);

// If a user is already logged in,
if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true) {
	header("location: user_page.php");
	exit;
}

$method = $_SERVER["REQUEST_METHOD"];

if ($method == "POST") {
	$username = trim($_POST["username"]);
	$password = trim($_POST["password"]);

	if (empty($username)) {
		$username_error = "Enter your username.";
	}

	if (empty($password)) {
		$password_error = "Enter your password.";
	}

	// If both fields are filled out, we attempt to validate the login.
	if (empty($username_error) && empty($password_error)) {
		// Define UserModel object for validation comparison.
		$users = new UserModel();
		$result = $users->getUserByUsername($username, "login");

		$result->bind_result($id, $username, $password_hashed, $is_admin);
		if ($result->num_rows == 1) {
			if ($result->fetch()) {
				if (password_verify($password, $password_hashed)) {
					$_SESSION["logged_in"] = true;
					$_SESSION["id"] = $id;
					$_SESSION["username"] = $username;
                    $_SESSION["is_admin"] = $is_admin;

					header("location: user_page.php");
				} else {
					// Password doesn't exist. Generic error so as to not give unwelcome intruders any concrete info.
					$login_error = "Incorrect username or password.";
				}
			}
		} else {
			// Username doesn't exist. Generic error so as to not give unwelcome intruders any concrete info.
			$login_error = "Incorrect username or password.";
		}

		// Close database link.
		$users->close();
	}
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Login</title>
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
            <a href="index.php" class="btn btn-primary">Index</a>
        </div>
        <div class="container">
            <div class="row">
                <div class="col"></div>
                <div class="wrapper my-5 col-6">
                    <h2>Login</h2>
                    <p>Please fill in your details to login.</p>

                    <?php
                    if(!empty($login_error)){
                        echo '<div class="alert alert-danger">' . $login_error . '</div>';
                    }
                    ?>

                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-floating field">
                            <input type="text" name="username" id="fieldUsername" placeholder="Username" class="form-control <?php echo (!empty($username_error)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                            <label for="fieldUsername">Username</label>
                            <span class="invalid-feedback"><?php echo $username_error; ?></span>
                        </div>
                        <div class="form-floating field">
                            <input type="password" name="password" id="fieldPassword" placeholder="Password" class="form-control <?php echo (!empty($password_error)) ? 'is-invalid' : ''; ?>">
                            <label for="fieldPassword">Password</label>
                            <span class="invalid-feedback"><?php echo $password_error; ?></span>
                        </div>
                        <div class="form-group field">
                            <input type="submit" class="btn btn-primary" value="Login">
                        </div>
                        <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
                    </form>
                </div>
                <div class="col"></div>
            </div>
        </div>
    </body>
</html>
