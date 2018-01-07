// JavaScript Document
$(function()
{
function animationLeft(){
$(".project-details").show();	
$(".project-details").css({"margin-right" : -$(".project-details").outerWidth()});
	$(".goTolink").click(function(e) {
		var ProList = $(".all-projects-list").outerWidth(); 
		var MapList = $(".custom-google-map").outerWidth(); 
		var PPsList = $(".project-details").outerWidth(); 
		$(".project-details").animate({marginRight : ProList});
	});	
$(".block-buster").on('click','#close-pane',function(){$(".project-details").stop( true,true).animate({marginRight : -$(".project-details").outerWidth()},1000).removeClass('prod')});	
}

animationLeft();
});