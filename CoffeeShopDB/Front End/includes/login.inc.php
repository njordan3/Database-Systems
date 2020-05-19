<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include_once("library.php");
  if (isset($_POST['login'])) {
      $user = cleanInput($_POST['user']);
      $ssn = cleanInput($_POST['ssn']);
      if (empty($user) && empty($ssn)) {
	  header("Location: ../#login?err=UsrSsn");
	  exit();
      } else if (empty($user)) {
	  header("Location: ../#login?err=Usr");
	  exit();
      } else if (empty($ssn)) {
	  header("Location: ../#login?err=Ssn&usr=$user");
 	  exit();
      } else {
	  $login = login($user, $ssn);
	  if (!empty($login)) {
	      store($login);
	      header("Location: ../#home");
	  } else {
	      header("Location:  ../#home");
	      exit();
	  }
      }
  }
?>
