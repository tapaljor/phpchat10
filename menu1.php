<div id='cssmenu'>
<ul>
   	<li><a href='membership.php'>Chat</a></li>
   	<li><a href='messagearchive.php'>Message archive</a>
   	<li><a href='account.php'>Account</a>
   	<li><a href='tibet_timeline.php'>Tibet</a></li>
	<?php
		if ( utility::check_authentication()) {
			echo "<li><a>$_SESSION[AdminCHATP]</a></li>";
			echo "<li><a href=\"index.php?logout=yes\">Log out</a></li>";
		} else  {
			echo "<li><a href=\"index.php\">Log in</a></li>";
		}
	?>
</ul>
</div>

