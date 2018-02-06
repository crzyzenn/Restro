<!DOCTYPE html>
<html>
<head>
	<title>Details</title>
	<!-- Latest compiled and minified CSS & JS -->
	<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	<script src="//code.jquery.com/jquery.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script> -->
</head>

<body>
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
				<a class="navbar-brand" href="#">Title</a>
			</div>
	
			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse navbar-ex1-collapse">
				<ul class="nav navbar-nav">
					<li class="active"><a href="#">Link</a></li>
					<li><a href="details.php">Show details</a></li>
				</ul>
				<form class="navbar-form navbar-left" role="search">
					<div class="form-group">
						<input type="text" class="form-control" placeholder="Search">
					</div>
					<button type="submit" class="btn btn-default">Submit</button>
				</form>
				<ul class="nav navbar-nav navbar-right">
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
				</ul>
			</div><!-- /.navbar-collapse -->
		</div>
	</nav>
<table class="table table-hover">
		<thead>
			<tr>
				<th>Name</th>
				<th>Homescore</th>
				<th>Awayscore</th>
			</tr>
		</thead>
		<tbody>
			
	<?php

		require_once 'vendor/autoload.php';

		use GraphAware\Neo4j\Client\ClientBuilder;

		$client = ClientBuilder::create()
		    ->addConnection('bolt', 'bolt://neo4j:root@localhost:7687')
		    ->build();
		$query = "MATCH (n:MATCH) RETURN n.title as name, n.homescore as home, n.awayscore as away";
		$result = $client->run($query);

		foreach ($result->getRecords() as $record) {		    
		    echo "<tr>";
				echo "<td>".$record->value('name')."</td>"; 
				echo "<td>".$record->value('home')."</td>"; 
				echo "<td>".$record->value('away')."</td>"; 
			echo "</tr>"; 
		}


		// Create a node
		$query = "MATCH (n:PERSON) DELETE n";
		$result = $client->run($query);

	?>

	
		
			
		</tbody>
	</table>
	
</body>
</html>
