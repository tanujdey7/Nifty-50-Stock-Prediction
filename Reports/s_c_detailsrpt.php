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
if (!isset($s_c_details_rpt))
	$s_c_details_rpt = new s_c_details_rpt();
if (isset($Page))
	$OldPage = $Page;
$Page = &$s_c_details_rpt;

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
<div id="ew-center" class="<?php echo $s_c_details_rpt->CenterContentClass ?>">
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
<div id="gmp_s_c_details" class="<?php if (IsResponsiveLayout()) { echo "table-responsive "; } ?>ew-grid-middle-panel">
<table class="<?php echo $Page->ReportTableClass ?>">
<thead>
	<!-- Table header -->
	<tr class="ew-table-header">
<?php if ($Page->Comp_ID->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="Comp_ID"><div class="s_c_details_Comp_ID"><span class="ew-table-header-caption"><?php echo $Page->Comp_ID->caption() ?></span></div></td>
<?php } else { ?>
	<td data-field="Comp_ID">
<?php if ($Page->sortUrl($Page->Comp_ID) == "") { ?>
		<div class="ew-table-header-btn s_c_details_Comp_ID">
			<span class="ew-table-header-caption"><?php echo $Page->Comp_ID->caption() ?></span>
		</div>
<?php } else { ?>
		<div class="ew-table-header-btn ew-pointer s_c_details_Comp_ID" onclick="ew.sort(event,'<?php echo $Page->sortUrl($Page->Comp_ID) ?>',0);">
			<span class="ew-table-header-caption"><?php echo $Page->Comp_ID->caption() ?></span>
			<span class="ew-table-header-sort"><?php if ($Page->Comp_ID->getSort() == "ASC") { ?><i class="fa fa-sort-up"></i><?php } elseif ($Page->Comp_ID->getSort() == "DESC") { ?><i class="fa fa-sort-down"></i><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->Comp_Name->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="Comp_Name"><div class="s_c_details_Comp_Name"><span class="ew-table-header-caption"><?php echo $Page->Comp_Name->caption() ?></span></div></td>
<?php } else { ?>
	<td data-field="Comp_Name">
<?php if ($Page->sortUrl($Page->Comp_Name) == "") { ?>
		<div class="ew-table-header-btn s_c_details_Comp_Name">
			<span class="ew-table-header-caption"><?php echo $Page->Comp_Name->caption() ?></span>
		</div>
<?php } else { ?>
		<div class="ew-table-header-btn ew-pointer s_c_details_Comp_Name" onclick="ew.sort(event,'<?php echo $Page->sortUrl($Page->Comp_Name) ?>',0);">
			<span class="ew-table-header-caption"><?php echo $Page->Comp_Name->caption() ?></span>
			<span class="ew-table-header-sort"><?php if ($Page->Comp_Name->getSort() == "ASC") { ?><i class="fa fa-sort-up"></i><?php } elseif ($Page->Comp_Name->getSort() == "DESC") { ?><i class="fa fa-sort-down"></i><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->Comp_Website->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="Comp_Website"><div class="s_c_details_Comp_Website"><span class="ew-table-header-caption"><?php echo $Page->Comp_Website->caption() ?></span></div></td>
<?php } else { ?>
	<td data-field="Comp_Website">
<?php if ($Page->sortUrl($Page->Comp_Website) == "") { ?>
		<div class="ew-table-header-btn s_c_details_Comp_Website">
			<span class="ew-table-header-caption"><?php echo $Page->Comp_Website->caption() ?></span>
		</div>
<?php } else { ?>
		<div class="ew-table-header-btn ew-pointer s_c_details_Comp_Website" onclick="ew.sort(event,'<?php echo $Page->sortUrl($Page->Comp_Website) ?>',0);">
			<span class="ew-table-header-caption"><?php echo $Page->Comp_Website->caption() ?></span>
			<span class="ew-table-header-sort"><?php if ($Page->Comp_Website->getSort() == "ASC") { ?><i class="fa fa-sort-up"></i><?php } elseif ($Page->Comp_Website->getSort() == "DESC") { ?><i class="fa fa-sort-down"></i><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->Headquaters->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="Headquaters"><div class="s_c_details_Headquaters"><span class="ew-table-header-caption"><?php echo $Page->Headquaters->caption() ?></span></div></td>
<?php } else { ?>
	<td data-field="Headquaters">
<?php if ($Page->sortUrl($Page->Headquaters) == "") { ?>
		<div class="ew-table-header-btn s_c_details_Headquaters">
			<span class="ew-table-header-caption"><?php echo $Page->Headquaters->caption() ?></span>
		</div>
<?php } else { ?>
		<div class="ew-table-header-btn ew-pointer s_c_details_Headquaters" onclick="ew.sort(event,'<?php echo $Page->sortUrl($Page->Headquaters) ?>',0);">
			<span class="ew-table-header-caption"><?php echo $Page->Headquaters->caption() ?></span>
			<span class="ew-table-header-sort"><?php if ($Page->Headquaters->getSort() == "ASC") { ?><i class="fa fa-sort-up"></i><?php } elseif ($Page->Headquaters->getSort() == "DESC") { ?><i class="fa fa-sort-down"></i><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->Founded->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="Founded"><div class="s_c_details_Founded"><span class="ew-table-header-caption"><?php echo $Page->Founded->caption() ?></span></div></td>
<?php } else { ?>
	<td data-field="Founded">
<?php if ($Page->sortUrl($Page->Founded) == "") { ?>
		<div class="ew-table-header-btn s_c_details_Founded">
			<span class="ew-table-header-caption"><?php echo $Page->Founded->caption() ?></span>
		</div>
<?php } else { ?>
		<div class="ew-table-header-btn ew-pointer s_c_details_Founded" onclick="ew.sort(event,'<?php echo $Page->sortUrl($Page->Founded) ?>',0);">
			<span class="ew-table-header-caption"><?php echo $Page->Founded->caption() ?></span>
			<span class="ew-table-header-sort"><?php if ($Page->Founded->getSort() == "ASC") { ?><i class="fa fa-sort-up"></i><?php } elseif ($Page->Founded->getSort() == "DESC") { ?><i class="fa fa-sort-down"></i><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->Industry->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="Industry"><div class="s_c_details_Industry"><span class="ew-table-header-caption"><?php echo $Page->Industry->caption() ?></span></div></td>
<?php } else { ?>
	<td data-field="Industry">
<?php if ($Page->sortUrl($Page->Industry) == "") { ?>
		<div class="ew-table-header-btn s_c_details_Industry">
			<span class="ew-table-header-caption"><?php echo $Page->Industry->caption() ?></span>
		</div>
<?php } else { ?>
		<div class="ew-table-header-btn ew-pointer s_c_details_Industry" onclick="ew.sort(event,'<?php echo $Page->sortUrl($Page->Industry) ?>',0);">
			<span class="ew-table-header-caption"><?php echo $Page->Industry->caption() ?></span>
			<span class="ew-table-header-sort"><?php if ($Page->Industry->getSort() == "ASC") { ?><i class="fa fa-sort-up"></i><?php } elseif ($Page->Industry->getSort() == "DESC") { ?><i class="fa fa-sort-down"></i><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->Symbol->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="Symbol"><div class="s_c_details_Symbol"><span class="ew-table-header-caption"><?php echo $Page->Symbol->caption() ?></span></div></td>
<?php } else { ?>
	<td data-field="Symbol">
<?php if ($Page->sortUrl($Page->Symbol) == "") { ?>
		<div class="ew-table-header-btn s_c_details_Symbol">
			<span class="ew-table-header-caption"><?php echo $Page->Symbol->caption() ?></span>
		</div>
<?php } else { ?>
		<div class="ew-table-header-btn ew-pointer s_c_details_Symbol" onclick="ew.sort(event,'<?php echo $Page->sortUrl($Page->Symbol) ?>',0);">
			<span class="ew-table-header-caption"><?php echo $Page->Symbol->caption() ?></span>
			<span class="ew-table-header-sort"><?php if ($Page->Symbol->getSort() == "ASC") { ?><i class="fa fa-sort-up"></i><?php } elseif ($Page->Symbol->getSort() == "DESC") { ?><i class="fa fa-sort-down"></i><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->Series->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="Series"><div class="s_c_details_Series"><span class="ew-table-header-caption"><?php echo $Page->Series->caption() ?></span></div></td>
<?php } else { ?>
	<td data-field="Series">
<?php if ($Page->sortUrl($Page->Series) == "") { ?>
		<div class="ew-table-header-btn s_c_details_Series">
			<span class="ew-table-header-caption"><?php echo $Page->Series->caption() ?></span>
		</div>
<?php } else { ?>
		<div class="ew-table-header-btn ew-pointer s_c_details_Series" onclick="ew.sort(event,'<?php echo $Page->sortUrl($Page->Series) ?>',0);">
			<span class="ew-table-header-caption"><?php echo $Page->Series->caption() ?></span>
			<span class="ew-table-header-sort"><?php if ($Page->Series->getSort() == "ASC") { ?><i class="fa fa-sort-up"></i><?php } elseif ($Page->Series->getSort() == "DESC") { ?><i class="fa fa-sort-down"></i><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->ISIN_Code->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="ISIN_Code"><div class="s_c_details_ISIN_Code"><span class="ew-table-header-caption"><?php echo $Page->ISIN_Code->caption() ?></span></div></td>
<?php } else { ?>
	<td data-field="ISIN_Code">
<?php if ($Page->sortUrl($Page->ISIN_Code) == "") { ?>
		<div class="ew-table-header-btn s_c_details_ISIN_Code">
			<span class="ew-table-header-caption"><?php echo $Page->ISIN_Code->caption() ?></span>
		</div>
<?php } else { ?>
		<div class="ew-table-header-btn ew-pointer s_c_details_ISIN_Code" onclick="ew.sort(event,'<?php echo $Page->sortUrl($Page->ISIN_Code) ?>',0);">
			<span class="ew-table-header-caption"><?php echo $Page->ISIN_Code->caption() ?></span>
			<span class="ew-table-header-sort"><?php if ($Page->ISIN_Code->getSort() == "ASC") { ?><i class="fa fa-sort-up"></i><?php } elseif ($Page->ISIN_Code->getSort() == "DESC") { ?><i class="fa fa-sort-down"></i><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->Img->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="Img"><div class="s_c_details_Img"><span class="ew-table-header-caption"><?php echo $Page->Img->caption() ?></span></div></td>
<?php } else { ?>
	<td data-field="Img">
<?php if ($Page->sortUrl($Page->Img) == "") { ?>
		<div class="ew-table-header-btn s_c_details_Img">
			<span class="ew-table-header-caption"><?php echo $Page->Img->caption() ?></span>
		</div>
<?php } else { ?>
		<div class="ew-table-header-btn ew-pointer s_c_details_Img" onclick="ew.sort(event,'<?php echo $Page->sortUrl($Page->Img) ?>',0);">
			<span class="ew-table-header-caption"><?php echo $Page->Img->caption() ?></span>
			<span class="ew-table-header-sort"><?php if ($Page->Img->getSort() == "ASC") { ?><i class="fa fa-sort-up"></i><?php } elseif ($Page->Img->getSort() == "DESC") { ?><i class="fa fa-sort-down"></i><?php } ?></span>
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
<?php if ($Page->Comp_Name->Visible) { ?>
		<td data-field="Comp_Name"<?php echo $Page->Comp_Name->cellAttributes() ?>>
<span<?php echo $Page->Comp_Name->viewAttributes() ?>><?php echo $Page->Comp_Name->getViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->Comp_Website->Visible) { ?>
		<td data-field="Comp_Website"<?php echo $Page->Comp_Website->cellAttributes() ?>>
<span<?php echo $Page->Comp_Website->viewAttributes() ?>><?php echo $Page->Comp_Website->getViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->Headquaters->Visible) { ?>
		<td data-field="Headquaters"<?php echo $Page->Headquaters->cellAttributes() ?>>
<span<?php echo $Page->Headquaters->viewAttributes() ?>><?php echo $Page->Headquaters->getViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->Founded->Visible) { ?>
		<td data-field="Founded"<?php echo $Page->Founded->cellAttributes() ?>>
<span<?php echo $Page->Founded->viewAttributes() ?>><?php echo $Page->Founded->getViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->Industry->Visible) { ?>
		<td data-field="Industry"<?php echo $Page->Industry->cellAttributes() ?>>
<span<?php echo $Page->Industry->viewAttributes() ?>><?php echo $Page->Industry->getViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->Symbol->Visible) { ?>
		<td data-field="Symbol"<?php echo $Page->Symbol->cellAttributes() ?>>
<span<?php echo $Page->Symbol->viewAttributes() ?>><?php echo $Page->Symbol->getViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->Series->Visible) { ?>
		<td data-field="Series"<?php echo $Page->Series->cellAttributes() ?>>
<span<?php echo $Page->Series->viewAttributes() ?>><?php echo $Page->Series->getViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->ISIN_Code->Visible) { ?>
		<td data-field="ISIN_Code"<?php echo $Page->ISIN_Code->cellAttributes() ?>>
<span<?php echo $Page->ISIN_Code->viewAttributes() ?>><?php echo $Page->ISIN_Code->getViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->Img->Visible) { ?>
		<td data-field="Img"<?php echo $Page->Img->cellAttributes() ?>>
<span<?php echo $Page->Img->viewAttributes() ?>><?php echo $Page->Img->getViewValue() ?></span></td>
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
<div id="gmp_s_c_details" class="<?php if (IsResponsiveLayout()) { echo "table-responsive "; } ?>ew-grid-middle-panel">
<table class="<?php echo $Page->ReportTableClass ?>">
<?php } ?>
<?php if ($Page->TotalGroups > 0 || FALSE) { // Show footer ?>
</table>
</div>
<?php if (!($Page->DrillDown && $Page->TotalGroups > 0)) { ?>
<div class="card-footer ew-grid-lower-panel">
<?php include "s_c_details_pager.php" ?>
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