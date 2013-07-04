jQuery(document).ready(function($) {
	
	// the checkbox that's already on the page will be validated, just by initializing chloroform
	$('#example3-1').chloroform();
	$('#example3-2').chloroform();
	
	// elements that are added dynamically don't get noticed by chloroform, unless you
	// register them
	$('#add_field').click(function(){
		var newfield = $('<input type="checkbox" data-validate="required"></input>');
		$('#fields').append(newfield);
		
		// the new field must be registered with chloroform. Here's the syntax to do that.
		$('#example3-1').chloroform('register', newfield);
		
	});

	$('#add_field2').click(function(){
		var newfield2 = $('<input type="checkbox" name="choices2" data-validate="choices(3)"></input>');
		$('#fields2').append(newfield2);
		
		// the new field must be registered with chloroform. Here's the syntax to do that.
		$('#example3-2').chloroform('register', newfield2);
		
	});

});