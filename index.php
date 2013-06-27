<?php

$example1 = <<<EOM
<form id="formexample1" class="example">
	<fieldset>
		<label>Put a Number in here</label>
		<input type="text" placeholder="must be a number" data-validate="numeric,required" class="input-xlarge" />
		<label class="checkbox">
			<input type="checkbox" data-validate="required"> Check this
		</label>
		<button type="submit" class="btn btn-primary">Submit</button>
	</fieldset>
</form>	
EOM;
$js1 = <<<EOM
$('#formexample1').chloroform();
EOM;



$example2 = <<<EOM
<form id="formexample2" class="example">
	<fieldset>
		<label>Enter a prime number less than 300</label>
		<input type="text" placeholder="prime number less than 300" data-validate="isprimeunder300" class="input-xlarge" />
		<button type="submit" class="btn btn-primary">Submit</button>
	</fieldset>
</form>	
EOM;
$js2 = <<<EOM
$('#formexample2').chloroform();
function isprimeunder300(elem,not) {
	var n = elem.val();
	if (isNaN(n)){
		return {'valid':false,'message':'that isn\'t a number'};
	}
	if (n>=300){
		return {'valid':false,'message':'that number is higher than 300'};
	}
	var m=Math.sqrt(n);
	for (var i=2;i&lt;=m;i++){
		if (n%i==0) {
			return {'valid':false,'message':'that number is not prime'}
		}
	} 
	return {'valid':true};
}
EOM;



$example3 = <<<EOM
<form id="formexample3" class="example">
	<fieldset>
		<div class="row">
			<div class="span4">
				<label>Pick a date for the party</label>
				
				<input type="hidden" id="datepickerinput" data-surrogate-element="#datepicker" data-validate="amibusythatday" />
				<div id="datepicker"></div>
			</div>
			<div class="span3">

				<p>
				<label for="giftamount">Gift budget:</label>
				<input type="text" id="giftamount" data-validate="min(100);max(150)" style="border: 0; color: #f6931f; font-weight: bold;" />
				</p>
				<div id="slider-range-min"></div>

			</div>
		</div>
		<button type="submit" class="btn btn-primary">Submit</button>
	</fieldset>
</form>	
EOM;
$js3 = <<<EOM
$('#formexample3').chloroform();

$( "#datepicker" ).datepicker({
	'onSelect':function(date){
		$('#datepickerinput').val(date);
	}
});

$( "#slider-range-min" ).slider({
	range: "min",
	value: 37,
	min: 1,
	max: 700,
	slide: function( event, ui ) {
		$( "#giftamount" ).val( ui.value );
	}
});
$( "#giftamount" ).val( $( "#slider-range-min" ).slider( "value" ) );
EOM;





?><!DOCTYPE html>
<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
<script src="bootstrap/js/bootstrap.js"></script>
<script src="chloroform/chloroform.js"></script>
<script src="https://google-code-prettify.googlecode.com/svn/loader/run_prettify.js"></script>

<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script src="assets/script.js"></script>

<style>
li.L0, li.L1, li.L2, li.L3, li.L5, li.L6, li.L7, li.L8 { 
	list-style-type: decimal !important 
}
</style>

<link rel="stylesheet" href="assets/prettify.css" />
<link rel="stylesheet" href="bootstrap/css/bootstrap.css" />
<link rel="stylesheet" href="chloroform/themes/blackbubble/blackbubble.css" />
<link rel="stylesheet" href="assets/style.css" />

<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />

</head>
<body>

<div class="navbar navbar-fixed-top">
	<div class="container">
		<div class="navbar-inner">
			<a class="brand" href="#">Chloroform</a>
			<ul class="nav">
				<li class="active"><a href="#home">Home</a></li>
				<li><a href="examples.php">Examples</a></li>
				<li><a href="documentation.php">Documentation</a></li>
			</ul>
		</div>
	</div>
</div>


<div class="container" id="home">
	<div class="row">
		<div class="span12 logo">
			
			<h1>Chloroform</h1>
			<img src="assets/chloroform_logo.png" />
			<p class="byline">form validation, done well</p>
			<a href="https://github.com/ianring/Chloroform/archive/master.zip" class="btn btn-info btn-large">Download Chloroform</a>
		</div>
	</div>
	
	<hr/>
	
	<div class="row">
		<div class="span4">
			<h3>Why?</h3>
			<p>Because other form validators are so damned annoying. When they work, they're not flexible. When they're easy to install, they're buggy. When they're flexible, they're complicated.</p>
			<p>Chloroform exists because the world needs a really, really good client-side form validation tool.</p>
		</div>
		<div class="span4">
			<h3>It's easy</h3>
			<p>Start with the simplest example. You'll have form validation in seconds. Then dig in, and you'll see there's a lot you can do with this little power tool.</p>
		</div>
		<div class="span4">
			<h3>You can help</h3>
			<p>Chloroform is open-source. If you can make it better, fork it and branch it and send a git pull request.</p>
			<p><a href="https://github.com/ianring/Chloroform" class='btn btn-block btn-info btn-superchunky'><i class='icon-github'></i>View on GitHub</a></p>
			
		</div>
	</div>

	<div class="row">
		<hr/>
	</div>
</div>





<div class="container">
	<div class="row">
		<div class="span8">
		
			<ul class="nav nav-tabs">
				<li class="active"><a href="#example1" data-toggle="tab">Example</a></li>
				<li><a href="#html1" data-toggle="tab">HTML</a></li>
				<li><a href="#js1" data-toggle="tab">JavaScript</a></li>
			</ul>		
				
			<div class="tab-content">
				<div class="tab-pane active" id="example1">
					<?php echo $example1; ?>
				</div>
				<div class="tab-pane" id="html1">
<pre class="prettyprint linenums">
<?php echo htmlspecialchars($example1);?>
</pre>
				</div>
				<div class="tab-pane" id="js1">
<pre class="prettyprint linenums">
<?php echo htmlspecialchars($js1);?>
</pre>
				</div>
			</div>
		
		</div>
		<div class="span4">
			<h3>The Basics</h3>
			<p>The simplest use of Chloroform is to make sure that checkboxes are checked, text fields aren't empty, and fields don't contain bad values.</p>
		</div>
	</div>
	
	
	
	<div class="row">
		<hr/>
	</div>
	
	
	
	<div class="row">
		<div class="span4">
			<h3>Flexible Rules</h3>
			<p>Chloroform comes with a really good selection of built-in validation rules, and its simple, open structure makes adding your own custom rules an easy task.</p>
		</div>

		<div class="span8">
		
			<ul class="nav nav-tabs">
				<li class="active"><a href="#example2" data-toggle="tab">Example</a></li>
				<li><a href="#html2" data-toggle="tab">HTML</a></li>
				<li><a href="#js2" data-toggle="tab">JavaScript</a></li>
			</ul>		
				
			<div class="tab-content">
				<div class="tab-pane active" id="example2">
					<?php echo $example2; ?>
				</div>
				<div class="tab-pane" id="html2">
<pre class="prettyprint linenums">
<?php echo htmlspecialchars($example2);?>
</pre>
				</div>
				<div class="tab-pane" id="js2">
<pre class="prettyprint linenums">
<?php echo htmlspecialchars($js2);?>
</pre>
				</div>
			</div>

		</div>
	</div>
	
	
	<div class="row">
		<hr/>
	</div>
	
	
	<div class="row">
		<div class="span8">
		
			<ul class="nav nav-tabs">
				<li class="active"><a href="#example3" data-toggle="tab">Example</a></li>
				<li><a href="#html3" data-toggle="tab">HTML</a></li>
				<li><a href="#js3" data-toggle="tab">JavaScript</a></li>
			</ul>		

			<div class="tab-content">
				<div class="tab-pane active" id="example3">
					<?php echo $example3; ?>
				</div>
				<div class="tab-pane" id="html3">
<pre class="prettyprint linenums">
<?php echo htmlspecialchars($example3);?>
</pre>
				</div>
				<div class="tab-pane" id="js3">
<pre class="prettyprint linenums">
<?php echo htmlspecialchars($js3);?>
</pre>
				</div>
			</div>
		
		</div>
		
		<div class="span4">
			<h3>Chloroform works with everything.</h3>
			<p>You can put validation on anything. That's because Chloroform can bind validation to hidden elements, and feedback can be attached to any visible element.</p>
		</div>

	</div>

</div>

</body>
</html>