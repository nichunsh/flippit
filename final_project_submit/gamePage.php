<?php 


require 'config.php';


if (isset($_SESSION['logged']) && $_SESSION['logged'] && (!isset($_SESSION['question'])||empty($_SESSION['question']) || !$_SESSION['question'] )){
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	if ($mysqli->connect_errno) {
		echo $mysqli->connect_error;
		exit();
	}

	$sql = "SELECT * FROM questions WHERE user_id =". $_SESSION['userID'] .";";
	$result = $mysqli->query($sql);
	if (!$result) {
		echo $mysqli->error;
		exit();
	}

	$data = array();
	while ($row = $result->fetch_assoc())
	{
    	$data[] = $row;
	}

	$same = true;


	while ($same) {
		$curl = curl_init();
			curl_setopt_array($curl, array(
			CURLOPT_URL => 'http://jservice.io/api/random',
			CURLOPT_RETURNTRANSFER => true
		));
		$response = curl_exec($curl);

		$response = json_decode($response, true);

		if (strpos(($response[0])['answer'], "(")===false) {
			$match = false;
			foreach ($data as $row){
				if ($row['question_id'] == ($response[0])['id']){
					$match = true;
				}
			}

			$same = $match;
		}

		

	}


}


 ?>
<!DOCTYPE html>
<html>
<head>
	<link href="https://fonts.googleapis.com/css?family=Bangers|Fredoka+One&display=swap" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="navbarstyle.css">
	<title>FLIPPIT</title>
	<script src="https://kit.fontawesome.com/27db736dc1.js" crossorigin="anonymous"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<?php include 'navbar.php'; ?>
	<div id="body">
		<?php if (isset($_SESSION['logged']) && $_SESSION['logged'] && (!isset($_SESSION['question'])||empty($_SESSION['question']) || !$_SESSION['question'] )): ?>
		<div id="QuestionGame">
			<div class="yellow flex alignCenter">
				<div class="grow"><hr class="yellowHR"></div>
				<h2 class="yellow">&nbsp; Try a Question? &nbsp;</h2>
				<div class="grow"><hr class="yellowHR"></div>
			</div>
			
			<div>
				<div class="yellowCard">
					<div class="yellowCardinner">
						<div class="yellowCardf green">
							<p id="Category" class="purple"><?php echo ucfirst((($response[0])['category'])['title']); ?></p>
							<p id="Question"><?php echo ($response[0])['question']; ?></p>
							<p id="Value" class="blue"><?php echo ($response[0])['value']; ?></p>
						</div>
						<div class="yellowCardb green">
							<p id="Answer" class="blue">What is/are | Who is/are</p>
							<p id="answerInput" class="red"><?php echo ucfirst(($response[0])['answer']); ?></p>
						</div>
					</div>
				</div>
				<div id="answerDiv">
					<label for="answer">What is/are | Who is/are :</label>
					<input type="text" id="answer" name="answer" class="inputBoxR">
					<button type="submit" id="flippit" class="button purple yellowButton">Flippit!</button>
				</div>
				<div id="result"></div>
			</div>
		</div>
		<?php endif; ?>
		<div id="Game">

			<div class="green flex alignCenter">
				<div class="grow"><hr class="greenHR"></div>
				<h2 class="green">&nbsp; 
					<?php if (isset($_SESSION['logged']) && $_SESSION['logged'] && (!isset($_SESSION['question'])||empty($_SESSION['question']) || !$_SESSION['question'] )){
						echo 'OR NOT';
					} else {
						echo "FLIPPIT";
					} ?>

				 &nbsp;</h2>
				<div class="grow"><hr class="greenHR"></div>
			</div>
			<div id="Setting">
				<h3>Mode: 
					<select id="gameType">
						<option value="24">24 Cards</option>
						<option id="big" value="48" disabled="disabled">48 Cards</option>
					</select>
				</h3>
				<button id="playButton" class="button purpleButton paleyellow">Play!</button>
				<div id="color" class="colorRed hidden"></div>
			</div>
			<div id="timer"><div id="Cards"></div></div>
			<div id="Stats">
				<h3><span class="purple">Timer: </span><span id="time"><span id="hr" class="hidden">00:</span><span id="min">00</span>:<span id="sec">00</span>.<span id="milli">00</span></span></h3>
				<h3><span class="purple">Flipps: </span><span id="flip">0</span></h3>
			</div>
			<div id="again">
				<button class="button paleyellow purpleButton">Play Again</button>
			</div>
		</div>
	</div>
	<script src="http://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
	<?php if (isset($_SESSION['logged']) && $_SESSION['logged'] && (!isset($_SESSION['question'])||empty($_SESSION['question']) || !$_SESSION['question'] )): ?>
	<script>

		var id = <?php echo ($response[0])['id'];  ?>;
		var aInput = "<?php echo ucfirst(($response[0])['answer']); ?>";
		console.log(id)
		
		$(document).ready(function(){
			$('#flippit').on('click',function(){
				$('#answerInput').html(aInput)
				$('.yellowCardinner').css('transform','rotateY(180deg)')
				$('#answerDiv').slideUp(800)

				var answer = aInput.toLowerCase()
				var userAnswer = $('#answer').val().trim().toLowerCase()
				
				if(answer == userAnswer){
					$('#result').text('You are Correct!').addClass('red')
					let link = 'value='+ $('#Value').text() + '&id=' + id
					ajaxPost('questionStats.php', link, function(response){
						console.log(response)
					})
				} else {
					$('#result').text('Better luck next time').addClass('blue')
					let link = 'value=-'+ $('#Value').text() + '&id=' + id
					ajaxPost('questionStats.php',link, function(response){
						console.log(response)
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
	<?php endif; ?>
	<script src="navbar.js"></script>
	<script src="game.js"></script>
	
</body>
</html>