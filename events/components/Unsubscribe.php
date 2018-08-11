<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	if(isset($_GET['guid'])){
		$guid = $_GET['guid'];
	} else {
		$guid = 0;
	}//end if
	
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "users WHERE GUID = '" . $guid . "'");
	
	if(hasRows($result)){
		$uID = mysql_result($result,0,0);
		$fname = mysql_result($result,0,1);
		$lname = mysql_result($result,0,2);
		$email = mysql_result($result,0,3);
		$oID = mysql_result($result,0,4);
		$zip = mysql_result($result,0,5);
		$guid = $_GET['guid'];
	?>
		You are unsubscribing the following account.<br>
		<li>To cancel <a href="<?echo CalRoot;?>" class="eventMain">leave this page now</a>.<br>
		<li>If you wish to resubscribe later you may do so here at the <?echo CalName;?> website.
		<br><br>
		<b>Name:</b> <i><?echo $fname . " " . $lname;?></i><br>
		<b>Email:</b> <i><?echo $email;?></i><br><br>
		<form name="unsubscribe" id="unsubscribe" method="post" action="<?echo HC_UnsubscribeAction;?>">
			<input type="hidden" name="dID" id="dID" value="<?echo $guid;?>">
			<input type="submit" name="submit" id="submit" value="Unsubscribe Now" class="eventButton">
		</form>
	<?
	} else {
	?>
		Invalid account.
		<br><br>
		<a href="<?echo CalRoot;?>/" class="eventMain">Click here to return to the <?echo CalName;?></a>
	<?
	}//end if
?>