<?php 
	require 'essentials.php'; 	
	error_reporting(E_ERROR | E_PARSE);
?>
	
	<div id = 'right-container'>
		<?php		

		// When form data is sent
		if(isset($_POST['add'])){			

			$query = 'CREATE (n:FOOD{name:"'.$_POST['name'].'", description:"'.$_POST['description'].'", price:'.$_POST["price"].', category:"'.$_POST['category'].'"})';
			$result = $client->run($query); 


			$a = 1; 
			while(($_FILES['myImage'.$a]['size'] != 0)){								

				$target_dir = "../Images/";
				$target_file = $target_dir . basename($_FILES["myImage".$a]["name"]);				
				$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

				// Allow certain file formats
				if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
				    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";				    				    
				    
				    // Put other fields intact
				    echo "<script>$(document).ready(function(){
						$('#name').val('".$_POST['name']."'); 
						$('#desc').val('".$_POST['description']."'); 
						$('#price').val('".$_POST['price']."'); 
					});
					</script>";
				    
				}
				else{
					// IF FILES UPLOADED
					if (move_uploaded_file($_FILES["myImage".$a]["tmp_name"], $target_file)) {			     	   
						echo "Item has been added";
			     	   // ADD IMAGE FILE NAME TO DATABASE
						$query = "MATCH (n:FOOD{name:'".$_POST['name']."'}) SET n.image".$a."='".$target_file."', n.numImages=".$a; 
			     	   $result = $client->run($query); 			     	   
				    }
				}
			     
				
				// Increment a
				$a++; 
			}



			// if($result){
			// 	echo "<script>alert('Successfully added!')</script>"; 
			// }			
		}



	?>

		<form action="addItem.php" method="POST" class = 'form-group' enctype = "multipart/form-data">			
			<legend><h4 class = 'white'>Add item</h4></legend>		
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
							<input type="text" name="name" id="name" class="form-control" value="" required="required" title="">
						</td>
						<td></td>
					</tr>
					<tr>
						<td>Item Description</td>
						<td>
							<textarea name="description" id="desc" class="form-control" rows="10" required="required"></textarea>					
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
							<input type="number" name="price" id="price" class="form-control" value="" required="required" placeholder = "$" title="">
						</td>
						<td></td>						
					</tr>
					<tr>
						<td>Images</td>
						<td>
							<input type="file" name= "myImage1" class="form-control">									
						</td>
						<td>
							<button class = 'btn-sm btn-default' onclick = 'return false;' id = 'add'><span class = 'glyphicon glyphicon-plus'></span></button>
							<button class = 'btn-sm btn-danger' onclick = 'return false;' id = 'remove'><span class = 'glyphicon glyphicon-remove'></span></button>
						</td>
					</tr>
				</tbody>
			</table>


			
			<div class = 'form-group col-xs-4'>
				<button type="submit" name = 'add' class="btn-sm btn-default"><span class = 'glyphicon glyphicon-plus-sign'></span> Add Item</button>
			</div>

		</form>


	</div>		
	<footer>
		
	</footer>
</body>
</html>
