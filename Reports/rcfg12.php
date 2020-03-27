<?php

/**
 * PHP Report Maker 12 configuration file
 */
namespace PHPReportMaker12\project1;

// Use
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// Font
$FONT_NAME = "Verdana";

// External JavaScripts
// External StyleSheets
// Database connection info

$CONNECTIONS["DB"] = array("conn" => NULL, "id" => "DB", "type" => "MYSQL", "host" => "localhost", "port" => 3306, "user" => "root", "pass" => "", "db" => "predictor", "qs" => "`", "qe" => "`");
$CONNECTIONS[0] = &$CONNECTIONS["DB"];

// Language settings
$REPORT_LANGUAGE_FOLDER = $RELATIVE_PATH . "phprptlang/";
$LANGUAGE_FILE = [];
$LANGUAGE_FILE[] = ["en", "", "english.xml"];

// Chart
define(PROJECT_NAMESPACE . "CHART_WIDTH", 600);
define(PROJECT_NAMESPACE . "CHART_HEIGHT", 500);
define(PROJECT_NAMESPACE . "CHART_SHOW_BLANK_SERIES", FALSE); // Show blank series
define(PROJECT_NAMESPACE . "CHART_SHOW_ZERO_IN_STACK_CHART", FALSE); // Show zero in stack chart

// Drill down setting
define(PROJECT_NAMESPACE . "USE_DRILLDOWN_PANEL", TRUE); // Use popup panel for drill down

// Filter
define(PROJECT_NAMESPACE . "SHOW_CURRENT_FILTER", FALSE); // True to show current filter
define(PROJECT_NAMESPACE . "SHOW_DRILLDOWN_FILTER", TRUE); // True to show drill down filter

// Generate report settings
$REPORTS_LIST = [];
$ReportParameters = [];

// Save report on server for file output
define(PROJECT_NAMESPACE . "REPORT_SAVE_OUTPUT_ON_SERVER", FALSE); // Change to TRUE to save on server

// Table level constants
define(PROJECT_NAMESPACE . "TABLE_GROUP_PER_PAGE", "grpperpage");
define(PROJECT_NAMESPACE . "TABLE_START_GROUP", "start");
define(PROJECT_NAMESPACE . "TABLE_SORTCHART", "sortc"); // Table sort chart

// Row types
define(PROJECT_NAMESPACE . "ROWTYPE_DETAIL", 8); // Row type detail
define(PROJECT_NAMESPACE . "ROWTYPE_TOTAL", 9); // Row type group summary

// Row total types
define(PROJECT_NAMESPACE . "ROWTOTAL_GROUP", 1); // Page summary
define(PROJECT_NAMESPACE . "ROWTOTAL_PAGE", 2); // Page summary
define(PROJECT_NAMESPACE . "ROWTOTAL_GRAND", 3); // Grand summary

// Row total sub types
define(PROJECT_NAMESPACE . "ROWTOTAL_HEADER", 0); // Header
define(PROJECT_NAMESPACE . "ROWTOTAL_FOOTER", 1); // Footer
define(PROJECT_NAMESPACE . "ROWTOTAL_SUM", 2); // SUM
define(PROJECT_NAMESPACE . "ROWTOTAL_AVG", 3); // AVG
define(PROJECT_NAMESPACE . "ROWTOTAL_MIN", 4); // MIN
define(PROJECT_NAMESPACE . "ROWTOTAL_MAX", 5); // MAX
define(PROJECT_NAMESPACE . "ROWTOTAL_CNT", 6); // CNT

// Empty/Null/Not Null/Init/all values
define(PROJECT_NAMESPACE . "EMPTY_VALUE", "##empty##");
define(PROJECT_NAMESPACE . "INIT_VALUE", "##init##");
define(PROJECT_NAMESPACE . "ALL_VALUE", "##all##");

// Boolean values for ENUM('Y'/'N') or ENUM(1/0)
define(PROJECT_NAMESPACE . "TRUE_STRING", "'Y'");
define(PROJECT_NAMESPACE . "FALSE_STRING", "'N'");

// Email
define(PROJECT_NAMESPACE . "MAX_EMAIL_SENT_PERIOD", 20);
define(PROJECT_NAMESPACE . "EMAIL_WRITE_LOG", TRUE); // Write to log file
define(PROJECT_NAMESPACE . "EMAIL_LOG_SIZE_LIMIT", 255); // Email log field size limit
define(PROJECT_NAMESPACE . "EMAIL_WRITE_LOG_TO_DATABASE", FALSE); // Write email log to database
define(PROJECT_NAMESPACE . "EMAIL_LOG_TABLE_DBID", "DB"); // Email log table dbid
define(PROJECT_NAMESPACE . "EMAIL_LOG_TABLE_NAME", ""); // Email log table name
define(PROJECT_NAMESPACE . "EMAIL_LOG_FIELD_NAME_DATETIME", ""); // Email log DateTime field name
define(PROJECT_NAMESPACE . "EMAIL_LOG_FIELD_NAME_IP", ""); // Email log IP field name
define(PROJECT_NAMESPACE . "EMAIL_LOG_FIELD_NAME_SENDER", ""); // Email log Sender field name
define(PROJECT_NAMESPACE . "EMAIL_LOG_FIELD_NAME_RECIPIENT", ""); // Email log Recipient field name
define(PROJECT_NAMESPACE . "EMAIL_LOG_FIELD_NAME_SUBJECT", ""); // Email log Subject field name
define(PROJECT_NAMESPACE . "EMAIL_LOG_FIELD_NAME_MESSAGE", ""); // Email log Message field name

// Page break
define(PROJECT_NAMESPACE . "PAGE_BREAK_HTML", "<p style=\"page-break-after:always;\">&nbsp;</p>\r\n");

// Add Chart exporter API
$API_ACTIONS["exportchart"] = function(Request $request, Response &$response) {
	$class = PROJECT_NAMESPACE . 'ChartExporter';
	if (class_exists($class)) {
		$exporter = new $class();
		return $exporter->export();
	}
	return FALSE;
};

// Add report request API
$API_ACTIONS["generatereporturl"] = function(Request $request, Response &$response) {
	$class = PROJECT_NAMESPACE . 'ReportGenerator';
	if (class_exists($class)) {
		$req = new $class();
		return $req->getUrl();
	}
	return FALSE;
};
$API_ACTIONS["generatereport"] = function(Request $request, Response &$response) {
	$class = PROJECT_NAMESPACE . 'ReportGenerator';
	if (class_exists($class)) {
		$req = new $class();
		return $req->generate();
	}
	return FALSE;
};

// Export reports
$EXPORT_REPORT = [
	"email" => "exportEmail",
	"print" => "exportHtml",
	"html" => "exportHtml",
	"word" => "exportWord",
	"excel" => "exportExcel",
	"pdf" => "exportPdf"
];

// Full URL protocols ("http" or "https")
$FULL_URL_PROTOCOLS += [
	"export" => "", // export
	"genurl" => "" // generate url
];

// Comma separated values delimiter
$CSV_DELIMITER = ",";

// Float fields default decimal position
define(PROJECT_NAMESPACE . "DEFAULT_DECIMAL_PRECISION", 2);

// Use Custom Template in report
define(PROJECT_NAMESPACE . "USE_CUSTOM_TEMPLATE", TRUE);

// Language
$ReportLanguage = NULL;

// Report options
$ReportOptions = ["ReportTypes" => [], "UserNameList" => []];

// Dashboard report checking
$DashboardReport = FALSE;

// Drilldown panel
$DrillDownInPanel = FALSE;

// Chart
$Chart = NULL;
$DEFAULT_CHART_RENDERER = "";
$USE_FUSIONCHARTS_TRIAL = FALSE; // For Gannt and Candlestick only
$FUSIONCHARTS_TRIAL_PATH = "FusionChartsTrial/js/";
$USE_GOOGLE_CHARTS = FALSE; // Use Google charts

// Gantt chart
define(PROJECT_NAMESPACE . "GANTT_INTERVAL_YEAR", 5);
define(PROJECT_NAMESPACE . "GANTT_INTERVAL_QUARTER", 4);
define(PROJECT_NAMESPACE . "GANTT_INTERVAL_MONTH", 3);
define(PROJECT_NAMESPACE . "GANTT_INTERVAL_WEEK", 2);
define(PROJECT_NAMESPACE . "GANTT_INTERVAL_DAY", 1);
define(PROJECT_NAMESPACE . "GANTT_INTERVAL_NONE", 0);
define(PROJECT_NAMESPACE . "GANTT_WEEK_START", 1); // 0 (for Sunday) through 6 (for Saturday)
?>
<?php

// Word
$USE_PHPWORD = FALSE;
$WORD_MAX_IMAGE_WIDTH = 400; // Portrait
$WORD_MAX_IMAGE_HEIGHT = 550; // Portrait
?>
<?php

// Excel
$USE_PHPEXCEL = FALSE;
$EXCEL_MAX_IMAGE_WIDTH = 400; // Portrait
$EXCEL_MAX_IMAGE_HEIGHT = 550; // Portrait
?>
<?php

// PDF stylesheet
$PDF_STYLESHEET_FILENAME = "";

// Make sure width/height not larger than page width/height or "infinite table loop" error
$PDF_MAX_IMAGE_WIDTH = 650; // Portrait
$PDF_MAX_IMAGE_HEIGHT = 900; // Portrait
$PDF_IMAGE_SCALE_FACTOR = 1.53; // Scale factor
?>
<?php

// FusionCharts path (trial version)
$FUSIONCHARTS_PATH = "FusionChartsTrial/js/";
?>