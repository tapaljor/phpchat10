<head>
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
	<!--- Without this it will assume mobile browser as full screen -->

	<link rel="stylesheet" href="css/skyform.css"/><!-- Registration form-->
	<link rel="stylesheet" href="css/stylesheet.css"/><!-- Registration form-->

	<script src="js/jquery.js"></script>
	<script src="js/custom.js"></script>
</head>
<?php
session_start();

header("Cache-control: max-age: 1000");

require_once('config.php');
require_once CLASSES . 'class.db.php';
require_once CLASSES . 'class.utility.php';
require_once CLASSES . 'class.listcountry.php';
require_once CLASSES . 'class.listregion.php';
require_once CLASSES . 'class.listhobby.php';
require_once CLASSES . 'class.user.php';

$db = new database();
$listcountry = new listcountry($db);
$listregion = new listregion($db);
$listhobby = new listhobby($db);
$user = new user($db);

utility::title('SOCET IO');

if ( isset($_GET["add"]) && !empty($_GET["add"])) {
?>
	<div class="body body-s" style="margin: auto; max-width: 400px;">
	<form action="upload.php" method="POST" class="sky-form">
		<header><div id="file_error">Registration</div></header>

		<input type='hidden' name='registerdate' value="<?php echo $nowstamp; ?>"/>
		<input type="hidden" name="salt" value="<?php echo utility::create_token(); ?>"/>

		<fieldset>
		<section>
			<label class="input">
			<input type="text" placeholder="Nickname*" name="username" required="required" onchange="check_username(this.value);">
			<b class="tooltip tooltip-bottom-right">Only alpha numeric for username/nickname</b>
			</label>
		</section>
		<section>
			<label class="input">
			<input placeholder="Password*" type="password" name="password" required="required">
			</label>
		</section>
		<section>
			<label class="input">
			<input placeholder="Confirm password*" type="password" required="required" name="re_password" onchange="compare_password(password, re_password);">
			</label>
		</section>
		<section>
			<label class="select">
			<select name="gender" required>
				<option value="">Select gender</option>
				<option value="1">Male</option>
				<option value="2">Female</option>
				<option value="3">Transgender</option>
			</select>
			</label>
		</section>
		<section>
			<label class="select">
			<select name="country" required onchange="getlistregion(this.value); return false;">
				<option value="">Select your country</option>
				<?php
				$array1 = $listcountry->get();
				foreach($array1 as $rows1) {
					echo "<option value=\"$rows1[code]\">$rows1[name]</option>";
				}		
			?>
			</select>
			</label>
		</section>
		<section id="listregion">
		</section>
		<section>
			<label class="select">
			<select name="hobby" required>
				<option value="">Select hobby</option>
			<?php
				$array1 = $listhobby->get();
				foreach($array1 as $rows1) {
					echo "<option value=\"$rows1[id]\">$rows1[name]</option>";
				}		
			?>
			</select>
			</label>
		</section>
		<section>
			<img src="myimage.php" width="150em;"/>
		</section>
		<section>
			<label class="input">
			<input type="text" name="captcha" required="required" placeholder="Enter code above"/>
			<b class="tooltip tooltip-bottom-right">Enter code above</b>
			</label>
		</section>
		<section>
			<i>I agree terms & conditions. <a href="#" onclick="readtc(); return false;">Read T&C</a></i>
		</section>
		<section id="tc" style="border: 1px solid silver; padding: 1em; display: none;">
		<h4 style="text-decoration: underline;">Terms and Conditions</h4>
		<p>Tashi Delek!</p>
		<p>Thank you for visting the page.</p>
		<p>Minors are discouraged to visit the page.</p>
		<p>Registration is free.</p>
		<p>The is an entertainment application.</p>
		<p>'Honey can attract more honey'. Please be nice.</p>
		<p>This application hold no responsible for emotional assault.</p>
		</section>
		</fieldset>
		<footer>
			<button type="submit" name="register" class="button" value="register">Create account</button>
		</footer>
	</form>
	</div>
<?php
}

if ( isset($_POST["register"])  && !empty($_POST["register"]) ) {

	$_POST = $db->clean_array($_POST);

	if($_POST["captcha"] !== $_SESSION["validation_code"]) {
		die('<div id="file_error">Captcha failure</div>');
	}
	utility::is_username($_POST["username"]);

	$users = $user->get_num_rows("WHERE username = '$_POST[username]'");
	if ( $users > 0 ) {
		 die('<div id="file_error">Username is already there</div>');
	}
	
	if( $_POST["password"] != $_POST["re_password"]) {
		 die('<div id="file_error">Password not matching</div>');
	}
	$_POST["password"] = md5(md5($_POST["password"]).$_POST["salt"]);

	//Fields that are not required for database
	unset($_POST["save"]);
	unset($_POST["tokenh"]);
	unset($_POST["re_password"]);
	unset($_POST["captcha"]);
	unset($_POST["checkbox"]);
	unset($_POST["register"]);

	$_POST["status"] = 2;
	$status = $user->add($_POST);

	if ( $status ) { 

		$conditions = "ORDER BY id DESC LIMIT 0, 1";
		$array = $user->get($conditions);
		foreach ( $array as $rows) {

			$_SESSION["tsa_gong"] = utility::create_token();
			$_SESSION["AdminCHATP"] = $rows["username"];
			$_SESSION["idCHATP"] = $rows["id"];
			$_SESSION["idhashCHATP"] = md5($rows["id"].md5($_SESSION["tsa_gong"]));
		}
		header('location: account.php');
	} else {
		echo '<div id="file_error">Registration failed</div>';
	}
}
