jQuery(document).ready(function($) {
	$('#example1').chloroform();
	
});

function custom1(element,not){
	if (element.val() == "123"){
		return {'valid':true}
	} else {
		return {'valid':false,'message':'dude, that\'s not right.'}
	}
}
