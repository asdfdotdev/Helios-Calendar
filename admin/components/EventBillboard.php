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
				feedback(1, $hc_lang_manage['Feed01']);
				break;
		}
	}
	
	appInstructions(0, "Billboard_Events", $hc_lang_manage['TitleBillboard'], $hc_lang_manage['InstructBillboard']);
	
	$result = doQuery("SELECT PkID, Title, StartDate, Views FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND IsBillboard = 1 AND StartDate >= '" . cIn(SYSDATE) . "' ORDER BY StartDate, Views DESC");
	if(hasRows($result)){
		echo '
		<ul class="data">
			<li class="row header uline">
				<div style="width:68%;">'.$hc_lang_manage['Title'].'</div>
				<div style="width:10%;">'.$hc_lang_manage['Date'].'</div>
				<div class="number" style="width:10%;">'.$hc_lang_manage['Views'].'</div>
				<div style="width:10%;">&nbsp;</div>
			</li>
		</ul>
		<ul class="data">
		<div class="blbd">';
		
		$cnt = 0;
		while($row = mysql_fetch_row($result)){
			$hl = ($cnt % 2 == 1) ? ' hl':'';
			echo '
			<li class="row '.$hl.'">
				<div class="txt" title="'.cOut($row[1]).'" style="width:70%;">'.cOut($row[1]).'</div>
				<div style="width:10%;">'.stampToDate($row[2], $hc_cfg[24]).'</div>
				<div class="number" style="width:10%;">'.number_format(cOut($row[3]),0,'',',').'</div>
				<div class="tools" style="width:10%;">
					<a href="'.AdminRoot.'/index.php?com=eventedit&amp;eID='.$row[0].'"><img src="'.AdminRoot.'/img/icons/edit.png" width="16" height="16" alt="" /></a>
					<a href="javascript:;" onclick="doDelete(\'' . $row[0] . '\');return false;"><img src="'.AdminRoot.'/img/icons/delete.png" width="16" height="16" alt="" /></a>
				</div>
			</li>';
			++$cnt;
		}
		
		echo '
		</div>
		</ul>
		<script>
		//<!--
		function doDelete(eID){
			if(confirm("'.$hc_lang_manage['Valid01'] . "\\n\\n          " . $hc_lang_manage['Valid02'] . "\\n          " . $hc_lang_manage['Valid03'].'"))
				window.location.href = "'.AdminRoot.'/components/EventBillboardAction.php?eID=" + eID + "&tkn='.set_form_token(1).'";
		}
		//-->
		</script>';
	} else {
		echo '<p>'.$hc_lang_manage['NoBillboard'].'</p>';
	}
?>