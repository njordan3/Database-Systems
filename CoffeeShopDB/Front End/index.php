<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once("includes/dbconnect.php");
include_once("includes/library.php");

ini_set("session.cookie_httponly", 1);
session_start();
include_once("pages/templates/header.php");
?>
	<div id="content"></div>
<?php
include_once("pages/templates/footer.php");
?>

