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
	<script language="JavaScript" type="text/JavaScript" src="<?echo CalRoot;?>/includes/java/Email.js"></script>
	<script language="JavaScript" type="text/JavaScript" src="<?echo CalRoot;?>/includes/java/Checkboxes.js"></script>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function chkFrm()
	{
	dirty = 0;
	warn = "Your registration could not be completed for the following reason(s):";
	
<?	if(in_array(4, $hc_captchas)){	?>
		if(document.frmEventNewsletter.proof.value == ''){
			dirty = 1;
			warn = warn + '\n*Authentication Text is Required';
		}//end if
<?	}//end if	?>
	
		if(document.frmEventNewsletter.firstname.value == ''){
			dirty = 1;
			warn = warn + '\n*First Name is Required';
		}//end if
	
		if(document.frmEventNewsletter.lastname.value == ''){
			dirty = 1;
			warn = warn + '\n*Last Name is Required';
		}//end if
		
		if(document.frmEventNewsletter.email.value == ''){
			dirty = 1;
			warn = warn + '\n*Your Email Address is Required';
		} else {
			if(chkEmail(document.frmEventNewsletter.email) == 0){
				dirty = 1;
				warn = warn + '\n*Invalid Email Format';
			}//end if
		}//end if
		
		if(validateCheckArray('frmEventNewsletter','catID[]',1,'Category') > 0){
			dirty = 1;
			warn = warn + '\n*Category Selection is Required';
		}//end if
		
		if(document.frmEventNewsletter.occupation.value == 0){
			dirty = 1;
			warn = warn + '\n*Occupation is Required';
		}//end if
		
		if(document.frmEventNewsletter.zip.value == ''){
			dirty = 1;
			warn = warn + '\n*Postal Code is Required';
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
<?	if (isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1,"Sign-Up Successful. Please Activate Your Registration<br>Email Sent With Activation Instructions.");
				break;
				
			case "2" :
				feedback(2,"Email Address Is Already Registered.<br>Please Enter New Email Address");
				break;
				
			case "3":
				feedback(1,"Registration Activated Successfully.<br>You Will Receive Our Next Mailing.");
				break;
				
		}//end switch
	}//end if	?>
	<br />
	To register for our newsletter complete the form below. Selecting the categories you wish to be notified about, and click 'Submit Registration'.
	<br /><br />
	You will receive a <b>confirmation notice</b> activation instructions.
	<br /><br />
	<b>Announcements will not be sent until registration is activated.</b>
	<br /><br />
	<form name="frmEventNewsletter" id="frmEventNewsletter" method="post" action="<?echo HC_SignupAction;?>" onsubmit="return chkFrm();">
	<fieldset>
		<legend>Event Newsletter Sign Up</legend>
	<?	fakeFormFields();	?>
	<?	if(in_array(2, $hc_captchas)){	?>
			<div class="frmReq">
				<label for="proof">Authentication:</label>
		<?php	buildCaptcha();	?><br />
			</div>
			<div class="frmReq">
				<label>&nbsp;</label>
				<input name="proof" id="proof" type="text" maxlength="8" size="9" value="" onblur="alert('Please verify you have entered the authentication text correctly or you will have to re-enter your information to sign up.');" />
				<-- Enter Image Text Here
			</div>
	<?	}//end if	?>
		<div class="frmReq">
			<label for="hc_f1">First Name:</label>
			<input name="hc_f1" id="hc_f1" type="text" value="<?if(isset($_GET['fname'])){echo $_GET['fname'];}//end if?>" size="20" maxlength="50" />
		</div>
		<div class="frmReq">
			<label for="hc_f2">Last Name:</label>
			<input name="hc_f2" id="hc_f2" type="text" value="<?if(isset($_GET['lname'])){echo $_GET['lname'];}//end if?>" size="20" maxlength="50" />
		</div>
		<div class="frmReq">
			<label for="hc_f3">Email:</label>
			<input name="hc_f3" id="hc_f3" type="text" value="" size="30" maxlength="75" />
		</div>
		<div class="frmReq">
			<label for="occupation">Occupation:</label>
		<?	include('includes/selectOccupation.php');?>
		</div>
		<div class="frmReq">
			<label for="hc_f4">Postal Code:</label>
			<input name="hc_f4" id="hc_f4" type="text" value="<?if(isset($_GET['zip'])){echo $_GET['zip'];}//end if?>" maxlength="10" size="12" />
		</div>
		<div class="frmReq">
			<label>Categories:</label>
			<table cellpadding="0" cellspacing="0" border="0"><tr>
			<?	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "categories WHERE IsActive = 1 AND PkID > 0 ORDER BY CategoryName");
				$cnt = 0;
				
				while($row = mysql_fetch_row($result)){
					if(($cnt % 2 == 0) && ($cnt > 0) ){echo "</tr><tr>";}	?>
					<td><label for="catID_<?echo $row[0];?>" class="category"><input name="catID[]" id="catID_<?echo $row[0];?>" type="checkbox" value="<?echo $row[0];?>" class="noBorderIE" /><?echo cOut($row[1]);?></label></td>
				<?	$cnt = $cnt + 1;
				}//end while?>
			</tr></table>
		<?	if($cnt > 1){	?>
			<br />
			<label>&nbsp;</label>
			[ <a class="eventMain" href="javascript:;" onclick="checkAllArray('frmEventNewsletter', 'catID[]');">Select All Categories</a> 
			&nbsp;|&nbsp; <a class="eventMain" href="javascript:;" onclick="uncheckAllArray('frmEventNewsletter', 'catID[]');">Deselect All Categories</a> ]
		<?	}//end if	?>
		</div>
		<br />
	</fieldset>
	<br />
	<input name="submit" id="submit" type="submit" value=" Submit Registration " class="button" />
	</form>