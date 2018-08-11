<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(!defined('hcAdmin')){header("HTTP/1.1 403 No Direct Access");exit();}

	include(HCLANG.'/admin/newsletter.php');

	$mID = (isset($_GET['mID']) && is_numeric($_GET['mID'])) ? cIn(strip_tags($_GET['mID'])) : 0;
	
	$stop = false;
	$title = $subject = $message = '';
	$template = $archive = 0;
	$startDate = strftime($hc_cfg[24],strtotime(SYSDATE));
	$endDate = strftime($hc_cfg[24], strtotime(SYSDATE)+($hc_cfg[53]*86400));

	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "mailers WHERE PkID = '" . $mID . "' AND IsActive = 1");
	if(hasRows($result)){
		$title = cOut(mysql_result($result,0,1));
		$subject = cOut(mysql_result($result,0,2));
		$startDate = (mysql_result($result,0,3) != '') ? stampToDate(mysql_result($result,0,3), $hc_cfg[24]) : $startDate;
		$endDate = (mysql_result($result,0,4) != '') ? stampToDate(mysql_result($result,0,4), $hc_cfg[24]) : $endDate;
		$template = mysql_result($result,0,5);
		$message = cOut(mysql_result($result,0,6));
		$archive = mysql_result($result,0,9);
		$cDate = (mysql_result($result,0,7) != '') ? stampToDate(mysql_result($result,0,7), $hc_cfg[24]) : '';
		$mDate = (mysql_result($result,0,8) != '') ? stampToDate(mysql_result($result,0,8), $hc_cfg[24]) : '';
	}
	
	appInstructions(0, "Compose_Draft", $hc_lang_news['TitleDraft'], $hc_lang_news['InstructDraft']);
	
	echo '
	<form name="frmNewsletter" id="frmNewsletter" method="post" action="'.AdminRoot.'/components/MailCreateAction.php" onsubmit="return validate();">';
	set_form_token();
	echo '
	<input type="hidden" name="mID" id="mID" value="'.$mID.'" />
	<fieldset>
		<legend>'.$hc_lang_news['Settings'].'</legend>
		<label>'.$hc_lang_news['MailName'].'</label>
		<input name="mailTitle" id="mailTitle" type="text" size="35" maxlength="50" value="'.$title.'" required="required" />
		<span class="output">
			<a class="tooltip" data-tip="'.$hc_lang_news['Tip04'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
		<label>'.$hc_lang_news['MailSubject'].'</label>
		<input name="mailSubj" id="mailSubj" type="text" size="70" maxlength="50" value="'.$subject.'" required="required" />
		<span class="output">
			<a class="tooltip" data-tip="'.$hc_lang_news['Tip05'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
		<label>'.$hc_lang_news['Dates'].'</label>
		<input name="startDate" id="startDate" type="text" size="12" maxlength="10" value="'.$startDate.'" required="required" />
		<a href="javascript:;" onclick="calx.select(document.getElementById(\'startDate\'),\'cal1\',\''.$hc_cfg[51].'\');return false;" id="cal1" class="ds calendar" tabindex="-1"></a>
		<span class="output">&nbsp;&nbsp;'.$hc_lang_news['To'].'&nbsp;&nbsp;</span>
		<input name="endDate" id="endDate" type="text" size="12" maxlength="10" value="'.$endDate.'" required="required" />
		<a href="javascript:;" onclick="calx.select(document.getElementById(\'endDate\'),\'cal2\',\''.$hc_cfg[51].'\');return false;" id="cal2" class="ds calendar" tabindex="-1"></a>
		<label>'.$hc_lang_news['SendGroups'].'</label>';
	
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "mailgroups WHERE IsActive = 1");
	if(hasRows($result)){
		$result = doQuery("SELECT mg.*, m.PkID as Selected
						 FROM " . HC_TblPrefix . "mailgroups mg
							 LEFT JOIN " . HC_TblPrefix . "mailersgroups mgs ON (mgs.GroupID = mg.PkID AND mgs.MailerID = '" . $mID . "')
							 LEFT JOIN " . HC_TblPrefix . "mailers m ON (mgs.MailerID = m.PkID and m.IsActive = 1)
						 WHERE mg.IsActive = 1
						 Group By mg.PkID, mg.Name, mg.Description, mg.IsPublic, mg.IsActive, m.PkID");
		$cnt = 1;
		echo '<div class="catCol">';
		while($row = mysql_fetch_row($result)){
			if($cnt > ceil(mysql_num_rows($result) / 3)){
				echo ($cnt > 1) ? '</div>' : '';
				echo '
			<div class="catCol">';
				$cnt = 1;
			}
			echo '
			<label for="grpID_'.$row[0].'"><input name="grpID[]" id="grpID_'.$row[0].'" type="checkbox" value="'.$row[0].'"'.(($row[5] != '') ? ' checked="checked"':'').'>'.cOut($row[1]).'</label>';
			++$cnt;
		}
		echo '</div>';
	} else {
		$stop = true;
		echo $hc_lang_news['NoGroups'].'
		<input type="hidden" name="grpID[]" id="grpID_0" value="" />';
	}
		
	echo '
		<label>'.$hc_lang_news['Template'].'</label>';

		$result = doQuery("SELECT * FROM " . HC_TblPrefix . "templatesnews WHERE IsActive = 1 ORDER BY TemplateName");
		if(hasRows($result)){
			echo '<select name="templateID" id="templateID">';
			while($row = mysql_fetch_row($result)){
				echo '<option ';
				echo ($row[0] == $template) ? 'selected="selected" ' : '';
				echo 'value="' . cOut($row[0]) . '">' . cOut($row[1]) . '</option>';
			}
			echo '</select>';
		} else {
			$stop = true;
			echo '
			<span class="output">
				'.$hc_lang_news['NoTemplates'].' (<a href="' . AdminRoot . '/index.php?com=mailtmplt&amp;nID=0">' . $hc_lang_news['AddTemplate'] . '</a>)
			</span>';
		}

	echo '
		<label>'.$hc_lang_news['ArchStatus'].'</label>
		<select name="archStatus" id="archStatus">
			<option'.(($archive == 0) ? ' selected="selected"' : '').' value="0">'.$hc_lang_news['ArchStatus0'].'</option>
			<option'.(($archive == 1) ? ' selected="selected"' : '').' value="1">'.$hc_lang_news['ArchStatus1'].'</option>
		</select>
		<span class="output">
			<a class="tooltip" data-tip="'.$hc_lang_news['Tip06'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>';

	echo ($mID > 0 && $mDate != '') ? '<label>'.$hc_lang_news['LastModDate'].'</label><span class="output">'.$mDate.'</span>' : '';
	echo '
		<label>&nbsp;</label>
		<span class="output">
			'.$hc_lang_news['MessageNote'] . ' <b>[message]</b>
		</span>
		<label>'.$hc_lang_news['Message'].'</label>
		<textarea name="mailMsg" id="mailMsg" rows="20" class="mce_edit">'.$message.'</textarea>
	</fieldset>
	
	<input name="submit" id="submit" type="submit"'.(($stop == true) ? ' disabled="disabled"' : '').' value="'.$hc_lang_news['SaveDraft'].'" />
	'.(($mID > 0) ? '<input type="button" name="cancel" id="cancel" value=" ' . $hc_lang_news['Cancel'] . '  " onclick="window.location.href=\'' . AdminRoot . '/index.php?com=newscreate\';return false;" />' : '').'
	</form>
	<div id="dsCal" class="datePicker"></div>
	
	<script src="'.CalRoot.'/inc/javascript/validation.js"></script>
	<script src="'.CalRoot.'/inc/lang/'.$_SESSION['LangSet'].'/popCal.js"></script>
	<script src="'.CalRoot.'/inc/javascript/DateSelect.js"></script>
	<script>
	//<!--
	var calx = new CalendarPopup("dsCal");
	calx.showNavigationDropdowns();
	calx.setCssPrefix("hc_");
	calx.offsetX = 30;
	calx.offsetY = -5;
	
	function validate(){
		var err = before = "";
		
		if(document.getElementById("submit").disabled)
			return false;

		err +=reqField(document.getElementById("mailTitle"),"'.$hc_lang_news['Valid39'].'\n");
		err +=reqField(document.getElementById("mailSubj"),"'.$hc_lang_news['Valid40'].'\n");
		err += validDate(document.getElementById("startDate"),"'.$hc_cfg[51].'","'.$hc_lang_news['Valid15'].' '.strtoupper($hc_cfg[51]).'\n");
		err +=reqField(document.getElementById("startDate"),"'.$hc_lang_news['Valid16'].'\n");
		err += validDate(document.getElementById("endDate"),"'.$hc_cfg[51].'","'.$hc_lang_news['Valid17'].' '.strtoupper($hc_cfg[51]).'\n");
		err +=reqField(document.getElementById("endDate"),"'.$hc_lang_news['Valid18'].'\n");
		err += validDateBefore(document.getElementById("startDate").value,document.getElementById("endDate").value,"'.$hc_cfg[51].'","'.$hc_lang_news['Valid13'].'\n");
		err += validCheckArray("frmNewsletter","grpID[]",1,"'.$hc_lang_news['Valid41'].'\n");
		
		before += validDateBefore("'.SYSDATE.'",document.getElementById("startDate").value,"'.$hc_cfg[51].'","'.$hc_lang_news['Valid13'].'\n");
		before += validDateBefore("'.SYSDATE.'",document.getElementById("endDate").value,"'.$hc_cfg[51].'","'.$hc_lang_news['Valid13'].'\n");
		
		if(err != ""){
			alert(err);
			return false;
		} else {
			if(before != ""){
				if(confirm("'.$hc_lang_news['Valid14'].'"))
					return true;
				else
					return false;
			} else {
				return true;
			}
		}
	}
	//-->
	</script>';
	
	makeTinyMCE('82%',1,0,'mailMsg');