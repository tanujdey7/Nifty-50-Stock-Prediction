<?php
namespace PHPReportMaker12\project1;

// Global user functions
// Filter for 'Last Month' (example)
function GetLastMonthFilter($FldExpression, $dbid) {
	$today = getdate();
	$lastmonth = mktime(0, 0, 0, $today['mon']-1, 1, $today['year']);
	$val = date("Y|m", $lastmonth);
	$wrk = $FldExpression . " BETWEEN " .
		QuotedValue(DateValue("month", $val, 1, $dbid), DATATYPE_DATE, $dbid) .
		" AND " .
		QuotedValue(DateValue("month", $val, 2, $dbid), DATATYPE_DATE, $dbid);
	return $wrk;
}

// Filter for 'Starts With A' (example)
function GetStartsWithAFilter($FldExpression, $dbid) {
	return $FldExpression . Like("'A%'", $dbid);
}
if (!function_exists(PROJECT_NAMESPACE . "Page_Loading")) {

	// Page Loading event
	function Page_Loading() {

		//echo "Page Loading";
	}
}
if (!function_exists(PROJECT_NAMESPACE . "Page_Rendering")) {

	// Page Rendering event
	function Page_Rendering() {

		//echo "Page Rendering";
	}
}
if (!function_exists(PROJECT_NAMESPACE . "Page_Unloaded")) {

	// Page Unloaded event
	function Page_Unloaded() {

		//echo "Page Unloaded";
	}
}
if (!function_exists(PROJECT_NAMESPACE . "PersonalData_Downloading")) {

	// Personal Data Downloading event
	function PersonalData_Downloading(&$row) {

		//echo "PersonalData Downloading";
	}
}
if (!function_exists(PROJECT_NAMESPACE . "PersonalData_Deleted")) {

	// Personal Data Deleted event
	function PersonalData_Deleted($row) {

		//echo "PersonalData Deleted";
	}
}
?>