<?php
				include "includes/header.php";
				?>

				<a class="btn btn-primary" href="edit-stock_details.php?act=add"> <i class="glyphicon glyphicon-plus-sign"></i> Add New Stock_details</a>

				<h1>Stock details</h1>
				<p>This table includes <?php echo counting("stock_details", "id");?> Stock Details.</p>

				<table id="sorted" class="table table-striped table-bordered">
				<thead>
				<tr>
							<th>Comp ID</th>
			<th>Symbol</th>
			<th>Open</th>
			<th>Close</th>
			<th>Volume</th>

				<th class="not">Edit</th>
				<th class="not">Delete</th>
				</tr>
				</thead>

				<?php
				$stock_details = getAll("stock_details");
				if($stock_details) foreach ($stock_details as $stock_detailss):
					?>
					<tr>
		<td><?php echo $stock_detailss['Comp_ID']?></td>
		<td><?php echo $stock_detailss['Symbol']?></td>
		<td><?php echo $stock_detailss['Open']?></td>
		<td><?php echo $stock_detailss['Close']?></td>
		<td><?php echo $stock_detailss['Volume']?></td>


						<td><a href="edit-stock_details.php?act=edit&id=<?php echo $stock_detailss['Comp_ID']?>"><i class="glyphicon glyphicon-edit"></i></a></td>
						<td><a href="save.php?act=delete&id=<?php echo $stock_detailss['Comp_ID']?>&cat=stock_details" onclick="return navConfirm(this.href);"><i class="glyphicon glyphicon-trash"></i></a></td>
						</tr>
					<?php endforeach; ?>
					</table>
					<?php include "includes/footer.php";?>
				