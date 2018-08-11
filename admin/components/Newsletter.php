<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
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
	
	if(isset($_POST['startDate'])){
		$startDate = $_POST['startDate'];
	} else {
		if($step > 0){
			header("Location:" . CalAdminRoot . "/index.php?com=newsletter");
		}//end if
	}//end if
	
	if(isset($_POST['endDate'])){
		$endDate = $_POST['endDate'];
	} else {
		if($step > 0){
			header("Location:" . CalAdminRoot . "/index.php?com=newsletter");
		}//end if
	}//end if
	
	if(isset($_POST['template'])){
		$template = $_POST['template'];
	} else {
		if($step > 0){
			header("Location:" . CalAdminRoot . "/index.php?com=newsletter");
		}//end if
	}//end if
?>
<script language="JavaScript"/>

function chkDate(obj){
	var re = /^(\d{1,2})[\/|-](\d{1,2})[\/|-](\d{2}|\d{4})$/
	if(obj.value != ''){
		if(!re.test(obj.value)) {
			return 1;
		} else {
			return 0;
		}//end if
	}//end if
}//end chkDate()

function chkFrm(){
	var dirty = 0;
	var warn = 'Plugin could not be generated for the following reasons:\n';
	var startDate = document.frm.startDate.value;
	var endDate = document.frm.endDate.value
	
	if(compareDates(startDate, 'MM/d/yyyy', endDate, 'MM/d/yyyy') == 1){
		dirty = 1;
		warn = warn + "\n*Start Date Cannot Occur After End Date";
	}//end if
	
	if(chkDate(document.frm.startDate) == 1){
		dirty = 1;
		warn = warn + '\n*Start Date Format Invalid';
	} else if(document.frm.startDate.value == ''){
		dirty = 1;
		warn = warn + '\n*Start Date is Required';
	} else if(document.frm.startDate.value.length < 10) {
		dirty = 1;
		warn = warn + '\n*Start Date Must Include Leading 0\'s (MM/DD/YYYY)';	
	}//end if
	
	if(chkDate(document.frm.endDate) == 1){
		dirty = 1;
		warn = warn + '\n*End Date Format Invalid';
	} else if(document.frm.endDate.value == ''){
		dirty = 1;
		warn = warn + '\n*End Date is Required';
	} else if(document.frm.endDate.value.length < 10) {
		dirty = 1;
		warn = warn + '\n*End Date Must Include Leading 0\'s (MM/DD/YYYY)';
	}//end if
	
	if(dirty > 0){
		alert(warn + '\n\nPlease make the required changes and try again.');
		return false;
	}//end if
	
}//end chkFrm()


var calx = new CalendarPopup();
</script>

<form name="frm" id="frm" method="post" <?if($step > 0){echo " action=\"" . CalAdminRoot . "/" . HC_NewsletterAction . "\"";}else {echo "action=\"index.php?com=newsletter&step=1\"";}?> onSubmit="return chkFrm();">
<?php 
	if (isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1,"Announcements Sent Successfully!");
				break;
				
		}//end switch
	}//end if
	
		if($step == 0){
			appInstructions(0, "Sending_Event_Newsletters", "Event Newsletter", "Step 1 of 2) Customize your newsletter settings.");
		?>
			<br>
			<div align="right"></div>
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td colspan="3" class="eventMain"><b>Newsletter Settings</b> [ <a href="<?echo CalAdminRoot;?>/index.php?com=newsletteredit" class="main">View/Edit Templates</a> ]</td>
				</tr>
				<tr><td colspan="3"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
				<tr><td colspan="3" class="eventSeparator"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
				<tr><td colspan="3"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
				<tr>
					<td colspan="2">
						
						<table cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td><input type="text" name="startDate" id="startDate" value="<?echo date("m/d/Y");?>" class="input" size="10"></td>
								<td class="eventMain">&nbsp;<a href="javascript:;" onclick="calx.select(document.forms[0].startDate,'anchor1','MM/dd/yyyy'); return false;" name="anchor1" id="anchor1"><img src="<?echo CalRoot;?>/images/datepicker/cal.gif" width="16" height="16" border="0" alt=""></a></td>
								<td class="eventMain">&nbsp;&nbsp;and&nbsp;&nbsp;</td>
								<td class="eventMain"><input type="text" name="endDate" id="endDate" value="<?echo date("m/d/Y", mktime(0, 0, 0, date("m") + 1, 1 - 1, date("Y")));?>" class="input" size="10"></td>
								<td>&nbsp;<a href="javascript:;" onclick="calx.select(document.forms[0].endDate,'anchor2','MM/dd/yyyy'); return false;" name="anchor2" id="anchor2"><img src="<?echo CalRoot;?>/images/datepicker/cal.gif" width="16" height="16" border="0" alt=""></a></td>
							</tr>
						</table>
						
					</td>
					<td width="25" align="right"><? appInstructionsIcon("Newsletter Date Range", "Select the dates you want the alert to include. All events occuring <b>on and between</b> these days will be included in your newsletter."); ?></td>
				</tr>
				<tr><td colspan="3"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
				<tr><td colspan="3" class="eventSeparator"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
				<tr><td colspan="3"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
				<tr>
					<td width="75" class="eventMain">Template:</td>
					<td class="eventMain">
					<?php
						$result = doQuery("SELECT * FROM " . HC_TblPrefix . "newsletters WHERE IsActive = 1 ORDER BY TemplateName");
						if(hasRows($result)){	?>
							<select name="template" id="template" class="input">
							<?	while($row = mysql_fetch_row($result)){	?>
									<option value="<?echo cOut($row[0]);?>"><?echo cOut($row[1]);?></option>
							<?	}//end while	?>
							</select>
					<?	} else {	
							$stop = true;?>
							No templates currently available.<br>
							<a href="<?echo CalAdminRoot;?>/index.php?com=newsletteredit&nID=0" class="main">Click here to add a template</a>
					<?	}//end if	?>
					</td>
					<td width="25" align="right"><? appInstructionsIcon("Newsletter Template", "Select the template you wish to apply to this Event Newsletter."); ?></td>
				</tr>
				
				
		<?php
			} else {
				appInstructions(1, "Sending_Event_Newsletters", "Event Newsletter", "Step 2 of 2) Confirm settings and review newsletter event stats.<br>If you wish to send click '<i>Send Now</i>', or click '<i>Start Over</i>' to stop.");
		?>
		<br>
		<ul>
		<?php
			$startDate2 = dateToMySQL($startDate, "/");
			$endDate2 = dateToMySQL($endDate, "/");
			$result = doQuery("SELECT count(*) FROM " . HC_TblPrefix . "events where AlertSent = 0 AND StartDate Between '" . $startDate2 . "' AND '" . $endDate2 . "'");
			
			echo "<li><i>There are <b>" . mysql_result($result,0,0) . "</b> events between these dates that have not yet been announced.</i>";
			
			$result = doQuery("SELECT * FROM " . HC_TblPrefix . "events where AlertSent = 0 AND StartDate >= NOW() AND StartDate Between '" . $startDate2 . "' AND '" . $endDate2 . "' ORDER BY StartDate");
			
			$sendOk = 0;
			if(hasRows($result)){
				$sendOk = 1;
				$firstDate = stampToDate(mysql_result($result,0,9), "m/d/Y");
				echo "<li><i>First unannounced event occurs on: " . $firstDate . "</i>";
			}//end if
		?></ul>
		<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td colspan="3">
					<table cellpadding="2" cellspacing="0" border="0">
						<tr>
							<td class="eventMain">
								<input type="hidden" name="theStartDate" id="theStartDate" value="<?echo $startDate;?>">
								<input type="text" name="startDate" id="startDate" value="<?echo $startDate;?>" class="input" size="10" disabled>
								</td>
							<td class="eventMain">&nbsp;and&nbsp;</td>
							<td class="eventMain">
								<input type="hidden" name="theEndDate" id="theEndDate" value="<?echo $endDate;?>">
								<input type="text" name="endDate" id="endDate" value="<?echo $endDate;?>" class="input" size="10" disabled></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr><td colspan="3"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
			<tr><td colspan="3" class="eventSeparator"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
			<tr><td colspan="3"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
			<tr>
				<td width="75" class="eventMain">Template:</td>
				<td colspan="2">
				<?php
					$result = doQuery("SELECT * FROM " . HC_TblPrefix . "newsletters WHERE IsActive = 1 ORDER BY TemplateName");
					
					if(hasRows($result)){
				?>
					<input type="hidden" name="templateID" id="templateID" value="<?echo $template;?>">
					<select name="template" id="template" class="input" disabled>
					<?
						while($row = mysql_fetch_row($result)){
						?>
							<option <?if($template == $row[0]){echo "SELECTED";}//end if?> value="<?echo cOut($row[0]);?>"><?echo cOut($row[1]);?></option>
						<?
						}//end while
					?>
					</select>
				<?	} else {	?>
					<a href="" class="main">Add a Template to Send Newsletter.</a>
				<?	}//end if	?>
				</td>
			</tr>
		<?php
			}//end if
		?>
		
		<?php
			if($step > 0){
				$query = "SELECT count(*) FROM " . HC_TblPrefix . "events where AlertSent = 0";
				$result = mysql_query($query) or die(mysql_error());
				
				//echo "<li><i>There are " . mysql_result($result,0,0) . " events that have not yet been announced.</i>";
				
				$query = "SELECT * FROM " . HC_TblPrefix . "events where AlertSent = 0 AND StartDate >= NOW() ORDER BY StartDate";
				$result = mysql_query($query) or die(mysql_error());
				
				if($sendOk > 0){
					$firstDate = stampToDate(mysql_result($result,0,9), "m/d/Y");
				}//end if
				
			}//end if
		?>
		<tr><td colspan="3"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
		<tr><td colspan="3" class="eventSeparator"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
		<tr><td colspan="3"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
		<tr>
			<td colspan="3">
				<?php
					if($step == 0){
						if(!isset($stop)){?>
							<input type="submit" name="submit" id="submit" value="   Submit Dates   " class="button">
					<?
						}//end if
					} else {
						if($sendOk > 0){
					?>
						
						<input type="submit" name="submit" id="submit" value="   Send Now   " class="button">&nbsp;&nbsp;
					<?
						}//end if
					?>
						<input type="button" name="redo" id="redo" value=" Start Over " class="button" onClick="javascript:document.location.href='<?echo CalAdminRoot . "/index.php?com=newsletter";?>'; return false;">
					<?
					}//end if
				?>
			</td>
		</tr>
	</table>
</form>