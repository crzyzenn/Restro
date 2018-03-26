<?php 
	require 'essentials.php'; 
	loadLayout("Restro | Admin - Table", "Table");	

	if (isset($_GET['added'])) {
		echo "<script>$.notify('Table has been added', {autoHideDelay:2000, className:'success'});</script>";
	}
	else if (isset($_GET['error'])) {
		echo "<script>$.notify('Table name might have already been used. Please try again with a different name.', {autoHideDelay:3000, class:'danger'});</script>";
	}
	else if (isset($_GET['delete'])) {

		// Check if the table is connected with nodes
		$que = 'MATCH (N:TABLE{id:"'.$_GET['id'].'"})-[x]-(user)-[y]-() RETURN type(x) as type'; 
		$resss = $client->run($que); 

		if (empty($resss->getRecords())) {
			$que = 'MATCH (N:TABLE) WHERE N.id = "'.$_GET['id'].'" DELETE N';
			$resss = $client->run($que); 	
		}
		else{		
			$name = ""; 
			// Get invoice name
			$invoiceName = $client->run('MATCH (N:TABLE{id:"'.$_GET['id'].'"})-[x]-(invoice)-[y]-(inv) WHERE type(y) = "GENERATED_INVOICE" return inv.filename as name'); 
			foreach ($invoiceName->getRecords() as $invoice) {
				$name =  $invoice->value('name'); 
			}

			echo $name;
			$que = 'MATCH (N:TABLE{id:"'.$_GET['id'].'"})-[x]-(user)-[y]-() DELETE x,y,user,N'; 
			$resss = $client->run($que); 
			

			$anotherQuery = "MATCH (n:INVOICE) WHERE n.filename = '".$name."' DELETE n";
			$client->run($anotherQuery); 

			unlink("../Invoices/".$name);
			echo "<script>$.notify('Table has been successfully deleted', {autoHideDelay:2000, className:'success'})</script>"; 
		}

		// If connected nodes are found, delete all
		// catch(Exception $e){
			
			// $que = 'MATCH (N:TABLE{id:"'.$_GET['id'].'"})-[x]-(user)-[y]-()  DELETE x,y,user,N'; 
			// $resss = $client->run($que); 

			// $anotherQuery = "MATCH (n:INVOICE) WHERE n.filename = 'Invoice-Session".$_SESSION['user_code'].".pdf' DELETE n";
			// unlink("../Invoices/Invoice-Session".$_SESSION['user_code'].".pdf");
		// }



		// Try to delete normally
		// try{
		// 	echo "Here";
		// 	try{
		// 		
		// 	}
		// 	catch(Exception $e){
		// 		echo "here";
		// 	}
			
		// }
		// catch(Error $e){
		// 	echo "asdfasdf";
		// 	// echo $e->getMessage();

		// 	// 
			echo "<script>$.notify('Table has been successfully deleted', {autoHideDelay:2000, className:'success'})</script>"; 
		// // }

		
 	}
 	else if (isset($_POST['floor'])) {
 		$client->run('MATCH (n:TABLE) WHERE n.id = "'.$_POST['old_id'].'" SET n.id ="'.$_POST['id'].'", n.floor = '.$_POST['floor']);
 		echo "<script>$.notify('Changes saved.', {autoHideDelay:2000, className:'success'})</script>"; 

 	}
?>

	<div id = 'right-container'>
		<!-- <a href="addTable.php?c" data-toggle = 'tooltip' data-placement = 'bottom' title = 'Add Table'><span class = 'glyphicon glyphicon-plus-sign glyph'></span></a> -->


		<a data-toggle="modal" href='#modal-id'><span class = 'glyphicon glyphicon-plus-sign glyph'></span></a>
		<div class="modal fade" id="modal-id">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title">Add table</h4>
					</div>
					<div class="modal-body">
						<form action="addTable.php?c" method="POST" class = 'form-group'>	

							<table class="table">
								
								<tbody>
									<tr>
										<td>Table Id</td>
										<td>
											<input type="text" name="id" class="form-control" value="" required="required" title="" placeholder="Table identifier">
										</td>
										<td></td>
									</tr>
									
									<tr>
										<td>Floor</td>
										<td>
											<input type="text" name="floor" id="price" class="form-control" value="" required="required" placeholder = "Floor number" title="">
										</td>

									</tr>					
								</tbody>
							</table>


							
							<div class = 'form-group col-xs-4'>
								
							</div>

					

						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							<button type="submit" name = 'add' class="btn-sm btn-primary"><span class = 'glyphicon glyphicon-plus-sign'></span> Add Table</button>
						</div>
					</form>
				</div>
			</div>
		</div>


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
					$modalCount = 0;  
					foreach ($results->getRecords() as $result) {
						$modalCount++; 
						echo "<tr>";
							echo "<td>";
								echo $result->value("id");								

							echo "</td>";


							echo "<td>";
								echo $result->value("floor");
							echo "</td>";

							echo "<td>";
							?>
								<a class="btn btn-primary" data-toggle="modal" href='#modal-id<?php echo $modalCount;?>'><span class = 'glyphicon glyphicon-edit'></span></a>
								<div class="modal fade" id="modal-id<?php echo $modalCount;?>">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
												<h4 class="modal-title"><span class = 'glyphicon glyphicon-edit'></span> Edit</h4>
											</div>
											<div class="modal-body">
												
												<?php 												
													$table = $client->run("MATCH (N:TABLE) WHERE N.id = '".$result->value("id")."' RETURN N.id as id, N.floor as floor");
													foreach ($table->getRecords() as $tab) {
														
													
												?>
													<form method = 'POST' action = 'tableopen.php'>
														<table class="table">
															
															<tbody>
																<tr>
																	<td>Table Id</td>
																	<td>
																		<input type="text" name="id" class="form-control" value="<?php echo $tab->value('id') ?>" required="required" title="" placeholder="Table identifier">
																		<input type="hidden" name="old_id" class="form-control" value="<?php echo $tab->value('id') ?>" required="required" title="" placeholder="Table identifier">

																	</td>
																	<td></td>
																</tr>
																
																<tr>
																	<td>Floor</td>
																	<td>
																		<input type="number" name="floor" id="price" class="form-control" value="<?php echo $tab->value('floor') ?>" required="required" placeholder = "Floor number" title="">
																		<input type="hidden" name="old_floor" id="price" class="form-control" value="<?php echo $tab->value('floor') ?>" required="required" placeholder = "Floor number" title="">
																	</td>

																</tr>					
															</tbody>
														</table>
													

												<?
													}
												?>	


											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-danger" onclick = "var a = confirm('Are you sure?'); if(a) window.location = 'tableopen.php?delete&id=<?php echo $result->value('id')?>'"><span class = 'glyphicon glyphicon-remove-sign'></span> Delete</button>


												
												<button type="submit" class="btn btn-primary"><span class = 'glyphicon glyphicon-floppy-save'></span> Save changes</button>
												<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
											</div>
										</form>
										</div>
									</div>
								</div>
							<?
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
