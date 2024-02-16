<?php 

    $host = "awseb-e-z6tqhgjkp2-stack-awsebrdsdatabase-fdrytig7x6au.cvekeitapkvt.us-east-1.rds.amazonaws.com";
    $username = "ebroot";
    $password = "Potato101";
    $database = "grocery_db";

    // Creating database connection
    $con = mysqli_connect($host, $username, $password, $database);

    // Check database connection
    if(!$con) {
        die("Connection Failed: ". mysqli_connect_error());
    }
?>