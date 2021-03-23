<?php 

require 'config.php';

if (!isset($_SESSION['userID']) || empty($_SESSION['userID'])){
	$user = 0;
} else {
	$user = $_SESSION['userID'];
}

if (isset($_POST['centiseconds']) && !empty($_POST['centiseconds']) && isset($_POST['flips']) && !empty($_POST['flips'])&& isset($_POST['type']) && !empty($_POST['type'])) {

	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	if ($mysqli->connect_errno) {
		echo $mysqli->connect_error;
		exit();
	}

	$sql = $mysqli->prepare("INSERT INTO games(flips, centiseconds, type, user_id) VALUES (?, ?, ?, ?);");

	$sql->bind_param("iiii", $_POST['flips'], $_POST['centiseconds'], $_POST['type'], $user);

	$execute = $sql->execute();

	if (!$execute) {
		echo $mysqli->error;
		exit();
	}

	if ($sql->affected_rows > 0 && $user != 0){
		$game = "UPDATE users SET games_played = games_played + 1 WHERE id =" . $user . ";";
		$update = $mysqli->query($game);
		if (!$update) {
			echo $mysqli->error;
			exit();
		}

		if ($mysqli->affected_rows > 0){
			$message = 'Success!';
		} else {
			$message = 'Fail';
		}

	} else {
		$message = 'guest';
	}

	$sql->close();
	$mysqli->close();


}

echo $message;



 ?>