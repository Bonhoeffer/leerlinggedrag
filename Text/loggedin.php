<?php
session_start();

if ($_SESSION['gb'] == "" || !isset($_SESSION['gb'])){ 
	header("Location: index.php");
}
?>
<!DOCTYPE html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
		<link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico" /> <!-- favicon -->
		<link rel="apple-touch-icon" href="images/ipad-icon.png" /> <!-- ipad icon -->
		<meta name="apple-mobile-web-app-status-bar-style" content="black" />
		<meta name="apple-mobile-web-app-capable" content="yes" />