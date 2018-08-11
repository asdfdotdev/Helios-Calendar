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

	$rID = (isset($_GET['rID']) && is_numeric($_GET['rID'])) ? cIn($_GET['rID']) : 0;
	$result = doQuery("SELECT n.PkID, n.Subject, n.StartDate, n.EndDate, n.TemplateID, n.SentDate, n.MailerID, n.IsArchive, n.Message, 
						tn.TemplateName, a.FirstName, a.LastName, a.Email, n.SendCount, n.Views, n.ArchViews
					FROM " . HC_TblPrefix . "mailers m
					LEFT JOIN " . HC_TblPrefix . "newsletters n ON (m.PkID = n.MailerID)
					LEFT JOIN " . HC_TblPrefix . "templatesnews tn ON (n.TemplateID = tn.PkID AND tn.IsActive = 1)
					LEFT JOIN " . HC_TblPrefix . "admin a ON (n.SendingAdminID = a.PkID)
					WHERE n.PkID = '" . $rID . "' AND n.IsActive = 1");
					
	if(hasRows($result)){
		if(mysql_result($result,0,7) == 1){
			$hc_Side[] = array(CalRoot . '/newsletter/index.php?n=' . md5($rID),'iconEmailOpen.png',$hc_lang_news['ViewArchive1'],1);
		}//end if

		echo '<br /><form name="frmNewsletter" id="frmNewsletter" method="post" action="' . CalAdminRoot . '/components/MailQueueAction.php" onsubmit="return chkFrm();">';
		echo '<input type="hidden" name="mID" id="mID" value="' . $rID . '" />';
		echo '<input type="hidden" name="next" id="next" value="0" />';
		echo '<fieldset><legend>' . $hc_lang_news['Statistics'] . '</legend>';
		echo '<div class="frmOpt"><label>&nbsp;</label><b>' . number_format(mysql_result($result,0,13),0,'.',',') . '</b> ' . $hc_lang_news['SentStats'] . '</div>';
		echo '<div class="frmOpt"><label>&nbsp;</label><b>' . number_format(mysql_result($result,0,14),0,'.',',') . '</b> ' . $hc_lang_news['ViewStats'] . '</div>';
		echo '<div class="frmOpt"><label>&nbsp;</label><b>' . number_format(mysql_result($result,0,15),0,'.',',') . '</b> ' . $hc_lang_news['ArchStats'] . '</div>';
		echo '</fieldset><br />';
		echo '<fieldset><legend>' . $hc_lang_news['Summary'] . '</legend>';
		echo '<div class="frmOpt">';
		echo '<label>' . $hc_lang_news['MailSentBy'] . '</label>';
		echo '<a href="mailto:' . mysql_result($result,0,12) . '" class="main">' . trim(mysql_result($result,0,10) . ' ' . mysql_result($result,0,11)) . '</a></div>';
		echo '<div class="frmOpt">';
		echo '<label>' . $hc_lang_news['MailSent'] . '</label>';
		echo stampToDate(mysql_result($result,0,5), $hc_cfg24) . '</div>';
		echo '<div class="frmOpt">';
		echo '<label>' . $hc_lang_news['MailSubject'] . '</label>';
		echo mysql_result($result,0,1) . '</div>';
		echo '<div class="frmOpt">';
		echo '<label>' . $hc_lang_news['Dates'] . '</label>';
		echo stampToDate(mysql_result($result,0,2), $hc_cfg24) . ' - ' . stampToDate(mysql_result($result,0,3), $hc_cfg24);
		echo '</div>';
		echo '<div class="frmOpt">';
		echo '<label>' . $hc_lang_news['Template'] . '</label>';
		echo (mysql_result($result,0,9) != '') ? mysql_result($result,0,9) . '</div>' : $hc_lang_news['NoTemplateB'] . '</div>';
		echo '<div class="frmOpt">';
		echo '<label>' . $hc_lang_news['ArchStatus'] . '</label>';
		echo (mysql_result($result,0,7) == 1) ? $hc_lang_news['ArchStatus1'] . '</div>' : $hc_lang_news['ArchStatus0'] . '</div>';
		echo '</fieldset><br />';
		if(mysql_result($result,0,8) != ''){
			echo '<fieldset><legend>' . $hc_lang_news['Message'] . '</legend>' . mysql_result($result,0,8) . '</fieldset><br />';
		}//end if
	} else {
		echo '<p>' . $hc_lang_news['NoReport'] . '</p>';
	}//end if
?>