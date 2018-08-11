<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2006 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	if(isset($_GET['step']) && is_numeric($_GET['step'])){
		$step = $_GET['step'];
	} else {
		$step = 0;
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
				feedback(1,"Newsletter Sent Successfully!");
				break;
				
		}//end switch
	}//end if
	
	if($step == 0){
		appInstructions(0, "Sending_Event_Newsletters", "Event Newsletter", "Step 1 of 2) Customize your newsletter settings.");	
		
		
		$result = doQuery("SELECT * FROM " . HC_TblPrefix . "users WHERE IsRegistered = 1");
		if(!hasRows($result)){	?>
			<br />
			There are no active newsletter recipients. Newsletter cannot be sent.
	<?	} else {	?>
		<script language="JavaScript" type="text/JavaScript" src="<?echo CalRoot;?>/includes/java/Dates.js"></script>
		<script language="JavaScript" type="text/JavaScript" src="<?echo CalRoot;?>/includes/java/DateSelect.js"></script>
		<script language="JavaScript" type="text/JavaScript">
		//<!--
		function chkFrm(){
			var dirty = 0;
			var warn = 'Plugin could not be generated for the following reasons:\n';
			var startDate = document.frmNewsletter.startDate.value;
			var endDate = document.frmNewsletter.endDate.value
			
			if(compareDates(startDate, '<?echo $hc_popDateValid;?>', '', '<?echo $hc_popDateValid;?>') == 1){
				dirty = 1;
				warn = warn + "\n*Start Date Cannot Occur After End Date";
			}//end if
			
			if(compareDates('<?echo date($hc_popDateFormat);?>', '<?echo $hc_popDateValid;?>', startDate, '<?echo $hc_popDateValid;?>') == 1){
				dirty = 1;
				warn = warn + "\n*Start Date Cannot Occur Before Today";
			}//end if
			
			if(!isDate(document.frmNewsletter.startDate.value, '<?echo $hc_popDateValid;?>')){
				dirty = 1;
				warn = warn + '\n*Start Date Format is Invalid Date or Format. Required Format: <?echo strtolower($hc_popDateValid);?>';
			} else if(document.frmNewsletter.startDate.value == ''){
				dirty = 1;
				warn = warn + '\n*Start Date is Required';
			}//end if
			
			if(!isDate(document.frmNewsletter.endDate.value, '<?echo $hc_popDateValid;?>')){
				dirty = 1;
				warn = warn + '\n*End Date Format is Invalid Date or Format. Required Format: <?echo strtolower($hc_popDateValid);?>';
			} else if(document.frmNewsletter.endDate.value == ''){
				dirty = 1;
				warn = warn + '\n*End Date is Required';
			} else if(document.frmNewsletter.endDate.value.length < 10) {
				dirty = 1;
				warn = warn + '\n*End Date Must Include Leading 0\'s (<?echo $hc_popDateFormat;?>)';
			}//end if
			
			if(dirty > 0){
				alert(warn + '\n\nPlease make the required changes and try again.');
				return false;
			}//end if
		}//end chkFrm()
		
		var calx = new CalendarPopup();
		//-->
		</script>
		<div style="width:400px;">
		<form name="frmNewsletter" id="frmNewsletter" method="post" action="<?echo "index.php?com=newsletter&amp;step=1";?>" onsubmit="return chkFrm();">
		<br />
		<fieldset>
			<legend>Newsletter Settings</legend>
			<div class="frmOpt">
				<label>Event Dates:</label>
				<input size="12" maxlength="10" type="text" name="startDate" id="startDate" value="<?echo date($hc_popDateFormat);?>" />
				&nbsp;<a href="javascript:;" onclick="calx.select(document.frmNewsletter.startDate,'anchor1','<?echo $hc_popDateValid;?>'); return false;" name="anchor1" id="anchor1"><img src="<?echo CalAdminRoot;?>/images/icons/iconCalendar.gif" width="16" height="16" border="0" alt="" /></a>
				&nbsp;&nbsp;to&nbsp;&nbsp;
				<input size="12" maxlength="10" type="text" name="endDate" id="endDate" value="<?echo date($hc_popDateFormat, mktime(0, 0, 0, date("m"), date("d") + 7, date("Y")));?>" />
				&nbsp;<a href="javascript:;" onclick="calx.select(document.frmNewsletter.endDate,'anchor2','<?echo $hc_popDateValid;?>'); return false;" name="anchor2" id="anchor2"><img src="<?echo CalAdminRoot;?>/images/icons/iconCalendar.gif" width="16" height="16" border="0" alt="" /></a>
			</div>
			<div class="frmOpt">
				<label for="template">Template:</label>
			<?	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "newsletters WHERE IsActive = 1 ORDER BY TemplateName");
				if(hasRows($result)){	?>
					<select name="templateID" id="templateID">
				<?	while($row = mysql_fetch_row($result)){	?>
						<option value="<?echo cOut($row[0]);?>"><?echo cOut($row[1]);?></option>
				<?	}//end while	?>
					</select>
			<?	} else {	
					$stop = true;?>
					No templates currently available.<br />
					<a href="<?echo CalAdminRoot;?>/index.php?com=newsletteredit&amp;nID=0" class="main">Click here to add a template</a>
			<?	}//end if	?>
			</div>
		</fieldset>
		<br />
	<?	if(!isset($stop)){	?>
		<input type="submit" name="submit" id="submit" value="   Submit Dates   " class="button" />
	<?	}//end if	?>
		</form>
		</div>
	
<?		}//end if
	} else {
		appInstructions(1, "Sending_Event_Newsletters", "Event Newsletter", "Step 2 of 2) Confirm settings and review newsletter event stats.<br />If you wish to send click '<i>Send Now</i>', or click '<i>Start Over</i>' to stop.");	
		$startDateF = dateToMySQL(cIn($_POST['startDate']), "/", $hc_popDateFormat);
		$endDateF = dateToMySQL(cIn($_POST['endDate']), "/", $hc_popDateFormat);
		?>
		<br />
		<?	$result = doQuery("SELECT count(*) FROM " . HC_TblPrefix . "events where IsActive = 1 AND IsApproved = 1 AND AlertSent = 0 AND StartDate Between '" . $startDateF . "' AND '" . $endDateF . "'");
			echo "There are <b>" . mysql_result($result,0,0) . "</b> events between these dates that have not yet been announced.";
			
			$result = doQuery("SELECT * FROM " . HC_TblPrefix . "events where AlertSent = 0 AND StartDate >= NOW() AND StartDate Between '" . $startDateF . "' AND '" . cIn($endDateF) . "'");
			
			$sendOk = 0;
			if(hasRows($result)){
				$sendOk = 1;
				echo "<br /><br />First unannounced event occurs on " . stampToDate(mysql_result($result,0,9), $hc_dateFormat);
			}//end if	?>
		<div style="width:400px;">
		<form name="frmNewsletter" id="frmNewsletter" method="post" action="<?echo CalAdminRoot . "/components/NewsletterAction.php";?>">
		<input name="dateFormat" id="dateFormat" type="hidden" value="<?echo strtolower($hc_popDateFormat);?>" />
		<input name="startDate" id="startDate" type="hidden" value="<?echo $startDateF;?>" />
		<input name="endDate" id="endDate" type="hidden" value="<?echo $endDateF;?>" />
		<input name="templateID" id="template" type="hidden" value="<?echo $_POST['templateID'];?>" />
		<br />
	<fieldset>
			<legend>Send Newsletter</legend>
			<div class="frmOpt">
				<label>Event Dates:</label>
				<input name="disabled1" id="disabled1" disabled="disabled" type="text" size="12" maxlength="10" value="<?echo $_POST['startDate'];?>" />
				&nbsp;&nbsp;to&nbsp;&nbsp;
				<input name="disabled2" id="disabled2" disabled="disabled" type="text" size="12" maxlength="10" value="<?echo $_POST['endDate'];?>" />
			</div>
			<div class="frmOpt">
				<label>Template:</label>
			<?	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "newsletters WHERE IsActive = 1 ORDER BY TemplateName");	?>
				<select name="disabled3" id="disabled3" disabled="disabled">
			<?	while($row = mysql_fetch_row($result)){	?>
					<option <?if($_POST['templateID'] == $row[0]){echo "selected=\"selected=\"";}?> value="<?echo cOut($row[0]);?>"><?echo cOut($row[1]);?></option>
			<?	}//end while	?>
				</select>
			</div>
		</fieldset>
		<br />
	<?	if($sendOk > 0){	?>
		<input type="submit" name="submit" id="submit" value="   Send Now   " class="button" />&nbsp;&nbsp;
	<?	}//end if	?>
		<input type="button" name="redo" id="redo" value=" Start Over " onclick="javascript:document.location.href='<?echo CalAdminRoot . "/index.php?com=newsletter";?>'; return false;" class="button" />
	</form>
	</div>
<?	}//end if	?>
