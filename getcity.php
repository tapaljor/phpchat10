<?php
include('config.php');
require_once CLASSES . "class.utility.php";
require_once CLASSES . "class.db.php";
require_once CLASSES . "class.city.php";

$db = new database();
$city = new city($db);

if( utility::is_post() ) {

	$conditions = "WHERE stateid = $_POST[id]";
	$citys = $city->get($conditions);

	echo '<section><label class="select"><select name="city">';
	foreach($citys as $rows) {
		echo "<option value=\"$rows[id]\">$rows[name]</option>";
	}
	echo '</select></label></section>';
}

echo '<script src="js/custom.js"></script>';

