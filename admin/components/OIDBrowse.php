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
	
	appInstructions(0, "User_Management", $hc_lang_oiduser['TitleBrowse'], $hc_lang_oiduser['InstructBrowse']);
	
	$resDiff = 5;
	$resLimit = (isset($_GET['a']) && is_numeric($_GET['a']) && abs($_GET['a']) <= 100 && $_GET['a'] % 25 == 0) ? cIn(abs($_GET['a'])) : 25;
	$resPage = (isset($_GET['p']) && is_numeric($_GET['p'])) ? cIn(abs($_GET['p'])) : 0;
	$statusID = (isset($_GET['t']) && is_numeric($_GET['t'])) ? cIn(abs($_GET['t'])) : 1;
	
	$resultC = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "oidusers WHERE IsActive = " . $statusID);
	$totPages = ceil(mysql_result($resultC,0,0)/$resLimit);
	if($totPages <= $resPage && $totPages > 0){$resPage = ($totPages - 1);}
	
	$result = doQuery("SELECT PkID, ShortName, LastLogin
						FROM " . HC_TblPrefix . "oidusers
						WHERE IsActive = " . $statusID . "
						ORDER BY ShortName
						LIMIT " . $resLimit . " OFFSET " . ($resPage * $resLimit));
	
	echo '<br />';
	echo '<b>' . $hc_lang_oiduser['PerPage'] . '</b> ';
	for($x = 25;$x <= 100;$x = $x + 25){
		if($x > 25){echo "&nbsp;|&nbsp;";}			
		echo ($x != $resLimit) ?
			'<a href="' . CalAdminRoot . '/index.php?com=oiduser&p=' . $resPage . '&a=' . $x . '&t=' . $statusID . '" class="eventMain">' . $x . '</a>':
			'<b>' . $x . '</b>';
	}//end for
	
	echo '<br /><br /><b>' . $hc_lang_oiduser['Show'] . '</b> ';
	echo ($statusID != 1) ?
			'<a href="' . CalAdminRoot . '/index.php?com=oiduser&p=' . $resPage . '&a=' . $resLimit . '&t=1" class="eventMain">' . $hc_lang_oiduser['ActiveUsers'] . '</a>':
			'<b>' . $hc_lang_oiduser['ActiveUsers'] . '</b>';
	echo ' | ';
	echo ($statusID != 2) ?
			'<a href="' . CalAdminRoot . '/index.php?com=oiduser&p=' . $resPage . '&a=' . $resLimit . '&t=2" class="eventMain">' . $hc_lang_oiduser['BannedUsers'] . '</a>':
			'<b>' . $hc_lang_oiduser['BannedUsers'] . '</b>';
	
	if(hasRows($result)){	?>
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
		$x = (($resPage - $resDiff) > 0) ? ($resPage - $resDiff) : 0;
		$cnt = 0;
		
		echo '<div style="clear:both;padding-top:10px;"><b>' . $hc_lang_oiduser['Page'] . '</b>&nbsp;';
		if($resPage > ($resDiff)){
			echo '<a href="' . CalAdminRoot . '/index.php?com=oiduser&p=0&a=' . $resLimit . '&t=' . $statusID . '" class="eventMain">1</a>&nbsp;...&nbsp;';
		}//end if
		
		while($cnt <= ($resDiff*2) && $x <= ($totPages - 1)){
			echo ($cnt > 0) ? ' | ' : '';
			echo ($resPage != $x) ?
				'<a href="' . CalAdminRoot . '/index.php?com=oiduser&p=' . $x . '&a=' . $resLimit . '&t=' . $statusID . '" class="eventMain">' . ($x + 1) . '</a>':
				'<b>' . ($x + 1) . '</b>';
			++$x;
			++$cnt;
		}//end while
		
		if($resPage < ($totPages - ($resDiff + 1))){
			echo '&nbsp;...&nbsp;<a href="' . CalRoot . '/index.php?com=ocomm&p=' . ($totPages - 1) . '&a=' . $resLimit . '&t=' . $statusID . '" class="eventMain">' . $totPages . '</a>';
		}//end if
		echo '</div>';
		
		echo '<div class="oidList">';
		echo '<div class="oidIdentity">' . $hc_lang_oiduser['IdentityLabel'] . '</div>';
		echo '<div class="oidLastLogin">' . $hc_lang_oiduser['LastLoginLabel'] . '</div>';
		echo '<div class="oidTools">&nbsp;</div>';
		echo '&nbsp;</div>';
		$cnt = 0;
		
		while($row = mysql_fetch_row($result)){
			echo ($cnt % 2 == 1) ? '<div class="oidIdentityHL">' : '<div class="oidIdentity">';
			echo $row[1] . '</div>';
			echo ($cnt % 2 == 1) ? '<div class="oidLastLoginHL">' : '<div class="oidLastLogin">';
			
               $loginStamp = explode(" ", $row[2]);
			$loginDate = explode("-",$loginStamp[0]);
			$loginTime = explode(":", $loginStamp[1]);
			$loginStamp = date("Y-m-d G:i:s", mktime(($loginTime[0]+$hc_cfg35), $loginTime[1], $loginTime[2], $loginDate[1], $loginDate[2], $loginDate[0]));
			echo stampToDate($loginStamp,$hc_cfg24 . ' @ ' . $hc_cfg23);
			echo '</div>';
			echo ($cnt % 2 == 1) ? '<div class="oidToolsHL">' : '<div class="oidTools">';
			echo '<a href="' . CalAdminRoot . '/index.php?com=oidedit&amp;uID=' . $row[0] . '" class="main"><img src="' . CalAdminRoot . '/images/icons/iconUserEdit.png" width="16" height="16" alt="" border="0" style="vertical-align:middle;" /></a>&nbsp;';
			echo '<a href="javascript:;" onclick="doDelete(\'' . $row[0] . '\');return false;" class="main"><img src="' . CalAdminRoot . '/images/icons/iconUserDelete.png" width="16" height="16" alt="" border="0" style="vertical-align:middle;" /></a>&nbsp;';
			echo ($statusID != 2) ?
				'<a href="javascript:;" onclick="doBan(\'' . $row[0] . '\');return false;" class="main"><img src="' . CalAdminRoot . '/images/icons/iconUserBan.png" width="16" height="16" alt="" border="0" style="vertical-align:middle;" /></a>&nbsp;':
				'<a href="javascript:;" onclick="unBan(\'' . $row[0] . '\');return false;" class="main"><img src="' . CalAdminRoot . '/images/icons/iconUserUnban.png" width="16" height="16" alt="" border="0" style="vertical-align:middle;" /></a>&nbsp;';
			echo ($adminComments == 1) ? '&nbsp;<a href="' . CalAdminRoot . '/index.php?com=cmntmgt&uID=' . $row[0] . '" class="main"><img src="' . CalAdminRoot . '/images/icons/iconComments.png" width="16" height="16" alt="" border="0" style="vertical-align:middle;" /></a>' : '';
			echo '</div>';
			++$cnt;
		}//end while		
	} else {
		echo '<br /><br />' . $hc_lang_oiduser['NoUsers'] . '<br /><br />';
	}//end if?>