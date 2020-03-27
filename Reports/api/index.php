<?php
namespace PHPReportMaker12\project1;

// Set up relative path
$RELATIVE_PATH = "../";
$ROOT_RELATIVE_PATH = "../";

// Autoload
include_once $RELATIVE_PATH . "rautoload.php";

// Create language object
$Language = new Language();

// Run
$Api = new Api();
$Api->run();
?>