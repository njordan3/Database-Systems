<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include_once("library.php");
if (isset($_GET["order"]) && isset($_GET["disc"])) {
	$temp = explode(",", $_GET["order"]);
	$length = count($temp)/5;
	$order = array();
	$j = 0;
	for ($i = 0; $i < $length; $i++) {
		$order[$i] = array($temp[$j],$temp[$j+1],$temp[$j+2],$temp[$j+3],$temp[$j+4]);
		$j+=5;
	}
	$discount = $_GET["disc"];
	$name = NULL;
	$phone = NULL;
	if (isset($_GET["cust"])) {
		$name = $_GET["cust"];
	}
	if (isset($_GET["phone"])) {
		$phone = $_GET["phone"];
	}
	$dbServer = "coffeeshopdb.cniwq5h2gums.us-east-2.rds-preview.amazonaws.com";
	$dbName = "CoffeeShopDB";
	$dbUser = "coffeeshop";
	$dbPass = "cmps3420";
	$connection = pg_connect("host=$dbServer dbname=$dbName user=$dbUser password=$dbPass connect_timeout=3");
	if ($connection) {
		$sql = "select max(sorderid)+1 from sorder";
		$query = pg_query($connection, $sql);
		$sID = pg_fetch_result($query,0,0);
		$sql = "select max(customerid)+1 from customer";
		$query = pg_query($connection, $sql);
		$cID = pg_fetch_result($query,0,0);
		$eID = $_SESSION['eid'];
		for ($i = 0; $i < $length; $i++) {
			$size = $order[$i][0];
			$product = $order[$i][1];
			$quantity = $order[$i][2];
			$price = $order[$i][3];
			$instr = $order[$i][4];
			if ($phone == '') {
				$phone=NULL;
			}
			$sql = "select addSOrder($sID,$cID,'$phone','$name','False',$discount,'False','$product','$size',$quantity,'$instr',$price,$eID,'Cash')";
			if (!pg_query($connection, $sql)) {
			    echo "ERROR ADDING SORDER";
			} else {
				header("Location: ../#home?t=2");
			}
		}
	}
} else {
	header("Location: ../#home");
}
?>


