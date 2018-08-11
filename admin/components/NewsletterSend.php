<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(!defined('hcAdmin')){header("HTTP/1.1 403 No Direct Access");exit();}

	include(HCLANG.'/admin/newsletter.php');
	
	appInstructions(0, "Sending_Newsletters", $hc_lang_news['TitleSend'], $hc_lang_news['InstructSend']);

	$nID = (isset($_GET['nID']) && is_numeric($_GET['nID'])) ? cIn(strip_tags($_GET['nID'])) : 0;

	$result = doQuery("SELECT PkID, Subject, SendCount FROM " . HC_TblPrefix . "newsletters WHERE PkID = '" . $nID . "' AND IsActive = 1 AND Status < 3");
	if(hasRows($result)){
		echo '
		<fieldset>
			<legend>'.$hc_lang_news['ProgressLabel'].'</legend>
			
			<label>'.$hc_lang_news['MailSubject'].'</label>
			<span class="output">'.mysql_result($result,0,1).'</span>

			<label>'.$hc_lang_news['MailingSize'].'</label>
			<span class="output">'.number_format(mysql_result($result,0,2),0,'.',',') . ' ' . $hc_lang_news['Subscribers'].'</span>

			<label>'.$hc_lang_news['Progress'].'</label>
			<iframe src="'.AdminRoot.'/components/NewsletterProgress.php?n='.$nID.'" name="progress" id="progress" border="0" scrolling="no"></iframe>
		</fieldset>
		<a href="#" onclick="startMailer(1);" class="newsSend">'.$hc_lang_news['Send'].'</a>
		<a href="#" onclick="startMailer(0);" class="newsPause">'.$hc_lang_news['Pause'].'</a>
		<div id="pauseIt" style="display:none;"></div>
		
		<script src="'.CalRoot.'/inc/javascript/ajxOutput.js"></script>
		<script>
		//<!--
			function startMailer(onoff){
				if(onoff == 1){
					ajxOutput("'.AdminRoot.'/components/NewsletterPause.php?n='.$nID.'&d=1", "pauseIt", "'.AdminRoot.'");
					setTimeout("document.getElementById(\'progress\').src = \'components/NewsletterProgress.php?n='.$nID.'\'",1000);
				} else {
					ajxOutput("'.AdminRoot.'/components/NewsletterPause.php?n='.$nID.'&d=2", "pauseIt", "'.AdminRoot.'");
					alert("'.$hc_lang_news['Valid38'].'");
				}
			}
		//-->
		</script>';
	} else {
		echo '<p>' . $hc_lang_news['NoNewsletter'] . '</p>';
	}
?>
