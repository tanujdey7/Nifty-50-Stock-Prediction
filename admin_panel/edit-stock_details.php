<?php
				include "includes/header.php";
				$data=[];

				$act = $_GET['act'];
				$col = 'Comp_ID';
				$id = $_GET['id'];
				if($act == "edit"){
					$stock_details = getById("stock_details", $id,$col);
				}
				?>

				<form method="post" action="save.php" enctype='multipart/form-data'>
					<fieldset>
						<legend class="hidden-first">Add New Stock_details</legend>
						<input name="cat" type="hidden" value="stock_details">
						<input name="id" type="hidden" value="<?=$id?>">
						<input name="act" type="hidden" value="<?=$act?>">
				
							<label>Comp ID</label>
							<input class="form-control" type="text" name="Comp_ID" value="<?=$id?>" /><br>
							
							<label>Symbol</label>
							<input class="form-control" type="text" name="Symbol" value="<?=$stock_details['Symbol']?>" /><br>
							
							<label>Open</label>
							<input class="form-control" type="text" name="Open" value="<?=$stock_details['Open']?>" /><br>
							
							<label>Close</label>
							<input class="form-control" type="text" name="Close" value="<?=$stock_details['Close']?>" /><br>
							
							<label>Volume</label>
							<input class="form-control" type="text" name="Volume" value="<?=$stock_details['Volume']?>" /><br>

							<label>Previous Close</label>
							<input class="form-control" type="text" name="prev_close" value="<?=$stock_details['prev_close']?>" /><br>
							<br>
					<input type="submit" value=" Save " class="btn btn-success">
					</form>
					<?php include "includes/footer.php";?>
				