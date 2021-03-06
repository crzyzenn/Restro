<?php 
	require 'essentials.php';
	loadLayout("Restro | Admin - Menu", "Menu");

	if (isset($_POST['edit'])) {		
		$client->run("MATCH (food:FOOD)-[x:HAS_CATEGORY]-(c) WHERE food.name = '".$_POST['oldName']."' SET food.name = '".$_POST['name']."', food.description = '".$_POST['description']."', food.price = '".$_POST['price']."'");
		echo "<script>$.notify('Edited', {autoHideDelay:2000, className:'success'});</script>";
 	} 	
 	
 	if (isset($_GET['delete'])) {
 		$que = 'MATCH (N:FOOD)-[x]-(c) WHERE ID(N)='.$_GET['id'].' DELETE x,N';
 		$resss = $client->run($que); 
 		echo "<script>$.notify('Item has been deleted', {autoHideDelay:2000, className:'success'})</script>"; 
 	}

 	if (isset($_GET['added'])) {
 		echo "<script>$.notify('Item has been added', {autoHideDelay:2000, className:'success'})</script>"; 	
 	}
?>



	<div id = 'right-container'>

		<!-- <a href="addItem.php?b" data-toggle = 'tooltip' data-placement = 'bottom' title = 'Add Item'><span style = 'color:white' class = 'glyphicon glyphicon-plus-sign glyph'></span></a> -->


		<!-- Adding an item -->
		<a data-toggle="modal" href='#modal-id'><span style = 'color:white; cursor: pointer;' class = 'glyphicon glyphicon-plus-sign glyph'></span></a>
		<div class="modal fade" id="modal-id">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title">Add new item</h4>
					</div>
					<div class="modal-body">
						<!-- Form -->
						<form action="addItem.php" method="POST" class = 'form-group' enctype = "multipart/form-data">			

							<table class="table table-responsive" id = 'uploadDiv'>
								<thead>
									<tr>
										<th></th>
										<th></th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>Item Name</td>
										<td>
											<input type="text" name="name" id="name" class="form-control" value="" required="required" title="">
										</td>
										<td></td>
									</tr>
									<tr>
										<td>Item Description</td>
										<td>
											<textarea name="description" id="desc" class="form-control" rows="10"></textarea>					
										</td>					
										<td></td>	
									</tr>
									<tr>
										<td>Category</td>
										<td>
											<select name="category" id="category" class="form-control">
												<?php 
													$categories = $client->run("MATCH (n:CATEGORY) return n.name as category"); 
													foreach ($categories->getRecords() as $category) {
														echo '<option value="'.$category->value('category').'">'.$category->value('category').'</option>';		
													}

												?>								
											</select>					
										</td>					
										<td></td>	
									</tr>
									<tr>
										<td>Price</td>
										<td>
											<input type="number" name="price" step="any" id="price" class="form-control" value="" required="required" placeholder = "$" title="">
										</td>
										<td></td>						
									</tr>
									<tr>
										<td>Images</td>
										<td>
											<input type="file" name= "myImage1" class="form-control">
											<input type="hidden" name= "add">									
										</td>
										<td>
											<button class = 'btn-sm btn-default' onclick = 'return false;' id = 'add'><span class = 'glyphicon glyphicon-plus'></span></button>
											<button class = 'btn-sm btn-danger' name = "add" id = 'remove'><span class = 'glyphicon glyphicon-remove'></span></button>
										</td>
									</tr>
								</tbody>
							</table>


							
						<!-- 	<div class = 'form-group col-xs-4'>
								<button type="submit" name = 'add' class="btn-sm btn-default"><span class = 'glyphicon glyphicon-plus-sign'></span> Add Item</button>
							</div> -->

						
							<!-- End of form -->
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							<button type="submit" class="btn btn-primary">Save changes</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<!-- End of adding an item -->


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
								
				echo "<script>$('#search').val('".$_POST['search']."')</script>"; 

				$query1 = "MATCH (n:FOOD) return max(n.numImages) as maxImg"; 
				$results = $client->run($query1);

				foreach ($results->getRecords() as $result) {
					$query = "MATCH (n:FOOD)-[:HAS_CATEGORY]-(c) WHERE toLower(n.name) CONTAINS toLower('".$_POST['search']."') return ID(n) as id, n.name as name, c.name as category, n.description as description, n.price as price, n.numImages as num,"; 

					for ($i=1; $i <= $result->value('maxImg'); $i++) { 
						if($i == $result->value('maxImg'))
							$query .= "n.image".$i; 	
						else
							$query .= "n.image".$i.",";
					}										
				}				
				$res = $client->run($query); 
			}

			else{
				$query1 = "MATCH (n:FOOD) return max(n.numImages) as s"; 
				$results = $client->run($query1); 	
				foreach ($results->getRecords() as $result) {
				$query = "MATCH (n:FOOD)-[:HAS_CATEGORY]-(c) return ID(n) as id, n.name as name, c.name as category, n.description as description, n.price as price, n.numImages as num,"; 					
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


		<!-- Menu table -->
		<div class="container-fluid">
			
			<table class="table table-hover">
				<thead>
					<tr>
						<th>Name</th>
						<th>Description</th>
						<th>Price</th>
						<th>Images</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						$id = 0;
						foreach ($res->getRecords() as $result) {
							echo "<tr >";
								echo "<td style = 'width:20%; padding: 2em 1em 2em 1em;'>".$result->value('name');
								echo "</td>";	
								echo "<td style = 'width:20em;'>".$result->value('description')."</td>";	
								echo "<td>$ ".$result->value('price')."</td>";
								echo "<td>";
									echo "<div class = 'images'>";
									// VIEW IMAGES
									for ($i=1; $i <= $result->value('num'); $i++) { 
										echo "<img src = '".$result->value('n.image'.$i)."'>";
									}
									echo "</div>";

								echo "</td>";
								echo "<td>";									
									echo '<h4 class="btn btn-sm btn-default" data-toggle="modal" href="#modal-id'.(++$id).'"><span class = "glyphicon glyphicon-edit">'; 
									?>
										</span></h4>
										<?php echo '<div class="modal fade" id="modal-id'.($id).'">'; ?>
											<div class="modal-dialog">
												<div class="modal-content">
													<div class="modal-header">
														<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
														<h4 class="modal-title" >Edit</h4>
													</div>
													<div class="modal-body">
														<form action="" method="POST" class = 'form-group' enctype = "multipart/form-data">																		
															<br>


															<table class="table table-responsive" id = 'uploadDiv'>
																<thead>
																	<tr>
																		<th></th>
																		<th></th>
																		<th></th>
																	</tr>
																</thead>
																<tbody>
																	<tr>
																		<td>Item Name</td>
																		<td>
																			<input type="text" name="name" id="name" class="form-control" value="<?php echo $result->value('name'); ?>" required="required" title="">
																			<input type="hidden" name="oldName" id="name" class="form-control" value="<?php echo $result->value('name'); ?>" required="required" title="">
																		</td>
																		<td></td>
																	</tr>
																	<tr>
																		<td>Item Description</td>
																		<td>
																			<textarea name="description" id="desc" class="form-control" rows="10"><?php echo $result->value('description'); ?></textarea>					
																		</td>					
																		<td></td>	
																	</tr>
																	<tr>
																		<td>Category</td>
																		<td>
																			<select name="category" id="input" class="form-control">
																				<?php 
																					$categories = $client->run("MATCH (n:CATEGORY) return n.name as category"); 
																					foreach ($categories->getRecords() as $category) {																						
																						if ($result->value('category') == $category->value('category')) {
																							echo '<option value="'.$category->value('category').'" selected>'.$category->value('category').'</option>';		
																						}
																						else{

																							echo '<option value="'.$category->value('category').'">'.$category->value('category').'</option>';		
																						}
																					}

																				?>								
																			</select>					
																		</td>					
																		<td></td>	
																	</tr>
																	<tr>
																		<td>Price</td>
																		<td>
																			<input type="number" step = "any" name="price" id="price" class="form-control" value="<?php echo $result->value('price'); ?>" required="required" placeholder = "$" title="">
																		</td>
																		<td></td>						
																	</tr>
																	<!-- <tr>
																		<td>Images</td>
																		<td>
																			<input type="file" name= "myImage1" class="form-control">									
																		</td>
																		<td>
																			<button class = 'btn-sm btn-default' onclick = 'return false;' id = 'add'><span class = 'glyphicon glyphicon-plus'></span></button>
																			<button class = 'btn-sm btn-danger' onclick = 'return false;' id = 'remove'><span class = 'glyphicon glyphicon-remove'></span></button>
																		</td>
																	</tr> -->
																</tbody>
															</table>

														
													</div>
													<div class="modal-footer">						<button type="button" class="btn btn-danger" onclick = "var a = confirm('Are you sure?'); if(a) window.location = 'menuopen.php?delete&id=<?php echo $result->value('id')?>'"><span class = 'glyphicon glyphicon-remove-sign'></span> Delete</button>								
														<button type="submit" name = 'edit' class="btn btn-primary"><span class = 'glyphicon glyphicon-save'></span> Save changes</button>
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
				</tbody>
			</table>
		</div>
		<!-- End of menu table -->
	</div>		
</body>
</html>
