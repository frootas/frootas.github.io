<?php
	session_start();

	$loggedIn = false;
	if (!empty($_SESSION["name"])) { 
		$loggedIn = true;
	}

?>
<!DOCTYPE html>
<html>
<head>
	<base href="http://localhost/php/login/index.php" /> 
	<!-- 
	<base href="http://my-website-url.com/" />
	-->
	<title>login system</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

	<nav>
		<ul>
			<li><a href="index.php">Home</a></li>
			<li><a href="#">About</a></li>
			<li><a href="#">Settings</a></li>
			<li><a href="check.php">check</a></li>
			<li><a href="topics.php">Topics</a></li>
			<span class="loginNav">
				<?php 
				if ($loggedIn == false) {
					// show sign-up/sign-in
					echo '<li><a href="login.php">login</a></li>';
					echo '<li><a href="signup.php">signup</a></li>';
				} else {
					echo '<li><a href="profile.php">' . $_SESSION["name"] . '</a></li>';
					echo '<li><a href="createClass.php">Create Class</a></li>';
					echo '<li><a href="joinClass.php">Join Class</a></li>';
					echo '
						<li><form action="signout.php" method="POST">
	        				<input type="submit" name="submit" value="signout">
	        			</form></li>
					';
					//echo '<li><a href="signout.php">signout</a></li>'; 
				}
				?>
			</span>
		</ul>		
	</nav>
