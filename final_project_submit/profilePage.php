<?php 
require 'config.php';

if (isset($_SESSION['logged']) && $_SESSION['logged']){
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	if ($mysqli->connect_errno) {
		echo $mysqli->connect_error;
		exit();
	}

	$sql = "SELECT * FROM users WHERE id = ". $_SESSION['userID'] .";";
	$userInfo = $mysqli->query($sql);
	if (!$userInfo) {
		echo $mysqli->error;
		exit();
	}

	$userInfo = $userInfo->fetch_assoc();

	$smallcardsSQL = "SELECT * FROM games WHERE user_id = " . $_SESSION['userID'] . " AND type = 24 ORDER BY centiseconds ASC LIMIT 10;";
	$smallcards = $mysqli->query($smallcardsSQL);
	if (!$smallcards) {
		echo $mysqli->error;
		exit();
	}

	$bigcardsSQL = "SELECT * FROM games WHERE user_id = ". $_SESSION['userID'] ." AND type = 48 ORDER BY centiseconds ASC LIMIT 10;";
	$bigcards = $mysqli->query($bigcardsSQL);
	if (!$bigcards) {
		echo $mysqli->error;
		exit();
	}


	$mysqli->close();


}



 ?>
<!DOCTYPE html>
<html>
<head>
	<link href="https://fonts.googleapis.com/css?family=Bangers|Fredoka+One&display=swap" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="navbarstyle.css">
	<title>Profile</title>
	<script src="https://kit.fontawesome.com/27db736dc1.js" crossorigin="anonymous"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<?php include 'navbar.php'; ?>
	<?php if (isset($_SESSION['logged']) && $_SESSION['logged']): ?>
	<div id="body">
		<div id="Profile">

			<div class="purple flex alignCenter">
				<div class="grow"><hr class="purpleHR"></div>
				<h2 class="purple">&nbsp; Hello <?php echo $_SESSION['username']; ?> &nbsp;</h2>
				<div class="grow"><hr class="purpleHR"></div>
			</div>

			<div class="purpleCard paleyellow">
			<p>Email: <span id="userEmail"><?php echo $userInfo['email']; ?></span></p>
			<p>Games Played: <?php echo $userInfo['games_played']; ?></p>
			<p>Question Answered: <?php echo $userInfo['questions_answered']; ?></p>
			<p>Value: <?php echo $userInfo['value']; ?></p>
			</div>
			<div id="editInfo">
				<div id="updateEmail">
					<div id="emailInput" class="hidden">
						<label for="email" class="label">New Email:</label><br>
						<input type="email" id="email" name="email" class="inputBoxR lab" value="<?php echo $userInfo['email'];?>">
					</div>
					<button id="submitEmail" class="button paleyellow purpleButton">Change Email</button>
					<div id="emailMessage" class="red" ></div>
				</div>
				<div id="updatePassword">
					<div id="passwordInputs" class="hidden">
						<label for="oldPassword">Old Password:</label><br>
						<input type="password" name="oldPassword" id="oldPassword" class="lab inputBoxR">
						<br>
						<label for="password">New Password:</label><br>
						<input type="password" name="password" id="password" class="lab inputBoxR">
						<br>
						<label for="passwordConfirm">Password Confirmation:</label><br>
						<input type="password" name="passwordConfirm" id="passwordConfirm" class="lab inputBoxR">
					</div>
					<button id="submitPassword" class="button paleyellow purpleButton">Change Password</button>
					<div id="passwordMessage" class="red"></div>
				</div>
			</div>
		</div>
		<div id="gameHistory">
			<div class="green flex alignCenter">
				<h2 class="green">Game History &nbsp;</h2>
				<div class="grow"><hr class="greenHR"></div>
			</div>
			
<?php if  ($smallcards->num_rows > 0 || $bigcards->num_rows > 0):?>
			<div class="historyTable">
<?php if ($smallcards->num_rows > 0): ?>
				<div>
					<h3>TOP 10 SCORES OF</h3>
					<h3 class="purple">24 Cards</h3>
				<table>
					<tbody>
						<tr>
							<th>Rank</th>
							<th>Time</th>
							<th>Flips</th>
						</tr>
<?php $i = 1;
while($row = $smallcards->fetch_assoc()): ?>
	<tr>
		<td><?php echo $i++; ?></td>
		<td><?php 
$centiseconds = $row['centiseconds'];
$hr = floor($centiseconds/360000);
$centiseconds = $centiseconds%360000;
$min = floor($centiseconds/6000);
$centiseconds = $centiseconds%6000;
$sec = floor($centiseconds/100);
$centiseconds = $centiseconds%100;


if ($hr == 0){


	echo str_pad($min, 2, '0', STR_PAD_LEFT) . ":" . str_pad($sec, 2, '0', STR_PAD_LEFT).".".str_pad($centiseconds, 2, '0', STR_PAD_LEFT);
} else {
	echo str_pad($min, 2, '0', STR_PAD_LEFT).":".str_pad($min, 2, '0', STR_PAD_LEFT) . ":" . str_pad($sec, 2, '0', STR_PAD_LEFT).".".str_pad($centiseconds, 2, '0', STR_PAD_LEFT);
}





		 ?></td>
		<td><?php echo $row['flips']; ?></td>
	</tr>
<?php endwhile; ?>
						
					</tbody>
				</table>
				</div>
<?php endif; ?>
<?php if ($bigcards->num_rows > 0): ?>
				<div>
					<h3>TOP 10 SCORES OF</h3>
					<h3 class="purple">48 Cards</h3>
				<table>
					<tbody>
						<tr>
							<th>Rank</th>
							<th>Time</th>
							<th>Flips</th>
						</tr>
<?php $i = 1;
while($row = $bigcards->fetch_assoc()): ?>
	<tr>
		<td><?php echo $i++; ?></td>
		<td><?php 
$centiseconds = $row['centiseconds'];
$hr = floor($centiseconds/360000);
$centiseconds = $centiseconds%360000;
$min = floor($centiseconds/6000);
$centiseconds = $centiseconds%6000;
$sec = floor($centiseconds/100);
$centiseconds = $centiseconds%100;


if ($hr == 0){


	echo str_pad($min, 2, '0', STR_PAD_LEFT) . ":" . str_pad($sec, 2, '0', STR_PAD_LEFT).".".str_pad($centiseconds, 2, '0', STR_PAD_LEFT);
} else {
	echo str_pad($min, 2, '0', STR_PAD_LEFT).":".str_pad($min, 2, '0', STR_PAD_LEFT) . ":" . str_pad($sec, 2, '0', STR_PAD_LEFT).".".str_pad($centiseconds, 2, '0', STR_PAD_LEFT);
}





		 ?></td>
		<td><?php echo $row['flips']; ?></td>
	</tr>
<?php endwhile; ?>
					</tbody>
				</table>
				</div>
<?php endif; ?>
			</div>
		</div>
		<div id="deleteRecords">
			<button class="button paleyellow redButton">Delete All Game Records</button>
			<p id="deleteMessage" class="red"></p>
		</div>
<?php endif ?>
	</div>
	<?php endif; ?>

	<script src="http://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
	<script src="navbar.js"></script>
	<script src="profile.js"></script>
</body>
</html>