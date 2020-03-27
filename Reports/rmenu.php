<?php
namespace PHPReportMaker12\project1;
?>
<?php
namespace PHPReportMaker12\project1;

// Menu Language
if ($Language && $Language->LanguageFolder == $LANGUAGE_FOLDER)
	$MenuLanguage = &$Language;
else
	$MenuLanguage = new Language();

// Navbar menu
$topMenu = new Menu("navbar", TRUE, TRUE);
echo $topMenu->toScript();

// Sidebar menu
$sideMenu = new Menu("menu", TRUE, FALSE);
$sideMenu->addMenuItem(1, "mi__login", $ReportLanguage->phrase("SimpleReportMenuItemPrefix") . $ReportLanguage->menuPhrase("1", "MenuText") . $ReportLanguage->phrase("SimpleReportMenuItemSuffix"), "_loginrpt.php", -1, "", TRUE, FALSE, FALSE, "", "", FALSE);
$sideMenu->addMenuItem(2, "mi_news", $ReportLanguage->phrase("SimpleReportMenuItemPrefix") . $ReportLanguage->menuPhrase("2", "MenuText") . $ReportLanguage->phrase("SimpleReportMenuItemSuffix"), "newsrpt.php", -1, "", TRUE, FALSE, FALSE, "", "", FALSE);
$sideMenu->addMenuItem(3, "mi_prediction", $ReportLanguage->phrase("SimpleReportMenuItemPrefix") . $ReportLanguage->menuPhrase("3", "MenuText") . $ReportLanguage->phrase("SimpleReportMenuItemSuffix"), "predictionrpt.php", -1, "", TRUE, FALSE, FALSE, "", "", FALSE);
$sideMenu->addMenuItem(4, "mi_s_c_details", $ReportLanguage->phrase("SimpleReportMenuItemPrefix") . $ReportLanguage->menuPhrase("4", "MenuText") . $ReportLanguage->phrase("SimpleReportMenuItemSuffix"), "s_c_detailsrpt.php", -1, "", TRUE, FALSE, FALSE, "", "", FALSE);
$sideMenu->addMenuItem(5, "mi_stock_details", $ReportLanguage->phrase("SimpleReportMenuItemPrefix") . $ReportLanguage->menuPhrase("5", "MenuText") . $ReportLanguage->phrase("SimpleReportMenuItemSuffix"), "stock_detailsrpt.php", -1, "", TRUE, FALSE, FALSE, "", "", FALSE);
$sideMenu->addMenuItem(6, "mi_user", $ReportLanguage->phrase("SimpleReportMenuItemPrefix") . $ReportLanguage->menuPhrase("6", "MenuText") . $ReportLanguage->phrase("SimpleReportMenuItemSuffix"), "userrpt.php", -1, "", TRUE, FALSE, FALSE, "", "", FALSE);
echo $sideMenu->toScript();
?>