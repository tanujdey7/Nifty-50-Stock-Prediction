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
if (!isset($stock_details_rpt))
	$stock_details_rpt = new stock_details_rpt();
if (isset($Page))
	$OldPage = $Page;
$Page = &$stock_details_rpt;

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
<div id="ew-center" class="<?php echo $stock_details_rpt->CenterContentClass ?>">
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
<div id="gmp_stock_details" class="<?php if (IsResponsiveLayout()) { echo "table-responsive "; } ?>ew-grid-middle-panel">
<table class="<?php echo $Page->ReportTableClass ?>">
<thead>
	<!-- Table header -->
	<tr class="ew-table-header">
<?php if ($Page->Comp_ID->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="Comp_ID"><div class="stock_details_Comp_ID"><span class="ew-table-header-caption"><?php echo $Page->Comp_ID->caption() ?></span></div></td>
<?php } else { ?>
	<td data-field="Comp_ID">
<?php if ($Page->sortUrl($Page->Comp_ID) == "") { ?>
		<div class="ew-table-header-btn stock_details_Comp_ID">
			<span class="ew-table-header-caption"><?php echo $Page->Comp_ID->caption() ?></span>
		</div>
<?php } else { ?>
		<div class="ew-table-header-btn ew-pointer stock_details_Comp_ID" onclick="ew.sort(event,'<?php echo $Page->sortUrl($Page->Comp_ID) ?>',0);">
			<span class="ew-table-header-caption"><?php echo $Page->Comp_ID->caption() ?></span>
			<span class="ew-table-header-sort"><?php if ($Page->Comp_ID->getSort() == "ASC") { ?><i class="fa fa-sort-up"></i><?php } elseif ($Page->Comp_ID->getSort() == "DESC") { ?><i class="fa fa-sort-down"></i><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->Symbol->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="Symbol"><div class="stock_details_Symbol"><span class="ew-table-header-caption"><?php echo $Page->Symbol->caption() ?></span></div></td>
<?php } else { ?>
	<td data-field="Symbol">
<?php if ($Page->sortUrl($Page->Symbol) == "") { ?>
		<div class="ew-table-header-btn stock_details_Symbol">
			<span class="ew-table-header-caption"><?php echo $Page->Symbol->caption() ?></span>
		</div>
<?php } else { ?>
		<div class="ew-table-header-btn ew-pointer stock_details_Symbol" onclick="ew.sort(event,'<?php echo $Page->sortUrl($Page->Symbol) ?>',0);">
			<span class="ew-table-header-caption"><?php echo $Page->Symbol->caption() ?></span>
			<span class="ew-table-header-sort"><?php if ($Page->Symbol->getSort() == "ASC") { ?><i class="fa fa-sort-up"></i><?php } elseif ($Page->Symbol->getSort() == "DESC") { ?><i class="fa fa-sort-down"></i><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->Open->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="Open"><div class="stock_details_Open"><span class="ew-table-header-caption"><?php echo $Page->Open->caption() ?></span></div></td>
<?php } else { ?>
	<td data-field="Open">
<?php if ($Page->sortUrl($Page->Open) == "") { ?>
		<div class="ew-table-header-btn stock_details_Open">
			<span class="ew-table-header-caption"><?php echo $Page->Open->caption() ?></span>
		</div>
<?php } else { ?>
		<div class="ew-table-header-btn ew-pointer stock_details_Open" onclick="ew.sort(event,'<?php echo $Page->sortUrl($Page->Open) ?>',0);">
			<span class="ew-table-header-caption"><?php echo $Page->Open->caption() ?></span>
			<span class="ew-table-header-sort"><?php if ($Page->Open->getSort() == "ASC") { ?><i class="fa fa-sort-up"></i><?php } elseif ($Page->Open->getSort() == "DESC") { ?><i class="fa fa-sort-down"></i><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->Close->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="Close"><div class="stock_details_Close"><span class="ew-table-header-caption"><?php echo $Page->Close->caption() ?></span></div></td>
<?php } else { ?>
	<td data-field="Close">
<?php if ($Page->sortUrl($Page->Close) == "") { ?>
		<div class="ew-table-header-btn stock_details_Close">
			<span class="ew-table-header-caption"><?php echo $Page->Close->caption() ?></span>
		</div>
<?php } else { ?>
		<div class="ew-table-header-btn ew-pointer stock_details_Close" onclick="ew.sort(event,'<?php echo $Page->sortUrl($Page->Close) ?>',0);">
			<span class="ew-table-header-caption"><?php echo $Page->Close->caption() ?></span>
			<span class="ew-table-header-sort"><?php if ($Page->Close->getSort() == "ASC") { ?><i class="fa fa-sort-up"></i><?php } elseif ($Page->Close->getSort() == "DESC") { ?><i class="fa fa-sort-down"></i><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->Volume->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="Volume"><div class="stock_details_Volume"><span class="ew-table-header-caption"><?php echo $Page->Volume->caption() ?></span></div></td>
<?php } else { ?>
	<td data-field="Volume">
<?php if ($Page->sortUrl($Page->Volume) == "") { ?>
		<div class="ew-table-header-btn stock_details_Volume">
			<span class="ew-table-header-caption"><?php echo $Page->Volume->caption() ?></span>
		</div>
<?php } else { ?>
		<div class="ew-table-header-btn ew-pointer stock_details_Volume" onclick="ew.sort(event,'<?php echo $Page->sortUrl($Page->Volume) ?>',0);">
			<span class="ew-table-header-caption"><?php echo $Page->Volume->caption() ?></span>
			<span class="ew-table-header-sort"><?php if ($Page->Volume->getSort() == "ASC") { ?><i class="fa fa-sort-up"></i><?php } elseif ($Page->Volume->getSort() == "DESC") { ?><i class="fa fa-sort-down"></i><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->prev_close->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="prev_close"><div class="stock_details_prev_close"><span class="ew-table-header-caption"><?php echo $Page->prev_close->caption() ?></span></div></td>
<?php } else { ?>
	<td data-field="prev_close">
<?php if ($Page->sortUrl($Page->prev_close) == "") { ?>
		<div class="ew-table-header-btn stock_details_prev_close">
			<span class="ew-table-header-caption"><?php echo $Page->prev_close->caption() ?></span>
		</div>
<?php } else { ?>
		<div class="ew-table-header-btn ew-pointer stock_details_prev_close" onclick="ew.sort(event,'<?php echo $Page->sortUrl($Page->prev_close) ?>',0);">
			<span class="ew-table-header-caption"><?php echo $Page->prev_close->caption() ?></span>
			<span class="ew-table-header-sort"><?php if ($Page->prev_close->getSort() == "ASC") { ?><i class="fa fa-sort-up"></i><?php } elseif ($Page->prev_close->getSort() == "DESC") { ?><i class="fa fa-sort-down"></i><?php } ?></span>
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
<?php if ($Page->Comp_ID->Visible) { ?>
		<td data-field="Comp_ID"<?php echo $Page->Comp_ID->cellAttributes() ?>>
<span<?php echo $Page->Comp_ID->viewAttributes() ?>><?php echo $Page->Comp_ID->getViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->Symbol->Visible) { ?>
		<td data-field="Symbol"<?php echo $Page->Symbol->cellAttributes() ?>>
<span<?php echo $Page->Symbol->viewAttributes() ?>><?php echo $Page->Symbol->getViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->Open->Visible) { ?>
		<td data-field="Open"<?php echo $Page->Open->cellAttributes() ?>>
<span<?php echo $Page->Open->viewAttributes() ?>><?php echo $Page->Open->getViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->Close->Visible) { ?>
		<td data-field="Close"<?php echo $Page->Close->cellAttributes() ?>>
<span<?php echo $Page->Close->viewAttributes() ?>><?php echo $Page->Close->getViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->Volume->Visible) { ?>
		<td data-field="Volume"<?php echo $Page->Volume->cellAttributes() ?>>
<span<?php echo $Page->Volume->viewAttributes() ?>><?php echo $Page->Volume->getViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->prev_close->Visible) { ?>
		<td data-field="prev_close"<?php echo $Page->prev_close->cellAttributes() ?>>
<span<?php echo $Page->prev_close->viewAttributes() ?>><?php echo $Page->prev_close->getViewValue() ?></span></td>
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
<div id="gmp_stock_details" class="<?php if (IsResponsiveLayout()) { echo "table-responsive "; } ?>ew-grid-middle-panel">
<table class="<?php echo $Page->ReportTableClass ?>">
<?php } ?>
<?php if ($Page->TotalGroups > 0 || FALSE) { // Show footer ?>
</table>
</div>
<?php if (!($Page->DrillDown && $Page->TotalGroups > 0)) { ?>
<div class="card-footer ew-grid-lower-panel">
<?php include "stock_details_pager.php" ?>
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