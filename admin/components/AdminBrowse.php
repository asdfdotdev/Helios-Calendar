<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(!defined('hcAdmin')){header("HTTP/1.1 403 No Direct Access");exit();}

	include(HCLANG.'/admin/admin.php');
	
	if(isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1, $hc_lang_admin['Feed03']);
				break;
			case "2" :
				feedback(3, $hc_lang_admin['Feed04']);
				break;
			case "3" :
				feedback(1, $hc_lang_admin['Feed05']);
				break;
			case "4" :
				feedback(1, $hc_lang_admin['Feed06']);
				break;
		}
	}
	
	appInstructions(0, "Editing_Admin_Users", $hc_lang_admin['TitleBrowseA'], $hc_lang_admin['InstructBrowseA']);
	
	$result = doQuery("SELECT PkID, FirstName, LastName, Email, LastLogin FROM " . HC_TblPrefix."admin WHERE IsActive = 1 AND SuperAdmin = 0 ORDER BY LastName, FirstName");
	if(hasRows($result)){
		echo '
		<ul class="data">
			<li class="row header uline">
				<div style="width:30%;">'.$hc_lang_admin['Name'].'</div>
				<div style="width:40%;">'.$hc_lang_admin['EmailLabel'].'</div>
				<div style="width:20%;">'.$hc_lang_admin['Login'].'</div>
				<div style="width:10%;">&nbsp;</div>
			</li>';
		
		$cnt = 0;
		while($row = mysql_fetch_row($result)){
			$hl = ($cnt % 2 == 1) ? ' hl':'';
			echo '
			<li class="row '.$hl.'">
				<div class="txt" style="width:30%;">'.cOut($row[2].', '.$row[1]).'</div>
				<div class="txt" style="width:40%;">'.cOut($row[3]).'</div>
				<div class="txt" style="width:20%;">'.(($row[4] != '') ? stampToDate($row[4],$hc_cfg[24].' '.$hc_cfg[23]) : 'N/A').'</div>
				<div class="tools" style="width:10%;">
					<a href="'.AdminRoot.'/index.php?com=adminedit&amp;aID='.$row[0].'"><img src="'.AdminRoot.'/img/icons/edit.png" width="16" height="16" alt="" /></a>
					<a href="javascript:;" onclick="doDelete(\'' . $row[0] . '\');return false;"><img src="'.AdminRoot.'/img/icons/delete.png" width="16" height="16" alt="" /></a>
				</div>
			</li>';
			++$cnt;
		}
		
		echo '
		</ul>
		<script>
		//<!--
		function doDelete(dID){
			if(confirm("'.$hc_lang_admin['Valid07'].'\\n\\n          '.$hc_lang_admin['Valid08'].'\\n          '.$hc_lang_admin['Valid09'].'")){
				document.location.href = "'.AdminRoot.'/components/AdminEditAction.php?dID=" + dID + "&tkn='.set_form_token(1).'";
			}
		}
		//-->
		</script>';
	} else {
		echo '<p>'.$hc_lang_admin['NoAdmin'].'</p>';
	}
?>