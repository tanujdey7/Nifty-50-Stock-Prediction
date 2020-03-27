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
if (!isset($prediction_rpt))
	$prediction_rpt = new prediction_rpt();
if (isset($Page))
	$OldPage = $Page;
$Page = &$prediction_rpt;

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
<div id="ew-center" class="<?php echo $prediction_rpt->CenterContentClass ?>">
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
<div id="gmp_prediction" class="<?php if (IsResponsiveLayout()) { echo "table-responsive "; } ?>ew-grid-middle-panel">
<table class="<?php echo $Page->ReportTableClass ?>">
<thead>
	<!-- Table header -->
	<tr class="ew-table-header">
<?php if ($Page->Prediction_ID->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="Prediction_ID"><div class="prediction_Prediction_ID"><span class="ew-table-header-caption"><?php echo $Page->Prediction_ID->caption() ?></span></div></td>
<?php } else { ?>
	<td data-field="Prediction_ID">
<?php if ($Page->sortUrl($Page->Prediction_ID) == "") { ?>
		<div class="ew-table-header-btn prediction_Prediction_ID">
			<span class="ew-table-header-caption"><?php echo $Page->Prediction_ID->caption() ?></span>
		</div>
<?php } else { ?>
		<div class="ew-table-header-btn ew-pointer prediction_Prediction_ID" onclick="ew.sort(event,'<?php echo $Page->sortUrl($Page->Prediction_ID) ?>',0);">
			<span class="ew-table-header-caption"><?php echo $Page->Prediction_ID->caption() ?></span>
			<span class="ew-table-header-sort"><?php if ($Page->Prediction_ID->getSort() == "ASC") { ?><i class="fa fa-sort-up"></i><?php } elseif ($Page->Prediction_ID->getSort() == "DESC") { ?><i class="fa fa-sort-down"></i><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->Comp_ID->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="Comp_ID"><div class="prediction_Comp_ID"><span class="ew-table-header-caption"><?php echo $Page->Comp_ID->caption() ?></span></div></td>
<?php } else { ?>
	<td data-field="Comp_ID">
<?php if ($Page->sortUrl($Page->Comp_ID) == "") { ?>
		<div class="ew-table-header-btn prediction_Comp_ID">
			<span class="ew-table-header-caption"><?php echo $Page->Comp_ID->caption() ?></span>
		</div>
<?php } else { ?>
		<div class="ew-table-header-btn ew-pointer prediction_Comp_ID" onclick="ew.sort(event,'<?php echo $Page->sortUrl($Page->Comp_ID) ?>',0);">
			<span class="ew-table-header-caption"><?php echo $Page->Comp_ID->caption() ?></span>
			<span class="ew-table-header-sort"><?php if ($Page->Comp_ID->getSort() == "ASC") { ?><i class="fa fa-sort-up"></i><?php } elseif ($Page->Comp_ID->getSort() == "DESC") { ?><i class="fa fa-sort-down"></i><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->User_ID->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="User_ID"><div class="prediction_User_ID"><span class="ew-table-header-caption"><?php echo $Page->User_ID->caption() ?></span></div></td>
<?php } else { ?>
	<td data-field="User_ID">
<?php if ($Page->sortUrl($Page->User_ID) == "") { ?>
		<div class="ew-table-header-btn prediction_User_ID">
			<span class="ew-table-header-caption"><?php echo $Page->User_ID->caption() ?></span>
		</div>
<?php } else { ?>
		<div class="ew-table-header-btn ew-pointer prediction_User_ID" onclick="ew.sort(event,'<?php echo $Page->sortUrl($Page->User_ID) ?>',0);">
			<span class="ew-table-header-caption"><?php echo $Page->User_ID->caption() ?></span>
			<span class="ew-table-header-sort"><?php if ($Page->User_ID->getSort() == "ASC") { ?><i class="fa fa-sort-up"></i><?php } elseif ($Page->User_ID->getSort() == "DESC") { ?><i class="fa fa-sort-down"></i><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->Prediction->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="Prediction"><div class="prediction_Prediction"><span class="ew-table-header-caption"><?php echo $Page->Prediction->caption() ?></span></div></td>
<?php } else { ?>
	<td data-field="Prediction">
<?php if ($Page->sortUrl($Page->Prediction) == "") { ?>
		<div class="ew-table-header-btn prediction_Prediction">
			<span class="ew-table-header-caption"><?php echo $Page->Prediction->caption() ?></span>
		</div>
<?php } else { ?>
		<div class="ew-table-header-btn ew-pointer prediction_Prediction" onclick="ew.sort(event,'<?php echo $Page->sortUrl($Page->Prediction) ?>',0);">
			<span class="ew-table-header-caption"><?php echo $Page->Prediction->caption() ?></span>
			<span class="ew-table-header-sort"><?php if ($Page->Prediction->getSort() == "ASC") { ?><i class="fa fa-sort-up"></i><?php } elseif ($Page->Prediction->getSort() == "DESC") { ?><i class="fa fa-sort-down"></i><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->Date->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="Date"><div class="prediction_Date"><span class="ew-table-header-caption"><?php echo $Page->Date->caption() ?></span></div></td>
<?php } else { ?>
	<td data-field="Date">
<?php if ($Page->sortUrl($Page->Date) == "") { ?>
		<div class="ew-table-header-btn prediction_Date">
			<span class="ew-table-header-caption"><?php echo $Page->Date->caption() ?></span>
		</div>
<?php } else { ?>
		<div class="ew-table-header-btn ew-pointer prediction_Date" onclick="ew.sort(event,'<?php echo $Page->sortUrl($Page->Date) ?>',0);">
			<span class="ew-table-header-caption"><?php echo $Page->Date->caption() ?></span>
			<span class="ew-table-header-sort"><?php if ($Page->Date->getSort() == "ASC") { ?><i class="fa fa-sort-up"></i><?php } elseif ($Page->Date->getSort() == "DESC") { ?><i class="fa fa-sort-down"></i><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->Open->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="Open"><div class="prediction_Open"><span class="ew-table-header-caption"><?php echo $Page->Open->caption() ?></span></div></td>
<?php } else { ?>
	<td data-field="Open">
<?php if ($Page->sortUrl($Page->Open) == "") { ?>
		<div class="ew-table-header-btn prediction_Open">
			<span class="ew-table-header-caption"><?php echo $Page->Open->caption() ?></span>
		</div>
<?php } else { ?>
		<div class="ew-table-header-btn ew-pointer prediction_Open" onclick="ew.sort(event,'<?php echo $Page->sortUrl($Page->Open) ?>',0);">
			<span class="ew-table-header-caption"><?php echo $Page->Open->caption() ?></span>
			<span class="ew-table-header-sort"><?php if ($Page->Open->getSort() == "ASC") { ?><i class="fa fa-sort-up"></i><?php } elseif ($Page->Open->getSort() == "DESC") { ?><i class="fa fa-sort-down"></i><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->Close->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="Close"><div class="prediction_Close"><span class="ew-table-header-caption"><?php echo $Page->Close->caption() ?></span></div></td>
<?php } else { ?>
	<td data-field="Close">
<?php if ($Page->sortUrl($Page->Close) == "") { ?>
		<div class="ew-table-header-btn prediction_Close">
			<span class="ew-table-header-caption"><?php echo $Page->Close->caption() ?></span>
		</div>
<?php } else { ?>
		<div class="ew-table-header-btn ew-pointer prediction_Close" onclick="ew.sort(event,'<?php echo $Page->sortUrl($Page->Close) ?>',0);">
			<span class="ew-table-header-caption"><?php echo $Page->Close->caption() ?></span>
			<span class="ew-table-header-sort"><?php if ($Page->Close->getSort() == "ASC") { ?><i class="fa fa-sort-up"></i><?php } elseif ($Page->Close->getSort() == "DESC") { ?><i class="fa fa-sort-down"></i><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->Shares_Traded->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="Shares_Traded"><div class="prediction_Shares_Traded"><span class="ew-table-header-caption"><?php echo $Page->Shares_Traded->caption() ?></span></div></td>
<?php } else { ?>
	<td data-field="Shares_Traded">
<?php if ($Page->sortUrl($Page->Shares_Traded) == "") { ?>
		<div class="ew-table-header-btn prediction_Shares_Traded">
			<span class="ew-table-header-caption"><?php echo $Page->Shares_Traded->caption() ?></span>
		</div>
<?php } else { ?>
		<div class="ew-table-header-btn ew-pointer prediction_Shares_Traded" onclick="ew.sort(event,'<?php echo $Page->sortUrl($Page->Shares_Traded) ?>',0);">
			<span class="ew-table-header-caption"><?php echo $Page->Shares_Traded->caption() ?></span>
			<span class="ew-table-header-sort"><?php if ($Page->Shares_Traded->getSort() == "ASC") { ?><i class="fa fa-sort-up"></i><?php } elseif ($Page->Shares_Traded->getSort() == "DESC") { ?><i class="fa fa-sort-down"></i><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->Turnover->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="Turnover"><div class="prediction_Turnover"><span class="ew-table-header-caption"><?php echo $Page->Turnover->caption() ?></span></div></td>
<?php } else { ?>
	<td data-field="Turnover">
<?php if ($Page->sortUrl($Page->Turnover) == "") { ?>
		<div class="ew-table-header-btn prediction_Turnover">
			<span class="ew-table-header-caption"><?php echo $Page->Turnover->caption() ?></span>
		</div>
<?php } else { ?>
		<div class="ew-table-header-btn ew-pointer prediction_Turnover" onclick="ew.sort(event,'<?php echo $Page->sortUrl($Page->Turnover) ?>',0);">
			<span class="ew-table-header-caption"><?php echo $Page->Turnover->caption() ?></span>
			<span class="ew-table-header-sort"><?php if ($Page->Turnover->getSort() == "ASC") { ?><i class="fa fa-sort-up"></i><?php } elseif ($Page->Turnover->getSort() == "DESC") { ?><i class="fa fa-sort-down"></i><?php } ?></span>
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
<?php if ($Page->Prediction_ID->Visible) { ?>
		<td data-field="Prediction_ID"<?php echo $Page->Prediction_ID->cellAttributes() ?>>
<span<?php echo $Page->Prediction_ID->viewAttributes() ?>><?php echo $Page->Prediction_ID->getViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->Comp_ID->Visible) { ?>
		<td data-field="Comp_ID"<?php echo $Page->Comp_ID->cellAttributes() ?>>
<span<?php echo $Page->Comp_ID->viewAttributes() ?>><?php echo $Page->Comp_ID->getViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->User_ID->Visible) { ?>
		<td data-field="User_ID"<?php echo $Page->User_ID->cellAttributes() ?>>
<span<?php echo $Page->User_ID->viewAttributes() ?>><?php echo $Page->User_ID->getViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->Prediction->Visible) { ?>
		<td data-field="Prediction"<?php echo $Page->Prediction->cellAttributes() ?>>
<span<?php echo $Page->Prediction->viewAttributes() ?>><?php echo $Page->Prediction->getViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->Date->Visible) { ?>
		<td data-field="Date"<?php echo $Page->Date->cellAttributes() ?>>
<span<?php echo $Page->Date->viewAttributes() ?>><?php echo $Page->Date->getViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->Open->Visible) { ?>
		<td data-field="Open"<?php echo $Page->Open->cellAttributes() ?>>
<span<?php echo $Page->Open->viewAttributes() ?>><?php echo $Page->Open->getViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->Close->Visible) { ?>
		<td data-field="Close"<?php echo $Page->Close->cellAttributes() ?>>
<span<?php echo $Page->Close->viewAttributes() ?>><?php echo $Page->Close->getViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->Shares_Traded->Visible) { ?>
		<td data-field="Shares_Traded"<?php echo $Page->Shares_Traded->cellAttributes() ?>>
<span<?php echo $Page->Shares_Traded->viewAttributes() ?>><?php echo $Page->Shares_Traded->getViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->Turnover->Visible) { ?>
		<td data-field="Turnover"<?php echo $Page->Turnover->cellAttributes() ?>>
<span<?php echo $Page->Turnover->viewAttributes() ?>><?php echo $Page->Turnover->getViewValue() ?></span></td>
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
<div id="gmp_prediction" class="<?php if (IsResponsiveLayout()) { echo "table-responsive "; } ?>ew-grid-middle-panel">
<table class="<?php echo $Page->ReportTableClass ?>">
<?php } ?>
<?php if ($Page->TotalGroups > 0 || FALSE) { // Show footer ?>
</table>
</div>
<?php if (!($Page->DrillDown && $Page->TotalGroups > 0)) { ?>
<div class="card-footer ew-grid-lower-panel">
<?php include "prediction_pager.php" ?>
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