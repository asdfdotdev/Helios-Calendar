<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(!defined('hcAdmin')){header("HTTP/1.1 403 No Direct Access");exit();}

	include(HCLANG.'/admin/reports.php');
	
	appInstructions(0, "Reports#Duplicate_Event_Report", $hc_lang_reports['TitleDup'], $hc_lang_reports['InstructDup']);
	
	if(isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1,$hc_lang_reports['Feed01']);
				break;
		}
	}

	$compTitle = (!isset($_GET['t'])) ? 'e1.Title = e2.Title AND ' : '';
	$token = set_form_token(1);

	$result = doQuery("SELECT e1.PkID as `First`, e1.Title as Title1, e1.StartDate as Date1, e1.LocID as LocID1, e1.LocationName, l.Name,
						e2.PkID as `Duplicate`, e2.Title as Title2, e2.StartDate as Date2, e2.LocID as LocID2, e2.LocationName, l.Name
					FROM " . HC_TblPrefix . "events e1
					    LEFT JOIN " . HC_TblPrefix . "events e2 ON (" . $compTitle . "e1.StartDate = e2.StartDate AND e1.StartTime = e2.StartTime AND e1.LocID = e2.LocID)
					    LEFT JOIN " . HC_TblPrefix . "locations l ON (l.PkID = e1.LocID OR l.PkID = e2.LocID)
					WHERE e1.PkID != e2.PkID AND e1.IsActive = 1 AND e2.IsActive = 1 AND e1.IsApproved = 1 AND e2.IsApproved = 1
					
					ORDER BY e1.PkID");
	
	echo '
		<form name="eventDuplicateRpt" id="eventDuplicateRpt" method="post" action="'.AdminRoot.'/components/EventDelete.php" onsubmit="return validate();">
		<input type="hidden" name="token" id="token" value="'.$token.'" />
		<fieldset style="border:0;">
		<span class="frm_ctrls">
			<label><input type="checkbox" onclick="window.location.href=\''.AdminRoot.'/index.php?com=reportdup'.((!isset($_GET['t'])) ? '&t=1\'" checked="checked" /> ' : '\'" /> ').$hc_lang_reports['IncludeTitle'].'</label>
		</span>
		</fieldset>';
	
	if(hasRows($result)){
		echo '
		<input type="hidden" name="dpID" id="dpID" value="1" />
		<ul class="data">
		<div class="drpt">';
		
		$cnt = $curID = 0;
		$foundDup = array();
		while($row = mysql_fetch_row($result)){
			if($curID != $row[0] && !in_array($row[0],$foundDup)){
				$cnt = 0;
				$curID = $row[0];
				echo '
			<li class="row uline header">
				<div class="txt" title="'.cOut($row[1]).'" style="width:73%;">'.cOut('('.$row[0].') '.$row[1]).'</div>
				<div style="width:12%;">'.stampToDate($row[2], $hc_cfg[24]).'</div>
				<div class="tools" style="width:15%;">
					<a href="'.AdminRoot.'/index.php?com=eventedit&amp;eID='.$row[0].'"><img src="'.AdminRoot.'/img/icons/edit.png" width="16" height="16" alt="" /></a>
					<a href="javascript:;" onclick="doDelete('.$row[0].')"><img src="'.AdminRoot.'/img/icons/delete.png" width="16" height="16" alt="" border="0" /></a>
				</div>
			</li>';
			}
			
			$hl = ($cnt % 2 == 1) ? ' hl':'';
			if(!in_array($row[0],$foundDup)){
				$foundDup[] = $row[6];
				
				echo '
			<li class="row'.$hl.'">
				<div class="txt" title="'.cOut($row[1]).'" style="width:73%;">'.cOut('('.$row[6].') '.$row[7]).'</div>
				<div style="width:12%;">'.stampToDate($row[8], $hc_cfg[24]).'</div>
				<div class="tools" style="width:15%;">
					<a href="'.AdminRoot.'/index.php?com=eventedit&amp;eID='.$row[6].'"><img src="'.AdminRoot.'/img/icons/edit.png" width="16" height="16" alt="" /></a>
					<a href="javascript:;" onclick="doDelete('.$row[6].')"><img src="'.AdminRoot.'/img/icons/delete.png" width="16" height="16" alt="" /></a>
					<input type="checkbox" name="eventID[]" id="eventID_'.$row[6].'" value="'.$row[6].'" />
				</div>
			</li>';
			}
			++$cnt;
		}
		echo '
		</div>
		</ul>
		<div class="catCtrl">
			[ <a href="javascript:;" onclick="checkAllArray(\'eventDuplicateRpt\',\'eventID[]\');">'.$hc_lang_core['SelectAll'].'</a>
			&nbsp;|&nbsp; <a href="javascript:;" onclick="uncheckAllArray(\'eventDuplicateRpt\',\'eventID[]\');">'.$hc_lang_core['DeselectAll'].'</a> ]
		</div>
		<input type="submit" name="submit" id="submit" value="'.$hc_lang_reports['Delete'].'" />
		</form>

		<script language="JavaScript" type="text/JavaScript">
		//<!--
		function doDelete(dID){
			if(confirm("'.$hc_lang_reports['Valid01'].'\n\n          '.$hc_lang_reports['Valid02'].'\n          '.$hc_lang_reports['Valid03'].'"))
				window.location.href="'.AdminRoot.'/components/EventDelete.php?dID=" + dID + "&dpID=1&tkn='.$token.'";
		}
		function validate(){
			if(validCheckArray("eventDuplicateRpt","eventID[]",1,"error") != ""){
				alert("'.$hc_lang_reports['Valid09'].'");
				return false;
			} else {
				if(confirm("'.$hc_lang_reports['Valid01'].'\n\n          '.$hc_lang_reports['Valid02'].'\n          '.$hc_lang_reports['Valid03'].'"))
					return true;
				else
					return false;
			}
		}
		//-->
		</script>';
	} else {
		echo '<p>' . $hc_lang_reports['NoEvent'] . '</p>';
	}
?>