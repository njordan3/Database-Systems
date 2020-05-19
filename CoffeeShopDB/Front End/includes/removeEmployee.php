<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include_once("library.php");
if (isset($_POST['removebtn'])) {
	$id = cleanInput($_POST['remove']);
	if (empty($id)) {
		header("Location: ../#home");
		exit();
	}
	$dbServer = "coffeeshopdb.cniwq5h2gums.us-east-2.rds-preview.amazonaws.com";
	$dbName = "CoffeeShopDB";                                                                                                         
	$dbUser = "coffeeshop";
	$dbPass = "cmps3420";
	$connection = pg_connect("host=$dbServer dbname=$dbName user=$dbUser password=$dbPass connect_timeout=3");
	if ($connection) {
		$sql = "select removeEmployee($id)";
		$query = pg_query($connection, $sql);
	}
	header("Location: ../#home");
} else {
	header("Location: ../#home");
}
?>
