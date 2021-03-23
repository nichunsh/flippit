<?php 
	require 'config.php';


	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	if ($mysqli->connect_errno) {
		echo $mysqli->connect_error;
		exit();
	}

	$leaderboardsmallSQL = "SELECT users.username, games.centiseconds, games.flips
	FROM games
	JOIN users
		ON games.user_id = users.id
	WHERE games.type = 24
	ORDER BY games.centiseconds
	LIMIT 10;";

	$leaderboardsmall = $mysqli->query($leaderboardsmallSQL);
	if (!$leaderboardsmall) {
		echo $mysqli->error;
		exit();
	}

	$leaderboardbigSQL = "SELECT users.username, games.centiseconds, games.flips
	FROM games
	JOIN users
		ON games.user_id = users.id
	WHERE games.type = 48
	ORDER BY games.centiseconds
	LIMIT 10;";

	$leaderboardbig = $mysqli->query($leaderboardbigSQL);
	if (!$leaderboardbig) {
		echo $mysqli->error;
		exit();
	}

	$mysqli->close();


 ?>
<!DOCTYPE html>
<html>
<head>
	<link href="https://fonts.googleapis.com/css?family=Bangers|Fredoka+One&display=swap" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="navbarstyle.css">
	<link rel="stylesheet" type="text/css" href="style.css">
	<title>Home</title>
	<script src="https://kit.fontawesome.com/27db736dc1.js" crossorigin="anonymous"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
	<?php include 'navbar.php'; ?>
	<div id="body">
		<div id="howToPlay">
			<div class="green flex alignCenter">
				<h2 class="green">How to Play &nbsp;</h2>
				<div class="grow"><hr class="greenHR"></div>
			</div>
			<p>All of the cards are laid face down and it is your job to match them!</p>

			<p>There's only one thing for you to do:<br><span class="purple titleFont">F L I P P I T !</span></p>


			<p class="flex wrap center"><span>&nbsp; &nbsp;<span class="underline success">  If they <span class="red">MATCH</span>, YAY! </span>&nbsp; &nbsp; </span><span> &nbsp; &nbsp;<span class="underline fail"> If <span class="blue">NOT</span>, try again! </span>&nbsp; &nbsp;</span></p>
			<p>See how fast you can finish each game!</p>

		</div>
		<div id="leaderboard">
			<div class="green flex alignCenter">
				<h2 class="green">Leaderboards &nbsp; </h2>
				<div class="grow"><hr class="greenHR"></div>
			</div>
			<div class="historyTable">
<?php if ($leaderboardsmall->num_rows >0): ?>
			<div>
				<h3>TOP 10 OF</h3>
				<h3 class="purple">24 Cards</h3>
			
			<table>
				<tbody>
					<tr>
						<th>Rank</th>
						<th class="username">User</th>
						<th>Time</th>
						<th>Flips</th>
					</tr>

<?php $i = 1;
while($row = $leaderboardsmall->fetch_assoc()): ?>
	<tr>
		<td><?php echo $i++; ?></td>
		<td class="username"><?php echo $row['username'] ?></td>
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
<?php if ($leaderboardbig->num_rows >0): ?>
			<div>
				<h3>TOP 10 OF</h3>
				<h3 class="purple">48 Cards</h3>
			
			<table>
				<tbody>
					<tr>
						<th>Rank</th>
						<th class="username">User</th>
						<th>Time</th>
						<th>Flips</th>
					</tr>

<?php $i = 1;
while($row = $leaderboardbig->fetch_assoc()): ?>
	<tr>
		<td><?php echo $i++; ?></td>
		<td class="username"><?php echo $row['username'] ?></td>
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
		<hr class="greenHR">
		<?php if (!isset($_SESSION['logged'])|| !$_SESSION['logged']): ?>
		<div id="forms">
			<div id="login" class="size">
				<form id="loginForm">
					<div>
						<div class="green flex alignCenter">
							<div class="grow"><hr class="paleHR"></div>
							<h2 class="paleyellow formTitle">&nbsp; Login &nbsp; </h2>
							<div class="grow"><hr class="paleHR"></div>
						</div>
						<div>
							<label for="login-username">Username:</label>
							<input type="text" id="login-username" name="username" class="inputBox">
						</div>
						<div>
							<label for="login-password">Password:</label>
							<input type="password" id="login-password" name="password" class="inputBox">
						</div>
						<div id="login-error" class="yellow"></div>
						<button type="submit" class="button purple yellowButton">Login</button>
						<a href="forgetPage.php">Forgot Password</a>
					</div>
				</form>
			</div>
			<br>
			<div id="register" class="size">
				<form id="registerForm">
					<div>
						<div class="green flex alignCenter">
							<div class="grow"><hr class="greenHR"></div>
							<h2 class="green formTitle wrap center nogrow">
								<span>&nbsp; New to &nbsp;</span>
								<span><span class="purple titleFont">F L I P P I T</span>&nbsp;&nbsp;? &nbsp; </span>
							</h2>
							<div class="grow"><hr class="greenHR"></div>
						</div>
						<div id="registerInput">
							<div>
								<label class="label" for="register-username">Username:</label>
								<input type="text" id="register-username" name="username" class="inputBoxR">
							</div>
							<div>
								<label class="label" for="register-email">Email Address:</label>
								<input type="email" id="register-email" name="email" class="inputBoxR">
							</div>
							<div>
								<label class="label" for="register-password">Password:</label>
								<input type="password" id="register-password" name="password" class="inputBoxR">
							</div>
							<div>
								<label class="label" for="register-passwordConfirmation">Confirm Password:</label>
								<input type="password" id="register-passwordConfirmation" name="passwordConfirm" class="inputBoxR">
							</div>
							<div id="register-error" class="red"></div>
						</div>
						<button type="submit" class="button yellow purpleButton">Start Flippin'</button>
					</div>
				</form>
			</div>
		</div>
		<?php endif; ?>

		<hr class="yellowHR">

	</div>
	<script src="http://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
	<script src="navbar.js"></script>
	<script src="homePage.js"></script>

</body>
</html>