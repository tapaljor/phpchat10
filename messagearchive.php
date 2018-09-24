<?php 
include('head.php');

require_once CLASSES . 'class.chat.php';
require_once CLASSES . 'class.encryption.php';
require_once CLASSES . 'class.user.php';

$chat = new chat($db);
$encryption = new encryption();
$user = new user($db);

if ( isset($_GET["delete"]) && !empty($_GET["delete"])) {

	$_GET = $db->clean_array($_GET);

	$id = $chat->match_hash($_GET["delete"]);

	$status = false;
	$status = $chat->delete($id);
	if ( $status) {
		header("Location: messagearchive.php");
	}
}

$chat->execute_only("UPDATE", "SET status = 2 WHERE destination = $_SESSION[idCHATP]"); 

$array = $chat->get("WHERE destination = $_SESSION[idCHATP] ORDER BY time DESC");
foreach($array as $rows) {

	$didh = md5($rows["id"].md5($_SESSION["tsa_gong"]));

	$array2 = $user->get_some_fields("salt", "WHERE id = $rows[destination]");
	foreach($array2 as $rows2) {
		$key = $rows2["salt"];
	}

	$idh = md5($rows["sender"].md5($_SESSION["tsa_gong"]));
	$timeago = utility::time_ago($rows["time"]);
	$message = $encryption->decrypt($rows["message"], $key);

	$array2 = $user->get_some_fields("username", "WHERE id = $rows[sender]");
	foreach($array2 as $rows2) {
		$senderla = $rows2["username"];
	}

	echo '<div id="messagearchive">';
		echo "<a href=\"home.php?destinationh=$idh&message=$message\">$timeago <b>$senderla</b> <span style='font-family: times; 
		font-weight: bold; font-style: italic;'>$message</span></a> <a href=\"messagearchive.php?delete=$didh\" style='color: red; font-style: italic;'> Delete</a><br/>";
	echo '</div>';
}

include('footer.php');
