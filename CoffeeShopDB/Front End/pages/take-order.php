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
</style>
<title>Take Order</title>
</head>
<body>
<div id="" class="container mx-auto" style="margin-top: 5%">
  <div class="card mx-auto text-center" style="width: 60%; background-color: rgb(244,71,107)">
    <div class="card mx-auto text-center" style="width: 100%; background-color: rgba(0,0,0,0.03)">
      <div class="card-body">
        <h1 class="display-4 card-title">Take Order</h1>                                                                                      </div>
    </div>
  </div>                                                                                                                                </div> 
<div class="container mx-auto px-0" style="margin-top: 2%;background-color: transparent;" ng-controller=itemsController>
	<div class="card">
  	<div class="card-header">
    	<h5 class="mb-0" style="font-size: 40px;" id="total_title">Total:</h5>
      <h5 class="mb-0" style="font-size: 30px;" id="item_title">Item:</h5>
    </div>
  	<div class="card-body d-flex flex-column" style="background-color: rgba(0,0,0,0.03);">
			<div class="form-group">
				<div class="row">
        	<div class="col-md-4">
						<label>*&nbsp;Customer Name</label>
						<input onchange=saveCustomer() class="form-control-lg" type="text" id="cust" name="Customer" style="width: 100%;">
					</div>
        	<div class="col-md-3">
						<label>*&nbsp;Customer Phone Number</label>
						<input onchange=saveCustomerPhone() class="form-control-lg" type="text" id="cust_num" style="width: 100%;" placeholder="###-###-####">
					</div>
          <div class="col-md-4 text-right">
          	<p style="width: 100%;">* = Optional</p>
          </div>
        </div>	
      	<div class="row align-items-end">
        	<div class="col-md-3">
						<label>Menu Item</label>
						<select class="border rounded custom-select custom-select-lg" name="Item" id="item_select" onchange=changeUnitCost()>
							<optgroup label="Menu Items">
								<option value="" selected disabled hidden>Product</option>
								<?php
         		 			$dbServer = "coffeeshopdb.cniwq5h2gums.us-east-2.rds-preview.amazonaws.com";
									$dbName = "CoffeeShopDB";
									$dbUser = "coffeeshop";
									$dbPass = "cmps3420";
									$connection = pg_connect("host=$dbServer dbname=$dbName user=$dbUser password=$dbPass connect_timeout=3");
									if ($connection) { 
										$sql = "select distinct product_name from menu_item";
										$query = pg_query($connection, $sql);
										while ($row = pg_fetch_array($query)) {
												echo "<option value='$row[0]' >$row[0]</option>";
										}
									}
								?>
							</optgroup>
						</select>
					</div>
					<div class="col-auto">
						<select class="border rounded form-control-lg" name="Size" id="size_select" onchange=changeUnitCost()>
							<optgroup label="Sizes">
								<option value="" selected disabled hidden>Sizes</option>
								<option value="Small" >Small</option>
								<option value="Medium">Medium</option>
								<option value="Large">Large</option>
							</optgroup>
						</select>
					</div>
          <div class="col-md-3">
						<label>Unit Cost</label>
						<input id="unit_cost" class="form-control-lg" type="text" readonly="true" name="Unit Cost" min="0">
					</div>
          <div class="col-md-2">
						<label>Choose Quantity</label>
						<input id="quantity_select" class="form-control-lg" type="number" name="Quantity" min="1" value="1" style="width: 100%;">
					</div>
        </div>
				<div class="row align-items-end">
        	<div class="col">
						<label>*&nbsp;Special Instructions</label>
						<input class="form-control-lg" id="instr" type="text" name="Special_instr" style="width: 100%;">
					</div>
          <div class="col-md-2">
						<label>Add to Cart</label>
						<button type="button" class="btn btn-lg" onclick=addToCart() style="width: 100%;background-color: rgb(244,71,107);">Add</button>
					</div>
        </div>
      <div class="table-responsive border rounded" style="width: 100%; margin-top: 1%">
				<div id="cart_table"></div>
      </div>
		</div>
    <div class="card-footer">
    	<div class="form-group">
      	<div class="row align-items-end">
        	<div class="col-md-3">
						<label>Void Last Transaction</label>
						<button type="button" class="btn btn-lg" onclick=removeItem() style="width: 100%;background-color: rgb(244,71,107);">Void Last Transaction</button>
					</div>
          <div class="col-md-3">
						<label>Void Entire Transaction&nbsp;</label>
						<button type="button" class="btn btn-lg" onclick=emptyCart() style="width: 80%;background-color: rgb(244,71,107);">Cancel Sale</button>
					</div>
          <div class="col-md-2">
						<label>Discount</label>
						<input name="discount" class="form-control-lg" id="discount" onchange=applyDiscount() type="number" value="0" style="width: 100%;" step="10" max="100" min="0">
					</div>
          <div class="col-md-4">
						<label>Complete Sale</label>
						<button class="btn btn-lg" type="button" onclick=transferData() style="width: 100%;background-color: rgb(244,71,107);">Complete Sale</button>
					</div>
        </div>
      </div>
    </div>
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
