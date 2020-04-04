<?php
	include 'header.php';
	include 'connect.php';
?>

	<div class="body-class">
		<h1>Add Post</h1>
		<?php
		$maxPosts = 100; // 100 posts per class
		//echo "max classes is $maxClasses <br>";
		$classCode = $_SESSION["classCode"];
		$sql = "SELECT * FROM forum WHERE class_code='$classCode' GROUP BY h_id";
		$result = mysqli_query($conn, $sql);
		$numPosts = mysqli_num_rows($result);
		$errorMsg = "";
		echo "the class has $numPosts posts <br>";
		if ($numPosts >= $maxPosts) {
			$pass = false;
			echo "You have exceeded the max amount of posts. Max amount is $maxPosts";
		} else {
			
			$heading = "";
			$description = "";
			$pass = false;

			//$name = $_SESSION["name"];
			$email = $_SESSION["email"];
			if (empty($_SESSION["uid"])) {
				$sql = "SELECT * FROM users WHERE user_email = '$email'";
				$result = mysqli_query($conn, $sql);
				$row = mysqli_fetch_assoc($result);
				$_SESSION['uid'] = $row['user_id'];
			}
			$uid = $_SESSION["uid"];

			$className = $_SESSION['className'];
			$classCode = $_SESSION['classCode'];
			echo "name is $className and code is $classCode <br>";

			if (!empty($className)) {
			    // get $classCode with $className and $uid
			    //$className = $_POST['members'];
			    /*
			    $sql = "SELECT * FROM classes WHERE user_id = '$uid' AND class_name = '$className'";
			    $result = mysqli_query($conn, $sql);
			    $row = mysqli_fetch_assoc($result);
			    $classCode = $row["class_code"];
			    */

			    if (isset($_POST["submit"])) {
			    	$pass = true;

			    	$heading = $_POST["heading"];
					$description = $_POST["description"];

					$heading = mysqli_real_escape_string($conn, $heading);
					$description = mysqli_real_escape_string($conn, $description);
			
			    	echo "<h3>$heading</h3><br>";
			    	$description = str_replace('\r\n', "<br>", $description);
			    	echo $description . "<br>";

			    	if (empty($heading)) {
			    		$pass = false;
			    		$errorMsg .= "there are empty fields";
			    	} else {
			    		if (empty($description)) {
				    		$pass = false;
				    		$errorMsg .= "there are empty fields";
				    	}
			    	}
			    	

			    } else {
			    	echo "not pressed";
			    }
		    } else {
		    	echo "empty class";
		    }

		    if ($pass == true) {
		    	$date = date("d/m/Y");
		    	// get $hid
		    	$sql = "SELECT DISTINCT h_id FROM forum WHERE class_code = '$classCode' AND comment = ''";
		    	$result = mysqli_query($conn, $sql);
				$hid = mysqli_num_rows($result);
		    	$hid++;
		    	
		    	$sql = "INSERT INTO forum (class_code, u_id, heading, description, date, h_id, deleted) VALUES ('$classCode', '$uid', '$heading', '$description', '$date', '$hid', 0);";
				//$_SESSION['uid'] = "SELECT user_id FROM users WHERE user_email='$email'";
				$connect = true;
				if (!mysqli_query($conn, $sql)) {	    
				    $connect = false;
				}

				mysqli_close($conn);

				if ($connect == true) { 
					// go to profile page
					header("Location: classes.php");
				} else {
					echo "There was an error adding the post";
				}
		    }
		}
		?>

		<form method="POST">
			<h3>Post heading:</h3>
			<textarea style="height: 70px;" name="heading" placeholder="Enter text here..."></textarea><br>
			<h3>Description:</h3>
			<textarea style="height: 200px;" name="description" placeholder="Enter text here..."></textarea><br>
			<input type="submit" name="submit" value="submit"><br><br>
			<span class="error"><?php echo $errorMsg; ?></span><br>
		</form>

	</div>
<?php
	include 'footer.php';
?>
