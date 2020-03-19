<?php
				include "includes/header.php";
				$data=[];

				$act = $_GET['act'];
				$col = "User_ID";
				if($act == "edit"){
					$id = $_GET['id'];
					$login = getById("login", $id,$col);
				}
?>
				<form method="post" action="save.php" enctype='multipart/form-data'>
					<fieldset>
						<legend class="hidden-first">Add New Login</legend>
						<input name="cat" type="hidden" value="login">
						<input name="id" type="hidden" value="<?=$id?>">
						<input name="act" type="hidden" value="<?=$act?>">
						<input name="User_ID" type="hidden" value="<?php echo $id?>">
				
							<label>User ID</label>
							<input class="form-control" type="text" name="User_ID" value="<?=$login['User_ID']?>" /><br>
							
							<label>Username</label>
							<input class="form-control" type="text" name="Username" value="<?=$login['Username']?>" /><br>
							
							<label>Email</label>
							<input class="form-control" type="text" name="Email" value="<?=$login['Email']?>" /><br>
							
							<label>Password</label>
							<input class="form-control" type="text" name="Password" value="<?=$login['Password']?>" /><br>
							<br>
					<input type="submit" value=" Save " class="btn btn-success">
					</form>
					<?php include "includes/footer.php";?>
				