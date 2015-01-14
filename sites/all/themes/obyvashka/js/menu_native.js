$(document).ready(function(){

//When mouse rolls over
$("div#color ul li").mouseover(function(){
$(this).stop().animate({width:'130px',height:'30px'},{queue:false, duration:600, easing: 'easeOutBounce'})
});

$("div#txt-up ul li").mouseover(function(){
$("div#color ul li").stop().animate({width:'130px',height:'30px'},{queue:false, duration:600, easing: 'easeOutBounce'})
});

//When mouse is removed
$("div#color ul li").mouseout(function(){
$(this).stop().animate({width:'10px',height:'30px'},{queue:false, duration:600, easing: 'easeOutBounce'})})
	

;

});