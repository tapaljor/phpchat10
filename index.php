<?php session_start();?>
<?php ob_start();?>
<head>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
	<!--- Without this it will assume mobile browser as full screen -->

  	<script src="js/jquery.js"></script>
      	<link rel="stylesheet" href="css/stylesheet.css"/>
      	<link rel="stylesheet" href="css/skyform.css"/>
</head>

<?php 

require_once('config.php');
require_once CLASSES . 'class.db.php';
require_once CLASSES . 'class.utility.php';
require_once CLASSES . 'class.user.php';
require_once CLASSES . 'class.log.php';

$db = new database();
$user = new user($db);
$log = new log($db);

utility::title("SOCKET IO");

//In some hosting hosting automatically updates my session, so I put this logi not to update session until I login in again.
if ( empty($_SESSION["tsa_gong"])) {
	$_SESSION["tsa_gong"] = utility::create_token(); //Used only once
}

if ( isset($_GET["logout"]) && !empty($_GET["logout"]) ) {

	$_data = array (
		'status' => 1,
		'id' => $_SESSION["idCHATP"]
		);
	$user->update($_data);

	//Getting last login from LOG table with ID
	$conditions = "WHERE user = '$_SESSION[AdminCHATP]' ORDER BY id DESC LIMIT 0, 1";
	$array = $log->get($conditions);
	foreach($array as $rows) {
		$lastid = $rows["id"];
	}

	$_data = array (
		'logouttime' => utility::gettime(),
		'id' => $lastid 
		);
	$log->update($_data);
	//End putting time to current logged in user

	$db->close_db();
	session_destroy();
	header('location: index.php');
}

if( isset($_POST["Login"]) && !empty($_POST["Login"]) ) {

	$logged_in = $salt = false;

	if( $_POST["tokenh"] != $_SESSION["tokenh"] ) {
		die('<div id="file_error">Token error</div>');
	}

	$status = false;
	$status = $user->check_login($_POST["username"], $_POST["password"]);
	if ( $status) {
		header('Location: membership.php');
	} else {
		die('<div id="file_error" style="color: red;">Access denied</div>');
	}
}
if ( isset($_POST["create_account"]) ) {
	header('Location: upload.php?add=38436bdd322322#97666*(&');
}
?>

<div class="body body-s" style="margin: auto; max-width: 300px;">
<form action="index.php" method="POST" class="sky-form" autocomplete="off">
	<header id="file_error">Login</header>
	<input type="hidden" id="tokenh" name="tokenh" value="<?php echo $_SESSION["tokenh"] = md5(utility::create_token()); ?>"/>
	<fieldset>
	<section>
		<label class="input">
		<input type="text" id="username" name="username" placeholder="Nickname*"/>
		</label>
	</section>
	<section>
		<label class="input">
		<input type="password" id="password" name="password" placeholder="Password*"/>
		</label>
	</section>
	</fieldset>
	<footer>
		<input type="submit" name="Login" value="Log in" class="button"/>
		<input type="submit" name="create_account" value="Create an account" class="button"/>
	</footer>
</form>
</div>
