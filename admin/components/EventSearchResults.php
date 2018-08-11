<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(!defined('hcAdmin')){header("HTTP/1.1 403 No Direct Access");exit();}
		
	include(HCLANG.'/admin/search.php');
	
	$sID = (isset($_POST['sID']) && is_numeric($_POST['sID'])) ? cIn($_POST['sID']) : 0;
	$startDate = (isset($_POST['startDate'])) ? strtotime(dateToMySQL(cIn(strip_tags($_POST['startDate'])), $hc_cfg[24])) : strtotime('1970-01-01');
	$endDate = (isset($_POST['endDate'])) ? strtotime(dateToMySQL(cIn(strip_tags($_POST['endDate'])), $hc_cfg[24])) : strtotime('1970-01-01');
	$keyword = (isset($_POST['keyword'])) ? strip_tags(str_replace("'","\"",$_POST['keyword'])) : '';
	$location = (isset($_POST['locPreset'])) ? cIn(strip_tags($_POST['locPreset'])) : '';
	$city = (isset($_POST['city'])) ? cIn(strip_tags($_POST['city'])) : '';
	$state = (isset($_POST['locState'])) ? cIn(strip_tags($_POST['locState'])) : '';
	$postal = (isset($_POST['postal'])) ? cIn(strip_tags($_POST['postal'])) : '';
	$catIDs = (isset($_POST['catID'])) ? implode(',',array_filter($_POST['catID'],'is_numeric')) : '';
	$seriesOnly = (isset($_POST['seriesonly'])) ? 1 : 0;
	$series = (isset($_GET['srsID'])) ? cIn(strip_tags($_GET['srsID'])) : '';
	$usrID = (isset($_POST['usrID']) && is_numeric($_POST['usrID'])) ? cIn($_POST['usrID']) : 0;
	
	if(isset($_GET['msg']) && is_numeric($_GET['msg'])){
		switch($_GET['msg']){
			case "1" :
				feedback(1, $hc_lang_search['Feed03']);
				break;
		}
	}
	if($sID == 1 || $series != ''){
		$sID = 1;
		$hlpText = ($seriesOnly == 1) ? $hc_lang_search['InstructEditRS'] : $hc_lang_search['InstructEditR'];
		appInstructions(1, "Editing_Events", $hc_lang_search['TitleEditR'], $hlpText);
		$frmAction = AdminRoot . "/index.php?com=eventedit";
		$buttonText = $hc_lang_search['EditGroup'];
	} elseif($sID == 2){
		appInstructions(1, "Delete_Event", $hc_lang_search['TitleDeleteR'], $hc_lang_search['InstructDeleteR']);
		$frmAction = AdminRoot . "/components/EventDelete.php";
		$buttonText = $hc_lang_search['DeleteGroup'];
	} elseif($sID == 3){
		appInstructions(1, "Event_Series", $hc_lang_search['TitleSeriesR'], $hc_lang_search['InstructSeriesR']);
		$frmAction = AdminRoot . "/components/EventCreateSeries.php";
		$buttonText = $hc_lang_search['CreateSeries'];
	} else {
		appInstructions(1, "Reports", $hc_lang_search['TitleReportR'], $hc_lang_search['InstructReportR']);
		$frmAction = AdminRoot . "/index.php?com=reportactivity";
		$buttonText = $hc_lang_search['GenerateReport'];
	}
	
	$query = "SELECT DISTINCT e.PkID, e.Title, e.StartDate, e.StartTime, e.EndTime, e.TBD, e.SeriesID, l.City, l.IsActive
			FROM " . HC_TblPrefix . "events as e
				INNER JOIN " . HC_TblPrefix . "eventcategories as ec ON (e.PkID = ec.EventID)
				LEFT JOIN " . HC_TblPrefix . "locations as l ON (e.LocID = l.PkID) ";
	if($series != ''){
		$query .= "WHERE SeriesID = '".$series."' AND e.IsActive = 1 AND e.IsApproved = 1";
	} else {
		$query .= "WHERE (StartDate BETWEEN '".date("Y-m-d",$startDate)."' AND '".date("Y-m-d",$endDate)."')
						AND e.IsActive = 1 AND e.IsApproved = 1 ";
		$query .= ($keyword != '') ? " AND MATCH(Title,LocationName,Description) AGAINST('".cIn($keyword,0)."' IN BOOLEAN MODE) " : '';
		$query .= ($location > 0) ? " AND l.PkID = '" . $location  . "'" : '';
		$query .= ($city != '') ? " AND (l.IsActive = 1 OR l.IsActive is NULL) AND (e.LocationCity = '".$city."' OR l.City = '".$city."')" : '';
		$query .= ($state != '') ? " AND (e.LocationState = '" . cIn($state) . "' or l.State = '" . cIn($state) . "')" : '';
		$query .= ($postal != '') ? " AND (e.LocationZip = '" . cIn($postal) . "' or l.Zip = '" . cIn($postal) . "')" : '';
		$query .= ($catIDs != '') ? " AND (ec.CategoryID In(" . cIn($catIDs) . "))" : '';
	}
	if($usrID > 0)
		$query .= " AND e.OwnerID = '" . $usrID . "'";

	$query .= ($seriesOnly == 1) ? " AND SeriesID != '' GROUP BY e.PkID, e.Title, e.StartDate, e.StartTime, e.EndTime, e.TBD, SeriesID, l.City, l.IsActive " : '';
	$query .= " ORDER BY e.StartDate, e.TBD, e.StartTime, e.Title";
	
	$result = doQuery($query);
	$row_cnt = mysql_num_rows($result);
	
	if(hasRows($result)){
		$cnt = $curDate = 0;
		$ctrls = ($seriesOnly == 0) ? '
		<div class="catCtrl">
			[ <a href="javascript:;" onclick="checkAllArray(\'eventSearchResult\',\'eventID[]\');">'.$hc_lang_core['SelectAll'].'</a>
			&nbsp;|&nbsp; <a href="javascript:;" onclick="uncheckAllArray(\'eventSearchResult\',\'eventID[]\');">'.$hc_lang_core['DeselectAll'].'</a> ]
		</div>' : '';
		
		echo $ctrls;
		echo '
		<form name="eventSearchResult" id="eventSearchResult" method="post" action="'.$frmAction.'" onsubmit="return validate();">';
		set_form_token();
		echo '
		<ul class="data">';
		while($row = mysql_fetch_row($result)){
			if($curDate != $row[2]){
				$cnt = 1;
				$curDate = $row[2];
				echo '
			<li class="header row uline">'.stampToDate($row[2], $hc_cfg[14]).'</li>';
			}
			
			echo '
			<li class="row'.(($cnt % 2 == 0) ? ' hl' : '').'">
				<div class="txt" style="width:20%;">';
			if($row[5] == 0){
				$time = ($row[3] != '') ? stampToDate($row[3], $hc_cfg[23]) : '';
				$time .= ($row[4] != '') ? ' - ' . stampToDate($row[4], $hc_cfg[23]) : '';
			} else {
				$time = ($row[5] == 1) ? $hc_lang_search['AllDay'] : $hc_lang_search['TBD'];
			}
			echo $time.'</div>
				<div class="txt" style="width:65%;" title="'.cOut($row[1]).'">'.cOut($row[1]).'</div>
				<div class="tools" style="width:15%;">';
			if(($sID == 1 || $sID == 2) && $seriesOnly == 0){
				echo '
					<a href="'.AdminRoot.'/index.php?com=eventedit&amp;eID='.$row[0].'" class="tools"><img src="'.AdminRoot.'/img/icons/edit.png" width="16" height="16" alt="" /></a>
					<a href="'.AdminRoot.'/index.php?com=eventedit&amp;eID='.$row[0].'" class="tools" target="_blank"><img src="'.AdminRoot.'/img/icons/edit_new.png" width="16" height="16" alt="" /></a>
					<a href="'.AdminRoot.'/index.php?com=eventadd&amp;eID='.$row[0].'" class="tools"><img src="'.AdminRoot.'/img/icons/recycle.png" width="16" height="16" alt="" /></a>';
			} elseif($sID == 1 && $seriesOnly == 1){
				echo '
					<a href="' . AdminRoot . '/index.php?com=eventedit&amp;sID='.$row[6].'"><img src="' . AdminRoot . '/img/icons/edit_group.png" width="16" height="16" alt="" /></a>
					<a href="' . AdminRoot . '/index.php?com=searchresults&amp;srsID='.$row[6].'"><img src="' . AdminRoot . '/img/icons/view_series.png" width="16" height="16" alt="" /></a>';
			}
			echo ($seriesOnly == 0) ? '
					<input type="checkbox" name="eventID[]" id="eventID_' . $row[0] . '" value="' . $row[0] . '" />' : '';
			echo '
				</div>
			</li>';
			++$cnt;
		}

		echo '
		</ul>
		'.$ctrls;
		if($seriesOnly == 0)
			echo ($sID == 3 && $row_cnt < 2) ? $hc_lang_search['None01'] : '<input type="submit" name="submit" id="submit" value="' . $buttonText . '" />';
 		
		echo '
		</form>';
		
		$js = '';
		
		if($sID == 1){
			$js = '
				if(confirm("'.$hc_lang_search['Valid10'] . "\\n\\n          " . $hc_lang_search['Valid11'] . "\\n          " . $hc_lang_search['Valid12'].'"))
					return true;
				else
					return false;';
		} elseif($sID == 2) {
			$js = '
				if(confirm("'.$hc_lang_search['Valid13'] . "\\n\\n          " . $hc_lang_search['Valid14'] . "\\n          " . $hc_lang_search['Valid15'].'"))
					return true;
				else
					return false;';
		} elseif($sID == 3) {
			$js = '
				if(confirm("'.$hc_lang_search['Valid16'] . "\\n\\n          " . $hc_lang_search['Valid17'] . "\\n          " . $hc_lang_search['Valid18'].'"))
					return true;
				else
					return false;';
		}
		
		echo '
		<script src="'.CalRoot.'/inc/javascript/validation.js"></script>
		<script>
		//<!--
		function validate(){
			if(validCheckArray("eventSearchResult","eventID[]",1,"error") != ""){
				alert("'.$hc_lang_search['Valid09'].'");
				return false;
			} else {
				'.$js.'
			}
		}
		//-->
		</script>';
	} else {
		echo '<p>' . $hc_lang_search['None02'] . '</p>';
	}
?>