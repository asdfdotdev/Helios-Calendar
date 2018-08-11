<?php
/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development, LLC.
 * @link http://www.refreshmy.com
 * @copyright (C) 2004-2011 Refresh Web Development
 * @license http://www.helioscalendar.com/license.html
 * @package Helios Calendar
 */
	if(!isset($hc_cfg00)){header("HTTP/1.1 403 No Direct Access");exit();}
	
	include($hc_langPath . $_SESSION['LangSet'] . '/admin/newsletter.php');

	$mID = (isset($_GET['mID']) && is_numeric($_GET['mID'])) ? cIn($_GET['mID']) : 0;
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
			}//end switch
		}//end if

		appInstructions(0, "Create_Newsletter", $hc_lang_news['TitleCreate'], $hc_lang_news['InstructCreateA']);
		
		$result = doQuery("SELECT m.PkID, m.Title, m.StartDate, m.EndDate, m.LastModDate, tn.TemplateName
						FROM " . HC_TblPrefix . "mailers m
							LEFT JOIN " . HC_TblPrefix . "templatesnews tn ON (m.TemplateID = tn.PkID AND tn.IsActive = 1)
						WHERE m.IsActive = 1
						ORDER BY m.LastModDate DESC, m.Title");
		if(hasRows($result)){?>
			<script language="JavaScript" type="text/JavaScript">
			//<!--
			function doDelete(dID){
				if(confirm('<?php echo $hc_lang_news['Valid29'] . "\\n\\n          " . $hc_lang_news['Valid30'] . "\\n          " . $hc_lang_news['Valid31'];?>')){
					document.location.href = '<?php echo CalAdminRoot . "/components/MailCreateAction.php";?>?dID=' + dID;
				}//end if
			}//end doDelete
			//-->
			</script>
	<?php
			echo '<div class="draftList">';
			echo '<div class="draftTitle"><b>' . $hc_lang_news['TitleLabel'] . '</b></div>';
			echo '<div class="draftModified"><b>' . $hc_lang_news['ModifyLabel'] . '</b></div>';
			echo '<div class="draftDates"><b>' . $hc_lang_news['DatesLabel'] . '</b></div>';
			echo '<div class="draftTemplate"><b>' . $hc_lang_news['TemplateLabel'] . '</b></div>';
			echo '<div class="draftTools">&nbsp;</div>&nbsp;';
			echo '</div>';

			$cnt = 0;
			while($row = mysql_fetch_row($result)){
				echo ($cnt % 2 == 0) ? '<div class="draftTitle">' : '<div class="draftTitleHL">';
				echo $row[1] . '</div>';

				echo ($cnt % 2 == 0) ? '<div class="draftModified">' : '<div class="draftModifiedHL">';
				echo ($row[4] != '') ? stampToDate($row[4], $hc_cfg24) . '</div>' : '&nbsp;</div>';

				echo ($cnt % 2 == 0) ? '<div class="draftDates">' : '<div class="draftDatesHL">';
				if($row[2] != ''){
					echo ($row[2] < date("Y-m-d")) ? '<span style="color:#DC143C;">' . stampToDate($row[2], $hc_cfg24) . '</span>' : stampToDate($row[2], $hc_cfg24);
				}//end if
				if($row[3] != ''){
					echo ($row[3] < date("Y-m-d")) ? '<span style="color:#DC143C;"> - ' . stampToDate($row[3], $hc_cfg24) . '</span>' : ' - ' . stampToDate($row[3], $hc_cfg24);
				}//end if
				echo '&nbsp;</div>';

				echo ($cnt % 2 == 0) ? '<div class="draftTemplate">' : '<div class="draftTemplateHL">';
				echo $row[5] . '&nbsp;</div>';

				echo ($cnt % 2 == 0) ? '<div class="draftTools">' : '<div class="draftToolsHL">';
				echo '<a href="' . CalAdminRoot . '/index.php?com=newscreate&amp;mID=' . $row[0] . '" class="main"><img src="' . CalAdminRoot . '/images/icons/iconEmailCreate.png" width="16" height="16" alt="" border="0" style="vertical-align:middle;" /></a>&nbsp;&nbsp;';
				echo '<a href="' . CalAdminRoot . '/index.php?com=newsdraft&amp;mID=' . $row[0] . '" class="main"><img src="' . CalAdminRoot . '/images/icons/iconEdit.png" width="16" height="16" alt="" border="0" style="vertical-align:middle;" /></a>&nbsp;&nbsp;';
				echo '<a href="javascript:;" onclick="doDelete(\'' . $row[0] . '\');return false;" class="main"><img src="' . CalAdminRoot . '/images/icons/iconDelete.png" width="15" height="15" alt="" border="0" style="vertical-align:middle;" /></a></div>';
				++$cnt;
			}//end while
		} else {
			echo '<p>' . $hc_lang_news['NoDraft'] . '</p>';
		}//end if
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
							 Group By mg.PkID
							 ORDER BY mg.Name");
			if(hasRows($resultG)){
				while($row = mysql_fetch_row($resultG)){
					if($row[2] != ''){
						$allSub += ($row[0] == 1) ? 1 : 0;
						$groups .= ($cnt > 0) ? ', ' : '';
						$groups .= cOut($row[1]);
						++$cnt;
					}//end if
				}//end while
			}//end if

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
			}//end if
			$subCnt = mysql_result($resultS,0,0);
			
			$resultE = doQuery("SELECT COUNT(DISTINCT e.PkID)
							FROM " . HC_TblPrefix . "events e
								LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (e.PkID = ec.EventID)
								LEFT JOIN " . HC_TblPrefix . "categories c ON (c.PkID = ec.CategoryID)
							WHERE e.IsActive = 1 AND e.IsApproved = 1 AND (ec.EventID IS NOT NULL AND c.IsActive = 1)
							AND e.StartDate BETWEEN '" . $mStart . "' AND '" . $mEnd . "'");
			$eCnt = ($now > $mEnd) ? 0 : mysql_result($resultE,0,0);
				
			appInstructions(0, "Create_Newsletter", $hc_lang_news['TitleCreate'], $hc_lang_news['InstructCreateB']);?>
			<script language="JavaScript" type="text/JavaScript">
			//<!--
			function approve(go){
				document.getElementById('next').value = go;
				document.getElementById('frmNewsletter').submit();
			}//end doDelete
			//-->
			</script>
<?php		$stop = 0;

			echo '<br /><form name="frmNewsletter" id="frmNewsletter" method="post" action="' . CalAdminRoot . '/components/MailQueueAction.php" onsubmit="return chkFrm();">';
			echo '<input type="hidden" name="mID" id="mID" value="' . $mID . '" />';
			echo '<input type="hidden" name="next" id="next" value="0" />';
			echo '<fieldset><legend>' . $hc_lang_news['Summary'] . '</legend>';
			echo '<div class="frmOpt"><label>&nbsp;</label><b>' . number_format($subCnt,0,'.',',') . '</b> ' . $hc_lang_news['SubStats'] . '</div>';
			echo '<div class="frmOpt"><label>&nbsp;</label><b>' . number_format($eCnt,0,'.',',') . '</b> ' . $hc_lang_news['EventStats'] . '</div>';
			echo '</fieldset><br />';
			if($subCnt == 0){
				$stop = 4;
			}//end if
			echo '<fieldset><legend>' . $hc_lang_news['Settings'] . '</legend>';
			echo '<div class="frmOpt">';
			echo '<label>' . $hc_lang_news['MailName'] . '</label>';
			echo mysql_result($result,0,1) . '</div>';
			echo '<div class="frmOpt">';
			echo '<label>' . $hc_lang_news['MailSubject'] . '</label>';
			echo mysql_result($result,0,2) . '</div>';
			echo '<div class="frmOpt">';
			echo '<label>' . $hc_lang_news['Dates'] . '</label>';
			if(mysql_result($result,0,3) != ''){
				echo (mysql_result($result,0,3) < date("Y-m-d")) ? '<span style="color:#DC143C;">' . stampToDate(mysql_result($result,0,3), $hc_cfg24) . '</span>' : stampToDate(mysql_result($result,0,3), $hc_cfg24);
			}//end if
			if(mysql_result($result,0,4) != ''){
				echo (mysql_result($result,0,4) < date("Y-m-d")) ? '<span style="color:#DC143C;"> - ' . stampToDate(mysql_result($result,0,4), $hc_cfg24) . '</span>' : ' - ' . stampToDate(mysql_result($result,0,4), $hc_cfg24);
			}//end if
			echo '</div>';
			echo '<div class="frmOpt">';
			echo '<label>' . $hc_lang_news['Groups'] . '</label>' . $groups;
			if($groups == ''){
				$stop = 2;
				echo '<span style="color:#DC143C;line-height:18px;">' . $hc_lang_news['NoGroups'] . '</span>';
			}//end if
			echo '</div>';
			echo '<div class="frmOpt">';
			echo '<label>' . $hc_lang_news['Template'] . '</label>';
			if(mysql_result($result,0,7) != ''){
				echo mysql_result($result,0,7) . '</div>';
			} else {
				$stop = 3;
				echo '<span style="color:#DC143C;line-height:18px;">' . $hc_lang_news['NoTemplate'] . '</span></div>';
			}//end if
			echo '<div class="frmOpt">';
			echo '<label>' . $hc_lang_news['ArchStatus'] . '</label>';
			echo (mysql_result($result,0,5) == 1) ? $hc_lang_news['ArchStatus1'] . '</div>' : $hc_lang_news['ArchStatus0'] . '</div>';
			echo '</fieldset><br />';
			echo '<fieldset><legend>' . $hc_lang_news['Message'];
			echo (strpos(mysql_result($result,0,8),'[message]') === false) ? ' ' . $hc_lang_news['NoMsgTemp'] . '</legend>' : '</legend>';
			echo (mysql_result($result,0,6) != '') ? mysql_result($result,0,6) : $hc_lang_news['NoMessage'];
			echo '</fieldset><br />';
			
			echo ($stop > 0) ? '<span style="color:#DC143C;">' . $hc_lang_news['NewsStop'] . ' ' . $hc_lang_news['NewsStop' . $stop] . '</p>' : '';
			echo '<input type="button" name="save" id="save"';
			echo ($stop > 0) ? ' disabled="disabled"' : '';
			echo ' value=" ' . $hc_lang_news['Approve0'] . ' " class="button" onclick="approve(0);return false;" />';
			echo '<input type="button" name="saveand" id="saveand"';
			echo ($stop > 0) ? ' disabled="disabled"' : '';
			echo ' value=" ' . $hc_lang_news['Approve1'] . ' " class="button" onclick="approve(1);return false;" />';
			echo '<input type="button" name="saveand" id="saveand" value=" ' . $hc_lang_news['Cancel'] . ' " class="button" onclick="window.location.href=\'' . CalAdminRoot . '/index.php?com=newscreate\';return false;" />';
		} else {
			echo '<p>' . $hc_lang_news['InvalidDraft'] . '</p>';
		}//end if
	}//end if
?>