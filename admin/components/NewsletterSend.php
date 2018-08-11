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
	appInstructions(0, "Sending_Newsletters", $hc_lang_news['TitleSend'], $hc_lang_news['InstructSend']);

	$nID = (isset($_GET['nID']) && is_numeric($_GET['nID'])) ? cIn($_GET['nID']) : 0;

	$result = doQuery("SELECT PkID, Subject, SendCount FROM " . HC_TblPrefix . "newsletters WHERE PkID = '" . $nID . "' AND IsActive = 1 AND Status < 3");
	if(hasRows($result)){?>
		<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/ajxOutput.js"></script>
		<script language="JavaScript" type="text/JavaScript">
		//<!--
			function startMailer(onoff){
				if(onoff == 1){
					ajxOutput('NewsletterPause.php?n=<?php echo $nID;?>&d=1', 'pauseIt', '<?php echo CalAdminRoot;?>');
					setTimeout("document.getElementById('progress').src = 'components/NewsletterProgress.php?n=<?php echo $nID;?>'",1000);
				} else {
					ajxOutput('NewsletterPause.php?n=<?php echo $nID;?>&d=2', 'pauseIt', '<?php echo CalAdminRoot;?>');
					alert('<?php echo $hc_lang_news['Valid38'];?>');
				}//end if
			}//end startMailer()
		//-->
		</script>
		<br />
		<fieldset>
			<legend><?php echo $hc_lang_news['ProgressLabel'];?></legend>
			<div frm="frmOpt">
				<label><?php echo $hc_lang_news['MailSubject'];?></label>
				<?php echo mysql_result($result,0,1);?>
			</div>
			<div frm="frmOpt">
				<label><?php echo $hc_lang_news['MailingSize'];?></label>
				<?php echo number_format(mysql_result($result,0,2),0,'.',',') . ' ' . $hc_lang_news['Newsletters'];?>
			</div>
			<div frm="frmOpt">
				<label><?php echo $hc_lang_news['Progress'];?></label>
				<iframe src="components/NewsletterProgress.php?n=<?php echo $nID;?>" id="progress" border="0" scrolling="no"></iframe>
			</div>
		</fieldset>
		<a href="#" onclick="startMailer(1);" class="newsSend"><?php echo $hc_lang_news['Send'];?></a>&nbsp;&nbsp;
		<a href="#" onclick="startMailer(0);" class="newsPause"><?php echo $hc_lang_news['Pause'];?></a>
		<div id="pauseIt" style="display:none;"></div>
<?php
	} else {
		echo '<p>' . $hc_lang_news['NoNewsletter'] . '</p>';
	}//end if
?>
