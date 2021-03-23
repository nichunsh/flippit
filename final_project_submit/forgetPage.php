<?php 
require 'config.php';
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
		<div id="login" class="size">
			<form id="loginForm">
				<div>
					<div class="green flex alignCenter">
							<div class="grow"><hr class="paleHR"></div>
							<h2 class="paleyellow formTitle">&nbsp; Password Recovery &nbsp; </h2>
							<div class="grow"><hr class="paleHR"></div>
					</div>
					<div>
						<label for="email-password">Email:</label>
						<input type="email" id="email-password" name="email" class="inputBox">
					</div>
					<div id="email-error" class="yellow"></div>
					<button type="submit" class="button green yellowButton">Recover Password</button>
				</div>
			</form>
		</div>
	</div>		
	<script src="http://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
	<script src="navbar.js"></script>
	<script>
		$(document).ready(function(){
			$('#loginForm').on('submit', function(){
				event.preventDefault()
				if ($('#email-password').val().trim().length == 0){
					if (!$('#email-password').hasClass('redBorder')){
						$('#email-password').addClass('redBorder')
					}
						$('#email-error').text('Fill in all fields.')

				} else {
					if ($('#email-password').hasClass('redBorder')){
						$('#email-password').removeClass('redBorder')
					}
						$('#email-error').text('')

						let link = 'email='+ $('#email-password').val().trim()
						ajaxPost('email.php', link, function(response){
							if (response == "Email Sent!") {
								$('#email-error').text(response)
								$('label').addClass('hidden')
								$('#email-password').addClass('hidden')
								$('.button').addClass('hidden')
								setTimeout(function(){
									window.location.replace("homePage.php")
								}, 2000)
							} else {
								$('#email-error').text(response)
							}
							
						})


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
</body>
</html>