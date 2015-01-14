$(document).ready(function(){


var li_count=$("#color ul li").size();
var i;
var choice;
var choice_a;

//When mouse rolls over

	for(i=0;i<li_count;i++)
	{
		choice="#color ul li:eq("+i+")";
		$(choice).mouseover(function(){
			$(this).stop().animate({backgroundPosition: '0px'},{queue:false, duration:600, easing: 'easeOutBounce'})
		});
	}
	for(i=0;i<li_count;i++)
	{
		choice="#color ul li:eq("+i+")";
		choice_a=choice+" a";
		$(choice_a).mouseover(function(){
		$(this).css('color','#ECF1C9')
		}); 
	}
//When mouse is removed

	for(i=0;i<li_count;i++)
	{
		choice="#color ul li:eq("+i+")";
		$(choice).mouseout(function(){
			$(this).stop().animate({backgroundPosition: '-120px'},{queue:false, duration:600, easing: 'easeOutBounce'})
		});
	}
	for(i=0;i<li_count;i++)
	{
		choice="#color ul li:eq("+i+")";
		choice_a=choice+" a";
		$(choice_a).mouseout(function(){
		$(this).css('color','#2479ab')
		}); 
	}

//$("#color ul li:eq(0)").mouseout(function(){
//$("#color ul li:eq(0) a").css('color','#2479ab'),
//$("#color ul li:eq(0)").stop().animate({backgroundPosition: '-120px'},{queue:false, duration:600, easing: 'easeOutBounce'})
//});

});