<?php
// Include config file
require_once "config.php";

$username = '';
$password = '';
$password_confirm = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $sql = "SELECT id FROM users WHERE username = ?";

    // Prepare and subsequently execute statement
    if ($statement = mysqli_prepare($link, $sql)) {
        $username_sql_parameter = trim($_POST['username']);

        // Bind variable to prepared statement, specify that username is a string
        $statement->bind_param('s', $username_sql_parameter);

        if ($statement->execute()) {
            $statement->store_result();

            if ($statement->num_rows() > 0) {
                //TODO: Add error statement, user already found.
            } else {
                $username = trim($_POST["username"]);
            }
        } else {
            echo "Something went wrong. Try again!";
        }

        $statement->close();
    }

    // Minimum length of password, not unique so there's no need to check DB for other instances.
    // (Nor should we be able to do so).
    if (strlen($_POST["password"]) < 8) {
        //TODO: Add error statement, 8 or more characters.
    } else {
        $password = trim($_POST['password']);
    }

    // Ensure password correctness by making sure both variables match.
    $password_confirm = trim($_POST['password_confirm']);
    if ($password != $password_confirm) {
        //TODO: Add error statement, passwords must match.
    }

    //TODO: Evaluate correctness of parameters.

    // Prepare and execute INSERT SQL statement to create user.

    $sql = "INSERT INTO users (username, password, is_admin) VALUES (?, ?, ?)";

    if ($statement = mysqli_prepare($link, $sql)) {
        // Prepare parameters, hash password, only admin can create admins.
        $sql_parameter_username = $username;
        $sql_parameter_password = password_hash($password, PASSWORD_DEFAULT);
        $sql_parameter_is_admin = 1;
        //TODO: Change is_admin to 0 after initial user.

        $statement->bind_param('ssi', $sql_parameter_username, $sql_parameter_password, $sql_parameter_is_admin);

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
}