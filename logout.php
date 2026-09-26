<?php

session_start();

// Destroy login session
session_unset();
session_destroy();

// Go back to login page
header("Location: index.php");
exit();

?>