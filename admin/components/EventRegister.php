<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	if(isset($_GET['eID']) && is_numeric($_GET['eID'])){
		$eID = $_GET['eID'];
	} else {
		$eID = 0;
	}//end if
	
	if(isset($_GET['aID']) && is_numeric($_GET['aID'])){
		$aID = $_GET['aID'];
	} else {
		$aID = 0;
	}//end if
	
	$result = doQuery("SELECT Title, StartDate FROM " . HC_TblPrefix . "events WHERE PkID = " . cIn($eID));
?>

<script language="JavaScript">
function chkFrm(){
dirty = 0;
warn = "Registration could not be processed for the following reason(s):\n\n";

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
			warn = warn + '\n*Invalid Email Format';
		}//end if
	}//end if
	
	if(document.frm.phone.value == ''){
		dirty = 1;
		warn = warn + '\n*Phone is Required';
	}//end if
	
	if(document.frm.address.value == ''){
		dirty = 1;
		warn = warn + '\n*Address is Required';
	}//end if
	
	if(document.frm.city.value == ''){
		dirty = 1;
		warn = warn + '\n*City is Required';
	}//end if
	
	if(document.frm.zip.value == ''){
		dirty = 1;
		warn = warn + '\n*Zip is Required';
	}//end if
	
	if(dirty > 0){
		alert(warn + '\n\nPlease complete the form and try again.');
		return false;
	} else {
		return true;
	}//end if
}//end chkFrm()

function chkEmail(obj){
	var x = obj.value;
	var filter  = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if (filter.test(x)) {
		return 1;
	} else {
		return 0;
	}//end if
}//end checkMail(object)
</script>

	<?php
	if(!isset($_GET['confirm'])){
	?>
	<form id="frm" name="frm" method="post" action="<?echo CalAdminRoot . "/" . HC_EventRegisterAction;?>" onSubmit="return chkFrm();">
	<input type="hidden" name="oldemail" id="oldemail" value="<?echo $email;?>">
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<tr>
				<td class="eventMain">
					<?php
						if (isset($_GET['msg'])){
							switch ($_GET['msg']){
								case "1" :
									feedback(1,"User Added Successfully.");
									break;
									
								case "2" :
									feedback(2,"Email Address Already Exists For This Event. User Not Added.");
									break;
									
							}//end switch
						}//end if
					?>
					<?php
						if($aID > 0){
							appInstructions(0, "", "Edit Event Registrant", "Use the form below to edit a registrant for the<br><b>" . mysql_result($result,0,0) . "</b> on " . stampToDate(mysql_result($result,0,1), "D M jS Y") . ".");
						}else{
							appInstructions(0, "", "Add Event Registrant", "Use the form below to add a registrant for the<br><b>" . mysql_result($result,0,0) . "</b> on " . stampToDate(mysql_result($result,0,1), "D M jS Y") . ".");
						}//end if
					?>
					<br>
				<div align="right"><a href="<?echo CalAdminRoot . "/index.php?com=eventedit&eID=" . $eID;?>" class="main">&laquo;&laquo;Return to Event Edit</a></div>
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
				<tr>
					<td width="65" class="eventMain">Name:</td>
					<td><input size="20" maxlength="50" type="input" name="name" id="name" value="<?if(isset($_GET['name'])){echo $_GET['name'];}//end if?>" class="input"></td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
				<tr>
					<td class="eventMain">Email:</td>
					<td><input size="25" maxlength="75" type="input" name="email" id="email" value="<?if(isset($_GET['email'])){echo $_GET['email'];}//end if?>" class="input"></td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
				<tr>
					<td class="eventMain">Phone:</td>
					<td><input size="15" maxlength="15" type="input" name="phone" id="phone" value="<?if(isset($_GET['phone'])){echo $_GET['phone'];}//end if?>" class="input"></td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="15" alt="" border="0"></td></tr>
				<tr>
					<td class="eventMain">Address:</td>
					<td><input size="20" maxlength="75" type="input" name="address" id="address" value="<?if(isset($_GET['address'])){echo $_GET['address'];}//end if?>" class="input"></td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
				<tr>
					<td>&nbsp;</td>
					<td><input size="20" maxlength="75" type="input" name="address2" id="address2" value="<?if(isset($_GET['address2'])){echo $_GET['address2'];}//end if?>" class="input"></td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
				<tr>
					<td class="eventMain">City:</td>
					<td><input size="20" maxlength="50" type="input" name="city" id="city" value="<?if(isset($_GET['city'])){echo $_GET['city'];}//end if?>" class="input"></td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
				<tr>
					<td class="eventMain">State:</td>
					<td>
						<?php
							if(!isset($_GET['state'])){
								$state = $defaultState;
							} else {
								$state = $_GET['state'];
							}//end if
							include('../events/includes/selectStates.php');
						?>
					</td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
				<tr>
					<td class="eventMain">Zip Code:</td>
					<td><input size="5" maxlength="5" type="input" name="zip" id="zip" value="<?if(isset($_GET['zip'])){echo $_GET['zip'];}//end if?>" class="input"></td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="15" alt="" border="0"></td></tr>
				<tr>
					<td class="eventMain">&nbsp;</td>
					<td>
						<input type="submit" name="submit" id="submit" value=" Submit Registration " class="button">
					</td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="1" alt="" border="0"></td></tr>
			</table>
			<img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0">
		</td>
		<input type="hidden" name="eventID" id="eventID" value="<?echo $eID;?>">
		</form>
	</tr>
</table>
<?php
	} else {
	?>
		You are attempting to register for an invalid event.<br><br>
		<a href="<?echo CalRoot;?>" class="eventMain">Please click here to find a valid event.</a>
	<?
	}//end if
?>