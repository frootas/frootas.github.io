<?php
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "login_system";
	//$tablename = "users";

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	//$error = false;

	// Check connection
	if (!$conn) {
	// if (!$conn) {
	    die("Connection failed: " . mysqli_connect_error());
	    //$error = true;
	} 

	/*
	// create database
	$sql = "CREATE DATABASE myDB";
	if (mysqli_query($conn, $sql)) {
	    echo "Database created successfully";
	} else {
	    echo "Error creating database: " . $conn->error;
	}
	*/

?>