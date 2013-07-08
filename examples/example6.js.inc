$('#example6').chloroform({
	'onBeforeValidateAll':function(form){
		form.find('input[type=submit]').prop('disabled',true);
		alert('the submit button is disabled to prevent double-clicking')
		return true;
	},
	'onAfterValidateAll':function(form,isValid){
		form.find('input[type=submit]').prop('disabled',false);
		return isValid;
	}
});
