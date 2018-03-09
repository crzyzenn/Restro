<?php 
	require 'essentials.php'; 	
?>

	<div id = 'right-container'>
		<a href="addTable.php?c" data-toggle = 'tooltip' data-placement = 'bottom' title = 'Add Table'><span class = 'glyphicon glyphicon-plus-sign glyph'></span></a>


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


		<?php 
			$query = "MATCH (n:TABLE) RETURN n.id as id, n.floor as floor"; 
			$results = $client->run($query); 			
		?>

		<table class="table table-responsive">
			<thead>
				<tr>
					<th>ID</th>
					<th>Floor Number</th>
				</tr>
			</thead>
			<tbody>
				<?php 
					foreach ($results->getRecords() as $result) {
						echo "<tr>";
							echo "<td>";
								echo $result->value("id");

							echo "</td>";


							echo "<td>";
								echo $result->value("floor");
							echo "</td>";
						echo "</tr>";
					}

				?>
				<tr>
					<td></td>
				</tr>
			</tbody>
		</table>



	</div>
</body>
</html>
