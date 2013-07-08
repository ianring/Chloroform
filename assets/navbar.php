<?php
$nav = array(
	"Home" => array("/","/index.php"),
	"Examples" => array("/examples/","/examples/index.php"),
	"Documentation" => array("/documentation/","/documentation/index.php")
);
?>
<div class="navbar navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container">
			<a class="brand" href="#">Chloroform</a>
			<ul class="nav">
			<?php
			
			foreach($nav as $k=>$v){
				echo '<li';
				if (is_array($v)){
					if (in_array($_SERVER['REQUEST_URI'],$v)){
						echo ' class="active"';
					}
				} else {
					if ($v == $_SERVER['REQUEST_URI']){
						echo ' class="active"';
					}
				}
				echo '><a href="';
				if (is_array($v)){
					$href = $v[0];
				} else {
					$href = $v;
				}
				echo $href;
				echo '">';
				echo $k;
				echo '</a></li>';
			}
			
			?>
			</ul>
		</div>
	</div>
</div>
