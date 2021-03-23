<?php 

require 'config.php';

if( !isset($_SESSION['userID']) || empty($_SESSION['userID']) ) {
	$message = "User not logged in.";
	
} else {

	$user = $_SESSION['userID'];
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	if ($mysqli->connect_errno) {
		echo $mysqli->connect_error;
		exit();
	}

	$deleteSQL = "DELETE FROM games WHERE user_id = ".$user.";";

	$delete = $mysqli->query($deleteSQL);
	if (!$delete) {
		echo $mysqli->error;
		exit();
	}

	if ($mysqli->affected_rows > 0){
		$message = 'Success!';

		$updateSQL = "UPDATE users SET games_played = 0 WHERE id =" . $user . ";";
		$update = $mysqli->query($updateSQL);

		if ($mysqli->affected_rows == 0){
			$message = 'Failed to update.';
		}

	} else {
		$message = 'Failed to delete.';
	}

	

	$mysqli->close();

}

echo $message;
 ?>