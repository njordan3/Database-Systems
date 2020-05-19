<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

session_start();
if (isset($_SESSION['active'])) {
    if ($_SESSION['active'] == true) {
	if ($_GET['t'] == 1) {
            include_once("inventory.php");
	} else if ($_GET['t'] == 2) {
            include_once("take-order.php");
	} else if ($_GET['t'] == 3) {
	    include_once("fill-order.php");
	} else {
            include_once("dashboard.php");
	}
    }
} else { 
  include_once("login.php");
}
?>
