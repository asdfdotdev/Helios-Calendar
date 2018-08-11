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
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Checkboxes.js"></script>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function chkFrm(){
	dirty = 0;
	warn = "Your registration could not be completed for the following reason(s):";
	
<?php	if(in_array(4, $hc_captchas)){	?>
			if(document.frmEventNewsletter.proof.value == ''){
				dirty = 1;
				warn = warn + '\n*Authentication Text is Required';
			}//end if
<?php 	}//end if	?>
	
		if(document.frmEventNewsletter.hc_f1.value == ''){
			dirty = 1;
			warn = warn + '\n*First Name is Required';
		}//end if
	
		if(document.frmEventNewsletter.hc_f2.value == ''){
			dirty = 1;
			warn = warn + '\n*Last Name is Required';
		}//end if
		
		if(document.frmEventNewsletter.hc_f3.value == ''){
			dirty = 1;
			warn = warn + '\n*Your Email Address is Required';
		} else {
			if(chkEmail(document.frmEventNewsletter.hc_f3) == 0){
				dirty = 1;
				warn = warn + '\n*Invalid Email Address Format';
			}//end if
		}//end if
		
		if(validateCheckArray('frmEventNewsletter','catID[]',1,'Category') > 0){
			dirty = 1;
			warn = warn + '\n*Category Selection is Required';
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
<?php
	if (isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1,"Sign-Up Successful. Please Activate Your Registration<br />Email Sent With Activation Instructions.");
				break;
				
			case "2" :
				feedback(2,"Email Address Is Already Registered.<br />Please Enter New Email Address");
				break;
				
			case "3":
				feedback(1,"Registration Activated Successfully.<br />You Will Receive Our Next Mailing.");
				break;
				
		}//end switch
	}//end if	?>
	<br />
	To register for our newsletter complete the form below. Select the categories you wish to be notified about, and click 'Submit Registration'.
	Only bold fields are required, additional information is optional.
	<br /><br />
	You will receive a <b>confirmation notice</b> with activation instructions.
	<br /><br />
	<b>Announcements will not be sent until registration is activated.</b>
	<br /><br />
	<form name="frmEventNewsletter" id="frmEventNewsletter" method="post" action="<?echo HC_SignupAction;?>" onsubmit="return chkFrm();">
	<fieldset>
		<legend>Event Newsletter Sign Up</legend>
	<?php 	
		fakeFormFields();
 		if(in_array(4, $hc_captchas)){	?>
			<div class="frmReq">
				<label for="proof">Authentication:</label>
		<?php	buildCaptcha();	?><br />
			</div>
			<div class="frmReq">
				<label>&nbsp;</label>
				<input name="proof" id="proof" type="text" maxlength="8" size="9" value="" />
				<-- Enter Image Text Here
			</div>
	<?php 
 		}//end if	?>
		<div class="frmReq">
			<label for="hc_f1">First Name:</label>
			<input name="hc_f1" id="hc_f1" type="text" value="<?php if(isset($_GET['fname'])){echo $_GET['fname'];}//end if?>" size="20" maxlength="50" />
		</div>
		<div class="frmReq">
			<label for="hc_f2">Last Name:</label>
			<input name="hc_f2" id="hc_f2" type="text" value="<?php if(isset($_GET['lname'])){echo $_GET['lname'];}//end if?>" size="20" maxlength="50" />
		</div>
		<div class="frmReq">
			<label for="hc_f3">Email:</label>
			<input name="hc_f3" id="hc_f3" type="text" value="" size="30" maxlength="75" />
		</div>
		<div class="frmOpt">
			<label for="occupation">Occupation:</label>
	<?php	include('includes/selectOccupation.php');?>
		</div>
		<div class="frmOpt">
			<label for="hc_fa">Birth Year:</label>
			<select name="hc_fa" id="hc_fa">
			<option value="0">[Select a Year]</option>
	<?php	$yearSU = date("Y") - 13;
			for($x=0;$x<=80;$x++){
				echo "<option value=\"" . ($yearSU - $x) . "\">" . ($yearSU - $x) . "</option>";
			}//end for	?>
			</select>
		</div>
		<div class="frmOpt">
			<label for="hc_fb">Gender:</label>
			<select name="hc_fb" id="hc_fb">
				<option value="0">[Select Gender]</option>
				<option value="1">Female</option>
				<option value="2">Male</option>
			</select>
		</div>
		<div class="frmOpt">
			<label for="hc_fc">Referral:</label>
			<select name="hc_fc" id="hc_fc">
				<option value="0">[Select How You Found Us]</option>
				<option value="1">Advertisement (Web)</option>
				<option value="2">Advertisement (Other)</option>
				<option value="3">Another Site (Event Content Syndication)</option>
				<option value="4">Another Site (Link)</option>
				<option value="5">Friend (Event Emailed By)</option>
				<option value="6">Friend (Other)</option>
				<option value="7">Search Engine</option>
				
			</select>
		</div>
		<div class="frmOpt">
			<label for="hc_f4">Postal Code:</label>
			<input name="hc_f4" id="hc_f4" type="text" value="<?php if(isset($_GET['zip'])){echo $_GET['zip'];}//end if?>" maxlength="10" size="12" />
		</div>
		<div class="frmReq">
			<label>Categories:</label>
			<table cellpadding="0" cellspacing="0" border="0"><tr>
			<?php 
				$result = doQuery("SELECT * FROM " . HC_TblPrefix . "categories WHERE IsActive = 1 AND PkID > 0 ORDER BY CategoryName");
				$cnt = 0;
				
				while($row = mysql_fetch_row($result)){
					if(($cnt % 2 == 0) && ($cnt > 0) ){echo "</tr><tr>";}	?>
					<td><label for="catID_<?php echo $row[0];?>" class="category"><input name="catID[]" id="catID_<?php echo $row[0];?>" type="checkbox" value="<?php echo $row[0];?>" class="noBorderIE" /><?php echo cOut($row[1]);?></label></td>
				<?php 
					$cnt = $cnt + 1;
				}//end while?>
			</tr></table>
	<?php 	if($cnt > 1){	?>
			<br />
			<label>&nbsp;</label>
			[ <a class="eventMain" href="javascript:;" onclick="checkAllArray('frmEventNewsletter', 'catID[]');">Select All Categories</a> 
			&nbsp;|&nbsp; <a class="eventMain" href="javascript:;" onclick="uncheckAllArray('frmEventNewsletter', 'catID[]');">Deselect All Categories</a> ]
	<?php 	}//end if	?>
		</div>
	</fieldset>
	<br />
	<input name="submit" id="submit" type="submit" value=" Submit Registration " class="button" />
	</form>