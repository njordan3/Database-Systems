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
@media (min-width: 1200px) {
    .container-order{
        max-width: 80%;
    }
}
  html {
    background: linear-gradient(rgba(47, 23, 15, 0.65), rgba(47, 23, 15, 0.65)),url('images/bg.jpg');
  }
</style>
<title>Fill Order</title>
</head>
<body>

<div class="container-order mx-auto" style="margin-top: 5%">
	<div id="singleHeader" style="display:">
  	<div class="card mx-auto text-center" style="width: 60%; background-color: rgb(244,71,107)">
    	<div class="card mx-auto text-center" style="width: 100%; background-color: rgba(0,0,0,0.03)">
      	<div class="card-body">
        	<h1 id="singleHeaderText" class="display-4 card-title">Fill Order</h1>
      	</div>
			</div>
		</div>
	</div>
	<div id="doubleHeader" class="row" style="margin-top: 1%; display: none">
		<div class="col">
  		<div class="card" style="background-color: rgb(244,71,107)">
      	<div class="card-body text-center">
        	<h1 class="display-4 card-title">Filled Orders</h1>
      	</div>
			</div>
		</div>
		<div class="col">
  		<div class="card" style="background-color: rgb(244,71,107)">
      	<div class="card-body text-center">
        	<h1 class="display-4 card-title">Un-filled Orders</h1>
      	</div>
			</div>
		</div>
  </div>
  <div id="cont_select" class="container">
	  <div class="row" style="margin-top: 1%">
		  <div class="col-md-2">
			  <select id="viewSelect" onchange=loadSOrderView() class="form-control-lg">
				  <option value="1" >View Un-filled</option>
				  <option value="2">View Filled</option>
				  <option value="3">View Both</option>
			  </select>
		  </div>
		  <div class="col-md-2">
			  <select id="viewDateSelect" onchange=loadSOrderDateView() class="form-control-lg" style="display:none">
				  <option value="1" >Today</option>
				  <option value="2">This Week</option>
				  <option value="3">All</option>
			  </select>
		  </div>
		</div>
	</div>
</div>

<?php
$dbServer = "coffeeshopdb.cniwq5h2gums.us-east-2.rds-preview.amazonaws.com";
$dbName = "CoffeeShopDB";
$dbUser = "coffeeshop";
$dbPass = "cmps3420";
$connection = pg_connect("host=$dbServer dbname=$dbName user=$dbUser password=$dbPass connect_timeout=3");
if ($connection) {
  $shop = $_SESSION["sid"];
	$empID = $_SESSION["eid"];
?>
<div id="cont" class="container">
<div class="row">
	<div id="filled_t" class="col mx-auto" style="margin-top: 1%; display: none">
      <?php
      $sql = "select distinct sorderid,pay_date,customer_name from filledSOrderInfo where shopid=$shop";
			$query = pg_query($connection, $sql);
			while ($row = pg_fetch_array($query)) {
			?>
  		<div id="f-card" class="card mx-auto" style="margin-top: 1%;">
    		<div class="card-body">
      		<div class="row">
						<div class="col">
							<?php $d = date("m-d-Y h:i:sa",strtotime($row[1]));
										echo "<h4>From $row[2] at $d</h4>"; ?>
							<?php /*echo "<h6 class='text-muted mb-2'>Time Received: $row[5]</h6>";*/ ?>
          	</div>
						<div class="mb-2" style="margin-right: 2%;">
							<?php echo "<input type='hidden' id='s$row[0]' value='$row[0]'>"; ?>
							<?php echo "<input type='hidden' id='e$row[0]' value='$empID'>"; ?>
            <button onclick=generatePDF(<?php echo $row[0]; ?>) class="btn pull-right" type="button" style="background-color: rgb(244,71,107);">View order</button>
						</div>
    			</div>
        	<div class="table-responsive-sm table-bordered" style="width: 100%;margin-top: 1%;">
        		<table class="table table-striped table-bordered table-condensed table-sm">
         			<thead>
            		<tr></tr>
            	</thead>
							<tbody>
								<?php
									$sql = "select * from filledSOrderInfo where shopid=$shop AND sorderid=$row[0]";
									$query2 = pg_query($connection, $sql);
									while ($row2 = pg_fetch_array($query2)) {
										$row_data = "$row2[7] $row2[6] ($row2[4]) x $row2[3]";
										echo "<tr>";
                		echo "<td>$row_data</td>";
										echo "</tr>";
									}
								?>
            	</tbody>
          	</table>
        	</div>
      	</div>
    	</div>
			<?php
			}
    ?>
  </div>
	<div id="unfilled_t" class="col mx-auto" style="margin-top: 1%; display:">
      <?php
      $sql = "select distinct sorderid,pay_date,customer_name from sorderInfo where shopid=$shop";
			$query = pg_query($connection, $sql);
			while ($row = pg_fetch_array($query)) {
			?>
  		<div id="unf-card" class="card mx-auto pulse animated" style="margin-top: 1%;">
    		<div class="card-body">
      		<div class="row">
						<div class="col">
							<?php $d = date("m-d-Y h:i:sa",strtotime($row[1]));
										echo "<h4>From $row[2] at $d</h4>"; ?>
							<?php /*echo "<h6 class='text-muted mb-2'>Time Received: $row[5]</h6>";*/ ?>
          	</div>
						<div class="mb-2" style="margin-right: 2%;">
							<?php echo "<input type='hidden' id='sordID$row[0]' value='$row[0]'>"; ?>
							<?php echo "<input type='hidden' id='empID$row[0]' value='$empID'>"; ?>
							<?php echo "<input type='hidden' id='time$row[0]' value='$row[1]'>"; ?>
              <button onclick=fillSOrder(<?php echo $row[0]; ?>) class="btn pull-right" type="button" style="background-color: rgb(244,71,107);">Complete Order</button>
						</div>
    			</div>
        	<div class="table-responsive-sm table-bordered" style="width: 100%;margin-top: 1%;">
        		<table class="table table-striped table-bordered table-condensed table-sm">
         			<thead>
            		<tr></tr>
            	</thead>
							<tbody>
								<?php
									$sql = "select * from sorderInfo where shopid=$shop AND sorderid=$row[0]";
									$query2 = pg_query($connection, $sql);
									while ($row2 = pg_fetch_array($query2)) {
										$row_data = "$row2[7] $row2[6] ($row2[4]) x $row2[3]";
										echo "<tr>";
                		echo "<td>$row_data</td>";
										echo "</tr>";
									}
								?>
            	</tbody>
          	</table>
        	</div>
      	</div>
    	</div>
			<?php
			}
		}
		?>
  </div>
</div>
</div>
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
		<div style="margin-left: 18%; margin-right: 0%">
  		<div class="collapse navbar-collapse" id="navbarResponsive">
    		<ul class="nav navbar-nav">
      		<li class="nav-item" role="presentation" style="padding:0px 24px">
						<a class="nav-link" style="text-transform: uppercase;color:rgba(255,255,255,0.7);font-family:Raleway;font-weight:800;"<?php if($tabIndex == 0) echo "active" ?>" href="#home?t=0">Home</a></li>
        	<li class="nav-item" role="presentation" style="padding:0px 24px">
						<a class="nav-link" style="text-transform: uppercase;color:rgba(255,255,255,0.7);font-family:Raleway;font-weight:800"<?php if($tabIndex == 2) echo "active" ?>" href="#home?t=2">Take Order</a></li>
        	<li class="nav-item" role="presentation" style="padding:0px 24px">
						<a class="nav-link" style="text-transform: uppercase;color:rgba(255,255,255,0.7);font-family:Raleway;font-weight:800"<?php if($tabIndex == 3) echo "active" ?>" href="#home?t=3">View Orders</a></li>
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
