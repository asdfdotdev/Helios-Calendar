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
	$hourOffset = date("G") + ($hc_cfg35);
	$stop = false;
	$title = $subject = $message = '';
	$template = $archive = 0;
	$startDate = stampToDate(date("Y-m-d"), $hc_cfg24);
	$endDate = stampToDate(date("Y-m-d", strtotime('+7 days')), $hc_cfg24);

	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "mailers WHERE PkID = '" . $mID . "' AND IsActive = 1");
	if(hasRows($result)){
		$title = cOut(mysql_result($result,0,1));
		$subject = cOut(mysql_result($result,0,2));
		$startDate = (mysql_result($result,0,3) != '') ? stampToDate(mysql_result($result,0,3), $hc_cfg24) : $startDate;
		$endDate = (mysql_result($result,0,4) != '') ? stampToDate(mysql_result($result,0,4), $hc_cfg24) : $endDate;
		$template = mysql_result($result,0,5);
		$message = cOut(mysql_result($result,0,6));
		$archive = mysql_result($result,0,10);
		$cDate = (mysql_result($result,0,7) != '') ? stampToDate(mysql_result($result,0,7), $hc_cfg24) : '';
		$mDate = (mysql_result($result,0,8) != '') ? stampToDate(mysql_result($result,0,8), $hc_cfg24) : '';
	}//end if

	appInstructions(0, "Compose_Draft", $hc_lang_news['TitleDraft'], $hc_lang_news['InstructDraft']);
?>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot . "/" . $hc_langPath . $_SESSION['LangSet'] . "/popCal.js";?>"></script>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/DateSelect.js"></script>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Checkboxes.js"></script>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function chkFrm(){
		var dirty = before = 0;
		var warn = '<?php echo $hc_lang_news['Valid12'];?>';

		if(document.getElementById('submit').disabled){
			dirty = 1;
		}//end if

		if(document.getElementById('mailTitle').value == ''){
			dirty = 1;
			warn = warn + "\n<?php echo $hc_lang_news['Valid39'];?>";
		}//end if

		if(document.getElementById('mailSubj').value == ''){
			dirty = 1;
			warn = warn + "\n<?php echo $hc_lang_news['Valid40'];?>";
		}//end if

		if(compareDates(document.getElementById('startDate').value, '<?php echo $hc_cfg51;?>', document.getElementById('endDate').value, '<?php echo $hc_cfg51;?>') == 1){
			dirty = 1;
			warn = warn + "\n<?php echo $hc_lang_news['Valid13'];?>";
		}//end if

		if(compareDates('<?php echo strftime($hc_cfg24);?>', '<?php echo $hc_cfg51;?>', document.getElementById('startDate').value, '<?php echo $hc_cfg51;?>') == 1){
			before = 1;
		}//end if

		if(compareDates('<?php echo strftime($hc_cfg24);?>', '<?php echo $hc_cfg51;?>', document.getElementById('endDate').value, '<?php echo $hc_cfg51;?>') == 1){
			before = 1;
		}//end if

		if(!isDate(document.getElementById('startDate').value, '<?php echo $hc_cfg51;?>')){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_news['Valid15'] . " " . strtolower($hc_cfg51);?>';
		} else if(document.getElementById('startDate').value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_news['Valid16'];?>';
		}//end if

		if(!isDate(document.getElementById('endDate').value, '<?php echo $hc_cfg51;?>')){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_news['Valid17'] . " " . strtolower($hc_cfg51);?>';
		} else if(document.getElementById('endDate').value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_news['Valid18'];?>';
		}//end if
		
		if(validateCheckArray('frmNewsletter','grpID[]',1) > 0){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_news['Valid41'];?>';
		}//end if

		if(dirty > 0){
			alert(warn + '\n\n<?php echo $hc_lang_news['Valid19'];?>');
			return false;
		} else if(before > 0) {
			if(confirm("<?php echo $hc_lang_news['Valid14'];?>")){
				return true;
			} else {
				return false;
			}//end if
		}//end if
	}//end chkFrm()

	var calx = new CalendarPopup("dsCal");
	calx.showNavigationDropdowns();
	calx.setCssPrefix("hc_");
	//-->
	</script>
	<br />
	<form name="frmNewsletter" id="frmNewsletter" method="post" action="<?php echo CalAdminRoot . "/components/MailCreateAction.php";?>" onsubmit="return chkFrm();">
	<input type="hidden" name="mID" id="mID" value="<?php echo $mID;?>" />
	<fieldset>
		<legend><?php echo $hc_lang_news['Settings'];?></legend>
		<div class="frmOpt">
			<label><?php echo $hc_lang_news['MailName'];?></label>
			<input size="35" maxlength="50" type="text" name="mailTitle" id="mailTitle" value="<?php echo $title;?>" />
			&nbsp;<?php appInstructionsIcon($hc_lang_news['Tip04A'],$hc_lang_news['Tip04B']);?>
		</div>
		<div class="frmOpt">
			<label><?php echo $hc_lang_news['MailSubject'];?></label>
			<input size="70" maxlength="50" type="text" name="mailSubj" id="mailSubj" value="<?php echo $subject;?>" />
			&nbsp;<?php appInstructionsIcon($hc_lang_news['Tip05A'],$hc_lang_news['Tip05B']);?>
		</div>
		<div class="frmOpt">
			<label><?php echo $hc_lang_news['Dates'];?></label>
			<input size="12" maxlength="10" type="text" name="startDate" id="startDate" value="<?php echo $startDate;?>" /><div class="hc_align">&nbsp;<a href="javascript:;" onclick="calx.select(document.getElementById('startDate'),'anchor1','<?php echo $hc_cfg51;?>'); return false;" name="anchor1" id="anchor1"><img src="<?php echo CalAdminRoot;?>/images/icons/iconCalendar.png" width="16" height="16" border="0" alt="" /></a>&nbsp;</div>
			<div class="hc_align">&nbsp;&nbsp;<?php echo $hc_lang_news['To'];?>&nbsp;&nbsp;</div>
			<input size="12" maxlength="10" type="text" name="endDate" id="endDate" value="<?php echo $endDate;?>" /><div class="hc_align">&nbsp;<a href="javascript:;" onclick="calx.select(document.getElementById('endDate'),'anchor2','<?php echo $hc_cfg51;?>'); return false;" name="anchor2" id="anchor2"><img src="<?php echo CalAdminRoot;?>/images/icons/iconCalendar.png" width="16" height="16" border="0" alt="" /></a></div>
		</div>
		<div class="frmOpt">
			<label><?php echo $hc_lang_news['Groups'];?></label>
	<?php	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "mailgroups WHERE IsActive = 1");
			if(hasRows($result)){
				$result = doQuery("SELECT mg.*, m.PkID as Selected
								 FROM " . HC_TblPrefix . "mailgroups mg
									 LEFT JOIN " . HC_TblPrefix . "mailersgroups mgs ON (mgs.GroupID = mg.PkID AND mgs.MailerID = '" . $mID . "')
									 LEFT JOIN " . HC_TblPrefix . "mailers m ON (mgs.MailerID = m.PkID and m.IsActive = 1)
								 WHERE mg.IsActive = 1
								 Group By mg.PkID");
				$cnt = 1;
				echo '<div class="catCol">';
				while($row = mysql_fetch_row($result)){
					if($cnt > ceil(mysql_num_rows($result) / 3)){
						echo ($cnt > 1) ? '</div>' : '';
						echo '<div class="catCol">';
						$cnt = 1;
					}//end if
					echo '<label for="grpID_' . $row[0] . '" class="group">';
					echo '<input name="grpID[]" id="grpID_' . $row[0] . '" type="checkbox" value="' . $row[0] . '" class="noBorderIE" ';
					echo ($row[5] != '') ? 'checked="checked" />' : '/>';
					echo cOut($row[1]) . '</label>';
					++$cnt;
				}//end while
				echo '</div>';
			} else {
				$stop = true;
				echo $hc_lang_news['NoGroups'];
				echo '<input type="hidden" name="grpID[]" id="grpID_0" value="" />';
			}//end if?>
		</div>
		<div class="frmOpt">
			<label><?php echo $hc_lang_news['Template'];?></label>
	<?php 	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "templatesnews WHERE IsActive = 1 ORDER BY TemplateName");
			if(hasRows($result)){
				echo '<select name="templateID" id="templateID">';
				while($row = mysql_fetch_row($result)){
					echo '<option ';
					echo ($row[0] == $template) ? 'selected="selected" ' : '';
					echo 'value="' . cOut($row[0]) . '">' . cOut($row[1]) . '</option>';
				}//end while
				echo '</select>';
			} else {
				$stop = true;
				echo $hc_lang_news['NoTemplates'] . '<br />';
				echo "<a href=\"" . CalAdminRoot . "/index.php?com=newsletteredit&amp;nID=0\" class=\"main\">" . $hc_lang_news['AddTemplate'] . "</a>";
			}//end if	?>
		</div>
		<div class="frmOpt">
			<label><?php echo $hc_lang_news['ArchStatus'];?></label>
			<select name="archStatus" id="archStatus">
				<option <?php echo ($archive == 0) ? 'selected="selected"' : '';?> value="0"><?php echo $hc_lang_news['ArchStatus0'];?></option>
				<option <?php echo ($archive == 1) ? 'selected="selected"' : '';?> value="1"><?php echo $hc_lang_news['ArchStatus1'];?></option>
			</select>
			&nbsp;<?php appInstructionsIcon($hc_lang_news['Tip06A'],$hc_lang_news['Tip06B']);?>
		</div>
	<?php
		if($mID > 0 && $mDate != ''){
			echo '<div class="frmOpt">';
			echo '<label>' . $hc_lang_news['LastModDate'] . '</label>';
			echo '<b style="line-height:18px;">' . $mDate . '</b>';
			echo '</div>';
		}//end if?>
	</fieldset>
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_news['Message'];?></legend>
		<div class="frmOpt">
	<?php
		echo $hc_lang_news['MessageNote'] . ' <b>[message]</b><br /><br />';
		makeTinyMCE('mailMsg', '100%', 'advanced',$message);?>
		</div>
	</fieldset>
	<br />
<?php
	echo '<input type="submit" name="submit" id="submit"';
	echo ($stop == true) ? ' disabled="disabled"' : '';
	echo ' value=" ' . $hc_lang_news['SaveDraft'] . ' " class="button" />&nbsp;&nbsp;';
	echo ($mID > 0) ? '<input type="button" name="cancel" id="cancel" value=" ' . $hc_lang_news['Cancel'] . '  " onclick="window.location.href=\'' . CalAdminRoot . '/index.php?com=newscreate\';return false;" class="button" />' : '';
	?>
	</form>
	<div id="dsCal" class="datePicker"></div>