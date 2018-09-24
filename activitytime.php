<?php
session_start();

require_once('config.php');
require_once CLASSES . 'class.utility.php';

$_SESSION["activitytime"];
if ( utility::gettime()-$_SESSION["activitytime"] >= 600) {
	header("Location: index.php?logout=yes");
}
