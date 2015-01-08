$(document).ready(function(){
	$('.partners').slick({
		slidesToShow: 4,
		slidesToScroll: 4,
		autoplay: true,
		variableWidth: true
	});
	
	$('.results-for').slick({
		slidesToShow: 1,
		slidesToScroll: 1,
		arrows: false,
		fade:true,
		asNavFor: '.results-nav'
	});
	
	$('.results-nav').slick({
		slidesToShow: 3,
		slidesToScroll: 1,
		asNavFor: '.results-for',
		dots: true,
		centerMode: true,
		focusOnSelect: true,
		autoplay: true,
		autoPlaySpeed: 3000
	});
	
});