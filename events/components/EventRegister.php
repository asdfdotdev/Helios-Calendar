<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2009 Refresh Web Development [www.RefreshMy.com]
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/public/register.php');
	
	$hourOffset = date("G") + ($hc_cfg35);
	$eID = (isset($_GET['eID']) && is_numeric($_GET['eID'])) ? $_GET['eID'] : 0;
	$result = doQuery("SELECT Title, StartDate, ContactName, ContactEmail FROM " . HC_TblPrefix . "events WHERE PkID = " . cIn($eID) . " AND StartDate >= '" . date("Y-m-d", mktime($hourOffset,0,0,date("m"),date("d"),date("Y"))) . "'");
	
	if(hasRows($result)){	
		if (isset($_GET['msg'])){
			switch ($_GET['msg']){
				case "1" :
				feedback(2, $hc_lang_register['Feed02']);
				break;
			}//end switch
		}//end if	?>
		<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Email.js"></script>
		<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/ajxOutput.js"></script>
		<script language="JavaScript" type="text/JavaScript">
		//<!--
		function testCAPTCHA(){
			if(document.frmEventRegister.proof.value != ''){
				var qStr = 'CaptchaCheck.php?capEntered=' + document.frmEventRegister.proof.value;
				ajxOutput(qStr, 'capChk', '<?php echo CalRoot;?>');
			} else {
				alert('<?php echo $hc_lang_register['Valid01'];?>');
			}//end if
		}//end testCAPTCHA()
		
		function chkFrm(){
		dirty = 0;
		warn = "<?php echo $hc_lang_register['Valid08'];?>";
		
	<?php 	if(in_array(3, $hc_captchas)){	?>
				if(document.frmEventRegister.proof.value == ''){
					dirty = 1;
					warn = warn + '\n<?php echo $hc_lang_register['Valid03'];?>';
				}//end if
	<?php 	}//end if	?>
		
			if(document.frmEventRegister.hc_f1.value == ''){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_register['Valid09'];?>';
			}//end if
			
			if(document.frmEventRegister.hc_f2.value == ''){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_register['Valid10'];?>';
			} else {
				if(chkEmail(document.frmEventRegister.hc_f2) == 0){
					dirty = 1;
					warn = warn + '\n<?php echo $hc_lang_register['Valid11'];?>';
				}//end if
			}//end if
			
			if(dirty > 0){
				alert(warn + '\n<?php echo $hc_lang_register['Valid12'];?>');
				return false;
			} else {
				return true;
			}//end if
		}//end chkregister()
		//-->
		</script>
<?php 	echo '<br />';
		if(!isset($_GET['confirm'])){
			echo $hc_lang_register['RegNotice'];?>
			<br /><br />
			<form id="frmEventRegister" name="frmEventRegister" method="post" action="<?php echo CalRoot;?>/components/EventRegisterAction.php" onsubmit="return chkFrm();">
	<?php 	fakeFormFields();	?>
			<input name="eventID" id="eventID" type="hidden" value="<?php echo $eID;?>" />
	<?php	if(in_array(3, $hc_captchas)){	?>
			<fieldset>
				<legend><?php echo $hc_lang_register['Authentication'];?></legend>
				<?php echo $hc_lang_register['CannotRead'];?><br /><br />
				<div class="frmReq">
					<label for="proof">&nbsp;</label>
			<?php	buildCaptcha();	?><br />
				</div>
				<div class="frmReq">
					<label><?php echo $hc_lang_register['ImageText'];?></label>
					<div style="float:left;padding-right:5px;"><input onblur="testCAPTCHA();" name="proof" id="proof" type="text" maxlength="8" size="8" value="" /></div>
					<div id="capChk"><a href="javascript:;" onclick="testCAPTCHA();" class="eventMain"><?php echo $hc_lang_register['Confirm'];?></a></div>
				</div>
			</fieldset>
			<br />
	<?php	}//end if	?>		
			<fieldset>
				<legend><?php echo $hc_lang_register['EventDetail'];?></legend>
				<div class="frmReq">
					<label><?php echo $hc_lang_register['Event'];?></label>
					<?php echo mysql_result($result,0,0);?>
				</div>
				<div class="frmReq">
					<label><?php echo $hc_lang_register['Date'];?></label>
					<?php echo stampToDate(mysql_result($result,0,1), $hc_cfg14);?>
				</div>
				<div class="frmReq">
					<label><?php echo $hc_lang_register['Contact'];?></label>
			<?php	cleanEmailLink(cOut(mysql_result($result,0,3)));?>
				</div>
			</fieldset>
			<br />
			<fieldset>
				<legend><?php echo $hc_lang_register['YourReg'];?></legend>
				<div class="frmReq">
					<label for="hc_f1"><?php echo $hc_lang_register['Name'];?></label>
					<input name="hc_f1" id="hc_f1" type="text" size="25" maxlength="50" value="<?php if(isset($_GET['name'])){echo $_GET['name'];}//end if?>" />
				</div>
				<div class="frmReq">
					<label for="hc_f2"><?php echo $hc_lang_register['Email'];?></label>
					<input name="hc_f2" id="hc_f2" type="text" size="35" maxlength="75" value="<?php if(isset($_GET['email'])){echo $_GET['email'];}//end if?>" />
				</div>
				<div class="frmOpt">
					<label for="hc_f3"><?php echo $hc_lang_register['Phone'];?></label>
					<input name="hc_f3" id="hc_f3" type="text" size="20" maxlength="25" value="<?php if(isset($_GET['phone'])){echo $_GET['phone'];}//end if?>" />
				</div>
				<div class="frmOpt">
					<label for="hc_f7"><?php echo $hc_lang_register['PartySize'];?></label>
					<select name="hc_f7" id="hc_f7">
					<option value="0"><?php echo $hc_lang_register['Alone'];?></option>
			<?php	for($x=1;$x<=10;$x++){
						echo "<option value=\"" . $x . "\">" . $hc_lang_register['Myself'] . " +" . $x . "</option>";
					}//end for	?>
					</select>
				</div>
				<div class="frmOpt">
					<label for="hc_f4"><?php echo $hc_lang_register['Address'];?></label>
					<input name="hc_f4" id="hc_f4" type="text" size="20" maxlength="75" value="<?php if(isset($_GET['address'])){echo $_GET['address'];}//end if?>" />
				</div>
				<div class="frmOpt">
					<label>&nbsp;</label>
					<input name="hc_f5" id="hc_f5" type="text" size="20" maxlength="75" value="<?php if(isset($_GET['address2'])){echo $_GET['address2'];}//end if?>" />
				</div>
				<div class="frmOpt">
					<label for="hc_f6"><?php echo $hc_lang_register['City'];?></label>
					<input name="hc_f6" id="hc_f6" type="text" size="20" maxlength="50" value="<?php if(isset($_GET['city'])){echo $_GET['city'];}//end if?>" />
				</div>
				<div class="frmOpt">
					<label for="locState"><?php echo $hc_lang_config['RegionLabel'];?></label>
					<?php
						$state = $hc_cfg21;
						if(isset($_GET['state'])){
							$state = $_GET['state'];
						}//end if
						include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/' . $hc_lang_config['RegionFile']);?>
				</div>
				<div class="frmOpt">
					<label for="hc_f8"><?php echo $hc_lang_register['Postal'];?></label>
					<input name="hc_f8" id="hc_f8" type="text" size="11" maxlength="11" value="<?php if(isset($_GET['zip'])){echo $_GET['zip'];}//end if?>" />
				</div>
			</fieldset>
			<br />
			<input name="submit" id="submit" type="submit" value="<?php echo $hc_lang_register['RegisterNow'];?>" class="button" />&nbsp;&nbsp;&nbsp;
			<input name="cancel" id="cancel" type="button" value="<?php echo $hc_lang_register['Cancel'];?>" onclick="document.location.href='<?php echo CalRoot;?>/index.php?com=detail&eID=<?php echo $eID;?>'; return false;" class="button" />
			</form>
<?php 	} else {
			echo $hc_lang_register['RegOk'] . "<br /><br />" . "<a href=\"" . CalRoot . "/index.php?com=detail&eID=" . $eID. "\" class=\"eventMain\">" . $hc_lang_register['ReturnListing'] . "</a>";
 		}//end if
	} else {
		echo '<br /><br />';
		echo $hc_lang_register['NoReg'] . "<br /><br /><a href=\"" . CalRoot . "\" class=\"eventMain\">" . $hc_lang_register['FindEvent'] . "</a>";
	}//end if	?>