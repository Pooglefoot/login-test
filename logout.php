<?php
// Initialise session
session_start();

// Unset all session variables (username, password, etc.)
session_unset();

// Destroy all remaining data associated with current session.
// session_destroy does not destroy session-associated global variables, thus the need for the above.
session_destroy();

// Redirect back to index.
header("location: index.php");
exit;
?>