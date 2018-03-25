<?php 
	require 'essentials.php'; 
	loadLayout('Test', 'About');
?>

<script type="text/javascript">
	function getData(a){
		var xmlhttp = new XMLHttpRequest(); 
		xmlhttp.onreadystatechange = function(){
			if (this.readyState == 4 & this.status == 200) {
				$('.new').html(this.responseText); 
				// console.log(this.responseText);
			}
		}; 
		xmlhttp.open("GET", "response.php?name="+a, true)
		xmlhttp.send(); 

	}

</script>


<div class="container-fluid">
	<form action="#" onclick = "return false;" method="POST" role="form">		

		<div class="form-group">			
			<input type="text" class="form-control" onkeyup="getData(this.value);" name = "name" id="search" placeholder="Search for">
		</div>
	</form>
</div>

<div class = 'new'></div>
