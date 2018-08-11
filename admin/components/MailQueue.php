<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(!defined('hcAdmin')){header("HTTP/1.1 403 No Direct Access");exit();}

	include(HCLANG.'/admin/newsletter.php');

	$mID = (isset($_GET['mID']) && is_numeric($_GET['mID'])) ? cIn(strip_tags($_GET['mID'])) : 0;
	$result = doQuery("SELECT PkID FROM " . HC_TblPrefix . "mailers WHERE PkID = '" . $mID . "'");
	
	if(!hasRows($result)){
		if(isset($_GET['msg'])){
			switch ($_GET['msg']){
				case "1" :
					feedback(1, $hc_lang_news['Feed13']);
					break;
				case "2" :
					feedback(1, $hc_lang_news['Feed14']);
					break;
				case "3" :
					feedback(1, $hc_lang_news['Feed15']);
					break;
			}
		}

		appInstructions(0, "Create_Newsletter", $hc_lang_news['TitleCreate'], $hc_lang_news['InstructCreateA']);
		
		$result = doQuery("SELECT m.PkID, m.Title, m.StartDate, m.EndDate, m.LastModDate, tn.TemplateName
						FROM " . HC_TblPrefix . "mailers m
							LEFT JOIN " . HC_TblPrefix . "templatesnews tn ON (m.TemplateID = tn.PkID AND tn.IsActive = 1)
						WHERE m.IsActive = 1
						ORDER BY m.LastModDate DESC, m.Title");
		if(hasRows($result)){
			echo '
		<ul class="data">
			<li class="row header uline">
				<div style="width:31%;">'.$hc_lang_news['TitleLabel'].'</div>
				<div style="width:12%;">'.$hc_lang_news['ModifyLabel'].'</div>
				<div style="width:22%;">'.$hc_lang_news['DatesLabel'].'</div>
				<div style="width:20%;">'.$hc_lang_news['TemplateLabel'].'</div>
				<div style="width:15%;">&nbsp;</div>
			</li>';

			$cnt = 0;
			while($row = mysql_fetch_row($result)){
				$hl = ($cnt % 2 == 1) ? ' hl':'';
				echo '
			<li class="row'.$hl.'">
				<div class="txt" title="'.cOut($row[1]).'" style="width:31%;">'.cOut($row[1]).'</div>
				<div style="width:12%;">'.stampToDate($row[4], $hc_cfg[24]).'</div>
				<div style="width:22%;">
					'.(($row[2] < date("Y-m-d")) ? '<span style="color:#DC143C;">' . stampToDate($row[2], $hc_cfg[24]) . '</span>' : stampToDate($row[2], $hc_cfg[24])).'
					'.(($row[3] < date("Y-m-d")) ? '<span style="color:#DC143C;"> - ' . stampToDate($row[3], $hc_cfg[24]) . '</span>' : ' - ' . stampToDate($row[3], $hc_cfg[24])).'
				</div>
				<div class="txt" title="'.cOut($row[5]).'" style="width:20%;">'.(($row[5] != '') ? cOut($row[5]) : '&nbsp;').'</div>
				<div class="tools" style="width:15%;">
					<a href="'. AdminRoot.'/index.php?com=newscreate&amp;mID='.$row[0].'"><img src="'.AdminRoot.'/img/icons/email_create.png" width="16" height="16" alt="" /></a>
					<a href="'. AdminRoot.'/index.php?com=newsdraft&amp;mID='.$row[0].'"><img src="'.AdminRoot.'/img/icons/edit.png" width="16" height="16" alt="" /></a>
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
			if(confirm("'.$hc_lang_news['Valid29'] . '\\n\\n          ' . $hc_lang_news['Valid30'] . '\\n          ' . $hc_lang_news['Valid31'].'"))
				document.location.href = "'.AdminRoot . '/components/MailCreateAction.php?dID=" + dID + "&tkn='.set_form_token(1).'";
		}
		//-->
		</script>';
		} else {
			echo '<p>' . $hc_lang_news['NoDraft'] . '</p>';
		}
	} else {
		$result = doQuery("SELECT m.PkID, m.Title, m.Subject, m.StartDate, m.EndDate, m.IsArchive, m.Message, tn.TemplateName, tn.TemplateSource
						FROM " . HC_TblPrefix . "mailers m
							LEFT JOIN " . HC_TblPrefix . "templatesnews tn ON (m.TemplateID = tn.PkID AND tn.IsActive = 1)
						WHERE m.PkID = '" . $mID . "' AND m.IsActive = 1");
		if(hasRows($result)){
			$now = date("Y-m-d");
			$mStart = ($now > mysql_result($result,0,3)) ? $now : mysql_result($result,0,3);
			$mEnd = mysql_result($result,0,4);
			$groups = '';
			$cnt = $allSub = $subCnt = 0;

			$resultG = doQuery("SELECT mg.PkID, mg.Name, m.PkID as Selected
							 FROM " . HC_TblPrefix . "mailgroups mg
								 LEFT JOIN " . HC_TblPrefix . "mailersgroups mgs ON (mgs.GroupID = mg.PkID AND mgs.MailerID = '" . $mID . "')
								 LEFT JOIN " . HC_TblPrefix . "mailers m ON (mgs.MailerID = m.PkID and m.IsActive = 1)
							 WHERE mg.IsActive = 1
							 Group By mg.PkID, mg.Name, m.PkID
							 ORDER BY mg.Name");
			if(hasRows($resultG)){
				while($row = mysql_fetch_row($resultG)){
					if($row[2] != ''){
						$allSub += ($row[0] == 1) ? 1 : 0;
						$groups .= ($cnt > 0) ? ', ' : '';
						$groups .= cOut($row[1]);
						++$cnt;
					}
				}
			}
			
			if($allSub > 0){
				$resultS = doQuery("SELECT COUNT(PkID) FROM " . HC_TblPrefix . "subscribers WHERE IsConfirm = 1");
			} else {
				$resultS = doQuery("SELECT COUNT(DISTINCT sgs.UserID)
								FROM " . HC_TblPrefix . "subscribersgroups sgs
									LEFT JOIN " . HC_TblPrefix . "mailgroups mg ON (sgs.GroupID = mg.PkID AND mg.IsActive = 1)
									LEFT JOIN " . HC_TblPrefix . "mailersgroups mgs ON (mgs.GroupID = sgs.GroupID)
									LEFT JOIN " . HC_TblPrefix . "mailers m ON (mgs.MailerID = m.PkID AND m.IsActive = 1)
									LEFT JOIN " . HC_TblPrefix . "subscribers s ON (s.PkID = sgs.UserID)
								WHERE m.PkID = '" . $mID . "' AND s.IsConfirm = 1");
			}
			$subCnt = mysql_result($resultS,0,0);
			
			$resultE = doQuery("SELECT COUNT(DISTINCT e.PkID)
							FROM " . HC_TblPrefix . "events e
								LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (e.PkID = ec.EventID)
								LEFT JOIN " . HC_TblPrefix . "categories c ON (c.PkID = ec.CategoryID)
							WHERE e.IsActive = 1 AND e.IsApproved = 1 AND (ec.EventID IS NOT NULL AND c.IsActive = 1)
							AND e.StartDate BETWEEN '" . cIn($mStart) . "' AND '" . cIn($mEnd) . "'");
			$eCnt = ($now > $mEnd) ? 0 : mysql_result($resultE,0,0);
				
			appInstructions(0, "Create_Newsletter", $hc_lang_news['TitleCreate'], $hc_lang_news['InstructCreateB']);
			
			$stop = ($subCnt == 0) ? 4 : 0;
			$stop = ($groups == '') ? 2 : $stop;
			$stop = (mysql_result($result,0,7) == '') ? 3 : $stop;
			$stopMsg = ($stop > 0) ? '<span class="alert">' . $hc_lang_news['NewsStop'] . ' ' . $hc_lang_news['NewsStop' . $stop] . '</span>' : '';
			
			echo '
			<form name="frmNewsletter" id="frmNewsletter" method="post" action="' . AdminRoot . '/components/MailQueueAction.php">';
			set_form_token();
			echo '
			<input type="hidden" name="mID" id="mID" value="' . $mID . '" />
			<input type="hidden" name="next" id="next" value="0" />
			<fieldset>
				<legend>' . $hc_lang_news['Summary'] . '</legend>
				<label>&nbsp;</label>
				<span class="output"><b>' . number_format($subCnt,0,'.',',') . '</b> ' . $hc_lang_news['SubStats'] . '</span>
				<label>&nbsp;</label>
				<span class="output"><b>' . number_format($eCnt,0,'.',',') . '</b> ' . $hc_lang_news['EventStats'] . '</span>
			</fieldset>
			<fieldset>
				<legend>'.$hc_lang_news['Settings'].'</legend>
				<label>'.$hc_lang_news['MailName'].'</label>
				<span class="output">'.mysql_result($result,0,1).'</span>
				<label>' . $hc_lang_news['MailSubject'] . '</label>
				<span class="output">'.mysql_result($result,0,2).'</span>
				<label>' . $hc_lang_news['Dates'].'</label>';
			
			if(mysql_result($result,0,3) != ''){
				echo (mysql_result($result,0,3) < date("Y-m-d")) ? '<span class="output alert">'.stampToDate(mysql_result($result,0,3), $hc_cfg[24]).'</span>' : '<span class="output">'.stampToDate(mysql_result($result,0,3), $hc_cfg[24]).'</span>';
			}
			if(mysql_result($result,0,4) != ''){
				echo (mysql_result($result,0,4) < date("Y-m-d")) ? '<span class="output alert">&nbsp;-&nbsp;'.stampToDate(mysql_result($result,0,4), $hc_cfg[24]).'</span>' : '<span class="output">&nbsp;-&nbsp;'.stampToDate(mysql_result($result,0,4), $hc_cfg[24]).'</span>';
			}
			
			echo '
				<label>'.$hc_lang_news['Groups'].'</label>
				'.(($groups == '') ? '<span class="output alert">'.$hc_lang_news['NoGroups'].'</span>' : '<span class="output">'.$groups.'</span>').'
				<label>'.$hc_lang_news['Template'].'</label>
				'.((mysql_result($result,0,7) == '') ? '<span class="output alert">'.$hc_lang_news['NoTemplate'].'</span>' : '<span class="output">'.mysql_result($result,0,7).'</span>').'
				<label>'.$hc_lang_news['ArchStatus'].'</label>
				<span class="output">'.((mysql_result($result,0,5) == 1) ? $hc_lang_news['ArchStatus1'] : $hc_lang_news['ArchStatus0']).'</span>
			</fieldset>
			<fieldset>
				<legend>'.$hc_lang_news['Message'].((strpos(mysql_result($result,0,8),'[message]') === false) ? ' '.$hc_lang_news['NoMsgTemp']:'').'</legend>
				'.((mysql_result($result,0,6) != '') ? mysql_result($result,0,6) : $hc_lang_news['NoMessage']).'
			</fieldset>
			<p>'.$stopMsg.'</p>
			<input name="save" id="save" type="button"'.(($stop > 0) ? ' disabled="disabled"' : '').' value="'.$hc_lang_news['Approve0'].'" onclick="approve(0);return false;" />
			<input name="saveand" id="saveand" type="button"'.(($stop > 0) ? ' disabled="disabled"' : '').' value="'.$hc_lang_news['Approve1'].'" onclick="approve(1);return false;" />
			<input name="saveand" id="saveand" type="button" value=" ' . $hc_lang_news['Cancel'] . ' " onclick="window.location.href=\'' . AdminRoot . '/index.php?com=newscreate\';return false;" />
			
			<script>
			//<!--
			function approve(go){
				document.getElementById("next").value = go;
				document.getElementById("frmNewsletter").submit();
			}
			//-->
			</script>';			
		} else {
			echo '<p>' . $hc_lang_news['InvalidDraft'] . '</p>';
		}
	}
?>