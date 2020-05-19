<?php
$dbServer = "coffeeshopdb.cniwq5h2gums.us-east-2.rds-preview.amazonaws.com";
$dbName = "CoffeeShopDB";
$dbUser = "coffeeshop";
$dbPass = "cmps3420";
$connection = pg_connect("host=$dbServer dbname=$dbName user=$dbUser password=$dbPass connect_timeout=3");
?>



