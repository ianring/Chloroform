$('#example7').chloroform({
	'onAfterValidateAll':function(form, isValid){
		// this function returns true or false. Its return value will override the validation of the entire form.
		if (isValid) {
			return confirm('The form is valid. \n\nAre you sure you want to submit this form?');
		} else {
			return false;
		}
	}
});
