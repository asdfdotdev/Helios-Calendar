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
	include($hc_langPath . $_SESSION['LangSet'] . '/public/comment.php');
	if(!file_exists(realpath('cache/censored.php'))){
		rebuildCache(2);
	}//end if
	include('cache/censored.php');
	
	$cID = (isset($_GET['cID']) && is_numeric($_GET['cID'])) ? $_GET['cID'] : 0;
	$tID = (isset($_GET['tID']) && is_numeric($_GET['tID'])) ? $_GET['tID'] : 0;
	$uID = (isset($_SESSION['hc_OpenIDPkID']) && is_numeric($_SESSION['hc_OpenIDPkID'])) ? $_SESSION['hc_OpenIDPkID'] : 0;
	$query = "SELECT * FROM " . HC_TblPrefix . "commentsreportlog crl
				WHERE crl.CommentID = '" . $cID . "' AND (ReportedIP = '" . $_SERVER["REMOTE_ADDR"] . "'";
	if($uID > 0){$query .= " OR crl.UserID = '" . $uID . "'";}
	$query .= ')';
	$result = doQuery($query);
	$noSubmit = (hasRows($result)) ? 1 : 0;
	
	$result = doQuery("SELECT e.PkID, e.Title, e.StartDate, oid.ShortName, c.*
						FROM " . HC_TblPrefix . "comments c
							LEFT JOIN " . HC_TblPrefix . "events e ON (c.EntityID = e.PkID)
							LEFT JOIN " . HC_TblPrefix . "oidusers oid ON (c.PosterID = oid.PkID)
						WHERE c.PkID = '" . cIn($cID) . "' AND c.IsActive = 1 AND c.TypeID = '" . $tID . "'");?>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Email.js"></script>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/ajxOutput.js"></script>
	<script language="JavaScript" type="text/javascript">
	//<!--
	function chkFrm(){
		dirty = 0;
		warn = '<?php echo $hc_lang_comment['Valid01'];?>';

		<?php	captchaValidation('6');?>
		
		if(document.hc_repcmnts.reportName.value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_comment['Valid03'];?>';
		}//end if
		
		if(document.hc_repcmnts.reportEmail.value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_comment['Valid04'];?>';
		} else if(chkEmail(document.hc_repcmnts.reportEmail) == 0){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_comment['Valid05'];?>';
		}//end if

		if(document.hc_repcmnts.reportDetails.value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_comment['Valid06'];?>';
		}//end if
		
		if(dirty > 0){
			alert(warn + '\n\n<?php echo $hc_lang_comment['Valid07'];?>');
			return false;
		} else {
			document.getElementById('submit').disabled = true;
			document.getElementById('submit').value = '<?php echo $hc_lang_core['Sending'];?>';
			return true;
		}//end if
	}//end chkFrm()
	
	function testCAPTCHA(){
		if(document.hc_repcmnts.proof.value != ''){
			var qStr = 'CaptchaCheck.php?capEntered=' + document.hc_repcmnts.proof.value;
			ajxOutput(qStr, 'capChk', '<?php echo CalRoot;?>');
		} else {
			alert('<?php echo $hc_lang_comment['Valid08'];?>');
		}//end if
	}//end testCAPTCHA()
	//-->
	</script>
<?php
	if(hasRows($result)){
		if($noSubmit == 1){
			feedback(2,$hc_lang_comment['Feed01']);
			echo '<form name="hc_repcmnts" id="hc_repcmnts" method="post" action="" onsubmit="return false;">';
		} else {
			echo '<form name="hc_repcmnts" id="hc_repcmnts" method="post" action="' . CalRoot . '/components/CommentReportAction.php" onsubmit="return chkFrm();">';
		}//end if
		echo '<input name="eID" id="eID" type="hidden" value="' . mysql_result($result,0,0) . '" />';
		echo '<input name="cID" id="cID" type="hidden" value="' . $cID . '" />';
		echo '<input name="tID" id="tID" type="hidden" value="1" />';
		echo '<input name="uID" id="uID" type="hidden" value="' . $uID . '" />';
		if($hc_cfg65 > 0 && in_array(7, $hc_captchas)){
			echo '<fieldset>';
			echo '<legend>' . $hc_lang_comment['Authentication'] . '</legend>';
			buildCaptcha();
			echo '</fieldset><br />';
		}//end if
		
		echo '<fieldset><legend>' . $hc_lang_comment['CommentDetails'] . '</legend>';
		echo '<div class="frmOpt"><label>' . $hc_lang_comment['EventTitle'] . '</label>' . cOut(mysql_result($result,0,1)) . '</div>';
		echo '<div class="frmOpt"><label>' . $hc_lang_comment['EventDate'] . '</label>' . stampToDate(cOut(mysql_result($result,0,2)), $hc_cfg14) . '</div>';
		echo '<div class="frmOpt"><label>' . $hc_lang_comment['SubmittedBy'] . '</label>' . cOut(mysql_result($result,0,3)) . '</div>';
		echo '<div class="frmOpt"><label>' . $hc_lang_comment['SubmittedAt'] . '</label>' . stampToDate(cOut(mysql_result($result,0,7)), $hc_cfg24 . ' ' . $hc_cfg23) . '</div>';
		echo '<div class="frmOpt"><label>' . $hc_lang_comment['CommentText'] . '</label><div style="float:left;width:80%;">' . cOut(censorWords(nl2br(mysql_result($result,0,5)),$hc_censored_words)) . '</div></div>';
		echo '</fieldset><br />';
		
		echo '<fieldset><legend>' . $hc_lang_comment['YourReport'] . '</legend>';
		echo '<div class="frmReq"><label for="reportName">' . $hc_lang_comment['YourName'] . '</label><input name="reportName" id="reportName" type="text" size="25" maxlength="50" value="" /></div>';
		echo '<div class="frmReq"><label for="reportEmail">' . $hc_lang_comment['YourEmail'] . '</label><input name="reportEmail" id="reportEmail" type="text" size="35" maxlength="75" value="" /></div>';
		
		echo (isset($_SESSION['hc_OpenIDShortName'])) ? '<div class="frmReq"><label>' . $hc_lang_comment['LoggedAs'] . '</label>' . $_SESSION['hc_OpenIDShortName'] . '</div>' : '';
		echo '<div class="frmReq"><label>' . $hc_lang_comment['YourIP'] . '</label>' . $_SERVER["REMOTE_ADDR"] . '</div>';
		echo '<div class="frmOpt"><label for="reportDetails">' . $hc_lang_comment['ReportDetails'] . '</label>' . $hc_lang_comment['Why'] . '</div>';
		echo '<div class="frmOpt"><label>&nbsp;</label><textarea name="reportDetails" id="reportDetails" rows="5" cols="5" style="width:75%;height:100px;"></textarea></div>';
		echo '</fieldset><br />';
		
		echo ($noSubmit != 1) ? '<input type="submit" name="submit" id="submit" value="' . $hc_lang_comment['Submit'] . '" class="button" />' : '';
		echo '&nbsp;&nbsp;';
		echo '<input name="cancel" id="cancel" type="button" value="' . $hc_lang_comment['Cancel'] . '" onclick="document.location.href=\'' . CalRoot . '/index.php?com=detail&amp;eID=' . cOut(mysql_result($result,0,0)) . '#comments\'; return false;" class="button" />';
		echo '</form>';
	} else {
		echo $hc_lang_comment['NoComment'];
		echo "<br /><br /><br /><br /><br />";
	}//end if?>