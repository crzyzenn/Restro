<?php 	
	require "essentials.php";
	loadLayout("Restro - Home"); 	

	// Today's date
	$date =  date('Y-M-d');

?>	
	<!-- Form table number -->
	<div class="container-fluid">

		<?php 
			// Front end check table id
			if (isset($_POST['table_id'])) {
				$query = "MATCH (n:TABLE) WHERE n.id = '".$_POST['table_id']."' RETURN n.id";
				$results = $client->run($query); 

				// If table id not valid
				if(empty($results->getRecords())){
					?>
					<div class="panel panel-danger">
						<div class="panel-heading">
				 			<span class = 'glyphicon glyphicon-remove-sign'></span> Invalid Table ID.
				 		</div>			 					 		
				 	</div>
					<?php 
				}

				// If table id is OK
				else{						
					if(isset($_SESSION["user".$_POST['table_id']])){
	 					echo "<span class = 'fas fa-sync-alt'></span> Continuing from last session.";
	 					echo "<script>window.location='menu.php';</script>";

	 				}
	 				else{	 					
	 					echo "<span class = 'fas fa-sync-alt'></span> Starting new session.";
	 					$_SESSION["user".$_POST['table_id']] = "true"; 
	 					$_SESSION["table"] = $_POST['table_id']; 
	 					$_SESSION["orders"] = array(); 
	 					$_SESSION['tick'] = 0;

	 					// Year, Month, Day, Hour, Minute
	 					// Unique user code for each session
	 					$_SESSION['user_code'] = date('y-m-d-h-i-s'); 	 					

 						// Create new session user in database
 						$query = 'MATCH (table:TABLE{id:"'.$_POST['table_id'].'"}) CREATE UNIQUE (user:USER{name:"session'.$_SESSION['user_code'].'"})-[:FROM_TABLE{date:"'.$date.'"}]->(table)'; 
 						$client->run($query); 

 						// Redirect to the restaurant menu
 						header("Location:menu.php");
	 				} 						 		
				}	 	
			}
		?>


<div id="myCarousel" style = "margin-top: 2em;" class="carousel slide" data-ride="carousel">
  <!-- Indicators -->
  <ol class="carousel-indicators">
    <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
    <li data-target="#myCarousel" data-slide-to="1"></li>
    <!-- <li data-target="#myCarousel" data-slide-to="2"></li> -->
  </ol>

  <!-- Wrapper for slides -->
  <div class="carousel-inner">
    <div class="item active">
      <img src="Images/1.jpg" alt="Los Angeles">
      <div class = 'carousel-caption'>
      	
      </div>
    </div>

    <div class="item">
      <img src="Images/2.jpg" alt="Chicago">
      <div class = 'carousel-caption'>
      	<!-- <h2>Get food recommendations</h2>	 -->
      </div>
      
    </div>
<!-- 
    <div class="item">
      <img src="Images/3.jpg" alt="New York">
      <div class = 'carousel-caption'>
      	<h2>Access across all devices.</h2>	
      </div>

    </div> -->

  </div>

  <!-- Left and right controls -->
  <a class="left carousel-control" href="#myCarousel" data-slide="prev">
    <span class="glyphicon glyphicon-chevron-left"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="right carousel-control" href="#myCarousel" data-slide="next">
    <span class="glyphicon glyphicon-chevron-right"></span>
    <span class="sr-only">Next</span>
  </a>
</div>
<br><br>

<!-- 
		<form action="index.php" method="POST" role="form">
			
			<table class="table">
			
				<tbody>
					<tr>
						<td><h1>Enter Table ID to get started</h1></td>
						<td>
							<br>
							<div class="input-group-sm">			
								<input type="name" name = 'table_id' class="form-control" id="" required="required" placeholder="Enter Table Number">
							</div>	
							<br>
						</td>
					</tr>
					<tr>
						<td>
							<br>
							<button type="submit" class="btn btn-primary btn-sm"><span class = 'glyphicon glyphicon-ok-circle'></span> Start Ordering!</button>
						</td>
					</tr>
				</tbody>
			</table>		

			


				<div class="container custom">
					<h2 class = 'text-center'></h2>
					<div class="input-group">			

						<input type="name" name = 'table_id' class="form-control" id="" required="required" placeholder="Enter Table Number">	
						<div class="input-group-addon"><span class = 'glyphicon glyphicon-tag'></span></div>			
					</div>	
					<br>
					<button type="submit" class="btn btn-primary"><span class = 'glyphicon glyphicon-ok-circle'></span> Start Ordering!</button>
		
				</div>
		</form> -->


		<!-- Table entering menu -->
		<div style = "margin: 0 auto; width: fit-content;">
			<h4 class="btn btn-primary btn-lg pointer" data-toggle="modal" href='#modal-id'>Start Ordering <i class="fas fa-utensils"></i></h4>
			<div class="modal fade" id="modal-id">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title">Enter table number:</h4>
						</div>
						<div class="modal-body">
							<!-- Table form -->
							<form action="index.php" method="POST" role="form">
								<h2 class = 'text-center'></h2>
								<div class="input-group">			

									<input type="name" name = 'table_id' class="form-control" id="" required="required" placeholder="Enter Table Number">	
									<div class="input-group-addon"><span class = 'glyphicon glyphicon-tag'></span></div>			
								</div>	
								<br>
								
				
							
							<!-- End of table form -->
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							<button type="submit" class="btn btn-primary"><span class = 'glyphicon glyphicon-ok-circle'></span> Go</button>
						</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		
		<!-- End of table entering menu -->


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