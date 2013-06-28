$(document).ready(function(){
	$('#formexample1').chloroform({
		'onAfterValidateAll':fakesubmit
	});
	$('#formexample2').chloroform({
		'onAfterValidateAll':fakesubmit
	});
	$('#formexample3').chloroform({
		'onAfterValidateAll':fakesubmit
	});
	
	
	$( "#datepicker" ).datepicker({
		'onSelect':function(date){
			$('#datepickerinput').val(date);
		}
	});

	$( "#slider-range-min" ).slider({
		range: "min",
		value: 37,
		min: 1,
		max: 700,
		slide: function( event, ui ) {
			$( "#giftamount" ).val( ui.value );
		}
	});
	$( "#giftamount" ).val( $( "#slider-range-min" ).slider( "value" ) );
	
	// ------------------------------
	
	$('#example-nav li a').click(function(){
		
		var hash = this.hash.substr(1);
		
		$('#example-nav li').removeClass('active');
		$(this).closest('li').addClass('active');
		
		$('.example').addClass('hide');
		$('#'+hash).removeClass('hide');
		// make the first tab active
		$('#'+hash).find('ul.nav li:first a').click();
		return false;
	})
	
});

function fakesubmit(form,isvalid){
	if (isvalid) {
		alert('if this was not a demo, this form would be submitting right now.');
	}
	return false;
}


function isprimeunder300(elem,not) {
	var n = elem.val();
	if (isNaN(n)){
		return {'valid':false,'message':'that isn\'t a number'};
	}
	if (n>=300){
		return {'valid':false,'message':'that number is higher than 300'};
	}
	var m=Math.sqrt(n);
	for (var i=2;i<=m;i++){
		if (n%i==0) {
			return {'valid':false,'message':'that number is not prime'}
		}
	} 
	return {'valid':true};
}

function amibusythatday(elem,not){
	var v = elem.val();
	var d = new Date(v);
	var dd = d.getDate();
	if (dd==7 || dd==17 || dd==27){
		return {'valid':false,'message':'Sorry, days with a 7 in them are bad luck.'};
	}
	if (d.getDate()%5 == 0){
		return {'valid':false,'message':'Rain in the forcast that day. Try another one.'};
	}
	switch (d.getDay()){
		case 0:
			return {'valid':true};
			break;
		case 1:
			return {'valid':false,'message':'Sorry, that date is no good.  I\'m busy on Mondays'};
			break;
		case 2:
			return {'valid':false,'message':'Sorry, that date is no good.  I\'m busy on Tuesdays'};
			break;
		case 3:
			return {'valid':false,'message':'Sorry, that date is no good.  I\'m busy on Wednesdays'};
			break;
		case 4:
			return {'valid':true};
			break;
		case 5:
			return {'valid':false,'message':'Sorry, that date is no good.  I\'m busy on Fridays'};
			break;
		case 6:
			return {'valid':false,'message':'Sorry, that date is no good.  I\'m busy on Saturdays'};
			break;
	}
	return {'valid':true};
}