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
	
	include($hc_langPath . $_SESSION['LangSet'] . '/admin/reports.php');
	
	appInstructions(0, "Reports", $hc_lang_reports['TitleAdd'], $hc_lang_reports['InstructAdd']);
	$result = doQuery("	SELECT * 
							FROM " . HC_TblPrefix . "events 
						WHERE StartDate >= '" . date("Y-m-d") . "' 
							AND IsActive = 1 
							AND IsApproved = 1
						ORDER BY PublishDate DESC, StartDate
						LIMIT 50");
	
	if(hasRows($result)){
		echo '<div class="recentList">';
		echo '<div class="recentTitle"><b>' . $hc_lang_reports['Event'] . '</b></div>';
		echo '<div class="recentDateA"><b>' . $hc_lang_reports['Added'] . '</b></div>';
		echo '<div class="recentDateO"><b>' . $hc_lang_reports['Occurs'] . '</b></div>';
		echo '<div class="recentTools">&nbsp;</div>';
		echo '&nbsp;</div>';
		
		$cnt = 0;
		$curDate = "";
		while($row = mysql_fetch_row($result)){
			echo ($cnt % 2 == 1) ? '<div class="recentTitleHL">' : '<div class="recentTitle">';
			echo cOut($row[1]) . '</div>';
			
			echo ($cnt % 2 == 1) ? '<div class="recentDateAHL">' : '<div class="recentDateA">';
			echo stampToDate(cOut($row[27]), $hc_cfg24) . '</div>';
			
			echo ($cnt % 2 == 1) ? '<div class="recentDateOHL">' : '<div class="recentDateO">';
			echo stampToDate(cOut($row[9]), $hc_cfg24) . '</div>';
			
			echo ($cnt % 2 == 1) ? '<div class="recentToolsHL">' : '<div class="recentTools">';
			echo '<a href="' . CalAdminRoot . '/index.php?com=eventedit&amp;eID=' . $row[0] . '" class="main"><img src="' . CalAdminRoot . '/images/icons/iconEdit.png" width="15" height="15" alt="" border="0" /></a>&nbsp;&nbsp;';
			
			echo ($row[18] == 1) ?
				'<a href="' . CalAdminRoot . '/index.php?com=eventbillboard" class="main"><img src="' . CalAdminRoot . '/images/icons/iconBillboard.png" width="15" height="15" alt="" border="0" /></a>':
				'<img src="' . CalAdminRoot . '/images/spacer.gif" width="15" height="15" alt="" border="0" />';
			echo '</div>';
			
			++$cnt;
		}//end while
	} else {
		echo '<br /><br />';
		echo $hc_lang_reports['NoEvent'];
	}//end if?>