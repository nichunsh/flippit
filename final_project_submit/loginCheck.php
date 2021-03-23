<?php 

require 'config.php';

if (!isset($_POST['username'])|| empty($_POST['username']) || !isset($_POST['password']) || empty($_POST['password'])) {
		$message = "Information not received.";
} else {

	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	if ($mysqli->connect_errno) {
		echo $mysqli->connect_error;
		exit();
	}

	$username = $mysqli->real_escape_string($_POST['username']);
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

	if ($userResult->num_rows > 0) {

		$row = $userResult->fetch_assoc();

		if ($row['password'] == $password){
			$message = "Success!";

			$_SESSION['username'] = $_POST['username'];
			$_SESSION['logged'] = true;
			$_SESSION['userID'] = $row['id'];
		} else {
			$message = "Password is incorrect.";
		}

	} else {
		$message = "Username does not exist.";
	}

	$mysqli->close();
}

echo $message;

 ?>