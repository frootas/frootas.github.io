		<form method="POST">
			<button name="makeQuestion">begin</button>		
		</form>
		
		<p style='color: red;' id='testID'></p>
		<p id='questionID'>question string : </p>
		<span>
			<!--
			<input id="ansBoxID" type="value" size="5">
			<button id="submitBtn" onclick="checkAns(ansBoxID.value)">Submit</button>	
			-->
			<form method="POST">
				<input name="ansBoxID" type="value" size="5">
				<button name="submitBtn">Submit</button>				
			</form>
		</span>
		
		<p id='feedbackID'>feedback : </p>

<?php
	//$CORRECT_ANSWER = "";
	$qString = "";
	$userAns = "";
	$feedback = "";
	$numCorrect = 0;
	$totalQuestions = 10;
	$attempts = 0;
	if (empty($topic)) {
		$topic = "";
	}

	//makeQuestion();
	if (isset($_POST['makeQuestion'])) {
		//echo "hello";
		makeQuestion();
	}

	if (isset($_POST['submitBtn'])) {
		$userAns = $_POST['ansBoxID'];
		checkAns($userAns);
	}

	function makeQuestion() {
		
		// must use global to access variables outside of functions
		global $numCorrect, $totalQuestions, $qString, $feedback, $topic;
		$qString = "qString is printing";
		if ($numCorrect < $totalQuestions) {
			$a = getNumber(1, 10);
			$b = getNumber(1, 10);
			switch ($topic) {
				case "701":
					$qString = $a . " + " . $b . " = ";
					$z = $a + $b;
					break;				
				default:
					$qString = "ERROR : topic is $topic";
					$z = "";
					break;
			}
			$GLOBALS['CORRECT_ANSWER'] = $z;
			echo "
			<script type='text/javascript'>
				questionID.innerHTML = '$qString';
				testID.innerHTML = 'correct answer is $CORRECT_ANSWER';
			</script>
			";
		} else {
			global $attempts;
			$score = 100 * $numCorrect / $attempts;
			$qString = "Test complete. You scored $numCorrect out of $attempts = $score % <br>";
		}		
	}
	
	function getNumber($min, $max) {
		return rand($min, $max);
	}

	function checkAns($userAns) {
		global $userAns, $feedback, $numCorrect, $attempts;
		if ($userAns === $CORRECT_ANSWER) {
			$feedback = "CONGRATULATIONS! THAT IS CORRECT <br>";
			$numCorrect++;
		} else {
			$feedback = "UNLUGGY UCE. THE CORRECT ANSWER IS $CORRECT_ANSWER <br>";
		}
		echo "
		<script type='text/javascript'>
			feedbackID.innerHTML = '$feedback';
		</script>
		";
		$attempts++;
		makeQuestion();
	}

?>