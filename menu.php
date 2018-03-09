<?php 
	session_start();
	require 'essentials.php'; 
	ini_set('display_errors', 0); 
	$query = "MATCH (n:FOOD) RETURN n"; 
	$results = $client->run($query); 
	$_SESSION['tick'] = 0; 
?>

<div class="container-fluid">
	

	<!-- LOG OUT -->
	<?php 
		if (isset($_GET['logout'])) {
			session_destroy();
			echo "<script>window.location = 'index.php';</script>";
		}
		else if(isset($_GET['confirm'])){
			echo "<script>var a = confirm('Are you sure?'); if(a) window.location = 'menu.php?logout'</script>";
		}

	?>
	<!-- END OF LOG OUT -->


	<div class = 'menu-bar'>
		<h3 class = 'padding pull-left'>Menu</h3>

		<!-- Cart modal -->
		<h4 class = 'cart' data-toggle="modal" href='#modal-id'>
			<span data-toggle = 'tooltip' data-placement = 'bottom' title="Cart" class = 'glyphicon glyphicon-shopping-cart'></span></h4>

		<!-- Logout -->
		<h4 name = "logout" data-toggle = 'tooltip' data-placement = 'bottom' title = 'Logout' onclick = "window.location = 'menu.php?confirm'" class="cart link"><span class = 'glyphicon glyphicon-log-out'></span></h4>
		</form>
		

		<div class="modal fade" id="modal-id">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title"><span class = 'glyphicon glyphicon-shopping-cart'></span></h4>
					</div>
					<form id = 'cartForm' action = 'confirmOrder.php' method = 'GET'>
						<div class="modal-body">
							<table class="table table-striped">
								<thead>
									<tr>
										<th>Item</th>
										<th>Quantity</th>	
										<th>Price ($)</th>									
										<th></th>
									</tr>
								</thead>

								
									<tbody id = 'cart'>
										<tr>
											<td><h3>Existing orders</h3></td>
											<td><h3></h3></td>
											<td><h3></h3></td>
										</tr>										
										<!-- View placed orders -->
										<?php 											
											$size = sizeof($_SESSION['orders']);
											
											for ($i=0; $i < $size; $i++) {		
												echo "<tr>";
													
													for ($j=0; $j < 3; $j++) { 
														echo "<td>".$_SESSION['orders'][$i][$j]."</td>";
													} 					
												echo "</tr>";							 
											}
										?>
										<tr>
											<td></td>
											<td></td>
											<td></td>
										</tr>
										<tr>
											<td><h3>Current orders</h3></td>
											<td><h3></h3></td>
											<td><h3></h3></td>
										</tr>
									</tbody>							
								
							</table>
							<hr>
							Total (VAT incl.): $<span id = 'total_price'></span>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
							<button type="button" id = 'confirm' class="btn btn-sm btn-primary">Confirm</button>
						</div>
					</form>
				</div>
			</div>
		</div>

		<!-- End of cart modal -->

	</div>

	<hr class = 'menuHr'>
					
	<div class="container custom pre-scrollable">					
		
				
		<table class="table table-hover menu" id = 'orderTable'>
			<thead>
				<tr>
					<!-- <th>S.N</th> -->
					<th><h4>Item</h4></th>
					<th><h4>Price</h4></th>
					<th></th>
				</tr>
			</thead>

		

			<tbody>				
				
				
				<?php 
					$a = 1; 
					$id = 1;
					foreach ($results->getRecords() as $result) {
						 
						echo "<tr>";		
							// echo "<td><br>".$a++."</td>";
							echo "<td>";
				
							echo '<h5 id = "item" style = "cursor:pointer;" data-toggle="modal" href="#modal-id'.$id.'">';
				
								echo $result->get('n')->value('name');

							echo "</h5>";
							
							// End of modal head

							// echo "<p id = 'description' style = 'cursor:pointer;' data-toggle = 'modal' href = '#modal-id".$id."'>".$result->get('n')->value('description')."</p>";
							echo "<img data-toggle = 'modal' href = '#modal-id".$id."' class = 'menuImage' src = 'Images/".$result->get('n')->value('image1')."'>";


							// Modal body
							
							echo '<div class="modal fade" id="modal-id'.$id.'">'; ?>
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
											<h4 class="modal-title" style = "color:#484847;" ><?php echo $result->get('n')->value('name'); ?></h4>
										</div>
										<div class="modal-body">
											<?php 
													// Description
													echo "<h4>Description</h4>";
													echo "<p>".$result->get('n')->value('description')."</p>";

													$total_images = $result->get('n')->value('numImages'); 
									    			echo "<div class = 'food_images'>";
										    			for ($i=1; $i <= $total_images; $i++) { 
									    				
									    					echo "<img src = '".substr($result->get('n')->value('image'.$i), 3)."' >";
										    				
										    			}
									    			echo "</div>";
											?>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>								
										</div>
									</div>
								</div>
							</div>

				<?php
							// End of modal

							echo "</td>";

							// Price
							echo "<td>$".$result->get('n')->value('price')."</td>";
							
							// Checkbox
							echo "<td>";

								echo "<a id = 'order' class = 'btn btn-sm btn-default'><span class = 'fas fa-cart-arrow-down'></span></a>";
							echo "</td>";
						echo "</tr>";	
						$id++;
					}				
				?>					
			</tbody>
		</table>
	</div>
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

