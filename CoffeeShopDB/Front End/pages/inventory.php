<?php
$tabIndex = 0;
if(isset($_GET['t'])) {
    $tabIndex = $_GET['t'];
}

include_once("includes/library.php");
session_start();
if (isset($_SESSION['active'])) {
?>
<style>
  html {
    background: linear-gradient(rgba(47, 23, 15, 0.65), rgba(47, 23, 15, 0.65)),url('images/bg.jpg');
  }
@keyframes chartjs-render-animation{from{opacity:.99}to{opacity:1}}.chartjs-render-monitor{animation:chartjs-render-animation 1ms}.chartjs-size-monitor,
.chartjs-size-monitor-expand,
.chartjs-size-monitor-shrink{position:absolute;direction:ltr;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1}.chartjs-size-monitor-expand>div{position:absolute;width:1000000px;height:1000000px;left:0;top:0}.chartjs-size-monitor-shrink>div{position:absolute;width:200%;height:200%;left:0;top:0}
</style>
<title>Inventory</title>
<div id="" class="container mx-auto" style="margin-top: 5%">
  <div class="card mx-auto text-center" style="width: 60%; background-color: rgb(244,71,107)">
    <div class="card mx-auto text-center" style="width: 100%; background-color: rgba(0,0,0,0.03)">
      <div class="card-body">
        <h1 class="display-4 card-title">Inventory</h1>                                                                                      </div>
    </div>
  </div>                                                                                                                                </div> 
<nav class="navbar navbar-light navbar-expand-lg fixed-top bg-dark" id="mainNav" style="filter: blur(0px) brightness(100%);">
	<h2> 
		<?php
		$name = $_SESSION['name'];
		echo "<span style='color: rgb(244,71,107)'>Welcome </span>";
		echo "<span style='color: rgba(255,255,255,0.7)'>$name</span>";
		?>
	</h2>
	<div class="container">
		<button data-toggle="collapse" data-target="#navbarResponsive" class="navbar-toggler" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div style="margin-left: 10%; margin-right: 0%">
  		<div class="collapse navbar-collapse" id="navbarResponsive">
    		<ul class="nav navbar-nav">
      		<li class="nav-item" role="presentation" style="padding:0px 24px">
						<a class="nav-link" style="text-transform: uppercase;color:rgba(255,255,255,0.7);font-family:Raleway;font-weight:800;"<?php if($tabIndex == 0) echo "active" ?>" href="#home?t=0">Dashboard</a></li>
        	<li class="nav-item" role="presentation" style="padding:0px 24px">
						<a class="nav-link" style="text-transform: uppercase;color:rgba(255,255,255,0.7);font-family:Raleway;font-weight:800"<?php if($tabIndex == 1) echo "active" ?>" href="#home?t=1">Inventory</a></li>
        	<li class="nav-item" role="presentation" style="padding:0px 24px">
						<a class="nav-link" style="text-transform: uppercase;color:rgba(255,255,255,0.7);font-family:Raleway;font-weight:800"<?php if($tabIndex == 2) echo "active" ?>" href="#home?t=2">Take Order</a></li>
        	<li class="nav-item" role="presentation" style="padding:0px 24px">
						<a class="nav-link" style="text-transform: uppercase;color:rgba(255,255,255,0.7);font-family:Raleway;font-weight:800"<?php if($tabIndex == 3) echo "active" ?>" href="#home?t=3">Fill Order</a></li>
      	</ul>
			</div>
		</div>
	</div>
	<form action="includes/logout.php" method="post">
		<button class="btn text-light ml-auto" name="logout" style="background-color: rgb(244,71,107);">
			<i class="icon-logout"></i>&nbsp;Log out
		</button>
	</form>
</nav>
<?php
} else {
    header("Location: ../#home");
}
?>
