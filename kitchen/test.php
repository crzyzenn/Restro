<?php 

	if (isset($_POST['mark'])) {
		foreach ($_POST['itemName'] as $key => $value) {
			// $client->run("MATCH P = (N:TABLE)-[]-(C)-[X]-(D) WHERE TYPE(X) <> 'GENERATED_INVOICE' AND D.name = '".$value."' SET X.orderStatus = 'NA'"); 			
			echo $value . "<br>";
		}
		echo $_POST['table']; 

		// $_POST['table']; 
		// header('Location:index.php?marked'); 
		// echo "<script>$.notify('Sucessfully marked as done!', {autoHideDelay:2000, className:'sucess'})</script>";

	}
?>