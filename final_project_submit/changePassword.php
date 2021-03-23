<?php 
require 'config.php';

if (!isset($_POST['password'])|| empty($_POST['password']) || !isset($_POST['newPassword'])|| empty($_POST['newPassword'])) {
		$message = "Information not received";
} else {

	if( !isset($_SESSION['userID']) || empty($_SESSION['userID']) ) {
		$message = "User not logged in.";
		
	} else {
		$user = $_SESSION['userID'];
		$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		if ($mysqli->connect_errno) {
			echo $mysqli->connect_error;
			exit();
		}

		$password = hash('sha256', $_POST['password']);
		$newPassword = hash('sha256', $_POST['newPassword']);

		$checkPasswordSQL = $mysqli->prepare("SELECT * FROM users WHERE id =" . $user . " AND password = ?;");
		$checkPasswordSQL->bind_param("s", $password);
	 	$em = $checkPasswordSQL->execute();
	 	if (!$em) {
			echo $mysqli->error;
			exit();
		}
		$passwordResult = $checkPasswordSQL->get_result();
		$checkPasswordSQL->close();

		if ($passwordResult->num_rows > 0) {
			$updateSQL = $mysqli->prepare("UPDATE users SET password = ? WHERE id =" . $user . ";");

			$updateSQL->bind_param("s", $newPassword);

			$execute = $updateSQL->execute();

			if (!$execute) {
				echo $mysqli->error;
				exit();
			}

			if ($updateSQL->affected_rows > 0) {
				$message = "Success!";
			} else {
				$message = "Password Update Unsuccessful";
			}
			$updateSQL->close();
		} else {
			$message = "Wrong Password.";
		}

		$mysqli->close();
	}

	
}

echo $message;
 ?>