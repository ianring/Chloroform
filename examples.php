<?php

$demos=array(
	'Validators' => array(
		'example1' => 'all built-in validators in one big form',
		'example2' => 'two forms on the same page, validated separately',
		'example3' => 'validating dynamic form elements',
		'example4' => 'custom rules',
		'example5'	=> 'changing arguments dynamically'
	),
	'Callback Functions' => array(
		'example3'=>'validating dynamic form elements',
		'example4' => 'custom rules',
		'example5' => 'changing arguments dynamically'
	),
	'Callback Functions' => array(
		'example6' => 'onBeforeValidateAll() callback',
		'example7' => 'onAfterValidateAll() callback',
		'example8' => 'onBeforeValidate() callback',
		'example9' => 'onAfterValidate() callback'
	),
	'Styling' => array(
		'example10' => 'using a different theme',
		'example11' => 'using multiple themes on the same page'
	),
	'Public Methods' => array(
		'example12' => 'validate()',
		'example13' => 'validateAll()',
		'example14' => 'register() and unregister()',
		'example15' => 'addrule() and removerule()',
		'example16' => 'applyrule() and revokerule()'
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
		
			<ul id="example-nav" class="nav nav-pills nav-stacked">
				
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
		<div class="span8 offset1">
		
			<h2>Examples</h2>
			
			<div class="examples">
			
				<?php
				
				foreach($demos as $groupname=>$group){
					foreach($group as $key=>$name){
					
						echo '<div class="example hide" id="'.$key.'">';
						
						$tabs = array();
						$panels = array();
						
						if (file_exists('examples/'.$key.".html.inc")){
							$tabs[] = '<li><a href="#example'.$key.'" data-toggle="tab">Example</a></li>';
							$tabs[] = '<li><a href="#html'.$key.'" data-toggle="tab">HTML</a></li>';
							
							$panel = '';
							$panel .= '<div class="tab-pane active" id="example'.$key.'">';
							$panel .= file_get_contents('examples/'.$key.".html.inc");
							$panel .= '</div>';
							$panels[] = $panel;
							
							$panel = '';
							$panel .= '<div class="tab-pane" id="html'.$key.'">';
							$panel .= '<pre class="prettyprint linenums">';
							$panel .= htmlspecialchars(file_get_contents('examples/'.$key.".html.inc"));
							$panel .= '</pre>';
							$panel .= '</div>';
							$panels[] = $panel;
						}
						if (file_exists('examples/'.$key.".js.inc")){
							$tabs[] = '<li><a href="#js'.$key.'" data-toggle="tab">JavaScript</a></li>';
							$panel = '';
							$panel .= '<div class="tab-pane" id="js'.$key.'">';
							$panel .= '<pre class="prettyprint linenums">';
							$panel .= htmlspecialchars(file_get_contents('examples/'.$key.".js.inc"));
							$panel .= '</pre>';
							$panel .= '</div>';
							$panels[] = $panel;
						}
						if (file_exists('examples/'.$key.".css.inc")){
							$tabs[] = '<li><a href="#css'.$key.'" data-toggle="tab">CSS</a></li>';
							$panel = '';
							$panel .= '<div class="tab-pane" id="css'.$key.'">';
							$panel .= '<pre class="prettyprint linenums">';
							$panel .= htmlspecialchars(file_get_contents('examples/'.$key.".css.inc"));
							$panel .= '</pre>';
							$panel .= '</div>';
							$panels[] = $panel;
						}
						
						echo '<ul class="nav nav-tabs">';
						echo implode('',$tabs);
						echo '</ul>		';
				
						echo '<div class="tab-content">';
						echo implode('',$panels);
						echo '</div>';
			
						
						echo '</div>';
					
					
					}
				}
				
				?>
				
				
			</div>
		</div>
	</div>
</div>
</body>
</html>
