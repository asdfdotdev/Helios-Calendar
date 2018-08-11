<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright � 2007 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	$guid = 0;
	if(isset($_GET['guid'])){
		$guid = $_GET['guid'];
	}//end if
	
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "users WHERE GUID = '" . cIn($guid) . "'");
	
	if(hasRows($result)){
		$uID = cOut(mysql_result($result,0,0));
		$fname = cOut(mysql_result($result,0,1));
		$lname = cOut(mysql_result($result,0,2));
		$email = cOut(mysql_result($result,0,3));	?>
		<br />
		You are unsubscribing from our event newsletter.
		<br /><br />
		<b>To cancel <a href="<?php echo CalRoot;?>" class="eventMain">leave this page now</a>.</b>
		<br /><br />
		If you wish to resubscribe later you may do so here at the <?php echo CalName;?> website.
		<br /><br />
		<b>Name:</b> <i><?php echo $fname . " " . $lname;?></i><br />
		<b>Email:</b> <i><?php echo $email;?></i><br /><br />
		<form name="unsubscribe" id="unsubscribe" method="post" action="<?php echo HC_UnsubscribeAction;?>">
			<input type="hidden" name="dID" id="dID" value="<?php echo $guid;?>" />
			<input type="submit" name="submit" id="submit" value="Unsubscribe Now" class="button" />
		</form>
<?php
	} else {	?>
		<br />
		Invalid account.
		<br /><br />
		<a href="<?php echo CalRoot;?>/" class="eventMain">Click here to return to the <?php echo CalName;?></a>
<?php	
	}//end if	?>