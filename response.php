

<?php 
	require 'connect.php'; 

	if (isset($_GET['all'])){
		$query = "MATCH (n:FOOD) RETURN n";
	}
	else if (isset($_GET['category'])) {		
		
		$query = "MATCH(n:FOOD)-[:HAS_CATEGORY]->(c) WHERE c.name = '".$_GET['category']."' RETURN n";
		echo "<h4 class = 'text-left'>Showing '".$_GET['category']."'</h4>";
	}		
	else{
		$query = "MATCH (n:FOOD) WHERE toLower(n.name) CONTAINS toLower('".$_GET['itemName']."') RETURN n"; 		
		echo "<h4 class = 'text-left'>Searching by '".$_GET['itemName']."'</h4>";
	}
		

	$results = $client->run($query);  
?>
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
							
							// Add the item to cart
							echo "<td>";

								echo "<a id = 'order' class = 'btn btn-sm btn-default'><span class = 'fas fa-cart-arrow-down'></span></a>";
							echo "</td>";
						echo "</tr>";	
						$id++;
					}				
				?>					
			</tbody>
		</table>
