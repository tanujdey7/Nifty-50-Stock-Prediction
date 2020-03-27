<?php
namespace PHPReportMaker12\project1;

/**
 * PHP Report Maker common classes and functions
 * (C) 2007-2019 e.World Technology Limited. All rights reserved.
*/

/**
 * Get Report DB helper (for backward compatibility)
 *
 * @param integer|string $dbid - DB ID
 * @return DbHelperBase
 */
function &ReportDbHelper($dbid = 0) {
	return DbHelper($dbid);
}

/**
 * Get report language object
 *
 * @return Lang Language object
 */
function &ReportLanguage() {
	return $GLOBALS["ReportLanguage"];
}

/**
 * Langauge class for reports
 */
class ReportLanguage extends Language
{

	// Constructor
	function __construct($langfolder = "", $langid = "")
	{
		global $REPORT_LANGUAGE_FOLDER;

		// Parent constuctor
		parent::__construct($langfolder ?: $REPORT_LANGUAGE_FOLDER, $langid);
	}

	// Get chart phrase
	public function chartPhrase($tblVar, $chtVar, $id)
	{
		return ConvertFromUtf8(@$this->Phrases["ew-language"]["project"]["table"][strtolower($tblVar)]["chart"][strtolower($chtVar)]["phrase"][strtolower($id)]["attr"]["value"]);
	}

	// Set chart phrase
	public function setChartPhrase($tblVar, $chtVar, $id, $value)
	{
		$this->Phrases["ew-language"]["project"]["table"][strtolower($tblVar)]["chart"][strtolower($chtVar)]["phrase"][strtolower($id)]["attr"]["value"] = $value;
	}

	// Language Load event
	function Language_Load() {

		// Example:
		//$this->setPhrase("MyID", "MyValue"); // Refer to language file for the actual phrase id
		//$this->setPhraseClass("MyID", "fa fa-xxx ew-icon"); // Refer to http://getbootstrap.com/components/#glyphicons for icon name

	}
}

/**
 * Report table class
 */
class ReportTable extends DbTableBase
{
	public $TableReportType;
	public $SourceTableIsCustomView = FALSE;
	public $ShowCurrentFilter = SHOW_CURRENT_FILTER;
	public $ShowDrillDownFilter = SHOW_DRILLDOWN_FILTER;
	public $UseDrillDownPanel = USE_DRILLDOWN_PANEL; // Use drill down panel
	public $UseCustomTemplate = USE_CUSTOM_TEMPLATE; // Use custom template in report
	public $FirstRowData = []; // First row data
	public $ExportChartPageBreak = TRUE; // Page break for chart when export
	public $PageBreakContent = PAGE_BREAK_HTML;
	public $RowTotalType; // Row total type
	public $RowTotalSubType; // Row total subtype
	public $RowGroupLevel; // Row group level

	// Session Group Per Page
	public function getGroupPerPage()
	{
		return @$_SESSION[PROJECT_NAME . "_" . $this->TableVar . "_grpperpage"];
	}
	public function setGroupPerPage($v)
	{
		@$_SESSION[PROJECT_NAME . "_" . $this->TableVar . "_grpperpage"] = $v;
	}

	// Session Start Group
	public function getStartGroup()
	{
		return @$_SESSION[PROJECT_NAME . "_" . $this->TableVar . "_start"];
	}
	public function setStartGroup($v)
	{
		@$_SESSION[PROJECT_NAME . "_" . $this->TableVar . "_start"] = $v;
	}

	// Session Order By
	public function getOrderBy()
	{
		return @$_SESSION[PROJECT_NAME . "_" . $this->TableVar . "_orderby"];
	}
	public function setOrderBy($v)
	{
		@$_SESSION[PROJECT_NAME . "_" . $this->TableVar . "_orderby"] = $v;
	}

	// Session Order By (for non-grouping fields)
	public function getDetailOrderBy()
	{
		return @$_SESSION[PROJECT_NAME . "_" . $this->TableVar . "_detailorderby"];
	}
	public function setDetailOrderBy($v)
	{
		@$_SESSION[PROJECT_NAME . "_" . $this->TableVar . "_detailorderby"] = $v;
	}

	// Reset attributes for table object
	public function resetAttributes()
	{
		$this->RowAttrs = [];
		foreach ($this->fields as $fld)
			$fld->resetAttributes();
	}

	// URL encode
	public function urlEncode($str)
	{
		return urlencode($str);
	}

	// Print
	public function raw($str)
	{
		return $str;
	}
}

/**
 * Class for crosstab
 */
class CrosstabTable extends ReportTable
{

	// Column field related
	public $ColumnFieldName;
	public $ColumnDateSelection = FALSE;
	public $ColumnDateType;

	// Summary fields
	public $SummaryFields = [];
	public $SummarySeparatorStyle = "unstyled";

	// Summary cells
	public $SummaryCellAttrs;
	public $SummaryViewAttrs;
	public $SummaryLinkAttrs;
	public $SummaryCurrentValues;
	public $SummaryViewValues;
	public $CurrentIndex = -1;

	// Summary cell attributes
	public function summaryCellAttributes($i)
	{
		$att = "";
		if (is_array($this->SummaryCellAttrs)) {
			if ($i >= 0 && $i < count($this->SummaryCellAttrs)) {
				$attrs = $this->SummaryCellAttrs[$i];
				if (is_array($attrs)) {
					foreach ($attrs as $k => $v) {
						if (trim($v) <> "")
							$att .= " " . $k . "=\"" . trim($v) . "\"";
					}
				}
			}
		}
		return $att;
	}

	// Summary view attributes
	public function summaryViewAttributes($i)
	{
		$att = "";
		if (is_array($this->SummaryViewAttrs)) {
			if ($i >= 0 && $i < count($this->SummaryViewAttrs)) {
				$attrs = $this->SummaryViewAttrs[$i];
				if (is_array($attrs)) {
					foreach ($attrs as $k => $v) {
						if (trim($v) <> "")
							$att .= " " . $k . "=\"" . trim($v) . "\"";
					}
				}
			}
		}
		return $att;
	}

	// Summary link attributes
	public function summaryLinkAttributes($i)
	{
		$att = "";
		if (is_array($this->SummaryLinkAttrs)) {
			if ($i >= 0 && $i < count($this->SummaryLinkAttrs)) {
				$attrs = $this->SummaryLinkAttrs[$i];
				if (is_array($attrs)) {
					foreach ($attrs as $k => $v) {
						if (trim($v) <> "") {
							$att .= " " . $k . "=\"" . trim($v) . "\"";
						}
					}
					if (@$attrs["onclick"] <> "" && @$attrs["href"] == "")
						$att .= " href=\"javascript:void(0);\"";
				}
			}
		}
		return $att;
	}

	// Render summary fields
	public function renderSummaryFields($idx)
	{
		global $ExportType, $CustomExportType, $USE_PHPEXCEL, $USE_PHPWORD;
		$html = "";
		$cnt = count($this->SummaryFields);
		for ($i = 0; $i < $cnt; $i++) {
			$smry = &$this->SummaryFields[$i];
			$vv = $smry->SummaryViewValues[$idx];
			if (@$smry->SummaryLinkAttrs[$idx]["onclick"] <> "" || @$smry->SummaryLinkAttrs[$idx]["href"] <> "") {
				$vv = "<a" . $smry->summaryLinkAttributes($idx) . ">" . $vv . "</a>";
			}
			$vv = "<span" . $smry->summaryViewAttributes($idx) . ">" . $vv . "</span>";
			if ($cnt > 0) {
				if ($ExportType == "" || $ExportType == "print" && $CustomExportType == "")
					$vv = "<li>" . $vv . "</li>";
				elseif ($ExportType == "excel" && $USE_PHPEXCEL || $ExportType == "word" && $USE_PHPWORD)
					$vv .= "    ";
				else
					$vv .= "<br>";
			}
			$html .= $vv;
		}
		if ($cnt > 0 && ($ExportType == "" || $ExportType == "print" && $CustomExportType == ""))
			$html = "<ul class=\"list-" . $this->SummarySeparatorStyle . " ew-crosstab-values\">" . $html . "</ul>";
		return $html;
	}

	// Render summary types
	public function renderSummaryCaptions($typ = "")
	{
		global $ReportLanguage, $ExportType, $CustomExportType, $USE_PHPEXCEL, $USE_PHPWORD;
		$html = "";
		$cnt = count($this->SummaryFields);
		if ($typ == "page") {
			return $ReportLanguage->phrase("RptPageSummary");
		} elseif ($typ == "grand") {
			return $ReportLanguage->phrase("RptGrandSummary");
		} else {
			for ($i = 0; $i < $cnt; $i++) {
				$smry = &$this->SummaryFields[$i];
				$st = $smry->SummaryCaption;
				$fld = $this->fields($smry->Name);
				$caption = $fld->caption();
				if ($caption <> "") $st = $caption . " (" . $st . ")";
				if ($cnt > 0) {
					if ($ExportType == "" || $ExportType == "print" && $CustomExportType == "")
						$st = "<li>" . $st . "</li>";
					elseif ($ExportType == "excel" && $USE_PHPEXCEL || $ExportType == "word" && $USE_PHPWORD)
						$st .= "    ";
					else
						$st .= "<br>";
				}
				$html .= $st;
			}
			if ($cnt > 0 && ($ExportType == "" || $ExportType == "print" && $CustomExportType == ""))
				$html = "<ul class=\"list-" . $this->SummarySeparatorStyle . " ew-crosstab-values\">" . $html . "</ul>";
			return $html;
		}
	}
}

/**
 * Summary field class
 */
class SummaryField
{
	public $Name; // Field name
	public $FieldVar; // Field variable name
	public $Expression; // Field expression (used in SQL)
	public $SummaryType;
	public $SummaryCaption;
	public $SummaryViewAttrs;
	public $SummaryLinkAttrs;
	public $SummaryCurrentValues;
	public $SummaryViewValues;
	public $SummaryCounts;
	public $SummaryValues;
	public $SummarySummaries;
	public $SummaryValueCounts;
	public $SummarySummaryCounts;
	public $SummaryInitValue;
	public $SummaryRowSummary;
	public $SummaryRowCount;

	// Constructor
	public function __construct($fldvar, $fldname, $fldexpression, $smrytype)
	{
		$this->FieldVar = $fldvar;
		$this->Name = $fldname;
		$this->Expression = $fldexpression;
		$this->SummaryType = $smrytype;
	}

	// Summary view attributes
	public function summaryViewAttributes($i)
	{
		$att = "";
		if (is_array($this->SummaryViewAttrs)) {
			if ($i >= 0 && $i < count($this->SummaryViewAttrs)) {
				$attrs = $this->SummaryViewAttrs[$i];
				if (is_array($attrs)) {
					foreach ($attrs as $k => $v) {
						if (trim($v) <> "")
							$att .= " " . $k . "=\"" . trim($v) . "\"";
					}
				}
			}
		}
		return $att;
	}

	// Summary link attributes
	public function summaryLinkAttributes($i)
	{
		$att = "";
		if (is_array($this->SummaryLinkAttrs)) {
			if ($i >= 0 && $i < count($this->SummaryLinkAttrs)) {
				$attrs = $this->SummaryLinkAttrs[$i];
				if (is_array($attrs)) {
					foreach ($attrs as $k => $v) {
						if (trim($v) <> "") {
							$att .= " " . $k . "=\"" . trim($v) . "\"";
						}
					}
					if (@$attrs["onclick"] <> "" && @$attrs["href"] == "")
						$att .= " href=\"javascript:void(0);\"";
				}
			}
		}
		return $att;
	}
}

/**
 * Report field class
 */
class ReportField extends DbField
{
	public $SumValue; // Sum
	public $AvgValue; // Average
	public $MinValue; // Minimum
	public $MaxValue; // Maximum
	public $CntValue; // Count
	public $SumViewValue; // Sum
	public $AvgViewValue; // Average
	public $MinViewValue; // Minimum
	public $MaxViewValue; // Maximum
	public $CntViewValue; // Count
	public $DrillDownUrl = ""; // Drill down URL
	public $CurrentFilter = ""; // Current filter in use
	public $GroupingFieldId = 0; // Grouping field id
	public $ShowGroupHeaderAsRow = FALSE; // Show grouping level as row
	public $DefaultDecimalPrecision = DEFAULT_DECIMAL_PRECISION;
	public $GroupByType; // Group By Type
	public $GroupInterval; // Group Interval
	public $GroupSql; // Group SQL
	public $GroupDbValues; // Group DB Values
	public $GroupSelectSql; // SELECT SQL to load Group DB Values
	public $GroupViewValue; // Group View Value
	public $GroupSummaryOldValue; // Group Summary Old Value
	public $GroupSummaryValue; // Group Summary Value
	public $GroupSummaryViewValue; // Group Summary View Value
	public $ValueList; // Value List
	public $SelectionList; // Selection List
	public $DefaultSelectionList; // Default Selection List
	public $AdvancedFilters; // Advanced Filters
	public $RangeFrom; // Range From
	public $RangeTo; // Range To
	public $DropDownList; // Dropdown List
	public $DropDownValue; // Dropdown Value
	public $DefaultDropDownValue; // Default Dropdown Value
	public $DateFilter; // Date Filter ("year"|"quarter"|"month"|"day"|"")
	public $LookupExpression = ""; // Lookup expression
	public $Delimiter = ""; // Field delimiter (e.g. comma) for delimiter separated value

	// Constructor
	public function __construct($tblvar, $tblname, $fldvar, $fldname, $fldexp, $fldtype, $flddtfmt = -1, $upload = FALSE, $fldviewtag="", $fldhtmltag="")
	{
		$this->TableVar = $tblvar;
		$this->TableName = $tblname;
		$this->FieldVar = $fldvar;
		$this->Param = substr($this->FieldVar, 2); // Remove "x_"
		$this->Name = $fldname;
		$this->Expression = $fldexp;
		$this->Type = $fldtype;
		$this->DataType = FieldDataType($fldtype);
		$this->DateTimeFormat = $flddtfmt;
		$this->AdvancedSearch = new AdvancedSearch($this->TableVar, $this->FieldVar);
		if ($upload)
			$this->Upload = new HttpUpload($this);
		$this->ViewTag = $fldviewtag;
		$this->HtmlTag = $fldhtmltag;
	}

	// Database value (override PHPMaker)
	public function setDbValue($v)
	{
		$this->OldValue = $this->DbValue; // Save DbValue to OldValue
		if ($this->Type == 131 || $this->Type == 139) // Convert adNumeric/adVarNumeric field
			$v = floatval($v);
		parent::setDbValue($v); // Call parent method
	}

	// Group value
	public function groupValue()
	{
		return $this->getGroupValue($this->CurrentValue);
	}

	// Group old value
	public function groupOldValue()
	{
		return $this->getGroupValue($this->OldValue);
	}

	// Get group value
	public function getGroupValue($v)
	{
		if ($this->GroupingFieldId == 1) {
			return $v;
		} else {
			return $this->getGroupValueBase($v);
		}
	}

	// Get group value base
	public function getGroupValueBase($v)
	{
		if (is_array($this->GroupDbValues)) {
			return @$this->GroupDbValues[$v];
		} elseif ($this->GroupByType <> "" && $this->GroupByType <> "n") {
			return GroupValue($this, $v);
		} else {
			return $v;
		}
	}

	// Field popup options
	public function popupOptions()
	{
		if ($this->Lookup !== NULL && is_array($this->Lookup->PopupOptions))
			return array_values($this->Lookup->PopupOptions);
		return [];
	}

	// Get select options HTML (override)
	public function selectOptionListHtml($name = "") {
		$html = parent::selectOptionListHtml($name);
		return str_replace(">" . INIT_VALUE . "</option>", "></option>", $html); // Do not show the INIT_VALUE as value
	}
}

// JavaScript for drill down
function DrillDownScript($url, $id, $hdr, $popover = TRUE, $objid = "", $event = TRUE) {
	if (trim($url) == "") {
		return "";
	} else {
		if ($popover) {
			$obj = ($objid == "") ? "this" : "'" . JsEncodeAttribute($objid) . "'";
			if ($event) {
				$wrkurl = preg_replace('/&(?!amp;)/', '&amp;', $url); // Replace & to &amp;
				return "ew.showDrillDown(event, " . $obj . ", '" . JsEncodeAttribute($wrkurl) . "', '" . JsEncodeAttribute($id) . "', '" . JsEncodeAttribute($hdr) . "'); return false;";
			} else {
				return "ew.showDrillDown(null, " . $obj . ", '" . JsEncodeAttribute($url) . "', '" . JsEncodeAttribute($id) . "', '" . JsEncodeAttribute($hdr) . "');";
			}
		} else {
			$wrkurl = str_replace("?d=1&", "?d=2&", $url); // Change d parameter to 2
			return "ew.redirect('" . JsEncodeAttribute($wrkurl) . "'); return false;";
		}
	}
}

/**
 * Chart parameter class
 */
class ChartParameter
{
	public $Key = "";
	public $Value = NULL;
	public $Output;

	// Constructor
	public function __construct($key, $value, $output = TRUE)
	{
		$this->Key = $key;
		$this->Value = $value;
		$this->Output = $output;
	}
}

/**
 * Chart class
 */
class DbChart
{
	public $Table; // Table object
	public $TableVar; // Retained for compatibility
	public $TableName; // Retained for compatibility
	public $Name; // Chart name
	public $ChartVar; // Chart variable name
	public $XFieldName; // Chart X Field name
	public $YFieldName; // Chart Y Field name
	public $Type; // Chart Type
	public $SeriesFieldName; // Chart Series Field name
	public $SeriesType; // Chart Series Type
	public $SeriesRenderAs = ""; // Chart Series renderAs
	public $SeriesYAxis = ""; // Chart Series Y Axis
	public $RunTimeSort = FALSE; // Chart run time sort
	public $SortType = 0; // Chart Sort Type
	public $SortSequence = ""; // Chart Sort Sequence
	public $SummaryType; // Chart Summary Type
	public $Width; // Chart Width
	public $Height; // Chart Height
	public $Align; // Chart Align
	public $DrillDownUrl = ""; // Chart drill down URL
	public $UseDrillDownPanel = USE_DRILLDOWN_PANEL; // Use drill down panel
	public $DefaultDecimalPrecision = DEFAULT_DECIMAL_PRECISION;
	public $SqlSelect;
	public $SqlWhere = "";
	public $SqlGroupBy;
	public $SqlOrderBy;
	public $XAxisDateFormat;
	public $NameDateFormat;
	public $SeriesDateType;
	public $SqlSelectSeries;
	public $SqlWhereSeries = "";
	public $SqlGroupBySeries;
	public $SqlOrderBySeries;
	public $ChartSeriesSql;
	public $ChartSql;
	public $PageBreak = TRUE; // Page break before/after chart
	public $PageBreakType = "before"; // "before" or "after"
	public $PageBreakContent = PAGE_BREAK_HTML; // Page break HTML
	public $DrillDownInPanel = FALSE;
	public $ScrollChart = FALSE;
	public $IsCustomTemplate = FALSE;
	public $ID;
	public $Parameters = [];
	public $Trends;
	public $Data;
	public $ViewData;
	public $Series;
	public $Caption = "";
	public $DataFormat = "json";
	protected $_dataLoaded = FALSE;

	/**
	 * Chart renderer class name
	 *
	 * @var string
	 */
	public $Renderer;

	/**
	 * Data source for renderer
	 *
	 * @var string XML or JSON (utf-8)
	 */
	public $DataSource = "";

	// Constructor
	public function __construct(&$tbl, $chartvar, $chartname, $xfld, $yfld, $type, $sfld, $stype, $smrytype, $width, $height, $align="")
	{
		global $DEFAULT_CHART_RENDERER;
		$this->Table = &$tbl;
		$this->TableVar = $tbl->TableVar; // For compatibility
		$this->TableName = $tbl->TableName; // For compatibility
		$this->ChartVar = $chartvar;
		$this->ChartName = $chartname;
		$this->ChartXFldName = $xfld;
		$this->ChartYFldName = $yfld;
		$this->Type = $type;
		$this->SeriesFieldName = $sfld;
		$this->SeriesType = $stype;
		$this->ChartSummaryType = $smrytype;
		$this->Width = $width;
		$this->Height = $height;
		$this->Align = $align;
		$this->ID = NULL;
		$this->Trends = NULL;
		$this->Data = NULL;
		$this->Series = NULL;
		if ($DEFAULT_CHART_RENDERER)
			$this->Renderer = $DEFAULT_CHART_RENDERER;
	}

	// Set chart caption
	public function setCaption($v)
	{
		$this->Caption = $v;
	}

	// Chart caption
	public function caption()
	{
		global $ReportLanguage;
		if ($this->Caption <> "")
			return $this->Caption;
		else
			return $ReportLanguage->chartPhrase($this->Table->TableVar, $this->ChartVar, "ChartCaption");
	}

	// X axis name
	public function xAxisName()
	{
		global $ReportLanguage;
		return $ReportLanguage->chartPhrase($this->Table->TableVar, $this->ChartVar, "ChartXAxisName");
	}

	// Y axis name
	public function yAxisName()
	{
		global $ReportLanguage;
		return $ReportLanguage->chartPhrase($this->Table->TableVar, $this->ChartVar, "ChartYAxisName");
	}

	// Primary axis name
	public function primaryYAxisName()
	{
		global $ReportLanguage;
		return $ReportLanguage->chartPhrase($this->Table->TableVar, $this->ChartVar, "ChartPYAxisName");
	}

	// Function SYAxisName
	public function secondaryYAxisName()
	{
		global $ReportLanguage;
		return $ReportLanguage->chartPhrase($this->Table->TableVar, $this->ChartVar, "ChartSYAxisName");
	}

	// Sort
	public function getSort()
	{
		return @$_SESSION[PROJECT_NAME . "_" . $this->Table->TableVar . "_" . TABLE_SORTCHART . "_" . $this->ChartVar];
	}
	public function setSort($v)
	{
		if (@$_SESSION[PROJECT_NAME . "_" . $this->Table->TableVar . "_" . TABLE_SORTCHART . "_" . $this->ChartVar] <> $v)
			$_SESSION[PROJECT_NAME . "_" . $this->Table->TableVar . "_" . TABLE_SORTCHART . "_" . $this->ChartVar] = $v;
	}

	// Set chart parameters
	public function setParameter($key, $value, $output)
	{
		$this->Parameters[$key] = new ChartParameter($key, $value, $output);
	}

	// Set chart parameters
	public function setParameters($parms)
	{
		if (is_array($parms)) {
			foreach ($parms as $parm)
				$this->Parameters[$parm->Key] = $parm;
		}
	}

	// Set up default chart parm
	public function setDefaultParameter($key, $value)
	{
		if (is_array($this->Parameters)) {
			$parm = $this->loadParameter($key);
			if ($parm === NULL) {
				$this->Parameters[$key] = new ChartParameter($key, $value, TRUE);
			} elseif ($parm == "") {
				$this->saveParameter($key, $value);
			}
		}
	}

	// Load chart parm
	public function loadParameter($key)
	{
		if (is_array($this->Parameters) && array_key_exists($key, $this->Parameters))
			return $this->Parameters[$key]->Value;
		return NULL;
	}

	// Save chart parm
	public function saveParameter($key, $value)
	{
		if (is_array($this->Parameters)) {
			if (array_key_exists($key, $this->Parameters))
				$this->Parameters[$key]->Value = $value;
			else
				$this->Parameters[$key] = new ChartParameter($key, $value, TRUE);
		}
	}

	// Load chart parms
	public function loadParameters()
	{

		// Initialize default values
		$this->setDefaultParameter("caption", "Chart");

		// Show names/values/hover
		$this->setDefaultParameter("shownames", "1"); // Default show names
		$this->setDefaultParameter("showvalues", "1"); // Default show values

		// Get showvalues/showhovercap
		$showValues = (bool)$this->loadParameter("showvalues");
		$showHoverCap = (bool)$this->loadParameter("showhovercap") || (bool)$this->loadParameter("showToolTip");

		// Show tooltip
		if ($showHoverCap && !$this->loadParameter("showToolTip"))
			$this->saveParameter("showToolTip", "1");

		// Format percent/number for Pie/Doughnut chart
		$showPercentageValues = $this->loadParameter("showPercentageValues");
		$showPercentageInLabel = $this->loadParameter("showPercentageInLabel");
		if ($this->isPieChart() || $this->isDoughnutChart()) {
			if ($showHoverCap == "1" && $showPercentageValues == "1" || $showValues == "1" && $showPercentageInLabel == "1") {
				$this->setDefaultParameter("formatNumber", "1");
				$this->saveParameter("formatNumber", "1");
			}
		}

		// Set up Candlestick chart
		if ($this->isCandlestickChart()) {
			$this->setDefaultParameter("bearBorderColor", "E33C3C");
			$this->setDefaultParameter("bearFillColor", "E33C3C");
			$this->setDefaultParameter("showVolumeChart", "0");
			if ($this->loadParameter("showAsBars"))
				$this->saveParameter("plotPriceAs", "BAR");
		}

		// Hide legend for single series (Column/Line/Area 2D)
		if ($this->ScrollChart && $this->isSingleSeries()) {
			$this->setDefaultParameter("showLegend", "0");
			$this->saveParameter("showLegend", "0");
		}
	}

	// Load view data
	public function loadViewData()
	{
		$sdt = $this->SeriesDateType;
		$xdt = $this->XAxisDateFormat;
		$ndt = $this->isCandlestickChart() ? $this->NameDateFormat : ""; // Candlestick
		if ($sdt <> "")
			$xdt = $sdt;
		$this->ViewData = [];
		if ($sdt == "" && $xdt == "" && $ndt == "") { // No formatting, just copy
			$this->ViewData = $this->Data;
		} elseif (is_array($this->Data)) { // Format data
			$cntData = count($this->Data);
			for ($i = 0; $i < $cntData; $i++) {
				$temp = [];
				$chartrow = $this->Data[$i];
				$cntRow = count($chartrow);
				$temp[0] = $this->getXValue($chartrow[0], $xdt); // X value
				$temp[1] = $this->seriesValue($chartrow[1], $sdt); // Series value
				for ($j = 2; $j < $cntRow; $j++) {
					if ($ndt <> "" && $j == $cntRow - 1)
						$temp[$j] = $this->getXValue($chartrow[$j], $ndt); // Name value
					else
						$temp[$j] = $chartrow[$j]; // Y values
				}
				$this->ViewData[] = $temp;
			}
		}
	}

	// Set up chart
	public function setupChart()
	{
		global $ExportType, $Page;

		// Set up chart base SQL
		if ($this->Table->TableReportType == "crosstab") { // Crosstab chart
			$sqlSelect = str_replace("<DistinctColumnFields>", $this->Table->DistinctColumnFields, $this->Table->getSqlSelect());
			$sqlChartSelect = $this->SqlSelect;
		} else {
			$sqlSelect = $this->Table->getSqlSelect();
			$sqlChartSelect = $this->SqlSelect;
		}
		$pageFilter = isset($Page) ? $Page->Filter : "";
		$dbType = GetConnectionType($this->Table->Dbid);
		if ($this->Table->SourceTableIsCustomView)
			$sqlChartBase = "(" . BuildReportSql($sqlSelect, $this->Table->getSqlWhere(), $this->Table->getSqlGroupBy(), $this->Table->getSqlHaving(), ($dbType == "MSSQL") ? $this->Table->getSqlOrderBy() : "", $pageFilter, "") . ") TMP_TABLE";
		else
			$sqlChartBase = $this->Table->getSqlFrom();

		// Set up chart series
		if (!EmptyString($this->SeriesFieldName)) {
			if ($this->SeriesType == 1) { // Multiple Y fields
				$ar = explode("|", $this->SeriesFieldName);
				$cnt = count($ar);
				$yaxis = explode(",", $this->SeriesYAxis);
				for ($i = 0; $i < $cnt; $i++) {
					$fld = &$this->Table->fields[$ar[$i]];
					if (StartsString("4", strval($this->Type))) { // Combination charts
						$series = @$yaxis[$i] == "2" ? "S" : "P";
						$this->Series[] = [$fld->caption(), $series];
					} else {
						$this->Series[] = $fld->caption();
					}
				}
			} elseif ($this->Table->TableReportType == "crosstab" && $this->SeriesFieldName == $this->Table->ColumnFieldName && $this->Table->ColumnDateSelection && $this->Table->ColumnDateType == "q") { // Quarter
				for ($i = 1; $i <= 4; $i++)
					$this->Series[] = QuarterName($i);
			} elseif ($this->Table->TableReportType == "crosstab" && $this->SeriesFieldName == $this->Table->ColumnFieldName && $this->Table->ColumnDateSelection && $this->Table->ColumnDateType == "m") { // Month
				for ($i = 1; $i <= 12; $i++)
					$this->Series[] = MonthName($i);
			} else { // Load chart series from SQL directly
				if ($this->Table->SourceTableIsCustomView) {
					$sql = $this->SqlSelectSeries . $sqlChartBase;
					$sql = BuildReportSql($sql, $this->SqlWhereSeries, $this->SqlGroupBySeries, "", $this->SqlOrderBySeries, "", "");
				} else {
					$sql = $this->SqlSelectSeries . $sqlChartBase;
					$chartFilter = $this->SqlWhereSeries;
					AddFilter($chartFilter, $this->Table->getSqlWhere());
					$sql = BuildReportSql($sql, $chartFilter, $this->SqlGroupBySeries, "", $this->SqlOrderBySeries, $pageFilter, "");
				}
				$this->ChartSeriesSql = $sql;
			}
		}

		// Run time sort, update SqlOrderBy
		if ($this->RunTimeSort)
			$this->SqlOrderBy .= ($this->SortType == 2) ? " DESC" : "";

		// Set up ChartSql
		if ($this->Table->SourceTableIsCustomView) {
			$sql = $sqlChartSelect . $sqlChartBase;
			$sql = BuildReportSql($sql, $this->SqlWhere, $this->SqlGroupBy, "", $this->SqlOrderBy, "", "");
		} else {
			$sql = $sqlChartSelect . $sqlChartBase;
			$chartFilter = $this->SqlWhere;
			AddFilter($chartFilter, $this->Table->getSqlWhere());
			$sql = BuildReportSql($sql, $chartFilter, $this->SqlGroupBy, "", $this->SqlOrderBy, $pageFilter, "");
		}
		$this->ChartSql = $sql;
	}

	// Load chart data
	public function loadChartData()
	{

		// Data already loaded, return
		if ($this->_dataLoaded)
			return;

		// Setup chart series data
		if ($this->ChartSeriesSql <> "") {
			$this->loadSeries();
			if (DEBUG_ENABLED)
				SetDebugMessage("(Chart Series SQL): " . $this->ChartSeriesSql);
		}

		// Setup chart data
		if ($this->ChartSql <> "") {
			$this->loadData();
			if (DEBUG_ENABLED)
			SetDebugMessage("(Chart SQL): " . $this->ChartSql);
		}

		// Sort data
		if ($this->SeriesFieldName <> "" && $this->SeriesType <> 1)
			$this->sortMultiData();
		else
			$this->sortData();
		$this->_dataLoaded = TRUE;
	}

	// Load Chart Series
	public function loadSeries()
	{
		$sql = $this->ChartSeriesSql;
		$cnn = Conn($this->Table->Dbid);
		$rscht = $cnn->Execute($sql);
		$sdt = $this->SeriesDateType;
		while ($rscht && !$rscht->EOF) {
			$this->Series[] = $this->seriesValue($rscht->fields[0], $sdt); // Series value
			$rscht->MoveNext();
		}
		if ($rscht)
			$rscht->Close();
	}

	// Get Chart Series value
	public function seriesValue($val, $dt)
	{
		if ($dt == "syq") {
			$ar = explode("|", $val);
			if (count($ar) >= 2)
				return $ar[0] . " " . QuarterName($ar[1]);
			else
				return $val;
		} elseif ($dt == "sym") {
			$ar = explode("|", $val);
			if (count($ar) >= 2)
				return $ar[0] . " " . MonthName($ar[1]);
			else
				return $val;
		} elseif ($dt == "sq") {
			return QuarterName($val);
		} elseif ($dt == "sm") {
			return MonthName($val);
		} else {
			if (is_string($val))
				return trim($val);
			else
				return $val;
		}
	}

	// Load Chart Data from SQL
	public function loadData()
	{
		$sql = $this->ChartSql;
		$cnn = Conn($this->Table->Dbid);
		$rscht = $cnn->Execute($sql);
		while ($rscht && !$rscht->EOF) {
			$temp = [];
			for ($i = 0; $i < $rscht->FieldCount(); $i++)
				$temp[$i] = $rscht->fields[$i];
			$this->Data[] = $temp;
			$rscht->MoveNext();
		}
		if ($rscht) $rscht->Close();
	}

	// Get Chart X value
	public function getXValue($val, $dt)
	{
		if (is_numeric($dt)) {
			return FormatDateTime($val, $dt);
		} elseif ($dt == "y") {
			return $val;
		} elseif ($dt == "xyq") {
			$ar = explode("|", $val);
			if (count($ar) >= 2)
				return $ar[0] . " " . QuarterName($ar[1]);
			else
				return $val;
		} elseif ($dt == "xym") {
			$ar = explode("|", $val);
			if (count($ar) >= 2)
				return $ar[0] . " " . MonthName($ar[1]);
			else
				return $val;
		} elseif ($dt == "xq") {
			return QuarterName($val);
		}
		elseif ($dt == "xm") {
			return MonthName($val);
		} else {
			if (is_string($val))
				return trim($val);
			else
				return $val;
		}
	}

	// Sort chart data
	public function sortData()
	{
		$ar = &$this->Data;
		$opt = $this->SortType;
		$seq = $this->SortSequence;
		if (($opt < 3 || $opt > 4) && $seq == "" || ($opt < 1 || $opt > 4) && $seq <> "")
			return;
		if (is_array($ar)) {
			$cntar = count($ar);
			for ($i = 0; $i < $cntar; $i++) {
				for ($j = $i+1; $j < $cntar; $j++) {
					switch ($opt) {
						case 1: // X values ascending
							$swap = CompareValueCustom($ar[$i][0], $ar[$j][0], $seq);
							break;
						case 2: // X values descending
							$swap = CompareValueCustom($ar[$j][0], $ar[$i][0], $seq);
							break;
						case 3: // Y values ascending
							$swap = CompareValueCustom($ar[$i][2], $ar[$j][2], $seq);
							break;
						case 4: // Y values descending
							$swap = CompareValueCustom($ar[$j][2], $ar[$i][2], $seq);
					}
					if ($swap) {
						$tmpar = $ar[$i];
						$ar[$i] = $ar[$j];
						$ar[$j] = $tmpar;
					}
				}
			}
		}
	}

	// Sort chart multi series data
	public function sortMultiData()
	{
		$ar = &$this->Data;
		$opt = $this->SortType;
		$seq = $this->SortSequence;
		if (!is_array($ar) || ($opt < 3 || $opt > 4) && $seq == "" || ($opt < 1 || $opt > 4) && $seq <> "")
			return;

		// Obtain a list of columns
		foreach ($ar as $key => $row) {
			$xvalues[$key] = $row[0];
			$series[$key] = $row[1];
			$yvalues[$key] = $row[2];
			$ysums[$key] = $row[0]; // Store the x-value for the time being
			if (isset($xsums[$row[0]])) {
				$xsums[$row[0]] += $row[2];
			} else {
				$xsums[$row[0]] = $row[2];
			}
		}

		// Set up Y sum
		if ($opt == 3 || $opt == 4) {
			$cnt = count($ysums);
			for ($i=0; $i<$cnt; $i++)
				$ysums[$i] = $xsums[$ysums[$i]];
		}

		// No specific sequence, use array_multisort
		if ($seq == "") {
			switch ($opt) {
				case 1: // X values ascending
					array_multisort($xvalues, SORT_ASC, $ar);
					break;
				case 2: // X values descending
					array_multisort($xvalues, SORT_DESC, $ar);
					break;
				case 3:
				case 4: // Y values
					if ($opt == 3) { // Ascending
						array_multisort($ysums, SORT_ASC, $ar);
					} elseif ($opt == 4) { // Descending
						array_multisort($ysums, SORT_DESC, $ar);
					}
			}

		// Handle specific sequence
		} else {

			// Build key list
			if ($opt == 1 || $opt == 2)
				$vals = array_unique($xvalues);
			else
				$vals = array_unique($ysums);
			foreach ($vals as $key => $val) {
				$keys[] = [$key, $val];
			}

			// Sort key list based on specific sequence
			$cntkey = count($keys);
			for ($i = 0; $i < $cntkey; $i++) {
				for ($j = $i+1; $j < $cntkey; $j++) {
					switch ($opt) {

						// Ascending
						case 1:
						case 3:
							$swap = CompareValueCustom($keys[$i][1], $keys[$j][1], $seq);
							break;

						// Descending
						case 2:
						case 4:
							$swap = CompareValueCustom($keys[$j][1], $keys[$i][1], $seq);
							break;
					}
					if ($swap) {
						$tmpkey = $keys[$i];
						$keys[$i] = $keys[$j];
						$keys[$j] = $tmpkey;
					}
				}
			}
			for ($i = 0; $i < $cntkey; $i++) {
				$xsorted[] = $xvalues[$keys[$i][0]];
			}

			// Sort array based on x sequence
			$arwrk = $ar;
			$rowcnt = 0;
			$cntx = intval(count($xsorted));
			for ($i = 0; $i < $cntx; $i++) {
				foreach ($arwrk as $key => $row) {
					if ($row[0] == $xsorted[$i]) {
						$ar[$rowcnt] = $row;
						$rowcnt++;
					}
				}
			}
		}
	}

	// Get color
	public function getPaletteColor($i)
	{
		$colorpalette = $this->loadParameter("colorpalette");
		$colors = explode("|", $colorpalette);
		if (is_array($colors)) {
			$cnt = count($colors);
			return $colors[$i % $cnt];
		}
		return "";
	}

	// Format name for chart
	public function formatName($name)
	{
		global $ReportLanguage;
		if ($name === NULL) {
			return $ReportLanguage->phrase("NullLabel");
		} elseif ($name == "") {
			return $ReportLanguage->phrase("EmptyLabel");
		}
		return $name;
	}

	// Is single series chart
	public function isSingleSeries()
	{
		return StartsString("1", strval($this->Type));
	}

	// Is zoom line chart
	public function isZoomLineChart()
	{
		return EndsString("92", strval($this->Type));
	}

	// Is pie chart
	public function isPieChart()
	{
		return EndsString("05", strval($this->Type));
	}

	// Is doughnut chart
	public function isDoughnutChart()
	{
		return EndsString("06", strval($this->Type));
	}

	// Is stack chart
	public function isStackedChart()
	{
		return StartsString("3", strval($this->Type));
	}

	// Is combination chart
	public function isCombinationChart()
	{
		return StartsString("4", strval($this->Type));
	}

	// Is candlestick chart
	public function isCandlestickChart()
	{
		return SameString($this->Type, "5099");
	}

	// Is gantt chart
	public function isGanttChart()
	{
		return SameString($this->Type, "6098");
	}

	// Format number for chart
	public function formatNumber($v)
	{
		$cht_decimalprecision = $this->loadParameter("decimals");
		if ($cht_decimalprecision === NULL) {
			if ($this->DefaultDecimalPrecision >= 0)
				$cht_decimalprecision = $this->DefaultDecimalPrecision; // Use default precision
			else
				$cht_decimalprecision = (($v - (int)$v) == 0) ? 0 : strlen(abs($v - (int)$v)) - 2; // Use original decimal precision
		}
		return number_format($v, $cht_decimalprecision, ".", "");
	}

	// Get chart X SQL
	public function getXSql($fldsql, $fldtype, $val, $dt)
	{
		$dbid = $this->Table->Dbid;
		if (is_numeric($dt)) {
			return $fldsql . " = " . QuotedValue(UnFormatDateTime($val, $dt), $fldtype, $dbid);
		} elseif ($dt == "y") {
			if (is_numeric($val))
				return GroupSql($fldsql, "y", 0, $dbid) . " = " . QuotedValue($val, DATATYPE_NUMBER, $dbid);
			else
				return $fldsql . " = " . QuotedValue($val, $fldtype, $dbid);
		} elseif ($dt == "xyq") {
			$ar = explode("|", $val);
			if (count($ar) >= 2 && is_numeric($ar[0]) && is_numeric($ar[1]))
				return GroupSql($fldsql, "y", 0, $dbid) . " = " . QuotedValue($ar[0], DATATYPE_NUMBER, $dbid) . " AND " . GroupSql($fldsql, "xq", 0, $dbid) . " = " . QuotedValue($ar[1], DATATYPE_NUMBER, $dbid);
			else
				return $fldsql . " = " . QuotedValue($val, $fldtype, $dbid);
		} elseif ($dt == "xym") {
			$ar = explode("|", $val);
			if (count($ar) >= 2 && is_numeric($ar[0]) && is_numeric($ar[1]))
				return GroupSql($fldsql, "y", 0, $dbid) . " = " . QuotedValue($ar[0], DATATYPE_NUMBER, $dbid) . " AND " . GroupSql($fldsql, "xm", 0, $dbid) . " = " . QuotedValue($ar[1], DATATYPE_NUMBER, $dbid);
			else
				return $fldsql . " = " . QuotedValue($val, $fldtype, $dbid);
		} elseif ($dt == "xq") {
			return GroupSql($fldsql, "xq", 0, $dbid) . " = " . QuotedValue($val, DATATYPE_NUMBER, $dbid);
		} elseif ($dt == "xm") {
			return GroupSql($fldsql, "xm", 0, $dbid) . " = " . QuotedValue($val, DATATYPE_NUMBER, $dbid);
		} else {
			return $fldsql . " = " . QuotedValue($val, $fldtype, $dbid);
		}
	}

	// Get chart series SQL
	public function getSeriesSql($fldsql, $fldtype, $val, $dt)
	{
		$dbid = $this->Table->Dbid;
		if ($dt == "syq") {
			$ar = explode("|", $val);
			if (count($ar) >= 2 && is_numeric($ar[0]) && is_numeric($ar[1]))
				return GroupSql($fldsql, "y", 0, $dbid) . " = " . QuotedValue($ar[0], DATATYPE_NUMBER, $dbid) . " AND " . GroupSql($fldsql, "xq", 0, $dbid) . " = " . QuotedValue($ar[1], DATATYPE_NUMBER, $dbid);
			else
				return $fldsql . " = " . QuotedValue($val, $fldtype, $dbid);
		} elseif ($dt == "sym") {
			$ar = explode("|", $val);
			if (count($ar) >= 2 && is_numeric($ar[0]) && is_numeric($ar[1]))
				return GroupSql($fldsql, "y", 0, $dbid) . " = " . QuotedValue($ar[0], DATATYPE_NUMBER, $dbid) . " AND " . GroupSql($fldsql, "xm", 0, $dbid) . " = " . QuotedValue($ar[1], DATATYPE_NUMBER, $dbid);
			else
				return $fldsql . " = " . QuotedValue($val, $fldtype, $dbid);
		} elseif ($dt == "sq") {
			return GroupSql($fldsql, "xq", 0, $dbid) . " = " . QuotedValue($val, DATATYPE_NUMBER, $dbid);
		} elseif ($dt == "sm") {
			return GroupSql($fldsql, "xm", 0, $dbid) . " = " . QuotedValue($val, DATATYPE_NUMBER, $dbid);
		} else {
			return $fldsql . " = " . QuotedValue($val, $fldtype, $dbid);
		}
	}

	// Show chart temp image
	public function getTempImageTag()
	{
		global $ExportType, $USE_PHPEXCEL, $USE_PHPWORD;
		$chartid = "chart_" . $this->ID;
		$chartImage = TempChartImage($chartid, $this->IsCustomTemplate);
		$this->resizeTempImage($chartImage);
		$wrk = "";
		if ($chartImage <> "") {
			$wrk .= "<img src=\"" . $chartImage . "\" alt=\"\">";
			if ($this->PageBreak)
				$attr = " data-page-break=\"" . ($this->PageBreakType == "before" ? "before" : "after") . "\"";
			if ($ExportType == "word" && $USE_PHPWORD || $ExportType == "excel" && $USE_PHPEXCEL || $ExportType == "pdf") {
				$wrk = "<table class=\"ew-chart\"" . $attr . "><tr><td>" . $wrk . "</td></tr></table>";
			} else {
				$wrk = "<div class=\"ew-chart\"" . $attr . ">" . $wrk . "</div>";
			}
		}
		if ($this->PageBreak) {
			if ($this->PageBreakType == "before") {
				$wrk = $this->PageBreakContent . $wrk;
			} else {
				$wrk .= $this->PageBreakContent;
			}
		}
		return $wrk;
	}

	// Resize temp image
	public function resizeTempImage($fn)
	{
		global $ExportType, $USE_PHPWORD, $USE_PHPEXCEL;
		$portrait = SameText($this->Table->ExportPageOrientation, "portrait");
		$exportPdf = ($ExportType == "pdf");
		$exportWord = ($ExportType == "word" && $USE_PHPWORD);
		$exportExcel = ($ExportType == "excel" && $USE_PHPEXCEL);
		if ($exportPdf) {
			global $PDF_MAX_IMAGE_WIDTH, $PDF_MAX_IMAGE_HEIGHT;
			$maxWidth = $portrait ? $PDF_MAX_IMAGE_WIDTH : $PDF_MAX_IMAGE_HEIGHT;
			$maxHeight = $portrait ? $PDF_MAX_IMAGE_HEIGHT : $PDF_MAX_IMAGE_WIDTH;
		} elseif ($exportWord) {
			global $WORD_MAX_IMAGE_WIDTH, $WORD_MAX_IMAGE_HEIGHT;
			$maxWidth = $portrait ? $WORD_MAX_IMAGE_WIDTH : $WORD_MAX_IMAGE_HEIGHT;
			$maxHeight = $portrait ? $WORD_MAX_IMAGE_HEIGHT : $WORD_MAX_IMAGE_WIDTH;
		} elseif ($exportExcel) {
			global $EXCEL_MAX_IMAGE_WIDTH, $EXCEL_MAX_IMAGE_HEIGHT;
			$maxWidth = $portrait ? $EXCEL_MAX_IMAGE_WIDTH : $EXCEL_MAX_IMAGE_HEIGHT;
			$maxHeight = $portrait ? $EXCEL_MAX_IMAGE_HEIGHT : $EXCEL_MAX_IMAGE_WIDTH;
		}
		if ($exportPdf || $exportWord || $exportExcel) {
			$w = ($this->Width > 0) ? min($this->Width, $maxWidth) : $maxWidth;
			$h = ($this->Height > 0) ? min($this->Height, $maxHeight) : $maxHeight;
			return ResizeFile($fn, $fn, $w, $h);
		}
		return true;
	}

	// Get renderAs
	public function getRenderAs($i)
	{
		$ar = explode(",", $this->SeriesRenderAs);
		return ($i < count($ar)) ? $ar[$i] : "";
	}

	// Render chart
	public function render($class = "", $width = -1, $height = -1)
	{
		global $ExportType, $CustomExportType, $DashboardReport, $ReportLanguage, $Page, $USE_PHPEXCEL, $USE_PHPWORD;

		// Get renderer class
		$rendererClass = ChartTypes::getRendererClass($this->Type);

		// Check chart size
		if ($width <= 0)
			$width = $this->Width;
		if ($height <= 0)
			$height = $this->Height;
		if (!is_numeric($width) || $width <= 0)
			$width = $rendererClass::$DefaultWidth;
		if (!is_numeric($height) || $height <= 0)
			$height = $rendererClass::$DefaultHeight;

		// Set up chart
		$this->setupChart();

		// Output HTML
		echo '<span class="' . $class . '">'; // Start chart span

		// Render chart content
		if ($ExportType == "" || $ExportType == "print" && $CustomExportType == "" || $ExportType == "email" && Post("contenttype") == "url") {

			// Output chart html first
			$isDashBoard = $DashboardReport;
			$chartDivName = $this->Table->TableVar . '_' . $this->ChartVar;
			$chartAnchor = 'cht_' . $chartDivName;

			// if (LOWERCASE_OUTPUT_FILE_NAME)
			// 	$chartAnchor = strtolower($chartAnchor);

			$html = '<a id="' . $chartAnchor . '"></a>' .
				'<div id="div_ctl_' . $chartDivName . '" class="ew-chart">';

			// Not dashboard / run time sort / Not export / Not drilldown
			$isDrillDown = isset($Page) ? $Page->DrillDown : FALSE;
			if (!$isDashBoard && $this->RunTimeSort && $ExportType == "" && !$isDrillDown) {
				$html .= '<div class="ew-chart-sort mb-1">' .
					'<form class="ew-form form-horizontal" action="' . CurrentPageName() . '#cht_' . $chartDivName . '">' .
					$ReportLanguage->phrase("ChartOrder") . '&nbsp;' .
					'<select id="chartordertype" name="chartordertype" class="form-control" onchange="this.form.submit();">' .
					'<option value="1"' . ($this->SortType == '1' ? ' selected' : '') . '>' . $ReportLanguage->phrase("ChartOrderXAsc") . '</option>' .
					'<option value="2"' . ($this->SortType == '2' ? ' selected' : '') . '>' . $ReportLanguage->phrase("ChartOrderXDesc") . '</option>' .
					'<option value="3"' . ($this->SortType == "3" ? ' selected' : '') . '>' . $ReportLanguage->phrase("ChartOrderYAsc") . '</option>' .
					'<option value="4"' . ($this->SortType == "4" ? ' selected' : '') . '>' . $ReportLanguage->phrase("ChartOrderYDesc") . '</option>' .
					'</select>' .
					'<input type="hidden" id="chartorder" name="chartorder" value="' . $this->ChartVar . '">' .
					'</form>' .
					'</div>';
			}
			$html .= '<div id="div_' . $chartDivName . '" class="ew-chart-div" data-drilldown-placement="right"></div>';
			$html .= '</div>';
			echo $html;

			// Load chart data
			$this->loadChartData();
			$this->loadParameters();
			$this->loadViewData();

			// Output JavaScript
			$renderer = new $rendererClass($this);
			echo $renderer->getScript($width, $height);
		} elseif ($ExportType == "pdf" || $CustomExportType <> "" || $ExportType == "email" || $ExportType == "excel" && $USE_PHPEXCEL || $ExportType == "word" && $USE_PHPWORD) { // Show temp image
			echo $this->getTempImageTag();
		}
		echo '</span>'; // End chart span
	}

	// Chart Rendering event
	function Chart_Rendering() {

		// Example:
		// var_dump($this); // Chart
		// if ($this->ID == "<Report>_<Chart>") { // Check chart ID
		//	$this->saveParameter("formatNumber", "1"); // Format number
		//	$this->saveParameter("numberSuffix", "%"); // % as suffix
		// }

	}

	// Chart Data Rendered event
	function Chart_DataRendered(&$obj) {

		// Example:
		// var_dump($this); // Chart
		// if ($this->ID == "<Report>_<Chart>" && $obj instanceof \DOMElement) { // Check chart ID and input object data type
		//	if ($obj->nodeName == "set") { // Multiply values by 100
		//		$val = $obj->getAttribute("value");
		//		$val = $val * 100;
		//		$obj->setAttribute("value", $val);
		//	}
		// }

	}

	// Chart Rendered event
	function Chart_Rendered(&$data) {

		// Example:
		// var_dump($this); // Chart
		// if ($this->ID == "<Report>_<Chart>") { // Check chart ID
		//	$doc = new \DOMDocument("1.0", "utf-8"); // Create new DOMDocument object
		//	$doc->loadXML($data); // Load XML
		//	//Enter your code to manipulate the DOMDocument object here
		//	$data = $doc->saveXML(); // Output XML
		// }

	}
}

/**
 * ChartRenderer interface
 */
interface IChartRenderer
{
	public function getScript($width, $height);
}

/**
 * FusionCharts renderer
 */
class FusionChartRenderer implements IChartRenderer
{
	public $Chart;
	public $XmlDocument;
	public $CompactDataMode = FALSE; // For zoomline chart only
	public $DataSeparator = "|"; // For zoomline chart only
	public static $DefaultWidth = CHART_WIDTH;
	public static $DefaultHeight = CHART_HEIGHT;
	protected $XmlRoot;

	// Constructor
	public function __construct($chart)
	{
		$this->Chart = $chart;
		$this->XmlDocument = new \DOMDocument("1.0", "utf-8");
	}

	// Chart XML
	public function getXml()
	{
		if ($this->Chart->DataSource <> "") // Already rendered as XML (e.g. Gantt chart)
			return $this->Chart->DataSource;

		// Chart_Rendering event
		if (method_exists($this->Chart, "Chart_Rendering"))
			$this->Chart->Chart_Rendering();
		$cht_type = $this->Chart->loadParameter("type");

		// Format line color for Multi-Series Column Dual Y chart
		$cht_lineColor = $this->Chart->isCombinationChart() ? $this->Chart->loadParameter("lineColor") : "";
		$chartseries = $this->Chart->Series;
		$chartdata = $this->Chart->ViewData;
		$cht_series = $this->Chart->isSingleSeries() ? 0 : 1; // $cht_series = 1 (Multi series charts)
		$cht_series_type = $this->Chart->loadParameter("seriestype");
		$cht_alpha = $this->Chart->loadParameter("alpha");

		// Hide legend for single series (Column 2D / Line 2D / Area 2D)
		$cht_single_series = $this->Chart->ScrollChart && $this->Chart->isSingleSeries() ? 1 : 0;
		if ($this->Chart->isZoomLineChart()) // Zoom line chart, use compact data mode
			$this->CompactDataMode = TRUE;
		if (is_array($chartdata)) {
			$this->writeAttributes(); // Write chart header

			// Candlestick
			if ($this->Chart->isCandlestickChart()) {

				// Write candlestick cat
				if (count($chartdata[0]) >= 7) {
					$cats = $this->XmlDocument->createElement("categories");
					$this->XmlRoot->appendChild($cats);
					$cntcat = count($chartdata);
					for ($i = 0; $i < $cntcat; $i++) {
						$xindex = $i+1;
						$name = $chartdata[$i][6];
						if ($name <> "")
							$this->writeCandlestickCategoryContent($cats, $xindex, $name);
					}
				}

				// Write candlestick data
				$data = $this->XmlDocument->createElement("dataset");
				$this->XmlRoot->appendChild($data);
				$cntdata = count($chartdata);
				for ($i = 0; $i < $cntdata; $i++) {
					$dt = $chartdata[$i][0];
					$open = ($chartdata[$i][2] === NULL) ? 0 : (float)$chartdata[$i][2];
					$high = ($chartdata[$i][3] === NULL) ? 0 : (float)$chartdata[$i][3];
					$low = ($chartdata[$i][4] === NULL) ? 0 : (float)$chartdata[$i][4];
					$close = ($chartdata[$i][5] === NULL) ? 0 : (float)$chartdata[$i][5];
					$xindex = $i + 1;
					$lnk = $this->getChartLink($this->Chart->DrillDownUrl, $this->Chart->Data[$i]);
					$this->writeCandlestickContent($data, $dt, $open, $high, $low, $close, $xindex, $lnk);
				}

			// Multi series
			} else if ($cht_series == 1) {

				// Multi-Y values
				if ($cht_series_type == "1") {

					// Write cat
					if ($this->CompactDataMode) {
						$cntcat = count($chartdata);
						$content = "";
						for ($i = 0; $i < $cntcat; $i++) {
							$name = $this->Chart->formatName($chartdata[$i][0]);
							if ($content <> "") $content .= $this->DataSeparator;
							$content .= $name;
						}
						$cats = $this->XmlDocument->createElement("categories", $content);
						$this->XmlRoot->appendChild($cats);
					} else {
						$cats = $this->XmlDocument->createElement("categories");
						$this->XmlRoot->appendChild($cats);
						$cntcat = count($chartdata);
						for ($i = 0; $i < $cntcat; $i++) {
							$name = $this->Chart->formatName($chartdata[$i][0]);
							$this->writeCategoryContent($cats, $name);
						}
					}

					// Write series
					$cntdata = count($chartdata);
					$cntseries = count($chartseries);
					if ($cntseries > count($chartdata[0])-2) $cntseries = count($chartdata[0])-2;
					for ($i = 0; $i < $cntseries; $i++) {
						if ($this->CompactDataMode) {
							$content = "";
							$showSeries = CHART_SHOW_BLANK_SERIES;
							for ($j = 0; $j < $cntdata; $j++) {
								$val = $chartdata[$j][$i+2];
								$val = ($val === NULL) ? 0 : (float)$val;
								if ($val <> 0)
									$showSeries = TRUE;
								if ($content <> "")
									$content .= $this->DataSeparator;
						 		$content .= $val;
							}
							$dataset = $this->XmlDocument->createElement("dataset", $content);
							$series = @$chartseries[$i];
							$seriesname = is_array($series) ? $series[0] : $series;
							if ($seriesname === NULL) {
								$seriesname = $ReportLanguage->phrase("NullLabel");
							} elseif ($seriesname == "") {
								$seriesname = $ReportLanguage->phrase("EmptyLabel");
							}
							$this->writeAttribute($dataset, "seriesname", $seriesname);
							if ($showSeries)
								$this->XmlRoot->appendChild($dataset);
						} else {
							$color = $this->Chart->getPaletteColor($i);
							$renderAs = $this->Chart->getRenderAs($i);
							$showSeries = CHART_SHOW_BLANK_SERIES;
							$dataset = $this->XmlDocument->createElement("dataset");
							$this->writeSeriesAttributes($dataset, $chartseries[$i], $color, $cht_alpha, $cht_lineColor, $renderAs);
							$writeSeriesHeader = TRUE;
							for ($j = 0; $j < $cntdata; $j++) {
								$val = $chartdata[$j][$i+2];
								$val = ($val === NULL) ? 0 : (float)$val;
								if ($val <> 0)
									$showSeries = TRUE;
								$lnk = $this->getChartLink($this->Chart->DrillDownUrl, $this->Chart->Data[$j]);
								$this->writeSeriesContent($dataset, $val, "", "", $lnk);
							}
							if ($showSeries)
								$this->XmlRoot->appendChild($dataset);
						}
					}

				// Series field
				} else {

					// Get series names
					if (is_array($chartseries)) {
						$nSeries = count($chartseries);
					} else {
						$nSeries = 0;
					}

					// Write cat
					$cntdata = count($chartdata);
					$chartcats = [];
					if ($this->CompactDataMode) {
						$content = "";
						for ($i = 0; $i < $cntdata; $i++) {
							$name = $chartdata[$i][0];
							if (!in_array($name, $chartcats)) {
								if ($content <> "") $content .= $this->DataSeparator;
								$content .= $name;
								$chartcats[] = $name;
							}
						}
						$cats = $this->XmlDocument->createElement("categories", $content);
						$this->XmlRoot->appendChild($cats);
					} else {
						$cats = $this->XmlDocument->createElement("categories");
						$this->XmlRoot->appendChild($cats);
						for ($i = 0; $i < $cntdata; $i++) {
							$name = $chartdata[$i][0];
							if (!in_array($name, $chartcats)) {
								$this->writeCategoryContent($cats, $name);
								$chartcats[] = $name;
							}
						}
					}

					// Write series
					$cntcats = count($chartcats);
					$cntdata = count($chartdata);
					for ($i = 0; $i < $nSeries; $i++) {
						$seriesname = (is_array($chartseries[$i])) ? $chartseries[$i][0] : $chartseries[$i];
						if ($this->CompactDataMode) {
							$content = "";
							$showSeries = CHART_SHOW_BLANK_SERIES;
							for ($j = 0; $j < $cntcats; $j++) {
								$val = 0;
								for ($k = 0; $k < $cntdata; $k++) {
									if ($chartdata[$k][0] == $chartcats[$j] && $chartdata[$k][1] == $seriesname) {
										$val = $chartdata[$k][2];
										$val = ($val === NULL) ? 0 : (float)$val;
										if ($val <> 0)
											$showSeries = TRUE;
										break;
									}
								}
								if ($content <> "")
									$content .= $this->DataSeparator;
						 		$content .= $val;
							}
							$dataset = $this->XmlDocument->createElement("dataset", $content);
							if ($seriesname === NULL) {
								$seriesname = $ReportLanguage->phrase("NullLabel");
							} elseif ($seriesname == "") {
								$seriesname = $ReportLanguage->phrase("EmptyLabel");
							}
							$this->writeAttribute($dataset, "seriesname", $seriesname);
							if ($showSeries)
								$this->XmlRoot->appendChild($dataset);
						} else {
							$color = $this->Chart->getPaletteColor($i);
							$renderAs = $this->Chart->getRenderAs($i);
							$showSeries = CHART_SHOW_BLANK_SERIES;
							$dataset = $this->XmlDocument->createElement("dataset");
							$this->writeSeriesAttributes($dataset, $chartseries[$i], $color, $cht_alpha, $cht_lineColor, $renderAs);
							for ($j = 0; $j < $cntcats; $j++) {
								$val = 0;
								$lnk = "";
								for ($k = 0; $k < $cntdata; $k++) {
									if ($chartdata[$k][0] == $chartcats[$j] && $chartdata[$k][1] == $seriesname) {
										$val = $chartdata[$k][2];
										$val = ($val === NULL) ? 0 : (float)$val;
										if ($val <> 0)
											$showSeries = TRUE;
										$lnk = $this->getChartLink($this->Chart->DrillDownUrl, $this->Chart->Data[$k]);
										break;
									}
								}
								$this->writeSeriesContent($dataset, $val, "", "", $lnk);
							}
							if ($showSeries)
								$this->XmlRoot->appendChild($dataset);
						}
					}
				}

			// Show single series
			} elseif ($cht_single_series == 1) {

				// Write multiple cats
				$cats = $this->XmlDocument->createElement("categories");
				$this->XmlRoot->appendChild($cats);
				$cntcat = count($chartdata);
				for ($i = 0; $i < $cntcat; $i++) {
					$name = $this->Chart->formatName($chartdata[$i][0]);
					if ($chartdata[$i][1] <> "") 
						$name .= ", " . $chartdata[$i][1];
					$this->writeCategoryContent($cats, $name);
				}

				// Write series
				$toolTipSep = $this->Chart->loadParameter("toolTipSepChar");
				if ($toolTipSep == "")
					$toolTipSep = ":";
				$cntdata = count($chartdata);
				$dataset = $this->XmlDocument->createElement("dataset");
				$this->writeSeriesAttributes($dataset, "", "", $cht_alpha, $cht_lineColor);
				for ($i = 0; $i < $cntdata; $i++) {
					$name = $this->Chart->formatName($chartdata[$i][0]);
					if ($chartdata[$i][1] <> "") 
						$name .= ", " . $chartdata[$i][1];
					$val = $chartdata[$i][2];
					$val = ($val === NULL) ? 0 : (float)$val;
					$color = $this->Chart->getPaletteColor($i);
					$toolText = $name . $toolTipSep . $this->Chart->formatNumber($val);
					$lnk = $this->getChartLink($this->Chart->DrillDownUrl, $this->Chart->Data[$i]);
					$this->writeSeriesContent($dataset, $val, $color, $cht_alpha, $lnk, $toolText);
					$this->XmlRoot->appendChild($dataset);
				}

			// Single series
			} else {
				$cntdata = count($chartdata);
				for ($i = 0; $i < $cntdata; $i++) {
					$name = $this->Chart->formatName($chartdata[$i][0]);
					$color = $this->Chart->getPaletteColor($i);
					if ($chartdata[$i][1] <> "") 
						$name .= ", " . $chartdata[$i][1];
					$val = $chartdata[$i][2];
					$val = ($val === NULL) ? 0 : (float)$val;
					$lnk = $this->getChartLink($this->Chart->DrillDownUrl, $this->Chart->Data[$i]);
					$this->writeContent($this->XmlRoot, $name, $val, $color, $cht_alpha, $lnk); // Get chart content
				}
			}

			// Get trend lines
			$this->writeTrendLines();
		}
		$xml = $this->XmlDocument->saveXML();

		// Chart_Rendered event
		if (method_exists($this->Chart, "Chart_Rendered"))
			$this->Chart->Chart_Rendered($xml);
		$this->Chart->DataSource = $xml;
		return $this->XmlRoot ? $xml : "";
	}

	// Output chart attributes
	public function writeAttributes()
	{
		$chart = $this->XmlDocument->createElement("chart");
		$this->XmlRoot = &$chart;
		$this->XmlDocument->appendChild($chart);
		$parms = $this->Chart->Parameters;
		if (is_array($parms)) {
			foreach ($parms as $parm) {
				if ($parm->Output)
					$this->writeAttribute($chart, $parm->Key, $parm->Value);
			}
		}
		if ($this->CompactDataMode) {
			$this->writeAttribute($chart, "compactdatamode", "1");
			$this->writeAttribute($chart, "dataseparator", $this->DataSeparator);
		}
	}

	// Output trend lines
	public function writeTrendLines()
	{
		$cht_trends = $this->Chart->Trends;
		if (is_array($cht_trends)) {
			foreach ($cht_trends as $trend) {
				$trends = $this->XmlDocument->createElement("trendlines");
				$this->XmlRoot->appendChild($trends);

				// Get all trend lines
				$this->writeTrendLine($trends, $trend);
			}
		}
	}

	// Output trend line
	public function writeTrendLine(&$node, $ar)
	{
		$line = $this->XmlDocument->createElement("line");
		@list($startval, $endval, $color, $dispval, $thickness, $trendzone, $showontop, $alpha, $tooltext, $valueonright, $dashed, $dashlen, $dashgap, $parentyaxis) = $ar;
		$this->writeAttribute($line, "startValue", $startval); // Starting y value
		if ($endval <> 0)
			$this->writeAttribute($line, "endValue", $endval); // Ending y value
		$this->writeAttribute($line, "color", $color); // Color
		if ($dispval <> "")
			$this->writeAttribute($line, "displayValue", $dispval); // Display value
		if ($thickness > 0)
			$this->writeAttribute($line, "thickness", $thickness); // Thickness
		$this->writeAttribute($line, "isTrendZone", $trendzone); // Display trend as zone or line
		$this->writeAttribute($line, "showOnTop", $showontop); // Show on top
		if ($alpha > 0)
			$this->writeAttribute($line, "alpha", $alpha); // Alpha
		if ($tooltext <> "")
			$this->writeAttribute($line, "toolText", $tooltext); // Tool text
		if ($valueonright <> "0")
			$this->writeAttribute($line, "valueOnRight", $valueonright); // Value on right
		if ($dashed <> "0") {
			$this->writeAttribute($line, "dashed", $dashed); // Dashed trend line
			$this->writeAttribute($line, "dashLen", $dashlen); // Dashed trend length
			$this->writeAttribute($line, "dashGap", $dashgap); // Dashed line gap
		}
		if ($parentyaxis <> "")
			$this->writeAttribute($line, "parentYAxis", $parentyaxis); // Parent Y Axis
		$node->appendChild($line);
	}

	// Series header/footer XML (multi series)
	public function writeSeriesAttributes(&$node, $series, $color, $alpha, $linecolor, $renderAs = "")
	{
		global $ReportLanguage;
		$seriesname = is_array($series) ? $series[0] : $series;
		if ($seriesname === NULL) {
			$seriesname = $ReportLanguage->phrase("NullLabel");
		} elseif ($seriesname == "") {
			$seriesname = $ReportLanguage->phrase("EmptyLabel");
		}
		$this->writeAttribute($node, "seriesname", $seriesname);
		if (is_array($series)) {
			if ($series[1] == "S" && $linecolor <> "")
				$this->writeAttribute($node, "color", $linecolor);
			else
				$this->writeAttribute($node, "color", $color);
		} else {
				$this->writeAttribute($node, "color", $color);
		}
		$this->writeAttribute($node, "alpha", $alpha);
		if (is_array($series))
			$this->writeAttribute($node, "parentyaxis", $series[1]);
		if ($renderAs <> "")
			$this->writeAttribute($node, "renderas", $renderAs);
		if (method_exists($this->Chart, "Chart_DataRendered"))
			$this->Chart->Chart_DataRendered($node);
	}

	// Series content XML (multi series)
	public function writeSeriesContent(&$node, $val, $color = "", $alpha = "", $lnk = "", $toolText = "")
	{
		$set = $this->XmlDocument->createElement("set");
		if ($this->Chart->isStackedChart() && $val == 0 && !CHART_SHOW_ZERO_IN_STACK_CHART)
			$this->writeAttribute($set, "value", "");
		else
			$this->writeAttribute($set, "value", $this->Chart->formatNumber($val));
		if ($color <> "")
			$this->writeAttribute($set, "color", $color);
		if ($alpha <> "")
			$this->writeAttribute($set, "alpha", $alpha);
		if ($lnk <> "")
			$this->writeAttribute($set, "link", $lnk);
		if ($toolText <> "")
			$this->writeAttribute($set, "toolText", $toolText);
		if (method_exists($this->Chart, "Chart_DataRendered"))
			$this->Chart->Chart_DataRendered($set);
		$node->appendChild($set);
	}

	// Category content XML (Candlestick category)
	public function writeCandlestickCategoryContent(&$node, $xindex, $name)
	{
		$cat = $this->XmlDocument->createElement("category");
		$this->writeAttribute($cat, "label", $name);
		$this->writeAttribute($cat, "x", $xindex);
		$this->writeAttribute($cat, "showline", "1");
		if (method_exists($this->Chart, "Chart_DataRendered"))
			$this->Chart->Chart_DataRendered($cat);
		$node->appendChild($cat);
	}

	// Chart content XML (Candlestick)
	public function writeCandlestickContent(&$node, $dt, $open, $high, $low, $close, $xindex, $lnk = "")
	{
		$set = $this->XmlDocument->createElement("set");
		$this->writeAttribute($set, "date", FormatDateTime($dt, 5)); // Format as yyyy/mm/dd
		$this->writeAttribute($set, "open", $this->Chart->formatNumber($open));
		$this->writeAttribute($set, "high", $this->Chart->formatNumber($high));
		$this->writeAttribute($set, "low", $this->Chart->formatNumber($low));
		$this->writeAttribute($set, "close", $this->Chart->formatNumber($close));
		if ($xindex <> "")
			$this->writeAttribute($set, "x", $xindex);
		if ($lnk <> "")
			$this->writeAttribute($set, "link", $lnk);
		if (method_exists($this->Chart, "Chart_DataRendered"))
			$this->Chart->Chart_DataRendered($set);
		$node->appendChild($set);
	}	

	// Write attribute
	public function writeAttribute(&$node, $name, $val)
	{
		$val = strval($val);
		if ($node->hasAttribute($name)) {
			$node->getAttributeNode($name)->value = XmlEncode(ConvertToUtf8($val));
		} else {
			$att = $this->XmlDocument->createAttribute($name);
			$att->value = XmlEncode(ConvertToUtf8($val));
			$node->appendChild($att);
		}
	}

	// Category content XML (multi series)
	public function writeCategoryContent(&$node, $name)
	{
		$cat = $this->XmlDocument->createElement("category");
		$this->writeAttribute($cat, "label", $name);
		if (method_exists($this->Chart, "Chart_DataRendered"))
			$this->Chart->Chart_DataRendered($cat);
		$node->appendChild($cat);
	}

	// Chart content XML
	public function writeContent(&$node, $name, $val, $color, $alpha, $lnk)
	{
		$cht_shownames = $this->Chart->loadParameter("shownames");
		$set = $this->XmlDocument->createElement("set");
		$this->writeAttribute($set, "label", $name);
		$this->writeAttribute($set, "value", $this->Chart->formatNumber($val));
		$this->writeAttribute($set, "color", $color);
		$this->writeAttribute($set, "alpha", $alpha);
		$this->writeAttribute($set, "link", $lnk);
		if ($cht_shownames == "1")
			$this->writeAttribute($set, "showName", "1");
		if (method_exists($this->Chart, "Chart_DataRendered"))
			$this->Chart->Chart_DataRendered($set);
		$node->appendChild($set);
	}

	// Get chart link
	public function getChartLink($src, $row)
	{
		if ($src <> "" && is_array($row)) {
			global $ReportLanguage;
			$cntrow = count($row);
			$lnk = $src;
			$sdt = $this->Chart->SeriesDateType;
			$xdt = $this->Chart->XAxisDateFormat;
			$ndt = $this->Chart->isCandlestickChart() ? $this->Chart->NameDateFormat : "";
			if ($sdt <> "") $xdt = $sdt;
			if (preg_match("/&t=([^&]+)&/", $lnk, $m))
				$tblcaption = $ReportLanguage->tablePhrase($m[1], "TblCaption");
			else
				$tblcaption = "";
			for ($i = 0; $i < $cntrow; $i++) { // Link format: %i:Parameter:FieldType%
				if (preg_match("/%" . $i . ":([^%:]*):([\d]+)%/", $lnk, $m)) {
					$fldtype = FieldDataType($m[2]);
					if ($i == 0) { // Format X SQL
						$lnk = str_replace($m[0], Encrypt($this->Chart->getXSql("@" . $m[1], $fldtype, $row[$i], $xdt)), $lnk);
					} elseif ($i == 1) { // Format Series SQL
						$lnk = str_replace($m[0], Encrypt($this->Chart->getSeriesSql("@" . $m[1], $fldtype, $row[$i], $sdt)), $lnk);
					} else {
						$lnk = str_replace($m[0], Encrypt("@" . $m[1] . " = " . QuotedValue($row[$i], $fldtype, $this->Chart->Table->Dbid)), $lnk);
					}
				}
			}

			// Fusioncharts v3.12.2 do not support "-" in drill down link. Need to replace "-" with another non-base64 character that is supported by FusionCharts (now use "$"). To be changed back to "-" before decrypt.
			// https://www.fusioncharts.com/dev/advanced-chart-configurations/drill-down/using-javascript-functions-as-links.html
			// - Special characters like (, ), -, % and , cannot be passed as a parameter while function call.

			$lnk = str_replace("-", "$", $lnk);
			return "javascript:" . DrillDownScript($lnk, $this->Chart->ID, $tblcaption, $this->Chart->UseDrillDownPanel, "div_" . $this->Chart->ID, FALSE);
		}
		return "";
	}

	// Get chart JavaScript
	public function getScript($width, $height)
	{
		$chartxml = $this->getXml();
		$scroll = $this->Chart->ScrollChart;
		$drilldown = $this->Chart->DrillDownInPanel;
		$typ = $this->Chart->Type ?: ChartTypes::$DefaultType; // Chart type (nnnn)
		$id = $this->Chart->ID; // Chart ID
		$trends = $this->Chart->Trends; // Trend lines
		$data = $this->Chart->Data;
		$series = $this->Chart->Series;
		$align = $this->Chart->Align;

		// Get chart type
		$charttype = ChartTypes::getName($typ);

		// Output JavaScript for FusionCharts
		$xml = preg_replace('/^' . preg_quote('<?xml version="1.0" encoding="utf-8"?>', '/') . '\s*/', '', $chartxml);
		$xml = JsEncode(ConvertFromUtf8($xml)); // For double quoted string
		$dataformat = $this->Chart->DataFormat;
		$chartid = "chart_$id" . ($drilldown ? "_" . Random() : "");
		$obj = ($drilldown ? "drillDownCharts" : "exportCharts");
		$wrk = <<<EOT
<script>
var chartdata = "$xml"; // Use double quote
chartdata = FusionCharts.transcodeData(chartdata, 'xml', 'json');
var chartoptions = { 'id': 'chart_$id', 'renderAt': 'div_$id', 'width': $width, 'height': $height, 'id': '$chartid', 'type': '$charttype', 'dataFormat': '$dataformat', 'dataSource': chartdata };
jQuery(document).trigger('draw', [chartoptions]);
var cht_$id = new FusionCharts(chartoptions);
cht_$id.render();
window.${obj}['chart_$id'] = cht_$id;
if (ew.DEBUG_ENABLED) {
	FusionCharts['debugger'].outputTo(function(message) { console.log(message); });
	FusionCharts['debugger'].enable(true);
	console.log(chartoptions);
}
</script>
EOT;

		// Show XML for debug
		if (DEBUG_ENABLED && $chartxml <> "") {
			$doc = new \DOMDocument("1.0", "utf-8");
			$doc->loadXML($chartxml);
			$doc->formatOutput = TRUE;
			SetDebugMessage("(Chart XML):<pre>" . HtmlEncode(ConvertFromUtf8($doc->saveXML())) . "</pre>");
		}
		return $wrk;
	}
}

/**
 * Google charts renderer (Supports Candlestick and Gantt chart only)
 */
class GoogleChartRenderer extends FusionChartRenderer
{
	public static $DefaultWidth = ""; // Use default google chart width
	public static $DefaultHeight = CHART_HEIGHT;

	// Get chart JavaScript
	public function getScript($width, $height)
	{
		global $ReportLanguage, $FONT_NAME;
		$xml = $this->getXml();
		$typ = $this->Chart->Type; // Chart type (nnnn)
		$id = $this->Chart->ID; // Chart ID
		$trends = $this->Chart->Trends; // Trend lines
		$align = $this->Chart->Align;

		// Get chart data
		$ar = Xml2Array($xml);

		// Output JavaScript
		if ($this->Chart->isCandlestickChart()) { // Candlestick

			// Get options
			$options = @$ar["chart"]["options"][0]["value"];
			if ($options <> "") // Decode user options to array
				$options = json_decode($options, TRUE); // Options must be UTF-8 encoded
			if (!is_array($options))
				$options = [];
			$sets = @$ar["chart"]["dataset"]["set"];
			$ar = [];

			// Set colors
			$risingColor = "green"; // Rising color
			$fallingColor = "red"; // Falling color
			$elementColor = "#888888"; // Chart element color
			if (is_array($sets)) {
				foreach ($sets as $set_id => $set) {
					$date = str_replace("/", "-", $set["attr"]["date"]);
					$open = floatval($set["attr"]["open"]);
					$high = floatval($set["attr"]["high"]);
					$low = floatval($set["attr"]["low"]);
					$close = floatval($set["attr"]["close"]);
					$ar[] = [$date, $low, $open, $close, $high];
				}
			}
			$dataJson = ConvertFromUtf8(json_encode($ar));
			$defOptions = ["legend" => "none", "width" => $width, "height" => $height];
			$defOptions["bar"] = ["groupWidth" => 1];
			$defOptions["candlestick"] = ["hollowIsRising" => TRUE,
				"fallingColor" => ["fill" => $fallingColor, "stroke" => $fallingColor, "strokeWidth" => 1],
				"risingColor" => ["fill" => $risingColor, "stroke" => $risingColor, "strokeWidth" => 1],
			];
			$defOptions["colors"] = [$elementColor];
			$defOptions["title"] = $this->Chart->caption();
			$defOptions["fontName"] = $FONT_NAME;
			$defOptions["fontSize"] = FONT_SIZE;
			$options = array_merge($defOptions, $options);
			$optionsJson = ConvertFromUtf8(json_encode($options));

			// Output
			$wrk = <<<EOT
<script>
google.charts.load('current', { 'packages': ['corechart'], 'language': ew.LANGUAGE_ID });
google.charts.setOnLoadCallback(function() {
	var data = $dataJson;
	for (var i = 0; i < data.length; i++);
		data[i][0] = new Date(data[i][0]);
	var d = new google.visualization.arrayToDataTable(data, true); // Treat first row as data as well;
	var options = $optionsJson;
	var chart = new google.visualization.CandlestickChart(document.getElementById('div_$id'));
	window.exportCharts['chart_$id'] = chart; // Export chart
	var args = { 'id': 'chart_$id', 'chart': chart, 'data': d, 'options': options };
	jQuery(document).trigger('draw', [args]);
	chart.draw(args.data, args.options);
	if (ew.DEBUG_ENABLED)
		console.log(args);
});
</script>
EOT;
		} elseif ($this->Chart->isGanttChart()) { // Gantt chart (does not support Categories/Milestones/Trendlines/Connectors)
			global $FONT_NAME;

			// Get options
			$options = @$ar["chart"]["options"][0]["value"];
			if ($options <> "") // Decode user options to array
				$options = json_decode($options, TRUE); // Options must be UTF-8 encoded
			if (!is_array($options))
				$options = [];

			// Get processes
			$processes = @$ar["chart"]["processes"]["process"];

			// Get tasks
			$tasks = @$ar["chart"]["tasks"]["task"];
			$ar = [];
			if (is_array($tasks)) {
				foreach ($tasks as $task) {
					$task_id = @$task["attr"]["id"];
					$task_name = @$task["attr"]["name"];

					// Get resource
					$resource = @$task["attr"]["resourceId"];
					if ($resource == "") {
						$processid = @$task["attr"]["processid"];
						if ($processid <> "") {
							$resource = $processid;
							if (is_array($processes)) {
								foreach ($processes as $process) {
									$pid = @$process["attr"]["id"];
									if ($pid == $processid) {
										$resource = @$process["attr"]["name"];
										break;
									}
								}
							}
						}
					}
					$start_date = str_replace("/", "-", @$task["attr"]["start"]); // ISO 8601 date
					$end_date = str_replace("/", "-", @$task["attr"]["end"]); // ISO 8601 date
					$duration = @$task["attr"]["duration"] ?: NULL;
					$percent_complete = @$task["attr"]["percentComplete"] ?: 0;
					$dependency = @$task["attr"]["dependencies"] ?: @$task["attr"]["fromtaskid"] ?: NULL;
					$ar[] = [$task_id, $task_name, $resource, $start_date, $end_date, $duration, $percent_complete, $dependency];
				}
			}
			$data = ConvertFromUtf8(json_encode($ar));
			$defOptions["gantt"]["labelStyle"] = ["fontName" => $FONT_NAME, "fontSize" => FONT_SIZE];
			if ($width > 0)
				$defOptions["width"] = $width;
			if ($height > 0)
				$defOptions["height"] = $height;
			$options = array_merge($defOptions, $options);
			$optionsJson = ConvertFromUtf8(json_encode($options));
			$wrk = <<<EOT
<script>
google.charts.load('current', { 'packages': ['gantt'], 'language': ew.LANGUAGE_ID });
google.charts.setOnLoadCallback(function() {
	var \$chartdiv = jQuery('#div_$id').addClass('ew-gantt');
	var chart = new google.visualization.Gantt(\$chartdiv[0]);
	var d = new google.visualization.DataTable();
	d.addColumn('string', 'Task ID');
	d.addColumn('string', 'Task Name');
	d.addColumn('string', 'Resource');
	d.addColumn('date', 'Start');
	d.addColumn('date', 'End');
	d.addColumn('number', 'Duration');
	d.addColumn('number', 'Percent Complete');
	d.addColumn('string', 'Dependencies');
	var data = $data;
	for (var i = 0; i < data.length; i++)
		d.addRow([data[i][0], data[i][1], data[i][2], new Date(data[i][3]), new Date(data[i][4]), data[i][5], data[i][6], data[i][7]]);
	var options = $optionsJson;
	window.exportCharts['chart_$id'] = chart; // Export chart
	var args = { 'id': 'chart_$id', 'chart': chart, 'data': d, 'options': options };
	jQuery(document).trigger('draw', [args]);
	chart.draw(args.data, args.options);
	if (ew.DEBUG_ENABLED)
		console.log(args);
});
</script>
EOT;
		}
		if (DEBUG_ENABLED) {
			$doc = new \DOMDocument("1.0", "utf-8");
			$doc->loadXML($xml);
			$doc->formatOutput = TRUE;
			SetDebugMessage("(Chart XML):<pre>" . HtmlEncode(ConvertFromUtf8($doc->saveXML())) . "</pre>");
			SetDebugMessage("(Chart JSON):<pre>" . ConvertFromUtf8(json_encode($ar, JSON_PRETTY_PRINT)) . "</pre>");
		}
		return $wrk;
	}
}

/**
 * Crosstab column class
 */
class CrosstabColumn
{
	public $Caption;
	public $Value;
	public $Visible;
	public function __construct($value, $caption, $visible = TRUE)
	{
		$this->Caption = $caption;
		$this->Value = $value;
		$this->Visible = $visible;
	}
}

/**
 * Advanced filter class
 */
class AdvancedFilter
{
	public $ID;
	public $Name;
	public $FunctionName;
	public $Enabled = TRUE;
	public function __construct($filterid, $filtername, $filterfunc)
	{
		$this->ID = $filterid;
		$this->Name = $filtername;
		$this->FunctionName = $filterfunc;
	}
}

/**
 * Lookup class
 */
class ReportLookup extends Lookup
{
	public $RenderViewFunc = "renderLookup";
	public $RenderEditFn = ""; // Reserved, NOT USED
	public $PopupOptions = NULL;

	/**
	 * Execute SQL and write JSON response
	 *
	 * @return boolean
	 */
	public function toJson()
	{
		$tbl = $this->getTable();
		if ($tbl === NULL)
			return FALSE;

		// Update expression for grouping fields
		foreach ($this->DisplayFields as $i => $displayField) {
			$displayField = @$tbl->fields[$displayField];
			if ($displayField && !EmptyValue($displayField->LookupExpression))
				$displayField->Expression = $displayField->LookupExpression;
		}
		$orderBy = $this->UserOrderBy;
		if ((SameText($this->LookupType, "popup") || SameText($this->LookupType, "modal") || SameText($this->LookupType, "autosuggest")) && EmptyValue($orderBy)) { // Popup/Modal/AutoSuggest filter, sort by display field ascending
			$displayField = @$tbl->fields[$this->DisplayFields[0]];
			if ($displayField)
				$this->UserOrderBy = $displayField->Expression . " ASC";
		}
		$sql = $this->getSql();
		$orderBy = $this->UserOrderBy;
		$pageSize = $this->PageSize;
		$offset = $this->Offset;
		$rs = $this->getRecordset($sql, $orderBy, $pageSize, $offset);
		if ($pageSize > 0) {
			$totalCnt = $tbl->getRecordCount($sql);
		} else {
			$totalCnt = $rs ? $rs->RecordCount() : 0;
		}
		if ($rs) {
			$rowCnt = $rs->RecordCount();
			$fldCnt = $rs->FieldCount();
			$rsarr = $rs->getRows();
			$rs->close();

			// Clean output buffer
			if (ob_get_length())
				ob_clean();

			// Output
			if (is_array($rsarr) && $rowCnt > 0) {
				foreach ($rsarr as &$row) {
					for ($i = 1; $i < $fldCnt; $i++) {
						$str = ConvertToUtf8(strval($row[$i]));
						$str = str_replace(["\r", "\n", "\t"], $this->KeepCrLf ? ["\\r", "\\n", "\\t"] : [" ", " ", " "], $str);
						$row[$i] = $str;
						if (SameText($this->LookupType, "autofill")) {
							$autoFillSourceField = @$this->AutoFillSourceFields[$i - 1];
							$autoFillSourceField = @$tbl->fields[$autoFillSourceField];
							if ($autoFillSourceField)
								$autoFillSourceField->setDbValue($row[$i]);
						} else {
							$displayField = @$this->DisplayFields[$i - 1];
							$displayField = @$tbl->fields[$displayField];
							if ($displayField)
								$displayField->setDbValue($row[$i]);
						}
					}
					if (SameText($this->LookupType, "autofill")) {
						if ($this->FormatAutoFill) { // Format auto fill
							$tbl->RowType == ROWTYPE_EDIT;
							$fn = $this->RenderEditFn;
							$render = method_exists($tbl, $fn);
							if ($render)
								$tbl->$fn();
							for ($i = 1; $i < $fldCnt; $i++) {
								$autoFillSourceField = @$this->AutoFillSourceFields[$i - 1];
								$autoFillSourceField = @$tbl->fields[$autoFillSourceField];
								if ($autoFillSourceField)
									$row[$i] = (!$render || $autoFillSourceField->AutoFillOriginalValue) ? $autoFillSourceField->CurrentValue : $autoFillSourceField->EditValue;
							}
						}
					} elseif ($this->LookupType <> "unknown") { // Format display fields for known lookup type
						$render = FALSE;
						if ($this->LookupType == "popup") { // Handle null/empty value for popup
							if ($row[0] === NULL) // Handle null value
								$row[0] = NULL_VALUE;
							elseif ($row[0] == "") // Handle empty value
								$row[0] = EMPTY_VALUE;
						} else {
							$tbl->RowType == ROWTYPE_VIEW;
							$fn = $this->RenderViewFunc;
							$render = method_exists($tbl, $fn);
							if ($render)
								$tbl->$fn();
						}
						for ($i = 1; $i < $fldCnt; $i++) {
							$displayField = @$this->DisplayFields[$i - 1];
							$displayField = @$tbl->fields[$displayField];
							if ($displayField) {
								$sfx = $i > 1 ? $i : "";
								$row[$i] = $render ? $displayField->ViewValue : $displayField->CurrentValue;
								$row["df" . $sfx] = $row[$i];
							}
						}
					}
				}
			}

			// Set up advanced filter
			if (in_array($this->LookupType, ["updateoption", "modal", "popup"])) {
				if (method_exists($tbl, "Page_FilterLoad"))
					$tbl->Page_FilterLoad();
				$linkField = @$tbl->fields[$this->LinkField];
				if ($linkField && is_array($linkField->AdvancedFilters)) {
					$ar = [];
					foreach ($linkField->AdvancedFilters as $filter) {
						if ($filter->Enabled)
							$ar[] = [0 => $filter->ID, "lf" => $filter->ID, 1 => $filter->Name, "df" => $filter->Name];
					}
					$rsarr = array_merge($ar, $rsarr);
				}
			}
			$result = ["result" => "OK", "records" => $rsarr, "totalRecordCount" => $totalCnt];
			if (DEBUG_ENABLED)
				$result["sql"] = $sql;
			WriteJson($result);
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Set popup options
	 *
	 * @param array $options Input options with formats:
	 *  1. Manual input data, e.g.: [ ["lv1", "dv", "dv2", "dv3", "dv4"], ["lv2", "dv", "dv2", "dv3", "dv4"], etc...]
	 *  2. Data from $rs->getRows(), e.g.: [ [0 => "lv1", "Field1" => "lv1", 1 => "dv", "Field2" => "dv2", ...], [0 => "lv2", "Field1" => "lv2", 1 => "dv", "Field2" => "dv2", ...], etc...]
	 * @return boolean Output array ["lv1" => [0 => "lv1", "lf" => "lv1", 1 => "dv", "df" => "dv", etc...], etc...]
	 */
	public function setPopupOptions($options)
	{
		$opts = $this->formatOptions($options);
		if ($opts === NULL)
			return FALSE;
		$this->PopupOptions = $opts;
		return TRUE;
	}
}

/**
 * Report Generator class
 */
class ReportGenerator
{

	/**
	 * Get report generator URL from POST data
	 *
	 * @return string Generate report URL
	 */
	public function getUrl()
	{
		$report = Post("report");
		if (EmptyValue($report))
			return FALSE;
		$params = [];

		// Set report parameters
		$reportType = Post("reporttype");
		$genKey = Encrypt($report, REPORT_LOG_ENCRYPT_KEY);
		$params["export"] = $reportType;
		if ($reportType == "email") {
			$sender = Post("sender", "");
			$recipient = Post("recipient", "");
			$cc = Post("cc", "");
			$bcc = Post("bcc", "");
			$subject = Post("subject", "");
			if ($sender == "" || $recipient == "" || $subject == "")
				return FALSE;
			$params["sender"] = $sender;
			$params["recipient"] = $recipient;
			$params["subject"] = $subject;
			if ($cc <> "")
				$params["cc"] = $cc;
			if ($bcc <> "")
				$params["bcc"] = $bcc;
		}
		$pageOption = Post("pageoption", "");
		if ($pageOption == "all") {
			$params["exportall"] = TRUE;
		} else {
			$params["exportall"] = FALSE;
			$params["start"] = 1;
		}

		// Set report filter
		$filterName = Post("filtername", "");
		if ($filterName == "") {
			$filterName = "_none";
		} elseif ($filterName == "@@current") {
			$filterName = "_user";
			$filter = JsonDecode(Post("filter", ""), TRUE);
			if (is_array($filter) && is_array(@$filter["data"])) {
				$filter = $filter["data"];
				foreach ($filter as $key => $val)
					$params[$key] = $val;
			}
		}
		$params["filtername"] = $filterName;

		// Set response type
		$responseType = Post("responsetype", "");
		$params["responsetype"] = $responseType;

		// Set show current filter
		$params["showfilter"] = Post("showcurrentfilter") === "1";
		$reportParams = Encrypt(JsonEncode(ConvertToUtf8($params)), REPORT_LOG_ENCRYPT_KEY);
		$reportUrl = FullUrl(GetUrl(API_URL), "generate") . "?action=generatereport&report=" . $report . "&key=" . urlencode($genKey) . "&params=" . urlencode($reportParams);
		WriteJson(["success" => TRUE, "url" => $reportUrl]);
		return TRUE;
	}

	/**
	 * Generate report
	 *
	 * @return void
	 */
	public function generate()
	{
		global $Response, $REPORTS_LIST, $RELATIVE_PATH;
		$report = Param("report");
		$key = Param("key");
		$reportParams = Param("params", "");
		if (EmptyValue($report) || Decrypt($key, REPORT_LOG_ENCRYPT_KEY) <> $report || !array_key_exists($report, $REPORTS_LIST) || EmptyValue($reportParams))
			return FALSE;

		// Build report URL
		$url = $RELATIVE_PATH . $REPORTS_LIST[$report];
		$reportUrl = FullUrl($url, "generatereport") . "?reportkey=" . urlencode($key) . "&reportparams=" . urlencode($reportParams);

		//echo $report . ":" . $reportUrl;
		$Response = $Response->withRedirect($reportUrl);
	}
}

/**
 * Get report parameter (generate/post/querystring data)
 *
 * @param string $name Name of paramter
 * @param mixed $default Default value if name not found 
 * @return string
*/
function ReportParam($name, $default = NULL) {
	global $ReportParameters;
	if (is_array($ReportParameters) && array_key_exists($name, $ReportParameters))
		return $ReportParameters[$name];
	else
		return Param($name, $default);
}

// Generate a GUID
function GetRandomGuid($trim = TRUE) {

	// Windows
	if (function_exists('com_create_guid')) {
		if ($trim === TRUE)
			return trim(com_create_guid(), '{}');
		else
			return com_create_guid();
	}

	// OSX/Linux
	if (function_exists('openssl_random_pseudo_bytes')) {
		$data = openssl_random_pseudo_bytes(16);
		$data[6] = chr(ord($data[6]) & 0x0f | 0x40); // Set version to 0100
		$data[8] = chr(ord($data[8]) & 0x3f | 0x80); // Set bits 6-7 to 10
		return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
	}

	// Fallback
	mt_srand((double)microtime() * 10000);
	$charid = strtolower(md5(uniqid(rand(), true)));
	$hyphen = chr(45); // "-"
	$lbrace = $trim ? "" : chr(123); // "{"
	$rbrace = $trim ? "" : chr(125); // "}"
	$guid = $lbrace . substr($charid, 0, 8) . $hyphen . substr($charid, 8, 4) . $hyphen .
		substr($charid, 12, 4) . $hyphen . substr($charid, 16, 4) . $hyphen . substr($charid, 20, 12) . $rbrace;
	return $guid;
}

/**
 * Convert field value for dropdown
 *
 * @param string $t Date type
 * @param mixed $val Field value
 * @return string Converted value
 */
function ConvertDisplayValue($t, $val) {
	if ($val === NULL) {
		return NULL_VALUE;
	} elseif ($val === "") {
		return EMPTY_VALUE;
	}
	if (is_float($val))
		$val = (float)$val;
	if ($t == "")
		return $val;
	if ($ar = explode(" ", $val)) {
		$ar = explode("-", $ar[0]);
	} else {
		return $val;
	}
	if (!$ar || count($ar) <> 3)
		return $val;
	list($year, $month, $day) = $ar;
	switch (strtolower($t)) {
		case "year":
			return $year;
		case "quarter":
			return "$year|" . ceil(intval($month)/3);
		case "month":
			return "$year|$month";
		case "day":
			return "$year|$month|$day";
		case "date":
			return "$year-$month-$day";
	}
}

/**
 * Get dropdown display value
 *
 * @param mixed $v Field value
 * @param string $t Date type
 * @param integer $fmt Date format
 * @return string Display value of the field value
 */
function GetDropDownDisplayValue($v, $t = "", $fmt = 0) {
	global $ReportLanguage;
	if (SameString($v, NULL_VALUE)) {
		return $ReportLanguage->phrase("NullLabel");
	} elseif (SameString($v, EMPTY_VALUE)) {
		return $ReportLanguage->phrase("EmptyLabel");
	} elseif (SameText($t, "boolean")) {
		return BooleanName($v);
	}
	if ($t == "")
		return $v;
	$ar = explode("|", strval($v));
	switch (strtolower($t)) {
		case "year":
			return $v;
		case "quarter":
			if (count($ar) >= 2)
				return QuarterName($ar[1]) . " " . $ar[0];
		case "month":
			if (count($ar) >= 2)
				return MonthName($ar[1]) . " " . $ar[0];
		case "day":
			if (count($ar) >= 3)
				return FormatDateTime($ar[0] . "-" . $ar[1] . "-" . $ar[2], $fmt);
		case "date":
			return FormatDateTime($v, $fmt);
	}
}

// Get filter value for dropdown
function GetFilterDropDownValue($fld, $sep = ", ") {
	global $ReportLanguage;
	$value = $fld->DropDownValue;
	$out = "";
	if ($value == INIT_VALUE || $value === NULL) {
		$out = ($sep == ",") ? "" : $ReportLanguage->phrase("PleaseSelect"); // Output empty string as value for input tag
	} else {
		if (!is_array($value))
			$value = [$value];
		$cnt = count($value);
		for ($i = 0; $i < $cnt; $i++) {
			$val = $value[$i];
			if (StartsString("@@", $val)) { // Lookup from AdvancedFilter
				if (is_array($fld->AdvancedFilters)) {
					foreach ($fld->AdvancedFilters as $filter) {
						if ($filter->Enabled && $val == $filter->ID) {
							$val = $filter->Name;
							break;
						}
					}
				}
			} else {
				if ($fld->DataType == DATATYPE_DATE)
					$val = FormatDateTime($val, $fld->DateTimeFormat);
			}
			$out .= ($out <> "" ? $sep : "") . $val;
		}
	}
	return $out;
}

// Get current filter value for modal lookup
function GetFilterCurrentValue($fld, $sep = ", ") {
	global $ReportLanguage;
	$value = $fld->DropDownValue;
	if (is_array($value))
		$value = implode($sep, $value);
	if ($value == INIT_VALUE || $value === NULL)
		$value = ($sep == ",") ? "" : $ReportLanguage->phrase("PleaseSelect"); // Output empty string as value for input tag
	return $value;
}

/**
 * Get Boolean Name
 *
 * @param mixed $v Value, treat "T", "True", "Y", "Yes", "1" as TRUE
 * @return string
 */
function BooleanName($v) {
	global $ReportLanguage;
	if ($v === NULL)
		return $ReportLanguage->phrase("NullLabel");
	elseif (strtoupper($v) == "T" || strtoupper($v) == "TRUE" || strtoupper($v) == "Y" || strtoupper($v) == "YES" Or strval($v) == "1")
		return $ReportLanguage->phrase("BooleanYes");
	else
		return $ReportLanguage->phrase("BooleanNo");
}

// Quarter name
function QuarterName($q) {
	global $ReportLanguage;
	switch ($q) {
	case 1:
		return $ReportLanguage->phrase("Qtr1");
	case 2:
		return $ReportLanguage->phrase("Qtr2");
	case 3:
		return $ReportLanguage->phrase("Qtr3");
	case 4:
		return $ReportLanguage->phrase("Qtr4");
	default:
		return $q;
	}
}

// Month name
function MonthName($m) {
	global $ReportLanguage;
	switch ($m) {
	case 1:
		return $ReportLanguage->phrase("MonthJan");
	case 2:
		return $ReportLanguage->phrase("MonthFeb");
	case 3:
		return $ReportLanguage->phrase("MonthMar");
	case 4:
		return $ReportLanguage->phrase("MonthApr");
	case 5:
		return $ReportLanguage->phrase("MonthMay");
	case 6:
		return $ReportLanguage->phrase("MonthJun");
	case 7:
		return $ReportLanguage->phrase("MonthJul");
	case 8:
		return $ReportLanguage->phrase("MonthAug");
	case 9:
		return $ReportLanguage->phrase("MonthSep");
	case 10:
		return $ReportLanguage->phrase("MonthOct");
	case 11:
		return $ReportLanguage->phrase("MonthNov");
	case 12:
		return $ReportLanguage->phrase("MonthDec");
	default:
		return $m;
	}
}

// Get group count for custom template
function GetGroupCount($ar, $key = []) {
	if (is_array($ar) && is_array($key)) {
		$lvl = count($key);
		$cnt = 0;
		if ($lvl > 1) { // Get next level
			$wrkkey = array_shift($key);
			$wrkar = @$ar[$wrkkey];
			$cnt += GetGroupCount($wrkar, $key);
		} else {
			$wrkar = ($lvl == 0) ? $ar : @$ar[$key[0]];
			if (is_array($wrkar)) { // Accumulate all values
				$grp = count($wrkar);
				for ($i = 1; $i < $grp; $i++)
					$cnt += GetGroupCount($wrkar, [$i]);
			} else {
				$cnt = $wrkar;
			}
		}
		return $cnt;
	}
	return 0;
}

// Join array
function JoinArray($ar, $sep, $ft, $pos = 0, $dbid = 0) {
	if (!is_array($ar))
		return "";
	$arwrk = array_slice($ar, $pos); // Return array from position pos
	$cntar = count($arwrk);
	for ($i = 0; $i < $cntar; $i++)
		$arwrk[$i] = QuotedValue($arwrk[$i], $ft, $dbid);
	return implode($sep, $arwrk);
}

// Get current year
function CurrentYear() {
	return intval(date('Y'));
}

// Get current quarter
function CurrentQuarter() {
	return ceil(intval(date('n'))/3);
}

// Get current month
function CurrentMonth() {
	return intval(date('n'));
}

// Get current day
function CurrentDay() {
	return intval(date('j'));
}

// Build Report SQL
function BuildReportSql($select, $where, $groupBy, $having, $orderBy, $filter, $sort) {
	$dbWhere = $where;
	if ($dbWhere <> "")
		$dbWhere = "(" . $dbWhere . ")";
	if ($filter <> "") {
		if ($dbWhere <> "")
			$dbWhere .= " AND ";
		$dbWhere .= "(" . $filter . ")";
	}
	$dbOrderBy = UpdateSortFields($orderBy, $sort, 1);
	$sql = $select;
	if ($dbWhere <> "")
		$sql .= " WHERE " . $dbWhere;
	if ($groupBy <> "")
		$sql .= " GROUP BY " . $groupBy;
	if ($having <> "")
		$sql .= " HAVING " . $having;
	if ($dbOrderBy <> "")
		$sql .= " ORDER BY " . $dbOrderBy;
	return $sql;
}

/**
 * Update sort fields
 *
 * @param string $orderBy Order By clause
 * @param string $sort Sort fields
 * @param [type] $opt Option (1: merge all sort fields, 2: merge OrderBy fields only)
 * @return string Order By clause
 */
function UpdateSortFields($orderBy, $sort, $opt) {
	if ($orderBy == "") {
		if ($opt == 1)
			return $sort;
		else
			return "";
	} elseif ($sort == "") {
		return $orderBy;
	} else {

		// Merge sort field list
		$arOrderBy = GetSortFields($orderBy);
		$cntOrderBy = count($arOrderBy);
		$arSort = GetSortFields($sort);
		$cntSort = count($arSort);
		for ($i = 0; $i < $cntSort; $i++) {

			// Get sort field
			$sortfld = trim($arSort[$i]);
			if (EndsText(" ASC", $sortfld) || EndsText(" DESC", $sortfld))
				$sortfld = trim(substr($sortfld, 0, -4));
			for ($j = 0; $j < $cntOrderBy; $j++) {

				// Get orderby field
				$orderfld = trim($arOrderBy[$j]);
				if (EndsText(" ASC", $orderfld) || EndsText(" DESC", $orderfld))
					$orderfld = trim(substr($orderfld, 0, -4));

				// Replace field
				if ($orderfld == $sortfld) {
					$arOrderBy[$j] = $arSort[$i];
					break;
				}
			}

			// Append field
			if ($opt == 1) {
				if ($orderfld <> $sortfld)
					$arOrderBy[] = $arSort[$i];
			}
		}
		return implode(", ", $arOrderBy);
	}
}

// Get sort fields
function GetSortFields($flds) {
	$offset = -1;
	$fldpos = 0;
	$ar = [];
	while ($offset = strpos($flds, ",", $offset + 1)) {
		$orderfld = substr($flds, $fldpos, $offset - $fldpos);
		if (EndsText(" ASC", $orderfld) || EndsText(" DESC", $orderfld)) {
			$fldpos = $offset+1;
			$ar[] = $orderfld;
		}
	}
	$ar[] = substr($flds, $fldpos);
	return $ar;
}

// Get reverse sort
function ReverseSort($sorttype) {
	return ($sorttype == "ASC") ? "DESC" : "ASC";
}

// Construct a crosstab field name
function CrosstabFieldExpression($smrytype, $smryfld, $colfld, $datetype, $val, $qc, $alias = "", $dbid = 0) {
	if (SameString($val, NULL_VALUE)) {
		$wrkval = "NULL";
		$wrkqc = "";
	} elseif (SameString($val, EMPTY_VALUE)) {
		$wrkval = "";
		$wrkqc = $qc;
	} else {
		$wrkval = $val;
		$wrkqc = $qc;
	}
	switch ($smrytype) {
	case "SUM":
		$fld = $smrytype . "(" . $smryfld . "*" . SqlDistinctFactor($colfld, $datetype, $wrkval, $wrkqc, $dbid) . ")";
		break;
	case "COUNT":
		$fld = "SUM(" . SqlDistinctFactor($colfld, $datetype, $wrkval, $wrkqc, $dbid) . ")";
		break;
	case "MIN":
	case "MAX":
		$dbtype = GetConnectionType($dbid);
		$aggwrk = SqlDistinctFactor($colfld, $datetype, $wrkval, $wrkqc, $dbid);
		$fld = $smrytype . "(IF(" . $aggwrk . "=0,NULL," . $smryfld . "))";
		if ($dbtype == "ACCESS")
			$fld = $smrytype . "(IIf(" . $aggwrk . "=0,NULL," . $smryfld . "))";
		elseif ($dbtype == "MSSQL" || $dbtype == "ORACLE" || $dbtype == "SQLITE")
			$fld = $smrytype . "(CASE " . $aggwrk . " WHEN 0 THEN NULL ELSE " . $smryfld . " END)";
		elseif ($dbtype == "MYSQL" || $dbtype == "POSTGRESQL")
			$fld = $smrytype . "(IF(" . $aggwrk . "=0,NULL," . $smryfld . "))";
		break;
	case "AVG":
		$sumwrk = "SUM(" . $smryfld . "*" . SqlDistinctFactor($colfld, $datetype, $wrkval, $wrkqc, $dbid) . ")";
		if ($alias != "")

//			$sumwrk .= " AS SUM_" . $alias;
			$sumwrk .= " AS " . QuotedName("sum_" . $alias, $dbid);
		$cntwrk = "SUM(" . SqlDistinctFactor($colfld, $datetype, $wrkval, $wrkqc, $dbid) . ")";
		if ($alias != "")

//			$cntwrk .= " AS CNT_" . $alias;
			$cntwrk .= " AS " . QuotedName("cnt_" . $alias, $dbid);
		return $sumwrk . ", " . $cntwrk;
	}
	if ($alias != "")
		$fld .= " AS " . QuotedName($alias, $dbid);
	return $fld;
}

/**
 * Construct SQL Distinct factor
 * - ACCESS
 * y: IIf(Year(FieldName)=1996,1,0)
 * q: IIf(DatePart(""q"",FieldName,1,0)=1,1,0))
 * m: (IIf(DatePart(""m"",FieldName,1,0)=1,1,0)))
 * others: (IIf(FieldName=val,1,0)))
 * - MS SQL
 * y: (1-ABS(SIGN(Year(FieldName)-1996)))
 * q: (1-ABS(SIGN(DatePart(q,FieldName)-1)))
 * m: (1-ABS(SIGN(DatePart(m,FieldName)-1)))
 * d: (CASE Convert(VarChar(10),FieldName,120) WHEN '1996-1-1' THEN 1 ELSE 0 END)
 * - MySQL
 * y: IF(YEAR(FieldName)=1996,1,0))
 * q: IF(QUARTER(FieldName)=1,1,0))
 * m: IF(MONTH(FieldName)=1,1,0))
 * - SQLITE
 * y: (CASE CAST(STRFTIME('%Y',FieldName) AS INTEGER) WHEN 1996 THEN 1 ELSE 0 END)
 * q: (CASE (CAST(STRFTIME('%m',FieldName) AS INTEGER)+2)/3 WHEN 1 THEN 1 ELSE 0 END)
 * m: (CASE CAST(STRFTIME('%m',FieldName) AS INTEGER) WHEN 1 THEN 1 ELSE 0 END)
 * - PostgreSql
 * y: CASE WHEN TO_CHAR(FieldName,'YYYY')='1996' THEN 1 ELSE 0 END
 * q: CASE WHEN TO_CHAR(FieldName,'Q')='1' THEN 1 ELSE 0 END
 * m: CASE WHEN TO_CHAR(FieldName,'MM')=LPAD('1',2,'0') THEN 1 ELSE 0 END
 * - Oracle
 * y: DECODE(TO_CHAR(FieldName,'YYYY'),'1996',1,0)
 * q: DECODE(TO_CHAR(FieldName,'Q'),'1',1,0)
 * m: DECODE(TO_CHAR(FieldName,'MM'),LPAD('1',2,'0'),1,0)
 *
 * @param DbField $fld Field
 * @param integer $dateType Date type
 * @param mixed $val Value
 * @param string $qc Quote character
 * @param integer $dbid Database ID
 * @return string
 */
function SqlDistinctFactor($fld, $dateType, $val, $qc, $dbid = 0) {
	$dbtype = GetConnectionType($dbid);
	if ($dbtype == "ACCESS") {
		if ($dateType == "y" && is_numeric($val)) {
			return "IIf(Year(" . $fld . ")=" . $val . ",1,0)";
		} elseif (($dateType == "q" || $dateType == "m") && is_numeric($val)) {
			return "IIf(DatePart(\"" . $dateType . "\"," . $fld . ")=" . $val . ",1,0)";
		} else {
			if ($val == "NULL")
				return "IIf(" . $fld . " IS NULL,1,0)";
			else
				return "IIf(" . $fld . "=" . $qc . AdjustSql($val, $dbid) . $qc . ",1,0)";
		}
	} elseif ($dbtype == "MSSQL") {
		if ($dateType == "y" && is_numeric($val)) {
			return "(1-ABS(SIGN(Year(" . $fld . ")-" . $val . ")))";
		} elseif (($dateType == "q" || $dateType == "m") && is_numeric($val)) {
			return "(1-ABS(SIGN(DatePart(" . $dateType . "," . $fld . ")-" . $val . ")))";
		} elseif ($dateType == "d") {
			return "(CASE CONVERT(VARCHAR(10)," . $fld . ",120) WHEN " . $qc . AdjustSql($val, $dbid) . $qc . " THEN 1 ELSE 0 END)";
		} elseif ($dateType == "dt") {
			return "(CASE CONVERT(VARCHAR," . $fld . ",120) WHEN " . $qc . AdjustSql($val, $dbid) . $qc . " THEN 1 ELSE 0 END)";
		} else {
			if ($val == "NULL")
				return "(CASE WHEN " . $fld . " IS NULL THEN 1 ELSE 0 END)";
			else
				return "(CASE " . $fld . " WHEN " . $qc . AdjustSql($val, $dbid) . $qc . " THEN 1 ELSE 0 END)";
		}
	} elseif ($dbtype == "MYSQL") {
		if ($dateType == "y" && is_numeric($val)) {
			return "IF(YEAR(" . $fld . ")=" . $val . ",1,0)";
		} elseif ($dateType == "q" && is_numeric($val)) {
			return "IF(QUARTER(" . $fld . ")=" . $val . ",1,0)";
		} elseif ($dateType == "m" && is_numeric($val)) {
			return "IF(MONTH(" . $fld . ")=" . $val . ",1,0)";
		} else {
			if ($val == "NULL") {
				return "IF(" . $fld . " IS NULL,1,0)";
			} else {
				return "IF(" . $fld . "=" . $qc . AdjustSql($val, $dbid) . $qc . ",1,0)";
			}
		}
	} elseif ($dbtype == "SQLITE") {
		if ($dateType == "y" && is_numeric($val)) {
			return "(CASE CAST(STRFTIME('%Y', " . $fld . ") AS INTEGER) WHEN " . $val . " THEN 1 ELSE 0 END)";
		} elseif ($dateType == "q" && is_numeric($val)) {
			return "(CASE (CAST(STRFTIME('%m', " . $fld . ") AS INTEGER)+2)/3 WHEN " . $val . " THEN 1 ELSE 0 END)";
		} elseif ($dateType == "m" && is_numeric($val)) {
			return "(CASE CAST(STRFTIME('%m', " . $fld . ") AS INTEGER) WHEN " . $val . " THEN 1 ELSE 0 END)";
		} elseif ($dateType == "d") {
			return "(CASE STRFTIME('%Y-%m-%d'," . $fld . ") WHEN " . $qc . AdjustSql($val, $dbid) . $qc . " THEN 1 ELSE 0 END)";
		} else {
			if ($val == "NULL")
				return "(CASE WHEN " . $fld . " IS NULL THEN 1 ELSE 0 END)";
			else
				return "(CASE " . $fld . " WHEN " . $qc . AdjustSql($val, $dbid) . $qc . " THEN 1 ELSE 0 END)";
		}
	} elseif ($dbtype == "POSTGRESQL") {
		if ($dateType == "y" && is_numeric($val)) {
			return "CASE WHEN TO_CHAR(" . $fld . ",'YYYY')='" . $val . "' THEN 1 ELSE 0 END";
		} elseif ($dateType == "q" && is_numeric($val)) {
			return "CASE WHEN TO_CHAR(" . $fld . ",'Q')='" . $val . "' THEN 1 ELSE 0 END";
		} elseif ($dateType == "m" && is_numeric($val)) {
			return "CASE WHEN TO_CHAR(" . $fld . ",'MM')=LPAD('" . $val . "',2,'0') THEN 1 ELSE 0 END";
		} else {
			if ($val == "NULL") {
				return "CASE WHEN " . $fld . " IS NULL THEN 1 ELSE 0 END";
			} else {
				return "CASE WHEN " . $fld . "=" . $qc . AdjustSql($val, $dbid) . $qc . " THEN 1 ELSE 0 END";
			}
		}
	} elseif ($dbtype == "ORACLE") {
		if ($dateType == "y" && is_numeric($val)) {
			return "DECODE(TO_CHAR(" . $fld . ",'YYYY'),'" . $val . "',1,0)";
		} elseif ($dateType == "q" && is_numeric($val)) {
			return "DECODE(TO_CHAR(" . $fld . ",'Q'),'" . $val . "',1,0)";
		} elseif ($dateType == "m" && is_numeric($val)) {
			return "DECODE(TO_CHAR(" . $fld . ",'MM'),LPAD('" . $val . "',2,'0'),1,0)";
		} elseif ($dateType == "d") {
			return "DECODE(" . $fld . ",TO_DATE(" . $qc . AdjustSql($val, $dbid) . $qc . ",'YYYY/MM/DD'),1,0)";
		} elseif ($dateType == "dt") {
			return "DECODE(" . $fld . ",TO_DATE(" . $qc . AdjustSql($val, $dbid) . $qc . ",'YYYY/MM/DD HH24:MI:SS'),1,0)";
		} else {
			if ($val == "NULL") {
				return "(CASE WHEN " . $fld . " IS NULL THEN 1 ELSE 0 END)";
			} else {
				return "DECODE(" . $fld . "," . $qc . AdjustSql($val, $dbid) . $qc . ",1,0)";
			}
		}
	}
}

// Evaluate summary value
function SummaryValue($val1, $val2, $ityp) {
	switch ($ityp) {
	case "SUM":
	case "COUNT":
	case "AVG":
		if ($val2 === NULL || !is_numeric($val2)) {
			return $val1;
		} else {
			return ($val1 + $val2);
		}
	case "MIN":
		if ($val2 === NULL || !is_numeric($val2)) {
			return $val1; // Skip null and non-numeric
		} elseif ($val1 === NULL) {
			return $val2; // Initialize for first valid value
		} elseif ($val1 < $val2) {
			return $val1;
		} else {
			return $val2;
		}
	case "MAX":
		if ($val2 === NULL || !is_numeric($val2)) {
			return $val1; // Skip null and non-numeric
		} elseif ($val1 === NULL) {
			return $val2; // Initialize for first valid value
		} elseif ($val1 > $val2) {
			return $val1;
		} else {
			return $val2;
		}
	}
}

// Match filter value
function MatchedFilterValue($ar, $value) {
	if (!is_array($ar)) {
		return (strval($ar) == strval($value));
	} else {
		foreach ($ar as $val) {
			if (strval($val) == strval($value))
				return TRUE;
		}
		return FALSE;
	}
}

/**
 * Render repeat column table
 *
 * @param integer $totcnt Total count
 * @param integer $rowcnt Zero based row count
 * @param integer $repeatcnt Repeat count
 * @param integer $rendertype Render type (1 or 2)
 * @return string HTML
 */
function RepeatColumnTable($totcnt, $rowcnt, $repeatcnt, $rendertype) {
	$wrk = "";
	if ($rendertype == 1) { // Render control start
		if ($rowcnt == 0) $wrk .= "<table class=\"" . ITEM_TABLE_CLASSNAME . "\">";
		if ($rowcnt % $repeatcnt == 0) $wrk .= "<tr>";
		$wrk .= "<td>";
	} elseif ($rendertype == 2) { // Render control end
		$wrk .= "</td>";
		if ($rowcnt % $repeatcnt == $repeatcnt - 1) {
			$wrk .= "</tr>";
		} elseif ($rowcnt == $totcnt - 1) {
			for ($i = ($rowcnt % $repeatcnt) + 1; $i < $repeatcnt; $i++) {
				$wrk .= "<td>&nbsp;</td>";
			}
			$wrk .= "</tr>";
		}
		if ($rowcnt == $totcnt - 1) $wrk .= "</table>";
	}
	return $wrk;
}

// Check if the value is selected
function IsSelectedValue(&$ar, $value, $ft) {
	if (!is_array($ar))
		return TRUE;
	$af = StartsString("@@", $value);
	foreach ($ar as $val) {
		if ($af || StartsString("@@", $val)) { // Advanced filters
			if ($val == $value)
				return TRUE;
		} elseif (SameString($value, NULL_VALUE) && $value == $val) {
				return TRUE;
		} else {
			if (CompareValueByFieldType($val, $value, $ft))
				return TRUE;
		}
	}
	return FALSE;
}

// Check if advanced filter value
function IsAdvancedFilterValue($v) {
	if (is_array($v) && count($v) > 0) {
		foreach ($v as $val) {
			if (!StartsString("@@", $val))
				return FALSE;
		}
		return TRUE;
	} elseif (StartsString("@@", $v)) {
		return TRUE;
	}
	return FALSE;
}

// Compare values based on field type
function CompareValueByFieldType($v1, $v2, $ft) {
	switch ($ft) {

	// Case adBigInt, adInteger, adSmallInt, adTinyInt, adUnsignedTinyInt, adUnsignedSmallInt, adUnsignedInt, adUnsignedBigInt
	case 20:
	case 3:
	case 2:
	case 16:
	case 17:
	case 18:
	case 19:
	case 21:
		if (is_numeric($v1) && is_numeric($v2)) {
			return (intval($v1) == intval($v2));
		}
		break;

	// Case adSingle, adDouble, adNumeric, adCurrency
	case 4:
	case 5:
	case 131:
	case 6:
		if (is_numeric($v1) && is_numeric($v2)) {
			return ((float)$v1 == (float)$v2);
		}
		break;

	//	Case adDate, adDBDate, adDBTime, adDBTimeStamp
	case 7:
	case 133:
	case 134:
	case 135:
		if (is_numeric(strtotime($v1)) && is_numeric(strtotime($v2))) {
			return (strtotime($v1) == strtotime($v2));
		}
		break;
	default:
		return (strcmp($v1, $v2) == 0); // Treat as string
	}
}

// Register filter
function RegisterFilter(&$fld, $id, $name, $functionName = "") {
	if (!is_array($fld->AdvancedFilters))
		$fld->AdvancedFilters = [];
	$wrkid = StartsString("@@", $id) ? $id : "@@" . $id;
	$key = substr($wrkid, 2);
	$fld->AdvancedFilters[$key] = new AdvancedFilter($wrkid, $name, $functionName);
}

// Unregister filter
function UnregisterFilter(&$fld, $id) {
	if (is_array($fld->AdvancedFilters)) {
		$wrkid = StartsString("@@", $id) ? $id : "@@" . $id;
		$key = substr($wrkid, 2);
		foreach ($fld->AdvancedFilters as $filter) {
			if ($filter->ID == $wrkid) {
				unset($fld->AdvancedFilters[$key]);
				break;
			}
		}
	}
}

// Return date value
function DateValue($FldOpr, $FldVal, $ValType, $dbid = 0) {

	// Compose date string
	switch (strtolower($FldOpr)) {
		case "year":
			if ($ValType == 1) {
				$wrkVal = "$FldVal-01-01";
			} elseif ($ValType == 2) {
				$wrkVal = "$FldVal-12-31";
			}
			break;
		case "quarter":
			list($y, $q) = explode("|", $FldVal);
			if (intval($y) == 0 || intval($q) == 0) {
				$wrkVal = "0000-00-00";
			} else {
				if ($ValType == 1) {
					$m = ($q - 1) * 3 + 1;
					$m = str_pad($m, 2, "0", STR_PAD_LEFT);
					$wrkVal = "$y-$m-01";
				} elseif ($ValType == 2) {
					$m = ($q - 1) * 3 + 3;
					$m = str_pad($m, 2, "0", STR_PAD_LEFT);
					$wrkVal = "$y-$m-" . DaysInMonth($y, $m);
				}
			}
			break;
		case "month":
			list($y, $m) = explode("|", $FldVal);
			if (intval($y) == 0 || intval($m) == 0) {
				$wrkVal = "0000-00-00";
			} else {
				if ($ValType == 1) {
					$m = str_pad($m, 2, "0", STR_PAD_LEFT);
					$wrkVal = "$y-$m-01";
				} elseif ($ValType == 2) {
					$m = str_pad($m, 2, "0", STR_PAD_LEFT);
					$wrkVal = "$y-$m-" . DaysInMonth($y, $m);
				}
			}
			break;
		case "day":
		default:
			$wrkVal = str_replace("|", "-", $FldVal);
			$wrkVal = preg_replace('/\s+\d{2}\:\d{2}(\:\d{2})$/', "", $wrkVal); // Remove trailing time
	}

	// Add time if necessary
	if (preg_match('/(\d{4}|\d{2})-(\d{1,2})-(\d{1,2})/', $wrkVal)) { // Date without time
		if ($ValType == 1) {
			$wrkVal .= " 00:00:00";
		} elseif ($ValType == 2) {
			$wrkVal .= " 23:59:59";
		}
	}

	// Check if datetime
	if (preg_match('/(\d{4}|\d{2})-(\d{1,2})-(\d{1,2}) (\d{1,2}):(\d{1,2}):(\d{1,2})/', $wrkVal)) { // DateTime
		$DateVal = $wrkVal;
	} else {
		$DateVal = "";
	}

	// Change date format if necessary
	$dbType = GetConnectionType($dbid);
	if (!SameText($dbType, "MYSQL") && !SameText($dbType, "SQLITE"))
		$DateVal = str_replace("-", "/", $DateVal);
	return $DateVal;
}

// "Past"
function IsPast($FldExpression, $dbid = 0) {
	$dt = date("Y-m-d H:i:s");
	$dbType = GetConnectionType($dbid);
	if (!SameText($dbType, "MYSQL") && !SameText($dbType, "SQLITE"))
		$dt = str_replace("-", "/", $dt);
	return ("($FldExpression < " . QuotedValue($dt, DATATYPE_DATE, $dbid) . ")");
}

// "Future";
function IsFuture($FldExpression, $dbid = 0) {
	$dt = date("Y-m-d H:i:s");
	$dbType = GetConnectionType($dbid);
	if (!SameText($dbType, "MYSQL") && !SameText($dbType, "SQLITE"))
		$dt = str_replace("-", "/", $dt);
	return ("($FldExpression > " . QuotedValue($dt, DATATYPE_DATE, $dbid) . ")");
}

// "Last 30 days"
function IsLast30Days($FldExpression, $dbid = 0) {
	$dt1 = date("Y-m-d", strtotime("-29 days"));
	$dt2 = date("Y-m-d", strtotime("+1 days"));
	$dbType = GetConnectionType($dbid);
	if (!SameText($dbType, "MYSQL") && !SameText($dbType, "SQLITE")) {
		$dt1 = str_replace("-", "/", $dt1);
		$dt2 = str_replace("-", "/", $dt2);
	}
	return ("($FldExpression >= " . QuotedValue($dt1, DATATYPE_DATE, $dbid) . " AND $FldExpression < " . QuotedValue($dt2, DATATYPE_DATE, $dbid) . ")");
}

// "Last 14 days"
function IsLast14Days($FldExpression, $dbid = 0) {
	$dt1 = date("Y-m-d", strtotime("-13 days"));
	$dt2 = date("Y-m-d", strtotime("+1 days"));
	$dbType = GetConnectionType($dbid);
	if (!SameText($dbType, "MYSQL") && !SameText($dbType, "SQLITE")) {
		$dt1 = str_replace("-", "/", $dt1);
		$dt2 = str_replace("-", "/", $dt2);
	}
	return ("($FldExpression >= " . QuotedValue($dt1, DATATYPE_DATE, $dbid) . " AND $FldExpression < " . QuotedValue($dt2, DATATYPE_DATE, $dbid) . ")");
}

// "Last 7 days"
function IsLast7Days($FldExpression, $dbid = 0) {
	$dt1 = date("Y-m-d", strtotime("-6 days"));
	$dt2 = date("Y-m-d", strtotime("+1 days"));
	$dbType = GetConnectionType($dbid);
	if (!SameText($dbType, "MYSQL") && !SameText($dbType, "SQLITE")) {
		$dt1 = str_replace("-", "/", $dt1);
		$dt2 = str_replace("-", "/", $dt2);
	}
	return ("($FldExpression >= " . QuotedValue($dt1, DATATYPE_DATE, $dbid) . " AND $FldExpression < " . QuotedValue($dt2, DATATYPE_DATE, $dbid) . ")");
}

// "Next 30 days"
function IsNext30Days($FldExpression, $dbid = 0) {
	$dt1 = date("Y-m-d");
	$dt2 = date("Y-m-d", strtotime("+30 days"));
	$dbType = GetConnectionType($dbid);
	if (!SameText($dbType, "MYSQL") && !SameText($dbType, "SQLITE")) {
		$dt1 = str_replace("-", "/", $dt1);
		$dt2 = str_replace("-", "/", $dt2);
	}
	return ("($FldExpression >= " . QuotedValue($dt1, DATATYPE_DATE, $dbid) . " AND $FldExpression < " . QuotedValue($dt2, DATATYPE_DATE, $dbid) . ")");
}

// "Next 14 days"
function IsNext14Days($FldExpression, $dbid = 0) {
	$dt1 = date("Y-m-d");
	$dt2 = date("Y-m-d", strtotime("+14 days"));
	$dbType = GetConnectionType($dbid);
	if (!SameText($dbType, "MYSQL") && !SameText($dbType, "SQLITE")) {
		$dt1 = str_replace("-", "/", $dt1);
		$dt2 = str_replace("-", "/", $dt2);
	}
	return ("($FldExpression >= " . QuotedValue($dt1, DATATYPE_DATE, $dbid) . " AND $FldExpression < " . QuotedValue($dt2, DATATYPE_DATE, $dbid) . ")");
}

// "Next 7 days"
function IsNext7Days($FldExpression, $dbid = 0) {
	$dt1 = date("Y-m-d");
	$dt2 = date("Y-m-d", strtotime("+7 days"));
	$dbType = GetConnectionType($dbid);
	if (!SameText($dbType, "MYSQL") && !SameText($dbType, "SQLITE")) {
		$dt1 = str_replace("-", "/", $dt1);
		$dt2 = str_replace("-", "/", $dt2);
	}
	return ("($FldExpression >= " . QuotedValue($dt1, DATATYPE_DATE, $dbid) . " AND $FldExpression < " . QuotedValue($dt2, DATATYPE_DATE, $dbid) . ")");
}

// "Yesterday"
function IsYesterday($FldExpression, $dbid = 0) {
	$dt1 = date("Y-m-d", strtotime("-1 days"));
	$dt2 = date("Y-m-d");
	$dbType = GetConnectionType($dbid);
	if (!SameText($dbType, "MYSQL") && !SameText($dbType, "SQLITE")) {
		$dt1 = str_replace("-", "/", $dt1);
		$dt2 = str_replace("-", "/", $dt2);
	}
	return ("($FldExpression >= " . QuotedValue($dt1, DATATYPE_DATE, $dbid) . " AND $FldExpression < " . QuotedValue($dt2, DATATYPE_DATE, $dbid) . ")");
}

// "Today"
function IsToday($FldExpression, $dbid = 0) {
	$dt1 = date("Y-m-d");
	$dt2 = date("Y-m-d", strtotime("+1 days"));
	$dbType = GetConnectionType($dbid);
	if (!SameText($dbType, "MYSQL") && !SameText($dbType, "SQLITE")) {
		$dt1 = str_replace("-", "/", $dt1);
		$dt2 = str_replace("-", "/", $dt2);
	}
	return ("($FldExpression >= " . QuotedValue($dt1, DATATYPE_DATE, $dbid) . " AND $FldExpression < " . QuotedValue($dt2, DATATYPE_DATE, $dbid) . ")");
}

// "Tomorrow"
function IsTomorrow($FldExpression, $dbid = 0) {
	$dt1 = date("Y-m-d", strtotime("+1 days"));
	$dt2 = date("Y-m-d", strtotime("+2 days"));
	$dbType = GetConnectionType($dbid);
	if (!SameText($dbType, "MYSQL") && !SameText($dbType, "SQLITE")) {
		$dt1 = str_replace("-", "/", $dt1);
		$dt2 = str_replace("-", "/", $dt2);
	}
	return ("($FldExpression >= " . QuotedValue($dt1, DATATYPE_DATE, $dbid) . " AND $FldExpression < " . QuotedValue($dt2, DATATYPE_DATE, $dbid) . ")");
}

// "Last month"
function IsLastMonth($FldExpression, $dbid = 0) {
	$dt1 = date("Y-m", strtotime("-1 months")) . "-01";
	$dt2 = date("Y-m") . "-01";
	$dbType = GetConnectionType($dbid);
	if (!SameText($dbType, "MYSQL") && !SameText($dbType, "SQLITE")) {
		$dt1 = str_replace("-", "/", $dt1);
		$dt2 = str_replace("-", "/", $dt2);
	}
	return ("($FldExpression >= " . QuotedValue($dt1, DATATYPE_DATE, $dbid) . " AND $FldExpression < " . QuotedValue($dt2, DATATYPE_DATE, $dbid) . ")");
}

// "This month"
function IsThisMonth($FldExpression, $dbid = 0) {
	$dt1 = date("Y-m") . "-01";
	$dt2 = date("Y-m", strtotime("+1 months")) . "-01";
	$dbType = GetConnectionType($dbid);
	if (!SameText($dbType, "MYSQL") && !SameText($dbType, "SQLITE")) {
		$dt1 = str_replace("-", "/", $dt1);
		$dt2 = str_replace("-", "/", $dt2);
	}
	return ("($FldExpression >= " . QuotedValue($dt1, DATATYPE_DATE, $dbid) . " AND $FldExpression < " . QuotedValue($dt2, DATATYPE_DATE, $dbid) . ")");
}

// "Next month"
function IsNextMonth($FldExpression, $dbid = 0) {
	$dt1 = date("Y-m", strtotime("+1 months")) . "-01";
	$dt2 = date("Y-m", strtotime("+2 months")) . "-01";
	$dbType = GetConnectionType($dbid);
	if (!SameText($dbType, "MYSQL") && !SameText($dbType, "SQLITE")) {
		$dt1 = str_replace("-", "/", $dt1);
		$dt2 = str_replace("-", "/", $dt2);
	}
	return ("($FldExpression >= " . QuotedValue($dt1, DATATYPE_DATE, $dbid) . " AND $FldExpression < " . QuotedValue($dt2, DATATYPE_DATE, $dbid) . ")");
}

// "Last two weeks"
function IsLast2Weeks($FldExpression, $dbid = 0) {
	if (strtotime("this Sunday") == strtotime("today")) {
		$dt1 = date("Y-m-d", strtotime("-14 days this Sunday"));
		$dt2 = date("Y-m-d", strtotime("this Sunday"));
	} else {
		$dt1 = date("Y-m-d", strtotime("-14 days last Sunday"));
		$dt2 = date("Y-m-d", strtotime("last Sunday"));
	}
	$dbType = GetConnectionType($dbid);
	if (!SameText($dbType, "MYSQL") && !SameText($dbType, "SQLITE")) {
		$dt1 = str_replace("-", "/", $dt1);
		$dt2 = str_replace("-", "/", $dt2);
	}
	return ("($FldExpression >= " . QuotedValue($dt1, DATATYPE_DATE, $dbid) . " AND $FldExpression < " . QuotedValue($dt2, DATATYPE_DATE, $dbid) . ")");
}

// "Last week"
function IsLastWeek($FldExpression, $dbid = 0) {
	if (strtotime("this Sunday") == strtotime("today")) {
		$dt1 = date("Y-m-d", strtotime("-7 days this Sunday"));
		$dt2 = date("Y-m-d", strtotime("this Sunday"));
	} else {
		$dt1 = date("Y-m-d", strtotime("-7 days last Sunday"));
		$dt2 = date("Y-m-d", strtotime("last Sunday"));
	}
	$dbType = GetConnectionType($dbid);
	if (!SameText($dbType, "MYSQL") && !SameText($dbType, "SQLITE")) {
		$dt1 = str_replace("-", "/", $dt1);
		$dt2 = str_replace("-", "/", $dt2);
	}
	return ("($FldExpression >= " . QuotedValue($dt1, DATATYPE_DATE, $dbid) . " AND $FldExpression < " . QuotedValue($dt2, DATATYPE_DATE, $dbid) . ")");
}

// "This week"
function IsThisWeek($FldExpression, $dbid = 0) {
	if (strtotime("this Sunday") == strtotime("today")) {
		$dt1 = date("Y-m-d", strtotime("this Sunday"));
		$dt2 = date("Y-m-d", strtotime("+7 days this Sunday"));
	} else {
		$dt1 = date("Y-m-d", strtotime("last Sunday"));
		$dt2 = date("Y-m-d", strtotime("+7 days last Sunday"));
	}
	$dbType = GetConnectionType($dbid);
	if (!SameText($dbType, "MYSQL") && !SameText($dbType, "SQLITE")) {
		$dt1 = str_replace("-", "/", $dt1);
		$dt2 = str_replace("-", "/", $dt2);
	}
	return ("($FldExpression >= " . QuotedValue($dt1, DATATYPE_DATE, $dbid) . " AND $FldExpression < " . QuotedValue($dt2, DATATYPE_DATE, $dbid) . ")");
}

// "Next week"
function IsNextWeek($FldExpression, $dbid = 0) {
	if (strtotime("this Sunday") == strtotime("today")) {
		$dt1 = date("Y-m-d", strtotime("+7 days this Sunday"));
		$dt2 = date("Y-m-d", strtotime("+14 days this Sunday"));
	} else {
		$dt1 = date("Y-m-d", strtotime("+7 days last Sunday"));
		$dt2 = date("Y-m-d", strtotime("+14 days last Sunday"));
	}
	$dbType = GetConnectionType($dbid);
	if (!SameText($dbType, "MYSQL") && !SameText($dbType, "SQLITE")) {
		$dt1 = str_replace("-", "/", $dt1);
		$dt2 = str_replace("-", "/", $dt2);
	}
	return ("($FldExpression >= " . QuotedValue($dt1, DATATYPE_DATE, $dbid) . " AND $FldExpression < " . QuotedValue($dt2, DATATYPE_DATE, $dbid) . ")");
}

// "Next two week"
function IsNext2Weeks($FldExpression, $dbid = 0) {
	if (strtotime("this Sunday") == strtotime("today")) {
		$dt1 = date("Y-m-d", strtotime("+7 days this Sunday"));
		$dt2 = date("Y-m-d", strtotime("+21 days this Sunday"));
	} else {
		$dt1 = date("Y-m-d", strtotime("+7 days last Sunday"));
		$dt2 = date("Y-m-d", strtotime("+21 days last Sunday"));
	}
	$dbType = GetConnectionType($dbid);
	if (!SameText($dbType, "MYSQL") && !SameText($dbType, "SQLITE")) {
		$dt1 = str_replace("-", "/", $dt1);
		$dt2 = str_replace("-", "/", $dt2);
	}
	return ("($FldExpression >= " . QuotedValue($dt1, DATATYPE_DATE, $dbid) . " AND $FldExpression < " . QuotedValue($dt2, DATATYPE_DATE, $dbid) . ")");
}

// "Last year"
function IsLastYear($FldExpression, $dbid = 0) {
	$dt1 = date("Y", strtotime("-1 years")) . "-01-01";
	$dt2 = date("Y") . "-01-01";
	$dbType = GetConnectionType($dbid);
	if (!SameText($dbType, "MYSQL") && !SameText($dbType, "SQLITE")) {
		$dt1 = str_replace("-", "/", $dt1);
		$dt2 = str_replace("-", "/", $dt2);
	}
	return ("($FldExpression >= " . QuotedValue($dt1, DATATYPE_DATE, $dbid) . " AND $FldExpression < " . QuotedValue($dt2, DATATYPE_DATE, $dbid) . ")");
}

// "This year"
function IsThisYear($FldExpression, $dbid = 0) {
	$dt1 = date("Y") . "-01-01";
	$dt2 = date("Y", strtotime("+1 years")) . "-01-01";
	$dbType = GetConnectionType($dbid);
	if (!SameText($dbType, "MYSQL") && !SameText($dbType, "SQLITE")) {
		$dt1 = str_replace("-", "/", $dt1);
		$dt2 = str_replace("-", "/", $dt2);
	}
	return ("($FldExpression >= " . QuotedValue($dt1, DATATYPE_DATE, $dbid) . " AND $FldExpression < " . QuotedValue($dt2, DATATYPE_DATE, $dbid) . ")");
}

// "Next year"
function IsNextYear($FldExpression, $dbid = 0) {
	$dt1 = date("Y", strtotime("+1 years")) . "-01-01";
	$dt2 = date("Y", strtotime("+2 years")) . "-01-01";
	$dbType = GetConnectionType($dbid);
	if (!SameText($dbType, "MYSQL") && !SameText($dbType, "SQLITE")) {
		$dt1 = str_replace("-", "/", $dt1);
		$dt2 = str_replace("-", "/", $dt2);
	}
	return ("($FldExpression >= " . QuotedValue($dt1, DATATYPE_DATE, $dbid) . " AND $FldExpression < " . QuotedValue($dt2, DATATYPE_DATE, $dbid) . ")");
}

// Days in month
function DaysInMonth($y, $m) {
	if (in_array($m, [1, 3, 5, 7, 8, 10, 12])) {
		return 31;
	} elseif (in_array($m, [4, 6, 9, 11])) {
		return 30;
	} elseif ($m == 2) {
		return ($y % 4 == 0) ? 29 : 28;
	}
	return 0;
}

/**
 * Get group value
 * Field type:
 * 	1: numeric, 2: date, 3: string
 * Group type:
 * 	numeric: i = interval, n = normal
 * 	date: d = Day, w = Week, m = Month, q = Quarter, y = Year
 * 	string: f = first nth character, n = normal
 *
 * @param DbField $fld Field
 * @param mixed $val Value
 * @return mixed 
 */
function GroupValue(&$fld, $val) {
	$ft = $fld->Type;
	$grp = $fld->GroupByType;
	$intv = $fld->GroupInterval;
	switch ($ft) {

	// Case adBigInt, adInteger, adSmallInt, adTinyInt, adSingle, adDouble, adNumeric, adCurrency, adUnsignedTinyInt, adUnsignedSmallInt, adUnsignedInt, adUnsignedBigInt (numeric)
	case 20:
	case 3:
	case 2:
	case 16:
	case 4:
	case 5:
	case 131:
	case 6:
	case 17:
	case 18:
	case 19:
	case 21:
		if (!is_numeric($val)) return $val;	
		$wrkIntv = intval($intv);
		if ($wrkIntv <= 0) $wrkIntv = 10;
		switch ($grp) {
			case "i":
				return intval($val/$wrkIntv);
			default:
				return $val;
		}

	// Case adDate, adDBDate, adDBTime, adDBTimeStamp (date)
//	case 7:
//	case 133:
//	case 134:
//	case 135:
	// Case adLongVarChar, adLongVarWChar, adChar, adWChar, adVarChar, adVarWChar (string)

	case 201: // String
	case 203:
	case 129:
	case 130:
	case 200:
	case 202:
		$wrkIntv = intval($intv);
		if ($wrkIntv <= 0) $wrkIntv = 1;
		switch ($grp) {
			case "f":
				return substr($val, 0, $wrkIntv);
			default:
				return $val;
		}
	default:
		return $val; // Ignore
	}
}

// Display group value
function DisplayGroupValue(&$fld, $val) {
	global $ReportLanguage;
	$ft = $fld->Type;
	$grp = $fld->GroupByType;
	$intv = $fld->GroupInterval;
	if ($val === NULL) return $ReportLanguage->phrase("NullLabel");
	if ($val == "") return $ReportLanguage->phrase("EmptyLabel");
	switch ($ft) {

	// Case adBigInt, adInteger, adSmallInt, adTinyInt, adSingle, adDouble, adNumeric, adCurrency, adUnsignedTinyInt, adUnsignedSmallInt, adUnsignedInt, adUnsignedBigInt (numeric)
	case 20:
	case 3:
	case 2:
	case 16:
	case 4:
	case 5:
	case 131:
	case 6:
	case 17:
	case 18:
	case 19:
	case 21:
		$wrkIntv = intval($intv);
		if ($wrkIntv <= 0) $wrkIntv = 10;
		switch ($grp) {
			case "i":
				return strval($val*$wrkIntv) . " - " . strval(($val+1)*$wrkIntv-1);
			default:
				return $val;
		}
		break;

	// Case adDate, adDBDate, adDBTime, adDBTimeStamp (date)
	case 7:
	case 133:
	case 134:
	case 135:
		$ar = explode("|", $val);
		switch ($grp) {
			Case "y":
				return $ar[0];
			Case "q":
				if (count($ar) < 2) return $val;
				return FormatQuarter($ar[0], $ar[1]);
			Case "m":
				if (count($ar) < 2) return $val;
				return FormatMonth($ar[0], $ar[1]);
			Case "w":
				if (count($ar) < 2) return $val;
				return FormatWeek($ar[0], $ar[1]);
			Case "d":
				if (count($ar) < 3) return $val;
				return FormatDay($ar[0], $ar[1], $ar[2]);
			Case "h":
				return FormatHour($ar[0]);
			Case "min":
				return FormatMinute($ar[0]);
			default:
				return $val;
		}
		break;
	default: // String and others
		return $val; // Ignore
	}
}

// Format quarter
function FormatQuarter($y, $q) {
	return "Q" . $q . "/" . $y;
}

// Format month
function FormatMonth($y, $m) {
	return $m . "/" . $y;
}

// Format week
function FormatWeek($y, $w) {
	return "WK" . $w . "/" . $y;
}

// Format day
function FormatDay($y, $m, $d) {
	return $y . "-" . $m . "-" . $d;
}

// Format hour
function FormatHour($h) {
	if (intval($h) == 0) {
		return "12 AM";
	} elseif (intval($h) < 12) {
		return $h . " AM";
	} elseif (intval($h) == 12) {
		return "12 PM";
	} else {
		return ($h-12) . " PM";
	}
}

// Format minute
function FormatMinute($n) {
	return $n . " MIN";
}

// Return detail filter SQL
function DetailFilterSql(&$fld, $fn, $val, $dbid=0) {
	$ft = $fld->DataType;
	if ($fld->GroupSql <> "") $ft = DATATYPE_STRING;
	$sqlwrk = $fn;
	if ($val === NULL) {
		$sqlwrk .= " IS NULL";
	} else {
		$sqlwrk .= " = " . QuotedValue($val, $ft, $dbid);
	}
	return $sqlwrk;
}

// Return popup filter SQL
function FilterSql(&$fld, $fn, $ft, $dbid = 0) {
	$ar = $fld->SelectionList;
	$af = $fld->AdvancedFilters;
	$gt = $fld->GroupByType;
	$gi = $fld->GroupInterval;
	$sql = $fld->GroupSql;
	$dlm = $fld->Delimiter;
	if (!is_array($ar)) {
		return TRUE;
	} else {
		$sqlwrk = "";
		$i = 0;
		foreach ($ar as $value) {
			if (SameString($value, EMPTY_VALUE)) { // Empty string
				$sqlwrk .= "$fn = '' OR ";
			} elseif (SameString($value, NULL_VALUE)) { // Null value
				$sqlwrk .= "$fn IS NULL OR ";
			} elseif (StartsString("@@", $value)) { // Advanced filter
				if (is_array($af)) {
					$afsql = AdvancedFilterSql($af, $fn, $value, $dbid); // Process popup filter
					if ($afsql !== NULL)
						$sqlwrk .= $afsql . " OR ";
				}
			} elseif ($dlm <> "") {
				$sql = GetMultiValueSearchSql($fn, trim($value), $dbid);
				if ($sql <> "")
					$sqlwrk .= $sql . " OR ";
			} elseif ($sql <> "") {
				$sqlwrk .= str_replace("%s", $fn, $sql) . " = '" . $value . "' OR ";
			} else {
				$sqlwrk .= "$fn IN (" . JoinArray($ar, ", ", $ft, $i, $dbid) . ") OR ";
				break;
			}
			$i++;
		}
	}
	if ($sqlwrk != "")
		$sqlwrk = "(" . substr($sqlwrk, 0, -4) . ")";
	return $sqlwrk;
}

// Return multi-value search SQL
function GetMultiValueSearchSql($fn, $val, $dbid) {
	if (SameString($val, INIT_VALUE) || SameString($val, ALL_VALUE)) {
		$sql = "";
	} elseif (GetConnectionType($dbid) == "MYSQL") {
		$sql = "FIND_IN_SET('" . AdjustSql($val, $dbid) . "', " . $fn . ")";
	} else {
		$sql = $fn . " = '" . AdjustSql($val, $dbid) . "' OR " . GetMultiValueSearchSqlPart($fn, $val, $dbid);
	}
	return $sql;
}

// Get multi value search SQL part
function GetMultiValueSearchSqlPart($fn, $val, $dbid) {
	global $CSV_DELIMITER;
	return $fn . Like("'" . AdjustSql($val, $dbid) . $CSV_DELIMITER . "%'", $dbid) . " OR " .
		$fn . Like("'%" . $CSV_DELIMITER . AdjustSql($val, $dbid) . $CSV_DELIMITER . "%'", $dbid) . " OR " .
		$fn . Like("'%" . $CSV_DELIMITER . AdjustSql($val, $dbid) . "'", $dbid);
}

// Return Advanced Filter SQL
function AdvancedFilterSql(&$af, $fn, $val, $dbid = 0) {
	if (!is_array($af)) {
		return NULL;
	} elseif ($val === NULL) {
		return NULL;
	} else {
		foreach ($af as $filter) {
			if (SameString($val, $filter->ID) && $filter->Enabled && !empty($filter->FunctionName)) {
				$func = PROJECT_NAMESPACE . $filter->FunctionName;
				return $func($fn, $dbid);
			}
		}
		return NULL;
	}
}

// Compare values by custom sequence
function CompareValueCustom($v1, $v2, $seq) {
	if ($seq == "_number") { // Number
		if (is_numeric($v1) && is_numeric($v2)) {
			return ((float)$v1 > (float)$v2);
		}
	} else if ($seq == "_date") { // Date
		if (is_numeric(strtotime($v1)) && is_numeric(strtotime($v2))) {
			return (strtotime($v1) > strtotime($v2));
		}
	} else if ($seq <> "") { // Custom sequence
		if (is_array($seq))
			$ar = $seq;
		else
			$ar = explode(",", $seq);
		if (in_array($v1, $ar) && in_array($v2, $ar))
			return (array_search($v1, $ar) > array_search($v2, $ar));
		else
			return in_array($v2, $ar);
	}
	return ($v1 > $v2);
}

// Load array from sql
function LoadArrayFromSql($sql, &$ar) {
	if (strval($sql) == "")
		return;
	$rswrk = $GLOBALS["Conn"]->Execute($sql);
	if ($rswrk) {
		while (!$rswrk->EOF) {
			$v = $rswrk->fields[0];
			if ($v === NULL) {
				$v = NULL_VALUE;
			} elseif ($v == "") {
				$v = EMPTY_VALUE;
			}
			if (!is_array($ar))
				$ar = [];
			$ar[] = $v;
			$rswrk->MoveNext();
		}
		$rswrk->Close();
	}
}

// Function to Match array
function MatchedArray($ar1, $ar2) {
	if (!is_array($ar1) && !is_array($ar2)) {
		return TRUE;
	} elseif (is_array($ar1) && is_array($ar2)) {
		return (count(array_diff($ar1, $ar2)) == 0);
	}
	return FALSE;
}

// Reporting inserting event
function Report_Inserting($rsnew) {

	// Enter your code here
	return TRUE; // Return TRUE to insert, FALSE to skip
}

// Escape chars for XML
function XmlEncode($val) {
	return htmlspecialchars(strval($val));
}

// Adjust email content
function AdjustEmailContent($content) {
	$content = str_replace('class="ew-grid"', "", $content);
	$content = str_replace('class="ew-grid-middle-panel"', "", $content);
	$content = str_replace('class="table-responsive ew-grid-middle-panel"', "", $content);
	$content = str_replace("table ew-table", "ew-export-table", $content);
	$tableStyles = "border-collapse: collapse;";
	$CellStyles = "border: 1px solid #dddddd; padding: 5px;";
	$doc = new \DOMDocument("1.0", "utf-8");
	@$doc->loadHTML('<?xml encoding="utf-8">' . ConvertToUtf8($content)); // Convert to utf-8
	$tables = $doc->getElementsByTagName("table");
	foreach ($tables as $table) {
		if (ContainsText($table->getAttribute("class"), "ew-export-table")) {
			if ($table->hasAttribute("style"))
				$table->setAttribute("style", $table->getAttribute("style") . $tableStyles);
			else
				$table->setAttribute("style", $tableStyles);
			$rows = $table->getElementsByTagName("tr");
			$rowcnt = $rows->length;
			for ($i = 0; $i < $rowcnt; $i++) {
				$row = $rows->item($i);
				$cells = $row->childNodes;
				$cellcnt = $cells->length;
				for ($j = 0; $j < $cellcnt; $j++) {
					$cell = $cells->item($j);
					if ($cell->nodeType <> XML_ELEMENT_NODE || $cell->tagName <> "td")
						continue;
					if ($cell->hasAttribute("style"))
						$cell->setAttribute("style", $cell->getAttribute("style") . $CellStyles);
					else
						$cell->setAttribute("style", $CellStyles);
				}
			}
		}
	}
	$content = $doc->saveHTML();
	$content = ConvertFromUtf8($content);
	return $content;
}

// Load email count
function LoadEmailCount() {

	// Read from log
	if (EMAIL_WRITE_LOG) {
		$ip = ServerVar("REMOTE_ADDR");

		// Load from database
		if (EMAIL_WRITE_LOG_TO_DATABASE) {
			$dt1 = date("Y-m-d H:i:s", strtotime("- " . MAX_EMAIL_SENT_PERIOD . "minute"));
			$dt2 = date("Y-m-d H:i:s");
			$emailSql = "SELECT COUNT(*) FROM " . EMAIL_LOG_TABLE_NAME .
				" WHERE " . QuotedName(EMAIL_LOG_FIELD_NAME_DATETIME, EMAIL_LOG_TABLE_DBID) .
				" BETWEEN " . QuotedValue($dt1, DATATYPE_DATE, EMAIL_LOG_TABLE_DBID) . " AND " . QuotedValue($dt2, DATATYPE_DATE, EMAIL_LOG_TABLE_DBID) .
				" AND " . QuotedName(EMAIL_LOG_FIELD_NAME_IP, EMAIL_LOG_TABLE_DBID) . 
				" = " . QuotedValue($ip, DATATYPE_STRING, EMAIL_LOG_TABLE_DBID);
			$rscnt = Conn(EMAIL_LOG_TABLE_DBID)->Execute($emailSql);
			if ($rscnt) {
				$_SESSION[EXPORT_EMAIL_COUNTER] = ($rscnt->RecordCount()>1) ? $rscnt->RecordCount() : $rscnt->fields[0];
				$rscnt->Close();
			} else {
				$_SESSION[EXPORT_EMAIL_COUNTER] = 0;
			}

		// Load from log file
		} else {
			$pfx = "email";
			$tab = "\t";
			$folder = UPLOAD_DEST_PATH;
			$randomkey = Encrypt(date("Ymd"), RANDOM_KEY);
			$fn = $pfx . "_" . date("Ymd") . "_" . $randomkey . ".txt";
			$filename =UploadPath(TRUE, $folder) . $fn;
			if (file_exists($filename)) {
				$arLines = file($filename);
				$cnt = 0;
				foreach ($arLines as $line) {
					if ($line <> "") {
						list($dtwrk, $ipwrk, $senderwrk, $recipientwrk, $subjectwrk, $messagewrk) = explode($tab, $line);
						$timediff = intval((strtotime("now") - strtotime($dtwrk,0))/60);
						if ($ipwrk == $ip && $timediff < MAX_EMAIL_SENT_PERIOD) $cnt++;
					}
				}
				$_SESSION[EXPORT_EMAIL_COUNTER] = $cnt;
			} else {
				$_SESSION[EXPORT_EMAIL_COUNTER] = 0;
			}
		}
	}
	if (!isset($_SESSION[EXPORT_EMAIL_COUNTER]))
		$_SESSION[EXPORT_EMAIL_COUNTER] = 0;
	return intval($_SESSION[EXPORT_EMAIL_COUNTER]);
}

// Add email log
function AddEmailLog($sender, $recipient, $subject, $message) {
	if (!isset($_SESSION[EXPORT_EMAIL_COUNTER]))
		$_SESSION[EXPORT_EMAIL_COUNTER] = 0;
	$_SESSION[EXPORT_EMAIL_COUNTER]++;

	// Save to email log
	if (EMAIL_WRITE_LOG) {
		$dt = date("Y-m-d H:i:s");
		$ip = ServerVar("REMOTE_ADDR");
		$senderwrk = TruncateText($sender);
		$recipientwrk = TruncateText($recipient);
		$subjectwrk = TruncateText($subject);
		$messagewrk = TruncateText($message);

		// Save to database
		if (EMAIL_WRITE_LOG_TO_DATABASE) {
			$emailSql = "INSERT INTO " . EMAIL_LOG_TABLE_NAME .
				" (" . QuotedName(EMAIL_LOG_FIELD_NAME_DATETIME, EMAIL_LOG_TABLE_DBID) . ", " .
				QuotedName(EMAIL_LOG_FIELD_NAME_IP, EMAIL_LOG_TABLE_DBID) . ", " .
				QuotedName(EMAIL_LOG_FIELD_NAME_SENDER, EMAIL_LOG_TABLE_DBID) . ", " .
				QuotedName(EMAIL_LOG_FIELD_NAME_RECIPIENT, EMAIL_LOG_TABLE_DBID) . ", " .
				QuotedName(EMAIL_LOG_FIELD_NAME_SUBJECT, EMAIL_LOG_TABLE_DBID) . ", " .
				QuotedName(EMAIL_LOG_FIELD_NAME_MESSAGE, EMAIL_LOG_TABLE_DBID) . ") VALUES (" .
				QuotedValue($dt, DATATYPE_DATE, EMAIL_LOG_TABLE_DBID) . ", " .
				QuotedValue($ip, DATATYPE_STRING, EMAIL_LOG_TABLE_DBID) . ", " .
				QuotedValue($senderwrk, DATATYPE_STRING, EMAIL_LOG_TABLE_DBID) . ", " .
				QuotedValue($recipientwrk, DATATYPE_STRING, EMAIL_LOG_TABLE_DBID) . ", " .
				QuotedValue($subjectwrk, DATATYPE_STRING, EMAIL_LOG_TABLE_DBID) . ", " .
				QuotedValue($messagewrk, DATATYPE_STRING, EMAIL_LOG_TABLE_DBID) . ")";
			Conn(EMAIL_LOG_TABLE_DBID)->Execute($emailSql);

		// Save to log file
		} else {
			$pfx = "email";
			$tab = "\t";
			$header = "date/time" . $tab . "ip" . $tab . "sender" . $tab . "recipient" . $tab . "subject" . $tab . "message";
			$msg = $dt . $tab . $ip . $tab . $senderwrk . $tab . $recipientwrk . $tab . $subjectwrk . $tab . $messagewrk;
			$folder = UPLOAD_DEST_PATH;
			$randomkey = Encrypt(date("Ymd"), RANDOM_KEY);
			$fn = $pfx . "_" . date("Ymd") . "_" . $randomkey . ".txt";
			$filename =UploadPath(TRUE, $folder) . $fn;
			if (file_exists($filename)) {
				$fileHandler = fopen($filename, "a+b");
			} else {
				$fileHandler = fopen($filename, "a+b");
				fwrite($fileHandler,$header."\r\n");
			}
			fwrite($fileHandler, $msg."\r\n");
			fclose($fileHandler);
		}
	}
}

function TruncateText($v, $maxlen = EMAIL_LOG_SIZE_LIMIT) {
	$v = preg_replace('/[\f\n\r\t\v]/', " ", $v);
	if (strlen($v) > $maxlen)
		$v = substr($v, 0, $maxlen - 3) . "...";
	return $v;
}

// Get base URL
function BaseUrl() {
	$url = FullUrl();
	return substr($url, 0, strrpos($url, "/") + 1);
}

// Load selection from a filter clause
function LoadSelectionFromFilter(&$fld, $filter, &$sel, $af = "") {
	$sel = "";
	if ($af <> "") { // Set up advanced filter first
		$ar = is_array($af) ? $af : [$af];
		$cnt = count($ar);
		for ($i = 0; $i < $cnt; $i++) {
			if (StartsString("@@", $ar[$i])) {
				if (!is_array($sel))
					$sel = [];
				$sel[] = $ar[$i];
			}
		}
	}
	if ($filter <> "") {
		$sql = $fld->Lookup->getSql(FALSE, $filter);
		LoadArrayFromSql($sql, $sel);
	}
}

// Load drop down list
function LoadDropDownList(&$list, $val) {
	if (is_array($val)) {
		$ar = $val;
	} elseif ($val <> INIT_VALUE && $val <> ALL_VALUE && $val <> "") {
		$ar = [$val];
	} else {
		$ar = [];
	}
	$list = [];
	foreach ($ar as $v) {
		if ($v <> INIT_VALUE && $v <> "" && !StartsString("@@", $v))
			$list[] = $v;
	}
}

// Load selection list
function LoadSelectionList(&$list, $val) {
	if (is_array($val)) {
		$ar = $val;
	} elseif ($val <> INIT_VALUE && $val <> "") {
		$ar = [$val];
	} else {
		$ar = [];
	}
	$list = [];
	foreach ($ar as $v) {
		if (SameString($v, ALL_VALUE)) {
			$list = INIT_VALUE;
			return;
		} elseif ($v <> INIT_VALUE && $v <> "") {
			$list[] = $v;
		}
	}
	if (count($list) == 0)
		$list = INIT_VALUE;
}

// Get extended filter
function GetExtendedFilter(&$fld, $default = FALSE, $dbid = 0) {
	$dbtype = GetConnectionType($dbid);
	$fldName = $fld->Name;
	$fldExpression = $fld->Expression;
	$fldDataType = $fld->DataType;
	$fldDateTimeFormat = $fld->DateTimeFormat;
	$fldVal1 = ($default) ? $fld->AdvancedSearch->SearchValueDefault : $fld->AdvancedSearch->SearchValue;
	if (IsFloatFormat($fld->Type)) $fldVal1 = ConvertToFloatString($fldVal1);
	$fldOpr1 = ($default) ? $fld->AdvancedSearch->SearchOperatorDefault : $fld->AdvancedSearch->SearchOperator;
	$fldCond = ($default) ? $fld->AdvancedSearch->SearchConditionDefault : $fld->AdvancedSearch->SearchCondition;
	$fldVal2 = ($default) ? $fld->AdvancedSearch->SearchValue2Default : $fld->AdvancedSearch->SearchValue2;
	if (IsFloatFormat($fld->Type)) $fldVal2 = ConvertToFloatString($fldVal2);
	$fldOpr2 = ($default) ? $fld->AdvancedSearch->SearchOperator2Default : $fld->AdvancedSearch->SearchOperator2;
	$wrk = "";
	$fldOpr1 = strtoupper(trim($fldOpr1));
	if ($fldOpr1 == "") $fldOpr1 = "=";
	$fldOpr2 = strtoupper(trim($fldOpr2));
	if ($fldOpr2 == "") $fldOpr2 = "=";
	$wrkFldVal1 = $fldVal1;
	$wrkFldVal2 = $fldVal2;
	if ($fldDataType == DATATYPE_BOOLEAN) {
		if ($dbtype == "ACCESS") {
			if ($wrkFldVal1 <> "") $wrkFldVal1 = ($wrkFldVal1 == "1") ? "True" : "False";
			if ($wrkFldVal2 <> "") $wrkFldVal2 = ($wrkFldVal2 == "1") ? "True" : "False";
		} else {

			//if ($wrkFldVal1 <> "") $wrkFldVal1 = ($wrkFldVal1 == "1") ? TRUE_STRING : FALSE_STRING;
			//if ($wrkFldVal2 <> "") $wrkFldVal2 = ($wrkFldVal2 == "1") ? TRUE_STRING : FALSE_STRING;

			if ($wrkFldVal1 <> "") $wrkFldVal1 = ($wrkFldVal1 == "1") ? "1" : "0";
			if ($wrkFldVal2 <> "") $wrkFldVal2 = ($wrkFldVal2 == "1") ? "1" : "0";
		}
	} elseif ($fldDataType == DATATYPE_DATE) {
		if ($wrkFldVal1 <> "") $wrkFldVal1 = UnFormatDateTime($wrkFldVal1, $fldDateTimeFormat);
		if ($wrkFldVal2 <> "") $wrkFldVal2 = UnFormatDateTime($wrkFldVal2, $fldDateTimeFormat);
	}
	if ($fldOpr1 == "BETWEEN") {
		$isValidValue = ($fldDataType <> DATATYPE_NUMBER ||
			($fldDataType == DATATYPE_NUMBER && is_numeric($wrkFldVal1) && is_numeric($wrkFldVal2)));
		if ($wrkFldVal1 <> "" && $wrkFldVal2 <> "" && $isValidValue)
			$wrk = $fldExpression . " BETWEEN " . QuotedValue($wrkFldVal1, $fldDataType, $dbid) .
				" AND " . QuotedValue($wrkFldVal2, $fldDataType, $dbid);
	} else {

		// Handle first value
		if (SameString($fldVal1, NULL_VALUE) || $fldOpr1 == "IS NULL") {
			$wrk = $fldExpression . " IS NULL";
		} elseif (SameString($fldVal1, NOT_NULL_VALUE) || $fldOpr1 == "IS NOT NULL") {
			$wrk = $fldExpression . " IS NOT NULL";
		} else {
			$isValidValue = ($fldDataType <> DATATYPE_NUMBER ||
				($fldDataType == DATATYPE_NUMBER && is_numeric($wrkFldVal1)));
			if ($wrkFldVal1 <> "" && $isValidValue && IsValidOperator($fldOpr1, $fldDataType))
				$wrk = $fldExpression . GetFilterSql($fldOpr1, $wrkFldVal1, $fldDataType, $dbid);
		}

		// Handle second value
		$wrk2 = "";
		if (SameString($fldVal2, NULL_VALUE) || $fldOpr2 == "IS NULL") {
			$wrk2 = $fldExpression . " IS NULL";
		} elseif (SameString($fldVal2, NOT_NULL_VALUE) || $fldOpr2 == "IS NOT NULL") {
			$wrk2 = $fldExpression . " IS NOT NULL";
		} else {
			$isValidValue = ($fldDataType <> DATATYPE_NUMBER ||
				($fldDataType == DATATYPE_NUMBER && is_numeric($wrkFldVal2)));
			if ($wrkFldVal2 <> "" && $isValidValue && IsValidOperator($fldOpr2, $fldDataType))
				$wrk2 = $fldExpression . GetFilterSql($fldOpr2, $wrkFldVal2, $fldDataType, $dbid);
		}

		// Combine SQL
		if ($wrk2 <> "") {
			if ($wrk <> "")
				$wrk = "(" . $wrk . " " . (($fldCond == "OR") ? "OR" : "AND") . " " . $wrk2 . ")";
			else
				$wrk = $wrk2;
		}
	}
	return $wrk;
}

// Check if valid operator
function IsValidOperator($opr, $fldDataType) {
	$valid = in_array($opr, ['=', '<>', '<', '<=', '>', '>=']);
	if ($fldDataType == DATATYPE_STRING || $fldDataType == DATATYPE_MEMO)
		$valid = $valid || in_array($opr, ['LIKE', 'NOT LIKE', 'STARTS WITH', 'ENDS WITH']);
	return $valid;
}

// Return search string
function GetFilterSql($fldOpr, $fldVal, $fldType, $dbid = 0) {
	if (SameString($fldVal, NULL_VALUE) || $fldOpr == "IS NULL") {
		return " IS NULL";
	} elseif (SameString($fldVal, NOT_NULL_VALUE) || $fldOpr == "IS NOT NULL") {
		return " IS NOT NULL";
	} elseif ($fldOpr == "LIKE") {
		return Like(QuotedValue("%$fldVal%", $fldType, $dbid), $dbid);
	} elseif ($fldOpr == "NOT LIKE") {
		return " NOT " . Like(QuotedValue("%$fldVal%", $fldType, $dbid), $dbid);
	} elseif ($fldOpr == "STARTS WITH") {
		return Like(QuotedValue("$fldVal%", $fldType, $dbid), $dbid);
	} elseif ($fldOpr == "ENDS WITH") {
		return Like(QuotedValue("%$fldVal", $fldType, $dbid), $dbid);
	} else {
		return " $fldOpr " . QuotedValue($fldVal, $fldType, $dbid);
	}
}

// Return date search string
function GetDateFilterSql($fldExpr, $fldOpr, $fldVal, $fldType, $dbid = 0) {
	if ($fldOpr == "Year" && $fldVal <> "") { // Year filter
		return GroupSql($fldExpr, "y", 0, $dbid) . " = " . $fldVal;
	} else {
		$wrkVal1 = DateValue($fldOpr, $fldVal, 1, $dbid);
		$wrkVal2 = DateValue($fldOpr, $fldVal, 2, $dbid);
		if ($wrkVal1 <> "" && $wrkVal2 <> "") {
			return $fldExpr . " BETWEEN " . QuotedValue($wrkVal1, $fldType, $dbid) . " AND " . QuotedValue($wrkVal2, $fldType, $dbid);
		} else {
			return "";
		}
	}
}

// Group filter
function GroupSql($fldExpr, $grpType, $grpInt = 0, $dbid = 0) {
	$dbtype = GetConnectionType($dbid);
	switch ($grpType) {
		case "f": // First n characters
			if ($dbtype == "ACCESS") // Access
				return "MID(" . $fldExpr . ",1," . $grpInt . ")";
			else if ($dbtype == "MSSQL" || $dbtype == "MYSQL") // MSSQL / MySQL
				return "SUBSTRING(" . $fldExpr . ",1," . $grpInt . ")";
			else // SQLite / PostgreSql / Oracle
				return "SUBSTR(" . $fldExpr . ",1," . $grpInt . ")";
			break;
		case "i": // Interval
			if ($dbtype == "ACCESS") // Access
				return "(" . $fldExpr . "\\" . $grpInt . ")";
			else if ($dbtype == "MSSQL") // MSSQL
				return "(" . $fldExpr . "/" . $grpInt . ")";
			else if ($dbtype == "MYSQL") // MySQL
				return "(" . $fldExpr . " DIV " . $grpInt . ")";
			else if ($dbtype == "SQLITE") // SQLite
				return "CAST(" . $fldExpr . "/" . $grpInt . " AS TEXT)";
			else if ($dbtype == "POSTGRESQL") // PostgreSql
				return "(" . $fldExpr . "/" . $grpInt . ")";
			else // Oracle
				return "FLOOR(" . $fldExpr . "/" . $grpInt . ")";
			break;
		case "y": // Year
			if ($dbtype == "ACCESS" || $dbtype == "MSSQL" || $dbtype == "MYSQL") // Access / MSSQL / MySQL
				return "YEAR(" . $fldExpr . ")";
			else if ($dbtype == "SQLITE") // SQLite
				return "CAST(STRFTIME('%Y'," . $fldExpr . ") AS INTEGER)";
			else // PostgreSql / Oracle
				return "TO_CHAR(" . $fldExpr . ",'YYYY')";
			break;
		case "xq": // Quarter
			if ($dbtype == "ACCESS") // Access
				return "FORMAT(" . $fldExpr . ", 'q')";
			else if ($dbtype == "MSSQL") // MSSQL
				return "DATEPART(QUARTER," . $fldExpr . ")";
			else if ($dbtype == "MYSQL") // MySQL
				return "QUARTER(" . $fldExpr . ")";
			else if ($dbtype == "SQLITE") // SQLite
				return "CAST(STRFTIME('%m'," . $fldExpr . ") AS INTEGER)+2)/3";
			else // PostgreSql / Oracle
				return "TO_CHAR(" . $fldExpr . ",'Q')";
			break;
		case "q": // Quarter (with year)
			if ($dbtype == "ACCESS") // Access
				return "FORMAT(" . $fldExpr . ", 'yyyy|q')";
			else if ($dbtype == "MSSQL") // MSSQL
				return "(STR(YEAR(" . $fldExpr . "),4) + '|' + STR(DATEPART(QUARTER," . $fldExpr . "),1))";
			else if ($dbtype == "MYSQL") // MySQL
				return "CONCAT(CAST(YEAR(" . $fldExpr . ") AS CHAR(4)), '|', CAST(QUARTER(" . $fldExpr . ") AS CHAR(1)))";
			else if ($dbtype == "SQLITE") // SQLite
				return "(CAST(STRFTIME('%Y'," . $fldExpr . ") AS TEXT) || '|' || CAST((CAST(STRFTIME('%m'," . $fldExpr . ") AS INTEGER)+2)/3 AS TEXT))";
			else // PostgreSql / Oracle
				return "(TO_CHAR(" . $fldExpr . ",'YYYY') || '|' || TO_CHAR(" . $fldExpr . ",'Q'))";
			break;
		case "xm": // Month
			if ($dbtype == "ACCESS") // Access
				return "FORMAT(" . $fldExpr . ", 'mm')";
			else if ($dbtype == "MSSQL" || $dbtype == "MYSQL") // MSSQL / MySQL
				return "MONTH(" . $fldExpr . ")";
			else if ($dbtype == "SQLITE") // SQLite
				return "CAST(STRFTIME('%m'," . $fldExpr . ") AS INTEGER)";
			else // PostgreSql / Oracle
				return "TO_CHAR(" . $fldExpr . ",'MM')";
			break;
		case "m": // Month (with year)
			if ($dbtype == "ACCESS") // Access
				return "FORMAT(" . $fldExpr . ", 'yyyy|mm')";
			else if ($dbtype == "MSSQL") // MSSQL
				return "(STR(YEAR(" . $fldExpr . "),4) + '|' + REPLACE(STR(MONTH(" . $fldExpr . "),2,0),' ','0'))";
			else if ($dbtype == "MYSQL") // MySQL
				return "CONCAT(CAST(YEAR(" . $fldExpr . ") AS CHAR(4)), '|', CAST(LPAD(MONTH(" . $fldExpr . "),2,'0') AS CHAR(2)))";
			else if ($dbtype == "SQLITE") // SQLite
				return "CAST(STRFTIME('%Y|%m'," . $fldExpr . ") AS TEXT)";
			else // PostgreSql / Oracle
				return "(TO_CHAR(" . $fldExpr . ",'YYYY') || '|' || TO_CHAR(" . $fldExpr . ",'MM'))";
			break;
		case "w":
			if ($dbtype == "ACCESS") // Access
				return "FORMAT(" . $fldExpr . ", 'yyyy|ww')";
			else if ($dbtype == "MSSQL") // MSSQL
				return "(STR(YEAR(" . $fldExpr . "),4) + '|' + REPLACE(STR(DATEPART(WEEK," . $fldExpr . "),2,0),' ','0'))";
			else if ($dbtype == "MYSQL") // MySQL

				//return "CONCAT(CAST(YEAR(" . $fldExpr . ") AS CHAR(4)), '|', CAST(LPAD(WEEKOFYEAR(" . $fldExpr . "),2,'0') AS CHAR(2)))";
				return "CONCAT(CAST(YEAR(" . $fldExpr . ") AS CHAR(4)), '|', CAST(LPAD(WEEK(" . $fldExpr . ",0),2,'0') AS CHAR(2)))";
			else if ($dbtype == "SQLITE") // SQLite
				return "CAST(STRFTIME('%Y|%W'," . $fldExpr . ") AS TEXT)";
			else
				return "(TO_CHAR(" . $fldExpr . ",'YYYY') || '|' || TO_CHAR(" . $fldExpr . ",'WW'))";
			break;
		case "d":
			if ($dbtype == "ACCESS") // Access
				return "FORMAT(" . $fldExpr . ", 'yyyy|mm|dd')";
			else if ($dbtype == "MSSQL") // MSSQL
				return "(STR(YEAR(" . $fldExpr . "),4) + '|' + REPLACE(STR(MONTH(" . $fldExpr . "),2,0),' ','0') + '|' + REPLACE(STR(DAY(" . $fldExpr . "),2,0),' ','0'))";
			else if ($dbtype == "MYSQL") // MySQL
				return "CONCAT(CAST(YEAR(" . $fldExpr . ") AS CHAR(4)), '|', CAST(LPAD(MONTH(" . $fldExpr . "),2,'0') AS CHAR(2)), '|', CAST(LPAD(DAY(" . $fldExpr . "),2,'0') AS CHAR(2)))";
			else if ($dbtype == "SQLITE") // SQLite
				return "CAST(STRFTIME('%Y|%m|%d'," . $fldExpr . ") AS TEXT)";
			else
				return "(TO_CHAR(" . $fldExpr . ",'YYYY') || '|' || LPAD(TO_CHAR(" . $fldExpr . ",'MM'),2,'0') || '|' || LPAD(TO_CHAR(" . $fldExpr . ",'DD'),2,'0'))";
			break;
		case "h":
			if ($dbtype == "ACCESS" || $dbtype == "MSSQL" || $dbtype == "MYSQL") // Access / MSSQL / MySQL
				return "HOUR(" . $fldExpr . ")";
			else if ($dbtype == "SQLITE") // SQLite
				return "CAST(STRFTIME('%H'," . $fldExpr . ") AS INTEGER)";
			else
				return "TO_CHAR(" . $fldExpr . ",'HH24')";
			break;
		case "min":
			if ($dbtype == "ACCESS" || $dbtype == "MSSQL" || $dbtype == "MYSQL") // Access / MSSQL / MySQL
				return "MINUTE(" . $fldExpr . ")";
			else if ($dbtype == "SQLITE") // SQLite
				return "CAST(STRFTIME('%M'," . $fldExpr . ") AS INTEGER)";
			else
				return "TO_CHAR(" . $fldExpr . ",'MI')";
			break;
	}
	return "";
}

// Get temp chart image
function TempChartImage($id, $custom = FALSE) {
	global $TempImages;
	$exportid = Param("exportid", "");
	if ($exportid <> "") {
		$file = $exportid . "_" . $id . ".png";
		$folder = AppRoot() . UPLOAD_DEST_PATH;
		$f = $folder . $file;
		if (file_exists($f)) {
			$tmpimage = basename($f);
			$TempImages[] = $tmpimage;
			$export = $custom ? "print" : Param("export", "");
			return TempImageLink($tmpimage, $export);
		}
		return "";
	}
}

// Check HTML for export
function CheckHtml($html) {
	$p1 = 'class="ew-table"';
	$p2 = ' data-page-break="before"';
	$p = '/' . preg_quote($p1) . '|' . preg_quote($p2) . '|' . preg_quote(PAGE_BREAK_HTML, '/') . '/';
	if (preg_match_all($p, $html, $matches, PREG_OFFSET_CAPTURE)) {
		foreach ($matches[0] as $match) {
			if ($match[0] == $p1) { // If table, break
				break;
			} elseif ($match[0] == PAGE_BREAK_HTML) { // If page breaks (no table before), remove and continue
				$html = preg_replace('/' . preg_quote($match[0], "/") . '/', "", $html, 1);
				continue;
			} elseif ($match[0] == $p2) { // If page breaks (no table before), remove and break
				$html = preg_replace('/' . preg_quote($match[0]) . '/', "", $html, 1);
				break;
			}
		}
	}
	return $html;
}
?>