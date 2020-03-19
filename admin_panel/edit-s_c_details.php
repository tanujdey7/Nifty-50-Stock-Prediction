<?php
				include "includes/header.php";
				$data=[];
				$col = 'Comp_ID';
				$act = $_GET['act'];
				$id = $_GET['id'];
				if($act == "edit"){
					$s_c_details = getById("s_c_details", $id,$col);
				}
				?>
				<form method="post" action="save.php" enctype='multipart/form-data'>
					<fieldset>
						<legend class="hidden-first">Edit Stock Company Details</legend>
						<input name="cat" type="hidden" value="s_c_details">
						<input name="id" type="hidden" value="<?=$id?>">
						<input name="act" type="hidden" value="<?=$act?>">
				
							<label>Company ID</label>
							<input class="form-control" type="text" name="Comp_ID" value="<?=$id?>" /><br>
							
							<label>Company Name</label>
							<input class="form-control" type="text" name="Comp_Name" value="<?=$s_c_details['Comp_Name']?>" /><br>
							
							<label>Company Website</label>
							<input class="form-control" type="text" name="Comp_Website" value="<?=$s_c_details['Comp_Website']?>" /><br>
							
							<label>Headquaters</label>
							<input class="form-control" type="text" name="Headquaters" value="<?=$s_c_details['Headquaters']?>" /><br>
							
							<label>Founded</label>
							<input class="form-control" type="text" name="Founded" value="<?=$s_c_details['Founded']?>" /><br>
							
							<label>Industry</label>
							<input class="form-control" type="text" name="Industry" value="<?=$s_c_details['Industry']?>" /><br>
							
							<label>Symbol</label>
							<input class="form-control" type="text" name="Symbol" value="<?=$s_c_details['Symbol']?>" /><br>
							
							<label>Series</label>
							<input class="form-control" type="text" name="Series" value="<?=$s_c_details['Series']?>" /><br>
							
							<label>ISIN Code</label>
							<input class="form-control" type="text" name="ISIN_Code" value="<?=$s_c_details['ISIN_Code']?>" /><br>
							
							<label>Img</label>
							<input class="form-control" type="text" name="Img" value="<?=$s_c_details['Img']?>" /><br>
							<br>
					<input type="submit" value=" Save " class="btn btn-success">
					</form>
					<?php include "includes/footer.php";?>
				