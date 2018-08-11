<?php
/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development LLC
 * @link http://www.refreshmy.com
 * @copyright (C) 2004-2011 Refresh Web Development
 * @license http://www.helioscalendar.com/license.html
 * @package Helios Calendar
 */
	if(!defined('hcAdmin')){header("HTTP/1.1 403 No Direct Access");exit();}

	include(HCLANG.'/admin/reports.php');
	
	appInstructions(0, "Reports", $hc_lang_reports['TitleAdd'], $hc_lang_reports['InstructAdd']);
	
	$result = doQuery("SELECT PkID, Title, StartDate, PublishDate, SubmittedAt, SeriesID, IsBillboard
					FROM " . HC_TblPrefix . "events
					WHERE IsActive = 1 AND IsApproved = 1 AND StartDate >= '" . cIn(SYSDATE) . "' AND SeriesID IS NULL
					UNION
					SELECT PkID, Title, StartDate, PublishDate, SubmittedAt, SeriesID, IsBillboard
					FROM " . HC_TblPrefix . "events
					WHERE IsActive = 1 AND IsApproved = 1 AND StartDate >= '" . cIn(SYSDATE) . "' AND SeriesID IS NOT NULL
					GROUP BY SeriesID
					ORDER BY PublishDate DESC, StartDate
					LIMIT 100");
	if(hasRows($result)){
		echo '
		<ul class="data">
			<li class="row header uline">
				<div class="txt" style="width:52%;">&nbsp;</div>
				<div style="width:11%;">'.$hc_lang_reports['Occurs'].'</div>
				<div style="width:12%;">'.$hc_lang_reports['Published'].'</div>
				<div style="width:11%;">'.$hc_lang_reports['Added'].'</div>
				<div class="tools" style="width:15%;">&nbsp;</div>
			</li>
		</ul>
		<ul class="data">
		<div class="rrpt">';
		
		$cnt = 0;
		while($row = mysql_fetch_row($result)){
			$hl = ($cnt % 2 == 1) ? ' hl':'';
		
			echo '
			<li class="row'.$hl.'">
				<div class="txt" title="'.cOut($row[1]).'" style="width:53%;">'.cOut('('.(($row[5] != '') ? '<i>'.$row[0].'</i>' : $row[0]).') '.$row[1]).'</div>
				<div style="width:12%;">'.stampToDate($row[2], $hc_cfg[24]).'</div>
				<div style="width:12%;">'.stampToDate($row[3], $hc_cfg[24]).'</div>
				<div style="width:11%;">'.(($row[4] != '') ? $hc_lang_reports['ByPub'] : $hc_lang_reports['ByAdmin']).'</div>
				<div class="tools" style="width:12%;">
					'.(($row[5] != '') ? '
					<a href="' . AdminRoot . '/index.php?com=eventedit&amp;sID='.$row[5].'"><img src="' . AdminRoot . '/img/icons/edit_group.png" width="16" height="16" alt="" /></a>
					<a href="' . AdminRoot . '/index.php?com=searchresults&amp;srsID='.$row[5].'"><img src="' . AdminRoot . '/img/icons/view_series.png" width="16" height="16" alt="" /></a>
					':'
					<a href="'.AdminRoot.'/index.php?com=eventedit&amp;eID='.$row[0].'"><img src="'.AdminRoot.'/img/icons/edit.png" width="16" height="16" alt="" /></a>
					<img src="'.AdminRoot.'/img/spacer.gif" width="16" height="16" alt="" />').'
					'.(($row[6] == 1) ? '<a href="'.AdminRoot.'/index.php?com=eventbillboard"><img src="'.AdminRoot.'/img/icons/billboard.png" width="16" height="16" alt="" /></a>':'').'
				</div>
			</li>';
			++$cnt;
		}
		echo '
		</div>
		</ul>';
	} else {
		echo '
		<p>'.$hc_lang_reports['NoEvent'].'</p>';
	}
?>