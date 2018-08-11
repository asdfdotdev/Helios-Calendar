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
	include($hc_langPath . $_SESSION['LangSet'] . '/public/sendtofriend.php');	?>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Email.js"></script>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/ajxOutput.js"></script>
	<script language="JavaScript" type="text/javascript">
	//<!--
	function chkFrm(){
	dirty = 0;
	warn = "<?php echo $hc_lang_sendtofriend['Valid01'];?>";

	<?php	captchaValidation('2');?>
		
		if(document.getElementById('hc_fx1').value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_sendtofriend['Valid03'];?>';
		}//end if
		
		if(document.getElementById('hc_fx2').value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_sendtofriend['Valid04'];?>';
		} else {
			if(chkEmail(document.getElementById('hc_fx2')) == 0){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_sendtofriend['Valid05'];?>';
			}//end if
		}//end if
		
		if(document.getElementById('hc_fx3').value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_sendtofriend['Valid06'];?>';
		}//end if
		
		if(document.getElementById('hc_fx4').value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_sendtofriend['Valid07'];?>';
		} else {
			if(chkEmail(document.getElementById('hc_fx4')) == 0){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_sendtofriend['Valid08'];?>';
			}//end if
		}//end if
		
		if(document.getElementById('message').value.length > 250){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_sendtofriend['Valid09'];?>';
		}//end if
		
		if(dirty > 0){
			alert(warn + '\n\n<?php echo $hc_lang_sendtofriend['Valid10'];?>');
			return false;
		} else {
			document.getElementById('submit').disabled = true;
			document.getElementById('submit').value = '<?php echo $hc_lang_core['Sending'];?>';
			return true;
		}//end if
	}//end chkFrm()
	
	function testCAPTCHA(){
		if(document.getElementById('proof').value != ''){
			var qStr = 'CaptchaCheck.php?capEntered=' + document.getElementById('proof').value;
			ajxOutput(qStr, 'capChk', '<?php echo CalRoot;?>');
		} else {
			alert('<?php echo $hc_lang_sendtofriend['Valid11'];?>');
		}//end if
	}//end testCAPTCHA()
	//-->
	</script>
<?php	
	$hourOffset = date("G") + ($hc_cfg35);
	$eID = (isset($_GET['eID']) && is_numeric($_GET['eID'])) ? cIn($_GET['eID']) : 0;
	$result = doQuery("SELECT Title, StartDate, StartTime, TBD FROM " . HC_TblPrefix . "events WHERE PkID = '" . $eID . "' AND StartDate >= '" . date("Y-m-d", mktime($hourOffset,0,0,date("m"),date("d"),date("Y"))) . "'");
	
	if(hasRows($result)){
		if (isset($_GET['msg'])){
			switch ($_GET['msg']){
				case "1" :
					feedback(1,$hc_lang_sendtofriend['Feed01']);
					break;	
			}//end switch
		}//end if
		
		echo '<p>' . $hc_lang_sendtofriend['SendLabel'] . '</p>';?>
	
		<form name="frmSendToFriend" id="frmSendToFriend" method="post" action="<?php echo CalRoot;?>/components/SendToFriendAction.php" onsubmit="return chkFrm();">
<?php 	fakeFormFields();	?>
		<input type="hidden" name="eID" id="eID" value="<?php echo $eID;?>" />
<?php	if($hc_cfg65 > 0 && in_array(2, $hc_captchas)){
			echo '<fieldset>';
			echo '<legend>' . $hc_lang_sendtofriend['Authentication'] . '</legend>';
			buildCaptcha();
			echo '</fieldset><br />';
		}//end if	?>
		<fieldset>
			<legend><?php echo $hc_lang_sendtofriend['EventDetail'];?></legend>
			<div class="frmReq">
				<label><?php echo $hc_lang_sendtofriend['Event'];?></label>
				<?php echo '<a href="' . CalRoot . '/index.php?com=detail&eID=' . $eID . '" class="eventMain">' . cOut(mysql_result($result,0,0)) . '</a>';?>
			</div>
			<div class="frmReq">
				<label><?php echo $hc_lang_sendtofriend['Date'];?></label>
				<?php echo stampToDate(mysql_result($result,0,1), $hc_cfg14);?>
			</div>
			<div class="frmReq">
				<label><?php echo $hc_lang_sendtofriend['Time'];?></label>
			<?php
				if(mysql_result($result,0,3) == 0){
					echo stampToDate("1980-01-01 " . mysql_result($result,0,2), $hc_cfg23);
				} elseif(mysql_result($result,0,3) == 1){
					echo $hc_lang_sendtofriend['AllDay'];
				} elseif(mysql_result($result,0,3) == 2){
					echo $hc_lang_sendtofriend['TBA'];
				}//end if?>
			</div>
		</fieldset>
		<br />
		<fieldset>
			<legend><?php echo $hc_lang_sendtofriend['CreateMsg'];?></legend>
			<div class="frmReq">
				<label for="hc_fx1"><?php echo $hc_lang_sendtofriend['MyName'];?></label>
				<input name="hc_fx1" id="hc_fx1" type="text" size="25" maxlength="100" />
		    </div>
		    <div class="frmReq">
				<label for="hc_fx2"><?php echo $hc_lang_sendtofriend['MyEmail'];?></label>
				<input id="hc_fx2" name="hc_fx2" type="text" size="35" maxlength="100" />
		    </div>
		    <div class="frmReq">
				<label for="hc_fx3"><?php echo $hc_lang_sendtofriend['FriendsName'];?></label>
				<input name="hc_fx3" id="hc_fx3" type="text" size="25" maxlength="100" />
		    </div>
		    <div class="frmReq">
				<label for="hc_fx4"><?php echo $hc_lang_sendtofriend['FriendsEmail'];?></label>
				<input name="hc_fx4" id="hc_fx4" type="text" size="35" maxlength="100" />
		    </div>
			<div class="frmOpt">
				<label for="hc_fx5"><?php echo $hc_lang_sendtofriend['Message'] . "<br /><br />" . $hc_lang_sendtofriend['MessageLimit'];?></label>
				<textarea name="hc_fx5" id="hc_fx5" rows="10" cols="10" style="width:80%;" onkeyup="this.value=this.value.slice(0, 250)"><?php echo $hc_lang_sendtofriend['SentMessage'];?></textarea>
			</div>
		</fieldset>
		<br />
		<input name="submit" id="submit" type="submit" value="<?php echo $hc_lang_sendtofriend['SendMessage'];?>" class="button" />
		<input name="cancel" id="cancel" type="button" value="<?php echo $hc_lang_sendtofriend['Cancel'];?>" class="button" onclick="window.location.href='<?php echo CalRoot;?>/?com=detail&amp;eID=<?php echo $eID;?>';return false;" />
		</form>
<?php 
	} else {	
		echo '<div style="min-height:350px;"><p>' . $hc_lang_sendtofriend['NoEvent'] . '</p>';
		echo '<p><a href="' . CalRoot . '/index.php" class="eventMain">' . $hc_lang_sendtofriend['ThisWeekLink'] . '</a></p></div>';
	}//end if	?>