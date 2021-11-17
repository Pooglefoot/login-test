<?php
class Database {
    protected $connection = null;

    // Establish connection to database.
    public function __construct() {
        try {
            $this->connection = new mysqli(
                DATABASE_SERVER,
                DATABASE_USERNAME,
                DATABASE_PASSWORD,
                DATABASE_NAME);

            if ( mysqli_connect_errno()) {
                throw new Exception("Could not connect to database.");
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    // Query for selecting DB entries.
    public function select($query = "", $parameters = []) {
        try {
			// Use prepared statements to avoid SQL injections.
            $statement = $this->connection->prepare($query);
            if ($statement === false) {
                throw New Exception("Unable to do prepared statement: " . $query);
            }

			// Properly bind parameters before executing prepared statement.
            $statement->bind_param($parameters[0], $parameters[1]);
            $statement->execute();
            $statement->store_result();

            return $statement;
        } catch(Exception $e) {
            throw New Exception($e->getMessage());
        }
    }

	// Query for posting DB entries.
    public function post($query = "", $parameters = []) {
        try {
			// Use prepared statements to avoid SQL injections.
            $statement = $this->connection->prepare($query);
			if ($statement === false) {
				throw New Exception("Unable to do prepared statement: " . $query);
			}

			// Properly bind parameters before executing prepared statement.
			$statement->bind_param($parameters[0], $parameters[1], $parameters[2], $parameters[3], $parameters[4]);
			if ($statement->execute()) {
				$statement->close();
				return true;
			}

			return false;
        } catch(Exception $e) {
            throw New Exception ($e->getMessage());
        }
    }

    // Closes the Database link.
    public function close() {
        try {
            $this->connection->close();
        } catch(Exception $e) {
            throw New Exception($e->getMessage());
        }
    }
}
?>