<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/admin/oiduser.php');
	
	if(isset($_GET['msg'])){
		switch ($_GET['msg']){
			case '1':
				feedback(1,$hc_lang_oiduser['Feed01']);
				break;
			case '2':
				feedback(1,$hc_lang_oiduser['Feed02']);
				break;
			case '3':
				feedback(1,$hc_lang_oiduser['Feed03']);
				break;
		}//end switch
	}//end if
	
	appInstructions(0, "User_Edit", $hc_lang_oiduser['TitleEdit'], $hc_lang_oiduser['InstructEdit']);
	
	$uID = (isset($_GET['uID']) && is_numeric($_GET['uID'])) ? cIn($_GET['uID']) : 0;
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "oidusers WHERE Pkid = " . cIn($uID));
	
	if(hasRows($result)){
		$cancelURL = (mysql_result($result,0,7) != 2) ? CalAdminRoot . '/index.php?com=oiduser&t=1' : CalAdminRoot . '/index.php?com=oiduser&t=2';?>
		<script language="JavaScript" type="text/JavaScript">
		//<!--
		function doDelete(dID){
			if(confirm('<?php echo $hc_lang_oiduser['Valid01'] . "\\n\\n          " . $hc_lang_oiduser['Valid02'] . "\\n          " . $hc_lang_oiduser['Valid03'];?>')){
				document.location.href = '<?php echo CalAdminRoot . "/components/OIDDelete.php";?>?dID=' + dID + '&tID=1';
			}//end if
		}//end doDelete
		
		function doBan(dID){
			if(confirm('<?php echo $hc_lang_oiduser['Valid04'] . "\\n\\n          " . $hc_lang_oiduser['Valid05'] . "\\n          " . $hc_lang_oiduser['Valid06'];?>')){
				document.location.href = '<?php echo CalAdminRoot . "/components/OIDDelete.php";?>?dID=' + dID + '&tID=2';
			}//end if
		}//end doDelete
		
		function unBan(dID){
			if(confirm('<?php echo $hc_lang_oiduser['Valid07'] . "\\n\\n          " . $hc_lang_oiduser['Valid08'] . "\\n          " . $hc_lang_oiduser['Valid09'];?>')){
				document.location.href = '<?php echo CalAdminRoot . "/components/OIDDelete.php";?>?dID=' + dID + '&tID=3';
			}//end if
		}//end doDelete
		//-->
		</script>
	<?php
		echo '<br />';
		echo '<div style="line-height:20px;font-weight:bold;">' . $hc_lang_oiduser['ManageUser'];
		echo '&nbsp;<a href="javascript:;" onclick="doDelete(\'' . $uID . '\');return false;" class="main"><img src="' . CalAdminRoot . '/images/icons/iconUserDelete.png" width="16" height="16" alt="" border="0" style="vertical-align:middle;" /></a>';
		echo (mysql_result($result,0,7) != 2) ?
			'&nbsp;<a href="javascript:;" onclick="doBan(\'' . $uID . '\');return false;" class="main"><img src="' . CalAdminRoot . '/images/icons/iconUserBan.png" width="16" height="16" alt="" border="0" style="vertical-align:middle;" /></a>':
			'&nbsp;<a href="javascript:;" onclick="unBan(\'' . $uID . '\');return false;" class="main"><img src="' . CalAdminRoot . '/images/icons/iconUserUnban.png" width="16" height="16" alt="" border="0" style="vertical-align:middle;" /></a>';
		echo ($adminComments == 1) ? '&nbsp;<a href="' . CalAdminRoot . '/index.php?com=cmntmgt&uID=' . $uID . '" class="main"><img src="' . CalAdminRoot . '/images/icons/iconComments.png" width="16" height="16" alt="" border="0" style="vertical-align:middle;" /></a>' : '';
		echo '</div><br />';
		
		echo '<form name="" id="" method="post" action="">';
		echo '<fieldset><legend>' . $hc_lang_oiduser['AccountDetails'] . '</legend>';
		echo '<div class="frmOpt"><label>' . $hc_lang_oiduser['Identity'] . '</label>';
		echo '<div style="line-height:20px;font-weight:bold;">' . mysql_result($result,0,2) . '</div></div>';
		echo '<div class="frmOpt"><label>' . $hc_lang_oiduser['LastLogin'] . '</label>';
		
          $loginStamp = explode(" ", mysql_result($result,0,5));
          $loginDate = explode("-",$loginStamp[0]);
          $loginTime = explode(":", $loginStamp[1]);
          $loginStamp = date("Y-m-d G:i:s", mktime(($loginTime[0]+$hc_cfg35), $loginTime[1], $loginTime[2], $loginDate[1], $loginDate[2], $loginDate[0]));
          echo '<div style="line-height:20px;font-weight:bold;">' . stampToDate($loginStamp, $hc_cfg24 . ' @ ' . $hc_cfg23) . '</div></div>';
		echo '<div class="frmOpt"><label>' . $hc_lang_oiduser['LastIP'] . '</label>';
		echo '<div style="line-height:20px;font-weight:bold;">' . mysql_result($result,0,6) . '</div></div>';
		echo '<div class="frmOpt"><label>' . $hc_lang_oiduser['LoginCnt'] . '</label>';
		echo '<div style="line-height:20px;font-weight:bold;">' . mysql_result($result,0,3) . '</div></div>';
		echo '</fieldset>';
		echo '<br />';
		echo '<input name="cancel" id="cancel" type="button" value="' . $hc_lang_oiduser['Cancel'] . '" onclick="window.location.href=(\'' . $cancelURL . '\');return false;" class="button" />';
		echo '</form>';
		
		echo '<br /><br />';
		echo '<fieldset><legend>' . $hc_lang_oiduser['CommentStats'] . '</legend>';
		
		
		$result = doQuery("SELECT COUNT(PkID) FROM " . HC_TblPrefix . "comments WHERE PosterID = " . cIn($uID) . " AND IsActive = 1");
		$cmntCount = mysql_result($result,0,0);
		
		$result = doQuery("SELECT SUM(rl.Score)
							FROM " . HC_TblPrefix . "recomndslog rl
								LEFT JOIN " . HC_TblPrefix . "comments c ON (rl.CommentID = c.PkID)
							WHERE c.PosterID = ". cIn($uID));
		$sumRecomnds = (mysql_result($result,0,0)) ? mysql_result($result,0,0) : 0;
		
		$result = doQuery("SELECT COUNT(rl.CommentID)
							FROM " . HC_TblPrefix . "recomndslog rl
								LEFT JOIN " . HC_TblPrefix . "comments c ON (rl.CommentID = c.PkID)
							WHERE rl.Score > 0 AND c.PosterID = ". cIn($uID));
		$otherF = mysql_result($result,0,0);
		
		$result = doQuery("SELECT COUNT(rl.CommentID)
							FROM " . HC_TblPrefix . "recomndslog rl
								LEFT JOIN " . HC_TblPrefix . "comments c ON (rl.CommentID = c.PkID)
							WHERE rl.Score < 0 AND c.PosterID = ". cIn($uID));
		$otherA = mysql_result($result,0,0);
		
		$result = doQuery("SELECT COUNT(Score) FROM " . HC_TblPrefix . "recomndslog WHERE Score > 0 AND OIDUser = ". cIn($uID));
		$thisF = mysql_result($result,0,0);
		
		$result = doQuery("SELECT COUNT(Score) FROM " . HC_TblPrefix . "recomndslog WHERE Score < 0 AND OIDUser = ". cIn($uID));
		$thisA = mysql_result($result,0,0);
		
		echo '<div class="frmOpt"><label>' . $hc_lang_oiduser['Comments'] . '</label>';
		echo '<div style="line-height:20px;font-weight:bold;">' . $cmntCount . '</div></div>';
		
		echo '<div class="frmOpt" style="background:#EFEFEF;"><label>' . $hc_lang_oiduser['TotalRecomnds'] . '</label>';
		echo '<div style="line-height:20px;font-weight:bold;">' . $sumRecomnds;
		appInstructionsIcon($hc_lang_oiduser['Tip01A'], $hc_lang_oiduser['Tip01B']);
		echo '</div></div>';
		
		echo '<div class="frmOpt"><label>' . $hc_lang_oiduser['OthersFor'] . '</label>';
		echo '<div style="line-height:20px;font-weight:bold;">' . $otherF;
		appInstructionsIcon($hc_lang_oiduser['Tip02A'], $hc_lang_oiduser['Tip02B']);
		echo '</div></div>';
		
		echo '<div class="frmOpt" style="background:#EFEFEF;"><label>' . $hc_lang_oiduser['OthersAgainst'] . '</label>';
		echo '<div style="line-height:20px;font-weight:bold;">' . $otherA;
		appInstructionsIcon($hc_lang_oiduser['Tip03A'], $hc_lang_oiduser['Tip03B']);
		echo '</div></div>';
		
		echo '<div class="frmOpt"><label>' . $hc_lang_oiduser['ForOthers'] . '</label>';
		echo '<div style="line-height:20px;font-weight:bold;">' . $thisF;
		appInstructionsIcon($hc_lang_oiduser['Tip04A'], $hc_lang_oiduser['Tip04B']);
		echo '</div></div>';
		
		echo '<div class="frmOpt" style="background:#EFEFEF;"><label>' . $hc_lang_oiduser['AgainstOthers'] . '</label>';
		echo '<div style="line-height:20px;font-weight:bold;">' . $thisA;
		appInstructionsIcon($hc_lang_oiduser['Tip05A'], $hc_lang_oiduser['Tip05B']);
		echo '</div></div>';
		
		echo '</fieldset>';
	} else {
		echo '<br />' . $hc_lang_oiduser['InvalidUser'] . '<br /><br />';
		echo '<a href="' . CalAdminRoot . '/index.php?com=oiduser" class="eventMain">' . $hc_lang_oiduser['ValidLink'] . '</a>';
	}//end if?>