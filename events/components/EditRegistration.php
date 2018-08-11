<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2008 Refresh Web Development [www.RefreshMy.com]
	
	Developed By: Chris Carlevato <support@refreshmy.com>
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar EUL found at www.HeliosCalendar.com/license.pdf
*/
	include($hc_langPath . $_SESSION['LangSet'] . '/public/register.php');
	
	if(isset($_GET['guid'])){
		$result = doQuery("SELECT * FROM " . HC_TblPrefix . "users WHERE GUID = '" . cIn($_GET['guid']) . "'");
		
		if(hasRows($result)){
			$uID = cOut(mysql_result($result,0,0));
			$fname = cOut(mysql_result($result,0,1));
			$lname = cOut(mysql_result($result,0,2));
			$email = cOut(mysql_result($result,0,3));
			$oID = cOut(mysql_result($result,0,4));
			$zip = cOut(mysql_result($result,0,5));
			$guid = cOut($_GET['guid']);
			$bYear = cOut(mysql_result($result,0,11));
			$gender = cOut(mysql_result($result,0,12));
			$referral = cOut(mysql_result($result,0,13));?>
			
			<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Email.js"></script>
			<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Checkboxes.js"></script>
			<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/ajxOutput.js"></script>
			<script language="JavaScript" type="text/JavaScript">
			//<!--
			function testCAPTCHA(){
				if(document.frmEventReg.proof.value != ''){
					var qStr = 'CaptchaCheck.php?capEntered=' + document.frmEventReg.proof.value;
					ajxOutput(qStr, 'capChk', '<?php echo CalRoot;?>');
				} else {
					alert('<?php echo $hc_lang_register['Valid01'];?>');
				}//end if
			}//end testCAPTCHA()
			
			function chkFrm(){
			dirty = 0;
			warn = "<?php echo $hc_lang_register['Valid02'];?>";
		
		<?php 	if(in_array(4, $hc_captchas)){	?>
					if(document.frmEventReg.proof.value == ''){
						dirty = 1;
						warn = warn + '\n<?php echo $hc_lang_register['Valid03'];?>';
					}//end if
		<?php 	}//end if	?>
			
				if(document.frmEventReg.hc_f1.value == ''){
					dirty = 1;
					warn = warn + '\n<?php echo $hc_lang_register['Valid04'];?>';
				}//end if
			
				if(document.frmEventReg.hc_f2.value == ''){
					dirty = 1;
					warn = warn + '\n<?php echo $hc_lang_register['Valid05'];?>';
				}//end if
				
				if(validateCheckArray('frmEventReg','catID[]',1,'Category') > 0){
					dirty = 1;
					warn = warn + '\n<?php echo $hc_lang_register['Valid06'];?>';
				}//end if
				
				if(dirty > 0){
					alert(warn + '\n\n<?php echo $hc_lang_register['Valid07'];?>');
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
						feedback(1, $hc_lang_register['Feed01']);
						break;
				}//end switch
			}//end if	
			echo "<br />";
			echo $hc_lang_register['Notice'];?>
			<br /><br />
			<form name="frmEventReg" id="frmEventReg" method="post" action="<?php echo HC_EditRegisterAction;?>" onsubmit="return chkFrm();">
			<input type="hidden" name="guid" id="guid" value="<?php echo $guid;?>" />
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
					<label><?php echo $hc_lang_register['ImageText'];?></label>
					<div style="float:left;padding-right:5px;"><input onblur="testCAPTCHA();" name="proof" id="proof" type="text" maxlength="8" size="8" value="" /></div>
					<div id="capChk"><a href="javascript:;" onclick="testCAPTCHA();" class="eventMain"><?php echo $hc_lang_register['Confirm'];?></a></div>
				</div>
			</fieldset>
			<br />
		<?php
			}//end if	?>
			<fieldset>
				<legend><?php echo $hc_lang_register['EditRegLabel'];?></legend>
				<div class="frmReq">
					<label><?php echo $hc_lang_register['Email'];?></label>
					<?php echo $email;?>
				</div>
				<div class="frmReq">
					<label for="hc_f1"><?php echo $hc_lang_register['FirstName'];?></label>
					<input name="hc_f1" id="hc_f1" type="text" size="15" maxlength="50" value="<?php echo $fname;?>" />
				</div>
				<div class="frmReq">
					<label for="hc_f2"><?php echo $hc_lang_register['LastName'];?></label>
					<input name="hc_f2" id="hc_f2" type="text" size="15" maxlength="50" value="<?php echo $lname;?>" />
				</div>
				<div class="frmOpt">
					<label for="occupation"><?php echo $hc_lang_register['Occupation'];?></label>
			<?php	$occupation = $oID;
					include($hc_langPath . $_SESSION['LangSet'] . '/' . $hc_lang_config['OccupationFile']);?>
				</div>
				<div class="frmOpt">
					<label for="hc_fa"><?php echo $hc_lang_register['BirthYear'];?></label>
					<select name="hc_fa" id="hc_fa">
					<option value="0"><?php echo $hc_lang_register['BirthYear0'];?></option>
			<?php	$yearSU = date("Y") - 13;
					for($x=0;$x<=80;$x++){
						if($bYear == ($yearSU - $x)){
							echo "<option selected=\"selected\" value=\"" . ($yearSU - $x) . "\">" . ($yearSU - $x) . "</option>";
						} else {
							echo "<option value=\"" . ($yearSU - $x) . "\">" . ($yearSU - $x) . "</option>";
						}//end if
					}//end for	?>
					</select>
				</div>
				<div class="frmOpt">
					<label for="hc_fb"><?php echo $hc_lang_register['Gender'];?></label>
					<select name="hc_fb" id="hc_fb">
						<option value="0"><?php echo $hc_lang_register['Gender0'];?></option>
						<option <?php if($gender == 1){echo "selected=\"selected\"";}?> value="1"><?php echo $hc_lang_register['GenderF'];?></option>
						<option <?php if($gender == 2){echo "selected=\"selected\"";}?> value="2"><?php echo $hc_lang_register['GenderM'];?></option>
					</select>
				</div>
				<div class="frmOpt">
					<label for="hc_fc"><?php echo $hc_lang_register['Referral'];?></label>
					<select name="hc_fc" id="hc_fc">
						<option value="0"><?php echo $hc_lang_register['Referral0'];?></option>
						<option <?php if($referral == 1){echo "selected=\"selected\"";}?> value="1"><?php echo $hc_lang_register['Referral1'];?></option>
						<option <?php if($referral == 2){echo "selected=\"selected\"";}?> value="2"><?php echo $hc_lang_register['Referral2'];?></option>
						<option <?php if($referral == 3){echo "selected=\"selected\"";}?> value="3"><?php echo $hc_lang_register['Referral3'];?></option>
						<option <?php if($referral == 4){echo "selected=\"selected\"";}?> value="4"><?php echo $hc_lang_register['Referral4'];?></option>
						<option <?php if($referral == 5){echo "selected=\"selected\"";}?> value="5"><?php echo $hc_lang_register['Referral5'];?></option>
						<option <?php if($referral == 6){echo "selected=\"selected\"";}?> value="6"><?php echo $hc_lang_register['Referral6'];?></option>
						<option <?php if($referral == 7){echo "selected=\"selected\"";}?> value="7"><?php echo $hc_lang_register['Referral7'];?></option>
						
					</select>
				</div>
				<div class="frmOpt">
					<label for="hc_f4"><?php echo $hc_lang_register['Postal'];?></label>
					<input name="hc_f4" id="hc_f4" type="text" value="<?php echo $zip;?>" maxlength="10" size="12" />
				</div>
				<div class="frmReq">
					<label><?php echo $hc_lang_register['Categories'];?></label>
			<?php 	$query = "	SELECT DISTINCT " . HC_TblPrefix . "categories.*, " . HC_TblPrefix . "usercategories.UserID
								FROM " . HC_TblPrefix . "categories 
									LEFT JOIN " . HC_TblPrefix . "usercategories ON (" . HC_TblPrefix . "categories.PkID =" . HC_TblPrefix . "usercategories.CategoryID AND " . HC_TblPrefix . "usercategories.UserID = " . cIn($uID) . ") 
								WHERE " . HC_TblPrefix . "categories.IsActive = 1
								ORDER BY CategoryName";
					getCategories('frmEventReg', 2, $query);?>
				</div>
			</fieldset>
			<br />
			<input type="submit" name="submit" id="submit" value=" <?php echo $hc_lang_register['SaveReg'];?> " class="button" />			
			</form>
<?php
		} else {
			echo "<br /><br />" . $hc_lang_register['InvalidAccount'];
		}//end if
	} else {
		echo "<br /><br />" . $hc_lang_register['InvalidAccount'];
	}//end if	?>