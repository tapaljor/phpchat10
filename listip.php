<?php include('head.php'); ?>

<?php

	utility::check_authentication();

	$sql = "SELECT * FROM listip ORDER BY id DESC LIMIT 0, 100";
	$db->select_display($sql);//second perimter left space we dont need 'em

	$sno = 1;

	echo '<table><tr class="bold"><td>Sno</td><td>ip1</td><td>ip2</td><td>Country name</td></tr>';
	while($rows = $db->get_one_result_display()) {

		echo '<td>'.$sno.'</td>';

		$tokenh = md5($rows["id"].$_SESSION["tsa_gong"]);
		$hash = array(
			0 => $tokenh
			);

		foreach($rows as $field=>$value) {
			if( $field != 'id') {
				echo "<td><div class=\"$rows[id]$field\">";
				echo $rows["$field"];
				echo "<a href='#' class='edit_text' id=\"listip_$hash[0]_$rows[id]_$rows[$field]_$field\" style='font-size: 2em;'>&#9998;</a>";
				echo '</div></td>';
			}
		}
		echo '</tr>';
		$sno++;
	}
?>
	<div id="status"></div>
	<form class="form">
		<input type="hidden" id="action" name="action" value="listip" />
		<td><?php echo $sno; ?> </td>
	        <td><input type="text" id="ip1" name="ip1" placeholder="IP1" /> </td>
	        <td><input type="text" id="ip2" name="ip2" placeholder="IP2" /> </td>
	        <td><input type="text" id="countryname" name="countryname" placeholder="Country name" /> </td>
		<td><input type="submit" name="register" value="Save"/></td>
	</form>
<?php
	echo '</tr></table>';
?>

<?php include('footer.php'); ?>
