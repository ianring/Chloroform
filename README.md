# Chloroform

Chloroform is the best jQuery-based client-side form validation plugin. There are lots of other ones. Some are good, others are horrid. This one is much better than most, and slightly awesomer than the second best. It's simple to use, easy to customize, highly extensible, and very efficient.


## Get Started

Of course, you need to include the Chloroform script and stylesheet in your head. You need jQuery too.

		<script src="path_to_jquery"></script>
		<script src="chloroform.js"></script>
		<link rel="stylesheet" href="chloroform.css" />

You also need to execute Chloroform on a form element. For best results, do this in a ready() callback.

		<script>
		jQuery(document).ready(function($) {
			$('#myform').chloroform();
		});
		</script>
		
		<form id="myform">
		etc.
		
There are a bunch of options you can pass in to the chloroform() method, but we'll look at those later.

## Your First Validation with Chloroform

To add form validation to your form elements, just add an attribute named "data-validate", with special rule names in it like "required" and "length".

For example, the field "myfield" below has a validation rule named "required". It must not be empty.

	<form id="myform">
	<input id="myfield" type="text" value="" data-validate="required"/>
	<input type="submit" value="Save"/>
	</form>

### Using Rules

A "rule" is a criterium that the value of a form element must fulfil, before it can be deemed valid. In reality, it's a javascript function that returns true or false.

The rules are named. They have names like "numeric" and "required". To apply a rule to an HTML form element, you add its name to the data-validate attribute, like the example above.

### Multiple Rules

To add multiple rules to the same form element, separate the rule names with a semicolon.

	<form id="myform">
	<input id="myfield" type="text" value="123" data-validate="required;email"/>
	<input type="submit" value="Save"/>
	</form>

The logic operator binding multiple rules is "AND". In order for a form element to be valid, it must obey all the rules. In the example above, the form element must be not-empty (required) AND it must also look like an email address.

If you have unusual validation rules that require something like an "OR" or some other logical operation, then you just need to create a custom rule. Which is easy to do.


### Inverting Validation with "!"

To indicate that an element should be valid when a rule does NOT pass, precede the rule name with a !.

For example, this element will be valid if the value is NOT an integer.

	<input type="text" id="field" data-validate="!integer" value="3.141"/>

You can do interesting things with this. For example, to prevent people from putting an email address into a username field, you could explicitly put a NOT email rule on it:

	<input type="text" id="username" data-validate="!email" value=""/>

You could also prevent words from being present in the value, by putting a NOT operator on the "contains" rule:

	<input type="text" id="username" data-validate="!contains[badword]" value=""/>


### Rules with Arguments

Some require arguments. For example, the "length" rule takes either one or two arguments. Arguments are expressed in round brackets, with multiple arguments separated by a comma.

For example, the field "myfield" below must be between 6 and 16 characters.

	<form id="myform">
	<input id="myfield" type="text" value="123" data-validate="length(6,16)"/>
	<input type="submit" value="Save"/>
	</form>

It looks like a function call, doesn't it? That's deliberate.

### Built-In Rules

Chloroform comes with an excellent collection of pre-rolled rules. Here are some that are defined in version 1.0:

* required
* equals
* sameas
* alpha
* numeric
* integer
* max
* min
* between
* length
* choices
* contains
* regex
* email
* url

It's a pretty solid collection of standard, well-written, optimized rules. But Chloroform doesn't end there - it's easy to write your own custom rules, too.

### Custom Rules

A custom rule is a named function that you write in the global scope. The function must adhere to these criteria:

* the first argument is the element being validated, preselected as a jQuery object so you can use methods like element.val()
* the second argument the "not" boolean, indicating if the rule is being applied normally, or inverted. Depending on the behaviour of the rule, this may mean just the difference between a pass or fail, but it may also change the error message being returned.
* the return value should be an object with one member named "valid". If valid is false, then a second member named "message" contains the error message to be displayed to the user.

Here's a good, simple example:

	function custom1(element,not){
		if (element.val() == "123"){
			return {'valid':true}
		} else {
			return {'valid':false,'message':'dude, that\'s not right.'}
		}
	}

This form element uses the custom1 rule:

	<input type="text" id="field6" data-validate="custom1" value="123"/>

It's that easy! If your data-validate attribute contains a name, Chloroform will look for a function with that name in the global scope. 

Here's one that's a little more complex, showing how the wording of the error message is altered by the "not" inversion. It also takes a third argument:

	function maximum(elem,not,max){
		var val = parseFloat($(elem).val());
		if (val > max){
			return not?{'valid':true}:{'valid':false,'message':'this must be less than '+max};
		}
		return not?{'valid':false,'message':'this must not be less than '+max}:{'valid':true};
	}

This rule would be applied to this form element. Notice the parameter in square brackets, which is ultimately passed along as the third argument of the maximum() function.

	<input type="text" id="field7" data-validate="maximum(5)" value="123"/>


## Chloroform Options



You can customize how chloroform behaves by defining a whole bunch of useful options.

	<script>
		jQuery(document).ready(function($) {
			$('#myform').chloroform({
				lang: 'en', 							// default language - i18n has not been implemented yet
				validateDataAttr: 'data-validate', 		// name of the attribute which stores what validation rules to apply
				scrollToBubble: true,					// if true, then the error bubble popup will scroll into view.
				
				// callback functions
				onBeforeValidateAll: function(){
					// do something before a validateAll action
				},
				onAfterValidateAll: function(){
					// do something after a validateAll action
				},
				onBeforeValidate:function(){
					// do something before a validate action
				},
				onAfterValidate:function(){
					// do something after a validate action
				}
			});
		});
	</script>

## Options

### lang
at this time, i18n is not implemented. The "lang" option is reserved for use as a language identifier.

### validateDataAttr
this is the attribute that will be used to register validation rules. By default it's "data-validate", but you can change that.

### scrollToBubble
if true, then the error bubble popup will scroll into view when it appears, with an animated "smooth scroll".

## Callback Functions

### onBeforeValidateAll()
returns boolean

if onBeforeValidateAll returns false, the validation is aborted and validateAll() returns false, and the submit event that triggered the validation is aborted. 
if onBeforeValidateAll returns true, then validation proceeds normally. 
Use this to prevent the validation from continuing based on some other factor which may invalidate the entire form.

### onAfterValidateAll(bool allPassed)
Has one argument, indicating if all the validation rules passed. If onAfterValidateAll returns true, then submission proceeds normally. If onAfterValidateAll returns false, then submission is stopped. 
Use this callback to hook in additional behaviour to the form, for example to control the disabled state of the submit button if the form isn't filled out correctly.

### onBeforeValidate()
returns boolean
this callback is triggered immediately before any validation begins on an element. If many elements are being validated, then this function will be called once for each element.
If this function returns false, then the validation will fail and abort.

### onAfterValidate(bool passed)
returns boolean
this callback is triggered immediately after all the validation rules are finished on an element. The argument passed into onAfterValidate() is whether the rules passed or failed.
If this function returns false, then validation fails and aborts.








## Chloroform Object Architecture

Chloroform achieves its validation by saving an object in the "data" property of each form element. This object doesn't just describe how the element should be validated - it actually contains the functions that are executed, when triggered by Chloroform's form handlers.

The plugin defines an array of elements. Each element has two data elements: rules and arguments.
For example, the plugin <i>elements</i> array might contain: [field1,field2,field3]. Each element in the array is a jQuery object, as would be returned from a jQuery selector like $('#field1').

The validation rules applied to each element are stored in its data object. The rules inhabit two named objects in the element's data: rules and arguments.

#### rules, arguments, and not

Each element has these three elements in its data object

#### element.data('rules')

element.data('rules') is an object - used like a named associative array - of functions. Each function is named, and doesn't matter if it's one of Cholroform's built-in preset functions, or a custom one.
When expressed as JSON, the data('rules') object will look like this:

	{
		'notafive':function(){
			var elem = arguments[0];
			var not = arguments[1];
			var val = parseFloat($(elem).val());
			if (val != 5){
				return {'valid':false,'message':'this is not a five'};
			}
			return {'valid':true};
		},
		'max':function()
			var elem = arguments[0];
			var not = arguments[1];
			var val = parseFloat($(elem).val());
			var max = parseFloat(arguments[2]);
			if (val > max){
				return {'valid':false,'message':'this must be less than '+max};
			}
			return {'valid':true};
		}
	}




All rules accept <i>element</i> as their first argument, and <i>not</i> as their second. Some accept more additional arguments. The return value of a rule function is strict, as will be explained below.

#### element.data('arguments')

element.data('arguments') is an object - again, used like an associative array - of argument arrays. Rules that require extra arguments store those arguments here.
The data('arguments') object is used to hold the arguments for those rule functions that accept them. For example, one of your rules might be 'length', which accepts the arguments (min,max). If your element validates when the length is between 5 and 16, then the value of data('arguments') will be:

	{
		'length':[5,16]
	}

By manipulating the data('arguments') object, you can change the parameters of a validation rule without having to change the rule itself. Arguments are populated automatically when you add arguments in square brackets in the data-validate attribute, eg: "length[5:16]"
When manipulating the arguments object, it is important to remember that every array in arguments must have a function of the same name in rules. The array of values must correspond - in the correct order - to the arguments expected by the corresponding rule function.



