<?php 
	require 'essentials.php'; 	
?>

	<div id = 'right-container'>

		<a href="addItem.php?b" data-toggle = 'tooltip' data-placement = 'right' title = 'Add Item'><span class = 'glyphicon glyphicon-plus-sign glyph'></span></a>

		<form class="form-inline pull-right" method = "POST" id = 'form' action = "menuopen.php?b&search">
			<div class="form-group">
				<div class="input-group">
					<input type="text" class="form-control" placeholder="Search" id = 'search' name = 'search'>
					<div class="input-group-addon">					
						<a onclick = "$('#form').submit();" class='link'><span class = 'glyphicon glyphicon-search' style = 'cursor: pointer;'></span></a>
					</div>										
				</div>				
			</div>
			
		</form>
		<hr>
		
		<!-- Display all foods available -->
		<?php 
			// Search
			if(isset($_POST['search'])){
				$query = "MATCH (n:FOOD) WHERE n.name CONTAINS '".$_POST['search']."' return n.name as name, n.description as description, n.price as price"; 
				$results = $client->run($query);
				echo "<script>$('#search').val('".$_POST['search']."')</script>"; 
			}

			else{
				$query1 = "MATCH (n:FOOD) return max(n.numImages) as s"; 
				$results = $client->run($query1); 	
				foreach ($results->getRecords() as $result) {
				$query = "MATCH (n:FOOD) return n.name as name, n.description as description, n.price as price, n.numImages as num,"; 					
					for ($i=1; $i <= $result->value('s'); $i++) { 
						 	
						if($i == $result->value('s'))
							$query .= "n.image".$i; 	
						else
							$query .= "n.image".$i.",";
					}										
				}

				$res = $client->run($query); 
			}			

		?>

		<table class="table table-responsive">
			<thead>
				<tr>
					<th>Name</th>
					<th>Description</th>
					<th>Price</th>
					<th>Images</th>
				</tr>
			</thead>
			<tbody>
				<?php 
					
					foreach ($res->getRecords() as $result) {
						echo "<tr >";
							echo "<td style = 'width:20%; padding: 2em 1em 2em 1em;'>".$result->value('name');
							echo "</td>";	
							echo "<td style = 'text-align: justify; padding: 2em 3em 2em 1em;'>".$result->value('description')."</td>";	
							echo "<td>$ ".$result->value('price')."</td>";
							echo "<td>";
								echo "<div class = 'images'>";
								// VIEW IMAGES
								for ($i=1; $i <= $result->value('num'); $i++) { 
									echo "<img src = '".$result->value('n.image'.$i)."' style = 'width:40%;'>";
								}
								echo "</div>";

							echo "</td>";
						echo "</tr>";
						
					}
					

					
				?>

				
			</tbody>
		</table>

	</div>		
	<footer>
		
	</footer>
</body>
</html>
