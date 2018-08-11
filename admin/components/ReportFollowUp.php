<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(!defined('hcAdmin')){header("HTTP/1.1 403 No Direct Access");exit();}

	include(HCLANG.'/admin/reports.php');
	
	if(isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1,$hc_lang_reports['Feed03']);
				break;
		}
	}
	
	appInstructions(0, "Reports", $hc_lang_reports['TitleFollow'], $hc_lang_reports['InstructFollow']);
	
	$result = doQuery("SELECT e.PkID, e.Title, f.Note, f.EntityType, f.PkID
					FROM " . HC_TblPrefix . "followup f
						LEFT JOIN " . HC_TblPrefix . "events e ON (f.EntityID = e.PkID AND f.EntityType = 1 AND e.IsActive = 1)
					WHERE f.EntityType = 1 AND e.IsActive = 1
					UNION
					SELECT e.SeriesID, e.Title, f.Note, f.EntityType, f.PkID
					FROM " . HC_TblPrefix . "followup f
						LEFT JOIN " . HC_TblPrefix . "events e ON (f.EntityID = e.SeriesID AND f.EntityType = 2 AND e.IsActive = 1)
					WHERE f.EntityType = 2 AND e.IsActive = 1
					UNION
					SELECT l.PkID, l.Name, f.Note, f.EntityType, f.PkID
					FROM " . HC_TblPrefix . "followup f
						LEFT JOIN " . HC_TblPrefix . "locations l ON (f.EntityID = l.PkID AND f.EntityType = 3 AND l.IsActive = 1)
					WHERE f.EntityType = 3 AND l.IsActive = 1
					ORDER BY EntityType DESC, Title");
		
	if(hasRows($result)){
		echo '
		<ul class="data">
			<li class="row header uline">
				<div style="width:30%;">&nbsp;</div>
				<div style="width:40%;">'.$hc_lang_reports['Note'].'</div>
				<div style="width:15%;">'.$hc_lang_reports['Type'].'</div>
				<div class="tools" style="width:15%;">&nbsp;</div>
			</li>
		</ul>
		<ul class="data">
		<div class="rrpt">';
		
		$cnt = 0;
		while($row = mysql_fetch_row($result)){
			$hl = ($cnt % 2 == 1) ? ' hl':'';
		
			switch($row[3]){
				case 1:
					$type = $hc_lang_reports['FollowType1'];
					$link = AdminRoot.'/index.php?com=eventedit&eID=';
					break;
				case 2:
					$type = $hc_lang_reports['FollowType2'];
					$link = AdminRoot.'/index.php?com=eventedit&sID=';
					break;
				case 3:
					$type = $hc_lang_reports['FollowType3'];
					$link = AdminRoot.'/index.php?com=addlocation&lID=';
					break;
			}
			
			echo '
			<li class="row'.$hl.'">
				<div class="txt" title="'.cOut($row[1]).'" style="width:30%;">'.cOut($row[1]).'</div>
				<div class="txt" title="'.cOut($row[2]).'" style="width:40%;font-style:italic;">'.cOut($row[2]).'&nbsp;</div>
				<div style="width:15%;">&nbsp;'.$type.'</div>
				<div class="tools" style="width:12%;">
					<a href="'.$link.$row[0].'"><img src="'.AdminRoot.'/img/icons/edit.png" width="16" height="16" alt="" /></a>
					<a href="javascript:;" onclick="doDelete(\''.$row[4].'\');return false;"><img src="'.AdminRoot.'/img/icons/delete.png" width="16" height="16" alt="" /></a>
					
					
				</div>
			</li>';
			++$cnt;
		}
		echo '
		</div>
		</ul>
		<script>
		//<!--
		function doDelete(dID){
			if(confirm("'.$hc_lang_reports['Valid10'].'\\n\\n          '.$hc_lang_reports['Valid11'].'\\n          '.$hc_lang_reports['Valid12'].'")){
				document.location.href = "'.AdminRoot.'/components/ReportDelete.php?dID=" + dID + "&tkn='.set_form_token(1).'";
			}
		}
		//-->
		</script>';
	} else {
		echo '
		<p>'.$hc_lang_reports['NoFollow'].'</p>';
	}
?>