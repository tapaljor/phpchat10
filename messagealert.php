<?php
session_start();

require_once('config.php');
require_once CLASSES . 'class.utility.php';
require_once CLASSES . 'class.db.php';
require_once CLASSES . 'class.encryption.php';
require_once CLASSES . 'class.chat.php';
require_once CLASSES . 'class.user.php';

$db = new database();
$encryption = new encryption();
$chat = new chat($db);
$user = new user($db);

/*$array = $user->get_some_fields("id, salt", "WHERE id = $_POST[destination]");
foreach($array as $rows) {
	$key = $rows["salt"];
}

$_POST["message"] = $encryption->encrypt($_POST["message"], $key);*/
unset($_POST["senderusername"]); 

$chat->add($_POST);

$total = $chat->get_num_rows("WHERE destination = $_POST[destination] && status != 2"); //Message for you which are unread

echo '<a href="messagearchive.php" style="font-family: times new roman; font-style: italic; color: red;">'.$total.' New messages</a>';

