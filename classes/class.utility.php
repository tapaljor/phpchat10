<?php

@session_start();

class utility {

	static function is_admin() {

		$status = false;
		if (isset($_SESSION["AdminCHATP"]) && $_SESSION["AdminCHATP"] == 'm2') {
			$status = true;
		}
		return $status;
	}
	static function is_post() {

		  return (
		  	isset($_SERVER['REQUEST_METHOD']) &&
		    		trim($_SERVER['REQUEST_METHOD']) != '' &&
		     		trim($_SERVER['REQUEST_METHOD']) == 'POST'
		     ) ? true : false;
	}
	static function pr($a) {
		echo '<pre>';
		print_r($a);
		echo '</pre>';
	}
	static function head_line($head) {

		echo "<title>$head</title>";
	}
	static function is_numeric($id) {

		if( !is_numeric($id)) {
			die('<div id="file_error">Not a numeric value</div>');
		}
	}
	static function is_alphanumeric( $text = '' ) {

		if ( !preg_match('/^[a-zA-Z0-9]+$/', $text ) ) {
			die('<p id="status">Only alpha numerics are allowed</p>');
		}
	}
	static function is_username( $text = '' ) {

		$status = true;
		if(!preg_match('/^[a-zA-Z0-9]+$/',$text) ) {
			$status = false;
		}
		if ( !$status) {
			die('<div id="file_error">Username cannot contain special characters or spaces</div>');
		}	
	}
	static function check_authentication() {

		$status = false;
		if( isset($_SESSION["AdminCHATP"]) && !empty($_SESSION["AdminCHATP"]) ) {
			$status = true;
		}
		return $status;
	}
	static function create_token() {

		for ($i = -1; $i <= 16; $i++) {
			$bytes = openssl_random_pseudo_bytes($i, $cstrong);
		       	$random_no = bin2hex($bytes);
		}
		return $random_no;
	}
	static function check_captcha($validation_code_from_user = '') {

		if($validation_code_from_user !== $_SESSION["validation_code"]) {
			die('<div id="file_error">Captcha failure</div>');
		}
	}
	static function get_qr($valued) {
		echo "<img src=qr_code/php/qr_img.php?d=$valued/>";
	}
	static function validate_email($email) {

		$status = true;
		if ( !filter_var($email, FILTER_VALIDATE_EMAIL) ) {
			$status = false;
		}
		return $status;
	}
	static function member( $array = array() ) {

		require_once 'config.php';
		require_once CLASSES . 'class.db.php';
		require_once CLASSES . 'class.likedislike.php';
		require_once CLASSES . 'class.listregion.php';
		require_once CLASSES . 'class.listhobby.php';

		$db = new database();
		$likedislike = new likedislike($db);
		$listregion = new listregion($db);
		$listhobby = new listhobby($db);
		
		$count = 0;
		foreach ( $array as $rows) {

			$idh = md5($rows["id"].md5($_SESSION["tsa_gong"]));
	
			echo '<div id="membership">';

				echo '<div class="container4">';
				if ( empty($rows["image"]) ) {
					echo "<a href=\"particular_one.php?idh=$idh\" title='Click here for detail'>";
					if( $rows["gender"] == 1) { 
						echo '<img src="images/male.jpeg"/>';
					} else {
						echo '<img src="images/female.jpeg"/>';
					}	
					echo '</a>';
				} else {
					echo "<a href=\"particular_one.php?idh=$idh\"><img src=\"upload/$rows[image]\" title='Click here for detail'/></a>";
				}
				echo '</div>';

				echo '<div class="profile">';
					echo "<p><a href=\"particular_one.php?idh=$idh\"><b>".substr($rows["username"],0,10).' | </b></a>';
					if ( $rows["gender"] == 1) {
						echo '<i>Male </i>';
					} else {
						echo '<i>Female </i>';
					}
					if ( $rows["status"] == 2) {
						echo '<span style="color: green; font-size: 1em; font-family: times; font-style: italic;"> (Online)</span>';
					} else {
						echo '<span style="color: red; font-size: 1em; font-family: times; font-style: italic;"> (Offline)</span>';
					}
					echo '</p>';
					$array1 = $listregion->get("WHERE id = $rows[region]");
					foreach($array1 as $rows1) {
						$regionname = $rows1["name"];
					}
					$array1 = $listhobby->get("WHERE id = $rows[hobby]");
					foreach($array1 as $rows1) {
						$hobbyname = $rows1["name"];
					}
					echo '<p>'.$regionname.' | '.$hobbyname;
					echo "<a href=\"home.php?destinationh=$idh\" class='names'> | Start chat</a>";
					echo '</p>';
				echo '</div>';
			echo '</div>';
			$count++;
		}
	}
	static function ads($results = array(), $from) {

		require_once 'config.php';
		require_once CLASSES . 'class.db.php';
		require_once CLASSES . 'class.countryinfo.php';
		require_once CLASSES . 'class.postadimage.php';

		$db = new database();
		$countryinfo = new countryinfo($db);
		$postadimage = new postadimage($db);

		echo '<div class="body body-s">';
		echo '<form class="sky-form">';
			echo '<fieldset>';
			echo '<section>';
			echo '<table><tr>';
			$count = 0;
			foreach($results as $rows) {

				$idh = md5($rows["id"].md5($_SESSION["tsa_gong"]));
				//AD idh

				echo '<section>';
					$currency = false;
					$countryinfos = $countryinfo->get("WHERE id = $rows[currency]");
					foreach($countryinfos as $rows1) {
						$currency = $rows1["currency_code"];
					}
					$time = utility::time_ago($rows["created"]);

					$conditions = "WHERE ad_id = $rows[id] ORDER BY id DESC LIMIT 0, 1";
					$postadimages = $postadimage->get($conditions);
					echo '<tr><td style="width: 1em; padding: 0 1em 0 0;">'; 
					$check = 0;
					foreach($postadimages as $rows1) {
						echo "<div class='container4'><img width='80px' src=\"upload_chat/$rows1[image]\"></div>";
						$check++;
					}
					if ( $check == 0) {
						echo '<div class="container4"><p style="font-size: 7em; margin: 0 0 1em 0;">&#127748;</p></div>';
					}
					echo '</td>';
					echo "<td><a href=\"particular_one_ad.php?idh=$idh\"><span style='font-style: italic;' class='names'>".substr($rows["title"], 0, 30).'...</span><br />'.$rows["brand"].' | '.$time.'</a></td>';
					echo '<td class="names">'.$currency.' '.$rows["price"].'</td></tr>';	
				echo '</section>';
				$count++;
			}
			echo '</tr></table>';
			echo '</section>';
			echo '</fieldset>';
		echo '</form>';
		echo '</div>';

		if( $count < 6) {
			$from = false;
		} else {
			$from = $from+6;
		}
		echo "<input type='hidden' class='from' value=\"$from\">";
	}
	static function time_ago($time) {
	
		$etime = time() - $time;
	
		if ($etime < 1) {
			return '0 seconds';
		}
		
		$a = array( 12 * 30 * 24 * 60 * 60  =>  'year',
			30 * 24 * 60 * 60           =>  'month',
			24 * 60 * 60                =>  'day',
			60 * 60                     =>  'hour',
			60                          =>  'min',
			1        	            =>  'sec' 
			);
		
		foreach ($a as $secs => $str) {
			$d = $etime / $secs;
			
			if ($d >= 1) {
				$r = round($d);
				return $r . ' ' . $str . ($r > 1 ? 's' : '') . ' ago';
			}
		}
	}
	static function clean_dirty_words($message) {

		//bad words array which is used to hide/remove if somebody types
		$words = "shit, piss, fuck, cunt, cock, fuk, sucker, mother, fucker, tits, turd, twat, nigger, nigro, beaner, spic, gooback, sandmonkey, homo, ligpa, likpa, kyakpa, kyagpa";

		$badwords = array();
		$badwords = explode(",", $words);
		$message = explode(" ", $message);

		foreach($message as $key=>$word) {

			foreach($badwords as $bad) {

				if ( trim(strtolower($bad)) === trim(strtolower($word))) {
					$word = '!@#$';
					break;
				} 
			}
			$array["$key"] = $word;
		}
		return $array;
	}
	static function title($title) {
		echo '<title>'.$title.'</title>';
	}
	static function gettime() {

		$now = date('Y-m-d H:i:s');
		return $nowstamp = strtotime($now);
	}

}
