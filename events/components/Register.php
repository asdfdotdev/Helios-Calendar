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
	
	$result = doQuery("SELECT Title, StartDate, ContactName, ContactEmail FROM " . HC_TblPrefix . "events WHERE PkID = " . cIn($eID));
	$row_cnt = mysql_num_rows($result);
	
	if($row_cnt > 0){
?>

<script language="JavaScript">
function chkregister(){
dirty = 0;
warn = "Registration could not be processed for the following reason(s):";

	if(document.register.name.value == ''){
		dirty = 1;
		warn = warn + '\n*Name is Required';
	}//end if
	
	if(document.register.email.value == ''){
		dirty = 1;
		warn = warn + '\n*Email is Required';
	} else {
		if(chkEmail(document.register.email) == 0){
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
	<form id="register" name="register" method="post" action="<?echo CalRoot;?>/components/RegisterAction.php" onSubmit="return chkregister();">

		<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td class="eventMain">
					<div align="right"><a href="<?echo CalRoot;?>/index.php?com=detail&eID=<?echo $eID;?>" class="eventMain">&laquo;&laquo;&nbsp;Return to Event Details</a></div><br>
					
					<?php
						if (isset($_GET['msg'])){
							switch ($_GET['msg']){
								case "1" :
									feedback(2,"Email address exists. Enter a new email address.");
									break;
									
							}//end switch
						}//end if
				
				if(isset($_GET['wID'])){
					?>
					<b>Overflow Registration</b> -- This event has reached its attendance limit, however you may wish to register 
					incase additional spots become available due to cancellation or increased capacity.
					<br><br>
				<?
				}//end if?>
				Please fill out the form below to register for <b><?echo mysql_result($result,0,0);?></b> on <?echo stampToDate(mysql_result($result,0,1), "D M jS Y");?>.
				<br><br>
				
				If neccessary the event coordinator
				<script language="JavaScript">
				<?
					$eParts = explode("@", cOut(mysql_result($result,0,3)));
					$edParts = explode(".", $eParts[1]);
				?>
					var who = '<?echo cOut(mysql_result($result,0,2));?>';
					var ename = '<?echo $eParts[0];?>';
					var edomain = '<?echo $edParts[0];?>';
					var eext = '<?echo $edParts[1];?>';
					document.write('<a href="mailto:' + ename + '@' + edomain + '.' + eext + '" class="eventMain"><b>' + who + '</b></a>');
				</script>
				 will contact you with further information or instruction.
				<br><br>
				
			<table cellpadding="0" cellspacing="0" border="0" bgcolor="" width="100%">
				<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
				<tr>
					<td width="65" class="eventMain">Name:</td>
					<td><input size="20" maxlength="50" type="input" name="name" id="name" value="<?if(isset($_GET['name'])){echo $_GET['name'];}//end if?>" class="eventInput"></td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
				<tr>
					<td class="eventMain">Email:</td>
					<td><input size="25" maxlength="75" type="input" name="email" id="email" value="<?if(isset($_GET['email'])){echo $_GET['email'];}//end if?>" class="eventInput"></td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
				<tr>
					<td class="eventMain">Phone:</td>
					<td><input size="15" maxlength="15" type="input" name="phone" id="phone" value="<?if(isset($_GET['phone'])){echo $_GET['phone'];}//end if?>" class="eventInput"></td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="15" alt="" border="0"></td></tr>
				<tr>
					<td class="eventMain">Address:</td>
					<td><input size="20" maxlength="75" type="input" name="address" id="address" value="<?if(isset($_GET['address'])){echo $_GET['address'];}//end if?>" class="eventInput"></td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
				<tr>
					<td>&nbsp;</td>
					<td><input size="20" maxlength="75" type="input" name="address2" id="address2" value="<?if(isset($_GET['address2'])){echo $_GET['address2'];}//end if?>" class="eventInput"></td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
				<tr>
					<td class="eventMain">City:</td>
					<td><input size="20" maxlength="50" type="input" name="city" id="city" value="<?if(isset($_GET['city'])){echo $_GET['city'];}//end if?>" class="eventInput"></td>
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
							include('includes/selectStates.php');
						?>
					</td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
				<tr>
					<td class="eventMain">Zip Code:</td>
					<td><input size="5" maxlength="5" type="input" name="zip" id="zip" value="<?if(isset($_GET['zip'])){echo $_GET['zip'];}//end if?>" class="eventInput"></td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="15" alt="" border="0"></td></tr>
				<tr>
					<td class="eventMain">&nbsp;</td>
					<td>
						<input type="submit" name="submit" id="submit" value=" Submit Registration " class="eventButton">&nbsp;&nbsp;<input type="button" name="cancel" id="cancel" value=" Cancel " class="eventButton" onclick="document.location.href='<?echo CalRoot;?>/index.php?com=detail&eID=<?echo $eID;?>'; return false;">
					</td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="1" alt="" border="0"></td></tr>
			</table>
			<img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0">
		</td>
		<input type="hidden" name="eventID" id="eventID" value="<?echo $eID;?>">
		</form>
		<?php
		} else {
		?>
		<table>
			<tr>
				<td colspan="2" class="eventMain">
					<br><br>
					Your registration was received you should receive an email confirmation shortly.
					<br><br>
					If you have any questions please get in touch with the event contact (available in the event listing).
					<br><br>
					<a href="<?echo CalRoot;?>/index.php?com=detail&eID=<?echo $eID;?>" class="eventMain">Click here to return to event listing.</a>
				</td>
		<?php
		}//end if
		?>
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