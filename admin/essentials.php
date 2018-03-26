<?php 
	require '../vendor/autoload.php'; 	

	// Establish connection to the database
	require_once '../vendor/autoload.php'; 
	use GraphAware\Neo4j\Client\ClientBuilder;
	$client = ClientBuilder::create()
		    ->addConnection('bolt', 'bolt://neo4j:root@localhost:7687')
		    ->build();
	function loadLayout($title, $active = 'Home'){
?>
<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title><?php echo $title; ?></title>
	<link rel = 'stylesheet' media="screen" href = '../bs/css/bootstrap.css'>
	<script src="../bs/js/jquery.js"></script>
	<script type="text/javascript" src = '../bs/js/bootstrap.js'></script>	
	<script type="text/javascript" src = '../bs/js/notify.js'></script>		

	<script type="text/javascript">
		
		$(document).ready(function(){
		    $('[data-toggle="tooltip"]').tooltip(); 

		    var a = 1; 
		    var b = 1; 
		    $("#add").on("click", function(){
				var uploadBar = "<tr><td></td><td><input type='file' id = 'uploadField' name='myImage" + (++a) + "' class='form-control'></td><td><button class = 'btn-sm btn-danger' onclick = 'return false;' id = 'remove'><span class = 'glyphicon glyphicon-remove'></span></button></td></div></tr>"; 
				$("#uploadDiv").append(uploadBar); 
			}); 


			$("#uploadDiv").on("click", "#remove", function(){
				a--; 
				b--; 
				var parent = $(this).closest('tr'); 
				parent.remove(); 				
			}); 
		});




	</script>
</head>
<body>		
	<header>
		<div id = 'bar'>
			<img src="../Images/home.jpg" style = 'width:10%;'>						
			

			<div id = 'logStatus'>
				Admin
				<a href = "#" class = 'gray' data-toggle = 'tooltip' data-placement = 'bottom' title = 'Log-out'><span class = 'glyphicon glyphicon-log-out'></span></a>
			</div>			
			

		</div>		
	</header>

	

	<div id = 'left-container'>		
		<ul class="myNav">
			<?php 
				if($active == 'Home'){
					echo "<a class = 'myLink' href='index.php'><li class = 'active'>Dashboard</li></a>"; 	
					echo "<a class = 'myLink' href='menuopen.php'><li>Menu Management</li></a>";
					echo "<a class = 'myLink' href='tableopen.php'><li>Table Management</li></a>"; 	
					echo "<a class = 'myLink' href='#'><li>Invoice Management</li></a>"; 
					echo "<a class = 'myLink' href='#'><li>Waitstaff Management</li></a>"; 
					echo "<a class = 'myLink' href='#'><li>Kitchen Management</li></a>";							
				}

				else if($active == 'Menu'){
					echo "<a class = 'myLink' href='index.php'><li>Dashboard</li></a>"; 	
					echo "<a class = 'myLink' href='menuopen.php'><li class = 'active'>Menu Management</li></a>"; 	
					echo "<a class = 'myLink' href='tableopen.php'><li>Table Management</li></a>"; 
					echo "<a class = 'myLink' href='#'><li>Invoice Management</li></a>"; 
					echo "<a class = 'myLink' href='#'><li>Waitstaff Management</li></a>"; 
					echo "<a class = 'myLink' href='#'><li>Kitchen Management</li></a>";			
				}

				else if($active == 'Table'){
					echo "<a class = 'myLink' href='index.php'><li>Dashboard</li></a>"; 	
					echo "<a class = 'myLink' href='menuopen.php'><li>Menu Management</li></a>"; 	
					echo "<a class = 'myLink' href='tableopen.php'><li class = 'active'>Table Management</li></a>"; 
					echo "<a class = 'myLink' href='#'><li>Invoice Management</li></a>"; 
					echo "<a class = 'myLink' href='#'><li>Waitstaff Management</li></a>"; 
					echo "<a class = 'myLink' href='#'><li>Kitchen Management</li></a>";			
				}

				else{
					echo "<a class = 'myLink' href='index.php'><li class = 'active'>Dashboard</li></a>"; 	
					echo "<a class = 'myLink' href='menuopen.php'><li>Menu Management</li></a>"; 
					echo "<a class = 'myLink' href='tableopen.php'><li>Table Management</li></a>"; 

					echo "<a class = 'myLink' href='#'><li>Invoice Management</li></a>"; 
					echo "<a class = 'myLink' href='#'><li>Waitstaff Management</li></a>"; 
					echo "<a class = 'myLink' href='#'><li>Kitchen Management</li></a>";			
				}
			?>
						
			
		</ul>				
	</div>

<?

}
?>