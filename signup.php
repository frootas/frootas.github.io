<?php
	include 'header.php';
	include 'connect.php';
	$errName = "";
	$name = "";
	$errEmail = "";
	$email = "";
	$errPwd = "";
	$pwd = "";
	$pass = false;

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if (isset($_POST['submit'])) { // confirm submit button has been pressed
			$pass = true;
			$name = $_POST["name"];
			$email = $_POST["email"];
			$pwd = $_POST["pwd"];

			$name = mysqli_real_escape_string($conn, $name);
			$email = mysqli_real_escape_string($conn, $email);
			$pwd = mysqli_real_escape_string($conn, $pwd);


			// check name is valid
			// first check if name is empty
			if (empty($name)) {
				$pass = false;
				$errName .= "Name is required <br>";
			}

			// check that name only contains letters
			if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
				$pass = false;
		    	$errName .= "Only letters and white space allowed <br>"; 
		    }

		    // now check email
		    // first check that email is empty
		    if (empty($email)) {
				$pass = false;
				$errEmail .= "E-mail is required <br>";
			} else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				// check for valid email
				$pass = false;
		    	$errEmail .= "Invalid email format <br>"; 
		    }

		    // check if email has been taken
		   	if ($pass == true) {
				$sql = "SELECT * FROM users WHERE user_email='$email'";
				$result = mysqli_query($conn, $sql);
				$resultCheck = mysqli_num_rows($result);

				if ($resultCheck > 0) {
					// user exists
					$errEmail .= "A user with that email already exists <br>";
					// echo "user exists";
					$pass = false;
				}
			}


		    // check password
		    if (empty($pwd)) {
				$pass = false;
				$errPwd .= "Password is required <br>";
			}
		}
	}

	if ($pass == true) {
		// save variables
		$_SESSION["name"] = $_POST["name"];
		$_SESSION["email"] = $_POST["email"];
		
		// add user
		$sql = "INSERT INTO users (user_email, user_name, user_pwd) VALUES ('$email', '$name', '$pwd');";
		//$_SESSION['uid'] = "SELECT user_id FROM users WHERE user_email='$email'";
		$connect = true;
		if (!mysqli_query($conn, $sql)) {	    
		    $connect = false;
		}

		mysqli_close($conn);

		if ($connect == true) { 
			// go to profile page
			header("Location: profile.php");
		} else {
			echo "There was an error adding the account";
		}
	}

?>
	<div class="body-class">
		<h1>Sign up page</h1>
		<p>This is the sign up page</p>
		<form action="" method="POST">
			Name: <input type="text" name="name" value="<?php echo $name; ?>"><br>
			<span class="error"><?php echo $errName; ?></span><br>
			E-mail: <input type="text" name="email" value="<?php echo $email; ?>"><br>
			<span class="error"><?php echo $errEmail; ?></span><br>
			Password: <input type="password" name="pwd"><br>
			<span class="error"><?php echo $errPwd; ?></span><br>
			<input type="submit" name="submit">
		</form>
	</div>
<?php
	include 'footer.php';
?>
