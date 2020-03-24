<?php
				include "includes/header.php";
				?>

				<a class="btn btn-primary" href="edit-s_c_details.php?act=add"> <i class="glyphicon glyphicon-plus-sign"></i> Add New Stock Company Details</a>

				<h1>Stock Company Details</h1>
				<p>This table includes <?php echo counting("s_c_details", "id");?> Stock Company Details.</p>

				<table id="sorted" class="table table-striped table-bordered">
				<thead>
				<tr>
					<th>Comp ID</th>
					<th>Comp Name</th>
					<th>Comp Website</th>
					<th>Headquaters</th>
					<th>Founded</th>
					<th>Industry</th>
					<th>Symbol</th>
					<th>Series</th>
					<th>ISIN Code</th>
					<th>Img</th>
					<th class="not">Edit</th>
					<th class="not">Delete</th>
				</tr>
				</thead>
				<?php
					$s_c_details = getAll("s_c_details");
					if($s_c_details) foreach ($s_c_details as $s_c_detailss):
				?>
				<tr>
					<td><?php echo $s_c_detailss['Comp_ID']?></td>
					<td><?php echo $s_c_detailss['Comp_Name']?></td>
					<td><?php echo $s_c_detailss['Comp_Website']?></td>
					<td><?php echo $s_c_detailss['Headquaters']?></td>
					<td><?php echo $s_c_detailss['Founded']?></td>
					<td><?php echo $s_c_detailss['Industry']?></td>
					<td><?php echo $s_c_detailss['Symbol']?></td>
					<td><?php echo $s_c_detailss['Series']?></td>
					<td><?php echo $s_c_detailss['ISIN_Code']?></td>
					<td><?php echo $s_c_detailss['Img']?></td>1
						<td><a href="edit-s_c_details.php?act=edit&id=<?php echo $s_c_detailss['Comp_ID']?>"><i class="glyphicon glyphicon-edit"></i></i></a></td>
						<td><a href="save.php?act=delete&id=<?php echo $s_c_detailss['Comp_ID']?>&cat=s_c_details" onclick="return navConfirm(this.href);"><i class="glyphicon glyphicon-trash"></i></a></td>
						</tr>
				<?php endforeach; ?>
					</table>
					<?php include "includes/footer.php";?>
				