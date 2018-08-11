<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2009 Refresh Web Development [www.RefreshMy.com]
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/public/register.php');
	
	$guid = isset($_GET['guid']) ? $_GET['guid'] : 0;
	
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "users WHERE GUID = '" . cIn($guid) . "'");
	if(hasRows($result)){
		$uID = cOut(mysql_result($result,0,0));
		$fname = cOut(mysql_result($result,0,1));
		$lname = cOut(mysql_result($result,0,2));
		$email = cOut(mysql_result($result,0,3));	
		echo '<br />';
		echo $hc_lang_register['Unsub'];
		echo '<br /><br />';
		echo '<b><a href="' . CalRoot . '" class="eventMain">' . $hc_lang_register['ExitLink'] . '</a></b>';
		echo '<br /><br />';
		echo $hc_lang_register['Resub'];
		echo '<br /><br />';
		echo '<b>' . $hc_lang_register['Name'] . '</b> <i>' . $fname . ' ' . $lname . '</i><br />';
		echo '<b>' . $hc_lang_register['Email'] . '</b> <i>' . $email . '</i><br /><br />';
		echo '<form name="unsubscribe" id="unsubscribe" method="post" action="' . HC_UnsubscribeAction . '">';
		echo '<input type="hidden" name="dID" id="dID" value="' . $guid . '" />';
		echo '<input type="submit" name="submit" id="submit" value="Unsubscribe Now" class="button" />';
		echo '</form>';
	} else {
		echo "<br />";
		echo $hc_lang_register['NoUnsub'];
		echo "<br /><br />";
	}//end if?>