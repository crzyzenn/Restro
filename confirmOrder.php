<?php 
	require 'essentials.php'; 
	ini_set('display_errors', 0); 
	$query = "MATCH (n:FOOD) RETURN n"; 
	$results = $client->run($query); 

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
				$size = sizeof($_GET['itemname']); //Size of array
				for ($i=0; $i < $size; $i++) { 						
					echo "<tr>";
						echo "<td>".$_GET['itemname'][$i]."</td>";
						echo "<td>".$_GET['quantity'][$i]."</td>";
						echo "<td> $".$_GET['price'][$i]."</td>";
					echo "</tr>";
					$total = $total + $_GET['price'][$i]; 
				}
				echo "<tr>";
				echo "<td>Total</td>";
				echo "<td></td>";
				echo "<td> $".$total."</td>";
				echo "</tr>";
				
			?>
		</tbody>
	</table>
	<button type="button" onclick = 'window.print();' class="btn btn-default btn-lg"><i class = 'fas fa-save'></i></button>	

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

