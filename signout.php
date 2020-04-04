<?php
	//$_SESSION["name"] = NULL;
	//include 'header.php';
	if (isset($_POST['submit'])) { // confirm submit button has been pressed
		session_start();
		// remove all session variables
		session_unset(); 

		// destroy the session 
		session_destroy();
		
	}
	header("Location: index.php");
	exit();

	//echo '<a href="index.php">home</a>';
	
?>
