$(document).ready(function() {
function activateThumb(no) {
	$("#carouselThumb li").removeClass("active");
	$("#carouselThumb li:eq("+no+")").addClass("active");
}
function bigSlideControl(arg) {
	var oBigController = arg;
	var currentItem = oBigController.getCurrentID();
	activateThumb(currentItem);
	if(currentItem==0) {
		$("#imgLeftBig").css({opacity:0.4});
	} else {
		$("#imgLeftBig").css({opacity:1});
	}
	
	//console.debug("currentItem "+currentItem)
	if(thumbs!=undefined) {
		thumbs.goto(parseInt(currentItem));
	}	
//	alert(currentItem);
}
function thumbSlideControl(arg) {
	var oController = arg;
	var currentItem = oController.getCurrentID();
	if(currentItem==0) {
		$("#imgLeftThumb").css({opacity:0.4});
	} else {
			$("#imgLeftThumb").css({opacity:1})
	}
}

// autoSlide:2000, 
var oBigController = $("#carouselBig").msCarousel({width:900, height:369,callback:bigSlideControl, showMessage:true, messageOpacity:1, autoSlide: 5000}).data("msCarousel");
var thumbs = $("#carouselThumb").msCarousel({boxClass:'li', width:900, height:78, callback:thumbSlideControl, scrollSpeed:500}).data("msCarousel");

//big button click
$("#imgRightBig").click(function() {
	oBigController.next();
});
$("#imgLeftBig").click(function() {
	oBigController.previous();
})

//thumb click
$("#imgRightThumb").click(function() {
	thumbs.next();
});
$("#imgLeftThumb").click(function() {
	thumbs.previous();
})

//add click event
$("#carouselThumb li").click(function(arg) {
	var target = this;
	var counter = $("#carouselThumb li").index(target);
	oBigController.goto(parseInt(counter));
})	
});