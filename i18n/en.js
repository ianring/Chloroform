if (typeof Chloroform == 'undefined'){
	Chloroform = {};
}
if (typeof Chloroform.i18n == 'undefined'){
	Chloroform.i18n = {};
}

/**
*
*    Adding your own translated phrases is easy!
*
*    The Chloroform.i18n object contains an object named after the language, e.g. "en" or "fr" or "es
*    Inside that, there are keys for the rule name. Every rule has a name. If you're defining i18n for a 
*    custom function, then you use the function name.
*
*    Inside that, you create key:value pairs containing your translated phrases.
*    A number in curly brackets is a substitution placeholder, sort of like you'd see in a regular expression or a sprinf.
*    {0} accepts the value of the first argument, {1} is the second, and so on.
*
*    Go take a look at some of the examples at chloroform.gralix.com, and you'll see that it makes sense.
*
*/

Chloroform.i18n.en = {
	'required':{
		'1':'this field is required'
	},
	'equals':{
		'1':'this field is not {0}'
	},
	'sameas':{
		'1':'values are not the same',
		'2':'values must not be the same'
	},
	'alpha':{
		'1':'this field is not alphabetic'
	},
	'numeric':{
		'1':'this field is not numeric',
		'2':'this field must not be numeric'
	},
	'integer':{
		'1':'this field is not an integer',
		'2':'this field must not be an integer'
	},
	'max':{
		'1':'this must be less than {0}',
		'2':'this must not be less than {0}'
	},
	'min':{
		'1':'this must be at least {0}',
		'2':'this must be at least {0}'
	},
	'between':{
		'1':'this must be between {0} and {1}',
		'2':'this must not be between {0} and {1}'
	},
	'length':{
		'1':'this must be at least {0} characters long',
		'2':'length must be {0}',
		'3':'length must be between {0} and {1}',
		'4':'length must not be {0}'
	},
	'choices':{
		'1':'you must choose at least {0} of these',
		'2':'you must choose exactly {0} of these',
		'3':'you must choose at least {0} of these',
		'4':'you must choose less than {0} of these'
	},
	'email':{
		'1':'please enter a valid email address'
	},
	'regex':{
		'1':'this field is not valid'
	}
	
	
}