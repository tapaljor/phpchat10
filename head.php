<?php session_start();?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>SOCKET IO PHP</title>

	<script src="js/jquery.js"></script>
	<script src="js/chat.js"></script>
	<script src="js/custom.js"></script>
	<script src="js/responsivemenu.js"></script>

	<link rel="stylesheet" href="css/stylesheet.css">
	<link rel="stylesheet" href="css/skyform.css">
	<link rel="stylesheet" href="css/responsivemenu.css">
</head>
<?php
	require_once('config.php');
  	require_once CLASSES . 'class.utility.php';
   	require_once CLASSES . 'class.db.php';
    	require_once CLASSES . 'class.user.php';

      	$db = new database();
       	$user = new user($db);
       
	if ( isset($_SESSION["idCHATP"])) {
		echo "<input type='hidden' id='currentuser' value=\"$_SESSION[idCHATP]\"/>";
		echo "<input type='hidden' id='tsa_gong' value=\"$_SESSION[tsa_gong]\"/>";
	}
?>

<body class="sky-form">
<?php require_once("menu1.php");?>
<fieldset>
<section class="col col-3">
	<div class="notification">
		<div id="bell">
		<a href="messagearchive.php"><img src='images/bell.svg' width='30px;'/></a>
		</div>
		<span class="newmessage"></span>
	</div>
</section>
<section class="col col-6">
