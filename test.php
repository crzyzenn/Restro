<!DOCTYPE html>
<html>
<head>
	<title>Mobile test</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Restaurant</title>
	<link rel = 'stylesheet' media="screen" href = 'bs/css/bootstrap.css'>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script type="text/javascript" src = 'bs/js/bootstrap.js'></script>		
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
					<a class="navbar-brand" href="#"><img src="Images/pizzahut-logo.png" class = 'home_img'></a>
				</div>
		
				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse navbar-ex1-collapse">
					<!-- <ul class="nav navbar-nav navbar-right">
						<li><a href="#">Link</a></li>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
							<ul class="dropdown-menu">
								<li><a href="#">Action</a></li>
								<li><a href="#">Another action</a></li>
								<li><a href="#">Something else here</a></li>
								<li><a href="#">Separated link</a></li>
							</ul>
						</li>
					</ul> -->
					<ul class="nav navbar-nav navbar-right">
						<li class="active"><a href="#">Home</a></li>
						<li><a href="#">About Us</a></li>
						<li><a href="#">Contact Us</a></li>
					</ul>
					<!-- <form class="navbar-form navbar-left" role="search">
						<div class="form-group">
							<input type="text" class="form-control" placeholder="Search">
						</div>
						<button type="submit" class="btn btn-default">Submit</button>
					</form> -->
					
				</div><!-- /.navbar-collapse -->
			</div>
		</nav>		
	</header>


	<!-- Form table number -->
	<div class="container-fluid">

		<?php 

			require 'vendor/autoload.php'; 	

			// Establish connection to the database
			require_once 'vendor/autoload.php'; 
			use GraphAware\Neo4j\Client\ClientBuilder;
			$client = ClientBuilder::create()
				    ->addConnection('bolt', 'bolt://neo4j:root@localhost:7687')
				    ->build();
			// Front end check table id
			if (isset($_POST['table_id'])) {
				 $query = "MATCH (n:TABLE) WHERE n.id = ".$_POST['table_id']." RETURN n.id";
				 $results = $client->run($query); 
				 if(empty($results->getRecords())){
				 	?>
				 	<div class="panel panel-danger">
				 		<div class="panel-heading">
				 			<span class = 'glyphicon glyphicon-remove-sign'></span> Invalid Table ID.
				 		</div>				 		
				 	</div>
				 	<?php 
				 }
				 else{
				 	echo "<script>window.location='menu.php';</script>";
				 }
				 
			}

		?>



		<form action="index.php" method="POST" role="form">
			
			<table class="table">
			
				<tbody>
					<tr>
						<td><br>Table ID</td>
						<td>
							<br>
							<div class="input-group-sm">			
								<input type="name" name = 'table_id' class="form-control" id="" required="required" placeholder="Table Number">
							</div>	
						</td>
					</tr>
					<tr>
						<td><br><button type="submit" class="btn btn-primary btn-sm"><span class = 'glyphicon glyphicon-ok-circle'></span> GO</button></td>
					</tr>
				</tbody>
			</table>
			
		</form>
	</div>

	<!-- End of form -->
	<hr>
	<footer>
	
	</footer>
</body>
</html>