<?php
namespace PHPReportMaker12\project1;
?>
<form name="ew-pager-form" class="form-inline ew-form ew-pager-form" action="<?php echo CurrentPageName() ?>">
<?php if (!isset($pager)) $pager = new PrevNextPager($Page->StartGroup, $Page->DisplayGroups, $Page->TotalGroups) ?>
<?php if ($pager->RecordCount > 0 && $pager->Visible) { ?>
<div class="ew-pager">
<span><?php echo $ReportLanguage->Phrase("Page") ?>&nbsp;</span>
<div class="ew-prev-next"><div class="input-group input-group-sm">
<div class="input-group-prepend">
<!--first page button-->
	<?php if ($pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default" title="<?php echo $ReportLanguage->phrase("PagerFirst") ?>" href="<?php echo CurrentPageName() ?>?start=<?php echo $pager->FirstButton->Start ?>"><i class="icon-first ew-icon"></i></a>
	<?php } else { ?>
	<a class="btn btn-default disabled" title="<?php echo $ReportLanguage->phrase("PagerFirst") ?>"><i class="icon-first ew-icon"></i></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default" title="<?php echo $ReportLanguage->phrase("PagerPrevious") ?>" href="<?php echo CurrentPageName() ?>?start=<?php echo $pager->PrevButton->Start ?>"><i class="icon-prev ew-icon"></i></a>
	<?php } else { ?>
	<a class="btn btn-default disabled" title="<?php echo $ReportLanguage->phrase("PagerPrevious") ?>"><i class="icon-prev ew-icon"></i></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control" type="text" name="<?php echo TABLE_PAGE_NO ?>" value="<?php echo $pager->CurrentPage ?>">
<div class="input-group-append">
<!--next page button-->
	<?php if ($pager->NextButton->Enabled) { ?>
	<a class="btn btn-default" title="<?php echo $ReportLanguage->phrase("PagerNext") ?>" href="<?php echo CurrentPageName() ?>?start=<?php echo $pager->NextButton->Start ?>"><i class="icon-next ew-icon"></i></a>
	<?php } else { ?>
	<a class="btn btn-default disabled" title="<?php echo $ReportLanguage->phrase("PagerNext") ?>"><i class="icon-next ew-icon"></i></a>
	<?php } ?>
<!--last page button-->
	<?php if ($pager->LastButton->Enabled) { ?>
	<a class="btn btn-default" title="<?php echo $ReportLanguage->phrase("PagerLast") ?>" href="<?php echo CurrentPageName() ?>?start=<?php echo $pager->LastButton->Start ?>"><i class="icon-last ew-icon"></i></a>
	<?php } else { ?>
	<a class="btn btn-default disabled" title="<?php echo $ReportLanguage->phrase("PagerLast") ?>"><i class="icon-last ew-icon"></i></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $ReportLanguage->Phrase("of") ?>&nbsp;<?php echo $pager->PageCount ?></span>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php if ($pager->RecordCount > 0) { ?>
<div class="ew-pager ew-rec">
	<span><?php echo $ReportLanguage->Phrase("Record") ?> <?php echo $pager->FromIndex ?> <?php echo $ReportLanguage->Phrase("To") ?> <?php echo $pager->ToIndex ?> <?php echo $ReportLanguage->Phrase("Of") ?> <?php echo $pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($Page->TotalGroups > 0 && !$DashboardReport) { ?>
<div class="ew-pager">
<input type="hidden" name="t" value="user">
<select name="<?php echo TABLE_GROUP_PER_PAGE; ?>" class="form-control form-control-sm ew-tooltip" onchange="this.form.submit();">
<option value="1"<?php if ($Page->DisplayGroups == 1) echo " selected" ?>>1</option>
<option value="2"<?php if ($Page->DisplayGroups == 2) echo " selected" ?>>2</option>
<option value="3"<?php if ($Page->DisplayGroups == 3) echo " selected" ?>>3</option>
<option value="4"<?php if ($Page->DisplayGroups == 4) echo " selected" ?>>4</option>
<option value="5"<?php if ($Page->DisplayGroups == 5) echo " selected" ?>>5</option>
<option value="10"<?php if ($Page->DisplayGroups == 10) echo " selected" ?>>10</option>
<option value="20"<?php if ($Page->DisplayGroups == 20) echo " selected" ?>>20</option>
<option value="50"<?php if ($Page->DisplayGroups == 50) echo " selected" ?>>50</option>
<option value="ALL"<?php if ($Page->getGroupPerPage() == -1) echo " selected" ?>><?php echo $ReportLanguage->Phrase("AllRecords") ?></option>
</select>
</div>
<?php } ?>
</form>
