<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(!isset($_SESSION['license']) || $_SESSION['license'] == false || !isset($_SESSION['review']) || $_SESSION['review'] == false || !isset($_SESSION['done']) || $_SESSION['done'] == false){
		hc_fail();
	} else {
		echo '
		<fieldset>
			<h3>Congratulations!</h3>
			<p>
				Setup of your Helios Calendar has finished. To complete the install process <b>delete the /setup directory</b> and click the links below to start using your Helios Calendar.
			</p>
			<p>
				<a href="' . CalRoot . '/" target="_blank" style="line-height:20px;">Click here to access your public calendar.</a>
			</p>
			<p>
				<a href="' . AdminRoot . '/" target="_blank" style="line-height:20px;">Click here to access your administration console.</a>
			</p>
		</fieldset>';
	}
?>