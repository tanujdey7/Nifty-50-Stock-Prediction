<?php
		include "includes/header.php";
		?>
		<table class="table table-striped">
		<tr>
		<th class="not">Table</th>
		<th class="not">Entries</th>
		</tr>
		
				<tr>
					<td><a href="login.php">Login</a></td>
					<td><?=counting("login", "id")?></td>
				</tr>
				<tr>
					<td><a href="prediction.php">Prediction</a></td>
					<td><?=counting("prediction", "id")?></td>
				</tr>
				
				<tr>
					<td><a href="s_c_details.php">Stock Company Details</a></td>
					<td><?=counting("s_c_details", "id")?></td>
				</tr>
				
				<tr>
					<td><a href="stock_details.php">Stock Details</a></td>
					<td><?=counting("stock_details", "id")?></td>
				</tr>
				
				<tr>
					<td><a href="user.php">User</a></td>
					<td><?=counting("user", "id")?></td>
				</tr>
				
				<tr>
					<td><a href="users.php">Users</a></td>
					<td><?=counting("users", "id")?></td>
				</tr>
				</table>
			<?php include "includes/footer.php";?>
			