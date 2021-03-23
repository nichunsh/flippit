<?php 

require 'config.php';


if (!isset($_POST['username'])|| empty($_POST['username']) || !isset($_POST['email'])|| empty($_POST['email']) || !isset($_POST['password']) || empty($_POST['password'])) {
		$message = "Information not received";
} else {

	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	if ($mysqli->connect_errno) {
		echo $mysqli->connect_error;
		exit();
	}

	$username = $mysqli->real_escape_string($_POST['username']);
	$email = $mysqli->real_escape_string($_POST['email']);
 	$password = hash('sha256', $_POST['password']);

 	$checkUserSQL = $mysqli->prepare("SELECT * FROM users WHERE username = ?;");
 	$checkUserSQL->bind_param("s", $username);
 	$user = $checkUserSQL->execute();
 	if (!$user) {
		echo $mysqli->error;
		exit();
	}
	$userResult = $checkUserSQL->get_result();
	$checkUserSQL->close();

	$checkEmailSQL = $mysqli->prepare("SELECT * FROM users WHERE email = ?;");
	$checkEmailSQL->bind_param("s", $email);
 	$em = $checkEmailSQL->execute();
 	if (!$em) {
		echo $mysqli->error;
		exit();
	}
	$emailResult = $checkEmailSQL->get_result();
	$checkEmailSQL->close();

	if ($userResult->num_rows > 0 && $emailResult->num_rows > 0) {
		$message = "Username and Email already exists.";
	} else if ($userResult->num_rows > 0) {
		$message = "Username taken.";
	} else if ($emailResult->num_rows > 0) {
		$message = "Email used for another account.";
	} else {
		$registerSQL = $mysqli->prepare("INSERT INTO users(username, email, password, games_played, value, questions_answered) VALUES (?, ?, ?, ?, ?, ?);");
		$a = 0;

		$registerSQL->bind_param("sssiii", $username, $email, $password, $a, $a, $a);

		$execute = $registerSQL->execute();

		if (!$execute) {
			echo $mysqli->error;
			exit();
		}

		if ($registerSQL->affected_rows > 0) {
			$message = "Success!";
		} else {
			$message = "Registration Unsuccessful";
		}
		$registerSQL->close();
	}

	$mysqli->close();
}



echo $message;

 ?>