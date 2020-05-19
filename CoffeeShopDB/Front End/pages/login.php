<?php
session_start();
include_once("includes/library.php");
if (!isset($_SESSION['active'])) {
    if (isset($_GET['err']) && !empty($_GET['err'])) {
	$user = cleanInput($_GET['user']);
    }
?>
<title>Coffee Shop Login</title>
<style>
  html {
    background-image: url("images/login-background.jpg");
    background-repeat: no-repeat;
    background-size: cover;
    background-position: 0% 40%;
  }
</style>
</head>
  <body>
    <div class="login-clean" style="background-color: transparent">
        <form action="includes/login.inc.php" method="post">
            <h2 style="filter: blur(0px);">Coffee Shop Employee Login</h2>
	    <div class="illustration">
	      <i class="icon ion-coffee icon"></i>
	    </div>
	    <div class="form-group">
	      <input class="form-control" type="text" autofocus="" name="user" placeholder="First name">
	    </div>
	    <div class="form-group">
	      <input class="form-control" type="password" name="ssn" placeholder="Last 4 digits of SSN" autofocus="" required="" minlength="4" maxlength="4">
	    </div>
	    <div class="form-group">
              <button class="btn btn-primary btn-block" name="login" type="submit">Log In</button>
	    </div>
        </form>
    </div>
<?php } else { ?>
  <h2>ALREADY LOGGED IN</h2>
<?php
}
?>
