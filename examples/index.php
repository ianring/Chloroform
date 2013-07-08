<?php

$demos=array(
	'Validators' => array(
		'example1' => 'built-in validators',
		'example2' => 'two forms on the same page',
		'example3' => 'dynamic form elements',
		'example4' => 'custom rules',
		'example5'	=> 'dynamic arguments',
		'example17' => 'aligning feedback with surrogate element',
		'example18' => 'attaching to other events',
		'example19' => 'validating a UI component'
	),
	'Callback Functions' => array(
		'example6' => 'onBeforeValidateAll',
		'example7' => 'onAfterValidateAll',
		'example8' => 'onBeforeValidate',
		'example9' => 'onAfterValidate'
	),
	'Styling and i18n' => array(
		'example10' => 'using a different theme',
		'example11' => 'using multiple themes on the same page',
		'example20' => 'multiple languages',
		'example21' => 'multiple languages in a custom rule'
	),
	'Showing off Public Methods' => array(
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
<script src="../assets/jquery-1.7.0-min.js"></script>
<script src="../bootstrap/js/bootstrap.js"></script>
<script src="../chloroform/i18n/en.js"></script>
<script src="../chloroform/i18n/fr.js"></script>
<script src="../chloroform/chloroform.js"></script>
<script src="../assets/run_prettify.js"></script>

<script src="../assets/jquery-ui.js"></script>
<script src="../assets/script.js"></script>

<link rel="stylesheet" href="../assets/prettify.css" />
<link rel="stylesheet" href="../bootstrap/css/bootstrap.css" />
<link rel="stylesheet" href="../chloroform/themes/blackbubble/blackbubble.css" />
<link rel="stylesheet" href="../chloroform/themes/earthygreen/earthygreen.css" />
<link rel="stylesheet" href="../chloroform/themes/mocha/mocha.css" />
<link rel="stylesheet" href="../chloroform/themes/cutesy/cutesy.css" />
<link rel="stylesheet" href="../assets/style.css" />

<link rel="stylesheet" href="../assets/jquery-ui.css" />

</head>
<body>

<?php include("../assets/navbar.php"); ?>

<div class="container" id="home">
	<div class="row">
		<div class="span3">
		
			<ul id="example-nav" class="nav nav-pills nav-stacked">
				
				<?php
				$first = true;
				foreach($demos as $groupname=>$group){
					echo '<li class="nav-header">' . $groupname . '</li>';
					foreach($group as $key=>$name){
						echo '<li class="'.($first?'active':'').'"><a href="#panel-'.$key.'">'.$name.'</a></li>';
						$first = false;
					}
				}
				?>
			</ul>
		
		
		</div>
		<div class="span8 offset1">
		
			<h2>Examples</h2>
			
			<div class="examples">
			
				<?php
				$first = true;
				foreach($demos as $groupname=>$group){
					
					foreach($group as $key=>$name){
					
						echo '<div class="example '.($first?'':'hide').'" id="panel-'.$key.'">';
						
						echo '<h3>'.$name.'</h3>';
						
						$tabs = array();
						$panels = array();
						$js = array();
						$css = array();
						
						if (file_exists($key.".html.inc")){
							$tabs[] = '<li class="active"><a href="#example'.$key.'" data-toggle="tab" >Example</a></li>';
							$tabs[] = '<li><a href="#html'.$key.'" data-toggle="tab">HTML</a></li>';
							
							$panel = '';
							$panel .= '<div class="tab-pane active panel-example" id="example'.$key.'">';
							$panel .= file_get_contents($key.".html.inc");
							$panel .= '</div>';
							$panels[] = $panel;
							
							$panel = '';
							$panel .= '<div class="tab-pane panel-html" id="html'.$key.'">';
							$panel .= '<pre class="prettyprint linenums">';
							$panel .= htmlspecialchars(file_get_contents($key.".html.inc"));
							$panel .= '</pre>';
							$panel .= '</div>';
							$panels[] = $panel;
						}
						if (file_exists($key.".js.inc")){
							$tabs[] = '<li><a href="#js'.$key.'" data-toggle="tab">JavaScript</a></li>';
							$panel = '';
							$panel .= '<div class="tab-pane panel-javascript" id="js'.$key.'">';
							$panel .= '<pre class="prettyprint linenums">';
							$j = file_get_contents($key.".js.inc");
							$panel .= htmlspecialchars($j);
							$panel .= '</pre>';
							$panel .= '</div>';
							$panels[] = $panel;
							$js[] = $j;
						}
						if (file_exists($key.".css.inc")){
							$tabs[] = '<li><a href="#css'.$key.'" data-toggle="tab">CSS</a></li>';
							$panel = '';
							$panel .= '<div class="tab-pane panel-css" id="css'.$key.'">';
							$panel .= '<pre class="prettyprint linenums">';
							$c = file_get_contents($key.".css.inc");
							$panel .= htmlspecialchars($c);
							$panel .= '</pre>';
							$panel .= '</div>';
							$panels[] = $panel;
							$css[] = $c;
						}
						
						echo '<ul class="nav nav-tabs">';
						echo implode('',$tabs);
						echo '</ul>		';
						
						echo '<div class="tab-content">';
						echo implode('',$panels);
						echo '</div>';
						
						echo '</div>';

						echo '<script>';
						echo implode('', $js);
						echo '</script>';
						
						echo '<style>';
						echo implode('', $css);
						echo '</style>';
						
						$first = false;
					}
				}
				
				?>
				
				
			</div>
		</div>
	</div>
</div>
</body>
</html>
