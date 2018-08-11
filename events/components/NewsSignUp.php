<?php
/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development, LLC.
 * @link http://www.refreshmy.com
 * @copyright (C) 2004-2011 Refresh Web Development
 * @license http://www.helioscalendar.com/license.html
 * @package Helios Calendar
 */
	include($hc_langPath . $_SESSION['LangSet'] . '/public/register.php');

	if(isset($_GET['s'])){?>
		<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Email.js"></script>
		<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/ajxOutput.js"></script>
		<script language="JavaScript" type="text/JavaScript">
		//<!--
		function testCAPTCHA(){
			if(document.getElementById('proof').value != ''){
				var qStr = 'CaptchaCheck.php?capEntered=' + document.getElementById('proof').value;
				ajxOutput(qStr, 'capChk', '<?php echo CalRoot;?>');
			} else {
				alert('<?php echo $hc_lang_register['Valid01'];?>');
			}//end if
		}//end testCAPTCHA()
		function chkFrm(){
		dirty = 0;
		warn = "<?php echo $hc_lang_register['Valid03'];?>";

		<?php	captchaValidation('4');?>
			
			if(document.getElementById('hc_fz').value == ''){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_register['Valid16'];?>';
			} else {
				if(chkEmail(document.getElementById('hc_fz')) == 0){
					dirty = 1;
					warn = warn + '\n<?php echo $hc_lang_register['Valid17'];?>';
				}//end if
			}//end if

			if(dirty > 0){
				alert(warn + '\n\n<?php echo $hc_lang_register['Valid19'];?>');
				return false;
			} else {
				document.getElementById('submit').disabled = true;
				document.getElementById('submit').value = '<?php echo $hc_lang_core['Sending'];?>';
				return true;
			}//end if
		}//end chkFrm()
		//-->
		</script>
	<?php
		echo '<br />';
		if(isset($_GET['msg'])){
			switch ($_GET['msg']){
				case "1" :
					feedback(1,$hc_lang_register['Feed03']);
					break;
				case "2" :
					feedback(2,$hc_lang_register['Feed05']);
					break;
			}//end switch
		}//end if
		echo '<p>' . $hc_lang_register['EditInstruct'] . '</p>';
		echo '<form name="frmEventNewsletter" id="frmEventNewsletter" method="post" action="' . CalRoot . '/components/NewsEditAction.php" onsubmit="return chkFrm();">';

		fakeFormFields();

		if($hc_cfg65 > 0 && in_array(4, $hc_captchas)){
			echo '<fieldset>';
			echo '<legend>' . $hc_lang_register['Authentication'] . '</legend>';
			buildCaptcha();
			echo '</fieldset><br />';
		}//end if
		echo '<fieldset>';
		echo '<legend>' . $hc_lang_register['RequestLink'] . '</legend>';
		echo '<div class="frmOpt"><label for="hc_fz">' . $hc_lang_register['Email'] . '</label>';
		echo '<input name="hc_fz" id="hc_fz" type="text" size="35" maxlength="50" value="" />';
		echo '</div>';
		echo '<div class="frmOpt"><label for="hc_fy">' . $hc_lang_register['IWant'] . '</label>';
		echo '<select name="hc_fy" id"hc_fy">';
		echo '<option value="0">' . $hc_lang_register['IWant0'] . '</option>';
		echo '<option value="1">' . $hc_lang_register['IWant1'] . '</option>';
		echo '</select>';
		echo '</div></fieldset><br />';
		echo '<input type="submit" name="submit" id="submit" value="  ' . $hc_lang_register['SendEditReg'] . '  " class="button" />';
		echo '</form>';
	} elseif(isset($_GET['d'])){
		echo '<br /><div style="min-height:250px;">';
		$g = cIn(strip_tags($_GET['d']));
		$result = doQuery("SELECT PkID FROM " . HC_TblPrefix . "subscribers WHERE GUID = '" . $g . "' AND GUID != '' AND IsConfirm = 1");
		if(hasRows($result)){
			echo '<form name="frmEventNewsletter" id="frmEventNewsletter" method="post" action="' . CalRoot . '/components/NewsEditAction.php" onsubmit="return chkFrm();">';
			echo '<input name="dID" id="dID" type="hidden" value="' . $g . '" />';
			echo '<fieldset><legend>' . $hc_lang_register['DeleteLabel'] . '</legend>';
			echo '<div class="frmOpt">' . $hc_lang_register['DeleteNotice'] . '</div>';
			echo '</fieldset>';
			echo '<br /><input type="submit" name="submit" id="submit" value="  ' . $hc_lang_register['CancelReg'] . '  " class="button" />';
			echo '</form>';
		}//end if
		echo '</div>';
	} elseif(isset($_GET['t']) && is_numeric($_GET['t'])){
		$t = cIn(strip_tags($_GET['t']));
		echo '<div style="min-height:250px;">';
		switch($t){
			case 1:
				echo '<br />' . $hc_lang_register['ThankYou1'];
				break;
			case 3:
				echo '<br />' . $hc_lang_register['ThankYou3'];
				break;
			case 4:
				echo '<br />' . $hc_lang_register['ThankYou4'];
				break;
			case 5:
				echo '<br />' . $hc_lang_register['ThankYou5'];
				break;
			default:
				echo '<br />' . $hc_lang_register['ThankYou2'];
				break;
		}//end switch
		echo '</div>';
	} else {
		$g = (isset($_GET['u']) && $_GET['u'] != '') ? cIn(strip_tags($_GET['u'])) : '';
		$result = doQuery("SELECT * FROM " . HC_TblPrefix . "subscribers WHERE GUID = '" . $g . "' AND GUID != '' AND IsConfirm = 1");
		if(hasRows($result)){
			$submit = $hc_lang_register['UpdateReg'];
			$uID = mysql_result($result,0,0);
			$firstname = mysql_result($result,0,1);
			$lastname = mysql_result($result,0,2);
			$email = mysql_result($result,0,3);
			$occupation = mysql_result($result,0,4);
			$zipcode = mysql_result($result,0,5);
			$addedby = mysql_result($result,0,8);
			$birthyear = mysql_result($result,0,11);
			$gender = mysql_result($result,0,12);
			$referral = mysql_result($result,0,13);
			$format = mysql_result($result,0,14);
		} else {
			$submit = $hc_lang_register['SubmitReg'];
			$uID = $occupation = 0;
			$firstname = $lastname = $email = $zipcode = $birthyear = $gender = $referral = $format = '';
		}//end if?>
		<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Email.js"></script>
		<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Checkboxes.js"></script>
		<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/ajxOutput.js"></script>
		<script language="JavaScript" type="text/JavaScript">
		//<!--
		function testCAPTCHA(){
			if(document.getElementById('proof').value != ''){
				var qStr = 'CaptchaCheck.php?capEntered=' + document.getElementById('proof').value;
				ajxOutput(qStr, 'capChk', '<?php echo CalRoot;?>');
			} else {
				alert('<?php echo $hc_lang_register['Valid01'];?>');
			}//end if
		}//end testCAPTCHA()

		function chkFrm(){
		dirty = 0;
		warn = "<?php echo $hc_lang_register['Valid13'];?>";

		<?php	captchaValidation('4');?>

			if(document.getElementById('hc_f1').value == ''){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_register['Valid14'];?>';
			}//end if

			if(document.getElementById('hc_f2').value == ''){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_register['Valid15'];?>';
			}//end if

			if(document.getElementById('hc_f3').value == ''){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_register['Valid16'];?>';
			} else {
				if(chkEmail(document.getElementById('hc_f3')) == 0){
					dirty = 1;
					warn = warn + '\n<?php echo $hc_lang_register['Valid17'];?>';
				}//end if
			}//end if

			if(document.getElementById('hc_fa').value == 0){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_register['Valid20'];?>';
			}//end if

			if(validateCheckArray('frmEventNewsletter','catID[]',1,'Category') > 0){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_register['Valid18'];?>';
			}//end if

			if(dirty > 0){
				alert(warn + '\n\n<?php echo $hc_lang_register['Valid19'];?>');
				return false;
			} else {
				document.getElementById('submit').disabled = true;
				document.getElementById('submit').value = '<?php echo $hc_lang_core['Sending'];?>';
				return true;
			}//end if
		}//end chkFrm()
		//-->
		</script>
	<?php
		echo '<br />';
		if(isset($_GET['msg'])){
			switch ($_GET['msg']){
				case "1" :
					feedback(2,$hc_lang_register['Feed04']);
					break;
			}//end switch
		}//end if
		
		echo $hc_lang_register['SubInstruct'];?>
		<form name="frmEventNewsletter" id="frmEventNewsletter" method="post" action="<?php echo CalRoot . '/components/NewsSignUpAction.php'?>" onsubmit="return chkFrm();">
		<input name="uID" id="uID" type="hidden" value="<?php echo $uID;?>" />
		<input name="gID" id="gID" type="hidden" value="<?php echo $g;?>" />
	<?php
		if($hc_cfg65 > 0 && in_array(4, $hc_captchas)){
			echo '<fieldset>';
			echo '<legend>' . $hc_lang_register['Authentication'] . '</legend>';
			buildCaptcha();
			echo '</fieldset>';
		}//end if	?>
		<br />
		<fieldset>
			<legend><?php echo $hc_lang_register['Subscriber'];?></legend>
			<div class="frmOpt">
				<label>&nbsp;</label>
				<b><?php echo $hc_lang_register['SubscriberLabel'];?></b>
			</div>
			<div class="frmReq">
				<label for="hc_f1"><?php echo $hc_lang_register['FName'];?></label>
				<input name="hc_f1" id="hc_f1" type="text" size="20" maxlength="50" value="<?php echo $firstname;?>" />
			</div>
			<div class="frmReq">
				<label for="hc_f2"><?php echo $hc_lang_register['LName'];?></label>
				<input name="hc_f2" id="hc_f2" type="text" size="30" maxlength="50" value="<?php echo $lastname;?>" />
			</div>
			<div class="frmReq">
				<label for="hc_f3"><?php echo $hc_lang_register['Email'];?></label>
				<input name="hc_f3" id="hc_f3" type="text" size="45" maxlength="75" value="<?php echo $email;?>" />
			</div>
			<div class="frmReq">
				<label for="hc_fa"><?php echo $hc_lang_register['Birth'];?></label>
				<select name="hc_fa" id="hc_fa">
				<option value="0"><?php echo $hc_lang_register['Birth0'];?></option>
		<?php	$yearSU = date("Y") - 14;
				for($x=0;$x<=80;$x++){
					$year = $yearSU - $x;
					echo ($year == $birthyear) ? '<option selected="selected" value="' . $year . '">' : '<option value="' . $year . '">';
					echo $year . '</option>';
				}//end for?>
				</select>
			</div>
			<div class="frmOpt">
				<label for="occupation"><?php echo $hc_lang_register['Occupation'];?></label>
		<?php 	include($hc_langPath . $_SESSION['LangSet'] . '/' . $hc_lang_config['OccupationFile']);?>
			</div>
			<div class="frmOpt">
				<label for="hc_fb"><?php echo $hc_lang_register['Gender'];?></label>
				<select name="hc_fb" id="hc_fb">
					<option value="0"><?php echo $hc_lang_register['Gender0'];?></option>
					<option <?php if($gender == 1){echo 'selected="selected"';}?> value="1"><?php echo $hc_lang_register['GenderF'];?></option>
					<option <?php if($gender == 2){echo 'selected="selected"';}?> value="2"><?php echo $hc_lang_register['GenderM'];?></option>
				</select>
			</div>
			<div class="frmOpt">
				<label for="hc_fc"><?php echo $hc_lang_register['Referral'];?></label>
				<select name="hc_fc" id="hc_fc">
					<option value="0"><?php echo $hc_lang_register['Referral0'];?></option>
					<option <?php if($referral == 1){echo 'selected="selected"';}?> value="1"><?php echo $hc_lang_register['Referral1'];?></option>
					<option <?php if($referral == 2){echo 'selected="selected"';}?> value="2"><?php echo $hc_lang_register['Referral2'];?></option>
					<option <?php if($referral == 3){echo 'selected="selected"';}?> value="3"><?php echo $hc_lang_register['Referral3'];?></option>
					<option <?php if($referral == 4){echo 'selected="selected"';}?> value="4"><?php echo $hc_lang_register['Referral4'];?></option>
					<option <?php if($referral == 5){echo 'selected="selected"';}?> value="5"><?php echo $hc_lang_register['Referral5'];?></option>
					<option <?php if($referral == 6){echo 'selected="selected"';}?> value="6"><?php echo $hc_lang_register['Referral6'];?></option>
					<option <?php if($referral == 7){echo 'selected="selected"';}?> value="7"><?php echo $hc_lang_register['Referral7'];?></option>
				</select>
			</div>
			<div class="frmOpt">
				<label for="hc_f4"><?php echo $hc_lang_register['Postal'];?></label>
				<input name="hc_f4" id="hc_f4" type="text" size="12" maxlength="10" value="<?php echo $zipcode;?>" />
			</div>
		</fieldset>
		<br />
		<fieldset>
			<legend><?php echo $hc_lang_register['Subscription'];?></legend>
			<div class="frmOpt">
				<label>&nbsp;</label>
				<b><?php echo $hc_lang_register['CategoriesLabel'];?></b>
			</div>
			<div class="frmOpt">
				<label><?php echo $hc_lang_register['Categories'];?></label>
		<?php 	$query = ($uID != '') ?
						"SELECT c.PkID, c.CategoryName, c.ParentID, c.CategoryName as Sort, uc.UserID as Selected
						FROM " . HC_TblPrefix . "categories c
							LEFT JOIN " . HC_TblPrefix . "categories c2 ON (c.PkID = c2.PkID)
							LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (c.PkID = ec.CategoryID)
							LEFT JOIN " . HC_TblPrefix . "subscriberscategories uc ON (uc.CategoryID = c.PkID AND uc.UserID = '" . $uID . "')
						WHERE c.ParentID = 0 AND c.IsActive = 1
						GROUP BY c.PkID
						UNION
						SELECT c.PkID, c.CategoryName, c.ParentID, c2.CategoryName as Sort, uc.UserID as Selected
						FROM " . HC_TblPrefix . "categories c
							LEFT JOIN " . HC_TblPrefix . "categories c2 ON (c.ParentID = c2.PkID)
							LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (c.PkID = ec.CategoryID)
							LEFT JOIN " . HC_TblPrefix . "subscriberscategories uc ON (uc.CategoryID = c.PkID AND uc.UserID = '" . cIn($uID) . "')
						WHERE c.ParentID > 0 AND c.IsActive = 1
						GROUP BY c.PkID
						ORDER BY Sort, ParentID, CategoryName" : NULL;
				getCategories('frmEventNewsletter', 3, $query);?>
			</div>
			<div class="frmOpt">
				<label for="format"><?php echo $hc_lang_register['LinkFormat'];?></label>
				<select name="format" id="format">
					<option <?php if($format == 0){echo 'selected="selected"';}?> value="0"><?php echo $hc_lang_register['LinkFormat0'];?></option>
					<option <?php if($format == 1){echo 'selected="selected"';}?> value="1"><?php echo $hc_lang_register['LinkFormat1'];?></option>
				</select>
			</div>
		</fieldset>
		<br />
	<?php
		$result = doQuery("SELECT mg.PkID, mg.Name, mg.Description, sg.UserID
						FROM " . HC_TblPrefix . "mailgroups mg
							LEFT JOIN " . HC_TblPrefix . "subscribersgroups sg ON (mg.PkID = sg.GroupID AND sg.UserID = '" . $uID . "')
						WHERE mg.IsActive = 1 AND mg.PkID > 1 AND mg.IsPublic = 1
						ORDER BY Name");
		if(hasRows($result)){
			echo '<fieldset>';
			echo '<legend>' . $hc_lang_register['GroupLabel'] . '</legend>';
			echo '<div class="frmOpt">';
			echo '<label for="grpID_1" class="group"><input disabled="disabled" checked="checked" name="grpID[]" id="grpID_1" type="checkbox" value="1" class="noBorderIE" />';
			echo '<b>' . $hc_lang_register['GenericNews'] . '</b><br />' . $hc_lang_register['GenericNewsDesc'] . '</label>';
			$cnt = 0;
			while($row = mysql_fetch_row($result)){
				echo '<label for="grpID_' . $row[0];
				echo ($cnt % 2 == 0) ? '" class="groupHL"><input ' : '" class="group"><input ';
				echo 'name="grpID[]" id="grpID_' . $row[0] . '" type="checkbox" value="' . $row[0] . '" class="noBorderIE" ';
				echo ($row[3] == $uID && $uID > 0) ? 'checked="checked" ' : '';
				echo '/>' . cOut('<b>' . $row[1] . '</b><br />' . $row[2]) . '</label>';
				++$cnt;
			}//end while
			echo '</div></fieldset><br />';
		}//end if?>
		<input type="submit" name="submit" id="submit" value="  <?php echo $submit;?>  " class="button" />
		</form>
<?php
	}//end if
?>