<?php
	include 'header.php';
	include 'connect.php';
?>

	<div class="body-class">
		<h1>Add homework</h1>
		<?php
		
		//echo "max classes is $maxClasses <br>";
		$classCode = $_SESSION["classCode"];
		$sql = "SELECT * FROM classes WHERE class_code='$classCode'";
		$result = mysqli_query($conn, $sql);
		$data = mysqli_fetch_assoc($result);
		$classYear = $data['year'];
		$errorMsg = "";
		
		// now show all the topics for that year for homework
		$sql = "SELECT * FROM topics WHERE year = '$classYear'";
		$result = mysqli_query($conn, $sql);
		$resultCheck = mysqli_num_rows($result);
		if ($resultCheck > 0) {
			
			$topics = array();	
			$curr = 0;
			// count the number of classes and save the codes in classes[] array
			while ($row = mysqli_fetch_assoc($result)) {
			  	$topics[$curr] = $row["name"];
			  	$topics[$curr+1]= $row["link"];
			   	$curr+=2;
			}
		}

		if (isset($_POST['addHomework'])) {
			// get students
			echo "hey";
			$sql = "SELECT * FROM classes WHERE class_code = '$classCode'";
			$result = mysqli_query($conn, $sql);
			$link = $_POST['addHomework'];			
			while ($row = mysqli_fetch_assoc($result)) {
				$uid = $row['user_id'];
				// check that user doesnt already have that homework from that class
				$check = "SELECT * FROM homework WHERE class_code = '$classCode' AND u_id = '$uid' AND link = '$link'";
				$checkResult = mysqli_query($conn, $check);
				if (mysqli_num_rows($checkResult) == 0) {
					// user does not have that homework for this class so we can add it
					$add = "INSERT INTO homework (class_code, link, u_id, completed) VALUES ('$classCode', '$link', '$uid', 0)";
					$connect = true;
					if (!mysqli_query($conn, $add)) {	    
					    $connect = false;
					}

					mysqli_close($conn);

					if ($connect == true) { 
						// go to profile page
						header("Location: classes.php");
					} else {
						echo "There was an error adding the comment";
					}
					
					// check for no connection
				}				
			}

		} else {
			echo "failed";
		}
		

		?>

		<form method="POST">
			<h3>add homework</h3>
			<?php 
			echo "<table class='homeworkTable'>";
			for ($i = 0; $i < count($topics); $i+=2) {
				echo "<tr>";
				echo "<td>" . $topics[$i] . "</td>";
				echo "<td><form method='POST'><button name='addHomework' value=" . $topics[$i+1] . ">" . $topics[$i+1] . "</button></form></td>";
				echo "</tr>";
			}
			echo "</table>";
			?>
		</form>

	</div>
<?php
	include 'footer.php';
?>
