<?php 
	session_start(); 
	require '../connect.php'; 
	if (isset($_POST['username'])) {
		// Get username and password from database
		$userdata = $client->run("MATCH (n:ADMIN) return n.username, n.password")->getRecord(); 		

		if($userdata->value('n.username') == $_POST['username'] && $userdata->value('n.password') == $_POST['password']){
			echo "ok"; 	
			$_SESSION['admin_id'] = $_POST['username']; 
		}
		
	}
	else if (isset($_GET['logout'])) {
		session_destroy(); 
		echo $_SESSION['admin_id']; 
		header('Location:index.php'); 
	}
?>