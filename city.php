<?php include("head.php");?>

<?php
	require_once CLASSES . 'class.country.php';
	require_once CLASSES . 'class.state.php';
	require_once CLASSES . 'class.city.php';
	require_once CLASSES . 'class.log.php';

	$country = new country($db);
	$state = new state($db);
	$city = new city($db);
	$log = new log($db);

	if ( utility::check_authentication() && isset($_GET["add_city"]) && !empty($_GET["add_city"])) {

		echo <<<HERE
		<div class="body body-s">
		<form action="city.php" method="POST" class="sky-form">
			<header>Add city</header>
			<input type="hidden" name="action" value="Add city"/>
			<fieldset>
			<section><label class="select">
			<select name="country" onchange="getstate(country);">
HERE;
			$countrys = $country->get();
			foreach($countrys as $rows) {
				echo "<option value=\"$rows[id]\">$rows[name]</option>";
			}
			echo <<<HERE
			</select>
			</section>
			<section id="state"></section>
			<section>
				<label class="input">
				<input type="text" name="name" placeholder="Enter country"/>
				</label>
			</section>
			</fieldset>
			<footer>
 				<input type="submit" name="save" class="button" value="Save">
			</footer>
		</form>
		</fieldset>
HERE;
		die();
	}

	if(isset($_POST["save"]) && !empty($_POST["save"]) ) {

		$_POST = $db->clean_array($_POST);

		$_data = array(
			'stateid' => $_POST[state],
			'name' => $_POST[name]
			);

		$status = false;
		$status = $city->add($_data);

		if($status) {

			$loga = array(
				'which' => $_POST["action"],
				'what' => $_POST["name"],
				'time' => $nowstamp,
				'user' => $_SESSION["AdminCHATP"]
				);
			$log->add($loga);
												
			header('Location: city.php');
		} else {
			echo '<p color: #fff;">Error: cannot upload city</p>';
		} 
	}
	if( isset($_POST["update"]) ) {

		$_POST = $db->clean_array($_POST); 

		$status = false;
		$status = $city->update($_POST);

		if($status) {

			$loga = array(
				'which' => $_POST["action"],
				'what' => $_POST["name"],
				'time' => $nowstamp,
				'user' => $_SESSION["AdminCHATP"]
				);
			$log->add($loga);

			header('Location: city.php');
		} else {
			echo '<p style="color: #fff;">Error: cannot update</p>';
		}
	}
	if( isset($_GET["delete"])) {

		$_GET = $db->clean_array($_GET);
		$id = $city->match_hash($_GET["delete"]);
		utility::is_numeric($id);

		$conditions = "WHERE id = $id";
		$status = $city->delete($conditions);

		if ( $status) {
			header("Location: city.php");
		} else {
			die("<p class='file_error'>Cannot delete country</p>");
		}
	}
	if( isset($_GET["edit"]) ) {

		$_GET = $db->clean_array($_GET);
		$id = $city->match_hash($_GET["edit"]);
		utility::is_numeric($id);

		$citys = $city->get("WHERE id = $id");
		foreach($citys as $rows) {

			echo '<div class="body body-s">';
			echo '<form action="city.php" method="POST" class="sky-form">';
				echo '<header>Edit</header>';
				echo '<fieldset>';
				echo "<input type=\"hidden\" name='action' value='Edit city'/>";
				echo "<input type=\"hidden\" name=\"id\" value=\"$rows[id]\"/>";

				echo '<section>State:</section>';
				echo '<section><label class="select"><select name="stateid">';
				$states = $state->get("WHERE id = $rows[stateid]");
				foreach($states as $rows1) {
					$countryid = $rows1["countryid"];
					echo "<option value=\"$rows1[id]\">$rows1[name]</option>";
				}
				$states = $state->get("WHERE countryid = $countryid");
				foreach($states as $rows1) {
					echo "<option value=\"$rows1[id]\">$rows1[name]</option>";
				}
				echo '</select><label></section>';
				echo '<section>Country:</section>';
				$countrys = $country->get("WHERE id = $countryid");
				foreach($countrys as $rows1) {
					echo $rows1["name"];
				}
				echo '<section>';
				echo '</select><label></section>';
				echo '<section><label class="input">';
				echo "<input type='text' name='name' value=\"$rows[name]\"/>";
				echo '</label></section>';
				echo '</fieldset>';
				echo '<footer>';
				echo '<input type="submit" name="update" class="button" value="Update"/>';
				echo '</footer>';
			echo '</form></fieldset></div>';
		}
		die();
	} else {

		$conditions = "ORDER BY id DESC LIMIT 0, 200";
		$cities = $city->get($conditions);

		echo '<div class="body body-s">';
		echo '<form class="sky-form">';
		echo '<header>Cities</header>';
		echo '<fieldset>';
		foreach($cities as $rows) {

			$idh = md5($rows["id"].md5($_SESSION["tsa_gong"]));

			echo '<div class="row">';
			echo "<section class='col col-6'><label class='input'><a href=country.php?id=$idh>".$rows["name"].'</label></section>';

			if ( utility::check_authentication()) {
				echo '<section class="col col-3">';
	               		echo "<a href=\"city.php?edit=$idh\" style=\"color: green;\"/>edit </a>";
				echo '</section>';

				echo '<section class="col col-3">';
	               		echo "<a href=\"city.php?delete=$idh\" style=\"color: red;\"/>X</a>";
				echo '</section>';
			}
			echo '</div>';
		}
               	echo "<footer><a href=\"city.php?add_city=yes\"><input type='button' class='button' value='Add city'/></a></footer>";
		echo '</fieldset></form></div>';
	}

	include("footer.php");
