# Chloroform

Chloroform is the best jQuery-based client-side form validation plugin. There are lots of other ones. Some are good, others are horrid. This one is better than them all. It's simple to use, easy to customize, highly extensible, and very efficient.


## Get Started

To add form validation to your form elements, just add an attribute named "data-validate", with special rule names in it like "required" and "length".

For example, the field "myfield" below has a validation rule named "required". It must not be empty.

	<form id="myform">
	<input id="myfield" type="text" value="" data-validate="required"/>
	<input type="submit" value="Save"/>
	</form>

### Using Rules

A "rule" is a criteria that the value of a form element must fulfil, before it can be deemed valid. 

The rules are named. They have names like "numeric" and "required". To apply a rule to an HTML form element, you add its name to the data-validate attribute, like the example above.

### Multiple Rules

To add multiple rules to the same form element, separate the rule names with a comma.

	<form id="myform">
	<input id="myfield" type="text" value="123" data-validate="required,email"/>
	<input type="submit" value="Save"/>
	</form>

### Inverting Validation with NOT

To indicate that an element should be valid when a rule does NOT pass, precede the rule name with a !.

For example, this element will be valid if the value is NOT an integer.

	<input type="text" id="field" data-validate="!integer" value="3.141"/>

You can do interesting things with this. For example, to prevent people from putting an email address into a username field, you could explicitly put a NOT email rule on it:

	<input type="text" id="username" data-validate="!email" value=""/>


### Rules with Parameters

Some require parameters. For example, the "length" rule takes either one or two parameters. Parameters are expressed in square brackets, separated by a colon.

For example, the field "myfield" below must be between 6 and 16 characters.

	<form id="myform">
	<input id="myfield" type="text" value="123" data-validate="length[6:16]"/>
	<input type="submit" value="Save"/>
	</form>

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

It's that easy!

Here's one that's a little more complex, showing how the wording of the error message is altered by the "not" inversion:

	function maximum(elem,not,max){
		var val = parseFloat($(elem).val());
		if (val > max){
			return not?{'valid':true}:{'valid':false,'message':'this must be less than '+max};
		}
		return not?{'valid':false,'message':'this must not be less than '+max}:{'valid':true};
	},



## Chloroform Architecture

The plugin defines an array of elements. Each element has two data elements: rules and arguments.
For example, the plugin <i>elements</i> array might contain: [field1,field2,field3]. Each element in the array is a jQuery object, as would be returned from a jQuery selector like $('#field1').

### element.data('rules')

element.data('rules') is an object - used like a named associative array - of functions. Each function is named, and doesn't matter if it's one of Cholroform's built-in preset functions, or a custom one.
When expressed as JSON, the data('rules') object will look like this:

	{
		'notafive':function(){
			var elem = arguments[0];
			var val = parseFloat($(elem).val());
			if (val != 5){
				return {'valid':false,'message':'this is not a five'};
			}
			return {'valid':true};
		},
		'max':function()
			var elem = arguments[0];
			var val = parseFloat($(elem).val());
			var max = parseFloat(arguments[1]);
			if (val > max){
				return {'valid':false,'message':'this must be less than '+max};
			}
			return {'valid':true};
		}
	}




All rules accept <i>element</i> as their first argument. Some accept more additional arguments. The return value of a rule function is strict, as will be explained below.

### element.data('arguments')

element.data('arguments') is an object - again, used like an associative array - of argument arrays. Rules that require extra arguments store those arguments here.
The data('arguments') object is used to hold the arguments for those rule functions that accept them. For example, one of your rules might be 'length', which accepts the arguments (min,max). If your element validates when the length is between 5 and 16, then the value of data('arguments') will be:

	{
	'length':[5,16]
	}

By manipulating the data('arguments') object, you can change the parameters of a validation rule without having to change the rule itself. Arguments are populated automatically when you add arguments in square brackets in the data-validate attribute, eg: "length[5:16]"
When manipulating the arguments object, it is important to remember that every array in arguments must have a function of the same name in rules. The array of values must correspond - in the correct order - to the arguments expected by the corresponding rule function.

## How it all fits together

When Chloroform is initialied, all these data objects are quickly assembled based on attributes defined in the HTML. When it's done, you'll end up with form fields that <em>know how to validate themselves</em>, and some handy public methods for triggering the validation.

For example, imagine a very simple form with one field and a submit button.

	<form id="myform">
	<input id="myfield" type="text" value="123" data-validate="required,length[6:16]"/>
	<input type="submit" value="Save"/>
	</form>

In the &lt;head&gt; of this page, you'll add this code:

	jQuery(document).ready(function($) {
	$('#myform').chloroform();
	});

With no great effort, Chloroform has recognized that <em>myfield</em> has a validation rule, so it has been added to the form's array of special fields that require validation. In this case it has only one member: [myfield]

At the same time, the element <em>myfield</em> has been given two data objects. They are:

	rules:{
		'required':function(element){… blah blah blah …}
		'length':function(element,min,max){… blahb blah blah …}
	},
	arguments:{
		'length':[6,16]
	}

Because <em>myform</em> is a form element (ie not a div or a table), Chloroform also knows to bind the validate() method to its submit() event. When the form is submitted, each of the elements in its <em>elements</em> array are asked, one at a time, to execute their collection of rules and return the result: pass or fail? If any of the rules fail, a message bubble is popped up and the element returns false - a failure. When the form receives a failure response from an element, the form submission is aborted.

	<script>
		jQuery(document).ready(function($) {
			$('#myform').chloroform({
				'onAfterValidateAll':function(valid){
					if (valid){
						alert('your form has submitted (but not really)')
						return false;
					}
					return valid;
				}
			});
		});
	</script>
	<h3>Try it</h3>
	<form id="myform">
	<input type="text" data-validate="required,length[6:16]"/>
	<input type="submit" value="Save"/>
	</form>


## Validation on Dynamic and Complicated Forms

Chloroform's flexibility is its power. Because of its simple structure, public methods, and easy manipulation, you can achieve interactive effects that are not possible with most client-side validation plugins. Chloroform is excellent for dynamic forms, forms that include complex UI components, or even validation on HTML elements that aren't in a &lt;form&gt; at all. Manipulating the validation dynamically is as easy as adding and removing functions to the 'rules' array.

### Public Methods

One of the design decisions that makes Chloroform amazing is that all its internal functions are exposed as public methods. It's a double-edged sword; it means you can reach in and use those functions to customize your form's behaviour in weird and interesting ways (that's good), though it also means that you can do weird and interesting things that <em>don't work</em>, or that make the whole plugin implode with errors (that's bad).
Don't let the risk impede your creativity.

<table>
<tr>
<th>method name</th>
<th>arguments</th>
<th>returns</th>
<th>description</th>
<th>example</th>
</tr>

<!-- validate -->
<tr>
<td><h4>validate(element)</h4></td>
<td>
<dt>element</dt>
<dd>the element to be validated</dd>
</td>
<td>true or false, indicating if all the rules passed</td>
<td><p>validates the rules on a single element</p></td>
<td>
<code>
var isvalid = $.fn.chloroform('validate',$('#efield1'));
</code>
<a href="example-validate.html">example</a>
</td>
</tr>

<!-- validateAll -->
<tr>
<td><h4>validateAll()</h4></td>
<td>none</td>
<td>true or false, indicating if the entire form validated</td>
<td><p>This function essentially executes validate() on each registered element, and returns true/false if they all passed. This function is normally triggered directly by a submit event.</p></td>
<td>
<code>
var isvalid = $.fn.chloroform('validateAll',$('#myform'));

var isvalid = $('#myform').chloroform('validateAll');

</code>
</td>
</tr>

<!-- register -->
<tr>
<td><h4>register(element)</h4></td>
<td>
<dt>element</dt>
<dd>the element to be added. Registering an element puts it in the list of elements to be checked when validateAll is called. Adding a data-validate attribute to an element does this automatically when the validator is initialized.</dd>
</td>
<td>true</td>
<td><p>Adds an element to the validator. Use this to hook up dynamically generated form elements.</p></td>
<td></td>
</tr>

<!-- unregister -->
<tr>
<td><h4>unregister(element)</h4></td>
<td>
<dt>element</dt>
<dd>the element to be removed from the list of elements being validated.</dd>
</td>
<td>true</td>
<td><p>Removes an element from the validator</p></td>
<td></td>
</tr>

<!-- addrule -->
<tr>
<td><h4>addrule(element,rule)</h4></td>
<td>
<dt>element</dt>
<dd>the element to which this rule will be added</dt>

<dt>rule</dt>
<dd>
either:
<ul>
<li>an object with one child node, whose name is the custom rule name and whose value is a function to be called when validating this element. <a href='#customrules'>See examples</a>.</li>
<li>a string representation of preset named rules, eg "required,length[5]"</li>
</ul>
</dd>
</td>
<td>true, if the argument is OK. false if the argument is bad.</td>
<td><p>Adds a rule to an element</p></td>
<td>
<code>
$.fn.chloroform(
'addrule',
$('#efield1'),
{
'isfoo':function(){
var elem = arguments[0];
if ($(elem).val() != 'foo'){
return {'valid':false,'message':'this field is not foo'};
}
return {'valid':true};
}
}
);
</code>	

</td>
</tr>

<!-- removerule -->
<tr>
<td><h4>removerule(element,rulename)</h4></td>
<td>element: the element being altered. rulename: the name of the rule being removed.</td>
<td>true</td>
<td><p>Removes a named rule from the element.</p></td>
<td></td>
</tr>

<!-- showbubble -->
<tr>
<td><h4>showbubble(element,string)</h4></td>
<td>
<dt>element</dt>
<dd>the element that the bubble will point to. string: the message to be shown in the bubble.</dd>
</td>
<td>true</td>
<td><p>shows and points the popup bubble at the specified element, with the message in it. This method is called by validate() when a rule fails. Note that since this is a public method, you can point it at any element, with any message.</p></td>
<td></td>
</tr>

<!-- hidebubble -->
<tr>
<td><h4>hidebubble()</h4></td>
<td>none</td>
<td><p>hides the popup bubble.</p></td>
<td></td>
</tr>
</table>
</section>


<h2>Callback functions</h2>	
<section>
<table>
<tr>
<th>function name</th>
<th>arguments</th>
<th>return</th>
<th>usage</th>
</tr>
<tr>
<td>onBeforeValidateAll()</td>
<td>none</td>
<td>boolean</td>
<td>
if onBeforeValidateAll returns false, the validation is aborted and validateAll() returns false, and the submit event that
triggered the validation is aborted. if onBeforeValidateAll returns true, then validation proceeds normally. Use this to prevent the validation from continuing.
</td>
</tr>
<tr>
<td>onAfterValidateAll(bool)</td>
<td>boolean</td>
<td>boolean</td>
<td>Has one argument, indicating if all the validation rules passed. If onAfterValidateAll returns true, then submission proceeds normally. If onAfterValidateAll returns false, then submission is stopped. Use this callback to hook in additional behaviour to the form, for example to control the disabled state of the submit button if the form isn't filled out correctly.</td>
</tr>
</table>
</section>



<a name="customrules"></a><h2>Custom Rules</h2>

when you use the name of a custom rule in the data-validate attribute of an element, or pass in a function as the argument of addrule(), the function must behave in a specific way.
