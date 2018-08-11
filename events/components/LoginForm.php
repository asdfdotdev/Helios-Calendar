<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/public/login.php');
	
	if(isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1":
				feedback(2,$hc_lang_login['Feed01']);
				break;
			case "2":
				feedback(2,$hc_lang_login['Feed02']);
				break;
			case "3":
				feedback(2,$hc_lang_login['Feed03']);
				break;
		}//end switch
	}//end if	?>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/ajxOutput.js"></script>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function chkFrm(){
		dirty = 0;
		warn = '<?php echo $hc_lang_login['Valid06'];?>';
		
<?php	if(in_array(5, $hc_captchas)){	?>
			if(document.hc_login.proof.value == ''){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_login['Valid02'];?>';
			}//end if
<?php 	}//end if	?>
		
		if(document.hc_login.myOID.value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_login['Valid03'];?>';
		}//end if
		
		if(dirty > 0){
			alert(warn + '\n\n<?php echo $hc_lang_login['Valid04'];?>');
			return false;
		} else {
			return true;
		}//end if
	}//end chkFrm()
	
	function testCAPTCHA(){
		if(document.hc_login.proof.value != ''){
			var qStr = 'CaptchaCheck.php?capEntered=' + document.hc_login.proof.value;
			ajxOutput(qStr, 'capChk', '<?php echo CalRoot;?>');
		} else {
			alert('<?php echo $hc_lang_login['Valid05'];?>');
		}//end if
	}//end testCAPTCHA()

	function doOpenID(sendTo){
		document.getElementById('myOID').value = sendTo;
		if(chkFrm()){
			document.hc_login.submit();
		}//end if
		return false;
	}//end doOpenID
	//-->
	</script>
	<form name="hc_login" id="hc_login" method="post" action="<?php echo CalRoot;?>/openid/LoginAction.php" onsubmit="return chkFrm();">
	<input type="hidden" name="policies[]" value="http://schemas.openid.net/pape/policies/2007/06/multi-factor-physical" />
	<input type="hidden" name="policies[]" value="http://schemas.openid.net/pape/policies/2007/06/multi-factor" />
	<input type="hidden" name="policies[]" value="http://schemas.openid.net/pape/policies/2007/06/phishing-resistant" />
<?php	if(in_array(5, $hc_captchas)){	?>
		<fieldset>
			<legend><?php echo $hc_lang_login['Authentication'];?></legend>
			<?php echo $hc_lang_login['CannotRead'];?><br /><br />
			<div class="frmReq">
				<label for="proof">&nbsp;</label>
		<?php	buildCaptcha();	?><br />
			</div>
			<div class="frmReq">
				<label><?php echo $hc_lang_login['ImageText'];?></label>
				<div style="float:left;padding-right:5px;"><input onblur="testCAPTCHA();" name="proof" id="proof" type="text" maxlength="8" size="8" value="" /></div>
				<div id="capChk"><a href="javascript:;" onclick="testCAPTCHA();" class="eventMain"><?php echo $hc_lang_login['Confirm'];?></a></div>
			</div>
		</fieldset><br />
<?php	}//end if	?>
		<fieldset>
		<legend><?php echo $hc_lang_login['LoginLabel'];?></legend>
			<div class="frmOpt">
				<label>&nbsp;</label>
				<?php echo '<a href="' . CalRoot . '/index.php?com=about" class="eventMain">' . $hc_lang_login['LoginLink'] . '</a>';?>
			</div>
			<div class="frmOpt">
				<label for="myOID"><?php echo $hc_lang_login['Identity'];?></label>
				<input name="myOID" id="myOID" value="" size="40" maxlength="250" type="text" class="openID" />
			</div>
			<div class="frmOpt">
				<label><?php echo $hc_lang_login['Or'];?></label>
				<div style="float:left;width:70%;">
				<a href="javascript:;" onclick="doOpenID('https://www.google.com/accounts/o8/id');"><img src="<?php echo CalRoot;?>/images/share/google.png" width="16" height="16" alt="" style="vertical-align:middle;" /></a> <a href="javascript:;" onclick="doOpenID('https://www.google.com/accounts/o8/id');" class="oidLogin"><?php echo $hc_lang_login['Google'];?></a><br />
				<a href="javascript:;" onclick="doOpenID('http://yahoo.com');"><img src="<?php echo CalRoot;?>/images/cal/yahoo.png" width="16" height="16" alt="" style="vertical-align:middle;" /></a> <a href="javascript:;" onclick="doOpenID('http://yahoo.com');" class="oidLogin"><?php echo $hc_lang_login['Yahoo'];?></a><br />
				<a href="javascript:;" onclick="doOpenID('http://www.myspace.com');"><img src="<?php echo CalRoot;?>/images/share/myspace.png" width="16" height="16" alt="" style="vertical-align:middle;" /></a> <a href="javascript:;" onclick="doOpenID('http://www.myspace.com');" class="oidLogin"><?php echo $hc_lang_login['MySpaceID'];?></a>
				</div>
			</div>
		</fieldset>
		<br />
		<input name="loginButton" id="loginButton" type="submit" value="<?php echo $hc_lang_login['LoginButton'];?>" />
	</form>