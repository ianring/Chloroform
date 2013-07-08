$('#example5').chloroform();

// this could have been done a totally different way, using a 
// custom validation function. But here, I want to show how you 
// can manipulate the "arguments" of a validator dynamically.

$('.minradio').click(function(){
	var min = $(this).val();
	
	// if you're curious, inspect the data() property of the element.
	// it contains all the ingredients for its own validation, and they
	// can be manipulated in real time.
	$('#mytextinput').data('arguments',{'length':[min]});
	
	// when you click a radio button, clear any feedback.
	// the public method for that is "hidebubble"
	$('#example5').chloroform('hideBubble');
	
});
