<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2007 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
?>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Email.js"></script>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function chkFrm(){
	dirty = 0;
	warn = "Message could not be sent for the following reason(s):";

<?php 	if(in_array(2, $hc_captchas)){	?>
			if(document.frmSendToFriend.proof.value == ''){
				dirty = 1;
				warn = warn + '\n*Authentication Text is Required';
			}//end if
<?php	}//end if	?>
	
		if(document.frmSendToFriend.hc_fx1.value == ''){
			dirty = 1;
			warn = warn + '\n*My Name is Required';
		}//end if
		
		if(document.frmSendToFriend.hc_fx2.value == ''){
			dirty = 1;
			warn = warn + '\n*My Email is Required';
		} else {
			if(chkEmail(document.frmSendToFriend.myEmail) == 0){
				dirty = 1;
				warn = warn + '\n*My Email Format is Invalid';
			}//end if
		}//end if
		
		if(document.frmSendToFriend.hc_fx3.value == ''){
			dirty = 1;
			warn = warn + '\n*Friends Name is Required';
		}//end if
		
		if(document.frmSendToFriend.hc_fx4.value == ''){
			dirty = 1;
			warn = warn + '\n*Friends Email is Required';
		} else {
			if(chkEmail(document.frmSendToFriend.friendEmail) == 0){
				dirty = 1;
				warn = warn + '\n*Friends Email Format is Invalid';
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
	//-->
	</script>
	<br />
<?php	
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
		<div id="nav-top"><a href="<?php echo CalRoot;?>/?com=detail&amp;eID=<?php echo $eID;?>" class="eventMain">&laquo;&laquo; Return to Event Details</a></div>
		<br />
		Use the form below to send an email notice about the event titled:
		<br /><br />
		<b><?php echo mysql_result($result,0,1);?></b>
		<br /><br />
		A link to the event will be included with your message.
		<br /><br />
		<form name="frmSendToFriend" id="frmSendToFriend" method="post" action="<?php echo CalRoot;?>/components/SendToFriendAction.php" onsubmit="return chkFrm();">
<?php 	fakeFormFields();	?>
		<input type="hidden" name="eID" id="eID" value="<?php echo $eID;?>" />
		<fieldset>
			<legend>Create Your Message</legend>
<?php 	if(in_array(2, $hc_captchas)){	?>
			<div class="frmReq">
				<label for="proof">Authentication:</label>
		<?php	buildCaptcha();	?><br />
			</div>
			<div class="frmReq">
				<label>&nbsp;</label>
				<input name="proof" id="proof" type="text" maxlength="8" size="9" value="" />
				<-- Enter Image Text Here
			</div>
<?php 	}//end if	?>
			<div class="frmReq">
				<label for="hc_fx1">My Name:</label>
				<input name="hc_fx1" id="hc_fx1" type="text" size="25" maxlength="100" />
		    </div>
		    <div class="frmReq">
				<label for="hc_fx2">My Email:</label>
				<input id="hc_fx2" name="hc_fx2" type="text" size="35" maxlength="100" />
		    </div>
		    <div class="frmReq">
				<label for="hc_fx3">Friends Name:</label>
				<input name="hc_fx3" id="hc_fx3" type="text" size="25" maxlength="100" />
		    </div>
		    <div class="frmReq">
				<label for="hc_fx4">Friends Email:</label>
				<input name="hc_fx4" id="hc_fx4" type="text" size="35" maxlength="100" />
		    </div>
			<div class="frmReq">
				<label for="hc_fx5">Message*:<br /><br /><b>*Limit: 250 Characters</b></label>
				<textarea name="hc_fx5" id="hc_fx5" rows="10" cols="45" onkeyup="this.value=this.value.slice(0, 250)"><?php echo "I was visiting the " . CalName . " today and found the " . cOut(mysql_result($result,0,1)) . " event and thought you would like to know about it.";	?></textarea>
			</div>
		</fieldset>
		<br />
		<input name="submit" id="submit" type="submit" value="Send Message" class="button" />
		</form>
<?php 
	} else {	?>
		<br />
		You are attempting to send an invalid event.<br /><br />
		<a href="<?php echo CalRoot;?>" class="eventMain">Click here to return to this week's event listing.</a>
<?php 
	}//end if	?>