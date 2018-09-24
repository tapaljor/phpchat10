<?php
include('config.php');
require_once CLASSES . "class.utility.php";
require_once CLASSES . "class.db.php";
require_once CLASSES . "class.state.php";

$db = new database();
$state = new state($db);

if( utility::is_post() ) {

	$conditions = "WHERE countryid = $_POST[id]";
	$states = $state->get($conditions);

	echo '<section><label class="select"><select name="state" onchange="getcity(state);">';
	foreach($states as $rows) {
		echo "<option value=\"$rows[id]\">$rows[name]</option>";
	}
	echo '</select></label></section>';
}

echo '<script src="js/custom.js"></script>';

