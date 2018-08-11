<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2007 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	if (isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1, "Event Data Imported Successfully!");
				break;
				
			case "2" :
				feedback(2, "Event Import Failed. Your CSV data could not be parsed.<br />Verify Settings and try again.");
				break;
				
		}//end switch
	}//end if
	
	appInstructions(0, "Event_Import", "Event Data Import", "Use the form below to import event data to your calendar in CSV format. Paste the contents of your CSV file into the text area below and click 'Import Events'.<br /><br />A template file, including a header row with column titles, is available at the link below, use it at your template to import events.<br /><br /><b>Note:</b> If you are importing large numbers of events, it is recomended<br />you break them into smaller groups of <b>500 or fewer</b> to prevent server timeouts.<br /><br />Also it is helpful to upload events by category group type as all events uploaded will be assigned to the selected categories.<br /><br /><a href=\"javascript:;\" onclick=\"window.location.href='" . CalAdminRoot . "/components/ToolImportAction.php?samp=1';\" class=\"eventMain\">Click here to download the template file.</a>");?>
	<br />
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Checkboxes.js"></script>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function chkFrm(){
	dirty = 0;
	warn = "Event could not be added for the following reason(s):";

		if(document.frmEventImport.enclChar.value == ''){
			dirty = 1;
			warn = warn + '\n*Field Enclosed Character is Required';
		}//end if
		
		if(document.frmEventImport.termChar.value == ''){
			dirty = 1;
			warn = warn + '\n*Field Terminated Character is Required';
		}//end if
		
		if(document.frmEventImport.eventIn.value == ''){
			dirty = 1;
			warn = warn + '\n*Event Data is Required';
		}//end if
		
		if(validateCheckArray('frmEventImport','catID[]',1) > 0){
			dirty = 1;
			warn = warn + '\n*Category Assignment is Required';
		}//end if
		
		if(dirty > 0){
			alert(warn + '\n\nPlease complete the form and try again.');
			return false;
		} else {
			return true;
		}//end if
	}//end chkFrm()
	//-->
	</script>
	<form name="frmEventImport" id="frmEventImport" method="post" action="<?php echo CalAdminRoot . "/components/ToolImportAction.php";?>" onsubmit="return chkFrm();">
	<fieldset>
		<legend>Data Fields</legend>
		<div class="frmOpt">
			<label>Enclosed By:</label>
			<select name="enclChar" id="enclChar">
				<option value="&quot;">"</option>
				<option value="'">'</option>
			</select>
		</div>
		<div class="frmOpt">
			<label>Terminated&nbsp;By:</label>
			<select name="termChar" id="termChar">
				<option value=",">,</option>
				<option value="tab">tab</option>
			</select>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend>Event Data</legend>
		<div class="frmOpt">
			<label>Event Data:<br />(CSV)</label>
			<textarea name="eventIn" id="eventIn" style="width:75%; height:200px;"></textarea>
		</div>
		<div class="frmOpt">
			<label>Categories:</label>
	<?php	getCategories('frmEventAdd', 2);?>
		</div>
	</fieldset>
	<br />
	<input name="submit" id="submit" type="submit" value="   Import Event Data   " class="button" />
	</form>