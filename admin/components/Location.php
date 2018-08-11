<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(!defined('hcAdmin')){header("HTTP/1.1 403 No Direct Access");exit();}

	include(HCLANG.'/admin/locations.php');
	
	$resDiff = 6;
	$resLimit = (isset($_GET['a']) && is_numeric($_GET['a']) && abs($_GET['a']) <= 100 && $_GET['a'] % 25 == 0) ? cIn(abs($_GET['a'])) : 25;
	$resOffset = (isset($_GET['p']) && is_numeric($_GET['p'])) ? cIn(abs($_GET['p'])) : 0;
	$term = $save = $queryS = '';
	if(isset($_GET['s']) && $_GET['s'] != ''){
		$term = cIn(cleanQuotes(strip_tags($_GET['s'])));
		$save = '&s='.$term;
		$queryS = " AND Name LIKE('%".$term."%')";
	}

	$hc_Side[] = array(CalRoot . '/index.php?com=location','map.png',$hc_lang_locations['LinkMap'],1);
	
	if(isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "3" :
				feedback(1, $hc_lang_locations['Feed03']);
				break;
			case "4" :
				feedback(2, $hc_lang_locations['Feed04']);
				break;
			case "5" :
				feedback(1, $hc_lang_locations['Feed05']);
				break;
		}
	}
	
	appInstructions(0, "Editing_Locations", $hc_lang_locations['TitleBrowse'], $hc_lang_locations['InstructBrowse']);
	$resultC = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "locations WHERE IsActive = 1 $queryS");
	$pages = ceil(mysql_result($resultC,0,0)/$resLimit);
	if($pages <= $resOffset && $pages > 0){$resOffset = ($pages - 1);}
	
	echo '
		<fieldset style="border:0px;">
			<label><b>'.$hc_lang_locations['ResPer'].'</b></label>
			<span class="output">';
			
		for($x = 25;$x <= 100;$x = $x + 25){
			echo ($x > 25) ? '&nbsp;|&nbsp;' : '';
			echo ($x != $resLimit) ?
				'<a href="'.AdminRoot.'/index.php?com=location&amp;p='.$resOffset.'&amp;a='.$x.$save.'">'.$x.'</a>':
				'<b>'.$x.'</b>';
		}
		
		echo '</span>
			<label><b>'.$hc_lang_locations['Page'].'</b></label>
			<span class="output">';

		$x = ($resOffset - $resDiff > 0) ? $resOffset - $resDiff : 0;
		$cnt = 0;
		
		echo ($resOffset > $resDiff) ? '<a href="'.AdminRoot.'/index.php?com=location&p=0&a='.$resLimit.'">1</a>&nbsp;...&nbsp;' : '';
		
		while($cnt <= ($resDiff * 2) && $x < $pages){
			echo ($cnt > 0) ? ' | ' : '';
			echo ($resOffset != $x) ?
				'<a href="'.AdminRoot.'/index.php?com=location&amp;p='.$x.'&amp;a='.$resLimit.$save.'">'.($x + 1).'</a>':
				'<b>' . ($x + 1) . '</b>';
			++$x;
			++$cnt;
		}
		
		echo ($resOffset < ($pages - ($resDiff + 1))) ? '&nbsp;...&nbsp;<a href="'.AdminRoot.'/index.php?com=location&p='.($pages - 1).'&a='.$resLimit.'">'.$pages.'</a>' : '';
		echo '</span>
			<label>&nbsp;</label>
			<span class="frm_ctrls">
				<input name="filter" id="filter" type="text" size="30" maxlength="50" value="'.$term.'" />
				<input name="filter_go" id="filter_go" type="button" value="'.$hc_lang_locations['Filter'].'" onclick="window.location.href=\''.AdminRoot.'/index.php?com=location&p=0&a='.$resLimit.'&s=\'+document.getElementById(\'filter\').value;" />
			</span>
			'.(($term != '') ? '<label>&nbsp;</label><span class="output"><a href="'.AdminRoot.'/index.php?com=location&p=0&a='.$resLimit.'">'.$hc_lang_locations['AllLink'].'</a></span>' : '').'
		</fieldset>';
	
	$result = doQuery("SELECT PkID, Name, IsPublic FROM " . HC_TblPrefix  . "locations WHERE IsActive = 1 $queryS ORDER BY IsPublic, Name LIMIT " . $resLimit . " OFFSET " . ($resOffset * $resLimit));
	if(hasRows($result)){
		echo '
		<ul class="data">
			<li class="row header uline">
				<div style="width:75%;">'.$hc_lang_locations['NameLabel'].'</div>
				<div style="width:15%;">'.$hc_lang_locations['StatusLabel'].'</div>
				<div style="width:10%;">&nbsp;</div>
			</li>';
		
		$cnt = 0;
		while($row = mysql_fetch_row($result)){
			$hl = ($cnt % 2 == 1) ? ' hl' : '';
			
			echo '
			<li class="row'.$hl.'">
				<div class="txt" style="width:75%;">'.cOut($row[1]).'</div>
				<div class="txt" style="width:15%;">'.(($row[2] == 1) ? $hc_lang_locations['Public'] : $hc_lang_locations['AdminOnly']).'</div>
				<div class="tools" style="width:10%;">
					<a href="'.AdminRoot.'/index.php?com=addlocation&amp;lID='.$row[0].'"><img src="'.AdminRoot.'/img/icons/edit.png" width="16" height="16" alt="" /></a>
					<a href="javascript:;" onclick="doDelete(\''.$row[0].'\');return false;"><img src="'.AdminRoot.'/img/icons/delete.png" width="16" height="16" alt="" /></a>
				</div>
			</li>';
			++$cnt;
		}
		echo '
		</ul>
		<script>
		//<!--
		function doDelete(dID){
			if(confirm("'.$hc_lang_locations['Valid01'].'\\n\\n          '.$hc_lang_locations['Valid02'].'\\n          '.$hc_lang_locations['Valid03'].'"))
				document.location.href = "'.AdminRoot.'/components/LocationEditAction.php?dID=" + dID + "&tkn='.set_form_token(1).'";
		}
		//-->
		</script>';
	} else {
		echo "<br />" . $hc_lang_locations['NoLoc'];
	}
?>