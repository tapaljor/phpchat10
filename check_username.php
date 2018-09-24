<?php
require_once('config.php');
require_once CLASSES . 'class.db.php';
require_once CLASSES . 'class.user.php';

$db = new database();
$user = new user($db);

$users = $user->get_num_rows("WHERE username = '$_POST[username]'");
$result = array (
	'code' => 1,
	'message' => "Available"
	);

if ( $users > 0 ) {
	$result["code"] = 0;
	$result["message"] = "Not available";
} 

echo json_encode($result);

