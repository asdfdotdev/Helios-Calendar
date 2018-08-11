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
				feedback(1,$hc_lang_reports['Feed02']);
				break;
		}
	}
	
	appInstructions(0, "Reports", $hc_lang_reports['TitleFail'], $hc_lang_reports['InstructFail']);
	
	$result = doQuery("SELECT a.PkID, a.FirstName, a.LastName, a.Email, alh.IP, alh.`Client`, alh.LoginTime, alh.PkID
					FROM " . HC_TblPrefix . "adminloginhistory alh
						LEFT JOIN " . HC_TblPrefix . "admin a ON (a.PkID = alh.AdminID)
					WHERE alh.IsFail = 1 AND a.SuperAdmin = 0
					ORDER BY LoginTime DESC LIMIT 200");
	if(hasRows($result)){
		echo '
		<ul class="data">
			<li class="row header uline">
				<div class="txt" style="width:25%;">&nbsp;</div>
				<div style="width:15%;">'.$hc_lang_reports['IP'].'</div>
				<div style="width:20%;">'.$hc_lang_reports['Date'].'</div>
				<div class="txt" style="width:30%">'.$hc_lang_reports['User'].'</div>
				<div class="tools" style="width:10%;">&nbsp;</div>
			</li>
		</ul>
		<ul class="data">
		<div class="prpt">';
		
		$cnt = 0;
		while($row = mysql_fetch_row($result)){
			$hl = ($cnt % 2 == 1) ? ' hl':'';
		
			echo '
			<li class="row'.$hl.'">
				<div class="txt" title="'.cOut($row[1]).'" style="width:25%;">'.cOut('('.$row[0].') '.trim($row[1].' '.$row[2])).'</div>
				<div style="width:15%;">'.cOut($row[4]).'</div>
				<div style="width:20%;">'.stampToDate($row[6], $hc_cfg[24].' '.$hc_cfg[23]).'</div>
				<div class="txt" title="'.cOut($row[5]).'" style="width:30%;">'.cOut($row[5]).'</div>
				<div class="tools" style="width:10%;">
					<a href="'.AdminRoot.'/index.php?com=adminedit&amp;aID='.$row[0].'"><img src="'.AdminRoot.'/img/icons/user_edit.png" width="16" height="16" alt="" /></a>
					<a href="javascript:;" onclick="doDelete(\'' . $row[7] . '\');return false;"><img src="'.AdminRoot.'/img/icons/delete.png" width="16" height="16" alt="" /></a>
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
			if(confirm("'.$hc_lang_reports['Valid04'].'\\n\\n          '.$hc_lang_reports['Valid05'].'\\n          '.$hc_lang_reports['Valid06'].'")){
				document.location.href = "'.AdminRoot.'/components/ReportFailAction.php?dID=" + dID + "&tkn='.set_form_token(1).'";
			}
		}
		//-->
		</script>';
	} else {
		echo '
		<p>'.$hc_lang_reports['NoFails'].'</p>';
	}
?>