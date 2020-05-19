<?php
$tabIndex = 0;
if(isset($_GET['t'])) {
    $tabIndex = $_GET['t'];
}

session_start();
include_once("includes/library.php");
if ($_SESSION['active']) {
?>
<style>
  html {
    background: linear-gradient(rgba(47, 23, 15, 0.65), rgba(47, 23, 15, 0.65)),url('images/bg.jpg');
  }
</style>
<title>Dashboard</title>

<script type="text/javascript" class="init">	
$(document).ready(function() {
	$('#example').DataTable();
} );
</script>
</head>
<body>
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
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="nav navbar-nav mx-auto">
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
        <form action="includes/logout.php" method="post">
	    <button class="btn text-light ml-auto" name="logout" style="background-color: rgb(244,71,107);">
	    	<i class="icon-logout"></i>&nbsp;Log out
	    </button>
	</form>
    </nav>
<div class="bootstrap_datatables">
	<div class="container py-5">
  	<header class="text-center text-black">
    	<h1 class="display-4">Bootstrap Datatables</h1>
  	</header>
  		<div class="row py-5">
    		<div class="col-lg-10 mx-auto">
      		<div class="card rounded shadow border-0">
        		<div class="card-body p-5 bg-white rounded">
          		<div class="table-responsive">
            		<table id="example" style="width:100%" class="table table-striped table-bordered">
    							<div id="example_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
										<div class="row"><div class="col-sm-12 col-md-6">
											<div class="dataTables_length" id="example_length">
											<label>Show 
											<select name="example_length" aria-controls="example" class="custom-select custom-select-sm form-control form-control-sm">
											<option value="10">10</option>
											<option value="25">25</option>
											<option value="50">50</option>
											<option value="100">100</option>
											</select> entries
										</label>
										</div>
									</div>
									<div class="col-sm-12 col-md-6">
										<div id="example_filter" class="dataTables_filter">
										<label>Search:
										<input type="search" class="form-control form-control-sm" placeholder="" aria-controls="example">
										</label>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
									<table id="example" style="width: 100%;" class="table table-striped table-bordered dataTable no-footer" role="grid" aria-describedby="example_info">
              			<thead>
                			<tr role="row">
												<th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="ShopID: activate to sort column ascending" style="width: 126px;">ShopID</th>
												<th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="EmployeeID: activate to sort column ascending" style="width: 194px;">EmployeeID</th>
												<th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="Name: activate to sort column ascending" style="width: 85px;">Name</th>
												<th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="Job: activate to sort column ascending" style="width: 28px;">Job</th>
              				</tr>
										</thead>
              			<tbody>  
       							<?php
										$dbServer = "coffeeshopdb.cniwq5h2gums.us-east-2.rds-preview.amazonaws.com";
										$dbName = "CoffeeShopDB";
										$dbUser = "coffeeshop";
										$dbPass = "cmps3420";
										$connection = pg_connect("host=$dbServer dbname=$dbName user=$dbUser password=$dbPass connect_timeout=3");
										if ($connection) {
	  									$shop = $_SESSION['sid'];
	 	 									$emp = $_SESSION['eid'];
	  									$sql = "select * from employeeInfo";
	  									$query = pg_query($connection, $sql);
											$count = 0;
	  									while ($row = pg_fetch_array($query)) {
												if ($count % 2 == 0) {
													echo "<tr role='row' class='even'>";
												} else {
													echo "<tr role='row' class='odd'>";
												}
	    									echo "<td>$row[0]</td>";
	    									echo "<td>$row[1]</td>";
	    									echo "<td>$row[2]</td>";
	    									echo "<td>$row[3]</td>";
	    									echo "</tr>";
												$count = $count+1;
	  									}
										} else {
	  									echo "<tr>";
	  									echo "<td>No Server Connection</td>";
	  									echo "<td>No Server Connection</td>";
	  									echo "<td>No Server Connection</td>";
	  									echo "<td>No Server Connection</td>";
	  									echo "</tr>";
										}
      							?>
 										</tbody>
	  							</table>
									</div>
								</div>
							<div class="row">
								<div class="col-sm-12 col-md-5">
									<div class="dataTables_info" id="example_info" role="status" aria-live="polite">
									Showing 1 to 10 of 30 entries
									</div>
								</div>
								<div class="col-sm-12 col-md-7">
									<div class="dataTables_paginate paging_simple_numbers" id="example_paginate">
									<ul class="pagination">
										<li class="paginate_button page-item previous disabled" id="example_previous">
											<a href="#" aria-controls="example" data-dt-idx="0" tabindex="0" class="page-link">Previous</a>
										</li>
										<li class="paginate_button page-item active">
											<a href="#" aria-controls="example" data-dt-idx="1" tabindex="0" class="page-link">1</a>
										</li>
										<li class="paginate_button page-item ">
											<a href="#" aria-controls="example" data-dt-idx="2" tabindex="0" class="page-link">2</a>
										</li>
										<li class="paginate_button page-item ">
											<a href="#" aria-controls="example" data-dt-idx="3" tabindex="0" class="page-link">3</a>
										</li>
										<li class="paginate_button page-item next" id="example_next">
											<a href="#" aria-controls="example" data-dt-idx="4" tabindex="0" class="page-link">Next</a>
										</li>
									</ul>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
</div>
<?php
} else {
    header("Location: ../#home");
}
?>
