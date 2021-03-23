<?php 
require 'config.php';

if (!isset($_POST['email'])|| empty($_POST['email'])) {
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

		$email = $mysqli->real_escape_string($_POST['email']);

		$checkEmailSQL = $mysqli->prepare("SELECT * FROM users WHERE email = ?;");
		$checkEmailSQL->bind_param("s", $email);
	 	$em = $checkEmailSQL->execute();
	 	if (!$em) {
			echo $mysqli->error;
			exit();
		}
		$emailResult = $checkEmailSQL->get_result();
		$checkEmailSQL->close();

		if ($emailResult->num_rows > 0) {
			$message = "Email used for another account.";
		} else {

			$updateSQL = $mysqli->prepare("UPDATE users SET email = ? WHERE id =" . $user . ";");

			$updateSQL->bind_param("s", $email);

			$execute = $updateSQL->execute();

			if (!$execute) {
				echo $mysqli->error;
				exit();
			}

			if ($updateSQL->affected_rows > 0) {
				$message = "Success!";
			} else {
				$message = "Email Update Unsuccessful";
			}
			$updateSQL->close();
		}

		$mysqli->close();
	}

	
}

echo $message;
 ?>