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

    // Select query.
    public function select($query = "", $parameters = []) {
        try {
            $statement = $this->executeStatement($query , $parameters);
            $result = $statement->get_result()->fetch_all(MYSQLI_ASSOC);
            $statement->close();

            return $result;
        } catch(Exception $e) {
            throw New Exception($e->getMessage());
        }
    }

    public function post($query = "", $parameters = []) {
        try {
            $statement = $this->executeStatement($query, $parameters);
            $statement->close();

        } catch(Exception $e) {
            throw New Exception ($e->getMessage());
        }
    }

    // Establish and execute prepared statement
    private function executeStatement($query = "", $parameters = []) {
        try {
            $statement = $this->connection->prepare($query);

            if($statement === false) {
                throw New Exception("Unable to do prepared statement: " . $query);
            }

            // Properly bind any parameters to prepared statements.
            // bind_param potentially takes more variables, but we know we have no more than 4.
            // Bad, non-scalable solution, but functional for now.
            // TODO: Generalise, bind parameters properly.
            // Done to avoid SQL injections.
            if($parameters) {
                if ($parameters[2]) {
                    $statement->bind_param($parameters[0], $parameters[1], $parameters[2], $parameters[3]);
                } else {
                    $statement->bind_param($parameters[0], $parameters[1]);
                }
            }

            $statement->execute();

            return $statement;
        } catch(Exception $e) {
            throw New Exception($e->getMessage());
        }
    }
}
?>