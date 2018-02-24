<?php 
	require 'essentials.php'; 
	ini_set('display_errors', 0); 
	$query = "MATCH (n:FOOD) RETURN n"; 
	$results = $client->run($query); 

?>

<div class="container-fluid">
	<div class = 'menu-bar'>
		<h3 class = 'padding pull-left'>Menu</h3>

	

		<!-- Cart modal -->


		<h4 class = 'cart' data-toggle="modal" href='#modal-id'><span class = 'glyphicon glyphicon-shopping-cart'></span></h4>
		<div class="modal fade" id="modal-id">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title"><span class = 'glyphicon glyphicon-shopping-cart'></span></h4>
					</div>
					<div class="modal-body">
						<table class="table table-striped">
							<thead>
								<tr>
									<th>Item</th>
									<th>Quantity</th>	
									<th>Price</th>									
									<th></th>
								</tr>
							</thead>
							<tbody id = 'cart'>
								
								
							</tbody>
						</table>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary">Confirm</button>
					</div>
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
				
							echo '<h4 id = "item" style = "cursor:pointer;" data-toggle="modal" href="#modal-id'.$id.'">';
				
								echo $result->get('n')->value('name');

							echo "</h4>";
							
							// End of modal head

							echo "<p id = 'description' style = 'cursor:pointer;' data-toggle = 'modal' href = '#modal-id".$id."'>".$result->get('n')->value('description')."</p>";


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
								// echo '<div class="form-group">
								// 	<label>
								// 		<input type="checkbox" name = "check'.$a++.'" value = "'.$result->get('n')->value('name').'">				
								// 	</label>
								// </div>'; 
								echo "<a id = 'order' class = 'btn btn-sm btn-default'><span class = 'glyphicon glyphicon-plus'></span></a>";
							echo "</td>";
						echo "</tr>";	
						$id++;
					}				
				?>					

				<!-- <tr>					
					<td>
						<br>
							<button type="submit" class="btn btn-sm btn-default"><span class = 'glyphicon glyphicon-shopping-cart'></span> Add</button>
						<br>
					</td>
					<td></td>
					<td></td>
				</tr> -->
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

