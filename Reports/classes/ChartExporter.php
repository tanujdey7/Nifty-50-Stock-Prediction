<?php
namespace PHPReportMaker12\project1;

/**
 * Chart exporter class for PHP Report Maker 12
 * (C) 2019 e.World Technology Limited
 */
class ChartExporter
{

	// Export
	public function export()
	{

		// Check token
		if (!$this->validPost())
			return $this->serverError("Invalid post request.");
		$json = Post("charts", "[]");
		$charts = json_decode($json);
		$files = [];
		foreach ($charts as $chart) {
			$img = FALSE;

			// Google Charts base64
			if ($chart->stream_type == "base64") {
				try {
					$img = base64_decode(preg_replace('/^data:image\/\w+;base64,/', "", $chart->stream));
				} catch (Exception $e) {
					return $this->serverError($e->getMessage());
				}
			} else { // SVG or FusionCharts

				// FusionCharts, requires cURL or Imagick
				if ($chart->chart_engine == "fusioncharts") {
					if (!function_exists("curl_init") && !class_exists("Imagick"))
						return $this->serverError("Both Imagick and cURL not installed on this server.");
				} else { // Others, requires Imagick
					if (!class_exists("Imagick"))
						return $this->serverError("Imagick not installed on this server.");
				}

				// Convert SVG string to image
				if ($chart->chart_engine == "fusioncharts")
					$img = $this->getImageFromFusionCharts($chart); // Get from fusioncharts.com
				if (!$img)
					$img = $this->getImageFromImagick($chart); // Get from Imagick
			}
			if ($img === FALSE)
				return $this->serverError("Unable to load image for chart engine '" . $chart->chart_engine . "' and stream type '" . $chart->stream_type . "'");

			// Save the file
			$params = $chart->parameters;
			$filename = "";
			if (preg_match('/exportfilename=(\w+\.png)\|/', $params, $matches)) // Must be .png for security
				$filename = $matches[1];
			if ($filename == "")
				return $this->serverError("Missing file name.");
			$path = ServerMapPath(UPLOAD_DEST_PATH);
			$realpath = realpath($path);
			if (!file_exists($realpath))
				return $this->serverError("Upload folder does not exist.");
			if (!is_writable($realpath))
				return $this->serverError("Upload folder is not writable.");
			$filepath = realpath($path) . PATH_DELIMITER . $filename;
			file_put_contents($filepath, $img);
			$files[] = $filename;
		}

		// Write success response
		WriteJson(["success" => TRUE, "files" => $files]);
		return TRUE;
	}

	// Send server error
	protected function serverError($msg)
	{
		WriteJson(["success" => FALSE, "error" => $msg]);
		return FALSE;
	}

	// Valid Post
	protected function validPost()
	{
		if (!CHECK_TOKEN || !IsPost())
			return TRUE;
		$token = Post(TOKEN_NAME);
		if ($token === NULL)
			return FALSE;
		$fn = PROJECT_NAMESPACE . CHECK_TOKEN_FUNC;
		if (is_callable($fn))
			return $fn($token);
		return FALSE;
	}

	// Get image from fusioncharts.com
	protected function getImageFromFusionCharts($chart)
	{
		if (function_exists("curl_init")) // Use cURL if available
			return ClientUrl("export.api3.fusioncharts.com", http_build_query($chart), "POST"); // Get the chart from fusioncharts.com
		return FALSE;
	}

	// Get image from Imagick
	protected function getImageFromImagick($chart)
	{
		if (class_exists("Imagick")) { // Use Imagick if available
			try {
				$img = new \Imagick();
				$svg = '<?xml version="1.0" encoding="utf-8" standalone="no"?>' . $chart->stream; // Get SVG string

				// Replace, for example, fill="url('#10-270-rgba_255_0_0_1_-rgba_255_255_255_1_')" by fill="rgb(255, 0, 0)" 
				//$svg = preg_replace('/fill="url\(\'#[\w-]+rgba_(\d+)_(\d+)_(\d+)_(\d+)_-[\w-]+\'\)"/', 'fill="rgb($1, $2, $3)"', $svg);

				$svg = preg_replace('/fill="url\(\'\#[\w-]+rgba_(\d+)_(\d+)_(\d+)_(\d+\.?\d?)_-[\w-\.?]+\'\)"/', 'fill="rgb($1, $2, $3)"', $svg);
				$img->readImageBlob($svg);
				$img->setImageBackgroundColor(new \ImagickPixel("transparent"));
				$img->setImageFormat("png24");
				return $img;
			} catch (Exception $e) {
				return $this->serverError($e->getMessage());
			}
		}
		return FALSE;
	}
}
?>