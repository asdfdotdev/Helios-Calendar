<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2006 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	if(isset($_GET['uID']) && is_numeric($_GET['uID'])){
		$uID = $_GET['uID'];
	} else {
		$uID = 0;
		
		if(isset($_GET['firstname'])){
			$firstname = $_GET['firstname'];
		} else {
			$firstname = "";
		}//end if
		
		if(isset($_GET['lastname'])){
			$lastname = $_GET['lastname'];
		} else {
			$lastname = "";
		}//end if
		
		if(isset($_GET['email'])){
			$email = $_GET['email'];
		} else {
			$email = "";
		}//end if
		
	}//end if	?>
	<script language="JavaScript" type="text/JavaScript" src="<?echo CalRoot;?>/includes/java/Email.js"></script>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function chkFrm(){
	dirty = 0;
	warn = "Administrator could not be added because of the following reasons:\n";
		
		if(document.frm.firstname.value == ''){
			dirty = 1;
			warn = warn + '\n*First Name is Required';
		}//end if
		
		if(document.frm.lastname.value == ''){
			dirty = 1;
			warn = warn + '\n*Last Name is Required';
		}//end if
		
		if(document.frm.email.value == ''){
			dirty = 1;
			warn = warn + '\n*Email is Required';
		}//end if
		
		if(document.frm.email.value != '' && chkEmail(document.frm.email) == 0){
			dirty = 1;
			warn = warn + '\n*Email Format is Invalid';
		}//end if
		
		if(dirty > 0){
			alert(warn + '\n\nPlease make the required changes and try again.');
			return false;
		} else {
			return true;
		}//end if
	}//end chkFrm
	//-->
	</script>

<?	if (isset($_GET['msg'])){
		switch ($_GET['msg']){
				
			case "1" :
				feedback(2, "Email Address Already Exists.<br />All Updates But Email Address Made Successfully.");
				break;
				
			case "2" :
				feedback(2, "Email Address Already Exists.<br />Admin Not Added");
				break;
				
		}//end switch
	}//end if
	
	if($uID == 0){
		appInstructions(0, "Add_Administrator", "Add Admin Account", "Use the form below to <b>add</b> an admin account.<br />The admin will receive an email informing them their account has been created.");
	} else {
		appInstructions(0, "Edit_Administrator", "Edit Admin Account", "Use the form below to <b>edit</b> the admin account.<br />If the admin needs a new password have them use the \"Lost Your Password?\" link at the login screen to reset it.");
	}//end if	
		
	$result = doQuery("	SELECT * FROM " . HC_TblPrefix . "admin
							LEFT JOIN " . HC_TblPrefix . "adminpermissions ON (" . HC_TblPrefix . "admin.PkID = " . HC_TblPrefix . "adminpermissions.AdminID)
						WHERE " . HC_TblPrefix  . "admin.PkID = " . cIn($uID));
	
	if(hasRows($result)){
		$firstname = cOut(mysql_result($result,0,1));
		$lastname = cOut(mysql_result($result,0,2));
		$email = cOut(mysql_result($result,0,3));
		$oldEmail = cOut(mysql_result($result,0,3));
		$password = cOut(mysql_result($result,0,4));
		$editEvent = cOut(mysql_result($result,0,12));
		$eventPending = cOut(mysql_result($result,0,13));
		$eventCategory = cOut(mysql_result($result,0,14));
		$userEdit = cOut(mysql_result($result,0,15));
		$adminEdit = cOut(mysql_result($result,0,16));
		$newsletter = cOut(mysql_result($result,0,17));
		$settings = cOut(mysql_result($result,0,18));
		$reports = cOut(mysql_result($result,0,19));
		
	} else {
		$oldEmail = "";
		$editEvent = 0;
		$eventPending = 0;
		$eventCategory = 0;
		$userEdit = 0;
		$adminEdit = 0;
		$newsletter = 0;
		$settings = 0;
		$reports = 0;
		
	}//end if	?>
	<div style="width:375px;">
	<form name="frm" id="frm" method="post" action="<?echo CalAdminRoot . "/" . HC_AdminEditAction;?>" onsubmit="return chkFrm();">
	<input type="hidden" name="uID" id="uID" value="<?echo $uID;?>" />
	<input type="hidden" name="oldEmail" id="oldEmail" value="<?echo $oldEmail;?>" />
	<br />
	<fieldset>
		<legend>Admin Details</legend>
		<div class="frmOpt">
			<label for="firstname">First Name:</label>
			<input name="firstname" id="firstname" type="text" size="20" maxlength="50" value="<?echo $firstname;?>" />
		</div>
		<div class="frmOpt">
			<label for="lastname">Last Name:</label>
			<input name="lastname" id="lastname" type="text" size="20" maxlength="50" value="<?echo $lastname;?>" />
		</div>
		<div class="frmOpt">
			<label for="email">Email:</label>
			<input name="email" id="email" type="text" size="30" maxlength="100" value="<?echo $email;?>" />
		</div>
	<?	if($uID == 0){	?>
		<div class="frmOpt">
			<label for="password">Password:</label>
			Created by Admin
		</div>
	<?	}//end if	?>
	</fieldset>
	<br />
	<fieldset>
		<legend>Account Permissions</legend>
	<?	if($_SESSION['AdminPkID'] != $uID){	?>
		<div class="frmOpt">
			<label class="adminEditLabel">Event Edit:</label>
			<label for="editEventAllow" class="adminEditRadio"><input <?if($editEvent == 1){echo "checked=\"checked\"";}//end if?> type="radio" name="editEvent" id="editEventAllow" value="1" class="noBorderIE" />Allow</label>
			<label for="editEventLocked" class="adminEditRadio"><input <?if($editEvent == 0){echo "checked=\"checked\"";}//end if?> type="radio" name="editEvent" id="editEventLocked" value="0" class="noBorderIE" />Locked</label>
			&nbsp;<? appInstructionsIcon("Event Edit", "Allows Administrator to Add, Edit and Delete Events. Add/Remove Events to the Billboard and manage Orphan Events."); ?>
		</div>
		<div class="frmOpt">
			<label class="adminEditLabel">Event Approval:</label>
			<label for="eventPendingAllow" class="adminEditRadio"><input <?if($eventPending == 1){echo "checked=\"checked\"";}//end if?> type="radio" name="eventPending" id="eventPendingAllow" value="1" class="noBorderIE" />Allow</label>
			<label for="eventPendingLocked" class="adminEditRadio"><input <?if($eventPending == 0){echo "checked=\"checked\"";}//end if?> type="radio" name="eventPending" id="eventPendingLocked" value="0" class="noBorderIE" />Locked</label>
			&nbsp;<? appInstructionsIcon("Event Approval", "Allows Administrator to Approve/Decline Events in the Pending Event Queue."); ?>
		</div>
		<div class="frmOpt">
			<label class="adminEditLabel">Category Edit:</label>
			<label for="eventCategoryAllow" class="adminEditRadio"><input <?if($eventCategory == 1){echo "checked=\"checked\"";}//end if?> type="radio" name="eventCategory" id="eventCategoryAllow" value="1" class="noBorderIE" />Allow</label>
			<label for="eventCategoryLocked" class="adminEditRadio"><input <?if($eventCategory == 0){echo "checked=\"checked\"";}//end if?> type="radio" name="eventCategory" id="eventCategoryLocked" value="0" class="noBorderIE" />Locked</label>
			&nbsp;<? appInstructionsIcon("Category Edit", "Allows Administrator to Add, Edit and Delete Event Categories."); ?>
		</div>
		<div class="frmOpt">
			<label class="adminEditLabel">Recipient Edit:</label>
			<label for="userEditAllow" class="adminEditRadio"><input <?if($userEdit == 1){echo "checked=\"checked\"";}//end if?> type="radio" name="userEdit" id="userEditAllow" value="1" class="noBorderIE" />Allow</label>
			<label for="userEditLocked" class="adminEditRadio"><input <?if($userEdit == 0){echo "checked=\"checked\"";}//end if?> type="radio" name="userEdit" id="userEditLocked" value="0" class="noBorderIE" />Locked</label>
			&nbsp;<? appInstructionsIcon("Alert Recipient Edit", "Allows Administrator to Add, Edit and Delete Alert Recipients."); ?>
		</div>
		<div class="frmOpt">
			<label class="adminEditLabel">Admin Edit:</label>
			<label for="adminEditAllow" class="adminEditRadio"><input <?if($adminEdit == 1){echo "checked=\"checked\"";}//end if?> type="radio" name="adminEdit" id="adminEditAllow" value="1" class="noBorderIE" />Allow</label>
			<label for="adminEditLocked" class="adminEditRadio"><input <?if($adminEdit == 0){echo "checked=\"checked\"";}//end if?> type="radio" name="adminEdit" id="adminEditLocked" value="0" class="noBorderIE" />Locked</label>
			&nbsp;<? appInstructionsIcon("Admin Edit", "Allows Administrator to Add, Edit and Delete Admin Accounts."); ?>
		</div>
		<div class="frmOpt">
			<label class="adminEditLabel">Newsletter Edit:</label>
			<label for="newsletterAllow" class="adminEditRadio"><input <?if($newsletter == 1){echo "checked=\"checked\"";}//end if?> type="radio" name="newsletter" id="newsletterAllow" value="1" class="noBorderIE" />Allow</label>
			<label for="newsletterLocked" class="adminEditRadio"><input <?if($newsletter == 0){echo "checked=\"checked\"";}//end if?> type="radio" name="newsletter" id="newsletterLocked" value="0" class="noBorderIE" />Locked</label>
			&nbsp;<? appInstructionsIcon("Event Newsletter", "Allows Administrator to Create and Send Event Newsletters."); ?>
		</div>
		<div class="frmOpt">
			<label class="adminEditLabel">Settings:</label>
			<label for="settingsAllow" class="adminEditRadio"><input <?if($settings == 1){echo "checked=\"checked\"";}//end if?> type="radio" name="settings" id="settingsAllow" value="1" class="noBorderIE" />Allow</label>
			<label for="settingsLocked" class="adminEditRadio"><input <?if($settings == 0){echo "checked=\"checked\"";}//end if?> type="radio" name="settings" id="settingsLocked" value="0" class="noBorderIE" />Locked</label>
			&nbsp;<? appInstructionsIcon("Settings", "Allows Administrator to Modify Calendar Settings."); ?>
		</div>
		<div class="frmOpt">
			<label class="adminEditLabel">Reports:</label>
			<label for="reportsAllow" class="adminEditRadio"><input <?if($reports == 1){echo "checked=\"checked\"";}//end if?> type="radio" name="reports" id="reportsAllow" value="1" class="noBorderIE" />Allow</label>
			<label for="reportsLocked" class="adminEditRadio"><input <?if($reports == 0){echo "checked=\"checked\"";}//end if?> type="radio" name="reports" id="reportsLocked" value="0" class="noBorderIE" />Locked</label>
			&nbsp;<? appInstructionsIcon("Reports", "Allows Administrator access to event activity reports."); ?>
		</div>
	<?	} else {	?>
		<div class="frmOpt">
			You cannot change access settings for your account.
		</div>
	<?	}//end if	?>
	</fieldset>
	
	<br />
	<input type="submit" name="submit" id="submit" value="  Save Admin  " class="button" />
	</form>
	</div>