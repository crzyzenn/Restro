<?php 
	require '../vendor/autoload.php'; 	

	// Establish connection to the database
	require_once '../vendor/autoload.php'; 
	use GraphAware\Neo4j\Client\ClientBuilder;
	$client = ClientBuilder::create()
		    ->addConnection('bolt', 'bolt://neo4j:root@localhost:7687')
		    ->build();
		    
	
?>




<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>Kitchen</title>
		<link rel = 'stylesheet' media="screen" href = '../bs/css/bootstrap.css'>
		<script src="../bs/js/jquery.js"></script>
		<script type="text/javascript" src = '../bs/js/bootstrap.js'></script>	
		<script type="text/javascript" src = '../bs/js/notify.js'></script>	
		<script defer src="../bs/js/fontawesome-all.js"></script>


		<!-- Test script -->

		<!-- End of Test script -->


	</head>

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
					<a class="link" href="index.php"><img src="../Images/home.png" class = 'home_img'></a>
				</div>
		
				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse navbar-ex1-collapse">					
					

				</div><!-- /.navbar-collapse -->
			</div>
		</nav>		
	</header>

	<?php 
		// Form validation
		if (isset($_POST['mark'])) {
			foreach ($_POST['itemName'] as $key => $value) {
				$client->run("MATCH P = (N:TABLE)-[]-(C)-[X]-(D) WHERE TYPE(X) <> 'GENERATED_INVOICE' AND D.name = '".$value."' AND N.id = '".$_POST['table']."' SET X.orderStatus = 'Completed'"); 							
			
			}
		}
	?>
	<body>
		<div class="jumbotron">
			<div class="container">
				<h1>Current Orders</h1>
				<hr>
				<?php 					

					// Get the total number of orders
					$totalOrdersQuery = $client->run("MATCH P = (N:TABLE)-[]-(C)-[X]-(D) WHERE TYPE(X) <> 'GENERATED_INVOICE' AND X.orderStatus = 'NA' RETURN DISTINCT N.id as table"); 
					// $totalOrders = $totalOrdersQuery->getRecord()->value('totalOrders');



				?>

				<!-- Partition -->
				<?php 
					if (empty($totalOrdersQuery->getRecords())) {
						echo "<h3>No orders available :(</h3>";
					}					
					$iter = 0; 
					foreach ($totalOrdersQuery->getRecords() as $tables) { 
						$iter++;
						// Get orders fromt that specific table
						$results = $client->run("MATCH P = (N:TABLE)-[]-(C)-[X]-(D) WHERE TYPE(X) <> 'GENERATED_INVOICE' AND X.orderStatus = 'NA' AND N.id = '".$tables->value('table')."' RETURN D.name as name, X.quantity as quantity, N.id as table"); 
				?>
						<div class = 'partition'>
							<h4 class="text-center">
								<?php 
									 
									echo '<i class="fas fa-coffee"></i> ' . $tables->value('table');
									echo "<hr>";
								?>
							</h4>
							<div class = 'container pre-scrollable'>
								<form action = "#" method = "POST">
									<table class="table table-hover">
										<thead>
											<tr>							
												<th>Item</th>
												<th>Quantity</th>
												<th></th>
											</tr>
										</thead>
										<tbody>

											<?php 
												foreach ($results->getRecords() as $orders) {
													echo "<tr>";
														echo "<td class = 'itemName'>".$orders->value('name')."</td>";
														echo "<input type = 'hidden' name = 'itemName[]' value = '".$orders->value('name')."'>";
														echo "<input type = 'hidden' name = 'table' value = '".$tables->value('table')."'>";
														echo "<td>".$orders->value('quantity')."</td>";
													echo "</tr>";
												

												}
											?>
										</tbody>
									</table>
									<a class="btn btn-primary" data-toggle="modal" href='#modal-id<?php echo $iter; ?>'><span class = 'glyphicon glyphicon-check'></span> Mark as done</a>
									<div class="modal fade" id="modal-id<?php echo $iter; ?>">
										<div class="modal-dialog">
											<div class="modal-content">
												<div class="modal-header">
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
													<h4 class="modal-title">Are you sure?</h4>
												</div>
												<div class="modal-body">
													<h5>The orders will be removed from the order section and considered completed.</h5>
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
													<button type="submit" name = "mark" class="btn btn-primary">Ok</button>
												</div>
											</div>
										</div>
									</div>
									<!-- <button type="submit" name = "mark" class="btn btn-primary"></button> -->
								</form>
							</div>	
						</div>
				<!-- End of partition -->
				<?
					}
				?>
				
			</div>
		</div>
	</body>
</html>

<?
// }

?>