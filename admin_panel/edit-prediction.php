<?php
				include "includes/header.php";
				$data=[];

				$act = $_GET['act'];
				if($act == "edit"){
					$id = $_GET['id'];
					$prediction = getById("prediction", $id);
				}
				?>

				<form method="post" action="save.php" enctype='multipart/form-data'>
					<fieldset>
						<legend class="hidden-first">Add New Prediction</legend>
						<input name="cat" type="hidden" value="prediction">
						<input name="id" type="hidden" value="<?=$id?>">
						<input name="act" type="hidden" value="<?=$act?>">
				
							<label>Comp ID</label>
							<input class="form-control" type="text" name="Comp_ID" value="<?=$prediction['Comp_ID']?>" /><br>
							
							<label>User ID</label>
							<input class="form-control" type="text" name="User_ID" value="<?=$prediction['User_ID']?>" /><br>
							
							<label>Prediction</label>
							<input class="form-control" type="text" name="Prediction" value="<?=$prediction['Prediction']?>" /><br>
							
							<label>Date</label>
							<input class="form-control" type="text" name="Date" value="<?=$prediction['Date']?>" /><br>
							
							<label>Open</label>
							<input class="form-control" type="text" name="Open" value="<?=$prediction['Open']?>" /><br>
							
							<label>Close</label>
							<input class="form-control" type="text" name="Close" value="<?=$prediction['Close']?>" /><br>
							
							<label>Shares Traded</label>
							<input class="form-control" type="text" name="Shares_Traded" value="<?=$prediction['Shares_Traded']?>" /><br>
							
							<label>Turnover</label>
							<input class="form-control" type="text" name="Turnover" value="<?=$prediction['Turnover']?>" /><br>
							<br>
					<input type="submit" value=" Save " class="btn btn-success">
					</form>
					<?php include "includes/footer.php";?>
				