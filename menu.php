<?php 
	require 'essentials.php'; 

	$query = "MATCH (n:FOOD) RETURN n.name as name, n.description as description, n.price as price"; 
	$results = $client->run($query); 

?>

<div class="container-fluid">
	

	<div class="container custom">					
		<h3>Menu</h3>	
		<hr>
					
				
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
						foreach ($results->getRecords() as $result) {
							echo "<tr>";		
								// echo "<td><br>".$a++."</td>";
								echo "<td>"; 
									echo "<h4>".$result->value('name')."</h4>";
									echo "<p>".$result->value('description')."</p>";
								echo "</td>";
								echo "<td>$".$result->value('price')."</td>";
								echo "<td>";
									echo '<div class="form-group">
										<label>
											<input type="checkbox" name = "check'.$a++.'" value = "'.$result->value('name').'">				
										</label>
									</div>'; 
								echo "</td>";
							echo "</tr>";		
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

		<button type="submit" class="btn btn-sm btn-default"><span class = 'glyphicon glyphicon-shopping-cart'></span> Add</button>


		
	</div>
			
</div>