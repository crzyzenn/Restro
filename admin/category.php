<?php 
	require 'essentials.php'; 
	loadLayout("Restro | Admin - Category", "Category");

	if (isset($_POST['catName'])) {
 		$client->run('MATCH (n:CATEGORY) WHERE n.name = "'.$_POST['old_id'].'" SET n.name = "'.$_POST['catName'].'"');
 		echo "<script>$.notify('Changes saved.', {autoHideDelay:2000, className:'success'})</script>"; 

 	}
	else if(isset($_POST['add'])){
		$client->run('CREATE (N:CATEGORY{name:"'.$_POST['name'].'"})');
 		echo "<script>$.notify('Category successfully added.', {autoHideDelay:2000, className:'success'})</script>"; 
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
						<form action="#" method="POST" class = 'form-group'>	

							<table class="table">
								
								<tbody>
									<tr>
										<td>Category Name</td>
										<td>
											<input type="text" name="name" class="form-control" value="" required="required" title="" placeholder="Name">
										</td>
										<td></td>
									</tr>
								</tbody>
							</table>


							
							<div class = 'form-group col-xs-4'>
								
							</div>

					

						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							<button type="submit" name = 'add' class="btn-sm btn-primary"><span class = 'glyphicon glyphicon-plus-sign'></span> Add Category</button>
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
			$query = "MATCH (n:CATEGORY) RETURN n.name as name"; 
			$results = $client->run($query); 			
		?>

		<table class="table table-responsive">
			<thead>
				<tr>					
					<th><h4>Category Name</h4></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php
					$modalCount = 0;  
					foreach ($results->getRecords() as $result) {
						$modalCount++; 
						echo "<tr>";
							echo "<td>";
								echo $result->value("name");								

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
													$table = $client->run("MATCH (N:CATEGORY) WHERE N.name = '".$result->value("name")."' RETURN N.name as name");
													foreach ($table->getRecords() as $tab) {
														
													
												?>
													<form method = 'POST' action = '#'>
														<table class="table">
															
															<tbody>
																<tr>
																	<td>Category Name</td>
																	<td>
																		<input type="text" name="catName" class="form-control" value="<?php echo $tab->value('name') ?>" required="required" title="" placeholder="Table identifier">
																		<input type="hidden" name="old_id" class="form-control" value="<?php echo $tab->value('name') ?>" required="required" title="" placeholder="Table identifier">

																	</td>
																	<td></td>
																</tr>															
															</tbody>
														</table>
													

												<?
													}
												?>	


											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-danger" onclick = "var a = confirm('Are you sure?'); if(a) window.location = 'category.php?delete&name=<?php echo $result->value('name')?>'"><span class = 'glyphicon glyphicon-remove-sign'></span> Delete</button>


												
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
