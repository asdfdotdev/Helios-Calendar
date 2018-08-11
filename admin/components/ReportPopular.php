<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/admin/reports.php');
	
	if(!isset($_GET['mID'])){
		appInstructions(0, "Reports", $hc_lang_reports['TitlePop'], $hc_lang_reports['InstructPop']);
		$result = doQuery("	SELECT * 
								FROM " . HC_TblPrefix . "events 
							WHERE StartDate >= '" . date("Y-m-d") . "' 
								AND IsActive = 1 
								AND IsApproved = 1
								AND Views > 0
							ORDER BY Views DESC, StartDate
							LIMIT 50");
	} else {
		appInstructions(0, "Reports", $hc_lang_reports['TitlePopM'], $hc_lang_reports['InstructPopM']);
		$result = doQuery("	SELECT * 
								FROM " . HC_TblPrefix . "events 
							WHERE StartDate >= '" . date("Y-m-d") . "' 
								AND IsActive = 1 
								AND IsApproved = 1 
								AND MViews > 0
							ORDER BY MViews DESC, StartDate
							LIMIT 50");
	}//end if
	
	if(hasRows($result)){
		echo '<div class="mostPopularList">';
		echo '<div class="mostPopularTitle"><b>' . $hc_lang_reports['Event'] . '</b></div>';
		echo '<div class="mostPopularDate"><b>' . $hc_lang_reports['Occurs'] . '</b></div>';
		echo '<div class="mostPopularViews"><b>' . $hc_lang_reports['Views'] . '</b></div>';
		echo '&nbsp;</div>';
		
		$cnt = 0;
		$curDate = "";
		while($row = mysql_fetch_row($result)){
			$viewCount = (isset($_GET['mID'])) ? $row[34] : $row[28];
			if($viewCount == 0){break;}
			
			echo ($cnt % 2 == 1) ? '<div class="mostPopularTitleHL">' : '<div class="mostPopularTitle">';
			echo $row[1] . '</div>';
			
			echo ($cnt % 2 == 1) ? '<div class="mostPopularDateHL">' : '<div class="mostPopularDate">';
			echo stampToDate($row[9], $hc_cfg24) . '</div>';
			
			echo ($cnt % 2 == 1) ? '<div class="mostPopularViewsHL">' : '<div class="mostPopularViews">';
			echo cOut($viewCount) . '</div>';
			
			echo ($cnt % 2 == 1) ? '<div class="mostPopularToolsHL">' : '<div class="mostPopularTools">';
			echo '<a href="' . CalAdminRoot . '/index.php?com=eventedit&amp;eID=' . $row[0] . '" class="main"><img src="' . CalAdminRoot . '/images/icons/iconEdit.png" width="15" height="15" alt="" border="0" style="vertical-align:middle;" /></a>&nbsp;&nbsp;';
			
			echo ($row[18] == 1) ?
				'<a href="' . CalAdminRoot . '/index.php?com=eventbillboard" class="main"><img src="' . CalAdminRoot . '/images/icons/iconBillboard.png" width="15" height="15" alt="" border="0" style="vertical-align:middle;" /></a>':
				'<img src="' . CalAdminRoot . '/images/spacer.gif" width="15" height="15" alt="" border="0" style="vertical-align:middle;" />';
			echo '</div>';
			
			++$cnt;
		}//end while
	} else {
		echo '<br /><br />';
		echo $hc_lang_report['NoEvents'];
	}//end if	?>