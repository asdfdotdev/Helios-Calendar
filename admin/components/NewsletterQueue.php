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
	if(!isset($hc_cfg00)){header("HTTP/1.1 403 No Direct Access");exit();}
	
	include($hc_langPath . $_SESSION['LangSet'] . '/admin/newsletter.php');

	$hc_Side[] = array(CalRoot . '/index.php?com=archive','iconEmailOpen.png',$hc_lang_news['ViewArchive'],1);

	$type = (isset($_GET['t']) && is_numeric($_GET['t'])) ? cIn($_GET['t']) : 0;
	$saveType = '&t=' . $type;
	$queryT = ($type == 1) ? " AND n.Status > 2" : " AND n.Status <= 2";
	$queryS = $saveLink = $saveSearch= $term = '';
	if(isset($_GET['s']) && $_GET['s'] != ''){
		$term = cIn(cleanQuotes(strip_tags($_GET['s'])));
		$queryS = " AND MATCH(Subject,Message) AGAINST('" . str_replace("'", "\"", $term) . "' IN BOOLEAN MODE)";
		$saveSearch .= '&s=' . $term;
	}//end if
	$saveLink = $saveType . $saveSearch;

	$resDiff = 6;
	$resLimit = (isset($_GET['a']) && is_numeric($_GET['a']) && abs($_GET['a']) <= 100 && $_GET['a'] % 25 == 0) ? cIn(abs($_GET['a'])) : 25;
	$resOffset = (isset($_GET['p']) && is_numeric($_GET['p'])) ? cIn(abs($_GET['p'])) : 0;

	$resultC = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix  . "newsletters n WHERE n.IsActive = 1 $queryT $queryS");
	$pages = ceil(mysql_result($resultC,0,0)/$resLimit);
	if($pages <= $resOffset && $pages > 0){$resOffset = ($pages - 1);}?>
<?php
	if(isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1, $hc_lang_news['Feed16']);
				break;
			case "2" :
				feedback(1, $hc_lang_news['Feed17']);
				break;
			case "3" :
				feedback(1, $hc_lang_news['Feed19']);
				break;
		}//end switch
	}//end if

	appInstructions(0, "Newsletter_Queue", $hc_lang_news['TitleQueue'], $hc_lang_news['InstructQueue']);

	echo '<br /><fieldset style="border:0px;">';
	echo '<div class="frmOpt">';
	echo '<label><b>' . $hc_lang_news['View'] . '</b></label>';
	echo ($type == 1) ?
		'<a href="' . CalAdminRoot . '/index.php?com=newsqueue&p=0&a=' . $resLimit . '&t=0' . $saveSearch . '" class="eventMain">' . $hc_lang_news['InProgress'] . '</a> | <b>' . $hc_lang_news['Complete'] . '</b>':
		'<b>' . $hc_lang_news['InProgress'] . '</b> | <a href="' . CalAdminRoot . '/index.php?com=newsqueue&p=0&a=' . $resLimit . '&t=1' . $saveSearch . '" class="eventMain">' . $hc_lang_news['Complete'] . '</a>';
	echo '</div>';
	echo '<div class="frmOpt">';
	echo '<label><b>' . $hc_lang_news['ResPer'] . '</b></label>';
	for($x = 25;$x <= 100;$x = $x + 25){
		if($x > 25){echo "&nbsp;|&nbsp;";}
		echo ($x != $resLimit) ?
			'<a href="' . CalAdminRoot . '/index.php?com=newsqueue&amp;p=' . $resOffset . '&amp;a=' . $x . $saveLink . '" class="eventMain">' . $x . '</a>':
			'<b>' . $x . '</b>';
	}//end for
	echo '</div>';

	$cnt = 0;
	$x = (($resOffset - $resDiff) < 0) ? 0 : $resOffset - $resDiff;

	echo '<div class="frmOpt"><label><b>' . $hc_lang_news['Page'] . '</b></label>';
	echo (($resOffset > $resDiff) && $pages > 0) ? '<a href="' . CalAdminRoot . '/index.php?com=newsqueue&p=0&a=' . $resLimit . $saveLink . '" class="eventMain">1</a>&nbsp;...&nbsp;' : '';
	while($cnt <= ($resDiff * 2) && $x < $pages){
		echo ($cnt > 0) ? ' | ' : '';
		echo ($resOffset != $x) ?
			'<a href="' . CalAdminRoot . '/index.php?com=newsqueue&amp;p=' . $x . '&amp;a=' . $resLimit . $saveLink . '" class="eventMain">' . ($x + 1) . '</a>':
			'<b>' . ($x + 1) . '</b>';
		++$x;
		++$cnt;
	}//end while
	echo ($pages == 0) ? '1' : '';
	echo (($resOffset < ($pages - ($resDiff + 1))) && $pages > 0) ? '&nbsp;...&nbsp;<a href="' . CalAdminRoot . '/index.php?com=newsqueue&p=' . ($pages - 1) . '&a=' . $resLimit . $saveLink . '" class="eventMain">' . $pages . '</a>' : '';
	echo '</div>';
	echo '<div class="frmOpt">';
	echo '<label>&nbsp;</label>';
	echo '<input type="text" name="filter" id="filter" size="30" maxlength="50" value="' . $term . '" />';
	echo '<input type="button" name="go" id="go" value="' . $hc_lang_news['FilterNews'] . '" class="buttonFilter" onclick="window.location.href=\'' . CalAdminRoot . '/index.php?com=newsqueue&p=0&a=' . $resLimit . '&t=' . $saveType . '&s=\'+document.getElementById(\'filter\').value;" />';
	echo ($saveSearch != '') ? '<div class="frmOpt"><label>&nbsp;</label><a href="' . CalAdminRoot . '/index.php?com=newsqueue' . $saveType . '" class="main">' . $hc_lang_news['AllNewsLink'] . '</a></div>' : '';
	echo '</div></fieldset>';

	$result = doQuery("SELECT n.PkID, n.SentDate, n.Subject, n.SendCount, n.`Status`, n.IsArchive, n.SendingAdminID,
					(SELECT COUNT(ns.SubscriberID)
						FROM " . HC_TblPrefix . "newssubscribers ns
						WHERE ns.NewsletterID = n.PkID) as ToGo
					FROM " . HC_TblPrefix . "newsletters n
					WHERE n.IsActive = 1 $queryT $queryS
					ORDER BY n.Status, n.SentDate DESC, PkID DESC
					LIMIT " . $resLimit . " OFFSET " . ($resOffset * $resLimit));
	if(hasRows($result)){?>
		<script language="JavaScript" type="text/JavaScript">
		//<!--
		function doPause(nID){
			if(confirm('<?php echo $hc_lang_news['Valid35'] . "\\n\\n          " . $hc_lang_news['Valid36'] . "\\n          " . $hc_lang_news['Valid37'];?>')){
				document.location.href = '<?php echo CalAdminRoot . "/components/NewsletterPause.php";?>?n=' + nID + '&d=2&g=1';
			}//end if
		}//end doDelete

		function doDelete(dID){
			if(confirm('<?php echo $hc_lang_news['Valid32'] . "\\n\\n          " . $hc_lang_news['Valid33'] . "\\n          " . $hc_lang_news['Valid34'];?>')){
				document.location.href = '<?php echo CalAdminRoot . "/components/MailQueueAction.php";?>?dID=' + dID + '&tID=<?php echo $type;?>';
			}//end if
		}//end doDelete
		//-->
		</script>
<?php
		//Date, Subject, Recipients, Progress, Status Tools
		echo '<div class="newsList">';
		echo '<div class="newsDate"><b>' . $hc_lang_news['Created'] . '</b></div>';
		echo '<div class="newsSubject"><b>' . $hc_lang_news['Subject'] . '</b></div>';
		echo '<div class="newsCount"><b>' . $hc_lang_news['Count'] . '</b></div>';
		echo '<div class="newsProgress"><b>' . $hc_lang_news['Progress'] . '</b></div>';
		echo '<div class="newsStatus"><b>' . $hc_lang_news['Status'] . '</b></div>';
		echo '<div class="newsTools">&nbsp;</div>&nbsp;';
		echo '</div>';

		$cnt = 0;
		while($row = mysql_fetch_row($result)){
			echo ($cnt % 2 == 0) ? '<div class="newsDate">' : '<div class="newsDateHL">';
			echo stampToDate($row[1], $hc_cfg24) . '</div>';

			echo ($cnt % 2 == 0) ? '<div class="newsSubject">' : '<div class="newsSubjectHL">';
			echo $row[2] . '&nbsp;</div>';

			echo ($cnt % 2 == 0) ? '<div class="newsCount">' : '<div class="newsCountHL">';
			echo number_format($row[3],0,'.',',') . '&nbsp;</div>';
			
			echo ($cnt % 2 == 0) ? '<div class="newsProgress">' : '<div class="newsProgressHL">';
			echo ($row[4] == 3) ? '100%</div>' : number_format(100-(($row[7] / $row[3]) * 100),0) . '%</div>';

			echo ($cnt % 2 == 0) ? '<div class="newsStatus">' : '<div class="newsStatusHL">';
			echo  $hc_lang_news['Status' . $row[4]] . '</div>';

			echo ($cnt % 2 == 0) ? '<div class="newsTools">' : '<div class="newsToolsHL">';

			if($type == 0){
				echo '<img src="' . CalAdminRoot . '/images/spacer.gif" width="16" height="1" alt="" border="0" style="vertical-align:middle;" />&nbsp;&nbsp;';
				echo ($row[4] == 1) ?
					'<a href="javascript:;" onclick="doPause(\'' . $row[0] . '\');return false;"" class="main"><img src="' . CalAdminRoot . '/images/icons/iconPause.png" width="16" height="16" alt="" border="0" style="vertical-align:middle;" /></a>&nbsp;&nbsp;':
					'<a href="' . CalAdminRoot . '/index.php?com=newssend&amp;nID=' . $row[0] . '" class="main"><img src="' . CalAdminRoot . '/images/icons/iconPlay.png" width="16" height="16" alt="" border="0" style="vertical-align:middle;" /></a>&nbsp;&nbsp;';
			} else {
				echo ($row[5] == 1) ? '<a href="' . CalRoot . '/newsletter/index.php?n=' . md5($row[0]) . '" class="main" target="_blank"><img src="' . CalAdminRoot . '/images/icons/iconEmailOpen.png" width="16" height="16" alt="" border="0" style="vertical-align:middle;" /></a>&nbsp;&nbsp;' : '<img src="' . CalAdminRoot . '/images/spacer.gif" width="16" height="1" alt="" border="0" style="vertical-align:middle;" />&nbsp;&nbsp;';
				echo '<a href="' . CalAdminRoot . '/index.php?com=newsreport&amp;rID=' . $row[0] . '" class="main"><img src="' . CalAdminRoot . '/images/icons/iconReport.png" width="16" height="16" alt="" border="0" style="vertical-align:middle;" /></a>&nbsp;&nbsp;';
			}//end if
			echo '<a href="javascript:;" onclick="doDelete(\'' . $row[0] . '\');return false;" class="main"><img src="' . CalAdminRoot . '/images/icons/iconEmailDelete.png" width="15" height="15" alt="" border="0" style="vertical-align:middle;" /></a></div>';
			++$cnt;
		}//end while
	} else {
		echo '<p>' . $hc_lang_news['NoNewsletter' . $type] . '</p>';
	}//end if
?>