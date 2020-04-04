<?php
	include 'header.php';
	include 'connect.php';
?>

	<div class="body-class">
		<?php
			$name = $_SESSION["name"];
			$email = $_SESSION["email"];
			if (empty($_SESSION["uid"])) {
				$sql = "SELECT * FROM users WHERE user_email = '$email'";
				$result = mysqli_query($conn, $sql);
				$row = mysqli_fetch_assoc($result);
				$_SESSION['uid'] = $row['user_id'];
			}
			$uid = $_SESSION["uid"];
			if (isset($_POST['members'])) {
				$_SESSION["className"] = $_POST['members'];	
				$className = $_SESSION["className"];
				$sql = "SELECT * FROM classes WHERE user_id = '$uid' AND class_name = '$className'";
			    $result = mysqli_query($conn, $sql);
			    $row = mysqli_fetch_assoc($result);
			    $_SESSION["classCode"] = $row["class_code"];
			}
			
			$className = $_SESSION["className"];
			$classCode = $_SESSION["classCode"];
			//echo "name is $className and code is $classCode";
			
		?>
		<h1>Homework</h1>
		<?php
		// only admin should be seeing this
			$sql = "SELECT * FROM homework WHERE class_code = '$classCode' AND u_id = '$uid'";
			$result = mysqli_query($conn, $sql);
			echo "<table><tr><th>Homework</th><th>Num completed</th><th>Num incomplete</th></tr>";
			while ($row = mysqli_fetch_assoc($result)) {
				// and due date
				// have selection to see number of kids who have completed it
				$link = $row['link'];
				$completed = "SELECT * FROM homework WHERE class_code = '$classCode' AND link = '$link' AND completed = 1";
				$res = mysqli_query($conn, $completed);
				$numCompleted = mysqli_num_rows($res);
				$incompleted = "SELECT * FROM homework WHERE class_code = '$classCode' AND link = '$link' AND completed = 0";
				$res = mysqli_query($conn, $incompleted);
				$numInCompleted = mysqli_num_rows($res);
				//echo "<tr><td>" . $row['link'] . "</td><td>" . $numCompleted . "</td><td>" . $numInCompleted . "</td></tr>";
				echo "<tr><td><form method='POST'><button name='doHomework' value=$link>$link</button></form></td><td>$numCompleted</td><td>$numInCompleted</td></tr>";
			}
			echo "</table>";

			if (isset($_POST['doHomework'])) {
				$link = $_POST['doHomework'];
				$_SESSION['link'] = $link;
				header("Location: $link");
			}
		?>
		<br>
		<form action="addHomework.php" method="POST">
			<button name="addHomework">add homework</button>			
		</form>
		<br>
		<h1><?php echo $className . " class"; ?></h1>
		<br>
		<!-- show classes -->
		<?php
			if (!empty($className)) {			    	
			    // output the names of all with that $classCode
			    $sql = "SELECT * FROM classes WHERE class_code = '$classCode'";
			    $result = mysqli_query($conn, $sql);

				    // output data of each row
				$i = 0;
				$memberTable = array();
				while ($row = mysqli_fetch_assoc($result)) {
				    $uid = $row["user_id"];
				    $s = "SELECT * FROM users WHERE user_id = '$uid'";
				    $res = mysqli_query($conn, $s);
				    $r = mysqli_fetch_assoc($res);
			        $memberTable[$i] = "<tr><td>".$r["user_name"]."</td></tr>";
			        $i++;
			    }
		    }
		?>
		<div class="classesDiv">
			<div class="membersList">
			
				<?php
				//if (!empty($className)) {
				$name = "";
				if (empty($_POST['showMembers'])) {
					$name = "Show members";
				} else {
					$name = $_POST['showMembers'];
				}				
				if ($name == "Show members") {
					echo "<form method='POST'><input type='submit' name='showMembers' value='Hide members'></form><br>";
					//$className = $_POST['members'];
					echo "<table><tr><th>$className Members</th></tr>";
					for ($i = 0; $i < count($memberTable); $i++) {
						echo $memberTable[$i];
					}
					echo "</table>";
				} else {
					echo "<form method='POST'><input type='submit' name='showMembers' value='Show members'></form><br>";
				}
				?>
			</div>

			<div class="forum">
				<h1>Posts</h1>
				<form action="addPost.php" METHOD="POST">
					<input type='submit' name='addPost' value='Add post'>
				</form>
			</div>

			<div class="posts">
				<?php
				$sql = "SELECT * FROM classes WHERE class_code = '$classCode' AND admin = 1";
				$result = mysqli_query($conn, $sql);
				$data = mysqli_fetch_assoc($result);
				$adminID = $data['user_id'];

				$sql = "SELECT * FROM forum WHERE class_code = '$classCode' GROUP BY h_id";
			    $result = mysqli_query($conn, $sql);
			    //$data = mysqli_fetch_assoc($result);
			    $numRows = mysqli_num_rows($result);
			    //echo "<table>";
				for ($i = 0; $i < $numRows; $i++) {
					$hid = $i + 1;
					$sql = "SELECT * FROM forum WHERE class_code = '$classCode' AND h_id = '$hid'";
					$result = mysqli_query($conn, $sql);
			    	$data = mysqli_fetch_assoc($result);
			    	$heading = $data['heading'];
			    	$description = $data['description'];
			    	$date = $data['date'];
			    	$uid = $data['u_id'];
			    	$deleted = $data['deleted'];
			    	if ($deleted == 0) {
			    		echo "<h3>$heading</h3>";
				    	echo "<p>$date</p>";
				    	echo "<p>$description</p>";
				    	
				    	// delete post (and comments with it)
						if ($_SESSION["uid"] == $uid || $_SESSION["uid"] == $adminID) {
							echo "
							<form method='POST'>
								<button name='removePost' value='$hid'>Delete post</button>
							</form><br>
							";
							if (isset($_POST['removePost'])) {
								// are you sure?
								// set deleted = 1; // this can allow for undo
								$hid = $_POST['removePost'];
								$sql = "UPDATE forum SET deleted = 1 WHERE h_id = '$hid'";
								mysqli_query($conn, $sql);
								header("Location: classes.php");
							}
						}

						// now get comments
				    	echo "<h4>Comments</h4>";
					    $numComments = mysqli_num_rows($result);
				    	for ($j = 1; $j < $numComments; $j++) {
				    		$cid = $j;
							$sql = "SELECT * FROM forum WHERE class_code = '$classCode' AND h_id = '$hid' AND c_id='$cid'";
							$result = mysqli_query($conn, $sql);
					    	$data = mysqli_fetch_assoc($result);
					    	$comment = $data['comment'];
					    	$deleted = $data['deleted'];
					    	$uid = $data['u_id']; // get username
					    	// get $username from $uid
					    	$sql = "SELECT * FROM users WHERE user_id = '$uid'";
					    	$result = mysqli_query($conn, $sql);
					    	$data = mysqli_fetch_assoc($result);
					    	$username = $data['user_name'];

					    	if ($deleted == 0) {
					    		echo "<div class='comment'>";
					    		echo "<p>$date</p>";
						    	echo "<p>$username : $comment</p>";
						    	if ($_SESSION["uid"] == $uid || $_SESSION["uid"] == $adminID) {
						    		echo "
									<form method='POST'>
										<button name='removeComment' value='$cid'>Delete comment</button>
									</form><br>
									";
									if (isset($_POST['removeComment'])) {
										// are you sure?
										// set deleted = 1; // this can allow for undo
										$cid = $_POST['removeComment'];
										$sql = "UPDATE forum SET deleted = 1 WHERE h_id = '$hid' AND c_id = '$cid' AND u_id = '$uid'";
										mysqli_query($conn, $sql);
										header("Location: classes.php");
									}

						    	}
						    	echo "</div>";
					    	}
						}
						echo "
						<form action='addComment.php' method='POST'>
							<button name='addComment' value='$hid'>Add comment</button>
						</form><br>
						";
			    	}			    	
				}
				//echo "</table>";
				?>
			</div>
			
		</div>
	</div>
	
<?php
	include 'footer.php';
?>
