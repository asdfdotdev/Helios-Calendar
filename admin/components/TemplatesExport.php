<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(!defined('hcAdmin')){header("HTTP/1.1 403 No Direct Access");exit();}

	include(HCLANG.'/admin/tools.php');
	
	if (isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1, $hc_lang_tools['Feed05']);
				break;
			case "2" :
				feedback(1, $hc_lang_tools['Feed04']);
				break;
			case "3" :
				feedback(1, $hc_lang_tools['Feed06']);
				break;
		}
	}
	
	appInstructions(0, "Export_Templates", $hc_lang_tools['TitleExpTemp'], $hc_lang_tools['InstructExpTemp']);
	
	$result = doQuery("SELECT * FROM " . HC_TblPrefix  . "templates WHERE TypeID = 1 AND IsActive = 1 ORDER BY Name");
	if(hasRows($result)){
		echo '
		<a href="'.AdminRoot.'/index.php?com=templateedit" class="add"><img src="'.AdminRoot.'/img/icons/add.png" width="16" height="16" alt="" />' . $hc_lang_tools['AddT'] . '</a>
		<ul class="data">
			<li class="row header uline">
				<div style="width:40%;">'.$hc_lang_tools['NameLabel'].'</div>
				<div style="width:25%;">'.$hc_lang_tools['GroupLabel'].'</div>
				<div style="width:25%;">'.$hc_lang_tools['SortLabel'].'</div>
				<div style="width:10%;">&nbsp;</div>
			</li>';
		
		$cnt = 0;
		
		while($row = mysql_fetch_row($result)){
			$hl = ($cnt % 2 == 1) ? ' hl':'';
			
			switch($row[7]){
				case 0:
					$group = $hc_lang_tools['OptCategory'];
					break;
				case 1:
					$group = $hc_lang_tools['OptEvent'];
					break;
				case 2:
					$group = $hc_lang_tools['OptEventS'];
					break;
				case 3:
					$group = $hc_lang_tools['OptEventSC'];
					break;
			}
			
			echo '
			<li class="row'.$hl.'">
				<div style="width:40%;">'.cOut($row[1]).'</div>
				<div style="width:25%;">'.$group.'</div>
				<div style="width:25%;">'.$hc_lang_tools['OptSort'.$row[8]].'</div>
				<div class="tools" style="width:10%;">
					<a href="' . AdminRoot . '/index.php?com=templateedit&amp;tID='.$row[0].'"><img src="'.AdminRoot.'/img/icons/edit.png" width="15" height="15" alt="" /></a>
					<a href="javascript:;" onclick="doDelete(\''.$row[0].'\');return false;"><img src="'.AdminRoot.'/img/icons/delete.png" width="15" height="15" alt="" /></a>
				</div>
			</li>';
			++$cnt;
		}
		echo '
		<script>
		//<!--
		function doDelete(dID){
			if(confirm("'.$hc_lang_tools['Valid16'].'\\n\\n          '.$hc_lang_tools['Valid17'].'\\n          '.$hc_lang_tools['Valid18'].'"))
				document.location.href = "'.AdminRoot.'/components/TemplatesEditAction.php?dID=" + dID + "&tkn='.set_form_token(1).'";
		}
		//-->
		</script>';
	} else {
		echo '<p>'.$hc_lang_tools['NoTemps'].'</p>';
	}
?>