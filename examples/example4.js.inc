$('#example4').chloroform();

function customrule1(element,not){
	$val = element.val();
	
	var d = new Date();
	var n = d.getDay();
	if (n == 6){
		if (element.prop("checked")) {
			return {'valid':true};
		} else {
			return {'valid':false,'message':'it is saturday!'};
		}
	} else {
		if (element.prop("checked")) {
			return {'valid':false,'message':'it is not saturday!'};
		} else {
			return {'valid':true};
		}
	}
}