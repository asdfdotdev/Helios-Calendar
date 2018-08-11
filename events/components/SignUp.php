<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
?>

<script language="JavaScript">
function chkFrm()
{
dirty = 0;
warn = "Your submission could not be completed for the following reason(s):";

	if(document.eventAlert.firstname.value == ''){
		dirty = 1;
		warn = warn + '\n*First Name is Required';
	}//end if

	if(document.eventAlert.lastname.value == ''){
		dirty = 1;
		warn = warn + '\n*Last Name is Required';
	}//end if
	
	if(document.eventAlert.email.value == ''){
		dirty = 1;
		warn = warn + '\n*Your Email Address is Required';
	} else {
		if(chkEmail(document.eventAlert.email) == 0){
			dirty = 1;
			warn = warn + '\n*Invalid Email Format';
		}//end if
	}//end if
	
	if(validateCheckArray('eventAlert','catID[]',1,'Category') > 0){
		dirty = 1;
		warn = warn + '\n*Category Selection is Required';
	}//end if
	
	if(document.eventAlert.occupation.value == 0){
		dirty = 1;
		warn = warn + '\n*Occupation is Required';
	}//end if
	
	if(document.eventAlert.zip.value == ''){
		dirty = 1;
		warn = warn + '\n*Zip Code is Required';
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
				feedback(1,"Sign-Up Successful. Please Activate Your Registration<br>Email Sent With Activation Instructions.");
				break;
				
			case "2" :
				feedback(2,"Email Address Is Already Registered.<br>Please Enter New Email Address");
				break;
				
			case "3":
				feedback(1,"Account Activated Successfully.<br>You Will Receive Our Next Mailing.");
				break;
				
		}//end switch
	}//end if
?>

Complete the form below, selecting the categories you wish to be notified about, and click 'Sign-Up'.<br><br>
You will receive a <b>confirmation notice</b> shortly after submiting with activation instructions.<br><br>
<b>Announcements will not be sent until registration is activated.</b><br><br>
<b>Newsletter Signup</b>
<table cellpadding="0" cellspacing="0" border="0">
<form name="eventAlert" id="" method="post" action="<?echo HC_SignupAction;?>" onSubmit="return chkFrm();">
	<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
	<tr><td colspan="2" class="eventSeparator"><img src="<?echo CalRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
	<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
	<tr>
		<td class="eventMain" width="80">First Name:</td>
		<td><input size="15" maxlength="50" type="text" name="firstname" id="firstname" value="<?if(isset($_GET['fname'])){echo $_GET['fname'];}//end if?>" class="eventInput"></td>
	</tr>
	<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
	<tr>
		<td class="eventMain" width="80">Last Name:</td>
		<td><input size="15" maxlength="50" type="text" name="lastname" id="lastname" value="<?if(isset($_GET['lname'])){echo $_GET['lname'];}//end if?>" class="eventInput"></td>
	</tr>
	<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
	<tr>
		<td class="eventMain">Email:</td>
		<td><input size="30" maxlength="75" type="text" name="email" id="email" value="" class="eventInput"></td>
	</tr>
	<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
	<tr>
		<td class="eventMain">Occupation:</td>
		<td>
			<select name="occupation" id="occupation" class="eventInput">
				<option value="0">[Select an Occupation]</option>
			<?php
				$result = doQuery("SELECT * FROM " . HC_TblPrefix . "useroccupation WHERE IsActive = 1 ORDER BY Occupation");
				if(hasRows($result)){
					
					if(isset($_GET['occ'])){
						$oID = $_GET['occ'];
					} else {
						$oID = 0;
					}//end if
					
					while($row = mysql_fetch_row($result)){
					?>
						<option<?if($oID == $row[0]){echo " SELECTED";}//end if?> value="<?echo $row[0];?>"><?echo $row[1];?></option>
					<?
					}//while
					
				}//end if
			?>
			</select>
		</td>
	</tr>
	
	<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
	<tr>
		<td class="eventMain">Zip Code:</td>
		<td><input maxlength="5" size="5" type="text" name="zip" id="zip" value="<?if(isset($_GET['zip'])){echo $_GET['zip'];}//end if?>" class="eventInput"></td>
	</tr>
	<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
	<tr>
		<td class="eventMain" valign="top">Categories:</td>
		<td class="eventMain">
			
			<table cellpadding="0" cellspacing="0" border="0">
				<?php
					$result = doQuery("SELECT * FROM " . HC_TblPrefix . "categories WHERE IsActive = 1 AND PkID > 0 ORDER BY CategoryName");
					$cnt = 0;
					
					while($row = mysql_fetch_row($result)){
						if((fmod($cnt,2) == 0) AND ($cnt > 0)){echo "</tr><tr>";}//end if
					?>
						<td class="eventMain"><input type="checkbox" name="catID[]" id="catID_<?echo $row[0];?>" value="<?echo $row[0];?>"></td>
						<td class="eventMain"><label for="catID_<?echo $row[0];?>"><?echo $row[1];?></label>&nbsp;&nbsp;</td>
					<?
						$cnt = $cnt + 1;
					}//end while
				?>
			</table>
			<?	if($cnt > 1){	?>
				<img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"><br>
				[ <a class="eventMain" href="javascript:;" onClick="checkAllArray('eventAlert', 'catID[]');">Select All Categories</a> 
				&nbsp;|&nbsp; <a class="eventMain" href="javascript:;" onClick="uncheckAllArray('eventAlert', 'catID[]');">Deselect All Categories</a> ]
				<img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0">
			<?	}//end if	?>
		</td>
	</tr>
	<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
	<tr><td colspan="2" class="eventSeparator"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
	<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<input type="submit" name="submit" id="submit" value=" Sign-Up " class="eventButton">
		</td>
	</tr>
</form>
</table>