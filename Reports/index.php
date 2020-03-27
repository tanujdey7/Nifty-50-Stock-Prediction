<?php
namespace PHPReportMaker12\project1;

// Session
if (session_status() !== PHP_SESSION_ACTIVE)
	session_start(); // Init session data

// Output buffering
ob_start();

// Autoload
include_once "rautoload.php";
?>
<?php

// Create page object
if (!isset($rptindex))
	$rptindex = new rptindex();
if (isset($Page))
	$OldPage = $Page;
$Page = &$rptindex;

// Run the page
$Page->run();

// Setup login status
SetClientVar("login", LoginStatus());
?>
<?php include_once "rheader.php" ?>
<?php $Page->showMessage(); ?>
<?php include_once "rfooter.php" ?>
<?php
$Page->terminate();
if (isset($OldPage))
	$Page = $OldPage;
?>