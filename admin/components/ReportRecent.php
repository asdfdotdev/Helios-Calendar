<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/admin/reports.php');
	
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