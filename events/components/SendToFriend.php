<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	if(isset($_GET['eID']) && is_numeric($_GET['eID'])){
		$eID = $_GET['eID'];
	} else {
		$eID = 0;
	}//end if
	
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "events WHERE PkID = " . $eID);
	$row_cnt = mysql_num_rows($result);
	
	if($row_cnt > 0){
?>
<script language="JavaScript">
function chkFrm(){
dirty = 0;
warn = "Message could not be sent for the following reason(s):";

	if(document.EventSendFriend.myName.value == ''){
		dirty = 1;
		warn = warn + '\n*Your Name is Required';
	}//end if
	
	if(document.EventSendFriend.myEmail.value == ''){
		dirty = 1;
		warn = warn + '\n*Your Email is Required';
	} else {
		if(chkEmail(document.EventSendFriend.myEmail) == 0){
			dirty = 1;
			warn = warn + '\n*Your Email Format is Invalid';
		}//end if
	}//end if
	
	if(document.EventSendFriend.recipName.value == ''){
		dirty = 1;
		warn = warn + '\n*Recipient Name is Required';
	}//end if
	
	if(document.EventSendFriend.recipEmail.value == ''){
		dirty = 1;
		warn = warn + '\n*Your Email is Required';
	} else {
		if(chkEmail(document.EventSendFriend.recipEmail) == 0){
			dirty = 1;
			warn = warn + '\n*Recipient Email Format is Invalid';
		}//end if
	}//end if
	
	if(document.EventSendFriend.message.value.length > 250){
		dirty = 1;
		warn = warn + '\n*Message Limit of 250 Characters Exceeded.';
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
	if (isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1,"Notice Sent Successfully!");
				break;
				
		}//end switch
	}//end if
?>
		<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td colspan="2" class="eventMain">
					<div align="right"><a href="<?echo CalRoot;?>/?com=detail&eID=<?echo $eID;?>" class="eventMain">&laquo;&laquo; Return to Event Details</a></div>
					You are sending an email notice about the event:<br><br>
					<b><?echo mysql_result($result,0,1);?></b>
				</td>
			</tr>
			<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
			<tr><td colspan="2" class="eventSubmitSeparator"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
			<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
			
			<form name="EventSendFriend" id="EventSendFriend" method="post" action="<?echo CalRoot;?>/components/SendToFriendAction.php" onSubmit="return chkFrm();">
			<tr>
				<td width="110" class="eventMain">Your Name:</td>
				<td>
					<input size="20" maxlength="100" type="text" name="myName" id="myName" value="" class="eventInput">
				</td>
			</tr>
			<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
			<tr>
				<td class="eventMain">Your Email:</td>
				<td>
					<input size="30" maxlength="100" type="text" name="myEmail" id="myEmail" value="" class="eventInput">
				</td>
			</tr>
			<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
			<tr>
				<td class="eventMain">Recipient Name:</td>
				<td>
					<input size="20" maxlength="100" type="text" name="recipName" id="recipName" value="" class="eventInput">
				</td>
			</tr>
			<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
			<tr>
				<td class="eventMain">Recipient Email:</td>
				<td>
					<input size="30" maxlength="100" type="text" name="recipEmail" id="recipEmail" value="" class="eventInput">
				</td>
			</tr>
			<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
			<tr>
				<td class="eventMain" valign="top">
					Message:
				</td>
				<td class="eventMain">
					<?php
						echo "[ Recipient Name ],<br><br> I was visiting the " . CalName . " today and found a post for the <b>" . mysql_result($result,0,1) . "</b> event and thought you'd like to know about it.<br><br>You can get all the information about it at:<br><a href=\"" . CalRoot . "/index.php?com=detail&eID=" . $eID . "\" target=\"_new\" class=\"eventMain\">" . CalRoot . "/index.php?com=detail&eID=" . $eID . "</a>";
					?>
					<br><br>
					(<b>Limit: 250 Characters</b>)<br>
					<input type="hidden" name="eID" id="eID" value="<?echo $eID;?>">
					<textarea onKeyUp="this.value = this.value.slice(0, 250)" cols="50" rows="10" name="message" id="message" class="eventInput"></textarea>
				</td>
			</tr>
			<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
			<tr><td colspan="2" class="eventSubmitSeparator"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
			<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
			<tr>
				<td>&nbsp;</td>
				<td>
					<input type="submit" name="submit" id="submit" value=" Send Message " class="eventButton">
				</td>
			</tr>
			</form>
		</table>
<?
	} else {
?>
		<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td class="eventMain">
					<br>
					You are attempting to send an invalid event.<br><br>
					<a href="<?echo CalRoot;?>" class="eventMain">Click here to return to this week's event listing.</a>
				</td>
			</tr>
		</table>
<?
	}//end if
?>