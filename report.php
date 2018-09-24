<?php
include('head.php');
require_once('geoiploc.php');

utility::check_authentication();

$status = false;
if ( isset($_POST["go"]) && !empty($_POST["go"]) ) {

	if( $_POST["password"] == 'Tibet1959?') {
		$status = true;
	} else {
		die('<div id="file_error">You ran out of luck</div>');
	}
}

if( $status == 1) {

	$sql = "SELECT id FROM chat WHERE destination = $_SESSION[idCHAT] ORDER BY id DESC LIMIT 0, 1";
	$db->select_display($sql);
	while($rows = $db->get_one_result_display()) {
		$_SESSION["destination_OLD"] = $rows["id"];
	}

	echo '<p><b>Visitor country wise</b></p>';
	$total = 0;
	$result = $db->query("SELECT code, countryname FROM country", "all");
	while($rows1 = mysqli_fetch_assoc($result)) {

		$sql2 = $db->select_display("SELECT ip FROM login WHERE ip != ''");
		$num = 0;
		while($rows2 = $db->get_one_result_display($sql2)) {

			$ip = trim($rows2["ip"]);

			$country_code = getCountryFromIP($ip, "code");

			if(trim($rows1["code"]) == trim($country_code) ) {
				$num++;
			}
		}
		if( $num != 0) {
			$array["$rows1[countryname]"] = $num;

			//echo '<p>'.$rows1["countryname"].' = '.$num.'</p>';
		}
	}
	$_SESSION["r_array"] = $array;
	$_SESSION["title"] = 'Country wise member';
	$_SESSION["type"] = 'doughnut';
	
	require_once('graph.php');
}

?>
<div class="signup" style="width: 400px;">
<form method="post" action="report.php">
	<p><input type="password" name="password" placeholder="password"></p>
	<p><input type="submit" name="go" value="Enter"/></p>
</form>
</div>
<?php
include('footer.php');


