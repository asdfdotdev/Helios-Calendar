<?php
/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development LLC
 * @link http://www.refreshmy.com
 * @copyright (C) 2004-2012 Refresh Web Development
 * @license http://www.helioscalendar.com/license.html
 * @package Helios Calendar
 */
/*
	~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	|	Modifying or in anyway altering source code contained in this file is 	|
	|	not permitted and violates the Helios Calendar Software License Agreement	|
	|	DO NOT edit or reverse engineer any source code or files with this notice.	|
	~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
*/
	if(!isset($_SESSION['license']) || $_SESSION['license'] == false || !isset($_SESSION['review']) || $_SESSION['review'] == false || !isset($_SESSION['valid']) || $_SESSION['valid'] == false || !isset($_SESSION['done']) || $_SESSION['done'] == false){
		hc_fail();
	} else {
		echo '
		<fieldset>
			<h3>Congratulations!...</h3>
			<p>
				Setup of your Helios Calendar has finished. To complete the install process <b>delete the /setup directory</b> and click the links below to start using your Helios Calendar.
			</p>
			<p>
				<a href="' . CalRoot . '/" target="_blank" style="line-height:20px;">Click here to access your public calendar.</a>
			</p>
			<p>
				<a href="' . AdminRoot . '/" target="_blank" style="line-height:20px;">Click here to access your administration console.</a>
			</p>
			
			<h3>...and Welcome!</h3>
			<p>
				Helios Calendar is not built by Refresh Web Development alone, it is supported by a close-knit community of our fellow developers, publishers, event promoters and people from every walk of life around the world.
			</p>
			<p>
				We invite you to join the conversation and share your Helios Calendar story at <a href="http://www.refreshmy.com/forum/" target="_blank">the Refresh Community Forum</a>.
			</p>
			<p>
				Thank you for using Helios Calendar!
			</p>
		</fieldset>';
	}
?>