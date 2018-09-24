<?php require_once('head.php');

$destinationid = $destinationuser = '';
if (isset ($_GET["destinationh"]) && !empty($_GET["destinationh"])) {

	$destinationid = $user->match_hash($_GET["destinationh"]);

	if ( isset($_GET["message"]) && !empty($_GET["message"])) {
		$message = $_GET["message"];
	}

	$conditions = "WHERE id = $destinationid";
	$users = $user->get($conditions);
	foreach($users as $rows) {

		$destinationuser = $rows["username"];
		$encryptkey = $rows["salt"];
		utility::title("Session with $destinationuser");

		if ( $destinationuser !== $_SESSION["AdminCHATP"]) {

			echo '<header>';
				echo "<a href=\"particular_one.php?idh=$_GET[destinationh]\" class='names'>Session with ".$rows["username"].'</a></p>';
			echo '</header>';
		}
	}
}
?>

<div class="body body-s">
<form href="#" class="sky-form" id="messageform">

	<fieldset>

	<section id="messagebox">
	<?php
		if ( !empty($message)) {
			echo '<div class="incoming">'.$destinationuser.': '.$message.'</div>'; 
		}
	?>
	</section><!-- WHERE messages are displayed -->

	<input type="hidden" id="sender" value="<?php echo $_SESSION["idCHATP"]; ?>"/>
	<input type="hidden" id="destination" value="<?php echo $destinationid; ?>"/>
	<input type="hidden" id="senderusername" value="<?php echo $_SESSION["AdminCHATP"]; ?>"/>
	<input type="hidden" id="time" value="<?php echo $nowstamp; ?>"/>

	<!-- To represent person you are sending that IS DESTINATION, it may be available in blank too, (shouldn't come under if statement). Incase user didn't choose person to chat also receive message. That is you receive message even if you didn't choose person to chat -->
	<input type="hidden" id="tsa_gong" value="<?php echo $_SESSION["tsa_gong"]; ?>"/>
	<!--Your browser's TOKEN SESSION, to be used to click to reply message black by click on sender ID, As it should come in HASH for security reason.-->

	<?php
	if ( isset($destinationid) && !empty($destinationid)) {
	?>
		<section>
			<label class="input">
			<input type="text" id="message" required="required" placeholder="Message..." maxlength="200">
			</label>
		</section>
		<section>
			<input type="submit" class="button" style="margin: .2em 0 0 0;" value="Send"/>
		</section>
	<?php
	}
	?>
	</fieldset>
</form>
</div>

<?php require_once('footer.php');?>
