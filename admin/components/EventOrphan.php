<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(!defined('hcAdmin')){header("HTTP/1.1 403 No Direct Access");exit();}

	include(HCLANG.'/admin/manage.php');
	
	if(isset($_GET['msg'])){
		switch($_GET['msg']){
			case "1" :
				feedback(1, $hc_lang_manage['Feed02']);
				break;
		}
	}
	
	$c = (isset($_GET['c']) && cIn(strip_tags($_GET['c'])) == 0) ? 0 : 1;
	$l = (isset($_GET['l']) && cIn(strip_tags($_GET['l'])) == 0) ? 0 : 1;
	
	if($c == 0 && $l == 0)
		$c = $l = 1;
	
	$result = doQuery("SELECT e.PkID, e.Title, e.StartDate, 1 as NoType
					FROM " . HC_TblPrefix . "events e
						LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (e.PkID = ec.EventID)
						LEFT JOIN " . HC_TblPrefix . "categories c ON (c.PkID = ec.CategoryID)
					WHERE e.IsActive = 1 AND e.IsApproved = 1 AND (ec.EventID IS NULL OR c.IsActive = 0)
					GROUP BY e.PkID, e.Title, e.StartDate
					UNION
					SELECT e.PkID, e.Title, e.StartDate, 0 as NoType
					FROM " . HC_TblPrefix . "events e
						LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (e.PkID = ec.EventID)
						LEFT JOIN " . HC_TblPrefix . "categories c ON (c.PkID = ec.CategoryID)
					WHERE e.IsActive = 1 AND e.IsApproved = 1 AND e.LocID = 0 AND (e.LocationName = '' OR e.LocationName IS NULL OR e.LocationName = 'Unknown')
					AND (ec.EventID IS NOT NULL AND c.IsActive = 1)
					GROUP BY e.PkID, e.Title, e.StartDate
					ORDER BY StartDate");
	if(hasRows($result)){	
		appInstructions(0, "Orphan_Events", $hc_lang_manage['TitleOrphan'], $hc_lang_manage['InstructOrphan']);
		
		echo '
		<form name="eventOrphan" id="eventOrphan" method="post" action="'.AdminRoot.'/components/EventDelete.php" onsubmit="return validate();">';
		set_form_token();
		echo '
		<input type="hidden" name="oID" id="oID" value="1" />
		<div class="catCtrl">
			[ <a href="javascript:;" onclick="checkAllArray(\'eventOrphan\',\'eventID[]\');">'.$hc_lang_core['SelectAll'].'</a>
			&nbsp;|&nbsp; <a href="javascript:;" onclick="uncheckAllArray(\'eventOrphan\',\'eventID[]\');">'.$hc_lang_core['DeselectAll'].'</a> ]
		</div>
		<ul class="data">
			<li class="row header uline">
				<div style="width:60%;">'.$hc_lang_manage['Title'].'</div>
				<div style="width:15%;">'.$hc_lang_manage['Date'].'</div>
				<div style="width:10%;">'.$hc_lang_manage['Missing'].'</div>
				<div style="width:15%;">&nbsp;</div>
			</li>';
		
		$cnt = 0;
		while($row = mysql_fetch_row($result)){
			$hl = ($cnt % 2 == 1) ? ' hl':'';
			echo '
			<li class="row '.$hl.'">
				<div class="txt" style="width:60%;">'.cOut($row[1]).'</div>
				<div class="txt" style="width:15%;">'.stampToDate($row[2], $hc_cfg[24]).'</div>
				<div class="txt" style="width:10%;">'.$hc_lang_manage['Missing'.$row[3]].'</div>
				<div class="tools" style="width:15%;">
					<a href="'.AdminRoot.'/index.php?com=eventedit&amp;eID='.$row[0].'&amp;oID=1"><img src="'.AdminRoot.'/img/icons/edit.png" width="16" height="16" alt="" /></a>
					<a href="javascript:;" onclick="doDelete(\''.$row[0].'\');return false;"><img src="'.AdminRoot.'/img/icons/delete.png" width="16" height="16" alt="" /></a>
					<input type="checkbox" name="eventID[]" id="eventID_'.$row[0].'" value="'.$row[0].'" />
				</div>
			</li>';
			++$cnt;
		}
		
		echo '
		</ul>
		<div class="catCtrl">
			[ <a href="javascript:;" onclick="checkAllArray(\'eventOrphan\',\'eventID[]\');">'.$hc_lang_core['SelectAll'].'</a>
			&nbsp;|&nbsp; <a href="javascript:;" onclick="uncheckAllArray(\'eventOrphan\',\'eventID[]\');">'.$hc_lang_core['DeselectAll'].'</a> ]
		</div>
		<input type="submit" name="submit" id="submit" value="'.$hc_lang_manage['Delete'].'" />
		</form>
		
		<script src="'.CalRoot.'/inc/javascript/validation.js"></script>
		<script>
		//<!--
		function doDelete(eID){
			if(confirm("'.$hc_lang_manage['Valid04'] . "\\n\\n          " . $hc_lang_manage['Valid05'] . "\\n          " . $hc_lang_manage['Valid06'].'"))
				window.location.href = "'.AdminRoot.'/components/EventDelete.php?dID=" + eID + "&oID=1";
		}
		function validate(){
			if(validCheckArray("eventOrphan","eventID[]",1,"error") != ""){
				alert("'.$hc_lang_manage['Valid07'].'");
				return false;
			} else {
				if(confirm("'.$hc_lang_manage['Valid04']."\\n\\n          ".$hc_lang_manage['Valid05']."\\n          ".$hc_lang_manage['Valid06'].'"))
					return true;
				else
					return false;
			}
			return true;
		}
		//-->
		</script>';
	} else {
		echo '<p>'.$hc_lang_manage['NoOrphan'].'</p>';
	}
?>