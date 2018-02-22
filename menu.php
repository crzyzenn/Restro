<?php 
	require 'essentials.php'; 
	ini_set('display_errors', 0); 
	$query = "MATCH (n:FOOD) RETURN n"; 
	$results = $client->run($query); 

?>

<div class="container-fluid">
	
	<h3 class = 'padding'>Menu</h3>
		<hr class = 'menuHr'>
					
	<div class="container custom pre-scrollable">					
		
				
		<table class="table table-hover">
			<thead>
				<tr>
					<!-- <th>S.N</th> -->
					<th><h4>Item</h4></th>
					<th><h4>Price</h4></th>
					<th></th>
				</tr>
			</thead>

		

			<tbody>

				<form action="menu.php" method="GET" role="form">
				
				
					<?php 
						$a = 1; 
						$id = 1;
						foreach ($results->getRecords() as $result) {
							 
							echo "<tr>";		
								// echo "<td><br>".$a++."</td>";
								echo "<td>";
					
								echo '<h4 style = "cursor:pointer;" data-toggle="modal" href="#modal-id'.$id.'">';
					
									echo $result->get('n')->value('name');

								echo "</h4>";
								
								// End of modal head

								echo "<p style = 'cursor:pointer;' data-toggle = 'modal' href = '#modal-id".$id."'>".$result->get('n')->value('description')."</p>";


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
									echo '<div class="form-group">
										<label>
											<input type="checkbox" name = "check'.$a++.'" value = "'.$result->get('n')->value('name').'">				
										</label>
									</div>'; 
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
				</form>
			</tbody>		
		</table>		
	</div>

	<button type="submit" class="btn btn-sm btn-default" style = 'margin-left: 1em;'><span class = 'glyphicon glyphicon-shopping-cart'></span> Add</button>
</div>
