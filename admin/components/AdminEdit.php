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
	include($hc_langPath . $_SESSION['LangSet'] . '/admin/admin.php');
	
	if (isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(2, $hc_lang_admin['Feed01']);
				break;
				
			case "2" :
				feedback(2, $hc_lang_admin['Feed02']);
				break;
		}//end switch
	}//end if
	
	if(isset($_GET['uID']) && is_numeric($_GET['uID'])){
		appInstructions(0, "Editing_Admin_Users", $hc_lang_admin['TitleEditA'], $hc_lang_admin['InstructEditA']);
		$uID = $_GET['uID'];
	} else {
		appInstructions(0, "Adding_Admin_Users", $hc_lang_admin['TitleAddA'], $hc_lang_admin['InstructAddA']);
		$uID = 0;
		
		$firstname = "";
		if(isset($_GET['firstname'])){
			$firstname = $_GET['firstname'];
		}//end if
		
		$lastname = "";
		if(isset($_GET['lastname'])){
			$lastname = $_GET['lastname'];
		}//end if
		
		$email = "";
		if(isset($_GET['email'])){
			$email = $_GET['email'];
		}//end if
	}//end if
	
	$result = doQuery("	SELECT * FROM " . HC_TblPrefix . "admin
							LEFT JOIN " . HC_TblPrefix . "adminpermissions ON (" . HC_TblPrefix . "admin.PkID = " . HC_TblPrefix . "adminpermissions.AdminID)
						WHERE " . HC_TblPrefix  . "admin.PkID = " . cIn($uID));
	$oldEmail = "";
	$editEvent = 0;
	$eventPending = 0;
	$eventCategory = 0;
	$userEdit = 0;
	$adminEdit = 0;
	$newsletter = 0;
	$settings = 0;
	$tools = 0;
	$reports = 0;
	$locEdit = 0;
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
		$tools = cOut(mysql_result($result,0,19));
		$reports = cOut(mysql_result($result,0,20));
		$locEdit = cOut(mysql_result($result,0,21));
	}//end if	?>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Email.js"></script>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function chkFrm(){
	dirty = 0;
	warn = "<?php echo $hc_lang_admin['Valid01'];?>";
		
		if(document.frm.firstname.value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_admin['Valid02'];?>';
		}//end if
		
		if(document.frm.lastname.value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_admin['Valid03'];?>';
		}//end if
		
		if(document.frm.email.value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_admin['Valid04'];?>';
		}//end if
		
		if(document.frm.email.value != '' && chkEmail(document.frm.email) == 0){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_admin['Valid05'];?>';
		}//end if
		
		if(dirty > 0){
			alert(warn + '\n\n<?php echo $hc_lang_admin['Valid06'];?>');
			return false;
		} else {
			return true;
		}//end if
	}//end chkFrm
	//-->
	</script>
	<div style="width:375px;">
	<form name="frm" id="frm" method="post" action="<?php echo CalAdminRoot . "/components/AdminEditAction.php";?>" onsubmit="return chkFrm();">
	<input type="hidden" name="uID" id="uID" value="<?php echo $uID;?>" />
	<input type="hidden" name="oldEmail" id="oldEmail" value="<?php echo $oldEmail;?>" />
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_admin['Details'];?></legend>
		<div class="frmOpt">
			<label for="firstname"><?php echo $hc_lang_admin['FName'];?></label>
			<input name="firstname" id="firstname" type="text" size="20" maxlength="50" value="<?php echo $firstname;?>" />
		</div>
		<div class="frmOpt">
			<label for="lastname"><?php echo $hc_lang_admin['LName'];?></label>
			<input name="lastname" id="lastname" type="text" size="20" maxlength="50" value="<?php echo $lastname;?>" />
		</div>
		<div class="frmOpt">
			<label for="email"><?php echo $hc_lang_admin['Email'];?></label>
			<input name="email" id="email" type="text" size="30" maxlength="100" value="<?php echo $email;?>" />
		</div>
<?php 	if($uID == 0){	?>
		<div class="frmOpt">
			<label><?php echo $hc_lang_admin['Password'];?></label>
			<?php echo $hc_lang_admin['CreatedBy'];?>
		</div>
<?php 	}//end if	?>
	</fieldset>
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_admin['Permissions'];?></legend>
<?php 	if($_SESSION['AdminPkID'] != $uID){	?>
		<div class="frmOpt">
			<label class="adminEditLabel"><?php echo $hc_lang_admin['EventEdit:'];?></label>
			<label for="editEventAllow" class="adminEditRadio"><input <?php if($editEvent == 1){echo "checked=\"checked\"";}//end if?> type="radio" name="editEvent" id="editEventAllow" value="1" class="noBorderIE" /><?php echo $hc_lang_admin['Allow'];?></label>
			<label for="editEventLocked" class="adminEditRadio"><input <?php if($editEvent == 0){echo "checked=\"checked\"";}//end if?> type="radio" name="editEvent" id="editEventLocked" value="0" class="noBorderIE" /><?php echo $hc_lang_admin['Locked'];?></label>
			&nbsp;<?php appInstructionsIcon($hc_lang_admin['EventEdit'], $hc_lang_admin['EventEditL']); ?>
		</div>
		<div class="frmOpt">
			<label class="adminEditLabel"><?php echo $hc_lang_admin['EventApp:'];?></label>
			<label for="eventPendingAllow" class="adminEditRadio"><input <?php if($eventPending == 1){echo "checked=\"checked\"";}//end if?> type="radio" name="eventPending" id="eventPendingAllow" value="1" class="noBorderIE" /><?php echo $hc_lang_admin['Allow'];?></label>
			<label for="eventPendingLocked" class="adminEditRadio"><input <?php if($eventPending == 0){echo "checked=\"checked\"";}//end if?> type="radio" name="eventPending" id="eventPendingLocked" value="0" class="noBorderIE" /><?php echo $hc_lang_admin['Locked'];?></label>
			&nbsp;<?php appInstructionsIcon($hc_lang_admin['EventApp'], $hc_lang_admin['EventAppL']); ?>
		</div>
		<div class="frmOpt">
			<label class="adminEditLabel"><?php echo $hc_lang_admin['Category:'];?></label>
			<label for="eventCategoryAllow" class="adminEditRadio"><input <?php if($eventCategory == 1){echo "checked=\"checked\"";}//end if?> type="radio" name="eventCategory" id="eventCategoryAllow" value="1" class="noBorderIE" /><?php echo $hc_lang_admin['Allow'];?></label>
			<label for="eventCategoryLocked" class="adminEditRadio"><input <?php if($eventCategory == 0){echo "checked=\"checked\"";}//end if?> type="radio" name="eventCategory" id="eventCategoryLocked" value="0" class="noBorderIE" /><?php echo $hc_lang_admin['Locked'];?></label>
			&nbsp;<?php appInstructionsIcon($hc_lang_admin['Category'], $hc_lang_admin['CategoryL']); ?>
		</div>
		<div class="frmOpt">
			<label class="adminEditLabel"><?php echo $hc_lang_admin['LocEdit:'];?></label>
			<label for="locEditAllow" class="adminEditRadio"><input <?php if($locEdit == 1){echo "checked=\"checked\"";}//end if?> type="radio" name="editLoc" id="locEditAllow" value="1" class="noBorderIE" /><?php echo $hc_lang_admin['Allow'];?></label>
			<label for="locEditLocked" class="adminEditRadio"><input <?php if($locEdit == 0){echo "checked=\"checked\"";}//end if?> type="radio" name="editLoc" id="locEditLocked" value="0" class="noBorderIE" /><?php echo $hc_lang_admin['Locked'];?></label>
			&nbsp;<?php appInstructionsIcon($hc_lang_admin['LocEdit'], $hc_lang_admin['LocEditL']); ?>
		</div>
		<div class="frmOpt">
			<label class="adminEditLabel"><?php echo $hc_lang_admin['RecEdit:'];?></label>
			<label for="userEditAllow" class="adminEditRadio"><input <?php if($userEdit == 1){echo "checked=\"checked\"";}//end if?> type="radio" name="userEdit" id="userEditAllow" value="1" class="noBorderIE" /><?php echo $hc_lang_admin['Allow'];?></label>
			<label for="userEditLocked" class="adminEditRadio"><input <?php if($userEdit == 0){echo "checked=\"checked\"";}//end if?> type="radio" name="userEdit" id="userEditLocked" value="0" class="noBorderIE" /><?php echo $hc_lang_admin['Locked'];?></label>
			&nbsp;<?php appInstructionsIcon($hc_lang_admin['RecEdit'], $hc_lang_admin['RecEditL']); ?>
		</div>
		<div class="frmOpt">
			<label class="adminEditLabel"><?php echo $hc_lang_admin['AdminEdit:'];?></label>
			<label for="adminEditAllow" class="adminEditRadio"><input <?php if($adminEdit == 1){echo "checked=\"checked\"";}//end if?> type="radio" name="adminEdit" id="adminEditAllow" value="1" class="noBorderIE" /><?php echo $hc_lang_admin['Allow'];?></label>
			<label for="adminEditLocked" class="adminEditRadio"><input <?php if($adminEdit == 0){echo "checked=\"checked\"";}//end if?> type="radio" name="adminEdit" id="adminEditLocked" value="0" class="noBorderIE" /><?php echo $hc_lang_admin['Locked'];?></label>
			&nbsp;<?php appInstructionsIcon($hc_lang_admin['AdminEdit'], $hc_lang_admin['AdminEditL']); ?>
		</div>
		<div class="frmOpt">
			<label class="adminEditLabel"><?php echo $hc_lang_admin['EventNews:'];?></label>
			<label for="newsletterAllow" class="adminEditRadio"><input <?php if($newsletter == 1){echo "checked=\"checked\"";}//end if?> type="radio" name="newsletter" id="newsletterAllow" value="1" class="noBorderIE" /><?php echo $hc_lang_admin['Allow'];?></label>
			<label for="newsletterLocked" class="adminEditRadio"><input <?php if($newsletter == 0){echo "checked=\"checked\"";}//end if?> type="radio" name="newsletter" id="newsletterLocked" value="0" class="noBorderIE" /><?php echo $hc_lang_admin['Locked'];?></label>
			&nbsp;<?php appInstructionsIcon($hc_lang_admin['EventNews'], $hc_lang_admin['EventNewsL']); ?>
		</div>
		<div class="frmOpt">
			<label class="adminEditLabel"><?php echo $hc_lang_admin['Settings:'];?></label>
			<label for="settingsAllow" class="adminEditRadio"><input <?php if($settings == 1){echo "checked=\"checked\"";}//end if?> type="radio" name="settings" id="settingsAllow" value="1" class="noBorderIE" /><?php echo $hc_lang_admin['Allow'];?></label>
			<label for="settingsLocked" class="adminEditRadio"><input <?php if($settings == 0){echo "checked=\"checked\"";}//end if?> type="radio" name="settings" id="settingsLocked" value="0" class="noBorderIE" /><?php echo $hc_lang_admin['Locked'];?></label>
			&nbsp;<?php appInstructionsIcon($hc_lang_admin['Settings'], $hc_lang_admin['SettingsL']); ?>
		</div>
		<div class="frmOpt">
			<label class="adminEditLabel"><?php echo $hc_lang_admin['Tools:'];?></label>
			<label for="toolsAllow" class="adminEditRadio"><input <?php if($tools == 1){echo "checked=\"checked\"";}//end if?> type="radio" name="tools" id="toolsAllow" value="1" class="noBorderIE" /><?php echo $hc_lang_admin['Allow'];?></label>
			<label for="toolsLocked" class="adminEditRadio"><input <?php if($tools == 0){echo "checked=\"checked\"";}//end if?> type="radio" name="tools" id="toolsLocked" value="0" class="noBorderIE" /><?php echo $hc_lang_admin['Locked'];?></label>
			&nbsp;<?php appInstructionsIcon($hc_lang_admin['Tools'], $hc_lang_admin['ToolsL']); ?>
		</div>
		<div class="frmOpt">
			<label class="adminEditLabel"><?php echo $hc_lang_admin['Reports:'];?></label>
			<label for="reportsAllow" class="adminEditRadio"><input <?php if($reports == 1){echo "checked=\"checked\"";}//end if?> type="radio" name="reports" id="reportsAllow" value="1" class="noBorderIE" /><?php echo $hc_lang_admin['Allow'];?></label>
			<label for="reportsLocked" class="adminEditRadio"><input <?php if($reports == 0){echo "checked=\"checked\"";}//end if?> type="radio" name="reports" id="reportsLocked" value="0" class="noBorderIE" /><?php echo $hc_lang_admin['Locked'];?></label>
			&nbsp;<?php appInstructionsIcon($hc_lang_admin['Reports'], $hc_lang_admin['ReportsL']); ?>
		</div>
<?php 	} else {
			echo "<div class=\"frmOpt\">";
			echo $hc_lang_admin['NoEdit'];
			echo "</div>";
		}//end if	?>
	</fieldset>
	<br />
	<input type="submit" name="submit" id="submit" value=" <?php echo $hc_lang_admin['Save'];?> " class="button" />
	</form>
	</div>