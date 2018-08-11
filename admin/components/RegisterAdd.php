<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2006 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	if(isset($_GET['rID']) && is_numeric($_GET['rID'])){
		$rID = $_GET['rID'];
	} else {
		$rID = 0;
	}//end if
	
	if (isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(2,"Email Address Already Exists For This Event.<br />All Change But Email Address Saved.");
				break;
				
		}//end switch
	}//end if	
	
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "registrants WHERE PkID = " . cIn($rID));
	if(hasRows($result)){
		appInstructions(0, "", "Edit Event Registrant", "Use the form below to edit the registrant.");
		$name = mysql_result($result,0,1);
		$email = mysql_result($result,0,2);
		$phone = mysql_result($result,0,3);
		$address = mysql_result($result,0,4);
		$address2 = mysql_result($result,0,5);
		$city = mysql_result($result,0,6);
		$state = mysql_result($result,0,7);
		$postal = mysql_result($result,0,8);
	} else {
		$name = "";
		$email = "";
		$phone = "";
		$address = "";
		$address2 = "";
		$city = "";
		$state = $hc_defaultState;
		$postal = "";
		appInstructions(0, "", "Add Event Registrant", "Use the form below to add a registrant for this event.");
	}//end if
?>
	<script language="JavaScript" type="text/JavaScript" src="<?echo CalRoot;?>/includes/java/Email.js"></script>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function chkFrm(){
	dirty = 0;
	warn = "Registration could not be processed for the following reason(s):";
	
		if(document.frm.name.value == ''){
			dirty = 1;
			warn = warn + '\n*Name is Required';
		}//end if
		
		if(document.frm.email.value == ''){
			dirty = 1;
			warn = warn + '\n*Email is Required';
		} else {
			if(chkEmail(document.frm.email) == 0){
				dirty = 1;
				warn = warn + '\n*Email Format is Invalid';
			}//end if
		}//end if
		
		if(dirty > 0){
			alert(warn + '\n\nPlease complete the form and try again.');
			return false;
		} else {
			return true;
		}//end if
	}//end chkFrm()
	//-->
	</script>
	<div style="width:350px;">
	<form id="frm" name="frm" method="post" action="<?echo CalAdminRoot . "/components/RegisterAddAction.php";?>" onsubmit="return chkFrm();">
	<input name="oldemail" id="oldemail" type="hidden" value="<?echo $email;?>" />
	<input name="eventID" id="eventID" type="hidden" value="<?echo $_GET['eID'];?>" />
	<input name="rID" id="rID" type="hidden" value="<?echo $rID;?>" />
	<br />
	<fieldset>
		<legend>Add Event Registrant</legend>
		<div class="frmReq">
			<label for="name">Name:</label>
			<input name="name" id="name" type="text" size="20" maxlength="50" value="<?echo $name;?>" />
		</div>
		<div class="frmReq">
			<label for="name">Email:</label>
			<input name="email" id="email" type="text" size="30" maxlength="75" value="<?echo $email;?>" />
		</div>
		<div class="frmOpt">
			<label for="phone">Phone:</label>
			<input name="phone" id="phone" type="text" size="20" maxlength="25" value="<?echo $phone;?>" />
		</div>
		<div class="frmOpt">
			<label for="address">Address:</label>
			<input name="address" id="address" type="text" size="25" maxlength="75" value="<?echo $address;?>" />
		</div>
		<div class="frmOpt">
			<label for="address2"></label>
			<input name="address2" id="address2" type="text" size="25" maxlength="75" value="<?echo $address2;?>" />
		</div>
		<div class="frmOpt">
			<label for="city">City:</label>
			<input name="city" id="city" type="text" size="20" maxlength="50" value="<?echo $city;?>" />
		</div>
		<div class="frmOpt">
			<label for="locState"><?echo HC_StateLabel;?>:</label>
		<?	include('../events/includes/' . HC_StateInclude);?>
		</div>
		<div class="frmOpt">
			<label for="zip">Postal Code:</label>
			<input name="zip" id="zip" type="text" size="5" maxlength="5" value="<?echo $postal;?>" />
		</div>
	</fieldset>
	<br />
	<input type="submit" name="submit" id="submit" value=" Save Registrant " class="button" />&nbsp;&nbsp;
	<input name="cancel" id="cancel" type="button" value="   Cancel   " onclick="window.location.href='<?echo CalAdminRoot;?>/index.php?com=eventedit&amp;eID=<?echo $_GET['eID'];?>';" class="button" />
	</form>
	</div>