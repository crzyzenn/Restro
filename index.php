<?php 
	require 'essentials.php'; 
?>


	<!-- Form table number -->
	<div class="container-fluid">

		<?php 

			
			// Front end check table id
			if (isset($_POST['table_id'])) {
				 $query = "MATCH (n:TABLE) WHERE n.id = ".$_POST['table_id']." RETURN n.id";
				 $results = $client->run($query); 
				 if(empty($results->getRecords())){
				 	?>
				 	<div class="panel panel-danger">
				 		<div class="panel-heading">
				 			<span class = 'glyphicon glyphicon-remove-sign'></span> Invalid Table ID.
				 		</div>			 					 		
				 	</div>
				 	<?php 
				 }
				 else{	
				 	echo "<script>window.location='menu.php';</script>";
				 }
				 
			}

		?>



		<form action="index.php" method="POST" role="form">
			
			<table class="table">
			
				<tbody>
					<tr>
						<td><br>Table ID</td>
						<td>
							<br>
							<div class="input-group-sm">			
								<input type="name" name = 'table_id' class="form-control" id="" required="required" placeholder="Table Number">
							</div>	
							<br>
						</td>
					</tr>
					<tr>
						<td>
							<br>
							<button type="submit" class="btn btn-primary btn-sm"><span class = 'glyphicon glyphicon-ok-circle'></span> GO</button>
						</td>
					</tr>
				</tbody>
			</table>
			
		</form>
	</div>

	<!-- End of form -->
	<hr>
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
</body>
</html>