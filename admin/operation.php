<?php 
	
	require '../vendor/autoload.php'; 	

	// Establish connection to the database
	require_once '../vendor/autoload.php'; 
	use GraphAware\Neo4j\Client\ClientBuilder;
	$client = ClientBuilder::create()
		    ->addConnection('bolt', 'bolt://neo4j:root@localhost:7687')
		    ->build();

	// Front end check table id
	if (isset($_POST['table_id'])) {
		 $query = "MATCH (n:TABLE) WHERE n.id = ".$_POST['table_id']." RETURN n.id";
		 $results = $client->run($query); 
		 if(empty($results->getRecords())){
		 	alert("Incorrect Id"); 
		 }
		 
	}

	function alert($name){		
		echo "<script>alert('".$name."');</script>"; 
	}

?>