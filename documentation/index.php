<?php


?><!DOCTYPE html>
<html>
<head>
<script src="../assets/jquery-1.7.0-min.js"></script>
<script src="../bootstrap/js/bootstrap.js"></script>
<script src="../chloroform/chloroform.js"></script>
<script src="../assets/run_prettify.js"></script>

<script src="../assets/jquery-ui.js"></script>
<script src="../assets/script.js"></script>

<style>
</style>

<link rel="stylesheet" href="../assets/prettify.css" />
<link rel="stylesheet" href="../bootstrap/css/bootstrap.css" />
<link rel="stylesheet" href="../chloroform/themes/blackbubble/blackbubble.css" />
<link rel="stylesheet" href="../chloroform/themes/earthygreen/earthygreen.css" />
<link rel="stylesheet" href="../assets/style.css" />
<link rel="stylesheet" href="../assets/jquery-ui.css" />

</head>
<body>

<?php include("../assets/navbar.php"); ?>


<div class="container" id="home">
	<h1>Chloroform Documentation</h1>
	<div class="row">
		<div class="span3">
		
		<ul>
			<li><a href="#gettingstarted">Getting Started</a>
				<ul>
					<li><a href="#invertingarule">Inverting a Rule</a></li>
					<li><a href="#ruleswitharguments">Rules with arguments</a></li>
					<li><a href="#multiplerules">Multiple Rules</a></li>
				</ul>
			</li>
			
			<li><a href="#options">Options</a>
				<ul>
					<li><a href="#optionslang">lang</a></li>
					<li><a href="#optionstheme">theme</a></li>
					<li><a href="#optionsvalidatedataattr">validateDataAttr</a></li>
					<li><a href="#optionsscrolltobubble">scrollToBubble</a></li>
				</ul>
			</li>
			<li><a href="#publicmethods">Public Methods</a>
				<ul>
					<li><a href="#publicmethodsregister">register</a></li>
					<li><a href="#publicmethodsunregister">unregister</a></li>
					<li><a href="#publicmethodsvalidate">validate</a></li>
					<li><a href="#publicmethodsvalidateall">validateAll</a></li>
					<li><a href="#publicmethodssetoption">setOption</a></li>
					<li><a href="#publicmethodsshowbubble">showBubble</a></li>
					<li><a href="#publicmethodshidebubble">hideBubble</a></li>
				</ul>
			</li>
			<li><a href="#builtinrules">Built-in Rules</a></li>
			<li><a href="#customrules">Custom Rules</a>
				<ul>
					<li><a href="#customrulesauthoring">Authoring a Custom Validation Rule</a></li>
				</ul>
			</li>
			<li><a href="#callbacks">Callback Functions</a>
				<ul>
					<li><a href="#onbeforevalidateall">onBeforeValidateAll</a></li>
					<li><a href="#onaftervalidateall">onAfterValidateAll</a></li>
					<li><a href="#onbeforevalidate">onBeforeValidate</a></li>
					<li><a href="#onaftervalidate">onAfterValidate</a></li>
				</ul>
			</li>
			<li><a href="#datacollections">Data Collections</a>
				<ul>
					<li><a href="#dataoptions">form.data('options')</a></li>
					<li><a href="#dataelements">form.data('elements')</a></li>
					<li><a href="#datarules">input.data('rules')</a></li>
					<li><a href="#dataarguments">input.data('arguments')</a></li>
					<li><a href="#datanot">input.data('not')</a></li>
				</ul>
			</li>
			
			<li><a href="#i18n">Internationalization</a></li>
		</ul>
		
		
		</div>
		<div class="span8 offset1">
		
		<a name="gettingstarted"></a>
		<h2>Getting Started</h2>
			
			<p>Chloroform is for client-side validation of HTML forms. So, to start you'll need an HTML form. Here's one:</p>

<?php
$t = <<<EOM
<form id="myform">
	<input type="text" value="hello world" />
	<input type="submit" value="Submit" />
</form>
EOM;
?>
<pre class="prettyprint linenums">
<?php echo htmlspecialchars($t); ?>
</pre>

<p>Let's suppose that you want to enforce the rule that our text box must have something in it. Chloroform comes with a whole bunch of built-in rules, and one of them is named "required". It does what we want.</p>
<p>To add validation to our field, we simply add an HTML attribute named "data-validate", and give it our "required" rule:</p>

<?php
$t = <<<EOM
<form id="myform">
	<input type="text" value="hello world" data-validate="required" />
	<input type="submit" value="Submit" />
</form>
EOM;
?>
<pre class="prettyprint linenums">
<?php echo htmlspecialchars($t); ?>
</pre>
			
			<p>Right there in the HTML, we're saying what criteria we expect from the validation. But the HTML markup doesn't actually <em>do anything</em>. To enforce those validation rules, we need to tell Chloroform to change the behaviour of the form named "myform".</p>
			
			<p>Before going any further, we should load up prerequisite ingredients. Chloroform requires jQuery, so that library needs to be loaded first. Chloroform needs its core script, a theme CSS file, and at least one language file.</p>

<?php
$t = <<<EOM
<script src="jquery.min.js"></script>
<script src="/chloroform/chloroform.js"></script>
<script src="/chloroform/i18n/en.js"></script>
<link rel="stylesheet" href="/chloroform/themes/blackbubble/blackbubble.css" />
EOM;
?>
<pre class="prettyprint linenums">
<?php echo htmlspecialchars($t); ?>
</pre>
			
			<p>Now Chloroform is loaded, but it isn't doing anything. To get Chloroform to validate this form, execute this JavaScript code:</p>
			
<?php
$t = <<<EOM
<script>
$('#myform').chloroform();
</script>
EOM;
?>
<pre class="prettyprint linenums">
<?php echo htmlspecialchars($t); ?>
</pre>
			
			<p>... and it works!</p>
			
			<p>Here's what the entire example might look like in your HTML, all in one blurb.</p>

<?php
$t = <<<EOM
<script src="jquery.min.js"></script>
<script src="/chloroform/chloroform.js"></script>
<script src="/chloroform/i18n/en.js"></script>
<link rel="stylesheet" href="/chloroform/themes/blackbubble/blackbubble.css" />

<form id="myform">
	<input type="text" value="hello world" data-validate="required" />
	<input type="submit" value="Submit" />
</form>
<script>
$('#myform').chloroform();
</script>

EOM;
?>
<pre class="prettyprint linenums">
<?php echo htmlspecialchars($t); ?>
</pre>

		<p>There are <a href="/examples/">lots of other examples</a> you can look at, showing everything that Chloroform can do.</p>
		
		<hr/>


		<a name="invertingarule"></a>
		<h3>Inverting a rule</h3>
		<p>Sometimes you will want a field to be invalid if the field <em>does not</em> match a pattern. To invert a rule, precede its name with an exclamation mark, like this:</p>
<?php
$t = <<<EOM
<input type="text" value="hello world" data-validate="!email" />
EOM;
?>
<pre class="prettyprint linenums">
<?php echo htmlspecialchars($t); ?>
</pre>
		<p>In that example, the field will be considered valid if the value <em>does not</em> resemble an email address.</p>

		
		<a name="ruleswitharguments"></a>
		<h3>Rules with Arguments</h3>
		<p>Some rules require one or more parameters to describe how they ought to validate. For example, the rule named "length" takes either one or two arguments, defining the minimum and maximum length allowed in the field. Parameters - also called <em>arguments</em> - are defined by following the rule name with the arguments in parentheses, separated by commas. </p>
		<p>Here is a field which requires the content to be between 7 and 13 characters long.</p>
<?php
$t = <<<EOM
<input type="text" value="hello world" data-validate="length(7,13)" />
EOM;
?>
<pre class="prettyprint linenums">
<?php echo htmlspecialchars($t); ?>
</pre>


		<a name="multiplerules"></a>
		<h3>Multiple rules</h3>
		
		<p>To apply more than one rule to a form field, use multiple rule names, and separate them by a semicolon.</p>
		<p>This example shows a field which must not be empty, and which must be formatted like an email address.</p>
<?php
$t = <<<EOM
<input type="text" value="hello world" data-validate="required;email" />
EOM;
?>
<pre class="prettyprint linenums">
<?php echo htmlspecialchars($t); ?>
</pre>

		<p>Chloroform has a limitation that you may only have one instance of the same rule name on an element. The rule name is used as a key in all the element's data() collections, so if you have more than one with the same name, they'll overwrite each other.</p>
		<p>The logical operator on multiple rules is "AND", not "OR". The field must satisfy all the rules in order to be valid.</p>
		<p>If you have a situation where you need more sophisticated logic, a Custom rule is your best strategy.</p>


		<hr/>
			
			
			<p>Chloroform is easy to use in this simple case, but it's also an incredibly versatile and powerful tool. With a little programming skill, you can extend the functionality of Chloroform to change themes, language, create custom rules, and more.</p>
			
			<p><br/></p>
			<p class="text-center"><a href="/examples/">You'll learn a lot by looking at all the live examples</a></p>
		
		<hr/>
		
		<a name="options"></a>
		<h2>Options</h2>
			
			<p>Chloroform can be instantiated with options that change its behaviour and appearance. Here are all the defaults:</p>
			
<?php
$t = <<<EOM
$('#myform').chloroform({
	'theme':'blackbubble',
	'lang':'en',
	'validateDataAttr':'data-validate',
	'scrollToBubble':true
});
EOM;
?>
<pre class="prettyprint linenums">
<?php echo htmlspecialchars($t); ?>
</pre>
			
			<a name="optionslang"></a>
			<dt>lang</dt>
			<dd>for translated phrases, <tt>lang</tt> is the language code. For example, "en" for English, "fr" for French, "es" for Spanish.</dd>
			
			<a name="optionstheme"></a>
			<dt>theme</dt>
			<dd><tt>theme</tt> is used to theme your feedback bubble to suit your site's look &amp; feel. The theme is a class name applied to the bubble's container element, which alters its appearance according to rules defined in a CSS stylesheet.</dd>
			
			<a name="optionsvalidatedataattr"></a>
			<dt>validateDataAttr</dt>
			<dd>the default attribute name for validation is <tt>data-validate</tt>. If that name is being used for something else, you can choose a different one so Chloroform doesn't interfere.</dd>
			
			<a name="optionsscrolltobubble"></a>
			<dt>scrollToBubble</dt>
			<dd>boolean. <tt>true</tt> means the feedback bubble will be scrolled to the middle of the screen when a validation error is triggered.</dd>
			
			<p>Options are defined when Chloroform is instantiated. To change an option of an existing Chloroform instance, use the public method <tt>setOption</tt></p>
			
		<hr/>
		<a name="publicmethods"></a>
		<h2>Public Methods</h2>
			
			<p>Chloroform has a number of public methods exposed that you can use to control your form. To be consistent with other jQuery plugins, Chloroform uses this syntax to call public methods:</p>
			
<?php
$t = <<<EOM
$('#myform').chloroform('method_name', argument1, argument2, argument3...);
EOM;
?>
<pre>
<?php echo htmlspecialchars($t); ?>
</pre>
			<p>For example, here's how you call the "setOption" method, to change Chloroform's language to Spanish:</p>

<?php
$t = <<<EOM
// setOption takes two arguments
$('#myform').chloroform('setOption','lang','es');
EOM;
?>
<pre class="prettyprint linenums">
<?php echo htmlspecialchars($t); ?>
</pre>

			<p>Another common use of a public method is calling the "hideBubble" method, to force the feedback bubble to go away:</p>

<?php
$t = <<<EOM
// hideBubble has no arguments
$('#myform').chloroform('hideBubble');
EOM;
?>
<pre class="prettyprint linenums">
<?php echo htmlspecialchars($t); ?>
</pre>

			<p>Below are all Chloroform's public methods.</p>

			
			<a name="publicmethodsregister"></a>
			<h3>register</h3>
			<div class="methodsynopsis">
				<span class="type">bool</span>
				<span class="funcname">register</span>
				(
				<span class="methodparam">
					<span class="type">jQuery</span>
					<span class="parameter">$element</span>
				</span>
				)
			</div>
			<p>this method adds an element to the form.data('elements') collection, so it will be checked during validateAll()</p>
			
			<a name="publicmethodsunregister"></a>
			<h3>unregister</h3>
			<div class="methodsynopsis">
				<span class="type">bool</span>
				<span class="funcname">unregister</span>
				(
				<span class="methodparam">
					<span class="type">jQuery</span>
					<span class="parameter">$element</span>
				</span>
				)
			</div>
			<p>This method removes an element from the form.data('elements') collection, so it will not be checked during validateAll()</p>
			
			<a name="publicmethodsvalidate"></a>
			<h3>validate</h3>
			<div class="methodsynopsis">
				<span class="type">bool</span>
				<span class="funcname">validate</span>
				(
				<span class="methodparam">
					<span class="type">jQuery</span>
					<span class="parameter">$element</span>
				</span>
				)
			</div>
			<p>This method iterates the data('rules') collection, executing each until they have all returned a true result, or until one of them returns a false result. </p>
			
			<a name="publicmethodsvalidateall"></a>
			<h3>validateAll</h3>
			<div class="methodsynopsis">
				<span class="type">bool</span>
				<span class="funcname">validateAll</span>
				()
			</div>
			<p>This is the method that is invoked on a form submission. It iterates over the form.data('elements') collection, invoking validate() for each one. The validation aborts if any elements return a false result.
			
			<a name="publicmethodssetoption"></a>
			<h3>setOption</h3>
			<div class="methodsynopsis">
				<span class="type">bool</span>
				<span class="funcname">register</span>
				(
				<span class="methodparam">
					<span class="type">string</span>
					<span class="parameter">optionName</span>
				</span>
				,
				<span class="methodparam">
					<span class="type">mixed</span>
					<span class="parameter">value</span>
				</span>
				)
			</div>
			<p>The setoption method is used to change Chloroform options after instantiation.
			<p>As with all options, this method is invoked using chloroform() with the method name as the first parameter, and arguments as subsequent parameters.
<?php
$t = <<<EOM
$('#myform').chloroform({
	'theme':'blackbubble'
});

$('#mybutton').click(function(){
	$('#myform').chloroform('setOption','theme','earthygreen');
});
EOM;
?>
<pre class="prettyprint linenums">
<?php echo htmlspecialchars($t); ?>
</pre>
				


			<a name="publicmethodsshowbubble"></a>
			<h3>showBubble</h3>
			<div class="methodsynopsis">
				<span class="type">bool</span>
				<span class="funcname">showBubble</span>
				(
				<span class="methodparam">
					<span class="type">jQuery</span>
					<span class="parameter">$element</span>
				</span>
				,
				<span class="methodparam">
					<span class="type">string</span>
					<span class="parameter">message</span>
				</span>
				)
			</div>
			<p>This is the cheater's way of making a feedback bubble appear, pointing at an element. You can achieve interesting effects by using this to provide a "tooltip" which has the same style as your form validation feedback.</p>
<?php
$t = <<<EOM
$('#myform').chloroform();

$('#mybutton').click(function(){
	$('#myform').chloroform('showbubble',$('#agreecheckbox'),'don\'t forget to click on this');
});
EOM;
?>
<pre class="prettyprint linenums">
<?php echo htmlspecialchars($t); ?>
</pre>
			


			<a name="publicmethodshidebubble"></a>
			<h3>hideBubble</h3>
			<div class="methodsynopsis">
				<span class="type">bool</span>
				<span class="funcname">hideBubble</span>
				()
			</div>
			<p>This is a cheater method that lets you hide the feedback bubble. You'll want to trigger this method whenever something in the form or the page layout changes, or else you end up with a bubble floating on the page pointing at something inappropriate.</p>
		
		<hr/>
		
		
		
		<a name="builtinrules"></a>
		<h2>Built-in Rules</h2>
		
		<p>Chloroform comes with this excellent collection of built-in rules, to achieve the most common form validation tasks. They are:</p>

		<dt>required</dt>
		<dd>Validates if a text field is not empty, a checkbox is checked, a radio group has a selection, a select box has an option selected.</dd>
		
		<dt>equals(x)</dt>
		<dd>Validates if the value of the field is equal to the value in the argument</dd>
		
		<dt>sameas(element_id)</dt>
		<dd>Validates if the value of the field is the same as the value of another field, identified by its id attribute.</dd>
		
		<dt>alpha</dt>
		<dd>Validates if the field contains only alphabetic characters (a to z), space, underscore, and hyphen.</dd>
		
		<dt>numeric</dt>
		<dd>Validates if the field contains only numeric digits, decimal (dot), and negative symbol (hyphen).</dd>
		
		<dt>integer</dt>
		<dd>Validates if the field contains an integer.</dd>
		
		<dt>max(n)</dt>
		<dd>Validates if the value of the field is less than or equal to the value of the argument. Works with numeric or alphabetic comparison.</dd>
		
		<dt>min(n)</dt>
		<dd>Validates if the value of the field is greater than or equal to the value of the argument. Works with numeric or alphabetic comparison.</dd>
		
		<dt>between(min[,max])</dt>
		<dd>If one argument is provided, then it is treated as a "min". If two arguments are provided, the first is "min" and the second is "max". </dd>
		
		<dt>length(min[,max])</dt>
		<dd>Validates if the character length of the value is within the range defined by the arguments. If one argument is provided, it is "min". If two arguments are provided, they are "min" and "max".</dd>
		
		<dt>choices(min[,max])</dt>
		<dd>Used to enforce selection of options in a group of checkboxes, or options in a select box. Validates if the number of options selected is within the range defined by the arguments. If one argument is provided, it is "min". If two arguments are provided, they are "min" and "max".</dd>
		
		<dt>contains(x)</dt>
		<dd>Validates if the value of the field contains the string argument.</dd>
		
		<dt>regex(pattern)</dt>
		<dd>Validates of the values of the field matches the regular expression defined in the argument.</dd>
		
		<dt>email</dt>
		<dd>Validates if the field resembles the syntax of an email address.</dd>
		
		<dt>url</dt>
		<dd>Validates if the field resembles a web URL.</dd>
		
		<dt>ajax(url)</dt>
		<dd>Performs a synchronous AJAX request to the URL specified in the argument, and validates if the response evaluates as true. <b>warning: this feature is not currently functional... coming soon</b> </dd>
		
		
		<hr/>
		<a name="customrules"></a>
		<h2>Custom Rules</h2>
		
		<p>To use a custom function, simply define a JavaScript function in the global scope, then use that function name in the data-validate attribute of the form element.</p>
		
		<a href="/examples/#example4">See an example of a custom rule applied to a checkbox</a>
		
		<a name="customrulesauthoring"></a>
		<h3>Authoring a Custom Validation Rule</h3>

		<p>Chloroform rule functions have a specific set of arguments, and a strict format for its return value. The parameter definition for a Chloroform rule looks like this:</p>

				<div class="methodsynopsis">
					<span class="type">object</span>
					<span class="funcname">customRuleName</span>(
					<span class="methodparam">
						<span class="type">jQuery</span>
						<span class="parameter">$element</span>
					</span>,<span class="methodparam">
						<span class="type">boolean</span>
						<span class="parameter">not</span>
					</span>[,<span class="methodparam">
						<span class="type">mixed</span>
						<span class="parameter">argument1</span>
					</span>[,<span class="methodparam">
						<span class="type">mixed</span>
						<span class="parameter">argument2</span>
					</span>...]])
				</div>
		
		<p>Here's an example:</p>
		
<?php
$t = <<<EOM
function customrule1(element,not){
	\$val = element.val();
	
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
EOM;
?>
<pre class="prettyprint linenums">
<?php echo htmlspecialchars($t); ?>
</pre>


		<h4>Parameters</h4>
		<p>A Chloroform rule takes at least two, and maybe more, parameters.</p>

		<dt>element</dt>
		<dd>this is the element being validated, provided as a jQuery element object, so you can cal jQuery methods on it. Typically, you will get element.val(), but you could really do anything you want.</dd>

		<dt>not</dt>
		<dd>boolean. if this is true, then the validator is indicating that the rule should be inverted; if the rule would return valid, return invalid instead, and vice-versa.</dd>

		<h4>Return</h4>
		<p>If the element is valid, then the function must return:</p>
<pre>
{'valid':true}
</pre>
		<p>If the element is not valid, then the function must return:</p>
<pre>
{'valid':false,'message':<em>message_string</em>}
</pre>
		
		<p>The <em><tt>message_string</tt> can be:</em>
		<ul>
			<li>The actual string message to be displayed in the feedback bubble, <em>or</em></li>
			<li>A string key, corresponding to translated phrases that have already been added to the Chloroform.i18n collection</li>
		</ul>
		
		<hr/>
		
		<a name="callbacks"></a>
		<h2>Callback Functions</h2>
			
			<p>Chloroform provides callbacks that you can hook into during validation.
			
			<a name="onbeforevalidateall"></a>
			<dt><h3>onBeforeValidateAll</h3></dt>
			<dd>
				<div class="methodsynopsis">
					<span class="type">bool</span>
					<span class="funcname">onBeforeValidateAll</span>
					(
					<span class="methodparam">
						<span class="type">jQuery</span>
						<span class="parameter">$form</span>
					</span>
					)
				</div>
				<p>Triggered at the onset of validateAll().</p>
				<p>If this callback returns true, then the validateAll() proceeds. If the callback returns false, validateAll() will be canceled.</p>
			</dd>
			
			<a name="onaftervalidateall"></a>
			<dt><h3>onAfterValidateAll</h3></dt>
			<dd>
				<div class="methodsynopsis">
					<span class="type">bool</span>
					<span class="funcname">onAfterValidateAll</span>
					(
					<span class="methodparam">
						<span class="type">jQuery</span>
						<span class="parameter">$form</span>
					</span>
					,
					<span class="methodparam">
						<span class="type">boolean</span>
						<span class="parameter">isValid</span>
					</span>
					)
				</div>
				<p>Triggered at the end of validateAll().</p>
				<p>If this callback returns true, then the the form is submitted. If the callback returns false, form submission is canceled.</p>
			</dd>
			
			<a name="onbeforevalidate"></a>
			<dt><h3>onBeforeValidate</h3></dt>
			<dd>
				<div class="methodsynopsis">
					<span class="type">bool</span>
					<span class="funcname">onBeforeValidate</span>
					(
					<span class="methodparam">
						<span class="type">jQuery</span>
						<span class="parameter">$element</span>
					</span>
					)
				</div>
				<p>Triggered at the onset of validate().</p>
				<p>If this callback returns true, then the validate() proceeds. If the callback returns false, validate() returns false and does not proceed with testing the rules.</p>
			</dd>
			
			<a name="onaftervalidate"></a>
			<dt><h3>onAfterValidate</h3></dt>
			<dd>
				<div class="methodsynopsis">
					<span class="type">bool</span>
					<span class="funcname">onAfterValidate</span>
					(
					<span class="methodparam">
						<span class="type">jQuery</span>
						<span class="parameter">$element</span>
					</span>
					,
					<span class="methodparam">
						<span class="type">boolean</span>
						<span class="parameter">isValid</span>
					</span>
					)
				</div>
				<p>Triggered at the end of validate().</p>
				<p>If this function returns false, then validate() returns false. If onAfterValidate() returns true, then validate() proceeds normally. </p>
			</dd>
			
		<hr/>
		
		
		<a name="datacollections"></a>
		<h2>Data Collections</h2>
		<p>The data collections attached to elements are what makes Chloroform work. You should use Chloroform's public methods to manipulate them, but you could also access them directly with jQuery's data() function.</p>
		
		<img src="../assets/fig_data_collections.png" />
		<br/>
		<a name="dataoptions"></a>
		<dt><h4>form.data('options')</h4></dt>
		<dd>
			<p>This is the collection of options.</p>
<?php
$t = <<<EOM
{
	'theme':'blackbubble',
	'lang':'en',
	'validateDataAttr':'data-validate',
	'scrollToBubble':true
}
EOM;
?>
<pre>
<?php echo htmlspecialchars($t); ?>
</pre>
		</dd>



		<a name="dataelements"></a>
		<dt><h4>form.data('elements')</h4></dt>
		<dd>
			<p>This is the collection of elements that are registered with Chloroform to be validated by the public method validateAll().</p>
<?php
$t = <<<EOM
[ element, element, element, element ]
EOM;
?>
<pre>
<?php echo htmlspecialchars($t); ?>
</pre>
		</dd>
		



		<a name="datarules"></a>

		<dt><h4>input.data('rules')</h4></dt>
		<dd>
			<p>This is an array of named functions. These are the functions that will be executed by the public method validate(). The important thing to comprehend here is that these aren't just the names of the rules - they are the actual executable functions. You can do a dirty hack to add a new validation rule by writing an anonymous function directly into this object!</p>
<?php
$t = <<<EOM
{ 
	'rule1':function(){
		...
	},
	'rule2':function(){
		...
	},
	'rule3':function(){
		...
	}
}
EOM;
?>
<pre>
<?php echo htmlspecialchars($t); ?>
</pre>
		</dd>
		




		<a name="dataarguments"></a>

		<dt><h4>input.data('arguments')</h4></dt>
		<dd>
			<p>For each element in the rules collection, there will be one in this collection that contains the arguments that will be passed in to the rule function.</p>
<?php
$t = <<<EOM
{ 
	'rule1':[],
	'rule2':['/abc/'],
	'rule3':[1]
}
EOM;
?>
<pre>
<?php echo htmlspecialchars($t); ?>
</pre>
		</dd>
		
		
		
		

		<a name="datanot"></a>

		<dt><h4>input.data('not')</h4></dt>
		<dd>
			<p>For each element in the rules collection, there will be one in this collection that indicates whether the result of the rule is inverted, i.e. if a "NOT" operator should be applied to the result.</p>
<?php
$t = <<<EOM
{ 
	'rule1':false,
	'rule2':false,
	'rule3':true
}
EOM;
?>
<pre>
<?php echo htmlspecialchars($t); ?>
</pre>
		</dd>
				
		


		<hr/>
		<a name="i18n"></a>
		<h2>Internationalization</h2>
		
		<p>Chloroform has great internationalization support. All the phrases that are displayed as validation feedback are easily switched to another language, by changing the "lang" option.</p>
		<p>The language for Chloroform's built-in rules are available in separate lexicon files. The phrases are organized by language and by rule name, and are easy to overwrite or edit as you see fit. Making your own language file is easy - just make a copy of the English one ("i18n/en.js") and change it. You can easily add new phrase translations for custom rules by extending the <tt>Chloroform.i18n</tt> object.</p>
		<p>For example, here's how you would initialize Chloroform to use the Spanish feedback phrases:</p>
<?php
$t = <<<EOM
<script src="/chloroform/i18n/es.js"></script>
<script>
$('#myform').chloroform({
	'lang':'es'
});
</script>
EOM;
?>
<pre class="prettyprint linenums">
<?php echo htmlspecialchars($t); ?>
</pre>

		


		
		</div>
	</div>
</div>
</body>
</html>
