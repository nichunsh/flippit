<?php 

require 'config.php';
if (isset($_SESSION['userID'])&&!empty($_SESSION['userID'])) {

	$user = $_SESSION['userID'];

	if (isset($_POST['id']) && !empty($_POST['id']) && isset($_POST['value']) && !empty($_POST['value'])) {

		$_SESSION['question'] = true;

		$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		if ($mysqli->connect_errno) {
			echo $mysqli->connect_error;
			exit();
		}

		$logQ = $mysqli->prepare("INSERT INTO questions(user_id, question_id) VALUES (". $user .", ?);");

		$logQ->bind_param("i", $_POST['id']);

		$execute = $logQ->execute();

		if (!$execute) {
			echo $mysqli->error;
			exit();
		}

		$value = $_POST['value'];

		$question = $mysqli->prepare("UPDATE users SET questions_answered = questions_answered + 1, value = GREATEST(value + ?, 0) WHERE id =" . $user . ";");

		$question->bind_param("i", $_POST['value']);

		echo $_POST['value'];

		$update = $question->execute();

		if (!$update) {
			echo $mysqli->error;
			exit();
		}


		if ($logQ->affected_rows > 0 && $question->affected_rows > 0) {
			$message = "Value Updated";
		} else {
			$message = "Values Failed to update";
		}

		$logQ->close();

		$question->close();
		
		$mysqli->close();

	}
}

echo $message;


 ?>