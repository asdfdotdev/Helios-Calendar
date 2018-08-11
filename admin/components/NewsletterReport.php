<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(!defined('hcAdmin')){header("HTTP/1.1 403 No Direct Access");exit();}

	include(HCLANG.'/admin/newsletter.php');

	$rID = (isset($_GET['rID']) && is_numeric($_GET['rID'])) ? cIn($_GET['rID']) : 0;
	$result = doQuery("SELECT n.PkID, n.Subject, n.StartDate, n.EndDate, n.TemplateID, n.SentDate, n.MailerID, n.IsArchive, n.Message, 
						tn.TemplateName, a.FirstName, a.LastName, a.Email, n.SendCount, n.Views, n.ArchViews
					FROM " . HC_TblPrefix . "mailers m
					LEFT JOIN " . HC_TblPrefix . "newsletters n ON (m.PkID = n.MailerID)
					LEFT JOIN " . HC_TblPrefix . "templatesnews tn ON (n.TemplateID = tn.PkID AND tn.IsActive = 1)
					LEFT JOIN " . HC_TblPrefix . "admin a ON (n.SendingAdminID = a.PkID)
					WHERE n.PkID = '" . $rID . "' AND n.IsActive = 1");
	if(hasRows($result)){
		if(mysql_result($result,0,7) == 1)
			$hc_Side[] = array(CalRoot . '/newsletter/index.php?n=' . md5($rID),'iconEmailOpen.png',$hc_lang_news['ViewArchive1'],1);
		
		echo '
		<form name="frmNewsletter" id="frmNewsletter" method="post" action="'.AdminRoot.'/components/MailQueueAction.php" onsubmit="return validate();">
		<input type="hidden" name="mID" id="mID" value="'.$rID.'" />
		<input type="hidden" name="next" id="next" value="0" />
		
		<fieldset>
			<legend>' . $hc_lang_news['Statistics'] . '</legend>
			<label>&nbsp;</label>
			<span class="output">
				<b>'.number_format(mysql_result($result,0,13),0,'.',',').'</b> '.$hc_lang_news['SentStats'].'
			</span>
			<label>&nbsp;</label>
			<span class="output">
				<b>'.number_format(mysql_result($result,0,14),0,'.',',').'</b> '.$hc_lang_news['ViewStats'].'
			</span>
			<label>&nbsp;</label>
			<span class="output">
				<b>'.number_format(mysql_result($result,0,15),0,'.',',').'</b> '.$hc_lang_news['ArchStats'].'
			</span>
		</fieldset>
		<fieldset>
			<legend>'.$hc_lang_news['Summary'].'</legend>
			<label>'.$hc_lang_news['MailSentBy'].'</label>
			<span class="output">
				<a href="mailto:'.mysql_result($result,0,12).'">'.trim(mysql_result($result,0,10).' '.mysql_result($result,0,11)).'</a>
			</span>
			<label>'.$hc_lang_news['MailSent'].'</label>
			<span class="output">
				'.stampToDate(mysql_result($result,0,5), $hc_cfg[24]).'
			</span>
			<label>'.$hc_lang_news['MailSubject'].'</label>
			<span class="output">
				'.cOut(mysql_result($result,0,1)).'
			</span>
			<label>'.$hc_lang_news['Dates'].'</label>
			<span class="output">
				'.stampToDate(mysql_result($result,0,2), $hc_cfg[24]).' - '.stampToDate(mysql_result($result,0,3), $hc_cfg[24]).'
			</span>
			<label>'.$hc_lang_news['Template'].'</label>
			<span class="output">
				'.((mysql_result($result,0,9) != '') ? mysql_result($result,0,9) : $hc_lang_news['NoTemplateB']).'
			</span>
			<label>' . $hc_lang_news['ArchStatus'] . '</label>
			<span class="output">
				'.((mysql_result($result,0,7) == 1) ? $hc_lang_news['ArchStatus1'] : $hc_lang_news['ArchStatus0']).'
			</span>
		</fieldset>';
		if(mysql_result($result,0,8) != ''){
			echo '<fieldset><legend>' . $hc_lang_news['Message'] . '</legend>' . mysql_result($result,0,8) . '</fieldset><br />';
		}
	} else {
		echo '<p>' . $hc_lang_news['NoReport'] . '</p>';
	}
?>