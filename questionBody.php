		<button name="makeQuestion" onclick="makeQuestion()">begin</button>
		
		<p style='color: red;' id='testID'></p>
		<p id='questionID'>question string : </p>
		<span>
			<input id="ansBoxID" type="value" size="5">
			<button id="submitBtn" onclick="checkAns(ansBoxID.value)">Submit</button>
		</span>
		
		<p id='feedbackID'>feedback : </p>

		<p id='scoreID'></p>

		<button id="nextID" onclick="nextQuestion()">Next</button>

		<div id="updateHomework" style="display: none;">
			<form method="POST">
				<button name="updateHomework">update homework</button>
			</form>
		</div>

		<?php
			if (isset($_POST['updateHomework'])) {
				$uid = $_SESSION["uid"];
				//$sql = "SELECT * FROM homework WHERE u_id = '$uid' AND link = '$link'";
				$sql = "UPDATE homework SET completed = 1 WHERE u_id = '$uid' AND link = '$link'";
				$result = mysqli_query($conn, $sql);
				/*
				$data = mysqli_fetch_assoc($result);
				echo $data['u_id'] ." and " . $data['hw_id'] . " and " . $data['link'];
				$data['completed'] = 1;
				*/
			}
		?>
