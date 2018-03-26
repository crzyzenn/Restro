<?php 
	require 'essentials.php'; 	
	error_reporting(E_ERROR | E_PARSE);
?>
	
	<div id = 'right-container'>
		<?php		

		// When form data is sent
		if(isset($_POST['add'])){			
			try{
				$query = "CREATE (n:TABLE{id:'".$_POST['id']."', floor:".$_POST['floor']."})";
				$result = $client->run($query); 
				
				if($result){
					header('Location:tableopen.php?added');		
				}	
			}
			catch(Exception $e){
				header('Location:tableopen.php?error');	
			}
			
		}



	?>

		<form action="addTable.php?c" method="POST" class = 'form-group'>			
			<legend><h4 class = 'white'>Add Table</h4></legend>		
			<br>


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
						<td></td>						
					</tr>					
				</tbody>
			</table>


			
			<div class = 'form-group col-xs-4'>
				<button type="submit" name = 'add' class="btn-sm btn-default"><span class = 'glyphicon glyphicon-plus-sign'></span> Add Table</button>
			</div>

		</form>


	</div>		
</body>
</html>
