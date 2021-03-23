var flips = 0
var start = 0
var x = 0
var milli = 0
var sec = 0
var min = 0


$(document).ready(function(){
	$('#color').on('click',function(){
		$(this).toggleClass('colorRed')
		$(this).toggleClass('colorBlue')

		$('.f').toggleClass('colorRedf')
		$('.f').toggleClass('colorBluef')

		$('.b').toggleClass('colorRedb')
		$('.b').toggleClass('colorBlueb')

	})

	$('#flippit').on('click',function(){
		event.preventDefault()
		$('.yellowCardinner').css('transform','rotateY(180deg)')
		$('#answerDiv').slideUp(800)
	})

	$('#Cards').one('click', function(){
		start = Date.now()
		x = setInterval(updateTimer, 10)
	})

	$('.f').on('click', getCards)
});

function getCards() {
	console.log('card')
	if ($(this).parent().css('transform')!= 'rotateY(180deg)'){
		$('.f').off('click', getCards)
		$('.f').on('click',flip($(this)))
	}
}

function updateTimer() {
	var elapse = Date.now() - start

	milli = elapse

	min = Math.floor(milli/60000)
	milli = milli%60000
	sec = Math.floor(milli/1000)
	milli = milli%1000

	$('#min').text(("00"+min).slice(-2))
	$('#sec').text(("00"+sec).slice(-2))
	$('#milli').text(("00"+milli).slice(-2))
	// $('#milli').text(milli)

	
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
				$('.f').off('click')
				var card2 = $(this)

				if (card2.attr('name') != card.attr('name')){
					card.parent().addClass('flip')
					card2.parent().addClass('flip')

					setTimeout(function(){
						card.parent().removeClass('flip')
						card2.parent().removeClass('flip')
					}, 1200)

					console.log('back')
				} else {
					card.parent().addClass('flip')
					card2.parent().addClass('flip')
					console.log('flip')
				}

				updateFlip()

				var done = true

				$('.card-inner').each(function (){
					if (!$(this).hasClass('flip')){
						done = false
					}
				})

				if (done) {
					clearInterval(x)
				} else {
					$('.f').on('click', getCards)
				}

				
			}
		}
	}

}