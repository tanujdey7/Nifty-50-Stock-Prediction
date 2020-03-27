<?php

/**
 * Gantt chart classes for PHP Report Maker 12
 */
namespace PHPReportMaker12\project1;

/**
 * Gantt Chart Categories
 */
class GanttCategories {
	public $Title = "";
	public $Interval = GANTT_INTERVAL_NONE; // 0-5
	public $CategoriesAttrs = [];
	public $CategoryAttrs = [];
	public $StartDate;
	public $EndDate;

	function SetTitle($title) {
		$this->Title = $title;
		$this->Interval = GANTT_INTERVAL_NONE; // Reset
	}

	function SetInterval($interval) {
		$this->Interval = $interval;
		$this->Title = ""; // Reset
	}
}

/**
 * Gantt Chart Data Column
 */
class GanttDataColumn {
	public $FieldName = ""; // Field name
	public $Caption = ""; // Header text
	public $ColumnAttrs = [];
	public $TextAttrs = [];
	public $FormatFunction = "";

	// Constructor
	function __construct($fldname, $caption, $formatfunc) {
		$this->FieldName = $fldname;
		$this->Caption = $caption;
		$this->FormatFunction = $formatfunc;
	}
}

/**
 * Gantt Chart
 */
class GanttChart extends DbChart {
	public $Name = "";
	public $ChartWidth; // Chart Width
	public $ChartHeight; // Chart Height
	public $ProcessesHeaderText;
	public $DateFormat = "yyyy/mm/dd";

	// Tables
	public $TaskTable = "";
	public $TaskTableDbId = "";
	public $ProcessTable = ""; // Optional
	public $ProcessTableDbId = ""; // Optional
	public $MilestoneTable = ""; // Optional
	public $MilestoneTableDbId = ""; // Optional
	public $ConnectorTable = ""; // Optional
	public $ConnectorTableDbId = ""; // Optional
	public $TrendlineTable = ""; // Optional
	public $TrendlineTableDbId = ""; // Optional

	// Task Table Fields
	public $TaskIdField = "";
	public $TaskNameField = "";
	public $TaskStartField = "";
	public $TaskEndField = "";
	public $TaskResourceIdField = "";
	public $TaskDurationField = "";
	public $TaskPercentCompleteField = "";
	public $TaskDependenciesField = "";
	public $TaskFromTaskIdField = ""; // Optional
	public $TaskMilestoneDateField = ""; // Optional
	public $TaskFilter = ""; // Table filter
	public $TaskIdFilter = ""; // Task Id filter
	public $TaskNameFilter = ""; // Task Name filter

	// Category
	public $Categories = []; // Array of GanttCategories
	public $Intervals = []; // Array of category intervals
	public $Connectors = []; // Array of connectors
	public $Trendlines = []; // Array of trendlines
	public $Milestones = []; // Array of milestones
	public $StartDate;
	public $EndDate;
	public $FixedStartDate; // Must in yyyy-mm-dd format
	public $FixedEndDate; // Must in yyyy-mm-dd format

	// Data columns
	public $DataColumns = []; // Array of GanttDataColumn

	// XML DOMDocument object
	var $XmlDocument;

	// Default styles
	public $HeaderColor = "4567AA";
	public $HeaderFontColor = "FFFFFF";
	public $CategoryColor = "";
	public $CategoryFontColor = "";
	public $HeaderIsBold = "1";
	public $TaskColors = [
		"FF0000", "FF0080", "FF00FF", "8000FF",
		"FF8000", "FF3D3D", "7AFFFF", "0000FF",
		"FFFF00", "FF7A7A", "3DFFFF", "0080FF",
		"80FF00", "00FF00", "00FF80", "00FFFF"
	]; // Task colors
	public $ShowWeekNumber = TRUE;

	// Chart properties
	public $ChartAttrs = []; // Attributes for <chart>
	public $ProcessesAttrs = []; // Attributes for <processes>
	public $ProcessAttrs = []; // Attributes for <process>
	public $TasksAttrs = []; // Attributes for <tasks>
	public $TaskAttrs = ["alpha"=>75]; // Attributes for <task>
	public $ConnectorsAttrs = []; // Attributes for <connectors>
	public $ConnectorAttrs = []; // Attributes for <connector>
	public $DataTableAttrs = []; // Attributes for <datatable>
	public $TrendlineAttrs = []; // Attributes for <trendline>
	public $MilestoneAttrs = ["radius" => "6", "shape" => "Polygon", "numSides" => "4", "borderColor" => "333333", "borderThickness" => "1"]; // Attributes for <milestone>

	// Connection
	public $Connection;

	// Constructor
	function __construct($table, $dbid) {
		global $ReportLanguage;
		$this->Type = "6098";
		$this->TaskTable = $table;
		$this->TaskTableDbId = $dbid;
		$this->Connection = &Conn($this->TaskTableDbId);
		$this->XmlDocument = new \DOMDocument("1.0", "utf-8");
		$this->XmlDocument->appendChild($this->XmlDocument->createElement("chart"));
		$this->ChartAttrs["extendcategoryBg"] = "0";
		$this->ProcessesHeaderText = $ReportLanguage->phrase("Tasks");
	}

	// Set attribute
	function setAttribute(&$element, $name, $value) {
		if (!$element)
			return;
		$element->setAttribute($name, ConvertToUtf8($value));
	}

	// Get inteval as integer
	function getIntervalValue($interval) {
		$interval = strtoupper($interval);
		if ($interval == "_YEAR") {
			return GANTT_INTERVAL_YEAR;
		} elseif ($interval == "_QUARTER") {
			return GANTT_INTERVAL_QUARTER;
		} elseif ($interval == "_MONTH") {
			return GANTT_INTERVAL_MONTH;
		} elseif ($interval == "_WEEK") {
			return GANTT_INTERVAL_WEEK;
		} elseif ($interval == "_DAY") {
			return GANTT_INTERVAL_DAY;
		}
		return GANTT_INTERVAL_NONE;
	}

	// Add categories
	function addCategories($type) {
		if ($type == "")
			return;
		if (in_array(strtoupper($type), ["_YEAR", "_QUARTER", "_MONTH", "_WEEK", "_DAY"])) { // Interval
			$intv = $this->getIntervalValue($type);
			if ($intv > GANTT_INTERVAL_NONE) {
				$this->Intervals[] = $intv;
				$cats = new GanttCategories();
				$cats->setInterval($intv);
				$this->Categories[$type] = $cats;
			}
		} else { // Title
			$cats = new GanttCategories();
			$cats->setTitle($type);
			$this->Categories[$type] = $cats;
		}
	}

	// Add data column
	function addDataColumn($fldname, $caption, $formatfunc = "") {
		$this->DataColumns[$fldname] = new GanttDataColumn($fldname, $caption, $formatfunc);
	}

	// Add connector
	function addConnector($ar) {
		$this->Connectors[] = $ar;
	}

	// Add trendline
	function addTrendline($ar) {
		$this->Trendlines[] = $ar;
	}

	// Add milestone
	function addMilestone($ar) {
		$this->Milestones[] = $ar;
	}

	// Create datetime
	function createDateTime($hour, $min, $sec, $month, $day, $year) {
		return mktime($hour, $min, $sec, $month, $day, $year);
	}

	// Get datetime info
	function getDateTime($ts) {
		return getdate($ts);
	}

	// Get datetime info
	function formatDate($format, $date) {
		return date($format, $date);
	}

	// Convert Year/Month/Day to string
	function ymdToString($y, $m, $d) {
		if ($this->DateFormat == "mm/dd/yyyy") {
			return str_pad($m, 2, "0", STR_PAD_LEFT) . "/" . str_pad($d, 2, "0", STR_PAD_LEFT) . "/" . strval($y);
		} elseif ($this->DateFormat == "dd/mm/yyyy") {
			return str_pad($d, 2, "0", STR_PAD_LEFT) . "/" . str_pad($m, 2, "0", STR_PAD_LEFT) . "/" . strval($y);
		} else { // "yyyy/mm/dd"
			return strval($y) . "/" . str_pad($m, 2, "0", STR_PAD_LEFT) . "/" . str_pad($d, 2, "0", STR_PAD_LEFT);
		}
	}

	// Convert date time info (from getdate) to string
	function dateTimeToString($dt) {
		return $this->ymdToString($dt["year"], $dt["mon"], $dt["mday"]);
	}

	// Convert date (timestamp) to string
	function dateToString($d) {
		$dt = $this->getDateTime($d);
		return $this->dateTimeToString($dt);
	}

	// Convert database date (yyyy-mm-dd) to yyyy/mm/dd
	function dbdateToString($str) {
		$date = $this->parseDate($str);
		return $this->dateToString($date);
	}

	// Parse string to datetime
	function parseDate($str) {
		if (preg_match('/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/', $str, $matches)) { // DateTime
			$year = $matches[1];
			$month = $matches[2];
			$day = $matches[3];
			$hour = $matches[4];
			$min = $matches[5];
			$sec = $matches[6];
			return $this->createDateTime($hour, $min, $sec, $month, $day, $year);
		} elseif (preg_match('/(\d{4})-(\d{2})-(\d{2})/', $str, $matches)) { // Date
			$year = $matches[1];
			$month = $matches[2];
			$day = $matches[3];
			return $this->createDateTime(0, 0, 0, $month, $day, $year);
		}
		return $str;
	}

	// Get task color
	function getTaskColor($i) {
		$color = "";
		if (is_array($this->TaskColors)) {
			$cntar = count($this->TaskColors);
			if ($cntar > 0) {
				$color = $this->TaskColors[$i % $cntar];
				$color = str_replace("#", "", $color);
			}
		}
		return $color;
	}

	// Set up start/end dates
	function setupStartEnd() {
		$sql = "SELECT MIN(" . QuotedName($this->TaskStartField, $this->TaskTableDbId) . "), MAX(". QuotedName($this->TaskEndField, $this->TaskTableDbId) . ") FROM " . $this->TaskTable;
		if ($this->TaskFilter <> "") $sql .= " WHERE " . $this->TaskFilter;
		$rs = $this->Connection->Execute($sql);
		if ($rs && !$rs->EOF) {
			$start = $rs->fields[0];
			$end = $rs->fields[1];
			$rs->Close();
		} else {
			die("Error: Missing tasks.");
		}
		$start = $this->parseDate($start);
		$end = $this->parseDate($end);
		$arStart = $this->getDateTime($start);
		$arEnd = $this->getDateTime($end);
		$min = $start;
		$max = $end;
		$cnt = 0;
		foreach ($this->Intervals as $interval) {
			if ($interval == GANTT_INTERVAL_YEAR ||
				$interval == GANTT_INTERVAL_QUARTER ||
				$interval == GANTT_INTERVAL_MONTH) {
				$mon = intval($arStart["mon"]);
				$tempstart = $this->createDateTime(0, 0, 0, $mon, 1, intval($arStart["year"]));
				$yr = intval($arEnd["year"]);
				$mon = intval($arEnd["mon"]);
				$tempend = $this->createDateTime(0, 0, 0, $mon, DaysInMonth($yr, $mon), intval($arEnd["year"]));
				$cnt++;
			} elseif ($interval == GANTT_INTERVAL_WEEK) {
				$wday = $arStart["wday"];
				$diff = ($wday >= GANTT_WEEK_START) ? ($wday - GANTT_WEEK_START) : ($wday + 7 - GANTT_WEEK_START);
				$tempstart = $start - $diff * 86400;
				$wday = $arEnd["wday"];
				$diff = ($wday >= GANTT_WEEK_START) ? ($wday - GANTT_WEEK_START) : ($wday + 7 - GANTT_WEEK_START);
				$tempend = $end + (6 - $diff) * 86400;
				$cnt++;
			}

			// Start date
			if ($tempstart < $min)
				$min = $tempstart;

			// End date
			if ($tempend > $max)
				$max = $tempend;
		}
		if ($cnt == 0) {
			$min -= 86400;
			$max += 86400;
		}
		$this->StartDate = $min;
		$this->EndDate = $max;

		// Check if fixed start date specified
		if (isset($this->FixedStartDate)) {
			$fd = $this->parseDate($this->FixedStartDate);
			if ($fd !== FALSE)
				$this->StartDate = $fd;
		}

		// Check if fixed end date specified
		if (isset($this->FixedEndDate)) {
			$fd = $this->parseDate($this->FixedEndDate);
			if ($fd !== FALSE)
				$this->EndDate = $fd;
		}
	}

	// Output table
	function OutputQuery($sql, $dbid, $tagname, $childtagname, $attrs, $childattrs) {
		$conn = &Conn($dbid);
		$rs = $conn->Execute($sql);
		$this->outputArray($rs->GetRows(), $tagname, $childtagname, $attrs, $childattrs);
		if ($rs)
			$rs->Close();
	}

	// Output table
	function OutputArray($rs, $tagname, $childtagname, $attrs, $childattrs) {
		if (is_array($rs)) {
			$elements = $this->XmlDocument->getElementsByTagName($tagname);
			$el = NULL;
			foreach ($elements as $element)
				$el = $element;
			if (!$el) {
				$el = $this->XmlDocument->createElement($tagname);
				foreach ($attrs as $attr => $value)
					$this->setAttribute($el, $attr, $value);
				if (method_exists($this, "Chart_DataRendered"))
					$this->Chart_DataRendered($el);
				$this->XmlDocument->documentElement->appendChild($el);
			}
			foreach ($rs as $row) {
				$cat = $this->XmlDocument->createElement($childtagname);
				foreach ($childattrs as $attr => $value)
					$this->setAttribute($cat, $attr, $value);
				foreach ($row as $name => $value) {
					if (!is_numeric($name)) {
						if (in_array($name, ["start", "end", "date"])) // Date attributes
					 		$value = $this->dbdateToString($value);
						$this->setAttribute($cat, $name, $value);
					}
				}
				if (method_exists($this, "Chart_DataRendered"))
					$this->Chart_DataRendered($cat);
				$el->appendChild($cat);
			}
		}
	}

	// Ouptut <categories> node
	function outputCategories() {
		global $ReportLanguage;
		foreach ($this->Categories as $cats) {
			$el = $this->XmlDocument->createElement("categories");
			if (!isset($cats->CategoriesAttrs["bgColor"]))
				$cats->CategoriesAttrs["bgColor"] = $this->CategoryColor;
			if (!isset($cats->CategoriesAttrs["fontColor"]))
				$cats->CategoriesAttrs["fontColor"] = $this->CategoryFontColor;
			foreach ($cats->CategoriesAttrs as $attr => $value)
				$this->setAttribute($el, $attr, $value);
			if (method_exists($this, "Chart_DataRendered"))
				$this->Chart_DataRendered($el);
			$this->XmlDocument->documentElement->appendChild($el);
			if ($cats->Title <> "") { // Title
				$cat = $this->XmlDocument->createElement("category");
				$this->setAttribute($cat, "start", $this->dateToString($this->StartDate));
				$this->setAttribute($cat, "end", $this->dateToString($this->EndDate));
				$this->setAttribute($cat, "name", $cats->Title);
				foreach ($cats->CategoryAttrs as $attr => $value)
					$this->setAttribute($cat, $attr, $value);
				if (method_exists($this, "Chart_DataRendered"))
					$this->Chart_DataRendered($cat);
				$el->appendChild($cat);
			} else { // Intervals
				$arStart = $this->getDateTime($this->StartDate);
				$arEnd = $this->getDateTime($this->EndDate);
				if ($cats->Interval == GANTT_INTERVAL_YEAR) {
					$yrs = intval($arStart["year"]);
					$yre = intval($arEnd["year"]);
					for ($y = $yrs; $y <= $yre; $y++) {
						$cat = $this->XmlDocument->createElement("category");
						$start = ($y == $yrs) ? $this->dateTimeToString($arStart) : $this->ymdToString($y, 1, 1);
						$end = ($y == $yre) ? $this->dateTimeToString($arEnd) : $this->ymdToString($y, 12, 31);
						$this->setAttribute($cat, "start", $start);
						$this->setAttribute($cat, "end", $end);

						//if ($start == $this->ymdToString($y, 1, 1) && $end == $this->ymdToString($y, 12, 31)) // Complete year
							$this->setAttribute($cat, "name", $y);
						foreach ($cats->CategoryAttrs as $attr => $value)
							$this->setAttribute($cat, $attr, $value);
						if (method_exists($this, "Chart_DataRendered"))
							$this->Chart_DataRendered($el);
						$el->appendChild($cat);
					}
				} elseif ($cats->Interval == GANTT_INTERVAL_QUARTER) {
					$yrs = intval($arStart["year"]);
					$mons = intval($arStart["mon"]);
					$qtrs = floor(($mons-1)/3) + 1;
					$qs = $yrs * 4 + $qtrs;
					$yre = intval($arEnd["year"]);
					$mone = intval($arEnd["mon"]);
					$qtre = floor(($mone-1)/3) + 1;
					$qe = $yre * 4 + $qtre;
					for ($q = $qs; $q <= $qe; $q++) {
						$cat = $this->XmlDocument->createElement("category");
						$yr = floor($q/4);
						$qtr = $q % 4;
						$yr = ($qtr == 0) ? $yr - 1 : $yr;
						$qtr = ($qtr == 0) ? 4 : $qtr;
						$mos = ($qtr - 1) * 3 + 1;
						$moe = $qtr * 3;
						$dys = $this->createDateTime(0, 0, 0, $mos, 1, $yr);
						if ($this->StartDate > $dys)
							$dys = $this->StartDate;
						$dy = DaysInMonth($yr, $moe);
						$dye = $this->createDateTime(0, 0, 0, $moe, $dy, $yr);
						if ($this->EndDate < $dye)
							$dye = $this->EndDate;
						$start = ($q == $qs) ? $this->dateToString($dys) : $this->ymdToString($yr, $mos, 1);
						$end = ($q == $qe) ? $this->dateToString($dye) : $this->ymdToString($yr, $moe, $dy);
						$this->setAttribute($cat, "start", $start);
						$this->setAttribute($cat, "end", $end);

						//if ($start == $this->ymdToString($yr, $mos, 1) && $end == $this->ymdToString($yr, $moe, $dy)) // Complete quarter
							$this->setAttribute($cat, "name", "Q" . $qtr);
						foreach ($cats->CategoryAttrs as $attr => $value)
							$this->setAttribute($cat, $attr, $value);
						if (method_exists($this, "Chart_DataRendered"))
							$this->Chart_DataRendered($cat);
						$el->appendChild($cat);
					}
				} elseif ($cats->Interval == GANTT_INTERVAL_MONTH) {
					$yrs = intval($arStart["year"]);
					$mons = intval($arStart["mon"]);
					$ms = $yrs * 12 + $mons;
					$yre = intval($arEnd["year"]);
					$mone = intval($arEnd["mon"]);
					$me = $yre * 12 + $mone;
					for ($m = $ms; $m <= $me; $m++) {
						$cat = $this->XmlDocument->createElement("category");
						$yr = floor($m/12);
						$mo = $m % 12;
						$yr = ($mo == 0) ? $yr - 1 : $yr;
						$mo = ($mo == 0) ? 12 : $mo;
						$dy = DaysInMonth($yr, $mo);
						$start = ($m == $ms) ? $this->dateTimeToString($arStart) : $this->ymdToString($yr, $mo, 1);
						$end = ($m == $me) ? $this->dateTimeToString($arEnd) : $this->ymdToString($yr, $mo, $dy);
						$this->setAttribute($cat, "start", $start);
						$this->setAttribute($cat, "end", $end);
						if ($start == $this->ymdToString($yr, $mo, 1) && $end == $this->ymdToString($yr, $mo, $dy)) // Complete month
							$this->setAttribute($cat, "name", MonthName($mo)); // Or FormatMonth
						foreach ($cats->CategoryAttrs as $attr => $value)
							$this->setAttribute($cat, $attr, $value);
						if (method_exists($this, "Chart_DataRendered"))
							$this->Chart_DataRendered($cat);
						$el->appendChild($cat);
					}
				} elseif ($cats->Interval == GANTT_INTERVAL_WEEK) {
					$ds = $this->StartDate;
					$de = $this->EndDate;
					for ($d = $ds; $d < $de; $d += 86400) {
						$dts = $this->getDateTime($d);

						//$dte = $this->getDateTime($d + 6*86400);
						$wday = $dts["wday"];
						$diff = ($wday >= GANTT_WEEK_START) ? ($wday - GANTT_WEEK_START) : ($wday + 7 - GANTT_WEEK_START);
						$ws = ($d == $ds) ? $ds : ($d - $diff * 86400);
						$we = ($d == $de) ? $de : ($d + (6 - $diff) * 86400);
						$d = $we;
						$cat = $this->XmlDocument->createElement("category");
						$this->setAttribute($cat, "start", $this->dateToString($ws));
						$this->setAttribute($cat, "end", $this->dateToString($we));
						if ($this->ShowWeekNumber && GANTT_WEEK_START == 1) { // Week start on Monday
							$this->setAttribute($cat, "name", $ReportLanguage->phrase("Week") . " " . $this->formatDate("W", $d));
						} else {
							$this->setAttribute($cat, "name", $ReportLanguage->phrase("Week"));
						}
						foreach ($cats->CategoryAttrs as $attr => $value)
							$this->setAttribute($cat, $attr, $value);
						if (method_exists($this, "Chart_DataRendered"))
							$this->Chart_DataRendered($cat);
						$el->appendChild($cat);
					}
				} elseif ($cats->Interval == GANTT_INTERVAL_DAY) {
					$ds = $this->StartDate;
					$de = $this->EndDate;
					for ($d = $ds; $d <= $de; $d += 86400) {
						$dt = $this->getDateTime($d);
						$md = $dt["mday"];
						$cat = $this->XmlDocument->createElement("category");
						$sdt = $this->dateTimeToString($dt);
						$this->setAttribute($cat, "start", $sdt);
						$this->setAttribute($cat, "end", $sdt);
						$this->setAttribute($cat, "name", $md);
						foreach ($cats->CategoryAttrs as $attr => $value)
							$this->setAttribute($cat, $attr, $value);
						if (method_exists($this, "Chart_DataRendered"))
							$this->Chart_DataRendered($cat);
						$el->appendChild($cat);
					}
				}
			}
		}
	}

	// Output Data Table
	function outputDataTable() {
		if ($this->ProcessTable == "" || empty($this->DataColumns))
			return;
		$dt = $this->XmlDocument->createElement("dataTable");
		foreach ($this->DataTableAttrs as $attr => $value)
			$this->setAttribute($dt, $attr, $value);
		if (method_exists($this, "Chart_DataRendered"))
			$this->Chart_DataRendered($dt);
		$this->XmlDocument->documentElement->appendChild($dt);
		$sql = "SELECT * FROM " . $this->ProcessTable;
		$conn = &Conn($this->ProcessTableDbId);
		$rs = $conn->Execute($sql);
		if ($rs && !$rs->EOF) {
			$i = 0;
			foreach ($this->DataColumns as $dc) {
				$col = $this->XmlDocument->createElement("dataColumn");
				if (!isset($dc->ColumnAttrs["headerbgColor"]))
					$dc->ColumnAttrs["headerbgColor"] = $this->HeaderColor;
				if (!isset($dc->ColumnAttrs["headerFontColor"]))
					$dc->ColumnAttrs["headerFontColor"] = $this->HeaderFontColor;
				if (!isset($dc->ColumnAttrs["bgColor"]))
					$dc->ColumnAttrs["bgColor"] = $this->CategoryColor;
				if (!isset($dc->ColumnAttrs["fontColor"]))
					$dc->ColumnAttrs["fontColor"] = $this->CategoryFontColor;
				foreach ($dc->ColumnAttrs as $attr => $value)
					$this->setAttribute($col, $attr, $value);
				$this->setAttribute($col, "headerText", $dc->Caption); // Column header
				if (method_exists($this, "Chart_DataRendered"))
					$this->Chart_DataRendered($col);
				$dt->appendChild($col);
				$rs->MoveFirst();
				while (!$rs->EOF) {
					$txt = $this->XmlDocument->createElement("text");
					foreach ($dc->TextAttrs as $attr => $value)
						$this->setAttribute($txt, $attr, $value);
					$fldval = $rs->fields[$dc->FieldName];
					$formatfunc = $dc->FormatFunction;
					if ($formatfunc <> "" && function_exists($formatfunc))
						$fldval = $formatfunc($fldval);
					$this->setAttribute($txt, "label", $fldval);
					if (method_exists($this, "Chart_DataRendered"))
						$this->Chart_DataRendered($txt);
					$col->appendChild($txt);
					$rs->MoveNext();
				}
				$i++;
			}
			$rs->Close();
		}
	}

	// Task table order by
	function taskTableOrderBy() {
		return " ORDER BY " . QuotedName($this->TaskStartField, $this->TaskTableDbId);
	}

	// Output Tasks
	function outputTasks() {
		$tasks = $this->XmlDocument->createElement("tasks");
		foreach ($this->TasksAttrs as $attr => $value)
			$this->setAttribute($tasks, $attr, $value);
		if (method_exists($this, "Chart_DataRendered"))
			$this->Chart_DataRendered($tasks);
		$this->XmlDocument->documentElement->appendChild($tasks);
		$sql = "SELECT * FROM " . $this->TaskTable;
		if ($this->TaskFilter <> "") $sql .= " WHERE " . $this->TaskFilter;
		$sql .= $this->taskTableOrderBy();
		$rs = $this->Connection->Execute($sql);
		if ($rs) {
			$arFields = [strtolower($this->TaskIdField), strtolower($this->TaskNameField), strtolower($this->TaskStartField), strtolower($this->TaskEndField),
				strtolower($this->TaskResourceIdField), strtolower($this->TaskDurationField), strtolower($this->TaskPercentCompleteField), strtolower($this->TaskDependenciesField)];
			$cnt = 0;
			while (!$rs->EOF) {
				$task = $this->XmlDocument->createElement("task");
				foreach ($this->TaskAttrs as $attr => $value) {
					if (!in_array(strtolower($attr), $arFields))
						$this->setAttribute($task, $attr, $value);
				}
				foreach ($rs->fields as $name => $value) {
					if (!is_numeric($name) && !in_array(strtolower($name), $arFields))
						$this->setAttribute($task, strtolower($name), $value);
				}
				if ($this->ProcessTable == "") // No process table, set up process id from task id
					$this->setAttribute($task, "processid", $rs->fields($this->TaskIdField));
				$this->setAttribute($task, "id", $rs->fields($this->TaskIdField));
				$this->setAttribute($task, "name", $rs->fields($this->TaskNameField));
				$this->setAttribute($task, "start", $this->dbdateToString($rs->fields($this->TaskStartField)));
				$this->setAttribute($task, "end", $this->dbdateToString($rs->fields($this->TaskEndField)));
				if ($this->TaskResourceIdField <> "")
					$this->setAttribute($task, "resourceId", $rs->fields($this->TaskResourceIdField));
				if ($this->TaskDurationField <> "")
					$this->setAttribute($task, "duration", $rs->fields($this->TaskDurationField));
				if ($this->TaskPercentCompleteField <> "")
					$this->setAttribute($task, "percentComplete", $rs->fields($this->TaskPercentCompleteField));
				if ($this->TaskDependenciesField <> "")
					$this->setAttribute($task, "dependencies", $rs->fields($this->TaskDependenciesField));
				if ($task->getAttribute("color") == "") {
					$color = $this->getTaskColor($cnt);
					if ($color <> "")
						$this->setAttribute($task, "color", $color);
				}
				if (method_exists($this, "Chart_DataRendered"))
					$this->Chart_DataRendered($task);
				$tasks->appendChild($task);
				$rs->MoveNext();
				$cnt++;
			}
			$rs->Close();
		}
	}

	// Process table order by
	function processTableOrderBy() {
		return "";
	}

	// Output processes
	function outputProcesses() {
		if (!isset($this->ProcessesAttrs["bgColor"]))
			$this->ProcessesAttrs["bgColor"] = $this->HeaderColor;
		if (!isset($this->ProcessesAttrs["fontColor"]))
			$this->ProcessesAttrs["fontColor"] = $this->HeaderFontColor;
		if (!isset($this->ProcessesAttrs["headerBgColor"]))
			$this->ProcessesAttrs["headerBgColor"] = $this->HeaderColor;
		if (!isset($this->ProcessesAttrs["headerFontColor"]))
			$this->ProcessesAttrs["headerFontColor"] = $this->HeaderFontColor;
		if (!isset($this->ProcessesAttrs["headerText"]))
			$this->ProcessesAttrs["headerText"] = $this->ProcessesHeaderText;
		if (!isset($this->ProcessesAttrs["isBold"]))
			$this->ProcessesAttrs["isBold"] = $this->HeaderIsBold;
		if ($this->ProcessTable <> "") { // Use process table
			$processes = $this->XmlDocument->createElement("processes");
			foreach ($this->ProcessesAttrs as $attr => $value)
				$this->setAttribute($processes, $attr, $value);
			if (method_exists($this, "Chart_DataRendered"))
				$this->Chart_DataRendered($processes);
			$this->XmlDocument->documentElement->appendChild($processes);
			$sql = "SELECT * FROM " . $this->ProcessTable;
			$sql .= $this->processTableOrderBy();
			$conn = &Conn($this->ProcessTableDbId);
			$rs = $conn->Execute($sql);
			if ($rs) {
				while (!$rs->EOF) {
					$process = $this->XmlDocument->createElement("process");
					foreach ($this->ProcessAttrs as $attr => $value)
						$this->setAttribute($process, $attr, $value);
					foreach ($rs->fields as $name => $value) {
						if (!is_numeric($name))
							$this->setAttribute($process, $name, $value);
					}
					if (method_exists($this, "Chart_DataRendered"))
						$this->Chart_DataRendered($process);
					$processes->appendChild($process);
					$rs->MoveNext();
				}
				$rs->Close();
			}
		} else { // Use task table as process table
			$fid = $this->TaskIdField;
			$fname = $this->TaskNameField;
			$fstart = $this->TaskStartField;
			$sql = "SELECT DISTINCT " . QuotedName($fid, $this->TaskTableDbId) . " AS id, " .
				QuotedName($fname, $this->TaskTableDbId) . " AS name, " . QuotedName($fstart, $this->TaskTableDbId) . " FROM " . $this->TaskTable;
			if ($this->TaskIdFilter <> "" || $this->TaskNameFilter <> "") {
				$sql .= " WHERE ";
				if ($this->TaskIdFilter <> "")
					$sql .= $this->TaskIdFilter;
				if ($this->TaskNameFilter <> "")
					$sql .= ($this->TaskIdFilter <> "" ? " AND " : "") . $this->TaskNameFilter;
			}
			$sql .= $this->taskTableOrderBy();
			$this->outputQuery($sql, $this->TaskTableDbId, "processes", "process", $this->ProcessesAttrs, $this->ProcessAttrs);
		}
	}

	// Render gantt chart
	function render($class = "", $width = -1, $height = -1) {
		global $ExportType, $CustomExportType, $USE_PHPEXCEL, $USE_PHPWORD;

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

		// Output
		echo '<span class="' . $class . '">'; // Start chart span
		if ($ExportType == "" || $ExportType == "print" && $CustomExportType == "" || $ExportType == "email" && Post("contenttype") == "url") {
			$this->saveXml();
			$renderer = new $rendererClass($this);
			echo '<div id="div_' . $this->ID . '"></div>' . $renderer->getScript($width, $height);
		} elseif ($ExportType == "pdf" || $CustomExportType <> "" || $ExportType == "email" || $ExportType == "excel" && $USE_PHPEXCEL || $ExportType == "word" && $USE_PHPWORD) { // Show temp image
			echo $this->getTempImageTag();
		}
		echo '</span>'; // End chart span
	}

	// Chart XML
	function saveXml() {

		// Chart_Rendering event
		if (method_exists($this, "Chart_Rendering"))
			$this->Chart_Rendering();

		// Start/End dates
		$this->setupStartEnd();

		// Chart attributes
		foreach ($this->ChartAttrs as $attr => $value)
			$this->setAttribute($this->Output->documentElement, $attr, $value);
		$this->setAttribute($this->Output->documentElement, "dateFormat", $this->DateFormat);

		// Chart_DataRendered event
		if (method_exists($this, "Chart_DataRendered"))
			$this->Chart_DataRendered($this->Output->documentElement);

		// Categories
		$this->outputCategories();

		// Processes
		$this->outputProcesses();

		// DataTable
		$this->outputDataTable();

		// Tasks
		$this->outputTasks();

		// Milestones
		if ($this->MilestoneTable <> "") {
			$sql = "SELECT * FROM " . $this->MilestoneTable;
			$this->outputQuery($sql, $this->MilestoneTableDbId, "milestones", "milestone", [], $this->MilestoneAttrs);
		} elseif ($this->TaskMilestoneDateField <> "") { // Use task table as milestone table
			$sql = "SELECT " . QuotedName($this->TaskIdField, $this->TaskTableDbId) . " AS ". QuotedName("taskId", $this->TaskTableDbId) . ", " .
				QuotedName($this->TaskMilestoneDateField, $this->TaskTableDbId) . " AS " . QuotedName("date", $this->TaskTableDbId) .
				" FROM " . $this->TaskTable .
				" WHERE " . QuotedName($this->TaskMilestoneDateField, $this->TaskTableDbId) . " IS NOT NULL";
			$this->outputQuery($sql, $this->TaskTableDbId, "milestones", "milestone", [], $this->MilestoneAttrs);
		}
		if (count($this->Milestones) > 0)
			$this->outputArray($this->Milestones, "milestones", "milestone", [], $this->MilestoneAttrs);

		// Trendlines
		if ($this->TrendlineTable <> "") {
			$sql = "SELECT * FROM " . $this->TrendlineTable;
			$this->outputQuery($sql, $this->TrendlineTableDbId, "trendlines", "line", [], $this->TrendlineAttrs);
		}
		if (count($this->Trendlines) > 0)
			$this->outputArray($this->Trendlines, "trendlines", "line", [], $this->TrendlineAttrs);

		// Connectors
		if ($this->ConnectorTable <> "") {
			$sql = "SELECT * FROM " . $this->ConnectorTable;
			$this->outputQuery($sql, $this->ConnectorTableDbId, "connectors", "connector", $this->ConnectorsAttrs, $this->ConnectorAttrs);
		} elseif ($this->TaskFromTaskIdField <> "") { // Use task table as connector table
			$sql = "SELECT " . QuotedName($this->TaskFromTaskIdField, $this->TaskTableDbId) . " AS ". QuotedName("fromTaskId", $this->TaskTableDbId) . ", " .
				QuotedName($this->TaskIdField, $this->TaskTableDbId) . " AS ". QuotedName("toTaskId", $this->TaskTableDbId) . " FROM " . $this->TaskTable;
			$this->outputQuery($sql, $this->TaskTableDbId, "connectors", "connector", $this->ConnectorsAttrs, $this->ConnectorAttrs);
		}
		if (count($this->Connectors) > 0)
			$this->outputArray($this->Connectors, "connectors", "connector", $this->ConnectorsAttrs, $this->ConnectorAttrs);

		// Get the XML
		$xml = $this->XmlDocument->saveXML();

		// Chart_Rendered event
		if (method_exists($this, "Chart_Rendered"))
			$this->Chart_Rendered($xml);

		// Save the XML
		$this->DataSource = $xml;
	}
}
?>