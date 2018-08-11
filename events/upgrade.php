<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2006 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/	
	
	include('includes/include.php');
	if(!defined('HC_Version')){
		exit('<br /><br />This file must be run from the Helios /events directory.');
	}//end if
	$curVersion = "1.1.2";	?>
<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta name="robots" content="noindex, nofollow" />
	<meta name="GOOGLEBOT" content="noindex, nofollow" />

	<meta http-equiv="author" content="Refresh Web Development LLC" />
	<meta http-equiv="copyright" content="2004-<?echo date("Y");?> All Rights Reserved" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta http-equiv="expires" content="0" />
	<meta name="MSSmartTagsPreventParsing" content="yes" />
	
	<link rel="bookmark" title="<?echo CalName;?>" href="<?echo CalRoot;?>" />
	<link rel="stylesheet" type="text/css" href="<?echo CalRoot;?>/css/helios.css" />
	<link rel="icon" href="<?echo CalRoot;?>/images/favicon.png" type="image/png" />
	
	<meta name="generator" content="Helios Calendar <?echo HC_Version;?>" /> <!-- leave this for stats -->
	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function chkFrm(){
		if(document.frm.uID.value == 0){
			alert('Please select the upgrade you wish to run.');
			return false;
		} else {
			if(document.frm.uID.value == 1){
				if(confirm('This will upgrade your 1.0.x version to <?echo $curVersion;?>\n\nAre you sure you want to perform the upgrade\n\n          Ok = YES, Run Upgrade\n          Cancel = NO, Do NOT Run Upgrade')){
					return true;
				} else {
					return false;
				}//end if
			}//end if
			
			if(document.frm.uID.value == 2){
				if(confirm('This will upgrade your 1.1 version to <?echo $curVersion;?>\n\nAre you sure you want to perform the upgrade\n\n          Ok = YES, Run Upgrade\n          Cancel = NO, Do NOT Run Upgrade')){
					return true;
				} else {
					return false;
				}//end if
			}//end if
		}//end if
		return true;
	}//end if
	//-->
	</script>
	<title>Helios Calendar Upgrade</title>
	<link rel="stylesheet" type="text/css" href="<?echo CalRoot;?>/template/style.css" />
</head>

<body>
<br />
<div id="container">
	<div id="content">
	
		<div style="padding:10px;"?>
		<?	if(!isset($_POST['uID'])){	?>
				Welcome to the Helios upgrade. To begin please be sure that your Helios <b>MySQL user has ALTER &amp; Create permissions</b> for your Helios database.
				<br /><br />
				Select the upgrade you wish to run from the list below and click "Run Upgrade". The neccessary
				database commands to update your Helios install will be run.
				<br /><br />
				To complete the upgrade be sure you have uploaded to your site all new files for the version you are upgrading to.
				<br /><br />
				<div style="border: solid 1px #666666;background: #EFEFEF;padding: 10px;">
					Be sure you select the proper current version (left number) repeating upgrades may break parts of your Helios install.
				</div>
				<br />
				<form name="frm" id="frm" method="post" action="upgrade.php" onsubmit="return chkFrm();">
					<select name="uID" id="uID">
						<option value="0">Select Upgrade to Run</option>
						<option value="2">1.1 or 1.1.1&nbsp;&nbsp;-->&nbsp;&nbsp;<?echo $curVersion;?></option>
						<option value="1">1.0.x&nbsp;&nbsp;-->&nbsp;&nbsp;<?echo $curVersion;?></option>
					</select>
					<br /><br />
					<input name="submit" id="submit" type="submit" value=" Run Upgrade " class="button" />
				</form>
		<?	} else {
				$status = 0;
				switch($_POST['uID']){
					
					case 1:	
						echo "<b>Upgrading from 1.0.x to " . $curVersion . "</b>";
						echo "<br /><br />Checking for Mini-Cal Start Day Setting...";
						$result = doQuery("SELECT * FROM " . HC_TblPrefix . "settings WHERE PkID = 22");
						if(hasRows($result)){
							$status = 1;
							echo "<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Start Day Setting Found";
						} else {
							echo "<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Adding Start Day Setting";
							doQuery("INSERT INTO "  . HC_TblPrefix . "settings(PkID, SettingValue) VALUES(22,0)");
							$result = doQuery("SELECT * FROM " . HC_TblPrefix . "settings WHERE PkID = 22");
							if(hasRows($result)){
								echo "...Add Successful";
							} else {
								$status = 2;
								echo "...Add Failed";
							}//end if
						}//end if
						
						echo "<br /><br />Checking for Time Format Setting...";
						$result = doQuery("SELECT * FROM " . HC_TblPrefix . "settings WHERE PkID = 23");
						if(hasRows($result)){
							$status = 1;
							echo "<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Time Format Setting Found";
						} else {
							echo "<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Adding Time Format Setting";
							doQuery("INSERT INTO "  . HC_TblPrefix . "settings(PkID, SettingValue) VALUES(23,'h:i A')");
							$result = doQuery("SELECT * FROM " . HC_TblPrefix . "settings WHERE PkID = 23");
							if(hasRows($result)){
								echo "...Add Successful";
							} else {
								$status = 2;
								echo "...Add Failed";
							}//end if
						}//end if
						
						
						echo "<br /><br />Checking for Select Date Format...";
						$result = doQuery("SELECT * FROM " . HC_TblPrefix . "settings WHERE PkID = 24");
						if(hasRows($result)){
							$status = 1;
							echo "<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Select Date Setting Found";
						} else {
							echo "<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Adding Select Date Setting";
							doQuery("INSERT INTO "  . HC_TblPrefix . "settings(PkID, SettingValue) VALUES(24,'m/d/Y')");
							$result = doQuery("SELECT * FROM " . HC_TblPrefix . "settings WHERE PkID = 24");
							if(hasRows($result)){
								echo "...Add Succesful";
							} else {
								$status = 2;
								echo "...Add Failed";
							}//end if
						}//end if
						
						
						echo "<br /><br />Altering Password Field...";
						doQuery("ALTER TABLE " . HC_TblPrefix . "admin CHANGE Passwrd Passwrd VARCHAR(32)");
						echo "<br />Testing New Field...";
						doQuery("INSERT INTO " . HC_TblPrefix . "admin(Email,Passwrd) Values('helios_upgrade', 'abcdefghijklmnopqrstuvwxyz123456')");
						$result = doQuery("SELECT Passwrd FROM " . HC_TblPrefix . "admin WHERE Email = 'helios_upgrade'");
						if(hasRows($result)){
							if(mysql_result($result,0,0) == 'abcdefghijklmnopqrstuvwxyz123456'){
								echo "...Field Alter Successful";
							} else {
								$status = 2;
								echo "...Field Alter Failed";
							}//end if
						} else {
							$status = 2;
							echo "...Test Failed (Could Not Insert)";
						}//end if
						doQuery("DELETE FROM " . HC_TblPrefix . "admin WHERE Email = 'helios_upgrade'");
						
						
						echo "<br /><br />Updating Phone Number Fields...";
						echo "<br />...Altering " . HC_TblPrefix . "events...";
						doQuery("ALTER TABLE " . HC_TblPrefix . "events CHANGE ContactPhone ContactPhone VARCHAR(25)");
						echo "Testing New Field";
						doQuery("INSERT INTO " . HC_TblPrefix . "events(Title,ContactPhone) Values('helios_upgrade', 'abcdefghijklmnopqrstuvwxy')");
						$result = doQuery("SELECT ContactPhone FROM " . HC_TblPrefix . "events WHERE Title = 'helios_upgrade'");
						if(hasRows($result)){
							if(mysql_result($result,0,0) == 'abcdefghijklmnopqrstuvwxy'){
								echo "...Successful";
							} else {
								$status = 2;
								echo "...Failed";
							}//end if
						} else {
							$status = 2;
							echo "...Failed (Could Not Insert)";
						}//end if
						doQuery("DELETE FROM " . HC_TblPrefix . "events WHERE Title = 'helios_upgrade'");
						
						echo "<br />...Altering " . HC_TblPrefix . "registrants...";
						doQuery("ALTER TABLE " . HC_TblPrefix . "registrants CHANGE Phone Phone VARCHAR(25)");
						echo "Testing New Field";
						doQuery("INSERT INTO " . HC_TblPrefix . "registrants(Name,Phone) Values('helios_upgrade', 'abcdefghijklmnopqrstuvwxy')");
						$result = doQuery("SELECT Phone FROM " . HC_TblPrefix . "registrants WHERE Name = 'helios_upgrade'");
						if(hasRows($result)){
							if(mysql_result($result,0,0) == 'abcdefghijklmnopqrstuvwxy'){
								echo "...Successful";
							} else {
								$status = 2;
								echo "...Failed";
							}//end if
						} else {
							$status = 2;
							echo "...Failed (Could Not Insert)";
						}//end if
						doQuery("DELETE FROM " . HC_TblPrefix . "registrants WHERE Name = 'helios_upgrade'");
						
						
						echo "<br /><br />Encrypting Passwords...";
						$result = doQuery("SELECT PkID, Email, Passwrd FROM " . HC_TblPrefix . "admin");
						if(hasRows($result)){
							while($row = mysql_fetch_row($result)){
								doQuery("UPDATE " . HC_TblPrefix . "admin SET Passwrd = '" . md5(md5($row[2]) . $row[1]) . "' WHERE PkID = " . $row[0]);
							}//end while
						} else {
							echo "No Admin Accounts Found";
						}//end if
						
						echo "<br /><br />Adding Field to Admin Table...";
						doQuery("ALTER TABLE "  . HC_TblPrefix . "admin ADD PCKey VARCHAR(32)");
						
					case 2:
						if($_POST['uID'] == 2){
							echo "<b>Upgrading from 1.1 or 1.1.1 to " . $curVersion . "</b><br /><br />";
						} else {
							echo "<br /><br />";
						}//end if
						
						
						echo "Adding Admin Tool Permissions";
						doQuery("ALTER TABLE " . HC_TblPrefix . "adminpermissions ADD Tools INT(3)  UNSIGNED DEFAULT \"0\" NOT NULL AFTER Settings");
						doQuery("UPDATE " . HC_TblPrefix . "adminpermissions SET Tools= 1");
						$result = doQuery("SELECT * FROM " . HC_TblPrefix . "adminpermissions LIMIT 1");
						if(hasRows($result)){
							echo "...Testing";
							if(mysql_result($result,0,"Tools") == 1){
								echo "...Setting Add Successful";
							} else {
								$status = 2;
								echo "...Setting Add Failed";
							}//end if
						} else {
							$status = 1;
							echo "...No Admin Accounts Present, Unable to Test Upgrade";
						}//end if
						
						
						echo "<br /><br />Adding Event Submit Alert Setting";
						$result = doQuery("SELECT * FROM " . HC_TblPrefix . "settings WHERE PkID = 25");
						if(hasRows($result)){
							$status = 1;
							echo "...Setting Found, Not Upgrading";
						} else {
							doQuery("INSERT INTO "  . HC_TblPrefix . "settings(PkID, SettingValue) VALUES(25,0)");
							echo "...Testing";
							$result = doQuery("SELECT * FROM " . HC_TblPrefix . "settings WHERE PkID = 25");
							if(hasRows($result)){
								echo "...Add Succesful";
							} else {
								$status = 2;
								echo "...Add Failed";
							}//end if
						}//end if
						
						
						echo "<br /><br />Adding Location ID...";
						doQuery("ALTER TABLE "  . HC_TblPrefix . "events ADD LocID INT(11)  UNSIGNED DEFAULT \"0\" NOT NULL");
						echo "Testing";
						doQuery("INSERT INTO " . HC_TblPrefix . "events(Title,LocID) Values('helios_upgrade', 99)");
						$result = doQuery("SELECT LocID FROM " . HC_TblPrefix . "events WHERE Title = 'helios_upgrade'");
						if(hasRows($result)){
							if(mysql_result($result,0,0) == '99'){
								echo "...Field Add Successful";
							} else {
								$status = 2;
								echo "...Field Add Failed";
							}//end if
						} else {
							$status = 2;
							echo "...Test Failed (Could Not Insert Data)";
						}//end if
						doQuery("DELETE FROM " . HC_TblPrefix . "events WHERE Title = 'helios_upgrade'");
						
						
						echo "<br /><br />Altering Login History Field...";
						doQuery("ALTER TABLE "  . HC_TblPrefix . "adminloginhistory CHANGE Client Client LONGTEXT");
						echo "Testing";
						doQuery("INSERT INTO " . HC_TblPrefix . "adminloginhistory(Client) VALUE('12345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901')");
						$result = doQuery("SELECT * FROM " . HC_TblPrefix . "adminloginhistory WHERE Client = '12345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901'");
						if(hasRows($result)){
							echo "...Alter Successful";
						} else {
							$status = 2;
							echo "...Alter Failed";
						}//end if
						doQuery("DELETE FROM " . HC_TblPrefix . "adminloginhistory WHERE Client = '12345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901'");
						
						
						echo "<br /><br />Adding Location Table...";
						doQuery("CREATE TABLE " . HC_TblPrefix . "locations (
								  PkID int(11) unsigned NOT NULL auto_increment,
								  Name varchar(100) ,
								  Address varchar(75) ,
								  Address2 varchar(75) ,
								  City varchar(50) ,
								  State varchar(30) ,
								  Zip varchar(15) ,
								  URL varchar(100) ,
								  Phone varchar(25) ,
								  Email varchar(75) ,
								  Descript longtext ,
								  IsPublic tinyint(3) unsigned NOT NULL DEFAULT '0' ,
								  IsActive tinyint(3) unsigned NOT NULL DEFAULT '0' ,
								  URLClicks int(11) unsigned NOT NULL DEFAULT '0' ,
								  PRIMARY KEY (PkID),
								  UNIQUE KEY PkID (PkID)
								)");
						echo "Testing";
						doQuery("INSERT INTO " . HC_TblPrefix . "locations(Name) VALUE('helios_upgrade')");
						$result = doQuery("SELECT * FROM " . HC_TblPrefix . "locations WHERE NAME = 'helios_upgrade'");
						if(hasRows($result)){
							echo "...Table Add Successful";
						} else {
							$status = 2;
							echo "...Table Add Failed";
						}//end if
						doQuery("DELETE FROM " . HC_TblPrefix . "locations WHERE NAME = 'helios_upgrade'");
				?>		<br /><br />
						<fieldset style="padding:10px;">
							<legend>Upgrade Results</legend>
					<?	switch($status){
							case 0:	?>
								Upgrade successful.
							<?	break;
							case 1:	?>
								One or more of the upgrads was only partialy completed.<br /><br />
								While performance should not be affected you will need to review the output above for the upgrade
								not completed successfully and test it to verify.
							<?	break;
							case 2:	?>
								One or more of the upgrades failed. This is most commonly caused by improper configuration of
								your MySQL user. Please verify your settings and try again. If you continue to have difficulty please
								access the Helios forums, or email support@helios.com for assistance.
					<?	}//end switch	?>
						</fieldset>
				<?	break;
				}//end switch
			}//end if	?>
			<br /><br />
			<b>NOTICE:</b> Delete this file before continuing use of your Helios Calendar.
		</div>
	</div>
	<div id="controls">
		<img src="<?echo CalAdminRoot;?>/images/logo.gif" width="200" height="50" alt="" border="0">
	</div>
	<div id="copyright">
		<a href="http://www.helioscalendar.com" class="copyright">Helios Calendar</a> Copyright 2004-<?echo date("Y");?> <a href="http://www.refreshwebdev.com" class="copyrightR">Refresh Web Development</a>
	</div>
</div>

</body>
</html>