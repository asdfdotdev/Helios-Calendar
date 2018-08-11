<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2006 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://http://www.google.com/search?lr=&ie=UTF-8&oe=UTF-8&q=Krucoffwww.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	if(isset($_GET['eID']) && is_numeric($_GET['eID'])){
		$eID = $_GET['eID'];
	} else {
		$eID = 0;
	}//end if	?>

	<div id="nav-top"><a href="<?echo CalRoot;?>/index.php?com=detail&eID=<?echo $eID;?>" class="eventMain">&laquo;&laquo;&nbsp;Return to Event Details</a></div>
	<br />
<?	$result = doQuery("SELECT Title, StartDate, ContactName, ContactEmail FROM " . HC_TblPrefix . "events WHERE PkID = " . cIn($eID) . " AND StartDate >= NOW()");
	$row_cnt = mysql_num_rows($result);
	
	if($row_cnt > 0){	
		if (isset($_GET['msg'])){
			switch ($_GET['msg']){
				case "1" :
					feedback(2,"Email address exists. Enter a new email address.");
					break;
					
			}//end switch
		}//end if	?>
		<script language="JavaScript" type="text/JavaScript" src="<?echo CalRoot;?>/includes/java/Email.js"></script>
		<script language="JavaScript" type="text/JavaScript">
		function chkFrm(){
		dirty = 0;
		warn = "Registration could not be processed for the following reason(s):";
		
			if(document.frmEventRegister.name.value == ''){
				dirty = 1;
				warn = warn + '\n*Name is Required';
			}//end if
			
			if(document.frmEventRegister.email.value == ''){
				dirty = 1;
				warn = warn + '\n*Email is Required';
			} else {
				if(chkEmail(document.frmEventRegister.email) == 0){
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
		</script>
	<?	if(!isset($_GET['confirm'])){	?>
		Complete the form below to register for <b><?echo mysql_result($result,0,0);?></b>
		<br />
		on <?echo stampToDate(mysql_result($result,0,1), "l, F jS Y");?>.
		<br /><br />
		If neccessary the event coordinator
		<script language="JavaScript">
		<?	$eParts = explode("@", cOut(mysql_result($result,0,3)));	?>
			var who = '<?echo cOut(mysql_result($result,0,2));?>';
			var ename = '<?echo $eParts[0];?>';
			var edomain = '<?echo $eParts[1];?>';
			document.write('<a href="mailto:' + ename + '@' + edomain + '" class="eventMain"><b>' + who + '</b></a>');
		</script>
		 may contact you with further information or instruction.
		<br /><br />
		<form id="frmEventRegister" name="frmEventRegister" method="post" action="<?echo CalRoot;?>/components/EventRegisterAction.php" onsubmit="return chkFrm();">		
		<input name="eventID" id="eventID" type="hidden" value="<?echo $eID;?>" />
		<fieldset>
			<legend>Registering for: <?echo mysql_result($result,0,0);?></legend>
			<div class="frmReq">
				<label for="name">Name:</label>
				<input name="name" id="name" type="text" size="25" maxlength="50" value="<?if(isset($_GET['name'])){echo $_GET['name'];}//end if?>" />
			</div>
			<div class="frmReq">
				<label for="email">Email:</label>
				<input name="email" id="email" type="text" size="35" maxlength="75" value="<?if(isset($_GET['email'])){echo $_GET['email'];}//end if?>" />
			</div>
			<div class="frmOpt">
				<label for="phone">Phone:</label>
				<input name="phone" id="phone" type="text" size="15" maxlength="15" value="<?if(isset($_GET['phone'])){echo $_GET['phone'];}//end if?>" />
			</div>
			<div class="frmOpt">
				<label for="address">Address;</label>
				<input size="20" maxlength="75" type="text" name="address" id="address" value="<?if(isset($_GET['address'])){echo $_GET['address'];}//end if?>" />
			</div>
			<div class="frmOpt">
				<label>&nbsp;</label>
				<input size="20" maxlength="75" type="text" name="address2" id="address2" value="<?if(isset($_GET['address2'])){echo $_GET['address2'];}//end if?>" />
			</div>
			<div class="frmOpt">
				<label for="city">City:</label>
				<input size="20" maxlength="50" type="text" name="city" id="city" value="<?if(isset($_GET['city'])){echo $_GET['city'];}//end if?>" />
			</div>
			<div class="frmOpt">
				<label for="state"><?echo HC_StateLabel;?></label>
				<?	$state = $hc_defaultState;
					if(isset($_GET['state'])){
						$state = $_GET['state'];
					}//end if
					include('../events/includes/' . HC_StateInclude);	?>
			</div>
			<div class="frmOpt">
				<label for="zip">Postal Code:</label>
				<input size="5" maxlength="5" type="text" name="zip" id="zip" value="<?if(isset($_GET['zip'])){echo $_GET['zip'];}//end if?>" />
			</div>
			
			<label>&nbsp;</label>
			<input name="submit" id="submit" type="submit" value="Register Now" />&nbsp;&nbsp;&nbsp;
			<input name="cancel" id="cancel" type="button" value=" Cancel " onclick="document.location.href='<?echo CalRoot;?>/index.php?com=detail&eID=<?echo $eID;?>'; return false;" />
		</fieldset>
		
		</form>
	<?	} else {	?>
			Your registration was received you should receive an email confirmation shortly.
			<br /><br />
			If you have any questions please get in touch with the event contact (available in the event listing).
			<br /><br />
			<a href="<?echo CalRoot;?>/index.php?com=detail&eID=<?echo $eID;?>" class="eventMain">Click here to return to event listing.</a>
	<?	}//end if
	} else {	?>
		You are attempting to register for an invalid or past event.<br><br>
		<a href="<?echo CalRoot;?>" class="eventMain">Please click here to browse events.</a>
<?	}//end if	?>