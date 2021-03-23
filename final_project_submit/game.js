var flips = 0
var start = 0
var x = 0
var milli = 0
var sec = 0
var min = 0
var hr = 0
var elapse = 0
var centi = 0


$(document).ready(function(){
	$('#again').hide()

	$('#color').on('click',function(){
		$(this).toggleClass('colorRed')
		$(this).toggleClass('colorBlue')

		$('.f').toggleClass('colorRedf')
		$('.f').toggleClass('colorBluef')

		$('.b').toggleClass('colorRedb')
		$('.b').toggleClass('colorBlueb')

	})

	$('#playButton').on('click', setUpGame)

	$('#again').on('click', playAgain)

	if ($(window).width()>700 && $(window).width()*$(window).height()>700*660){
		$('#big').removeAttr("disabled")
	}

	$(window).on('resize', limitOption)

});

function limitOption() {
	option = document.getElementById('big')
	if (($(window).width()>700 && $(window).width()*$(window).height()>700*660)|| ($(window).width()>=500 && $(window).width()<670 && $(window).width()*$(window).height()>134*86*24)){
		if (option.hasAttribute("disabled")){
			$('#big').removeAttr("disabled")
		}
	} else {
		if (!option.hasAttribute("disabled")){
			$('#big').attr('disabled','disabled')
		}
		if ($('#gameType').val() != 24){
			$('#gameType').val(24)

			if ($('#Cards').contents().length > 0) {
				setUpGame()
			}
		}
		
	}
}

function getCards() {
	if ($(this).parent().css('transform')!= 'rotateY(180deg)'){
		$('#Cards').off('click', '.f', getCards)
		$('#Cards').on('click', '.f', flip($(this)))
	}
	$('#gameType').off()

}

function updateTimer() {
	elapse = Date.now() - start

	milli = elapse

	hr = Math.floor(milli/3600000)
	milli = milli%3600000
	min = Math.floor(milli/60000)
	milli = milli%60000
	sec = Math.floor(milli/1000)
	milli = milli%1000
	centi = Math.floor(milli/10)

	



	$('#hr').text(("00"+hr).slice(-2)+":")
	if ( $('#hr').text() != "00:") {
		$('#hr').removeClass('hidden')
	}
	$('#min').text(("00"+min).slice(-2))
	$('#sec').text(("00"+sec).slice(-2))
	$('#milli').text(("00"+centi).slice(-2))

	
}

function updateFlip() {
	flips++
	$('#flip').text(flips)
}

function flip(card){
	return function() {
		if (!$(this).parent().hasClass('flip')){
			console.log('click')
			if (!$(this).is(card)){
				$('#Cards').off('click', '.f')
				var card2 = $(this)

				updateFlip()

				if (card2.attr('name') != card.attr('name')){
					card.parent().addClass('flip')
					card2.parent().addClass('flip')

					setTimeout(function(){
						card.parent().removeClass('flip')
						card2.parent().removeClass('flip')
						$('#Cards').on('click', '.f', getCards)
					}, 1000)

				} else {
					card.parent().addClass('flip')
					card2.parent().addClass('flip')
					console.log('flip')

					var done = true

					$('.card-inner').each(function (){
						if (!$(this).hasClass('flip')){
							done = false
						}
					})

					if (done) {

						clearInterval(x)
						console.log('stop timer')
						elapse = Math.floor(elapse/10)
						let link = String('centiseconds=' + elapse + '&flips=' + flips + '&type=' + $('#gameType').val())
						ajaxPost('gameStats.php', link, function(response){
							console.log(response)

							$('#again').slideDown(200)

						})
					} else {
						setTimeout(function(){
							$('#Cards').on('click', '.f', getCards)
						}, 600)
					}

				}

			}
		}
	}
}

function playAgain() {
	$('#again').slideUp(200)
	$('#hr').text("00:")
	if (!$('#hr').hasClass('hidden')){
		$('#hr').addClass('hidden')
	}
	$('#min').text("00")
	$('#sec').text("00")
	$('#milli').text("00")
	$('#flip').text(0)

	min=0
	hr=0
	sec=0
	milli=0
	flips=0
	centi=0

	setUpGame()
	

}

function setUpGame() {
	$('#playButton').off()
	if (!$('#playButton').hasClass('hidden')) {
		$('#playButton').addClass('hidden')
	}
	if ($('#color').hasClass('hidden')){
		$('#color').removeClass('hidden')
	}
	
	let num = $('#gameType').val()
	let id = []
	for (var i = 1 ; i < num/2+1; i++) {
		id.push(i)
		id.push(i)
	}

	shuffle(id)

	let cards = []


	for (var i = 0; i < id.length; i++) {
		let cd = document.createElement('div')
		$(cd).addClass('flex wrap').append(buildCard(id[i++]),buildCard(id[i]))
		cards.push(cd)
	}

	$('#Cards').empty()
	$('#Cards').append(cards)

	$('#timer').on('click', function(){
		$('#timer').off()
		console.log('timer off')
		start = Date.now()
		x = setInterval(updateTimer, 10)
	})
	
	$('#gameType').on('change',function(){
		setUpGame()
	})

	$('#Cards').on('click', '.f', getCards)

}

function buildCard(name){
	let card = document.createElement('div')
	$(card).addClass('card')

	let cardInner = document.createElement('div')
	$(cardInner).addClass('card-inner')

	let cardFront = document.createElement('div')
	let cardBack = document.createElement('div')

	if ($('#color').hasClass('colorRed')){
		$(cardFront).addClass('f colorRedf').attr('name', name)
		$(cardBack).addClass('b colorRedb')
	} else {
		$(cardFront).addClass('f colorBluef').attr('name', name)
		$(cardBack).addClass('b colorBlueb')
	}

	let src = 'icons/'+ name + '.png'
	
	let img = document.createElement('img')
	$(img).attr('src', src)

	$(cardBack).append($(img))

	$(cardInner).append($(cardFront),$(cardBack))

	$(card).append($(cardInner))

	return card
}

function shuffle(array) {
	for (i = array.length -1; i > 0; i--) {
	  j = Math.floor(Math.random() * i)
	  k = array[i]
	  array[i] = array[j]
	  array[j] = k
	}
	return array
}

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