<?php
session_start();
include_once("dbconnect.php");
include_once("library.php");
if(isset($_SESSION['active'])) {
if($_SESSION['active']) {
    if (isset($_POST['logout'])) {
        $_SESSION = array();
	session_destroy();
	pg_close($connection);
    }
}
}
header("Location: ../#home");
exit();
?>
