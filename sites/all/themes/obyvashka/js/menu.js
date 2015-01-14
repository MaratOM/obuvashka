$(document).ready(function(){
	$("#color ul li, ul.menu li").mouseover(function(){
			$(this).stop().animate({backgroundPosition: '0px 0px'},{queue:false, duration:600, easing: 'easeOutBounce'});
	});
	$("#color ul li a, ul.menu li a").mouseover(function(){
		$(this).css('color','#ECF1C9');
		}); 

	$("#color ul li, ul.menu li").mouseout(function(){
			$(this).stop().animate({backgroundPosition: '-120px 0px'},{queue:false, duration:600, easing: 'easeOutBounce'});
	});
	$("#color ul li a, ul.menu li a").mouseout(function(){
		$(this).css('color','#2479ab');
		}); 

});