<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(!defined('hcAdmin')){header("HTTP/1.1 403 No Direct Access");exit();}

	include(HCLANG.'/admin/reports.php');
	
	appInstructions(0, "Reports#Duplicate_Location_Report", $hc_lang_reports['TitleDupL'], $hc_lang_reports['InstructDupL']);
	
	if(isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1,$hc_lang_reports['Feed04']);
				break;
		}
	}

	$compName = (!isset($_GET['n'])) ? 'l1.Name = l2.Name AND  ' : '';
	$token = set_form_token(1);

	$result = doQuery("SELECT l1.PkID as `First`, l1.Name, l1.Address, l1.Address2, l1.City, l1.State, l1.Zip, l1.Country,
							(SELECT COUNT(*) FROM " . HC_TblPrefix . "events e WHERE e.IsActive = 1 AND e.LocID = l1.PkID) as Events,
						l2.PkID as `Duplicate`, l2.Name, l2.Address, l2.Address2, l2.City, l2.State, l2.Zip, l2.Country,
							(SELECT COUNT(*) FROM " . HC_TblPrefix . "events e WHERE e.IsActive = 1 AND e.LocID = l2.PkID) as DupEvents
					FROM " . HC_TblPrefix . "locations l1
					LEFT JOIN " . HC_TblPrefix . "locations l2 ON (".$compName." l1.Address = l2.Address AND l1.City = l2.City AND l1.State = l2.State AND l1.Zip = l2.Zip AND l1.Country = l2.Country)
					WHERE l1.PkID != l2.PkID AND l1.IsActive = 1 AND l2.IsActive = 1
					ORDER BY l1.PkID");
	
	echo '
		<fieldset style="border:0;">
		<span class="frm_ctrls">
			<label><input type="checkbox" onclick="window.location.href=\''.AdminRoot.'/index.php?com=reportdupl'.((!isset($_GET['n'])) ? '&n=1\'" checked="checked" /> ' : '\'" /> ').$hc_lang_reports['IncludeName'].'</label>
		</span>
		</fieldset>';
	
	if(hasRows($result)){
		echo '
		<ul class="data">
		<div class="drpt">';
		
		$cnt = $curID = 0;
		$foundDup = array();
		while($row = mysql_fetch_row($result)){
			$address = str_replace('<br />',' ',buildAddress($row[2],$row[3],$row[4],$row[5],$row[6],$row[7]));
				   
			if($curID != $row[0] && !in_array($row[0],$foundDup)){
				$cnt = 0;
				$curID = $row[0];
				echo '
			<li class="row uline header">
				<div class="txt" title="'.cOut($row[1]).'" style="width:29%;">
					'.cOut('('.$row[0].') '.$row[1]).'
				</div>
				<div class="txt" title="'.$address.'" style="width:45%;">'.$address.'</div>
				<div class="txt" style="width:11%;">'.$row[8].' '.$hc_lang_reports['Events'].'</div>
				<div class="tools" style="width:15%;">
					<a href="'.AdminRoot.'/index.php?com=addlocation&amp;lID='.$row[0].'"><img src="'.AdminRoot.'/img/icons/edit.png" width="16" height="16" alt="" /></a>
					<a href="javascript:;" onclick="doDelete('.$row[0].')"><img src="'.AdminRoot.'/img/icons/delete.png" width="16" height="16" alt="" border="0" /></a>
					<a href="'.AdminRoot.'/index.php?com=locsearch&tkn='.$token.'&l='.((!isset($_GET['n'])) ? cOut($row[1]) : '\''.cOut($row[2]).'\'').'"><img src="'.AdminRoot.'/img/icons/merge.png" width="16" height="16" alt="" /></a>
				</div>
			</li>';
			}
			
			$hl = ($cnt % 2 == 1) ? ' hl':'';
			if(!in_array($row[0],$foundDup)){
				$foundDup[] = $row[9];
				
				echo '
			<li class="row'.$hl.'">
				<div class="txt" title="'.cOut($row[10]).'" style="width:29%;">
					'.cOut('('.$row[9].') '.$row[10]).'
				</div>
				<div class="txt" title="'.$address.'" style="width:45%;">'.$address.'</div>
				<div class="txt" style="width:11%;">'.$row[17].' '.$hc_lang_reports['Events'].'</div>
				<div class="tools" style="width:15%;">
					<a href="'.AdminRoot.'/index.php?com=addlocation&amp;lID='.$row[9].'"><img src="'.AdminRoot.'/img/icons/edit.png" width="16" height="16" alt="" /></a>
					<a href="javascript:;" onclick="doDelete('.$row[9].')"><img src="'.AdminRoot.'/img/icons/delete.png" width="16" height="16" alt="" border="0" /></a>
				</div>
			</li>';
			}
			++$cnt;
		}
		echo '
		</div>
		</ul>

		<script language="JavaScript" type="text/JavaScript">
		//<!--
		function doDelete(dID){
			if(confirm("'.$hc_lang_reports['Valid13'].'\\n\\n          '.$hc_lang_reports['Valid14'].'\\n          '.$hc_lang_reports['Valid15'].'"))
				document.location.href = "'.AdminRoot.'/components/LocationEditAction.php?dpID=1&dID=" + dID + "&tkn='.$token.'";
		}
		//-->
		</script>';
	} else {
		echo '<p>' . $hc_lang_reports['NoLocation'] . '</p>';
	}
?>