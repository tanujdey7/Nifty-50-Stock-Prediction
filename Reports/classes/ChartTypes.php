<?php

/**
 * PHP Report Maker 12 Chart Types
 */
namespace PHPReportMaker12\project1;

/**
 * ChartTypes class
 */
class ChartTypes
{

	/**
	 * Supported chart types
	 * 
	 * Format - [chart_id, [normal_chart_name, scroll_chart_name]]
	 * chart_id - abnn
	 * **id = chart_id in previous version
	 * - a: 1 = Single Series, 2 = Multi Series, 3 = Stacked, 4 = Combination, 5 = Financial, 6 = Other
	 * - b: 0 = 2D, 1 = 3D
	 * - nn: 01 = Column, 02 = Line, 03 = Area, 04 = Bar, 05 = Pie, 06 = Doughnut, 07 Pareto
	 * - nn: 91 = Marimekko, 92 = Zoom-line
	 * - nn: 99 = Candlestick, 98 = Gantt
	 *
	 * @var array
	 */
	public static $Types = [

		// Single Series
		[1001, ["column2d", "scrollcolumn2d"]], // Column 2D (**1)
		[1101, ["column3d"]], // Column 3D (**5)
		[1002, ["line", "scrollline2d"]], // Line 2D (**4)
		[1003, ["area2d", "scrollarea2d"]], // Area 2D (**7)
		[1004, ["bar2d"]], // Bar 2D (**3)
		[1104, ["bar3d"]], // Bar 3D (**104)
		[1005, ["pie2d"]], // Pie 2D (**2)
		[1105, ["pie3d"]], // Pie 3D (**6)
		[1006, ["doughnut2d"]], // Doughnut 2D (**8)
		[1106, ["doughnut3d"]], // Doughnut 3D (**101)
		[1007, ["pareto2d"]], // Pareto 2D
		[1107, ["pareto3d"]], // Pareto 3D

		// Multi Series
		[2001, ["mscolumn2d", "scrollcolumn2d"]], // Multi-series Column 2D (**9)
		[2101, ["mscolumn3d"]], // Multi-series Column 3D (**10)
		[2002, ["msline", "scrollline2d"]], // Multi-series Line 2D (**11)
		[2003, ["msarea", "scrollarea2d"]], // Multi-series Area 2D (**12)
		[2004, ["msbar2d"]], // Multi-series Bar 2D (**13)
		[2104, ["msbar3d"]], // Multi-series Bar 3D (**102)
		[2091, ["marimekko"]], // Multi-series Marimekko
		[2092, ["zoomline"]], // Multi-series Zoom-line (CompactDataMode = TRUE)

		// Stacked
		[3001, ["stackedcolumn2d", "scrollstackedcolumn2d"]], // Stacked Column 2D (**14)
		[3101, ["stackedcolumn3d"]], // Stacked Column 3D (**15)
		[3003, ["stackedarea2d"]], // Stacked Area 2D (**16)
		[3004, ["stackedbar2d"]], // Stacked Bar 2D (**17)
		[3104, ["stackedbar3d"]], // Stacked Bar 3D (**103)

		// Combination
		[4001, ["mscombi2d", "scrollcombi2d"]], // Multi-series 2D Single Y Combination Chart (Column + Line + Area)
		[4101, ["mscombi3d"]], // Multi-series 3D Single Y Combination Chart (Column + Line + Area)
		[4111, ["mscolumnline3d"]], // Multi-series Column 3D + Line - Single Y Axis
		[4021, ["stackedcolumn2dline"]], // Stacked Column2D + Line single Y Axis
		[4121, ["stackedcolumn3dline"]], // Stacked Column3D + Line single Y Axis
		[4031, ["mscombidy2d", "scrollcombidy2d"]], // Multi-series 2D Dual Y Combination Chart (Column + Line + Area) (**18)
		[4131, ["mscolumn3dlinedy"]], // Multi-series Column 3D + Line - Dual Y Axis (**19)
		[4141, ["stackedcolumn3dlinedy"]], // Stacked Column 3D + Line Dual Y Axis

		// Zoom-line
		[4092, ["zoomlinedy"]], // Multi-series Zoom-line Dual Y-Axis (CompactDataMode = TRUE)

		// Financial
		[5099, ["candlestick"]], // Candlestick (**20)

		// Other
		[6098, ["gantt"]] // Gantt (**21)
	];

	/**
	 * Default type ID
	 *
	 * @var integer
	 */
	public static $DefaultType = 1001; // // Default

	/**
	 * Get chart type name
	 *
	 * @param integer $id Chart type ID
	 * @param boolean $scroll Whether chart is scrollable
	 * @return string Chart type name
	 */
	public static function getName($id, $scroll = FALSE) {
		foreach (self::$Types as $type) {
			if ($id == $type[0])
				return $scroll ? (count($type[1]) >= 2 ? $type[1][1] : $type[1][0]) : $type[1][0];
		}
		return "column2d"; // Default
	}

	/**
	 * Get renderer class
	 *
	 * @param integer $id Chart type ID
	 * @return string Renderer class name
	 */
	public static function getRendererClass($id) {
		global $USE_FUSIONCHARTS_TRIAL;
		if (!$USE_FUSIONCHARTS_TRIAL && ($id == 5099 || $id == 6098)) // Candlestick/Gantt 
			$cls = "GoogleChartRenderer";
		else
			$cls = "FusionChartRenderer";
		return PROJECT_NAMESPACE . $cls;
	}
}
?>