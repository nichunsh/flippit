$(document).ready(function(){

	$(".redButton").on('click', function() {
        if( confirm('You are about to delete all your game records.') ) {
        	console.log('deleting')

        	ajaxPost('deleteRecords.php', "", function(response){
				if (response == "Success!"){
					$('#deleteMessage').text(response)
					$('#submitEmail').off()
					setTimeout(function(){
						location.reload()
					}, 1200)
				} else {
					$('#deleteMessage').text(response)
				}
			})
        }
            
    })

    $('#submitEmail').on('click',function(){
    	$('#submitEmail').off()
    	$('#emailInput').slideDown(200)
    	$('#submitEmail').on('click',function(){
    		console.log('submit')

    		var empty = false
	    	if ($('#email').val().trim().length == 0){
				if (!$('#email').hasClass('redBorder')){
					$('#email').addClass('redBorder')
				}
				empty = true
			} else {
				if (!$('#email').val().trim().includes('@') || $('#email').val().trim().charAt($('#email').val().trim().length - 1)=='@'){
					if (!$('#email').hasClass('redBorder')){
						$('#email').addClass('redBorder')
					}
					$('#emailMessage').text('Not an Email.')
					
					return false
				} else {
					if ($('#email').hasClass('redBorder')){
						$('#email').removeClass('redBorder')
					}
				}
			}

			console.log($('#userEmail').text())


			if (empty){
				$('#emailMessage').text('Fill in email.')
			} else if ($('#email').val().trim() == $('#userEmail').text()) {
				if (!$('#email').hasClass('redBorder')){
					$('#email').addClass('redBorder')
				}
				$('#emailMessage').text('Email Unchanged.')
			} else {
				$('#emailMessage').text("")
				var link  = "email=" + $('#email').val().trim()

				ajaxPost('changeEmail.php', link, function(response){
					if (response == "Success!"){
						$('#emailMessage').text(response)
						$('#submitEmail').off()
						setTimeout(function(){
							location.reload()
						}, 1200)
					} else {
						$('#emailMessage').text(response)
					}
				})

			}
    		
    		
    	})
    })

    $('#submitPassword').on('click',function(){
    	$('#submitPassword').off()
    	$('#passwordInputs').slideDown(200)
    	$('#submitPassword').on('click',function(){
    		console.log('submit')

    		var empty = false
    		var match = true
    		if ($('#oldPassword').val().trim().length == 0){
				if(!$('#oldPassword').hasClass('redBorder')){
					$('#oldPassword').addClass('redBorder')
				}
				empty = true
			} else {
				if($('#oldPassword').hasClass('redBorder')){
					$('#oldPassword').removeClass('redBorder')
				}
			}

			if ($('#password').val().trim().length == 0){
				if(!$('#password').hasClass('redBorder')){
					$('#password').addClass('redBorder')
				}
				empty = true
			} else {
				if($('#password').hasClass('redBorder')){
					$('#password').removeClass('redBorder')
				}
			}

    		if ($('#passwordConfirm').val().trim().length == 0){
				if (!$('#passwordConfirm').hasClass('redBorder')) {
					$('#passwordConfirm').addClass('redBorder')
				}
				empty = true
			} else {
				if ($('#passwordConfirm').hasClass('redBorder')) {
					$('#passwordConfirm').removeClass('redBorder')
				}

				if ($('#passwordConfirm').val().trim() != $('#password').val().trim()){
					match = false
				}
			}

			if (empty){
				$('#passwordMessage').text('Fill in all fields.')
			} else {
				if (!match) {
					if (!$('#passwordConfirm').hasClass('redBorder')) {
						$('#passwordConfirm').addClass('redBorder')
					}
					if(!$('#password').hasClass('redBorder')){
						$('#password').addClass('redBorder')
					}
					$('#passwordMessage').text('Passwords do not match.')
				} else {
					$('#passwordMessage').text("")

					var password = $('#oldPassword').val().trim()
					var newPassword = $('#password').val().trim()

					let link = "password=" + password + "&newPassword=" + newPassword

					ajaxPost('changePassword.php', link, function(response){
						if (response == "Success!"){
						$('#passwordMessage').text(response)
						$('#submitPassword').off()
						setTimeout(function(){
							location.reload()
						}, 1200)
						} else {
							$('#passwordMessage').text(response)

							if (response.includes('Wrong')){
								if(!$('#oldPassword').hasClass('redBorder')){
									$('#oldPassword').addClass('redBorder')
								}
							}
						}
					})
				}
    		}
    	})
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