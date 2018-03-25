<?php
	require 'essentials.php';
	loadLayout("", "");

	echo '<h4 class = "cart link pointer" data-toggle="popover" data-html = "true" title="Search" data-placement = "bottom" data-content = "
				
					<div class = "input-group">
						<input name = "itemName" onkeyup = "getData(this.value);" class = "form-control" type = "text">
					</div>	
					<hr>
					<p>Filter by</p>
					<div class = "input-group-sm">
						<select name="category" class="form-control">
							<option value = "all" selected>View All</option>'; 
						
							$res = $client->run("MATCH (n:CATEGORY) RETURN n.name as name");

							foreach($res->getRecords() as $val){

								echo "<option value = ".$val->value("name").">".$val->value("name")."</option>"; 
							}
						

	echo '</select>
					</div>	
					<br>
					<button onclick = "getData($()value);" class = "btn btn-primary btn-sm" type = "submit">Filter <span class = "glyphicon glyphicon-filter"></span></button>
			>"'; 
?>
			<span class = "glyphicon glyphicon-search"></span>			
		</h4>'; 	
