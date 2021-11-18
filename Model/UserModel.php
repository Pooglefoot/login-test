<?php
require_once PROJECT_ROOT_PATH . "/Model/Database.php";

class UserModel extends Database {

	/**
	 * getUserByUsername gets a user id and the associated username by their Username and returns the row in question as a mysqli_result object.
	 * Uses function "select" from Database class, as UserModel extends it.
	 *
	 * @throws Exception
	 */
	public function getUserByUsername($username, $source) {
		if ($source === "create") {
			return $this->select("SELECT id, username FROM users WHERE username = ?", ["s", $username]);
		}

		if ($source === "login") {
			return $this->select("SELECT id, username, password, is_admin FROM users WHERE username = ?", ["s", $username]);
		}
    }

	/**
	 * createNewUser creates a new user. is_admin defaults to 0.
	 *
	 * @throws Exception
	 */
	public function createNewUser($username, $password, $created, $is_admin = 0) {
        return $this->post("INSERT INTO users (username, password, is_admin, created) VALUES (?, ?, ?, ?)",
            [
                "ssii",
                $username,
                $password,
                $is_admin,
                $created
            ]);
    }

	/**
	 * updateUser simply updates the password of currently active user.
	 *
	 * @throws Exception
	 */
	public function updateUser($id, $password) {
		return $this->update("UPDATE users SET password = ? WHERE id = ?", ["si", $password, $id]);
	}
}
?>