<?php 
require 'config.php';

if (!isset($_POST['email'])|| empty($_POST['email'])) {
	$message = "Information not received";
} else {
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

		$row = $emailResult->fetch_assoc();
		$mail = $row['email'];
		$mail = "notpromotion@gmail.com";
		$subject = "Password Reset";
		$msg = "<div>Reset your password using this <a href='http://303.itpwebdev.com/~nichunsh/final_project/recoverPage.php?id=" . $row['password'] . "'>link</a></div>";
		$headers = "From: noreply@flippit.com" . "\r\n" . "Content-Type: text/html";

		if(mail($mail, $subject, $msg, $headers)){
			$message = "Email Sent!";
		} else {
			$message = "Error";
		}

	} else {
		$message = "Email does not exist.";
	}

	$mysqli->close();

}

echo $message;

 ?>