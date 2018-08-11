<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/public/register.php');	?>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Email.js"></script>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Checkboxes.js"></script>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/ajxOutput.js"></script>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function testCAPTCHA(){
		if(document.frmEventNewsletter.proof.value != ''){
			var qStr = 'CaptchaCheck.php?capEntered=' + document.frmEventNewsletter.proof.value;
			ajxOutput(qStr, 'capChk', '<?php echo CalRoot;?>');
		} else {
			alert('<?php echo $hc_lang_register['Valid01'];?>');
		}//end if
	}//end testCAPTCHA()
	
	function chkFrm(){
	dirty = 0;
	warn = "<?php echo $hc_lang_register['Valid13'];?>";
	
<?php 	if(in_array(4, $hc_captchas)){	?>
			if(document.frmEventNewsletter.proof.value == ''){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_register['Valid03'];?>';
			}//end if
<?php 	}//end if	?>
	
		if(document.frmEventNewsletter.hc_f1.value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_register['Valid14'];?>';
		}//end if
	
		if(document.frmEventNewsletter.hc_f2.value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_register['Valid15'];?>';
		}//end if
		
		if(document.frmEventNewsletter.hc_f3.value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_register['Valid16'];?>';
		} else {
			if(chkEmail(document.frmEventNewsletter.hc_f3) == 0){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_register['Valid17'];?>';
			}//end if
		}//end if
		
		if(validateCheckArray('frmEventNewsletter','catID[]',1,'Category') > 0){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_register['Valid18'];?>';
		}//end if
		
		if(dirty > 0){
			alert(warn + '\n\n<?php echo $hc_lang_register['Valid19'];?>');
			return false;
		} else {
			return true;
		}//end if
		
	}//end chkFrm()
	//-->
	</script>
<?php
	if(isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1,$hc_lang_register['Feed03']);
				break;
			case "2" :
				feedback(2,$hc_lang_register['Feed04']);
				break;
			case "3":
				feedback(1,$hc_lang_register['Feed05']);
				break;
		}//end switch
	}//end if	
	
	echo "<br />";
	echo $hc_lang_register['SubInstruct'];
	echo "<br /><br />";?>
	<form name="frmEventNewsletter" id="frmEventNewsletter" method="post" action="<?php echo CalRoot . '/components/SignUpAction.php'?>" onsubmit="return chkFrm();">
<?php
	if(in_array(4, $hc_captchas)){	?>
	<fieldset>
		<legend><?php echo $hc_lang_register['Authentication'];?></legend>
		<?php echo $hc_lang_register['CannotRead'];?><br /><br />
		<div class="frmReq">
			<label for="proof">&nbsp;</label>
	<?php	buildCaptcha();	?><br />
		</div>
		<div class="frmReq">
			<label for="proof"><?php echo $hc_lang_register['ImageText'];?></label>
			<div style="float:left;padding-right:5px;"><input onblur="testCAPTCHA();" name="proof" id="proof" type="text" maxlength="8" size="8" value="" /></div>
			<div id="capChk"><a href="javascript:;" onclick="testCAPTCHA();" class="eventMain"><?php echo $hc_lang_register['Confirm'];?></a></div>
		</div>
	</fieldset>
	<br />
<?php
	}//end if	?>
	<fieldset>
		<legend><?php echo $hc_lang_register['SignUp'];?></legend>
<?php 	fakeFormFields();?>
		<div class="frmReq">
			<label for="hc_f1"><?php echo $hc_lang_register['FirstName'];?></label>
			<input name="hc_f1" id="hc_f1" type="text" value="<?php if(isset($_GET['fname'])){echo htmlspecialchars($_GET['fname']);}//end if?>" size="20" maxlength="50" />
		</div>
		<div class="frmReq">
			<label for="hc_f2"><?php echo $hc_lang_register['LastName'];?></label>
			<input name="hc_f2" id="hc_f2" type="text" value="<?php if(isset($_GET['lname'])){echo htmlspecialchars($_GET['lname']);}//end if?>" size="25" maxlength="50" />
		</div>
		<div class="frmReq">
			<label for="hc_f3"><?php echo $hc_lang_register['Email'];?></label>
			<input name="hc_f3" id="hc_f3" type="text" value="" size="30" maxlength="75" />
		</div>
		<div class="frmOpt">
			<label for="occupation"><?php echo $hc_lang_register['Occupation'];?></label>
	<?php	include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/' . $hc_lang_config['OccupationFile']);?>
		</div>
		<div class="frmOpt">
			<label for="hc_fa"><?php echo $hc_lang_register['BirthYear'];?></label>
			<select name="hc_fa" id="hc_fa">
			<option value="0"><?php echo $hc_lang_register['BirthYear0'];?></option>
	<?php	$yearSU = date("Y") - 13;
			for($x=0;$x<=80;$x++){
				echo "<option value=\"" . ($yearSU - $x) . "\">" . ($yearSU - $x) . "</option>";
			}//end for	?>
			</select>
		</div>
		<div class="frmOpt">
			<label for="hc_fb"><?php echo $hc_lang_register['Gender'];?></label>
			<select name="hc_fb" id="hc_fb">
				<option value="0"><?php echo $hc_lang_register['Gender0'];?></option>
				<option value="1"><?php echo $hc_lang_register['GenderF'];?></option>
				<option value="2"><?php echo $hc_lang_register['GenderM'];?></option>
			</select>
		</div>
		<div class="frmOpt">
			<label for="hc_fc"><?php echo $hc_lang_register['Referral'];?></label>
			<select name="hc_fc" id="hc_fc">
				<option value="0"><?php echo $hc_lang_register['Referral0'];?></option>
				<option value="1"><?php echo $hc_lang_register['Referral1'];?></option>
				<option value="2"><?php echo $hc_lang_register['Referral2'];?></option>
				<option value="3"><?php echo $hc_lang_register['Referral3'];?></option>
				<option value="4"><?php echo $hc_lang_register['Referral4'];?></option>
				<option value="5"><?php echo $hc_lang_register['Referral5'];?></option>
				<option value="6"><?php echo $hc_lang_register['Referral6'];?></option>
				<option value="7"><?php echo $hc_lang_register['Referral7'];?></option>
			</select>
		</div>
		<div class="frmOpt">
			<label for="hc_f4"><?php echo $hc_lang_register['Postal'];?></label>
			<input name="hc_f4" id="hc_f4" type="text" value="<?php if(isset($_GET['zip'])){echo $_GET['zip'];}//end if?>" maxlength="10" size="12" />
		</div>
		<div class="frmReq">
			<label><?php echo $hc_lang_register['Categories'];?></label>
	<?php 	getCategories('frmEventNewsletter', 3);?>
		</div>
	</fieldset>
	<br />
	<input name="submit" id="submit" type="submit" value="<?php echo $hc_lang_register['SubmitReg'];?>" class="button" />
	</form>