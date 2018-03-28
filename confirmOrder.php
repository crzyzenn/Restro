<?php 
	require 'essentials.php'; 
	if (!checkSession()) {
		header('Location:index.php'); 
	}
	else{
		loadLayout("Restro - Browse Menu", "Home");	


	




	ini_set('display_errors', 0); 
	$query = "MATCH (n:FOOD) RETURN n"; 
	$results = $client->run($query); 

	// Append to orders
	// Data stored in 2D array
	if ($_SESSION['tick'] == 0) {
		if(!is_null($_GET['itemname'])){
			$s = sizeof($_GET['itemname']);
			for ($i=0; $i < $s ; $i++) { 
				array_push($_SESSION['orders'], [$_GET['itemname'][$i], $_GET['quantity'][$i], $_GET['price'][$i]]); 	
			}			
			
			$_SESSION['tick'] = 1; 
		}
	}


	// Add orders to database
	$s = sizeof($_SESSION['orders']);

	for ($i=0; $i < $s; $i++) {
		$name = $_SESSION['orders'][$i][0]; 
		$quantity = $_SESSION['orders'][$i][1]; 
		$price = $_SESSION['orders'][$i][2];
		$query1 = 'MATCH (food:FOOD{name:"'.$name.'"}), (user:USER{name:"session'.$_SESSION['user_code'].'"}) CREATE UNIQUE (user)-[:ORDERED{quantity:'.$quantity.', price: '.$price.', orderStatus: "NA"}]->(food)'; 
		$results1 = $client->run($query1); 
	}

?>
<div class="container" style = "
    background-color: #3c763d;
    padding: .5em 3em;
    color: white;
    margin-bottom: 1em;
    width: 100%;">
		<h4 class = 'text-center'><i class="glyphicon glyphicon-check"></i> Your order has been confirmed</h4>
	
	</div>
<div class="container-fluid">

	<h3>Invoice </h3>
	<hr>		

	<!-- Get form values -->
	<table class="table table-hover">
		<thead>
			<tr>
				<th>Name</th>
				<th>Quantity</th>
				<th>Price</th>
			</tr>
		</thead>
		<tbody>
			<?php 
				$total = 0; 				
				$size = sizeof($_SESSION['orders']);
			
				for ($i=0; $i < $size; $i++) {
					echo "<tr>";
						
						for ($j=0; $j < 3; $j++) { 
							echo "<td>".$_SESSION['orders'][$i][$j]."</td>";
						} 	

						$total = $total + $_SESSION['orders'][$i][2];						
					echo "</tr>";							 
				}

				echo "<tr>";
				echo "<td>Total</td>";
				echo "<td></td>";
				echo "<td> $".$total."</td>";
				echo "</tr>";
			?>

		</tbody>
	</table>
	<a class="btn btn-default" href="invoicepdf.php" target="_blank" data-toggle= "tooltip" data-placement = "bottom" title = "Get a pdf version of the invoice"  role="button"><i class = 'fas fa-save'></i></a>
	<a class="btn btn-default" href="menu.php" data-toggle= "tooltip" data-placement = "bottom" title = "Browse Menu" role="button"><i class = 'glyphicon glyphicon-backward'></i></a>		

</div>



<footer>
	<div class="container-fluid">
		
			<h4>Follow us at:</h4>
			<ul>
				<li><i style = 'color:white' class = 'fab fa-facebook fa-2x'></i></li>
				<li><i style = 'color:white' class = 'fab fa-instagram fa-2x'></i></li>
				<li><i style = 'color:white' class = 'fab fa-twitter fa-2x'></i></li>
				<li><i style = 'color:white' class = 'fab fa-pinterest fa-2x'></i></li>					
			</ul>

			<p>&copy All Rights Reserved</p>
		
	</div>

</footer>
<?php 
	}
?>
