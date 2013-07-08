$('#example9').chloroform({
	'onAfterValidate':function(element, isValid){
		if (isValid){
			element.parent().addClass('valid');
			element.parent().removeClass('invalid');
		} else {
			element.parent().addClass('invalid');
			element.parent().removeClass('valid');
		}
		return true;
	}
});
