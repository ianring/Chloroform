<?php

$demos=array(
	'Validators' => array(
		'1' => 'all built-in validators in one big form',
		'2' => 'two forms on the same page, validated separately',
		'3' => 'validating dynamic form elements',
		'4' => 'custom rules',
		'5'	=> 'changing arguments dynamically'
	),
	'Callback Functions' => array(
		'3'=>'validating dynamic form elements',
		'4' => 'custom rules',
		'5' => 'changing arguments dynamically'
	),
	'Callback Functions' => array(
		'6' => 'onBeforeValidateAll() callback',
		'7' => 'onAfterValidateAll() callback',
		'8' => 'onBeforeValidate() callback',
		'9' => 'onAfterValidate() callback'
	),
	'Styling' => array(
		'10' => 'using a different theme',
		'11' => 'using multiple themes on the same page'
	),
	'Public Methods' => array(
		'12' => 'validate()',
		'13' => 'validateAll()',
		'14' => 'register() and unregister()',
		'15' => 'addrule() and removerule()',
		'16' => 'applyrule() and revokerule()'
	)
);

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
		<div class="span3">
		
			<ul class="nav nav-list">
				
				<?php
				foreach($demos as $groupname=>$group){
					echo '<li class="nav-header">' . $groupname . '</li>';
					foreach($group as $key=>$name){
						echo '<li><a href="#'.$key.'">'.$name.'</a></li>';
					}
				}
				?>
			</ul>		
		
		
		</div>
		<div class="span9">
		
			<h2>Examples</h2>
			


		
			<h2>all-in-one example</h2>

		
		</div>
	</div>
</div>
</body>
</html>
