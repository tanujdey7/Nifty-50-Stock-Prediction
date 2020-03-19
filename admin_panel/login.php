<?php
	include "includes/header.php";
?>

	<a class="btn btn-primary" href="edit-login.php?act=add"> <i class="glyphicon glyphicon-plus-sign"></i> Add New login</a>

				<h1>Login</h1>
				<p>This table includes <?php echo counting("login", "id");?> login.</p>

				<table id="sorted" class="table table-striped table-bordered">
				<thead>
				<tr>
					<th>User_ID</th>
					<th>Username</th>
					<th>Email</th>
					<th>Password</th>
					<th class="not">Edit</th>
					<th class="not">Delete</th>
					</tr>
				</thead>

				<?php
					$login = getAll("login");
					if($login) foreach ($login as $logins):
				?>
					<tr>
						<td><?php echo $logins['User_ID']?></td>
						<td><?php echo $logins['Username']?></td>
						<td><?php echo $logins['Email']?></td>
						<td><?php echo $logins['Password']?></td>
						<td><a href="edit-login.php?act=edit&id=<?php echo $logins['User_ID']?>"><i class="glyphicon glyphicon-edit"></i></a></td>
						<td><a href="save.php?act=delete&id=<?php echo $logins['User_ID']?>&cat=login" onclick="return navConfirm(this.href);"><i class="glyphicon glyphicon-trash"></i></a></td>
					</tr>
					<?php endforeach; ?>
					</table>
					<?php include "includes/footer.php";?>
				