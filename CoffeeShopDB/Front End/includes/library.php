<?php
include_once("dbconnect.php");
function cleanInput($input) {
    return htmlspecialchars(stripslashes(trim($input)));
}

function login($user, $ssn) {
    global $connection;
    cleanInput($user);
    cleanInput($ssn);
    $sql = "select * from employee";
    $query = pg_query($connection, $sql);
    $row;
    while ($row = pg_fetch_array($query)) {
	if ($ssn == substr($row[2], -4) && $user == substr($row[1], 0, strlen($user))) {
	    break;
	}
    }
    $sql = "select * from employeeInfo where employeeid=$row[0]";
    $query = pg_query($connection, $sql);
    $row = pg_fetch_array($query);
    return $row;
}

function store($details) {
    $_SESSION['sid'] = $details['shopid'];
    $_SESSION['eid'] = $details['employeeid'];
    $_SESSION['mid'] = $details['managerid'];
    $_SESSION['name'] = $details['name'];
    $_SESSION['job'] = $details['department_name'];
    $_SESSION['active'] = true;
    return ;
}
?>
