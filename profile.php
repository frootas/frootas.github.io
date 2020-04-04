<?php
	include 'header.php';
	include 'connect.php';
?>

	<div class="body-class">
		<h1>welcome</h1>
		<p>This is the profile page</p>

		<?php
			$name = $_SESSION["name"];
			$email = $_SESSION["email"];
			if (!empty($classCode)) {
				$_SESSION["classCode"] = $classCode;
			}
			
			if (empty($_SESSION["uid"])) {
				$sql = "SELECT * FROM users WHERE user_email = '$email'";
				$result = mysqli_query($conn, $sql);
				$row = mysqli_fetch_assoc($result);
				$_SESSION['uid'] = $row['user_id'];
			}
			$uid = $_SESSION["uid"];
			
			echo "Welcome $name. You have logged in with email address $email <br>";

			echo "you were logged in on " . date("l") . ", " . date("d/m/Y") . " at " . date("h:i a"). "<br>";
		?>
		<br>
		<!-- show classes -->
		<?php
			$sql = "SELECT class_name, class_code FROM classes WHERE user_id='$uid'";
			$result = mysqli_query($conn, $sql);
			$resultCheck = mysqli_num_rows($result);

			// if they have classes
			$hasClasses = false;
			if ($resultCheck > 0) {
			    $hasClasses = true;
			    $numClasses = 0;
			    $classes = array();	
			    // count the number of classes and save the codes in classes[] array
			    while ($row = mysqli_fetch_assoc($result)) {
			    	$classes[$numClasses] = $row["class_code"];
			    	$numClasses++;
			    }
			    echo "You are enrolled in $numClasses classes <br><br>";
			    echo "Click on that class to see members <br><br>";

			    $classTable = array();
			    for ($i = 0; $i < $numClasses; $i++) {			    	
			    	$classCode = $classes[$i]; // $row["class_code"];
			    	$sql = "SELECT * FROM classes WHERE class_code='$classCode'";
			    	$result = mysqli_query($conn, $sql);
			    	$data = mysqli_fetch_assoc($result);
			    	$className = $data['class_name'];

			    	$classTable[$i] = "<tr><td><form action='classes.php' method='POST'><input type='submit' name='members' value='$className'></form></td><td style='text-align: center;'>$classCode</td></tr>";
			    }
			    
			} else {
				echo "you are not enrolled in any classes.";
			}

		?>
		<div class="classesDiv">
			<div class="classesList">
				<?php
				if ($hasClasses) {
					echo "<table><tr><th>Classes</th><th>Code</th></tr>";
					for ($i = 0; $i < count($classTable); $i++) {
						echo $classTable[$i];
					}
					echo "</table>";
				}
				?>
			</div>
		</div>
	</div>
	
<?php
	include 'footer.php';
?>
