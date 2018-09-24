</section>
</fieldset>
<div id="footer">
	<a href="https://www.facebook.com/" title="Facebook"><img src="images/facebook.svg"></a>
	<a href="https://www.twitter.com" title="Twitter"><img src="images/g+.svg"></aa>
	<a href="https://www.google.com/g+" title="Google plus"><img src="images/twitter.svg"></a>

	<?php
	if ( utility::check_authentication() && $_SESSION["AdminCHATP"] == 'm2') {
		echo '<ul>';
		echo '<li><a style="font-weight: bold;">ADMIN</a></li>';
		echo '<li><a href="listcountry.php">Countries</a></li>';
		echo '<li><a href="listregion.php">Regions</a></li>';
		echo '<li><a href="listword.php">Bad words</a></li>';
		echo '<li><a href="listhobby.php">Hobbies</a></li>';
		echo '</ul>';
	}
	?>
	<p style="margin-top: 1em; font-size: .6em;">Copyright &copy; 2017</p>
</div>
</body>
</html>
<?php ob_flush();?>
