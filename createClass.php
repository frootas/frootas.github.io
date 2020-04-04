<?php
	include 'header.php';
	include 'connect.php';
	$error = "";
	$className = "";
	$pass = "";

	// person should already be logged in
	// so maybe we can save variables here
	
		// confirm that user has not exceeded max classes
	$maxClasses = 50;
	echo "max classes is $maxClasses <br>";
	$uid = $_SESSION["uid"];
	$sql = "SELECT * FROM classes WHERE user_id='$uid' AND admin=1";
	$result = mysqli_query($conn, $sql);
	$numClasses = mysqli_num_rows($result);
	echo "the user has $numClasses classes <br>";
	if ($numClasses >= $maxClasses) {
		$pass = false;
		$error .= "You have exceeded the max amount of classes. Max amount is $maxClasses";
	} else {
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {		
			if (isset($_POST['submit'])) { // confirm submit button has been pressed
				$pass = true;
				$className = $_POST["className"];
				
				$className = mysqli_real_escape_string($conn, $className);

				// check name is valid
				// first check if name is empty
				if (empty($className)) {
					$pass = false;
					$error .= "Name is required <br>";
				}

				if (empty($classYear)) {
					$pass = false;
					$error .= "Year is required <br>";
				}

				// check that name only contains letters
				if (!preg_match("/^[a-zA-Z ]*$/",$className)) {
					$pass = false;
			    	$error .= "Only letters and white space allowed <br>"; 
			    }

			    // check if that user has already made a class with that name
			    	// get user id of that person (in users)
			    if ($pass == true) {
			    	//$email = $_SESSION["email"];
			    	if (!empty($email)) {
			    		/*	
			    		$sql = "SELECT * FROM users WHERE user_email='$email'";
						$result = mysqli_query($conn, $sql);
						$data = mysqli_fetch_assoc($result); // get the data from that user
						$uid = $data['user_id'];
						*/
						// check that user id with user id of classes
						$sql = "SELECT * FROM classes WHERE user_id='$uid' AND class_name='$className'";
						$result = mysqli_query($conn, $sql);
						$resultCheck = mysqli_num_rows($result);
						if ($resultCheck > 0) {
							// user exists
							$error .= "You already have a class with that name <br>";
							// echo "user exists";
							$pass = false;
						}
			    	}
			    }
			}
		}
	}	

	if ($pass == true) {
		// save variables
		// $_SESSION["uid"] = $_POST["uid"];
		// $_SESSION["email"] = $_POST["email"];

		// generate class code
		$classCode = getClassCode();
		echo "class code is $classCode <br>";

		$validCode = 0;
		while ($validCode == 0) {
			// confirm class code has not been used
			$sql = "SELECT * FROM classes WHERE class_code='$classCode' AND admin=1";
			$result = mysqli_query($conn, $sql);
			$resultCheck = mysqli_num_rows($result);
			if ($resultCheck > 0) {
				// code exists
				$classCode = getClassCode();
			} else {
				$validCode = 1;
			}
		}


		$sql = "INSERT INTO classes (class_code, class_name, user_id, admin, year) VALUES ('$classCode', '$className', '$uid', 1, '$classYear');";

		$connect = true;
		if (!mysqli_query($conn, $sql)) {	    
		    $connect = false;
		}

		mysqli_close($conn);

		if ($connect == true) { 
			// go to profile page
			//echo "the class was successfully generated";
			header("Location: profile.php");
		} else {
			echo "There was an error adding the class. try again or contact support <br>";
		}
		
	}

	function getClassCode() {
		$letters = '1234567890QWERTYUIOPASDFGHJKLZXCVBNM';
    	$s = '';
    	$lettersLength = strlen($letters)-1;
    	$length = 6; // max cominations is (10+26) ^ 6 // over 2 billion

    	for($i = 0 ; $i < $length ; $i++) {
        	$s .= $letters[rand(0,$lettersLength)];
    	}

    	return $s;
	}

?>

	<div class="body-class">
		<h1>Add class</h1>
		<form action="" method="POST">
			Name: <input type="text" name="className" value="<?php echo $className; ?>"><br>
			Year (7-12): <input type="text" name="classYear" value="<?php echo $classYear; ?>"><br>
			<span class="error"><?php echo $error; ?></span><br>
			<input type="submit" name="submit">
		</form>

	</div>
<?php
	include 'footer.php';
?>
