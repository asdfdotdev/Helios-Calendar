<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2008 Refresh Web Development [www.RefreshMy.com]
	
	Developed By: Chris Carlevato <support@refreshmy.com>
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar EUL found at www.HeliosCalendar.com/license.pdf
*/
	include($hc_langPath . $_SESSION['LangSet'] . '/public/register.php');
	
	$guid = 0;
	if(isset($_GET['guid'])){
		$guid = $_GET['guid'];
	}//end if
	
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "users WHERE GUID = '" . cIn($guid) . "'");
	
	if(hasRows($result)){
		$uID = cOut(mysql_result($result,0,0));
		$fname = cOut(mysql_result($result,0,1));
		$lname = cOut(mysql_result($result,0,2));
		$email = cOut(mysql_result($result,0,3));	
		echo "<br />";
		echo $hc_lang_register['Unsub'];
		echo "<br /><br />";
		echo "<b><a href=\"" . CalRoot . "\" class=\"eventMain\">" . $hc_lang_register['ExitLink'] . "</a></b>";
		echo "<br /><br />";
		echo $hc_lang_register['Resub'];
		echo "<br /><br />";	?>
		<b><?php echo $hc_lang_register['Name'];?></b> <i><?php echo $fname . " " . $lname;?></i><br />
		<b><?php echo $hc_lang_register['Email'];?></b> <i><?php echo $email;?></i><br /><br />
		<form name="unsubscribe" id="unsubscribe" method="post" action="<?php echo HC_UnsubscribeAction;?>">
			<input type="hidden" name="dID" id="dID" value="<?php echo $guid;?>" />
			<input type="submit" name="submit" id="submit" value="Unsubscribe Now" class="button" />
		</form>
<?php
	} else {
		echo "<br />";
		echo $hc_lang_register['NoUnsub'];
		echo "<br /><br />";
	}//end if	?>