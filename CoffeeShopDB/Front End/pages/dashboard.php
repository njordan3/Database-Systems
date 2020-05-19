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
<title>Dashboard</title>
</head>
<body>
<div id="addEmployeeFormHeader" class="container mx-auto" style="margin-top: 5%; display: none;">
	<div class="card mx-auto text-center" style="width: 60%; background-color: rgb(244,71,107)">
		<div class="card mx-auto text-center" style="width: 100%; background-color: rgba(0,0,0,0.03)">
			<div class="card-body">
				<h1 class="display-4 card-title">Add Employee</h1>
			</div>
		</div>
	</div>
</div>
<div class="container mx-auto px-0" id="addEmployeeForm" style="margin-top: 1%; display: none;">
	<div class="card">
		<div class="card-body" style="background-color: rgba(0,0,0,0.03);">
			<button onclick=showEmployeeList() class='btn text-white pull-right' data-toggle='tooltip' data-bs-tooltip='' type='button' style='background-color: rgb(244,71,107);' title='Return to Dashboard'>
				<i class='fa fa-remove'></i>
			</button>
    	<div class="row">
      	<div class="col-md-4">
					<label>Employee Name</label>
					<input value="" id="empName" class="form-control-lg" type="text" name="Customer" style="width: 100%;" placeholder="First Last">
				</div>
        <div class="col-md-3">
					<label>Birth Date</label>
					<input value="" id="empBD" class="form-control-lg" type="date" style="width: 100%;">
				</div>
        <div class="col-md-2">
					<label>Sex</label>
					<select id="empSex" class="border rounded custom-select custom-select-lg" style="width: 100%">
						<option value="" selected disabled hidden></option>
						<option value="Male">Male</option>
						<option value="Female">Female</option>
					</select>
				</div>
      </div>
      <div class="row align-items-end">
      	<div class="col-md-3">
					<label>SSN</label>
					<input value="" id="empSSN" class="form-control-lg" type="text" style="width: 100%;" placeholder="###-##-####">
				</div>
      </div>
      <div class="row">
      	<div class="col-md-4">
					<label>Street Address</label>
						<input value="" id="empAddr" class="form-control-lg" type="text" name="Special_instr" style="width: 100%;" placeholder="123 Example Street">
				</div>
      </div>
      <div class="row align-items-end">
      	<div class="col-md-2">
					<label>Department</label>
					<select id="empDept" class="border rounded custom-select custom-select-lg" style="100%">
						<option value="" selected disabled hidden></option>
						<option value="Cashier">Cashier</option>
						<option value="Barista">Barista</option>
					</select>
				</div>
        <div class="col-md-2">
					<label>Starting Wage</label>
						<input value="" id="empWage" class="form-control-lg" type="number" style="width: 100%;" min="12.50" step="0.50" value="12.50">
				</div>
        <div class="col-md-2">
					<label>Starting Date</label>
					<input value="" id="empSDate" class="form-control-lg" type="date">
				</div>
      </div>
    </div>
		<div class="card-footer">
    	<div class="form-group">
      	<div class="row align-items-end">
        	<div class="col-md-3">
						<label>Add Employee</label>
						<button onclick=addEmployee() class="btn btn-lg" type="button" data-toggle='tooltip' data-bs-tooltip='' title='Add Employee' style="width: 100%;background-color: rgb(244,71,107);">Complete Form</button>
					</div>
        </div>
      </div>
    </div>
	</div>
</div>
<div id="employeeListHeader" class="container mx-auto" style="margin-top: 5%;">
	<div class="card mx-auto text-center pulse animated" style="width: 60%; background-color: rgb(244,71,107)">
		<div class="card mx-auto text-center" style="width: 100%; background-color: rgba(0,0,0,0.03)">
			<div class="card-body">
			<?php if ($_SESSION['job'] == "Manager") { ?>
				<button onclick=showEmployeeForm() class='btn pull-right bg-white' data-toggle='tooltip' data-bs-tooltip='' title="Add A New Employee" type="button" id="addEmployee" style="color: rgb(244,71,107);">
				<i class="fa fa-plus"></i>
				</button>
			<?php } ?>
				<h1 class="display-4 card-title">Employees</h1>
			</div>
		</div>
	</div>
</div>
<div id="employeeList" class="container mx-auto" style="display:; margin-top: 1%;">
	<div class="card mx-auto" style="width: 60%; background-color: transparent">
    			<?php
    			$dbServer = "coffeeshopdb.cniwq5h2gums.us-east-2.rds-preview.amazonaws.com";
    			$dbName = "CoffeeShopDB";
    			$dbUser = "coffeeshop";
    			$dbPass = "cmps3420";
    			$connection = pg_connect("host=$dbServer dbname=$dbName user=$dbUser password=$dbPass connect_timeout=3");
					if ($connection) {
						$shop = $_SESSION['sid'];
      			$sql = "select * from employeeInfo where shopid=$shop";
      			$query = pg_query($connection, $sql);
						while ($row = pg_fetch_array($query)) { 
							if ($row[4] == "Cashier") {
    						?> 
								<div class="card border-dark pulse animated" style="width: 100%;margin-right: auto;margin-left: auto;margin-top: 1%;background-position: 70%;background-size: contain;background-repeat: no-repeat; background-image: url('images/register.jpg');">
								<?php
							} else if ($row[4] == "Barista") {
    						?> 
								<div class="card border-dark pulse animated" style="width: 100%;margin-right: auto;margin-left: auto;margin-top: 1%;background-position: 70%;background-size: contain;background-repeat: no-repeat; background-image: url('images/pour.jpg');">
								<?php
							} else {
    						?> 
								<div class="card border-dark pulse animated" style="width: 100%;margin-right: auto;margin-left: auto;margin-top: 1%;background-position: 100%;background-size: contain;background-repeat: no-repeat; background-image: url('images/manager.jpg');">
								<?php
							}
							$now = time();
 							$dob = strtotime($row[6]);
 							$difference = $now - $dob;
 							//There are 31556926 seconds in a year.
							$age = floor($difference / 31556926);
							echo "<div class='card-body'>";
								if ($_SESSION['job'] == "Manager") {
								echo "<form action='includes/removeEmployee.php' method='post'>";
									echo "<input type='hidden' name='remove' value='$row[1]'>";
									echo "<button class='btn text-white pull-right' name='removebtn' data-toggle='tooltip' data-bs-tooltip='' type='submit' style='background-color: rgb(244,71,107);' title='Remove $row[3]'><i class='fa fa-remove'></i></button>";
									echo "</form>";	
								}
								echo "<h4 class='display-4 card-title' style='color: rgb(244,71,107);'>$row[3]</h4>";
            		echo "<h6 class='text-muted card-subtitle mb-2'>$row[4]</h6>";
            		echo "<p class='card-text' style='width: 90%;'>$row[5], $age, $row[7]</p>";
        			echo "</div>";
    				echo "</div>";
						}
					}
 					?>
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
<!--
<div class="container py-5 mt-5">
	<div class="row py-5">
  	<div class="col-lg-10 mx-auto">
    	<div class="card rounded shadow border-0">
      	<div class="card-body p-5 bg-white rounded">			
					<canvas id="myChart" width="100" height="100"></canvas>
					<script>
var ctx = document.getElementById('myChart').getContext('2d');
var test = [12, 19, 3, 5, 2, 3];
var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
        datasets: [{
            label: '# of Votes',
            data: test,
            backgroundColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        }
    }
});
						</script>
					</div>
				</div>
			</div>
		</div>
	</div>

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
       							<?php /*
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
										 */?>
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
</div>-->
<?php
} else {
    header("Location: ../#home");
}
?>
