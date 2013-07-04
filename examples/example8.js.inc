jQuery(document).ready(function($) {
	
	$('#example8').chloroform({
		'onBeforeValidate':function(element){
			// this is called for each element that gets validated.
			// it returns true or false. A false return will abort the entire validation.
			element.addClass('validating');
			return true;
		}
	});
	
});