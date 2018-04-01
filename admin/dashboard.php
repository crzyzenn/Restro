<?php 
	require 'essentials.php'; 
	loadLayout("Restro | Admin - Home", "Home");


	$sales = $client->run('match (n:TABLE)-[x]-(y)-[f]-(z) where x.date <> "null" return x.date,sum(f.price) as sum')->getRecords();
	$popularItems = $client->run('match (n:TABLE)-[x]-(y)-[f]-(z) where x.date <> "null" return z.name, count(z.name) as name order by name desc')->getRecords(); 
?>
	<div id = 'right-container'>
		<h1>Total Sales</h1>
		

		<table class="table table-hover">
			<thead>
				<tr>
					<th>Date</th>
					<th>Total sold</th>
				</tr>
			</thead>
			<tbody>
				<?php 
					foreach ($sales as $value) {
						echo "<tr>";
							echo "<td>";
								echo $value->value('x.date');
							echo "</td>";
							echo "<td>";
								echo "$".$value->value('sum');
							echo "</td>";
						echo "</tr>";
					}
				?>
			</tbody>
		</table>		
		<br>
		<br>
		<h1>Most popular items</h1>
		<table class="table table-hover">
			<thead>
				<tr>
					<th><h3>Itemname</h3></th>
					<th>Total Units Sold</th>
				</tr>
			</thead>
			<tbody>
				<?php 
					foreach ($popularItems as $value) {
						echo "<tr>";
							echo "<td>";
								echo $value->value('z.name');
							echo "</td>";
							echo "<td>";
								echo $value->value('name');
							echo "</td>";
						echo "</tr>";
					}
				?>
			</tbody>
		</table>
	</div>		
</body>
</html>
