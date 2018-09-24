<?php
session_start();
include('config.php');
require_once CLASSES . "class.utility.php";
require_once CLASSES . "class.db.php";
require_once CLASSES . "class.user.php";
require_once CLASSES . "class.likedislike.php";

$db = new database();
$user = new user($db);
$likedislike = new likedislike($db);

if( utility::is_post() ) {

	$loginid = $user->match_hash( $_POST["loginidhash"]);
	$likerid = $user->match_hash($_POST["likeridhash"]);

	$idh = md5($loginid.md5($_SESSION["tsa_gong"]));

	$conditions = "WHERE loginid = $loginid && likerid = $likerid && type = $_POST[type]";
	$liked = $likedislikes = $likedislike->get_total_rows($conditions);

	if ( !$liked) {

		$_data = array (
			'loginid' => $loginid,
			'likerid' => $likerid,
			'type' => $_POST["type"]
			);

		$likedislike->add($_data);

		$like = $dislike = 0;

		$conditions = "WHERE loginid = $loginid && type = 1";
		$likes = $likedislike->get_total_rows($conditions);
			
		$conditions = "WHERE loginid = $loginid && type = 0";
		$dislikes = $likedislike->get_total_rows($conditions);

		//Parameter passed for ajax like dislike loginid, likerid and 0, 1 like and dislike respectively
		echo "<a href='#' title='Like' onclick=\"likedislike('$idh', '$_SESSION[idhashCHATP]', 1); return false\" style='font-size: 2em;'>&#128077; <span style='font-size: 14px;'>$likes &nbsp;</span></a> ";
		echo "<a href='#' title='Dislike' onclick=\"likedislike('$idh', '$_SESSION[idhashCHATP]', 0); return false\" style='font-size: 2em; color: red;'>&#128078; <span style='font-size: 14px; color: red;'> $dislikes</span></a> ";
	} else {

		if( $_POST["type"] == 1) {
			echo '<div id="file_error">You have already liked</div>';
		} else {
			echo '<div id="file_error">You have already disliked</div>';
		}
	}
} 
  

