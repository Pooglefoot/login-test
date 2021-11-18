<?php
// Include config file
require __DIR__ . "/inc/bootstrap.php";

session_start();

// If a user is already logged in,
if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true) {
	header("location: user_page.php");
	exit;
}

// Nasty, dirty globals, for brevity.
$username = "";
$password = "";
$password_confirm = "";
$username_error = "";
$password_error = "";
$password_confirm_error = "";
$user_created_success = "";

$method = $_SERVER["REQUEST_METHOD"];

if ($method == "POST") {
    // Trim the entries in the form so that any leading and tailing spaces are deleted.
    $new_username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $password_confirm = trim($_POST["password_confirm"]);

	// Define UserModel DB link for validation comparison.
	$users = new UserModel();

    // Regex match on the posted username against characters, numbers and underscores.
	// If it contains any other symbol, we set the global username_error to reflect the error.
	// Else we call getUserByUsername to see if the posted username exists.
	// If it does, we set the global username_error to reflect the error, as we require password uniqueness.
    if (empty($new_username)) {
        $username_error = "Please enter a username.";
	} else if(!preg_match("/^[ÆØÅæøåA-Za-z0-9_]{3,20}$/", $new_username)) {
		$username_error = "Your username must consist of letters, numbers and underscores, and be at least 3, and at most 20 characters.";
    } else if ($users->getUserByUsername($new_username, "create")->num_rows > 0) {
		$username_error = "User already exists. Please try another name.";
    } else {
        $username = $new_username;
    }

	// Minimum length of password, not unique so there's no need to check DB for other instances.
	// (Nor should we be able to do so).
    if (empty($password)) {
        $password_error = "Please enter a password.";
	} else if (strlen($password) < 8) {
		$password_error = "Password must be longer than 8 characters.";
	} else {
		$password = trim($_POST["password"]);
	}

    // Ensure password correctness by making sure both variables match.
	if (empty($password_confirm)) {
        $password_confirm_error = "Please enter your password.";
    } else if ($password != $password_confirm) {
		$password_confirm_error = "Password does not match.";
	}

    // Before attempting an INSERT SQL statement, we check if any of the info entered (or not entered) in our form fields is invalid
    if (empty($username_error) && empty($password_error) && empty($password_confirm_error)) {
        if ($users->createNewUser($username, password_hash($password, PASSWORD_DEFAULT), time())) {
			$user_created_success = "User successfully created!";
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
                    <h2>Sign Up</h2>
                    <p>Please fill this form to create an account.</p>

					<?php
					if(!empty($user_created_success)){
						echo '<div class="alert alert-success">' . $user_created_success . ' <a href="login.php">Click here</a> to go to the login page.</div>';
					}
					?>

                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-floating field">
                            <input type="text" name="username" id="fieldUsername" placeholder="Username" class="form-control <?php echo (!empty($username_error)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                            <label for="fieldUsername">Username</label>
                            <span class="invalid-feedback"><?php echo $username_error; ?></span>
                        </div>
                        <div class="form-floating field">
                            <input type="password" name="password" id="fieldPassword" placeholder="Password" class="form-control <?php echo (!empty($password_error)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                            <label for="fieldPassword">Password</label>
                            <span class="invalid-feedback"><?php echo $password_error; ?></span>
                        </div>
                        <div class="form-floating field">
                            <input type="password" name="password_confirm" id="fieldPasswordConfirm" placeholder="Confirm Password" class="form-control <?php echo (!empty($password_confirm_error)) ? 'is-invalid' : ''; ?>" value="<?php echo $password_confirm; ?>">
                            <label for="fieldPasswordConfirm">Confirm Password</label>
                            <span class="invalid-feedback"><?php echo $password_confirm_error; ?></span>
                        </div>
                        <div class="form-group field">
                            <input type="submit" class="btn btn-primary" value="Submit">
                            <input type="reset" class="btn btn-secondary ml-2" value="Reset">
                        </div>
                        <p>Already have an account? <a href="login.php">Login here</a>.</p>
                    </form>
                </div>
                <div class="col"></div>
            </div>
        </div>
    </body>
</html>