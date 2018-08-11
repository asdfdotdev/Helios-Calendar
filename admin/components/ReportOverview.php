<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(!defined('hcAdmin')){header("HTTP/1.1 403 No Direct Access");exit();}

	include(HCLANG.'/admin/reports.php');
	
	appInstructions(0, "Reports", $hc_lang_reports['TitleOver'], $hc_lang_reports['InstructOver']);
	
	$na = $hc_lang_reports['NA'];
	
	$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1");
	$tCnt = (hasRows($result)) ? mysql_result($result,0,0) : '0';
	$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND StartDate >= '" . cIn(SYSDATE) . "'");
	$aCnt = (hasRows($result)) ? mysql_result($result,0,0) : '0';
	$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND StartDate < '" . cIn(SYSDATE) . "'");
	$pCnt = (hasRows($result)) ? mysql_result($result,0,0) : '0';
	
	$stats[$hc_lang_reports['ActiveLabel']] = ($aCnt != '') ? number_format($aCnt,0,'.',',') : $na;
	$stats[$hc_lang_reports['PassedLabel']] = ($pCnt != '') ? number_format($pCnt,0,'.',',') : $na;
	$stats[$hc_lang_reports['TotalLabel']] = ($tCnt != '') ? number_format($tCnt,0,'.',',') : $na;
	$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND IsBillboard = 1 AND StartDate >= '" . cIn(SYSDATE) . "'");
	$stats[$hc_lang_reports['Billboard']] = (hasRows($result)) ? number_format(mysql_result($result,0,0),0,'.',',') : $na;
	$result = doQuery("SELECT COUNT(*)
					FROM " . HC_TblPrefix . "events e
						LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (e.PkID = ec.EventID)
						LEFT JOIN " . HC_TblPrefix . "categories c ON (c.PkID = ec.CategoryID)
					WHERE e.IsActive = 1 AND e.IsApproved = 1 AND (ec.EventID IS NULL OR c.IsActive = 0)");
	$stats[$hc_lang_reports['Orphan']] = (hasRows($result)) ? number_format(mysql_result($result,0,0),0,'.',',') : $na;
	$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND StartDate = '" . cIn(SYSDATE) . "'");
	$stats[$hc_lang_reports['Today']] = (hasRows($result)) ? number_format(mysql_result($result,0,0),0,'.',',') : $na;
	$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND StartDate BETWEEN '" . cIn(SYSDATE) . "' AND ADDDATE('" . cIn(SYSDATE) . "',INTERVAL 7 DAY)");
	$stats[$hc_lang_reports['Next7']] = (hasRows($result)) ? number_format(mysql_result($result,0,0),0,'.',',') : $na;
	$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND StartDate BETWEEN '" . cIn(SYSDATE) . "' AND ADDDATE('" . cIn(SYSDATE) . "',INTERVAL 30 DAY)");	
	$stats[$hc_lang_reports['Next30']] = (hasRows($result)) ? number_format(mysql_result($result,0,0),0,'.',',') : $na;
	$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "subscribers WHERE IsConfirm = 1");
	$stats[$hc_lang_reports['ActiveUsers']] = (hasRows($result)) ? number_format(mysql_result($result,0,0),0,'.',',') : $na;
	$result = doQuery("SELECT MIN(StartDate) FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND StartDate IS NOT NULL");
	$stats[$hc_lang_reports['Earliest']] = (hasRows($result)) ? stampToDate(mysql_result($result,0,0),$hc_cfg[24]) : $na;
	$result = doQuery("SELECT MAX(StartDate) FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND StartDate IS NOT NULL");
	$stats[$hc_lang_reports['Latest']] = (hasRows($result)) ? stampToDate(mysql_result($result,0,0),$hc_cfg[24]) : $na;
	
	echo '
	<ul class="data">
		<li class="row header uline">
			<div style="width:80%;">'.$hc_lang_reports['General'].'</div>
			<div style="width:20%;">&nbsp;</div>
		</li>';
	$cnt = 0;
	foreach($stats as $key => $val){
		$hl = ($cnt % 2 == 1) ? ' hl':'';
		echo '
		<li class="row'.$hl.'">
			<div style="width:80%;">'.$key.'</div>
			<div class="number" style="width:20%;">'.$val.'</div>
		</li>';
		++$cnt;
	}
	echo '
	</ul>';
	
	$result = doQuery("SELECT SUM(Views), SUM(Directions), SUM(Downloads), SUM(EmailToFriend), SUM(URLClicks)
					FROM " . HC_TblPrefix . "events
					WHERE IsActive = 1 AND IsApproved = 1 AND StartDate >= '" . cIn(SYSDATE) . "'");
	if(hasRows($result)){
		echo '
	<ul class="data">
		<li class="row header uline">
			<div style="width:70%;">'.$hc_lang_reports['Active'].'</div>
			<div class="number" style="width:15%;">'.$hc_lang_reports['Average'].'</div>
			<div class="number" style="width:15%;">'.$hc_lang_reports['Total'].'</div>
		</li>
		<li class="row">
			<div style="width:70%;">'.$hc_lang_reports['Views'].'</div>
			<div class="number" style="width:15%;">'.(($aCnt > 0) ? number_format((mysql_result($result,0,0)/$aCnt),2,'.',','):'0').'</div>
			<div class="number" style="width:15%;">'.number_format(mysql_result($result,0,0),0,'.',',').'</div>
		</li>
		<li class="row hl">
			<div style="width:70%;">'.$hc_lang_reports['DriveDir'].'</div>
			<div class="number" style="width:15%;">'.(($aCnt > 0) ? number_format((mysql_result($result,0,1)/$aCnt),2,'.',','):'0').'</div>
			<div class="number" style="width:15%;">'.number_format(mysql_result($result,0,1),0,'.',',').'</div>
		</li>
		<li class="row">
			<div style="width:70%;">'.$hc_lang_reports['Downloads'].'</div>
			<div class="number" style="width:15%;">'.(($aCnt > 0) ? number_format((mysql_result($result,0,2)/$aCnt),2,'.',','):'0').'</div>
			<div class="number" style="width:15%;">'.number_format(mysql_result($result,0,2),0,'.',',').'</div>
		</li>
		<li class="row hl">
			<div style="width:70%;">'.$hc_lang_reports['EmailTo'].'</div>
			<div class="number" style="width:15%;">'.(($aCnt > 0) ? number_format((mysql_result($result,0,3)/$aCnt),2,'.',','):'0').'</div>
			<div class="number" style="width:15%;">'.number_format(mysql_result($result,0,3),0,'.',',').'</div>
		</li>
		<li class="row">
			<div style="width:70%;">'.$hc_lang_reports['URL'].'</div>
			<div class="number" style="width:15%;">'.(($aCnt > 0) ? number_format((mysql_result($result,0,4)/$aCnt),2,'.',','):'0').'</div>
			<div class="number" style="width:15%;">'.number_format(mysql_result($result,0,4),0,'.',',').'</div>
		</li>
	</ul>';
	}
	
	$result = doQuery("SELECT SUM(Views), SUM(Directions), SUM(Downloads), SUM(EmailToFriend), SUM(URLClicks)
					FROM " . HC_TblPrefix . "events
					WHERE IsActive = 1 AND IsApproved = 1 AND StartDate < '" . cIn(SYSDATE) . "'");
	if(hasRows($result)){
		echo '
	<ul class="data">
		<li class="row header uline">
			<div style="width:70%;">'.$hc_lang_reports['Passed'].'</div>
			<div class="number" style="width:15%;">'.$hc_lang_reports['Average'].'</div>
			<div class="number" style="width:15%;">'.$hc_lang_reports['Total'].'</div>
		</li>
		<li class="row">
			<div style="width:70%;">'.$hc_lang_reports['Views'].'</div>
			<div class="number" style="width:15%;">'.(($pCnt > 0) ? number_format((mysql_result($result,0,0)/$pCnt),2,'.',','):'0').'</div>
			<div class="number" style="width:15%;">'.number_format(mysql_result($result,0,0),0,'.',',').'</div>
		</li>
		<li class="row hl">
			<div style="width:70%;">'.$hc_lang_reports['DriveDir'].'</div>
			<div class="number" style="width:15%;">'.(($pCnt > 0) ? number_format((mysql_result($result,0,1)/$pCnt),2,'.',','):'0').'</div>
			<div class="number" style="width:15%;">'.number_format(mysql_result($result,0,1),0,'.',',').'</div>
		</li>
		<li class="row">
			<div style="width:70%;">'.$hc_lang_reports['Downloads'].'</div>
			<div class="number" style="width:15%;">'.(($pCnt > 0) ? number_format((mysql_result($result,0,2)/$pCnt),2,'.',','):'0').'</div>
			<div class="number" style="width:15%;">'.number_format(mysql_result($result,0,2),0,'.',',').'</div>
		</li>
		<li class="row hl">
			<div style="width:70%;">'.$hc_lang_reports['EmailTo'].'</div>
			<div class="number" style="width:15%;">'.(($pCnt > 0) ? number_format((mysql_result($result,0,3)/$pCnt),2,'.',','):'0').'</div>
			<div class="number" style="width:15%;">'.number_format(mysql_result($result,0,3),0,'.',',').'</div>
		</li>
		<li class="row">
			<div style="width:70%;">'.$hc_lang_reports['URL'].'</div>
			<div class="number" style="width:15%;">'.(($pCnt > 0) ? number_format((mysql_result($result,0,4)/$pCnt),2,'.',','):'0').'</div>
			<div class="number" style="width:15%;">'.number_format(mysql_result($result,0,4),0,'.',',').'</div>
		</li>
	</ul>';
	}
	
	$result = doQuery("SELECT SUM(Views), SUM(Directions), SUM(Downloads), SUM(EmailToFriend), SUM(URLClicks)
					FROM " . HC_TblPrefix . "events
					WHERE IsActive = 1 AND IsApproved = 1");
	if(hasRows($result)){
		echo '
	<ul class="data">
		<li class="row header uline">
			<div style="width:70%;">'.$hc_lang_reports['AllEvents'].'</div>
			<div class="number" style="width:15%;">'.$hc_lang_reports['Average'].'</div>
			<div class="number" style="width:15%;">'.$hc_lang_reports['Total'].'</div>
		</li>
		<li class="row">
			<div style="width:70%;">'.$hc_lang_reports['Views'].'</div>
			<div class="number" style="width:15%;">'.(($tCnt > 0) ? number_format((mysql_result($result,0,0)/$tCnt),2,'.',','):'0').'</div>
			<div class="number" style="width:15%;">'.number_format(mysql_result($result,0,0),0,'.',',').'</div>
		</li>
		<li class="row hl">
			<div style="width:70%;">'.$hc_lang_reports['DriveDir'].'</div>
			<div class="number" style="width:15%;">'.(($tCnt > 0) ? number_format((mysql_result($result,0,1)/$tCnt),2,'.',','):'0').'</div>
			<div class="number" style="width:15%;">'.number_format(mysql_result($result,0,1),0,'.',',').'</div>
		</li>
		<li class="row">
			<div style="width:70%;">'.$hc_lang_reports['Downloads'].'</div>
			<div class="number" style="width:15%;">'.(($tCnt > 0) ? number_format((mysql_result($result,0,2)/$tCnt),2,'.',','):'0').'</div>
			<div class="number" style="width:15%;">'.number_format(mysql_result($result,0,2),0,'.',',').'</div>
		</li>
		<li class="row hl">
			<div style="width:70%;">'.$hc_lang_reports['EmailTo'].'</div>
			<div class="number" style="width:15%;">'.(($tCnt > 0) ? number_format((mysql_result($result,0,3)/$tCnt),2,'.',','):'0').'</div>
			<div class="number" style="width:15%;">'.number_format(mysql_result($result,0,3),0,'.',',').'</div>
		</li>
		<li class="row">
			<div style="width:70%;">'.$hc_lang_reports['URL'].'</div>
			<div class="number" style="width:15%;">'.(($tCnt > 0) ? number_format((mysql_result($result,0,4)/$tCnt),2,'.',','):'0').'</div>
			<div class="number" style="width:15%;">'.number_format(mysql_result($result,0,4),0,'.',',').'</div>
		</li>
	</ul>';
	}
	
	$result = doQuery("SELECT Title, StartDate, Views FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND Views > 0 ORDER BY Views DESC, Title LIMIT 50");
	if(hasRows($result)){
		echo '
	<ul class="data">
		<li class="row header uline">
			<div style="width:72%;">'.$hc_lang_reports['MostViewed'].'</div>
			<div class="number" style="width:10%;">'.$hc_lang_reports['Date'].'</div>
			<div class="number" style="width:15%;">'.$hc_lang_reports['Count'].'</div>
			<div style="width:2%;">&nbsp;</div>
		</li>
	</ul>
	<div class="ostat">
	<ul class="data">';
		$cnt = 0;
		
		while($row = mysql_fetch_row($result)){
			$hl = ($cnt % 2 == 1) ? ' hl':'';
			echo '
		<li class="row'.$hl.'">
			<div class="txt" title="'.cOut($row[0]).'" style="width:74%;">'.cOut($row[0]).'</div>
			<div class="number" style="width:10%;">'.stampToDate($row[1], $hc_cfg[24]).'</div>
			<div class="number" style="width:15%;">'.number_format($row[2],0,'.',',').'</div>
		</li>';
			++$cnt;
		}
		echo '
	</ul>
	</div>';
	}
	
	$result = doQuery("SELECT Title, StartDate, Directions FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND Directions > 0 ORDER BY Directions DESC, Title LIMIT 50");
	if(hasRows($result)){
		echo '
	<ul class="data">
		<li class="row header uline">
			<div style="width:72%;">'.$hc_lang_reports['MostDirections'].'</div>
			<div class="number" style="width:10%;">'.$hc_lang_reports['Date'].'</div>
			<div class="number" style="width:15%;">'.$hc_lang_reports['Count'].'</div>
			<div style="width:2%;">&nbsp;</div>
		</li>
	</ul>
	<div class="ostat">
	<ul class="data">';
		$cnt = 0;
		
		while($row = mysql_fetch_row($result)){
			$hl = ($cnt % 2 == 1) ? ' hl':'';
			echo '
		<li class="row'.$hl.'">
			<div class="txt" title="'.cOut($row[0]).'" style="width:74%;">'.cOut($row[0]).'</div>
			<div class="number" style="width:10%;">'.stampToDate($row[1], $hc_cfg[24]).'</div>
			<div class="number" style="width:15%;">'.number_format($row[2],0,'.',',').'</div>
		</li>';
			++$cnt;
		}
		echo '
	</ul>
	</div>';
	}
	
	$result = doQuery("SELECT Title, StartDate, Downloads FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND Downloads > 0 ORDER BY Downloads DESC, Title LIMIT 50");
	if(hasRows($result)){
		echo '
	<ul class="data">
		<li class="row header uline">
			<div style="width:72%;">'.$hc_lang_reports['MostDownloads'].'</div>
			<div class="number" style="width:10%;">'.$hc_lang_reports['Date'].'</div>
			<div class="number" style="width:15%;">'.$hc_lang_reports['Count'].'</div>
			<div style="width:2%;">&nbsp;</div>
		</li>
	</ul>
	<div class="ostat">
	<ul class="data">';
		$cnt = 0;
		
		while($row = mysql_fetch_row($result)){
			$hl = ($cnt % 2 == 1) ? ' hl':'';
			echo '
		<li class="row'.$hl.'">
			<div class="txt" title="'.cOut($row[0]).'" style="width:74%;">'.cOut($row[0]).'</div>
			<div class="number" style="width:10%;">'.stampToDate($row[1], $hc_cfg[24]).'</div>
			<div class="number" style="width:15%;">'.number_format($row[2],0,'.',',').'</div>
		</li>';
			++$cnt;
		}
		echo '
	</ul>
	</div>';
	}
	
	$result = doQuery("SELECT Title, StartDate, EmailToFriend FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND EmailToFriend > 0 ORDER BY EmailToFriend DESC, Title LIMIT 10");
	if(hasRows($result)){
		echo '
	<ul class="data">
		<li class="row header uline">
			<div style="width:72%;">'.$hc_lang_reports['MostEmail'].'</div>
			<div class="number" style="width:10%;">'.$hc_lang_reports['Date'].'</div>
			<div class="number" style="width:15%;">'.$hc_lang_reports['Count'].'</div>
			<div style="width:2%;">&nbsp;</div>
		</li>
	</ul>
	<div class="ostat">
	<ul class="data">';
		$cnt = 0;
		
		while($row = mysql_fetch_row($result)){
			$hl = ($cnt % 2 == 1) ? ' hl':'';
			echo '
		<li class="row'.$hl.'">
			<div class="txt" title="'.cOut($row[0]).'" style="width:74%;">'.cOut($row[0]).'</div>
			<div class="number" style="width:10%;">'.stampToDate($row[1], $hc_cfg[24]).'</div>
			<div class="number" style="width:15%;">'.number_format($row[2],0,'.',',').'</div>
		</li>';
			++$cnt;
		}
		echo '
	</ul>
	</div>';
	}
	
	$result = doQuery("SELECT Title, StartDate, URLClicks FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND URLClicks > 0 ORDER BY URLClicks DESC, Title LIMIT 10");
	if(hasRows($result)){
		echo '
	<ul class="data">
		<li class="row header uline">
			<div style="width:72%;">'.$hc_lang_reports['MostURL'].'</div>
			<div class="number" style="width:10%;">'.$hc_lang_reports['Date'].'</div>
			<div class="number" style="width:15%;">'.$hc_lang_reports['Count'].'</div>
			<div style="width:2%;">&nbsp;</div>
		</li>
	</ul>
	<div class="ostat">
	<ul class="data">';
		$cnt = 0;
		
		while($row = mysql_fetch_row($result)){
			$hl = ($cnt % 2 == 1) ? ' hl':'';
			echo '
		<li class="row'.$hl.'">
			<div class="txt" title="'.cOut($row[0]).'" style="width:74%;">'.cOut($row[0]).'</div>
			<div class="number" style="width:10%;">'.stampToDate($row[1], $hc_cfg[24]).'</div>
			<div class="number" style="width:15%;">'.number_format($row[2],0,'.',',').'</div>
		</li>';
			++$cnt;
		}
		echo '
	</ul>
	</div>';
	}
	
	$result = doQuery("SELECT FirstName, LastName, Email, RegisteredAt FROM " . HC_TblPrefix . "subscribers WHERE IsConfirm = 1 ORDER BY RegisteredAt DESC, LastName, FirstName LIMIT 50");
	if(hasRows($result)){
		echo '
	<ul class="data">
		<li class="row header uline">
			<div style="width:88%;">'.$hc_lang_reports['NewestUsers'].'</div>
			<div style="width:10%;">'.$hc_lang_reports['Registered'].'</div>
			<div style="width:2%;">&nbsp;</div>
		</li>
	</ul>
	<div class="ostat">
	<ul class="data">';
		$cnt = 0;
		
		while($row = mysql_fetch_row($result)){
			$hl = ($cnt % 2 == 1) ? ' hl':'';
			echo '
		<li class="row'.$hl.'">
			<div class="txt" title="'.cOut($row[0]).'" style="width:30%;">'.cOut(trim($row[1].', '.$row[0])).'</div>
			<div style="width:60%;">'.cOut($row[2]).'</div>
			<div class="number" style="width:10%;">'.stampToDate($row[3], $hc_cfg[24]).'</div>
		</li>';
			++$cnt;
		}
		echo '
	</ul>
	</div>';
	}
?>