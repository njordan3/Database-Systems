<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include_once("library.php");
if (isset($_GET["name"]) && 
    isset($_GET["bd"]) &&
    isset($_GET["sex"]) &&
    isset($_GET["ssn"]) &&
    isset($_GET["addr"]) &&
    isset($_GET["dept"]) &&
    isset($_GET["wage"]) &&
	isset($_GET["sdate"])){
	$sID = $_SESSION["sid"];
	$mID = $_SESSION["mid"];
	$name = $_GET["name"];
	$bd = $_GET["bd"];
	$sex = $_GET["sex"];
	$ssn = $_GET["ssn"];
	$addr = $_GET["addr"];
	$dept = $_GET["dept"];
	$wage = $_GET["wage"];
	$sdate = $_GET["sdate"];
	$dbServer = "coffeeshopdb.cniwq5h2gums.us-east-2.rds-preview.amazonaws.com";
	$dbName = "CoffeeShopDB";
   	$dbUser = "coffeeshop";
    $dbPass = "cmps3420";
   	$connection = pg_connect("host=$dbServer dbname=$dbName user=$dbUser password=$dbPass connect_timeout=3");
    if ($connection) {
    	$sql = "select addEmployee('$name','$ssn','$addr','$bd','$sex','$dept','$wage',NULL,$sID,$mID,'$sdate')";
		if (!pg_query($connection, $sql)) {
	    	echo "ERROR ADDING EMPLOYEE";
        }
	header("Location: ../#home");
    }
} else {
	header("Location: ../#home");
}
?>

