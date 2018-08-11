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
	
	$hourOffset = date("G") + ($hc_cfg35);
	$eID = (isset($_GET['eID']) && is_numeric($_GET['eID'])) ? $_GET['eID'] : 0;
	$result = doQuery("SELECT Title, StartDate, StartTime, TBD, ContactName, ContactEmail FROM " . HC_TblPrefix . "events WHERE PkID = " . cIn($eID) . " AND StartDate >= '" . date("Y-m-d", mktime($hourOffset,0,0,date("m"),date("d"),date("Y"))) . "'");
	
	if(hasRows($result)){	
		if (isset($_GET['msg'])){
			switch ($_GET['msg']){
				case "1" :
					feedback(2, $hc_lang_register['Feed02']);
					break;
				case "2" :
					feedback(1, $hc_lang_register['Feed06']);
					break;
			}//end switch
		}//end if	?>
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
		warn = "<?php echo $hc_lang_register['Valid08'];?>";
		
	<?php	captchaValidation('3');?>
		
			if(document.getElementById('hc_f1').value == ''){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_register['Valid09'];?>';
			}//end if
			
			if(document.getElementById('hc_f2').value == ''){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_register['Valid10'];?>';
			} else {
				if(chkEmail(document.getElementById('hc_f2')) == 0){
					dirty = 1;
					warn = warn + '\n<?php echo $hc_lang_register['Valid11'];?>';
				}//end if
			}//end if
			
			if(dirty > 0){
				alert(warn + '\n<?php echo $hc_lang_register['Valid12'];?>');
				return false;
			} else {
				document.getElementById('submit').disabled = true;
				document.getElementById('submit').value = '<?php echo $hc_lang_core['Sending'];?>';
				return true;
			}//end if
		}//end chkregister()
		//-->
		</script>

<?php	echo '<p>' . $hc_lang_register['RegNotice'] . '</p>';?>
		
		<form id="frmEventRegister" name="frmEventRegister" method="post" action="<?php echo CalRoot;?>/components/EventRegisterAction.php" onsubmit="return chkFrm();">
<?php 	fakeFormFields();	?>
		<input name="eventID" id="eventID" type="hidden" value="<?php echo $eID;?>" />
<?php	if($hc_cfg65 > 0 && in_array(3, $hc_captchas)){
			echo '<fieldset>';
			echo '<legend>' . $hc_lang_register['Authentication'] . '</legend>';
			buildCaptcha();
			echo '</fieldset><br />';
		}//end if	?>
		<fieldset>
			<legend><?php echo $hc_lang_register['EventDetail'];?></legend>
			<div class="frmReq">
				<label><?php echo $hc_lang_register['Event'];?></label>
				<?php echo '<a href="' . CalRoot . '/index.php?com=detail&eID=' . $eID . '" class="eventMain">' . cOut(mysql_result($result,0,0)) . '</a>';?>
			</div>
			<div class="frmReq">
				<label><?php echo $hc_lang_register['Date'];?></label>
				<?php echo stampToDate(mysql_result($result,0,1), $hc_cfg14);?>
			</div>
			<div class="frmReq">
			<label><?php echo $hc_lang_register['Time'];?></label>
			<?php
				if(mysql_result($result,0,3) == 0){
					echo stampToDate("1980-01-01 " . mysql_result($result,0,2), $hc_cfg23);
				} elseif(mysql_result($result,0,3) == 1){
					echo $hc_lang_register['AllDay'];
				} elseif(mysql_result($result,0,3) == 2){
					echo $hc_lang_register['TBA'];
				}//end if?>
			</div>
			<div class="frmReq">
				<label><?php echo $hc_lang_register['Contact'];?></label>
		<?php	echo cOut(mysql_result($result,0,4)) . ' - ';
				cleanEmailLink(cOut(mysql_result($result,0,5)),cOut(mysql_result($result,0,0)));?>
			</div>
		</fieldset>
		<br />
		<fieldset>
			<legend><?php echo $hc_lang_register['YourReg'];?></legend>
			<div class="frmReq">
				<label for="hc_f1"><?php echo $hc_lang_register['Name'];?></label>
				<input name="hc_f1" id="hc_f1" type="text" size="25" maxlength="50" value="" />
			</div>
			<div class="frmReq">
				<label for="hc_f2"><?php echo $hc_lang_register['Email'];?></label>
				<input name="hc_f2" id="hc_f2" type="text" size="35" maxlength="75" value="" />
			</div>
			<div class="frmOpt">
				<label for="hc_f3"><?php echo $hc_lang_register['Phone'];?></label>
				<input name="hc_f3" id="hc_f3" type="text" size="20" maxlength="25" value="" />
			</div>
			<div class="frmOpt">
				<label for="hc_f7"><?php echo $hc_lang_register['PartySize'];?></label>
				<select name="hc_f7" id="hc_f7">
				<option value="0"><?php echo $hc_lang_register['Alone'];?></option>
		<?php	for($x=1;$x<=10;$x++){
					echo '<option value="' . $x . '">' . $hc_lang_register['Myself'] . ' +' . $x . '</option>';
				}//end for	?>
				</select>
			</div>
			<div class="frmOpt">
				<label for="hc_f4"><?php echo $hc_lang_register['Address'];?></label>
				<input name="hc_f4" id="hc_f4" type="text" size="20" maxlength="75" value="" />
			</div>
			<div class="frmOpt">
				<label for="hc_f5"><?php echo $hc_lang_register['Address2'];?></label>
				<input name="hc_f5" id="hc_f5" type="text" size="20" maxlength="75" value="" />
			</div>
		<?php
			$inputs = array(1 => array('City','hc_f6'),2 => array('Postal','hc_f8'));

			echo '<div class="frmOpt">';
			$first = ($hc_lang_config['AddressType'] == 1) ? 1 : 2;
			$second = ($first == 1) ? 2 : 1;

			echo '<label for="' . $inputs[$first][1] . '">' . $hc_lang_register[$inputs[$first][0]] . '</label>';
			echo '<input name="' . $inputs[$first][1] . '" id="' . $inputs[$first][1] . '" value="" type="text" maxlength="50" size="20" /><span style="color: #0000FF">*</span>';
			echo '</div>';

			if($hc_lang_config['AddressRegion'] != 0){
				echo '<div class="frmOpt">';
				echo '<label for="locState">' . $hc_lang_config['RegionLabel'] . '</label>';
				$state = $hc_cfg21;
				include($hc_langPath . $_SESSION['LangSet'] . '/' . $hc_lang_config['RegionFile']);
				echo '<span style="color: #0000FF;">*</span></div>';
			}//end if

			echo '<div class="frmOpt">';
			echo '<label for="' . $inputs[$second][1] . '">' . $hc_lang_register[$inputs[$second][0]] . '</label>';
			echo '<input name="' . $inputs[$second][1] . '" id="' . $inputs[$second][1] . '" value="" type="text" maxlength="50" size="20" /><span style="color: #0000FF">*</span>';
			echo '</div>';
			?>
			<div class="frmOpt">
				<label for="hc_f9"><?php echo $hc_lang_register['Country'];?></label>
				<input name="hc_f9" id="hc_f5" type="text" size="10" maxlength="50" value="" />
			</div>
		</fieldset>
		<br />
		<input name="submit" id="submit" type="submit" value="<?php echo $hc_lang_register['RegisterNow'];?>" class="button" />&nbsp;&nbsp;&nbsp;
		<input name="cancel" id="cancel" type="button" value="<?php echo $hc_lang_register['Cancel'];?>" onclick="document.location.href='<?php echo CalRoot;?>/index.php?com=detail&eID=<?php echo $eID;?>'; return false;" class="button" />
		</form>
<?php
	} else {
		echo '<div style="height:300px;"><br />' . $hc_lang_register['NoReg'] . '<br /><br /><a href="' . CalRoot . '" class="eventMain">' . $hc_lang_register['FindEvent'] . '</a></div>';
	}//end if	?>