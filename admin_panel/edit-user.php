<?php
				include "includes/header.php";
				$data=[];

				$act = $_GET['act'];
				$col = "User_ID";
				if($act == "edit"){
					$id = $_GET['id'];
					$user = getById("user", $id,$col);
				}
?>
				<form method="post" action="save.php" enctype='multipart/form-data'>
					<fieldset>
						<legend class="hidden-first">Add New User</legend>
						<input name="cat" type="hidden" value="user">
						<input name="id" type="hidden" value="<?php echo $id?>">
						<input name="act" type="hidden" value="<?php echo $act?>">
						<input name="User_ID" type="hidden" value="<?php echo $id?>">
						
						<label>First Name</label>
							<input class="form-control" type="text" name="First_Name" value="<?php echo $user['First_Name']?>" /><br>
							
							<label>Last Name</label>
							<input class="form-control" type="text" name="Last_Name" value="<?=$user['Last_Name']?>" /><br>
							
							<label>Age</label>
							<input class="form-control" type="text" name="Age" value="<?=$user['Age']?>" /><br>
							
							<label>Email</label>
							<input class="form-control" type="text" name="Email" value="<?=$user['Email']?>" /><br>
							
							<label>Username</label>
							<input class="form-control" type="text" name="Username" value="<?=$user['Username']?>" /><br>
							
							<label>Password</label>
							<input class="form-control" type="text" name="Password" value="<?=$user['Password']?>" /><br>
							
							<label>Img</label>
							<input class="form-control" type="text" name="img" value="<?=$user['img']?>" /><br>
							<br>
					<input type="submit" value=" Save " class="btn btn-success">
					</form>
					<?php include "includes/footer.php";?>
				