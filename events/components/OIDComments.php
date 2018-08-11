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
	include($hc_langPath . $_SESSION['LangSet'] . '/public/user.php');
	if(!file_exists(realpath('cache/censored.php'))){
		rebuildCache(2);
	}//end if
	include('cache/censored.php');
	
	$uID = (isset($_SESSION['hc_OpenIDPkID'])) ? $_SESSION['hc_OpenIDPkID'] : 0;
	
	echo '<p>' . $hc_lang_user['CommentDesc'] . '</p>';
	echo '<div class="oidUserCom">' . $hc_lang_user['CommentLabel'] . '</div>';
	
	$resDiff = 5;
	//$resLimit = (isset($_GET['a']) && is_numeric($_GET['a']) && abs($_GET['a']) <= 100 && $_GET['a'] % 25 == 0) ? cIn(abs($_GET['a'])) : 25;
	$resLimit = 10;
	$resPage = (isset($_GET['p']) && is_numeric($_GET['p'])) ? cIn(abs($_GET['p'])) : 0;

	$resultC = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "comments WHERE IsActive = 1 AND TypeID = 1 AND PosterID = '" . cIn($uID) . "'");
	$totPages = ceil(mysql_result($resultC,0,0)/$resLimit);
	if($totPages <= $resPage && $totPages > 0){$resPage = ($totPages - 1);}
	
	$result = doQuery("SELECT c.*, e.PkID, e.Title
						FROM " . HC_TblPrefix . "comments c
							LEFT JOIN " . HC_TblPrefix . "events e ON (c.EntityID = e.PkID)
						WHERE c.PosterID = '" . cIn($uID) . "' AND c.IsActive = 1 AND c.TypeID = 1
						ORDER BY PostTime DESC
						LIMIT " . $resLimit . " OFFSET " . ($resPage * $resLimit));
	
	if(hasRows($result)){
		$x = (($resPage - $resDiff) > 0) ? ($resPage - $resDiff) : 0;
		$cnt = 0;
		
		echo '<div style="clear:both;padding-top:10px;"><b>' . $hc_lang_user['Page'] . '</b>&nbsp;';
		if($resPage > ($resDiff)){
			echo '<a href="' . CalRoot . '/index.php?com=ocomm&p=0&a=' . $resLimit . '" class="eventMain">1</a>&nbsp;...&nbsp;';
		}//end if
		
		while($cnt <= ($resDiff*2) && $x <= ($totPages - 1)){
			echo ($cnt > 0) ? ' | ' : '';
			echo ($resPage != $x) ?
				'<a href="' . CalRoot . '/index.php?com=ocomm&p=' . $x . '&a=' . $resLimit . '" class="eventMain">' . ($x + 1) . '</a>':
				'<b>' . ($x + 1) . '</b>';
			++$x;
			++$cnt;
		}//end while
		
		if($resPage < ($totPages - ($resDiff + 1))){
			echo '&nbsp;...&nbsp;<a href="' . CalRoot . '/index.php?com=ocomm&p=' . ($totPages - 1) . '&a=' . $resLimit . '" class="eventMain">' . $totPages . '</a>';
		}//end if
		echo '</div><br />';
		
		while($row = mysql_fetch_row($result)){
			echo '<div class="commentFrame" >';
			echo '<div class="commentTools">';
			echo ($row[6] > 0) ? '+' . $row[6] : $row[6];
			echo ' Recomnds <br /></div>';
			echo '<b>' . $hc_lang_user['About'] . '</b> <a href="' . CalRoot . '/index.php?com=detail&eID=' . $row[8] . '" class="eventMain">' . $row[9] . '</a> <b>' . $hc_lang_user['Said'] . '</b>';
			echo '<br /><br /><div id="comment_' . $row[0] . '" class="comment">';
			echo censorWords(nl2br($row[1]),$hc_censored_words) . '<br /><br />';
			
			$cmntStamp = explode(" ", $row[3]);
			$cmntDate = explode("-",$cmntStamp[0]);
			$cmntTime = explode(":", $cmntStamp[1]);
			$cmntStamp = date("Y-m-d G:i:s", mktime(($cmntTime[0]+$hc_cfg35), $cmntTime[1], $cmntTime[2], $cmntDate[1], $cmntDate[2], $cmntDate[0]));
			echo '<b><i>' . stampToDate($cmntStamp, $hc_cfg24 . ' @ ' . $hc_cfg23) . '</i></b>&nbsp;&nbsp;';
			echo '</div></div>';
			++$cnt;
		}//end while		
	} else {
		echo '<br />' . $hc_lang_user['NoComments'] . '<br /><br />';
	}//end if?>