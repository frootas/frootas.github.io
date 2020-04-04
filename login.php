<?php
	include 'header.php';
	include 'connect.php';

	$email = "";
	$pwd = "";
	$error = "";
	$pass = false;

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if (isset($_POST['submit'])) { // confirm submit button has been pressed
			$pass = true;
			$email = $_POST["email"];
			$pwd = $_POST["pwd"];

			$email = mysqli_real_escape_string($conn, $email);
			$pwd = mysqli_real_escape_string($conn, $pwd);

			// check name is valid
			// first check if name is empty
			if (empty($email)) {
				$pass = false;
				$error .= "Name is required <br>";
			}

		    // check password
		    if (empty($pwd)) {
				$pass = false;
				$error .= "Password is required <br>";
			}

			// check that a user exists with that name (otherwise password)
			if ($pass == true) {
				// fetch user
				//$sql = "SELECT * FROM users WHERE user_uid = '$email' OR user_email = '$email'";
				$sql = "SELECT * FROM users WHERE user_email = '$email'";
				$result = mysqli_query($conn, $sql);
				$resultCheck = mysqli_num_rows($result);

				if ($resultCheck < 1) {
					// no users were returned
					$error .= "No users with that email exist <br>";
					$pass = false;
				} else {
					// check passwords match

					// dehashing the password
					
					$row = mysqli_fetch_assoc($result); // get the data from that user

					//$pwd is the input password
					$checkPwd = $row['user_pwd'];
					if ($pwd != $checkPwd) {
						$error .= "Incorrect password <br>";
						$pass = false;
					}

				}
			}		
		}
	}

	if ($pass == true) {
		//$_SESSION["name"] = $_POST["name"];
		//$_SESSION["email"] = $_POST["email"];
		$_SESSION['name'] = $row['user_name']; 
		$_SESSION['email'] = $row['user_email'];
		$_SESSION['uid'] = $row['user_id']; 

		header("Location: profile.php");
	}

?>
	<div class="body-class">
		<h1>login page</h1>
		<p>This is the login page</p>
		<form action="" method="POST">
			Name: <input type="text" name="email" value="<?php echo $email; ?>"><br><br>
			Password: <input type="password" name="pwd"><br>
			<span class="error"><?php echo $error; ?></span><br>
			<input type="submit" name="submit">
		</form>
	</div>
<?php
	include 'footer.php';
?>
