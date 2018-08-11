<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(!defined('hcAdmin')){header("HTTP/1.1 403 No Direct Access");exit();}

	include(HCLANG.'/admin/newsletter.php');

	if(isset($_GET['gID']) && is_numeric($_GET['gID'])){
		$gID = (isset($_GET['gID']) && is_numeric($_GET['gID'])) ? cIn(strip_tags($_GET['gID'])) : 0;
		$isPublic = 0;
		$name = $descript = '';
		$helpText = $hc_lang_news['InstructAddG'];

		$result = doQuery("SELECT * FROM " . HC_TblPrefix . "mailgroups WHERE PkID = '" . $gID . "' AND IsActive = 1");
		if(hasRows($result)){
			$name = cOut(mysql_result($result,0,1));
			$descript = cOut(mysql_result($result,0,2));
			$isPublic = cOut(mysql_result($result,0,3));
			$helpText = $hc_lang_news['InstructEditG'];
		}
		
		appInstructions(0, 'Subscriber_Groups', $hc_lang_news['TitleGroup'], $helpText);
		
		echo '
	<form name="frm" id="frm" method="post" action="'.AdminRoot.'/components/MailGroupsAction.php" onsubmit="return validate();">';
	set_form_token();
	echo '
	<input type="hidden" name="gID" id="nID" value="'.$gID.'" />
	'.(($gID == 1) ? '
	<input type="hidden" name="description" id="description" value="" />
	<input type="hidden" name="status" id="status" value="0" />':'').'
	<fieldset>
		<legend>'.$hc_lang_news['SubGroup'].'</legend>
		<label for="name">'.$hc_lang_news['GroupName'].'</label>
		<input name="name" id="name" type="text" size="40" maxlength="100" value="'.$name.'" />
		<label for="description">'.$hc_lang_news['GroupDesc'].'</label>
		<input'.(($gID == 1) ? ' disabled="disabled"' : '').' name="description" id="description" type="text" size="75" maxlength="250" value="'.$descript.'" />
		<span class="output">
			<a class="tooltip" data-tip="'.$hc_lang_news['Tip01'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
		<label for="status">'.$hc_lang_news['GroupStatus'].'</label>
		<select'.(($gID == 1) ? ' disabled="disabled"' : '').' name="status" id="status">
			<option'.(($isPublic == 0) ? ' selected="selected"' : '').' value="0">'.$hc_lang_news['AdminOnly'].'</option>
			<option'.(($isPublic == 1) ? ' selected="selected"' : '').' value="1">'.$hc_lang_news['Public'].'</option>
		</select>
		<span class="output">
			<a class="tooltip" data-tip="'.$hc_lang_news['Tip02'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
	</fieldset>
	<input type="submit" name="submit" value=" '.$hc_lang_news['SaveGroup'].' " />
	</form>

	<script>
	//<!--
	function validate(){
		var err = "";

		if(reqField(document.getElementById("name"),"error") != ""){
			alert('.$hc_lang_news['Valid27'].');
			return false;
		} else {
			return true;
		}
	}
	//-->
	</script>';
	} else {
		if(isset($_GET['msg'])){
			switch ($_GET['msg']){
				case "1" :
					feedback(1, $hc_lang_news['Feed10']);
					break;
				case "2" :
					feedback(1, $hc_lang_news['Feed11']);
					break;
				case "3" :
					feedback(1, $hc_lang_news['Feed12']);
					break;
			}
		}

		appInstructions(0, "Subscriber_Groups", $hc_lang_news['TitleGroup'], $hc_lang_news['InstructGroup']);
		
		echo '
	<a href="' . AdminRoot . '/index.php?com=subgrps&gID=0" class="add"><img src="' . AdminRoot . '/img/icons/add.png" width="16" height="16" alt="" />'.$hc_lang_news['NewGroup'].'</a>';

		$result = doQuery("SELECT PkID, Name, IsPublic,
							(SELECT COUNT(sg.UserID)
							FROM " . HC_TblPrefix . "subscribersgroups sg
								LEFT JOIN " . HC_TblPrefix . "subscribers s ON (sg.UserID = s.PkID)
							WHERE sg.GroupID = mg.PkID AND s.IsConfirm = 1) as GrpCnt,
							(SELECT COUNT(s.PkID)FROM " . HC_TblPrefix . "subscribers s WHERE s.IsConfirm = 1) as AllCnt
						FROM " . HC_TblPrefix . "mailgroups mg
						WHERE IsActive = 1 ORDER BY IsPublic, Name");

		if(hasRows($result)){
			echo '
	<ul class="data">
		<li class="row header uline">
			<div style="width:50%;">'.$hc_lang_news['GroupNameLabel'].'</div>
			<div style="width:20%;">'.$hc_lang_news['GroupStatusLabel'].'</div>
			<div class="number" style="width:20%;">'.$hc_lang_news['GroupCountLabel'].'</div>
			<div class="tools" style="width:10%;">&nbsp;</div>
		</li>';
			$cnt = 0;
			while($row = mysql_fetch_row($result)){
				$hl = ($cnt % 2 == 1) ? ' hl':'';
				
				echo '
		<li class="row'.$hl.'">
			<div style="width:50%;">'.cOut($row[1]).'</div>
			<div style="width:20%;">'.(($row[2] == 1) ? $hc_lang_news['Public'] : $hc_lang_news['AdminOnly']).'</div>
			<div class="number" style="width:20%;">'.(($row[0] == 1) ? number_format($row[4],0,'.',',') : number_format($row[3],0,'.',',')).'</div>
			<div class="tools" style="width:10%;">
				<a href="'.AdminRoot.'/index.php?com=subgrps&amp;gID='.$row[0].'"><img src="'.AdminRoot.'/img/icons/edit.png" width="16" height="16" alt="" /></a>
				'.(($row[0] > 1) ? '<a href="javascript:;" onclick="javascript:doDelete(\''.$row[0].'\');return false;"><img src="'.AdminRoot.'/img/icons/delete.png" width="16" height="16" alt="" /></a>' : '<img src="'.AdminRoot.'/img/spacer.gif" width="16" height="16" alt="" />').'
			</div>
		</li>';
				++$cnt;
			}
		echo '
	</ul>
	
	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function doDelete(dID){
		if(confirm("'.$hc_lang_news['Valid42'].'\\n\\n          '.$hc_lang_news['Valid43'].'\\n          '.$hc_lang_news['Valid44'].'"))
			document.location.href = "'.AdminRoot.'/components/MailGroupsAction.php?dID=" + dID + "&tkn='.set_form_token(1).'";
	}
	//-->
	</script>';
			
		} else {
			echo '<p>' . $hc_lang_news['NoGroup'] . '</p>';
		}
	}
?>