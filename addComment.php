<?php
	include 'header.php';
	include 'connect.php';

	$errorMsg = "";
	$pass = false;
	if (isset($_POST['addComment'])) {
		$hid = $_POST['addComment'];
		$_SESSION['hid'] = $hid;
	} else {
		if (isset($_POST["submit"])) {
			$pass = true;
			$hid = $_SESSION['hid'];
			$classCode = $_SESSION['classCode'];
			$uid = $_SESSION['uid'];

			// ensure that comment is not empty
			$comment = $_POST["comment"];
			$comment = mysqli_real_escape_string($conn, $comment);

			if (empty($comment)) {
				$pass = false;
				$errorMsg = "There cannot be empty fields";
			}

			if ($pass == true) {
				$date = date("d/m/Y");
		    	// get $hid
		    	$sql = "SELECT DISTINCT c_id FROM forum WHERE class_code = '$classCode' AND h_id = '$hid'";
		    	$result = mysqli_query($conn, $sql);
				$cid = mysqli_num_rows($result);
		    	//$cid++;

		    	$sql = "INSERT INTO forum (class_code, u_id, date, h_id, comment, c_id, deleted) VALUES ('$classCode', '$uid', '$date', '$hid', '$comment', '$cid', 0);";
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
					echo "There was an error adding the comment";
				}
			}
		}
	}


	
?>

	<div class="body-class">
		<h1>Add comment</h1>
		<form method="POST">
			<h3>Comment:</h3>
			<textarea style="height: 100px;" name="comment" placeholder="Enter text here..."></textarea><br>
			<input type="submit" name="submit" value="submit"><br><br>
			<span class="error"><?php echo $errorMsg; ?></span><br>
		</form>

	</div>
<?php
	include 'footer.php';
?>
