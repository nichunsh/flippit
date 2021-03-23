$(document).ready(function(){

$('#registerForm').on('submit', function(event){
	event.preventDefault()
	var empty = false
	var match = true
	if ($('#register-username').val().trim().length == 0){
		if (!$('#register-username').hasClass('redBorder')){
			$('#register-username').addClass('redBorder')
		}
		empty = true
	} else {
		if ($('#register-username').hasClass('redBorder')){
			$('#register-username').removeClass('redBorder')
		}
	}

	if ($('#register-email').val().trim().length == 0){
		if (!$('#register-email').hasClass('redBorder')){
			$('#register-email').addClass('redBorder')
		}
		empty = true
	} else {
		if ($('#register-email').hasClass('redBorder')){
			$('#register-email').removeClass('redBorder')
		}
	}

	if ($('#register-password').val().trim().length == 0){
		if(!$('#register-password').hasClass('redBorder')){
			$('#register-password').addClass('redBorder')
		}
		empty = true
	} else {
		if($('#register-password').hasClass('redBorder')){
			$('#register-password').removeClass('redBorder')
		}
	}

	if ($('#register-passwordConfirmation').val().trim().length == 0){
		if (!$('#register-passwordConfirmation').hasClass('redBorder')) {
			$('#register-passwordConfirmation').addClass('redBorder')
		}
		empty = true
	} else {
		if ($('#register-passwordConfirmation').hasClass('redBorder')) {
			$('#register-passwordConfirmation').removeClass('redBorder')
		}

		if ($('#register-passwordConfirmation').val().trim() != $('#register-password').val().trim()){
			match = false
		}
	}

	if (empty){
		
		$('#register-error').text('Fill in all fields.')
		return false


	} else {

		if (!match) {
			if (!$('#register-passwordConfirmation').hasClass('redBorder')) {
				$('#register-passwordConfirmation').addClass('redBorder')
			}
			if(!$('#register-password').hasClass('redBorder')){
				$('#register-password').addClass('redBorder')
			}
			$('#register-error').text('Passwords do not match.')
			return false
		} else {
			$('#register-error').text('')

			var username = $('#register-username').val().trim()
			var email = $('#register-email').val().trim()
			var password = $('#register-password').val().trim()

			let link = 'username='+username+'&email='+email+'&password='+password

			ajaxPost('userCheck.php', link, function(results) {
				console.log(results)
				if (results == "Success!"){
					$('#register-error').text(results)
					$('#register-username').val("")
					$('#register-email').val("")
					$('#register-password').val("")
					$('#register-passwordConfirmation').val("")
					return false
				} else {
					if (results.includes('Username')){
						if (!$('#register-username').hasClass('redBorder')){
							$('#register-username').addClass('redBorder')
						}
					}

					if (results.includes('Email')){
						if (!$('#register-email').hasClass('redBorder')){
							$('#register-email').addClass('redBorder')
						}
					}

					$('#register-error').text(results)
					return false
				}
			})

			
			return false
		}

		
	}
})

$('#loginForm').on('submit', function(event){
	event.preventDefault()
	var empty = false
	if ($('#login-username').val().trim().length == 0){
		if (!$('#login-username').hasClass('redBorder')){
			$('#login-username').addClass('redBorder')
		}
		empty = true
	} else {
		if ($('#login-username').hasClass('redBorder')){
			$('#login-username').removeClass('redBorder')
		}
	}

	if ($('#login-password').val().trim().length == 0){
		if(!$('#login-password').hasClass('redBorder')){
			$('#login-password').addClass('redBorder')
		}
		empty = true
	} else {
		if($('#login-password').hasClass('redBorder')){
			$('#login-password').removeClass('redBorder')
		}
	}

	if (empty){
		$('#login-error').text('Fill in all fields.')
		return false
	} else {
		$('#login-error').text('')

		var username = $('#login-username').val().trim()
		var password = $('#login-password').val().trim()

		let link = 'username='+username+'&password='+password

		ajaxPost('loginCheck.php', link, function(results) {
				console.log(results)
				if (results == "Success!"){
					$('#login-username').val("")
					$('#login-password').val("")
					$('#loginForm').off()
					window.location.replace("profilePage.php");
					return false
				} else {
					if (results.includes('Username')){
						if (!$('#login-username').hasClass('redBorder')){
							$('#login-username').addClass('redBorder')
						}
					}

					if (results.includes('Password')){
						if (!$('#login-password').hasClass('redBorder')){
							$('#login-password').addClass('redBorder')
						}
					}

					$('#login-error').text(results)
					return false
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