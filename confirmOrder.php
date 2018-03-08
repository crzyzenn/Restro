<?php 
	session_start();
	require 'essentials.php'; 
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
			
			// var_dump($_SESSION['orders']); 	
			$_SESSION['tick'] = 1; 
		}
	}	
	// var_dump($_SESSION['orders']); 


?>

<div class="container-fluid">
	<div class = 'menu-bar'>
		<!-- <h3 class = 'padding pull-left'><span class = 'glyphicon glyphicon-saved'></span> Your order has been confirmed!</h3> -->
		<div class="panel panel-default">
			<div class = 'panel-heading'>
				<span class = 'fas fa-check-circle'></span> Your order has been confirmed!
			</div>
		</div>
	</div>
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
	<a class="btn btn-default" href="invoicepdf.php" target="_blank" role="button"><i class = 'fas fa-save'></i> Save PDF</a>		

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

