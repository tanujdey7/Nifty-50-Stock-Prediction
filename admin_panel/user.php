<?php
				include "includes/header.php";
				?>

				<a class="btn btn-primary" href="edit-user.php?act=add"> <i class="glyphicon glyphicon-plus-sign"></i> Add New User</a>

				<h1>User</h1>
				<p>This table includes <?php echo counting("user", "id");?> user.</p>

				<table id="sorted" class="table table-striped table-bordered">
				<thead>
				<tr>
							<th>User ID</th>
			<th>First Name</th>
			<th>Last Name</th>
			<th>Age</th>
			<th>Email</th>
			<th>Username</th>
			<th>Password</th>
			<th>Img</th>

				<th class="not">Edit</th>
				<th class="not">Delete</th>
				</tr>
				</thead>

				<?php
				$user = getAll("user");
				if($user) foreach ($user as $users):
					?>
					<tr>
						<td><?php echo $users['User_ID']?></td>
						<td><?php echo $users['First_Name']?></td>
						<td><?php echo $users['Last_Name']?></td>
						<td><?php echo $users['Age']?></td>
						<td><?php echo $users['Email']?></td>
						<td><?php echo $users['Username']?></td>
						<td><?php echo $users['Password']?></td>
						<td><?php echo $users['img']?></td>
						<td><a href="edit-user.php?act=edit&id=<?php echo $users['User_ID']?>"><i class="glyphicon glyphicon-edit"></i></a></td>
						<td><a href="save.php?act=delete&id=<?php echo $users['User_ID']?>&cat=user" onclick="return navConfirm(this.href);"><i class="glyphicon glyphicon-trash"></i></a></td>
						</tr>
					<?php endforeach; ?>
					</table>
					<?php include "includes/footer.php";?>
				<?php echo $users['User_ID']?>