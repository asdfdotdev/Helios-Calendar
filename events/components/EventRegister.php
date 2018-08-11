<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2007 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	if(isset($_GET['eID']) && is_numeric($_GET['eID'])){
		$eID = $_GET['eID'];
	} else {
		$eID = 0;
	}//end if	?>

	<div id="nav-top"><a href="<?php echo CalRoot;?>/index.php?com=detail&eID=<?php echo $eID;?>" class="eventMain">&laquo;&laquo;&nbsp;Return to Event Details</a></div>
	<br />
<?php
	$result = doQuery("SELECT Title, StartDate, ContactName, ContactEmail FROM " . HC_TblPrefix . "events WHERE PkID = " . cIn($eID) . " AND StartDate >= NOW()");
	$row_cnt = mysql_num_rows($result);
	
	if($row_cnt > 0){	
		if (isset($_GET['msg'])){
			switch ($_GET['msg']){
				case "1" :
					feedback(2,"Email address exists. Enter a new email address.");
					break;
					
			}//end switch
		}//end if	?>
		<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Email.js"></script>
		<script language="JavaScript" type="text/JavaScript">
		//<!--
		function chkFrm(){
		dirty = 0;
		warn = "Registration could not be processed for the following reason(s):";
		
<?php 	if(in_array(3, $hc_captchas)){	?>
			if(document.frmEventRegister.proof.value == ''){
				dirty = 1;
				warn = warn + '\n*Authentication Text is Required';
			}//end if
<?php 	}//end if	?>
		
			if(document.frmEventRegister.hc_f1.value == ''){
				dirty = 1;
				warn = warn + '\n*Name is Required';
			}//end if
			
			if(document.frmEventRegister.hc_f2.value == ''){
				dirty = 1;
				warn = warn + '\n*Email is Required';
			} else {
				if(chkEmail(document.frmEventRegister.hc_f2) == 0){
					dirty = 1;
					warn = warn + '\n*Invalid Email Format';
				}//end if
			}//end if
			
			if(dirty > 0){
				alert(warn + '\nPlease complete the form and try again.');
				return false;
			} else {
				return true;
			}//end if
		}//end chkregister()
		//-->
		</script>
<?php 	if(!isset($_GET['confirm'])){	?>
		Complete the form below to register for <b><?php echo mysql_result($result,0,0);?></b>
		<br />
		on <?php echo stampToDate(mysql_result($result,0,1), "l, F jS Y");?>.
		<br /><br />
		If neccessary the event coordinator
		<script language="JavaScript" type="text/JavaScript">
	<?php 	$eParts = explode("@", cOut(mysql_result($result,0,3)));	?>
			//<!--
			var who = '<?php echo cOut(mysql_result($result,0,2));?>';
			var ename = '<?php echo $eParts[0];?>';
			var edomain = '<?php echo $eParts[1];?>';
			document.write('<a href="mailto:' + ename + '@' + edomain + '" class="eventMain"><b>' + who + '</b></a>');
			//-->
		</script>
		 may contact you with further information or instruction.
		<br /><br />
		<form id="frmEventRegister" name="frmEventRegister" method="post" action="<?php echo CalRoot;?>/components/EventRegisterAction.php" onsubmit="return chkFrm();">
<?php 	fakeFormFields();	?>
		<input name="eventID" id="eventID" type="hidden" value="<?php echo $eID;?>" />
		<fieldset>
			<legend>Registering for: <?php echo mysql_result($result,0,0);?></legend>
<?php 	if(in_array(3, $hc_captchas)){	?>
			<div class="frmReq">
				<label for="proof">Authentication:</label>
		<?php	buildCaptcha();	?><br />
			</div>
			<div class="frmReq">
				<label>&nbsp;</label>
				<input name="proof" id="proof" type="text" maxlength="8" size="8" value="" />
				<-- Enter Image Text Here
			</div>
<?php 	}//end if	?>
			<div class="frmReq">
				<label for="hc_f1">Name:</label>
				<input name="hc_f1" id="hc_f1" type="text" size="25" maxlength="50" value="<?php if(isset($_GET['name'])){echo $_GET['name'];}//end if?>" />
			</div>
			<div class="frmReq">
				<label for="hc_f2">Email:</label>
				<input name="hc_f2" id="hc_f2" type="text" size="35" maxlength="75" value="<?php if(isset($_GET['email'])){echo $_GET['email'];}//end if?>" />
			</div>
			<div class="frmOpt">
				<label for="hc_f3">Phone:</label>
				<input name="hc_f3" id="hc_f3" type="text" size="20" maxlength="25" value="<?php if(isset($_GET['phone'])){echo $_GET['phone'];}//end if?>" />
			</div>
			<div class="frmOpt">
				<label for="hc_f4">Address:</label>
				<input name="hc_f4" id="hc_f4" type="text" size="20" maxlength="75" value="<?php if(isset($_GET['address'])){echo $_GET['address'];}//end if?>" />
			</div>
			<div class="frmOpt">
				<label>&nbsp;</label>
				<input name="hc_f5" id="hc_f5" type="text" size="20" maxlength="75" value="<?php if(isset($_GET['address2'])){echo $_GET['address2'];}//end if?>" />
			</div>
			<div class="frmOpt">
				<label for="hc_f6">City:</label>
				<input name="hc_f6" id="hc_f6" type="text" size="20" maxlength="50" value="<?php if(isset($_GET['city'])){echo $_GET['city'];}//end if?>" />
			</div>
			<div class="frmOpt">
				<label for="locState"><?php echo HC_StateLabel;?>:</label>
				<?php
					$state = $hc_defaultState;
					if(isset($_GET['state'])){
						$state = $_GET['state'];
					}//end if
					include('../events/includes/' . HC_StateInclude);	?>
			</div>
			<div class="frmOpt">
				<label for="hc_f8">Postal Code:</label>
				<input name="hc_f8" id="hc_f8" type="text" size="11" maxlength="11" value="<?php if(isset($_GET['zip'])){echo $_GET['zip'];}//end if?>" />
			</div>
		</fieldset>
		<br />
		<input name="submit" id="submit" type="submit" value="Register Now" class="button" />&nbsp;&nbsp;&nbsp;
		<input name="cancel" id="cancel" type="button" value=" Cancel " onclick="document.location.href='<?php echo CalRoot;?>/index.php?com=detail&eID=<?php echo $eID;?>'; return false;" class="button" />
		</form>
<?php 	} else {	?>
			Your registration was received you should receive an email confirmation shortly.
			<br /><br />
			If you have any questions please get in touch with the event contact (available in the event listing).
			<br /><br />
			<a href="<?php echo CalRoot;?>/index.php?com=detail&eID=<?php echo $eID;?>" class="eventMain">Click here to return to event listing.</a>
<?php 	}//end if
	} else {	?>
		You are attempting to register for an invalid or past event.<br /><br />
		<a href="<?php echo CalRoot;?>" class="eventMain">Please click here to browse events.</a>
<?php
	}//end if	?>