qString = new String();
CORRECT_ANSWER = "";
userAns = "";
numCorrect = 0;
totalQuestions = 3;
attempts = 0;

function makeQuestion() {
	if (numCorrect < totalQuestions) {
		a = getNum(1, 10);
		b = getNum(1, 10);
		switch(topic) {
			case "701" :
				qString = a + " + " + b + " = ";
				CORRECT_ANSWER = a + b;
				break;
			case "702" :
				qString = a + " - " + b + " = ";
				CORRECT_ANSWER = a - b;
				break;
			case "703" :
				qString = a + " x " + b + " = ";
				CORRECT_ANSWER = a * b;
				break
			default :
				qString = "ERROR : topic is " + topic;
				CORRECT_ANSWER = "";
		}
		CORRECT_ANSWER = CORRECT_ANSWER.toString();
		questionID.innerHTML = qString;
	} else {
		score = 100 * numCorrect / attempts;
		questionID.innerHTML = "Test complete. You scored " + numCorrect + " out of " + attempts  + " = " + score + " %";
		updateHomework.style.display = "block";
	}
}

function checkAns(userAns) {
	testID.innerHTML = "user inputted " + userAns + "(" + typeof userAns + ") and correct answer is " + CORRECT_ANSWER + "(" + typeof CORRECT_ANSWER + ")";

	if (userAns == CORRECT_ANSWER) {
		feedback = "CONGRATULATIONS! THAT IS CORRECT";
		numCorrect++;
	} else {
		feedback = "UNLUGGY UCE. THE CORRECT ANSWER IS " + CORRECT_ANSWER;
	}
	feedbackID.innerHTML = feedback;
	attempts++;

	// show score
	scoreID.innerHTML = numCorrect + "/" + attempts;
}

function nextQuestion() {
	questionID.innerHTML = "";
	feedbackID.innerHTML = "";
	ansBoxID.value = "";
	makeQuestion();
}

function getNum(min, max) {
	return Math.floor(Math.random() * (max - min)) + min;
}
