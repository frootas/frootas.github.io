<?php
	include 'header.php';
	include 'connect.php';
?>
	<div class="body-class">
		<div class="table">
			<br>
			<?php
			$sql = "SELECT user_id, user_email, user_uid FROM users";
			$result = mysqli_query($conn, $sql);

			if ($result->num_rows > 0) {
			    echo "<table><tr><th style='width: 80px;'>ID</th><th>Email</th><th>Name</th></tr>";
			    // output data of each row
			    while($row = mysqli_fetch_assoc($result)) {
			        echo "<tr><td>".$row["user_id"]."</td><td>".$row["user_email"]."</td><td>".$row["user_uid"]."</td></tr>";
			    }
			    echo "</table>";
			} else {
			    echo "0 results";
			}
			?>	
		</div>
		
	</div>
<?php
	include 'footer.php';
?>
