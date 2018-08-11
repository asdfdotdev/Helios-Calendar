<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(!defined('hcAdmin')){header("HTTP/1.1 403 No Direct Access");exit();}

	include(HCLANG.'/admin/reports.php');
	
	$eID = (isset($_POST['eventID'])) ? implode(',',array_filter($_POST['eventID'],'is_numeric')) : array();
	$hc_Side[] = array('javascript:doDownload();','download_csv.png',$hc_lang_reports['DownloadRep'],0);
	
	appInstructions(0, "Reports", $hc_lang_reports['TitleAct'], $hc_lang_reports['InstructAct']);
	
	echo '
		<p><a href="'.AdminRoot.'/index.php?com=eventsearch" class="add"><img src="'.AdminRoot.'/img/icons/report.png" width="16" height="16" alt="" />'.$hc_lang_reports['NewReport'].'</a></p>';
	
	if(count($eID) > 0){
		$result = doQuery("SELECT e.PkID, e.Title, e.StartDate, e.Views, e.Directions, e.Downloads, e.EmailToFriend, e.URLClicks
						FROM " . HC_TblPrefix . "events e
						WHERE e.PkID IN(" . cIn($eID) . ") ORDER BY e.PkID");
		if(hasRows($result)){
			$mViews = $mDir = $mDwnl = $mEmail = $mURL = $aViews = $aDir = $aDwnl = $aEmail = $aURL = $cnt = 0;
			$resultX = doQuery("SELECT MAX(Views), MAX(Directions), MAX(Downloads), MAX(EmailToFriend), MAX(URLClicks),
									AVG(Views), AVG(Directions), AVG(Downloads), AVG(EmailToFriend), AVG(URLClicks)
							FROM " . HC_TblPrefix . "events
							WHERE IsActive = 1 AND IsApproved = 1");
			if(hasRows($resultX)){
				$mViews = cOut(mysql_result($resultX,0,0));
				$mDir = cOut(mysql_result($resultX,0,1));
				$mDwnl = cOut(mysql_result($resultX,0,2));
				$mEmail = cOut(mysql_result($resultX,0,3));
				$mURL =cOut(mysql_result($resultX,0,4));
				$aViews = cOut(round(mysql_result($resultX,0,5), 0));
				$aDir = cOut(round(mysql_result($resultX,0,6), 0));
				$aDwnl = cOut(round(mysql_result($resultX,0,7), 0));
				$aEmail = cOut(round(mysql_result($resultX,0,8), 0));
				$aURL = cOut(round(mysql_result($resultX,0,9), 0));
			}

			echo '
			<ul class="data">
				<li class="row header">
					<div style="width:31%;">&nbsp;</div>
					<div class="number" style="width:12%;">'.$hc_lang_reports['Views'].'</div>
					<div class="number" style="width:12%;">'.$hc_lang_reports['Directions'].'</div>
					<div class="number" style="width:12%;">'.$hc_lang_reports['Downloads'].'</div>
					<div class="number" style="width:12%;">'.$hc_lang_reports['EmailTo'].'</div>
					<div class="number" style="width:12%;">'.$hc_lang_reports['URL'].'</div>
					<div class="tools" style="width:8%;">&nbsp;</div>
				</li>
				<li class="row header" style="margin:0;padding:3px 0 0 0;">
					<div class="number" style="width:31%;">'.$hc_lang_reports['CalAve'].'</div>
					<div class="number" style="width:12%;">'.number_format($aViews,0,'.',',').'</div>
					<div class="number" style="width:12%;">'.number_format($aDir,0,'.',',').'</div>
					<div class="number" style="width:12%;">'.number_format($aDwnl,0,'.',',').'</div>
					<div class="number" style="width:12%;">'.number_format($aEmail,0,'.',',').'</div>
					<div class="number" style="width:12%;">'.number_format($aURL,0,'.',',').'</div>
					<div class="tools" style="width:8%;">&nbsp;</div>
				</li>
				<li class="row uline header" style="margin:0;padding:3px 0 0 0;">
					<div class="number" style="width:31%;">'.$hc_lang_reports['CalBest'].'</div>
					<div class="number" style="width:12%;">'.number_format($mViews,0,'.',',').'</div>
					<div class="number" style="width:12%;">'.number_format($mDir,0,'.',',').'</div>
					<div class="number" style="width:12%;">'.number_format($mDwnl,0,'.',',').'</div>
					<div class="number" style="width:12%;">'.number_format($mEmail,0,'.',',').'</div>
					<div class="number" style="width:12%;">'.number_format($mURL,0,'.',',').'</div>
					<div class="tools" style="width:8%;">&nbsp;</div>
				</li>
			</ul>
			<ul class="data">
			<div class="arpt">';

			while($row = mysql_fetch_row($result)){
				$hl = ($cnt % 2 == 1) ? ' hl':'';
				echo '
				<li class="row'.$hl.'">
					<div class="txt" title="'.cOut($row[1]).'" style="width:32%;">'.cOut('('.$row[0].') '.$row[1]).'</div>
					<div class="number" style="width:12%;">'.number_format(cOut($row[3]),0,'.',',').'</div>
					<div class="number" style="width:12%;">'.number_format(cOut($row[4]),0,'.',',').'</div>
					<div class="number" style="width:12%;">'.number_format(cOut($row[5]),0,'.',',').'</div>
					<div class="number" style="width:12%;">'.number_format(cOut($row[6]),0,'.',',').'</div>
					<div class="number" style="width:12%;">'.number_format(cOut($row[7]),0,'.',',').'</div>
					<div class="tools" style="width:8%;">
						'.((!isset($print)) ? '<a href="'.AdminRoot.'/index.php?com=eventedit&amp;eID='.$row[0].'" target="_blank"><img src="'.AdminRoot.'/img/icons/edit_new.png" width="16" height="16" alt="" /></a>
						<a href="'.CalRoot.'/index.php?&amp;eID='.$row[0].'" target="_blank"><img src="'.AdminRoot.'/img/icons/calendar.png" width="16" height="16" alt="" /></a>' : '&nbsp;').'
					</div>
				</li>';
			++$cnt;
			}
			
			unset($_SESSION['ReportDownload']);
			$_SESSION['ReportDownload'] = utf8_encode($eID);
				   
			echo '
			</div>
			</ul>
			
			<script>
			//<!--
			function doDownload(){
				document.location.href = "'.AdminRoot.'/components/ReportActivityD.php";
			}
			//-->
			</script>';
		} else {
			echo '<p>'.$hc_lang_reports['InvalidEvent'].'</p>';
		}
	} else {
		echo '<p>'.$hc_lang_reports['InvalidEvent'].'</p>';
	}
?>