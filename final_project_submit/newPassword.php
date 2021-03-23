<?php 
require 'config.php';

if (!isset($_POST['password'])|| empty($_POST['password']) || !isset($_POST['username'])|| empty($_POST['username']) || !isset($_POST['pwHashed'])|| empty($_POST['pwHashed'])) {
	$message = "Information not received";
} else {

	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	if ($mysqli->connect_errno) {
		echo $mysqli->connect_error;
		exit();
	}

	$username = $mysqli->real_escape_string($_POST['username']);
	$password = $_POST['pwHashed'];
	$newPassword = hash('sha256', $_POST['password']);

	$checkUserSQL = $mysqli->prepare("SELECT * FROM users WHERE username = ? AND password = ?;");
	$checkUserSQL->bind_param("ss", $username, $password);
 	$user = $checkUserSQL->execute();
 	if (!$user) {
		echo $mysqli->error;
		exit();
	}
	$userResult = $checkUserSQL->get_result();
	$checkUserSQL->close();

	if ($userResult->num_rows > 0) {
		$row = $userResult->fetch_assoc();
		$user = $row['id'];

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

		$message = "User has already changed password with link or Incorrect Username.";
	}

	$mysqli->close();
}

echo $message;

 ?>