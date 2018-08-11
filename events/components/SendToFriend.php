<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2006 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/	?>
<script language="JavaScript" type="text/JavaScript" src="<?echo CalRoot;?>/includes/java/Email.js"></script>
<script language="JavaScript" type="text/JavaScript">
function chkFrm(){
dirty = 0;
warn = "Message could not be sent for the following reason(s):";

	if(document.frmSendToFriend.myName.value == ''){
		dirty = 1;
		warn = warn + '\n*My Name is Required';
	}//end if
	
	if(document.frmSendToFriend.myEmail.value == ''){
		dirty = 1;
		warn = warn + '\n*My Email is Required';
	} else {
		if(chkEmail(document.frmSendToFriend.myEmail) == 0){
			dirty = 1;
			warn = warn + '\n*My Email Format is Invalid';
		}//end if
	}//end if
	
	if(document.frmSendToFriend.friendName.value == ''){
		dirty = 1;
		warn = warn + '\n*Friend Name is Required';
	}//end if
	
	if(document.frmSendToFriend.friendEmail.value == ''){
		dirty = 1;
		warn = warn + '\n*Friend Email is Required';
	} else {
		if(chkEmail(document.frmSendToFriend.friendEmail) == 0){
			dirty = 1;
			warn = warn + '\n*Friend Email Format is Invalid';
		}//end if
	}//end if
	
	if(document.frmSendToFriend.message.value.length > 250){
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
</script>
<br />
<?	
	if(isset($_GET['eID']) && is_numeric($_GET['eID'])){
		$eID = $_GET['eID'];
	} else {
		$eID = 0;
	}//end if
	
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "events WHERE PkID = " . cIn($eID));
	
	if(hasRows($result)){
		if (isset($_GET['msg'])){
			switch ($_GET['msg']){
				case "1" :
					feedback(1,"Your Email Was Sent Successfully!");
					break;
					
			}//end switch
		}//end if?>
		<div id="nav-top"><a href="<?echo CalRoot;?>/?com=detail&amp;eID=<?echo $eID;?>" class="eventMain">&laquo;&laquo; Return to Event Details</a></div>
		<br />
		Use the form below to send an email notice about the event titled:<br /><br />
		<b><?echo mysql_result($result,0,1);?></b>
		<br /><br />
		<form name="frmSendToFriend" id="frmSendToFriend" method="post" action="<?echo CalRoot;?>/components/SendToFriendAction.php" onsubmit="return chkFrm();">
		<input type="hidden" name="eID" id="eID" value="<?echo $eID;?>" />
		<fieldset>
			<legend>Create Your Message</legend>
		    <div class="frmReq">
				<label for="myName">My Name:</label>
				<input name="myName" id="myName" type="text" size="25" maxlength="100" />
		    </div>
		    <div class="frmReq">
				<label for="myEmail">My Email:</label>
				<input id="myEmail" name="myEmail" type="text" size="35" maxlength="100" />
		    </div>
		    <div class="frmReq">
				<label for="friendName">Friends Name:</label>
				<input name="friendName" id="friendName" type="text" size="25" maxlength="100" />
		    </div>
		    <div class="frmReq">
				<label for="friendEmail">Friends Email:</label>
				<input name="friendEmail" id="friendEmail" type="text" size="35" maxlength="100" />
		    </div>
			<div class="frmReq">
				<label for="message">Message*:<br /><br /><b>*Limit: 250 Characters</b></label>
				<textarea name="message" id="message" rows="10" cols="45" onkeyup="this.value=this.value.slice(0, 250)"><?	echo "I was visiting the " . CalName . " today and found a post for the " . cOut(mysql_result($result,0,1)) . " event and thought you would like to know about it.";	?></textarea>
			</div>
			
			<label>&nbsp;</label>
			<input name="submit" id="submit" type="submit" value="Send Message" class="button" />
		</fieldset>
		</form>
<?	} else {	?>
		<br />
		You are attempting to send an invalid event.<br /><br />
		<a href="<?echo CalRoot;?>" class="eventMain">Click here to return to this week's event listing.</a>
<?	}//end if	?>