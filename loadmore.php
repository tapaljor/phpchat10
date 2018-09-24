<?php
session_start();
require_once('config.php');
require_once CLASSES . 'class.db.php';
require_once CLASSES . 'class.utility.php';
require_once CLASSES . 'class.user.php';

$db = new database();
$user = new user($db);

$conditions = "WHERE status = 2 ORDER BY id DESC LIMIT $_POST[from], 10";
$array = $user->get($conditions);
utility::member($array);
echo '<div stye="display: none;" class="loading"></div>';

