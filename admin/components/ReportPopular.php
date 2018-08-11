<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(!defined('hcAdmin')){header("HTTP/1.1 403 No Direct Access");exit();}

	include(HCLANG.'/admin/reports.php');
	
	appInstructions(0, "Reports", $hc_lang_reports['TitlePop'], $hc_lang_reports['InstructPop']);
	
	$result = doQuery("SELECT PkID, Title, StartDate, PublishDate, (DATEDIFF('" . cIn(SYSDATE) . "', PublishDate)+1) as Diff, Views, (Views / (DATEDIFF('" . cIn(SYSDATE) . "', PublishDate)+1)) as Ave, IsBillboard
					FROM " . HC_TblPrefix . "events
					WHERE IsActive = 1 AND IsApproved = 1 AND Views > 0 AND StartDate >= '" . cIn(SYSDATE) . "'
					ORDER BY AVE DESC, StartDate LIMIT 100");
	if(hasRows($result)){
		echo '
		<ul class="data">
			<li class="row header uline">
				<div class="txt" style="width:38%;">&nbsp;</div>
				<div style="width:10%;">'.$hc_lang_reports['Occurs'].'</div>
				<div style="width:10%;">'.$hc_lang_reports['Published'].'</div>
				<div class="number" style="width:7%;">'.$hc_lang_reports['DaysActive'].'</div>
				<div class="number" style="width:12%;">'.$hc_lang_reports['Views'].'</div>
				<div class="number" style="width:12%;">'.$hc_lang_reports['Daily'].'</div>
				<div class="tools" style="width:12%;">&nbsp;</div>
			</li>
		</ul>
		<ul class="data">
		<div class="prpt">';
		
		$cnt = 0;
		while($row = mysql_fetch_row($result)){
			$hl = ($cnt % 2 == 1) ? ' hl':'';
		
			echo '
			<li class="row'.$hl.'">
				<div class="txt" title="'.cOut($row[1]).'" style="width:39%;">'.cOut('('.$row[0].') '.$row[1]).'</div>
				<div style="width:10%;">'.stampToDate($row[2], $hc_cfg[24]).'</div>
				<div style="width:10%;">'.stampToDate($row[3], $hc_cfg[24]).'</div>
				<div class="number" style="width:7%;">'.number_format(cOut($row[4]),0,'.',',').'</div>
				<div class="number" style="width:12%;">'.number_format(cOut($row[5]),0,'.',',').'</div>
				<div class="number" style="width:12%;">'.number_format(cOut($row[6]),2,'.',',').'</div>
				<div class="tools" style="width:10%;">
					<a href="'.AdminRoot.'/index.php?com=eventedit&amp;eID='.$row[0].'"><img src="'.AdminRoot.'/img/icons/edit.png" width="16" height="16" alt="" /></a>
					'.(($row[7] == 1) ? '<a href="'.AdminRoot.'/index.php?com=eventbillboard"><img src="'.AdminRoot.'/img/icons/billboard.png" width="16" height="16" alt="" /></a>':'').'
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