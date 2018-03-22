<?php 
	session_start();
	require 'connect.php';
	function loadLayout($title, $active = 'Home'){
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $title; ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	
	<link rel = 'stylesheet' media="screen" href = 'bs/css/bootstrap.css'>

	<script src="bs/js/jquery.js"></script>
	<script defer src="bs/js/fontawesome-all.js"></script>
	<script type="text/javascript" src = 'bs/js/bootstrap.js'></script>		
	<script type="text/javascript" src = 'bs/js/notify.js'></script>		
</head>
<body>
	<header>
		<nav class="navbar navbar-default" role="navigation">
			<div class="container-fluid">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="link" href="index.php"><img src="Images/home.jpg" class = 'home_img'></a>
				</div>
		
				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse navbar-ex1-collapse">					
					<ul class="nav navbar-nav navbar-right">
						<?php 
							if ($active == 'Home') {
								echo '<li class="active"><a href="#">Home</a></li>'; 
								echo '<li><a href="#">About Us</a></li>'; 
								echo '<li><a href="#">Contact Us</a></li>'; 
							}
							else if($active == 'About'){
								echo '<li><a href="#">Home</a></li>'; 
								echo '<li class="active"><a href="#">About Us</a></li>';
								echo '<li><a href="#">Contact Us</a></li>';
							}
							else{
								echo '<li><a href="#">Home</a></li>'; 
								echo '<li><a href="#">About Us</a></li>';
								echo '<li class="active"><a href="#">Contact Us</a></li>';	
							}
						?>
						
						
					</ul>
				
					
				</div><!-- /.navbar-collapse -->
			</div>
		</nav>		
	</header>

<?
	}

	function checkSession(){
		if (isset($_SESSION['user_code'])) {
			return true;
		}
		else{
			return false;
		}
	}
?>