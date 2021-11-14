<?php
require_once PROJECT_ROOT_PATH . "/Model/Database.php";

class UserModel extends Database {

    // getUserByUsername gets users by their Username and returns the row in question as a mysqli_result object.
    // Uses function "select" from Database class, as UserModel extends it.
    public function getUserByUsername($username) {
        return $this->select("SELECT id FROM users WHERE username = ?", ["s", $username]);
    }

    // createNewUser creates a new user. Admin set to 0 by default.
    public function createNewUser($username, $password, $is_admin = 0, $created) {
        $this->post("INSERT INTO users (username, password, is_admin, created) VALUES (?, ?, ?, ?)",
            [
                'ssii',
                $username,
                $password,
                $is_admin,
                $created
            ]);
    }
}
?>