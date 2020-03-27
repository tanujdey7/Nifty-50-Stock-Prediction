<?php
namespace PHPReportMaker12\project1;

// Session
if (session_status() !== PHP_SESSION_ACTIVE)
	session_start(); // Init session data

// Output buffering
ob_start();

// Autoload
include_once "rautoload.php";
?>
<?php

// Create page object
if (!isset($user_rpt))
	$user_rpt = new user_rpt();
if (isset($Page))
	$OldPage = $Page;
$Page = &$user_rpt;

// Run the page
$Page->run();

// Setup login status
SetClientVar("login", LoginStatus());
if (!$DashboardReport)
	WriteHeader(FALSE);

// Global Page Rendering event (in rusrfn*.php)
Page_Rendering();

// Page Rendering event
$Page->Page_Render();
?>
<?php if (!$DashboardReport) { ?>
<?php include_once "rheader.php" ?>
<?php } ?>
<script>
currentPageID = ew.PAGE_ID = "rpt"; // Page ID
</script>
<?php if (!$Page->DrillDown && !$DashboardReport) { ?>
<?php } ?>
<?php if (!$Page->DrillDown && !$DashboardReport) { ?>
<script>

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<a id="top"></a>
<?php if ($Page->Export == "" && !$DashboardReport) { ?>
<!-- Content Container -->
<div id="ew-container" class="container-fluid ew-container">
<?php } ?>
<?php if (ReportParam("showfilter") === TRUE) { ?>
<?php $Page->showFilterList(TRUE) ?>
<?php } ?>
<div class="btn-toolbar ew-toolbar">
<?php
if (!$Page->DrillDownInPanel) {
	$Page->ExportOptions->render("body");
	$Page->SearchOptions->render("body");
	$Page->FilterOptions->render("body");
	$Page->GenerateOptions->render("body");
}
?>
</div>
<?php $Page->showPageHeader(); ?>
<?php $Page->showMessage(); ?>
<?php if ($Page->Export == "" && !$DashboardReport) { ?>
<div class="row">
<?php } ?>
<?php if ($Page->Export == "" && !$DashboardReport) { ?>
<!-- Center Container - Report -->
<div id="ew-center" class="<?php echo $user_rpt->CenterContentClass ?>">
<?php } ?>
<!-- Summary Report begins -->
<div id="report_summary">
<?php

// Set the last group to display if not export all
if ($Page->ExportAll && $Page->Export <> "") {
	$Page->StopGroup = $Page->TotalGroups;
} else {
	$Page->StopGroup = $Page->StartGroup + $Page->DisplayGroups - 1;
}

// Stop group <= total number of groups
if (intval($Page->StopGroup) > intval($Page->TotalGroups))
	$Page->StopGroup = $Page->TotalGroups;
$Page->RecordCount = 0;
$Page->RecordIndex = 0;

// Get first row
if ($Page->TotalGroups > 0) {
	$Page->loadRowValues(TRUE);
	$Page->GroupCount = 1;
}
$Page->GroupIndexes = InitArray(2, -1);
$Page->GroupIndexes[0] = -1;
$Page->GroupIndexes[1] = $Page->StopGroup - $Page->StartGroup + 1;
while ($Page->Recordset && !$Page->Recordset->EOF && $Page->GroupCount <= $Page->DisplayGroups || $Page->ShowHeader) {

	// Show dummy header for custom template
	// Show header

	if ($Page->ShowHeader) {
?>
<?php if ($Page->Export == "word" || $Page->Export == "excel") { ?>
<div class="ew-grid"<?php echo $Page->ReportTableStyle ?>>
<?php } else { ?>
<div class="card ew-card ew-grid"<?php echo $Page->ReportTableStyle ?>>
<?php } ?>
<!-- Report grid (begin) -->
<div id="gmp_user" class="<?php if (IsResponsiveLayout()) { echo "table-responsive "; } ?>ew-grid-middle-panel">
<table class="<?php echo $Page->ReportTableClass ?>">
<thead>
	<!-- Table header -->
	<tr class="ew-table-header">
<?php if ($Page->User_ID->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="User_ID"><div class="user_User_ID"><span class="ew-table-header-caption"><?php echo $Page->User_ID->caption() ?></span></div></td>
<?php } else { ?>
	<td data-field="User_ID">
<?php if ($Page->sortUrl($Page->User_ID) == "") { ?>
		<div class="ew-table-header-btn user_User_ID">
			<span class="ew-table-header-caption"><?php echo $Page->User_ID->caption() ?></span>
		</div>
<?php } else { ?>
		<div class="ew-table-header-btn ew-pointer user_User_ID" onclick="ew.sort(event,'<?php echo $Page->sortUrl($Page->User_ID) ?>',0);">
			<span class="ew-table-header-caption"><?php echo $Page->User_ID->caption() ?></span>
			<span class="ew-table-header-sort"><?php if ($Page->User_ID->getSort() == "ASC") { ?><i class="fa fa-sort-up"></i><?php } elseif ($Page->User_ID->getSort() == "DESC") { ?><i class="fa fa-sort-down"></i><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->First_Name->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="First_Name"><div class="user_First_Name"><span class="ew-table-header-caption"><?php echo $Page->First_Name->caption() ?></span></div></td>
<?php } else { ?>
	<td data-field="First_Name">
<?php if ($Page->sortUrl($Page->First_Name) == "") { ?>
		<div class="ew-table-header-btn user_First_Name">
			<span class="ew-table-header-caption"><?php echo $Page->First_Name->caption() ?></span>
		</div>
<?php } else { ?>
		<div class="ew-table-header-btn ew-pointer user_First_Name" onclick="ew.sort(event,'<?php echo $Page->sortUrl($Page->First_Name) ?>',0);">
			<span class="ew-table-header-caption"><?php echo $Page->First_Name->caption() ?></span>
			<span class="ew-table-header-sort"><?php if ($Page->First_Name->getSort() == "ASC") { ?><i class="fa fa-sort-up"></i><?php } elseif ($Page->First_Name->getSort() == "DESC") { ?><i class="fa fa-sort-down"></i><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->Last_Name->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="Last_Name"><div class="user_Last_Name"><span class="ew-table-header-caption"><?php echo $Page->Last_Name->caption() ?></span></div></td>
<?php } else { ?>
	<td data-field="Last_Name">
<?php if ($Page->sortUrl($Page->Last_Name) == "") { ?>
		<div class="ew-table-header-btn user_Last_Name">
			<span class="ew-table-header-caption"><?php echo $Page->Last_Name->caption() ?></span>
		</div>
<?php } else { ?>
		<div class="ew-table-header-btn ew-pointer user_Last_Name" onclick="ew.sort(event,'<?php echo $Page->sortUrl($Page->Last_Name) ?>',0);">
			<span class="ew-table-header-caption"><?php echo $Page->Last_Name->caption() ?></span>
			<span class="ew-table-header-sort"><?php if ($Page->Last_Name->getSort() == "ASC") { ?><i class="fa fa-sort-up"></i><?php } elseif ($Page->Last_Name->getSort() == "DESC") { ?><i class="fa fa-sort-down"></i><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->Age->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="Age"><div class="user_Age"><span class="ew-table-header-caption"><?php echo $Page->Age->caption() ?></span></div></td>
<?php } else { ?>
	<td data-field="Age">
<?php if ($Page->sortUrl($Page->Age) == "") { ?>
		<div class="ew-table-header-btn user_Age">
			<span class="ew-table-header-caption"><?php echo $Page->Age->caption() ?></span>
		</div>
<?php } else { ?>
		<div class="ew-table-header-btn ew-pointer user_Age" onclick="ew.sort(event,'<?php echo $Page->sortUrl($Page->Age) ?>',0);">
			<span class="ew-table-header-caption"><?php echo $Page->Age->caption() ?></span>
			<span class="ew-table-header-sort"><?php if ($Page->Age->getSort() == "ASC") { ?><i class="fa fa-sort-up"></i><?php } elseif ($Page->Age->getSort() == "DESC") { ?><i class="fa fa-sort-down"></i><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->_Email->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="_Email"><div class="user__Email"><span class="ew-table-header-caption"><?php echo $Page->_Email->caption() ?></span></div></td>
<?php } else { ?>
	<td data-field="_Email">
<?php if ($Page->sortUrl($Page->_Email) == "") { ?>
		<div class="ew-table-header-btn user__Email">
			<span class="ew-table-header-caption"><?php echo $Page->_Email->caption() ?></span>
		</div>
<?php } else { ?>
		<div class="ew-table-header-btn ew-pointer user__Email" onclick="ew.sort(event,'<?php echo $Page->sortUrl($Page->_Email) ?>',0);">
			<span class="ew-table-header-caption"><?php echo $Page->_Email->caption() ?></span>
			<span class="ew-table-header-sort"><?php if ($Page->_Email->getSort() == "ASC") { ?><i class="fa fa-sort-up"></i><?php } elseif ($Page->_Email->getSort() == "DESC") { ?><i class="fa fa-sort-down"></i><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->Username->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="Username"><div class="user_Username"><span class="ew-table-header-caption"><?php echo $Page->Username->caption() ?></span></div></td>
<?php } else { ?>
	<td data-field="Username">
<?php if ($Page->sortUrl($Page->Username) == "") { ?>
		<div class="ew-table-header-btn user_Username">
			<span class="ew-table-header-caption"><?php echo $Page->Username->caption() ?></span>
		</div>
<?php } else { ?>
		<div class="ew-table-header-btn ew-pointer user_Username" onclick="ew.sort(event,'<?php echo $Page->sortUrl($Page->Username) ?>',0);">
			<span class="ew-table-header-caption"><?php echo $Page->Username->caption() ?></span>
			<span class="ew-table-header-sort"><?php if ($Page->Username->getSort() == "ASC") { ?><i class="fa fa-sort-up"></i><?php } elseif ($Page->Username->getSort() == "DESC") { ?><i class="fa fa-sort-down"></i><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->Password->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="Password"><div class="user_Password"><span class="ew-table-header-caption"><?php echo $Page->Password->caption() ?></span></div></td>
<?php } else { ?>
	<td data-field="Password">
<?php if ($Page->sortUrl($Page->Password) == "") { ?>
		<div class="ew-table-header-btn user_Password">
			<span class="ew-table-header-caption"><?php echo $Page->Password->caption() ?></span>
		</div>
<?php } else { ?>
		<div class="ew-table-header-btn ew-pointer user_Password" onclick="ew.sort(event,'<?php echo $Page->sortUrl($Page->Password) ?>',0);">
			<span class="ew-table-header-caption"><?php echo $Page->Password->caption() ?></span>
			<span class="ew-table-header-sort"><?php if ($Page->Password->getSort() == "ASC") { ?><i class="fa fa-sort-up"></i><?php } elseif ($Page->Password->getSort() == "DESC") { ?><i class="fa fa-sort-down"></i><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->img->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="img"><div class="user_img"><span class="ew-table-header-caption"><?php echo $Page->img->caption() ?></span></div></td>
<?php } else { ?>
	<td data-field="img">
<?php if ($Page->sortUrl($Page->img) == "") { ?>
		<div class="ew-table-header-btn user_img">
			<span class="ew-table-header-caption"><?php echo $Page->img->caption() ?></span>
		</div>
<?php } else { ?>
		<div class="ew-table-header-btn ew-pointer user_img" onclick="ew.sort(event,'<?php echo $Page->sortUrl($Page->img) ?>',0);">
			<span class="ew-table-header-caption"><?php echo $Page->img->caption() ?></span>
			<span class="ew-table-header-sort"><?php if ($Page->img->getSort() == "ASC") { ?><i class="fa fa-sort-up"></i><?php } elseif ($Page->img->getSort() == "DESC") { ?><i class="fa fa-sort-down"></i><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
	</tr>
</thead>
<tbody>
<?php
		if ($Page->TotalGroups == 0) break; // Show header only
		$Page->ShowHeader = FALSE;
	}
	$Page->RecordCount++;
	$Page->RecordIndex++;
?>
<?php

		// Render detail row
		$Page->resetAttributes();
		$Page->RowType = ROWTYPE_DETAIL;
		$Page->renderRow();
?>
	<tr<?php echo $Page->rowAttributes(); ?>>
<?php if ($Page->User_ID->Visible) { ?>
		<td data-field="User_ID"<?php echo $Page->User_ID->cellAttributes() ?>>
<span<?php echo $Page->User_ID->viewAttributes() ?>><?php echo $Page->User_ID->getViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->First_Name->Visible) { ?>
		<td data-field="First_Name"<?php echo $Page->First_Name->cellAttributes() ?>>
<span<?php echo $Page->First_Name->viewAttributes() ?>><?php echo $Page->First_Name->getViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->Last_Name->Visible) { ?>
		<td data-field="Last_Name"<?php echo $Page->Last_Name->cellAttributes() ?>>
<span<?php echo $Page->Last_Name->viewAttributes() ?>><?php echo $Page->Last_Name->getViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->Age->Visible) { ?>
		<td data-field="Age"<?php echo $Page->Age->cellAttributes() ?>>
<span<?php echo $Page->Age->viewAttributes() ?>><?php echo $Page->Age->getViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->_Email->Visible) { ?>
		<td data-field="_Email"<?php echo $Page->_Email->cellAttributes() ?>>
<span<?php echo $Page->_Email->viewAttributes() ?>><?php echo $Page->_Email->getViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->Username->Visible) { ?>
		<td data-field="Username"<?php echo $Page->Username->cellAttributes() ?>>
<span<?php echo $Page->Username->viewAttributes() ?>><?php echo $Page->Username->getViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->Password->Visible) { ?>
		<td data-field="Password"<?php echo $Page->Password->cellAttributes() ?>>
<span<?php echo $Page->Password->viewAttributes() ?>><?php echo $Page->Password->getViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->img->Visible) { ?>
		<td data-field="img"<?php echo $Page->img->cellAttributes() ?>>
<span<?php echo $Page->img->viewAttributes() ?>><?php echo $Page->img->getViewValue() ?></span></td>
<?php } ?>
	</tr>
<?php

		// Accumulate page summary
		$Page->accumulateSummary();

		// Get next record
		$Page->loadRowValues();
	$Page->GroupCount++;
} // End while
?>
<?php if ($Page->TotalGroups > 0) { ?>
</tbody>
<tfoot>
	</tfoot>
<?php } elseif (!$Page->ShowHeader && FALSE) { // No header displayed ?>
<?php if ($Page->Export == "word" || $Page->Export == "excel") { ?>
<div class="ew-grid"<?php echo $Page->ReportTableStyle ?>>
<?php } else { ?>
<div class="card ew-card ew-grid"<?php echo $Page->ReportTableStyle ?>>
<?php } ?>
<!-- Report grid (begin) -->
<div id="gmp_user" class="<?php if (IsResponsiveLayout()) { echo "table-responsive "; } ?>ew-grid-middle-panel">
<table class="<?php echo $Page->ReportTableClass ?>">
<?php } ?>
<?php if ($Page->TotalGroups > 0 || FALSE) { // Show footer ?>
</table>
</div>
<?php if (!($Page->DrillDown && $Page->TotalGroups > 0)) { ?>
<div class="card-footer ew-grid-lower-panel">
<?php include "user_pager.php" ?>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
</div>
<!-- Summary Report Ends -->
<?php if ($Page->Export == "" && !$DashboardReport) { ?>
</div>
<!-- /#ew-center -->
<?php } ?>
<?php if ($Page->Export == "" && !$DashboardReport) { ?>
</div>
<!-- /.row -->
<?php } ?>
<?php if ($Page->Export == "" && !$DashboardReport) { ?>
</div>
<!-- /.ew-container -->
<?php } ?>
<?php
$Page->showPageFooter();
if (DEBUG_ENABLED)
	echo GetDebugMessage();
?>
<?php

// Close recordsets
if ($Page->GroupRecordset)
	$Page->GroupRecordset->Close();
if ($Page->Recordset)
	$Page->Recordset->Close();
?>
<?php if (!$Page->DrillDown && !$DashboardReport) { ?>
<script>

// Write your table-specific startup script here
// console.log("page loaded");

</script>
<?php } ?>
<?php if (!$DashboardReport) { ?>
<?php include_once "rfooter.php" ?>
<?php } ?>
<?php
$Page->terminate();
if (isset($OldPage))
	$Page = $OldPage;
?>