setTimeout(function(){
	var maxWidth = 0;
	var itemsCount = 0;
	_.each($('.partners > div > a > img'), function(img){
		maxWidth+=img.clientWidth;
		itemsCount+=1;
	});
	$('.partners').css('max-width',maxWidth+'px');
	
	$('.partners').slick({
		slidesToShow: 1,
		slidesToScroll: 1,
		variableWidth: true,
		centerMode: (itemsCount == 1) ? false : true,
		autoplay: true,
		autoPlaySpeed: 2000
	});
	
	$('.partners').toggleClass('transparent');
}, 1000);