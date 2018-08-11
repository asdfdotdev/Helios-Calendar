<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2009 Refresh Web Development [www.RefreshMy.com]
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/admin/newsletter.php');
	
	$hourOffset = date("G") + ($hc_cfg35);
	
	$step = 0;
	if(isset($_GET['step']) && is_numeric($_GET['step'])){
		$step = $_GET['step'];
	}//end if
	
	$startDate = "";
	if(isset($_POST['startDate'])){
		$startDate = $_POST['startDate'];
	}//end if
	
	$endDate = "";
	if(isset($_POST['endDate'])){
		$endDate = $_POST['endDate'];
	}//end if
	
	$template = "";
	if(isset($_POST['template'])){
		$template = $_POST['template'];
	}//end if
	
	if (isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1,$hc_lang_news['Feed06']);
				break;
				
		}//end switch
	}//end if
	
	if($step == 0){
		appInstructions(0, "Sending_Newsletters", $hc_lang_news['TitleSendN'], $hc_lang_news['InstructSendN1']);
		
		$result = doQuery("SELECT * FROM " . HC_TblPrefix . "users WHERE IsRegistered = 1");
		if(!hasRows($result)){
			echo "<br />";
			echo $hc_lang_news['NoRecipients'];
		} else {	?>
		<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot . "/" . $hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . "/popCal.js";?>"></script>
		<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/DateSelect.js"></script>
		<script language="JavaScript" type="text/JavaScript">
		//<!--
		function chkFrm(){
			var dirty = 0;
			var warn = '<?php echo $hc_lang_news['Valid12'];?>\n';
			var startDate = document.frmNewsletter.startDate.value;
			var endDate = document.frmNewsletter.endDate.value
			
			if(compareDates(startDate, '<?php echo $hc_cfg51;?>', '', '<?php echo $hc_cfg51;?>') == 1){
				dirty = 1;
				warn = warn + "\n<?php echo $hc_lang_news['Valid13'];?>";
			}//end if
			
			if(compareDates('<?php echo strftime($hc_cfg24);?>', '<?php echo $hc_cfg51;?>', startDate, '<?php echo $hc_cfg51;?>') == 1){
				dirty = 1;
				warn = warn + "\n<?php echo $hc_lang_news['Valid14'];?>";
			}//end if
			
			if(!isDate(document.frmNewsletter.startDate.value, '<?php echo $hc_cfg51;?>')){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_news['Valid15'] . " " . strtolower($hc_cfg51);?>';
			} else if(document.frmNewsletter.startDate.value == ''){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_news['Valid16'];?>';
			}//end if
			
			if(!isDate(document.frmNewsletter.endDate.value, '<?php echo $hc_cfg51;?>')){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_news['Valid17'] . " " . strtolower($hc_cfg51);?>';
			} else if(document.frmNewsletter.endDate.value == ''){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_news['Valid18'];?>';
			}//end if
			
			if(dirty > 0){
				alert(warn + '\n\n<?php echo $hc_lang_news['Valid19'];?>');
				return false;
			}//end if
		}//end chkFrm()
		
		var calx = new CalendarPopup("dsCal");
		calx.setCssPrefix("hc_");
		//-->
		</script>
		<div style="width:400px;">
		<form name="frmNewsletter" id="frmNewsletter" method="post" action="<?php echo "index.php?com=newsletter&amp;step=1";?>" onsubmit="return chkFrm();">
		<br />
		<fieldset>
			<legend><?php echo $hc_lang_news['Settings'];?></legend>
			<div class="frmOpt">
				<label><?php echo $hc_lang_news['Dates'];?></label>
				<input size="12" maxlength="10" type="text" name="startDate" id="startDate" value="<?php echo strftime($hc_cfg24,mktime($hourOffset,date("i"),date("s"),date("m"),date("d"),date("Y")));?>" />&nbsp;<a href="javascript:;" onclick="calx.select(document.frmNewsletter.startDate,'anchor1','<?php echo $hc_cfg51;?>'); return false;" name="anchor1" id="anchor1"><img src="<?php echo CalAdminRoot;?>/images/icons/iconCalendar.png" width="16" height="16" border="0" alt="" /></a>
				&nbsp;&nbsp;<?php echo $hc_lang_news['To'];?>&nbsp;&nbsp;
				<input size="12" maxlength="10" type="text" name="endDate" id="endDate" value="<?php echo strftime($hc_cfg24,mktime($hourOffset,date("i"),date("s"),date("m"),date("d") + 7,date("Y")));?>" />&nbsp;<a href="javascript:;" onclick="calx.select(document.frmNewsletter.endDate,'anchor2','<?php echo $hc_cfg51;?>'); return false;" name="anchor2" id="anchor2"><img src="<?php echo CalAdminRoot;?>/images/icons/iconCalendar.png" width="16" height="16" border="0" alt="" /></a>
			</div>
			<div class="frmOpt">
				<label><?php echo $hc_lang_news['Template'];?></label>
		<?php 	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "newsletters WHERE IsActive = 1 ORDER BY TemplateName");
				if(hasRows($result)){	?>
					<select name="templateID" id="templateID">
			<?php 	while($row = mysql_fetch_row($result)){	?>
						<option value="<?php echo cOut($row[0]);?>"><?php echo cOut($row[1]);?></option>
			<?php 	}//end while	?>
					</select>
		<?php 	} else {	
					$stop = true;
					echo $hc_lang_news['NoTemplates'];
					echo "<br />";
					echo "<a href=\"" . CalAdminRoot . "/index.php?com=newsletteredit&amp;nID=0\" class=\"main\">" . $hc_lang_news['AddTemplate'] . "</a>";
				}//end if	?>
			</div>
		</fieldset>
		<br />
<?php 	if(!isset($stop)){	?>
		<input type="submit" name="submit" id="submit" value=" <?php echo $hc_lang_news['Submit'];?> " class="button" />
<?php 	}//end if	?>
		</form>
		</div>
<?php 	}//end if
	} else {
		appInstructions(1, "Sending_Newsletters", $hc_lang_news['TitleSendN'], $hc_lang_news['InstructSendN2']);
		$startDateF = dateToMySQL(cIn($_POST['startDate']), "/", $hc_cfg24);
		$endDateF = dateToMySQL(cIn($_POST['endDate']), "/", $hc_cfg24);
		?>
		<br />
	<?php 	$result = doQuery("	SELECT count(*), MIN(StartDate)
								FROM " . HC_TblPrefix . "events 
								WHERE IsActive = 1 AND 
									IsApproved = 1 AND 
									AlertSent = 0 AND 
									StartDate Between '" . $startDateF . "' AND '" . $endDateF . "'");
			echo "<b>" . $hc_lang_news['UnsentEvents'] . "</b> " . mysql_result($result,0,0);
			
			$sendOk = 0;
			if(mysql_result($result,0,1) != ''){
				$sendOk = 1;
				echo "<br /><br /><b>" . $hc_lang_news['FirstUnsent'] . "</b> " . stampToDate(mysql_result($result,0,1), $hc_cfg14);
			}//end if	?>
		<div style="width:400px;">
		<form name="frmNewsletter" id="frmNewsletter" method="post" action="<?php echo CalAdminRoot . "/components/NewsletterAction.php";?>">
		<input name="dateFormat" id="dateFormat" type="hidden" value="<?php echo strtolower($hc_cfg24);?>" />
		<input name="startDate" id="startDate" type="hidden" value="<?php echo $startDateF;?>" />
		<input name="endDate" id="endDate" type="hidden" value="<?php echo $endDateF;?>" />
		<input name="templateID" id="template" type="hidden" value="<?php echo $_POST['templateID'];?>" />
		<br />
	<fieldset>
			<legend><?php echo $hc_lang_news['SendNewsletter'];?></legend>
			<div class="frmOpt">
				<label><?php echo $hc_lang_news['Dates'];?></label>
				<input name="disabled1" id="disabled1" disabled="disabled" type="text" size="12" maxlength="10" value="<?php echo $_POST['startDate'];?>" />
				&nbsp;&nbsp;to&nbsp;&nbsp;
				<input name="disabled2" id="disabled2" disabled="disabled" type="text" size="12" maxlength="10" value="<?php echo $_POST['endDate'];?>" />
			</div>
			<div class="frmOpt">
				<label><?php echo $hc_lang_news['Template'];?></label>
		<?php 	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "newsletters WHERE IsActive = 1 ORDER BY TemplateName");	?>
				<select name="disabled3" id="disabled3" disabled="disabled">
		<?php 	while($row = mysql_fetch_row($result)){	?>
					<option <?php if($_POST['templateID'] == $row[0]){echo "selected=\"selected\"";}?> value="<?php echo cOut($row[0]);?>"><?php echo cOut($row[1]);?></option>
		<?php 	}//end while	?>
				</select>
			</div>
		</fieldset>
		<br />
<?php 	if($sendOk > 0){	?>
		<input type="submit" name="submit" id="submit" value=" <?php echo $hc_lang_news['SendNow'];?> " class="button" />&nbsp;&nbsp;
<?php 	}//end if	?>
		<input type="button" name="redo" id="redo" value=" <?php echo $hc_lang_news['StartOver'];?> " onclick="javascript:document.location.href='<?php echo CalAdminRoot . "/index.php?com=newsletter";?>'; return false;" class="button" />
	</form>
	</div>
<?php
	}//end if	?>
	<div id="dsCal" class="datePicker"></div>