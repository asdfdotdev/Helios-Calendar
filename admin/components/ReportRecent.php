<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(!defined('hcAdmin')){header("HTTP/1.1 403 No Direct Access");exit();}

	include(HCLANG.'/admin/reports.php');
	
	$order = (isset($_GET['m'])) ? 1 : 0;
	
	appInstructions(0, "Reports", $hc_lang_reports['TitleAdd'], $hc_lang_reports['InstructAdd']);
	
	$result = doQuery("SELECT PkID, Title, PublishDate, SeriesID, IsBillboard, LastMod
					FROM " . HC_TblPrefix . "events
					WHERE IsActive = 1 AND IsApproved = 1 AND StartDate >= '" . cIn(SYSDATE) . "' AND SeriesID IS NULL
					UNION
					SELECT SeriesID, Title, MIN(PublishDate), SeriesID, IsBillboard, MAX(LastMod)
					FROM " . HC_TblPrefix . "events
					WHERE IsActive = 1 AND IsApproved = 1 AND StartDate >= '" . cIn(SYSDATE) . "' AND (SeriesID IS NOT NULL AND SeriesID != '')
					GROUP BY SeriesID, Title, IsBillboard
					ORDER BY ".(($order == 0) ? 'PublishDate' : 'LastMod')." DESC
					LIMIT 100");
	if(hasRows($result)){
		echo '
		<ul class="data">
			<li class="row header uline">
				<div class="txt" style="width:54%;">&nbsp;</div>
				<div style="width:15%;"><a href="'.AdminRoot.'/index.php?com=reportrecent">'.$hc_lang_reports['Published'].'</a>'.(($order == 0) ? ' &#8595;' : '').'</div>
				<div style="width:15%;"><a href="'.AdminRoot.'/index.php?com=reportrecent&amp;m=1">'.$hc_lang_reports['LastMod'].'</a>'.(($order == 1) ? ' &#8595;' : '').'</div>
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
				<div class="txt" title="'.cOut($row[1]).'" style="width:55%;">'.cOut($row[1]).'</div>
				<div style="width:15%;">'.stampToDate($row[2], $hc_cfg[24]).'</div>
				<div style="width:15%;">'.stampToDate($row[5], $hc_cfg[24]).'</div>
				<div class="tools" style="width:15%;">
					'.(($row[3] != '') ? '
					<a href="' . AdminRoot . '/index.php?com=eventedit&amp;sID='.$row[0].'"><img src="' . AdminRoot . '/img/icons/edit_group.png" width="16" height="16" alt="" /></a>
					<a href="' . AdminRoot . '/index.php?com=searchresults&amp;srsID='.$row[0].'"><img src="' . AdminRoot . '/img/icons/view_series.png" width="16" height="16" alt="" /></a>
					':'
					<a href="'.AdminRoot.'/index.php?com=eventedit&amp;eID='.$row[0].'"><img src="'.AdminRoot.'/img/icons/edit.png" width="16" height="16" alt="" /></a>
					<img src="'.AdminRoot.'/img/spacer.gif" width="16" height="16" alt="" />').'
					'.(($row[4] == 1) ? '<a href="'.AdminRoot.'/index.php?com=eventbillboard"><img src="'.AdminRoot.'/img/icons/billboard.png" width="16" height="16" alt="" /></a>':'').'
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