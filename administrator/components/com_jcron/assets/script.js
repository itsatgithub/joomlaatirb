window.addEvent('domready', function(){

   $each([$('minute2'), $('hour2'), $('day2'), $('month2'), $('weekday2')], function(obj,index){
		obj.addEvent('click',function(e){

                    $('crontab').value = $('minute2').value + " " + $('hour2').value + " " +
                                         $('day2').value+" " + $('month2').value+" " + $('weekday2').value;

                });
	});

});


