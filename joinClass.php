<?php
	include 'header.php';
	include 'connect.php';
	$errorMsg = "";
	$classCode = "";
	$pass = "";

	// person should already be logged in
	// so maybe we can save variables here
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if (isset($_POST['submit'])) { // confirm submit button has been pressed
			$pass = true;
			$classCode = $_POST["classCode"];
			
			$classCode = mysqli_real_escape_string($conn, $classCode);

			// check name is valid
			// first check if name is empty
			if (empty($classCode)) {
				$pass = false;
				$errorMsg .= "Field is required <br>";
			}


			// check that code exists
			$sql = "SELECT * FROM classes WHERE class_code='$classCode'";
		    $result = mysqli_query($conn, $sql);
			$resultCheck = mysqli_num_rows($result);
			if ($resultCheck == 0) {
				// no results return
				$errorMsg .= "No classes with that code exist";
				$pass = false;
			}

		    // check if that user has already made a class with that name
		    	// get user id of that person (in users)
		    if ($pass == true) {
		    	$uid = $_SESSION["uid"];
		    	// go through classes table and look for code

		    	// check if user is already in that class
		    	$sql = "SELECT * FROM classes WHERE class_code='$classCode' AND user_id='$uid'";
		    	$result = mysqli_query($conn, $sql);
				$resultCheck = mysqli_num_rows($result);
				if ($resultCheck > 0) {
					// user has already been added to that class
					// ie an entry containing a user with that id matches that class
					$row = mysqli_fetch_assoc($result);
					$className = $row["class_name"];
					$errorMsgMsg .= "You are already entered in that class : $className";
					$pass = false;
				}
		    }
		}
	}

	if ($pass == true) {
		// save variables
		// $_SESSION["uid"] = $_POST["uid"];
		// $_SESSION["email"] = $_POST["email"];

		$sql = "SELECT * FROM classes WHERE class_code='$classCode'";
		$result = mysqli_query($conn, $sql);
		$data = mysqli_fetch_assoc($result); // get the data from that user
		$className = $data['class_name'];
		$classYear = $data['year'];
		echo "class name is $className";
		
		$sql = "INSERT INTO classes (user_id, class_code, class_name, admin, year) VALUES ('$uid', '$classCode', '$className', 0, '$classYear');";

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
			echo "There was an error adding the class. try again or contact support";
		}
		
	}
?>

	<div class="body-class">
		<h1>Join class</h1>
		<p>Enter the code to join the class</p>
		<form action="" method="POST">
			Code: <input type="text" name="classCode" value="<?php echo $classCode; ?>"><br>
			<span class="error"><?php echo $errorMsg; ?></span><br>
			<input type="submit" name="submit">
		</form>

	</div>
<?php
	include 'footer.php';
?>
