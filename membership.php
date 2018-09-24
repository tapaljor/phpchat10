<?php include("head.php");?>
<?php
require_once CLASSES.'class.listregion.php';

$listregion = new listregion($db);

utility::check_authentication();

if ( isset($_POST["search"]) && !empty($_POST["search"])) {

	$_POST = $db->clean_array($_POST);

	$conditions = "WHERE gender = $_POST[gender]";
	if ( $_POST["region"] != 'All') {
		$conditions .= " && region = $_POST[region]";
	}
	$conditions .= "ORDER BY id DESC";
	$array = $user->get($conditions);
	utility::member($array, 0);
	die();
}
echo '<div id="loadmore">';
	$conditions = "WHERE status = 2 ORDER BY id DESC LIMIT 0, 10";
	$array = $user->get($conditions); //Loading page one after another first the start page is 0
	utility::member($array, 0);
	echo "<a href='#' class='loading' onclick=\"loadmore(); return false;\"><input type='submit' class='button' value='Loadmore'/></a>";
echo '</div>';
//echo "<a href=\"membership.php?searchchat=search\"><input type='submit' class='button' value='Search by region'/></a>";

include("footer.php");
