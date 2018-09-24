<?php include('head.php'); ?>
<?php	
require_once CLASSES . 'class.likedislike.php';
require_once CLASSES . 'class.log.php';
require_once CLASSES . 'class.listregion.php';
require_once CLASSES . 'class.listcountry.php';
require_once CLASSES . 'class.listhobby.php';

$likedislike = new likedislike($db);
$log = new log($db);
$listregion = new listregion($db);
$listcountry = new listcountry($db);
$listhobby = new listhobby($db);

if( isset($_GET["idh"]) && !empty($_GET["idh"])) {

	$id = $user->match_hash($_GET["idh"]);

	$conditions = "WHERE id = $id";
	$array = $user->get($conditions);

	foreach($array as $rows) {

		$idh = md5($rows["id"].md5($_SESSION["tsa_gong"]));

		?>
		<div class="body body-s" id="loginform" style="margin: auto;">
		<form action="account.php" method="POST" class="sky-form">
		<header><?php echo $rows["username"];?></header>
		<fieldset>
		<section class="col col-2">
			<div class="container3">
			<?php
				if( $rows["gender"] == 1) { 
					echo '<img src="images/male.jpeg"/>';
				} else {
					echo '<img src="images/female.jpeg"/>';
				}
			?>
			</div>
			<?php
			if ( $rows["status"] == 2) {
				echo "<a style='color: green; font-size: 1em;' title=\"$rows[username] is online\" href=\"home.php?destinationh=$idh\">Online</a>";
			} else {
				echo "<a style='color: red; font-size: 1em;' title=\"$rows[username] is not active for now\">Offline</a>";
			}
			echo "<br/><a style='font-family: times; font-size: 1em; font-style: italic' href=\"home.php?destinationh=$idh\">Start chat</a><br/>";
			utility::get_qr($rows["username"]);
			?>
		</section>
		<section class="col col-6">
			<table>
			<tr><td><b>Nickname: </b></td>
			<td> <?php echo $rows["username"];?> </td>

			<tr><td> <b>Gender: </b></td>
			<td>
			<?php 
				if ( $rows["gender"] == 1) {
					echo 'Male';
				} else {
					echo 'Female';
				}
				?> 
			</td>
			<tr><td> <b>Region: </b> </td>
			<td>
			<?php 
				$array1 = $listregion->get("WHERE id = $rows[region]");
				foreach($array1 as $rows1) {
					echo $rows1["name"];
				}
				?>
			</td>
			<tr><td> <b>Country:</b></td>
			<td>
			<?php
				$array1 = $listcountry->get("WHERE code = '$rows[country]'");
				foreach($array1 as $rows1) {
					echo $rows1["name"];
				}
				?> 
			</td>
			<tr><td> <b>Hobby:</b></td>
			<td>
			<?php
				$array1 = $listhobby->get("WHERE id = $rows[hobby]");
				foreach($array1 as $rows1) {
					echo $rows1["name"];
				}
				?> 
			</td>
			<tr><td><b>Bio data: </b></td>
			<td> <?php echo str_replace("\\n", "<br/>", $rows["biodata"]);?> </td>
			<tr>
			<td id="likedislike" colspan="2">
			<?php
				$like = $dislike = false;

				$likes = $dislikes = false;
				$conditions = "WHERE loginid = $rows[id] && type = 1";
				$likes = $likedislike->get_total_rows($conditions);
		
				$conditions = "WHERE loginid = $rows[id] && type = 0";
				$dislikes = $likedislike->get_total_rows($conditions);

				//Parameter passed for ajax like dislike loginid, likerid and 0, 1 like and dislike respectively
				echo "<a href='#' title='Like' onclick=\"likedislike('$idh', '$_SESSION[idhashCHATP]', 1); return false\" style='font-size: 2em;'>&#128077; <span style='font-size: 14px;'>$likes &nbsp;</span></a> ";
				echo "<a href='#' title='Dislike' onclick=\"likedislike('$idh', '$_SESSION[idhashCHATP]', 0); return false\" style='font-size: 2em; color: red;'>&#128078; <span style='font-size: 14px; color: red;'> $dislikes</span></a> ";
			?>
			</td>
			</table>
		</section>
		<fieldset>
		</form></div>
	<?php
	}
} 

include('footer.php');


