<?php
	session_start();
	require_once('config.php');
	require_once CLASSES . 'class.db.php';
	require_once CLASSES . 'class.user.php';
	require_once CLASSES . 'class.utility.php';

	$db = new database();
	$user = new user($db);
	
	if( isset($_SESSION["AdminCHATP"]) && !empty($_SESSION["AdminCHATP"])) { 

		$conditions =" WHERE firstname LIKE '%$_POST[name]%' || lastname LIKE '%$_POST[name]%' || username LIKE '%$_POST[name]%' || 
				intro LIKE '%$_POST[name]%' LIMIT 0, 70";

		$result = $user->get($conditions);
		utility::member($result, 0);

	} else {
		echo '<p>Please login to access this page</p>';
	}

