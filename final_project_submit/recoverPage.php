<?php 
require 'config.php';

$message = "RESTRICTED ACCESS";

if (isset($_GET['id'])&& !empty($_GET['id'])){
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	if ($mysqli->connect_errno) {
		echo $mysqli->connect_error;
		exit();
	}

	$pwCheck = $mysqli->prepare("SELECT * FROM users WHERE password = ?;");
	$pwCheck->bind_param("s", $_GET['id']);
 	$pw = $pwCheck->execute();
 	if (!$pw) {
		echo $mysqli->error;
		exit();
	}
	$pwResult = $pwCheck->get_result();
	$pwCheck->close();

	if ($pwResult->num_rows > 0) {
		$exist = true;
	} else {
		$message = "Link has expired or user already changed password.";
	}

}



 ?>
<!DOCTYPE html>
<html>
<head>
	<link href="https://fonts.googleapis.com/css?family=Bangers|Fredoka+One&display=swap" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="navbarstyle.css">
	<title>Password Recovery</title>
	<script src="https://kit.fontawesome.com/27db736dc1.js" crossorigin="anonymous"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<?php include 'navbar.php'; ?>
	<div id="body">
<?php if (isset($_GET['id'])&& !empty($_GET['id']) && $exist): ?>
		<div id="login" class="size">
			<form id="loginForm">
				<div>
					<div class="green flex alignCenter">
							<div class="grow"><hr class="paleHR"></div>
							<h2 class="paleyellow formTitle">&nbsp; Reset Password &nbsp; </h2>
							<div class="grow"><hr class="paleHR"></div>
						</div>
						<div>
							<label for="reset-username">Username:</label>
							<input type="text" id="reset-username" name="username" class="inputBox">
						</div>
						<div>
							<label class="label" for="reset-password">Password:</label>
							<input type="password" id="reset-password" name="password" class="inputBox">
						</div>
						<div>
							<label class="label" for="reset-passwordConfirmation">Confirm Password:</label>
							<input type="password" id="reset-passwordConfirmation" name="passwordConfirm" class="inputBox">
						</div>
					<div id="reset-error" class="yellow"></div>
					<button type="submit" class="button green yellowButton">Reset Password</button>
				</div>
			</form>
		</div>
<?php else: ?>
		<div id="updateEmail" class="red"><?php echo $message; ?></div>
<?php endif; ?>
	</div>		
	<script src="http://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
	<script src="navbar.js"></script>
<?php if (isset($_GET['id'])&& !empty($_GET['id']) && $exist): ?>
	<script>
		$(document).ready(function(){
			
			var oldPasswordHashed = "<?php echo $_GET['id']; ?>";

			$('#loginForm').on('submit', function(event){
				event.preventDefault()
				var empty = false
				var match = true
				if ($('#reset-username').val().trim().length == 0){
					if (!$('#reset-username').hasClass('redBorder')){
						$('#reset-username').addClass('redBorder')
					}
					empty = true
				} else {
					if ($('#reset-username').hasClass('redBorder')){
						$('#reset-username').removeClass('redBorder')
					}
				}

				if ($('#reset-password').val().trim().length == 0){
					if(!$('#reset-password').hasClass('redBorder')){
						$('#reset-password').addClass('redBorder')
					}
					empty = true
				} else {
					if($('#reset-password').hasClass('redBorder')){
						$('#reset-password').removeClass('redBorder')
					}
				}

				if ($('#reset-passwordConfirmation').val().trim().length == 0){
					if (!$('#reset-passwordConfirmation').hasClass('redBorder')) {
						$('#reset-passwordConfirmation').addClass('redBorder')
					}
					empty = true
				} else {
					if ($('#reset-passwordConfirmation').hasClass('redBorder')) {
						$('#reset-passwordConfirmation').removeClass('redBorder')
					}

					if ($('#reset-passwordConfirmation').val().trim() != $('#reset-password').val().trim()){
						match = false
					}
				}

				if (empty){
					$('#reset-error').text('Fill in all fields.')
				} else {
					if (!match) {
						if (!$('#reset-passwordConfirmation').hasClass('redBorder')) {
							$('#reset-passwordConfirmation').addClass('redBorder')
						}
						if(!$('#reset-password').hasClass('redBorder')){
							$('#reset-password').addClass('redBorder')
						}
						$('#reset-error').text('Passwords do not match.')
						return false
					} else {
						$('#reset-error').text('')

						var username = $('#reset-username').val().trim()
						var password = $('#reset-password').val().trim()

						let link = 'username='+username+'&password='+password+'&pwHashed='+oldPasswordHashed

						ajaxPost('newPassword.php', link, function(results) {
							console.log(results)
							if (results == "Success!"){
								$('#reset-error').text(results)
								$('#reset-username').val("")
								$('#reset-password').val("")
								$('#reset-passwordConfirmation').val("")
								setTimeout(function(){
									window.location.replace("homePage.php")
								}, 1200)
								return false
							} else {
								if (results.includes('Username')){
									if (!$('#reset-username').hasClass('redBorder')){
										$('#reset-username').addClass('redBorder')
									}
								}

								$('#reset-error').text(results)
								return false
							}
						})

						

					}

					
				}
			})

		})

		function ajaxPost(endpointUrl, postData, returnFunction){
			var xhr = new XMLHttpRequest();
			xhr.open('POST', endpointUrl, true);
			xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			xhr.onreadystatechange = function(){
				if (xhr.readyState == XMLHttpRequest.DONE) {
					if (xhr.status == 200) {
						returnFunction( xhr.responseText );
					} else {
						alert('AJAX Error.');
						console.log(xhr.status);
					}
				}
			}
			xhr.send(postData);
		};
	</script>

<?php endif; ?>
</body>
</html>