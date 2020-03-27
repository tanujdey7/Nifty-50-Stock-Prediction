<?php
namespace PHPReportMaker12\project1;
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $ReportLanguage->projectPhrase("BodyTitle") ?></title>
<script>
var RELATIVE_PATH = "<?php echo $RELATIVE_PATH ?>";
var isAbsoluteUrl = function(url) {
	var r = new RegExp('^(?:[a-z]+:)?//', 'i');
	return r.test(url);
}
var getScript = function(url) {
	if (!isAbsoluteUrl(url))
		url = RELATIVE_PATH + url;
	document.write("<" + "script src=\"" + url + "\"><" + "/script>");
}
var getCss = function(url) {
	if (!isAbsoluteUrl(url))
		url = RELATIVE_PATH + url;
	document.write("<link rel=\"stylesheet\" type=\"text/css\" href=\"" + url + "\">");
}
</script>
<?php if (@$ExportType == "" || @$ExportType == "print") { ?>
<script>
getCss("adminlte3/css/<?php echo CssFile("adminlte.css") ?>");
getCss("plugins/font-awesome/css/font-awesome.min.css"); // Optional font
<?php foreach ($STYLESHEET_FILES as $cssfile) { // External Stylesheets ?>
getCss("<?php echo $cssfile ?>");
<?php } ?>
<?php if (!@$DrillDownInPanel) { ?>
getCss("<?php echo CssFile(PROJECT_STYLESHEET_FILENAME) ?>");
<?php } ?>
</script>
<?php if (IsResponsiveLayout()) { ?>
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php } ?>
<?php } else { // Export ?>
<style type="text/css">
<?php
	$cssfile = (@$ExportType == "pdf") ? ($PDF_STYLESHEET_FILENAME ?: PROJECT_STYLESHEET_FILENAME) : PROJECT_STYLESHEET_FILENAME;
	if ($cssfile)
		echo file_get_contents($cssfile);
?>
</style>
<?php } ?>
<script>if (!window.jQuery) getScript("jquery/jquery-3.4.1.min.js");</script>
<script>
if (window.jQuery && !jQuery.colorbox) getCss("colorbox/colorbox.css");
if (window.jQuery && !window.jQuery.widget) getScript("jquery/jquery.ui.widget.js");
if (window.jQuery && !window.jQuery.localStorage) getScript("jquery/jquery.storageapi.min.js");
if (!window.moment) getScript("moment/moment.min.js");
getScript("adminlte3/js/adminlte.js");
getScript("bootstrap4/js/bootstrap.bundle.min.js");
if (window.jQuery && !window.jQuery.fn.slimScroll) getScript("plugins/slimScroll/jquery.slimscroll.min.js");
if (window.jQuery && !window.jQuery.fileDownload) getScript("jquery/jquery.fileDownload.min.js");
if (window.jQuery && !window.jQuery.fn.typeahead) getScript("phprptjs/typeahead.jquery.js");
</script>
<?php foreach ($JAVASCRIPT_FILES as $jsfile) { // External JavaScripts ?>
<script>getScript("<?php echo $jsfile ?>");</script>
<?php } ?>
<?php if (@$CustomExportType == "") { ?>
<script src="<?php echo $RELATIVE_PATH . $FUSIONCHARTS_PATH ?>fusioncharts.js"></script>
<script src="<?php echo $RELATIVE_PATH . $FUSIONCHARTS_PATH ?>themes/fusioncharts.theme.ocean.js"></script>
<script src="<?php echo $RELATIVE_PATH . $FUSIONCHARTS_PATH ?>themes/fusioncharts.theme.carbon.js"></script>
<script src="<?php echo $RELATIVE_PATH . $FUSIONCHARTS_PATH ?>themes/fusioncharts.theme.zune.js"></script>
<?php if ($USE_FUSIONCHARTS_TRIAL) { ?>
<script src="<?php echo $RELATIVE_PATH . $FUSIONCHARTS_TRIAL_PATH ?>fusioncharts.powercharts.js"></script>
<script src="<?php echo $RELATIVE_PATH . $FUSIONCHARTS_TRIAL_PATH ?>fusioncharts.gantt.js"></script>
<?php } ?>
<?php if (!$USE_FUSIONCHARTS_TRIAL || $USE_GOOGLE_CHARTS) { ?>
<script src="https://www.gstatic.com/charts/loader.js"></script>
<?php } ?>
<?php } ?>
<script>if (window.jQuery && !jQuery.colorbox) getScript("colorbox/jquery.colorbox-min.js");</script>
<script>if (window.jQuery && typeof MobileDetect === 'undefined') getScript("phprptjs/mobile-detect.min.js");</script>
<script>getScript("phprptjs/clipboard.min.js");</script>
<script>getScript("phprptjs/ewp15.js");</script>
<script>getScript("phprptjs/ewr12.js");</script>
<script>
jQuery.extend(ew, {
	LANGUAGE_ID: "<?php echo $CurrentLanguage ?>",
	DATE_SEPARATOR: "<?php echo $DATE_SEPARATOR ?>", // Date separator
	TIME_SEPARATOR: "<?php echo $TIME_SEPARATOR ?>", // Time separator
	DATE_FORMAT: "<?php echo $DATE_FORMAT ?>", // Default date format
	DATE_FORMAT_ID: <?php echo $DATE_FORMAT_ID ?>, // Default date format ID
	DATETIME_WITHOUT_SECONDS: <?php echo VarToJson(DATETIME_WITHOUT_SECONDS) ?>, // Date/Time without seconds
	DECIMAL_POINT: "<?php echo $DECIMAL_POINT ?>",
	THOUSANDS_SEP: "<?php echo $THOUSANDS_SEP ?>",
	SESSION_TIMEOUT: <?php echo (SESSION_TIMEOUT > 0) ? SessionTimeoutTime() : 0 ?>, // Session timeout time (seconds)
	SESSION_TIMEOUT_COUNTDOWN: <?php echo SESSION_TIMEOUT_COUNTDOWN ?>, // Count down time to session timeout (seconds)
	SESSION_KEEP_ALIVE_INTERVAL: <?php echo SESSION_KEEP_ALIVE_INTERVAL ?>, // Keep alive interval (seconds)
	SESSION_URL: RELATIVE_PATH + "", // Session URL
	IS_LOGGEDIN: <?php echo VarToJson(IsLoggedIn()) ?>, // Is logged in
	IS_AUTOLOGIN: <?php echo VarToJson(IsAutoLogin()) ?>, // Is logged in with option "Auto login until I logout explicitly"
	TIMEOUT_URL: RELATIVE_PATH + "index.php", // Timeout URL
	API_URL: "<?php echo $RELATIVE_PATH ?><?php echo API_URL ?>", // API file name
	API_ACTION_NAME: "<?php echo API_ACTION_NAME ?>", // API action name
	API_OBJECT_NAME: "<?php echo API_OBJECT_NAME ?>", // API object name
	API_FIELD_NAME: "<?php echo API_FIELD_NAME ?>", // API field name
	API_KEY_NAME: "<?php echo API_KEY_NAME ?>", // API key name
	API_LOGIN_ACTION: "<?php echo API_LOGIN_ACTION ?>", // API login action
	API_FILE_ACTION: "<?php echo API_FILE_ACTION ?>", // API file action
	API_SESSION_ACTION: "<?php echo API_SESSION_ACTION ?>", // API get session action
	API_LOOKUP_ACTION: "<?php echo API_LOOKUP_ACTION ?>", // API lookup action
	API_LOOKUP_TABLE: "<?php echo API_LOOKUP_TABLE ?>", // API lookup table name
	LOOKUP_FILTER_VALUE_SEPARATOR: "<?php echo LOOKUP_FILTER_VALUE_SEPARATOR ?>", // Lookup filter value separator
	AUTO_SUGGEST_MAX_ENTRIES: <?php echo AUTO_SUGGEST_MAX_ENTRIES ?>, // Auto-Suggest max entries
	DISABLE_BUTTON_ON_SUBMIT: true,
	IMAGE_FOLDER: "phprptimages/", // Image folder
	LOOKUP_FILTER_VALUE_SEPARATOR: "<?php echo LOOKUP_FILTER_VALUE_SEPARATOR ?>", // Lookup filter value separator
	AUTO_SUGGEST_MAX_ENTRIES: <?php echo AUTO_SUGGEST_MAX_ENTRIES ?>, // Auto-Suggest max entries
	USE_JAVASCRIPT_MESSAGE: false,
	PROJECT_STYLESHEET_FILENAME: "<?php echo PROJECT_STYLESHEET_FILENAME ?>", // Project style sheet
	PDF_STYLESHEET_FILENAME: "<?php echo $PDF_STYLESHEET_FILENAME ?: PROJECT_STYLESHEET_FILENAME ?>", // Export PDF style sheet
	ANTIFORGERY_TOKEN: "<?php echo $CurrentToken ?>",
	CSS_FLIP: <?php echo VarToJson($CSS_FLIP) ?>,
	LAZY_LOAD: <?php echo VarToJson($LAZY_LOAD) ?>,
	RESET_HEIGHT: <?php echo VarToJson($RESET_HEIGHT) ?>,
	DEBUG_ENABLED: <?php echo VarToJson(DEBUG_ENABLED) ?>,
	SEARCH_FILTER_OPTION: "<?php echo SEARCH_FILTER_OPTION ?>",
	OPTION_HTML_TEMPLATE: <?php echo JsonEncode($OPTION_HTML_TEMPLATE) ?>
});
</script>
<script>if (window.jQuery && !window.jQuery.views) getScript("phprptjs/jsrender.min.js");</script>
<script>
<?php echo $ReportLanguage->toJson(); ?>
ew.vars = <?php echo JsonEncode($CLIENT_VAR) ?>;
</script>
<script>getScript("phprptjs/rusrfn12.js");</script>
<script>

// Write your client script here, no need to add script tags.
</script>
<meta name="generator" content="PHP Report Maker v12.0.7">
</head>
<body class="<?php echo $BODY_CLASS ?>" dir="<?php echo ($CSS_FLIP) ? "rtl" : "ltr" ?>">
<?php if (@!$SkipHeaderFooter) { ?>
<div class="wrapper ew-layout">
	<!-- Main Header -->
	<?php include_once "rmenu.php" ?>
	<!-- Navbar -->
	<nav class="<?php echo $NAVBAR_CLASS ?>">
		<!-- Left navbar links -->
		<ul id="ew-navbar" class="navbar-nav">
			<li class="nav-item">
				<a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
			</li>
		</ul>
		<!-- Right navbar links -->
		<ul id="ew-navbar-right" class="navbar-nav ml-auto"></ul>
	</nav>
	<!-- /.navbar -->
	<!-- Main Sidebar Container -->
	<aside class="<?php echo $SIDEBAR_CLASS ?>">
		<!-- Brand Logo //** Note: Only licensed users are allowed to change the logo ** -->
		<a href="#" class="brand-link">
			<span class="brand-text">PHP Report Maker 12</span>
		</a>
		<!-- Sidebar -->
		<div class="sidebar">
			<!-- Sidebar Menu -->
			<nav id="ew-menu" class="mt-2"></nav>
			<!-- /.sidebar-menu -->
		</div>
		<!-- /.sidebar -->
	</aside>
	<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper<?php if ($DashboardReport) { ?> ew-dashboard<?php } ?>">
	<?php if (PAGE_TITLE_STYLE <> "None") { ?>
		<!-- Content Header (Page header) -->
		<div class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0 text-dark"><?php echo CurrentPageHeading() ?> <small class="text-muted"><?php echo CurrentPageSubheading() ?></small></h1>
				</div><!-- /.col -->
				<div class="col-sm-6">
					<?php Breadcrumb()->render() ?>
				</div><!-- /.col -->
				</div><!-- /.row -->
			</div><!-- /.container-fluid -->
		</div>
		<!-- /.content-header -->
	<?php } ?>
		<!-- Main content -->
		<section class="content">
		<div class="container-fluid">
<?php } ?>