<?php 
	require '../connect.php'; 	
	error_reporting(E_ERROR | E_PARSE);
?>
	
		<?php		
		// When form data is sent
		if(isset($_POST['add'])){			

			$query = 'CREATE (n:FOOD{name:"'.$_POST['name'].'", description:"'.$_POST['description'].'", price:'.$_POST["price"].'})';
			$result = $client->run($query); 

			// Bind category on the newly added food
			$query1 = 'MATCH (food:FOOD{name:"'.$_POST['name'].'"}), (category:CATEGORY{name:"'.$_POST['category'].'"}) CREATE UNIQUE (food)-[:HAS_CATEGORY]->(category)'; 
			$result1 = $client->run($query1); 

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
					header('Location:menuopen.php?error'); 
				    
				}

				else{					
					// IF FILES UPLOADED
					if (move_uploaded_file($_FILES["myImage".$a]["tmp_name"], $target_file)) {		echo "Here";	     	   
						
			     	   	// ADD IMAGE FILE NAME TO DATABASE
						$query = "MATCH (n:FOOD{name:'".$_POST['name']."'}) SET n.image".$a."='".$target_file."', n.numImages=".$a; 
			     	   	$result = $client->run($query); 			     	   
			     	   	header('Location:menuopen.php?added'); 
				    }
				}

				// Increment a
				$a++; 
			}		
		}
	?>