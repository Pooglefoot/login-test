<?php
// Include config file
require_once 'config.php';

$username = '';
$password = '';
$password_confirm = '';

$username_err = '';
$password_err = '';
$password_confirm_err = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    printf("Start\n");

    // Set up SELECT statement as Prepared Statement to guard against injections.
    $mysql = "SELECT id FROM users WHERE username = ?";

    // Prepare and subsequently execute statement
    if ($statement = mysqli_prepare($link, $mysql)) {

        printf("Statement prepared\n");

        // Retrieve Username from field.
        $username_sql_parameter = trim($_POST['username']);

        // Bind variable to prepared statement, specify that username is a string
        $statement->bind_param('s', $username_sql_parameter);

        printf("Parameters bound\n");

        // Execute statements and store results so they can be compared.
        if ($statement->execute()) {
            $statement->store_result();

            // Usernames must be unique, no rows must show up in select statement.
            if ($statement->num_rows() > 0) {
                //TODO: Add error statement, user already found.
            } else {
                $username = trim($_POST['username']);

                printf("Username accepted\n");
            }
        } else {
            echo 'Something went wrong. Try again!';
        }

        $statement->close();
    }

    // Minimum length of password, not unique so there's no need to check DB for other instances.
    // (Nor should we be able to do so).
    if (strlen($_POST["password"]) < 5) {
        //TODO: Add error statement, 8 or more characters.
    } else {
        $password = trim($_POST['password']);

        printf("Password accepted\n");
    }

    // Ensure password correctness by making sure both variables match.
    $password_confirm = trim($_POST['password_confirm']);
    if ($password != $password_confirm) {
        //TODO: Add error statement, passwords must match.
    }

    printf("Password confirmation accepted\n");

    //TODO: Evaluate correctness of parameters.

    // Prepare and execute INSERT SQL statement to create user.

    $sql = "INSERT INTO users (username, password, is_admin, created) VALUES (?, ?, ?, ?)";

    if ($statement = mysqli_prepare($link, $sql)) {
        // Prepare parameters, hash password, only admin can create admins.
        $sql_parameter_username = $username;
        $sql_parameter_password = password_hash($password, PASSWORD_DEFAULT);
        $sql_parameter_is_admin = 0;
        $sql_parameter_created = time();
        //TODO: Change is_admin to 0 after initial user.

        $statement->bind_param(
                'ssii',
                $sql_parameter_username,
                $sql_parameter_password,
                $sql_parameter_is_admin,
                $sql_parameter_created
        );

        // Attempt to execute prepared statement
        if ($statement->execute()) {
            // Redirect to login page
            // TODO: Consider having this in Window with JS.
            header('location: login.php');
        } else {
            echo 'Something went wrong. Try again!';
        }

        $statement->close();
    }

    $link->close();

    printf("Uploaded and link closed.\n");
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