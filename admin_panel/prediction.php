<?php
				include "includes/header.php";
				?>

				<a class="btn btn-primary" href="edit-prediction.php?act=add"> <i class="glyphicon glyphicon-plus-sign"></i> Add New Prediction</a>

				<h1>Prediction</h1>
				<p>This table includes <?php echo counting("prediction", "id");?> prediction.</p>

				<table id="sorted" class="table table-striped table-bordered">
				<thead>
				<tr>
							<th>Prediction ID</th>
			<th>Comp ID</th>
			<th>User ID</th>
			<th>Prediction</th>
			<th>Date</th>
			<th>Open</th>
			<th>Close</th>
			<th>Shares Traded</th>
			<th>Turnover</th>

				<th class="not">Edit</th>
				<th class="not">Delete</th>
				</tr>
				</thead>

				<?php
				$prediction = getAll("prediction");
				if($prediction) foreach ($prediction as $predictions):
					?>
					<tr>
		<td><?php echo $predictions['Prediction_ID']?></td>
		<td><?php echo $predictions['Comp_ID']?></td>
		<td><?php echo $predictions['User_ID']?></td>
		<td><?php echo $predictions['Prediction']?></td>
		<td><?php echo $predictions['Date']?></td>
		<td><?php echo $predictions['Open']?></td>
		<td><?php echo $predictions['Close']?></td>
		<td><?php echo $predictions['Shares_Traded']?></td>
		<td><?php echo $predictions['Turnover']?></td>


						<td><a href="edit-prediction.php?act=edit&id=<?php echo $predictions['id']?>"><i class="glyphicon glyphicon-edit"></i></a></td>
						<td><a href="save.php?act=delete&id=<?php echo $predictions['id']?>&cat=prediction" onclick="return navConfirm(this.href);"><i class="glyphicon glyphicon-trash"></i></a></td>
						</tr>
					<?php endforeach; ?>
					</table>
					<?php include "includes/footer.php";?>
				