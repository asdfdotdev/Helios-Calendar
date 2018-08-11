<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2006 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	if(isset($_GET['sID']) && is_numeric($_GET['sID'])){
		$sID = $_GET['sID'];
	} else {
		$sID = 0;
	}//end if
?>
	<script language="JavaScript" type="text/JavaScript" src="<?echo CalRoot;?>/includes/java/Checkboxes.js"></script>
	<script language="JavaScript" type="text/JavaScript" src="<?echo CalRoot;?>/includes/java/Dates.js"></script>
	<script language="JavaScript" type="text/JavaScript" src="<?echo CalRoot;?>/includes/java/DateSelect.js"></script>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function chkFrm(){
	dirty = 0;
	warn = "Your search could not be completed because of the following reasons:";
	startDate = document.frmEventSearch.startDate.value;
	endDate = document.frmEventSearch.endDate.value;
		
		if(!isDate(document.frmEventSearch.startDate.value, '<?echo $hc_popDateValid;?>')){
			dirty = 1;
			warn = warn + '\n*Start Date Format is Invalid Date or Format. Required Format: <?echo strtolower($hc_popDateFormat);?>';
		} else if(document.frmEventSearch.startDate.value == ''){
			dirty = 1;
			warn = warn + "\n*Start Date is Required";
		}//end if 
		
		if(!isDate(document.frmEventSearch.endDate.value, '<?echo $hc_popDateValid;?>')){
			dirty = 1;
			warn = warn + '\n*End Date Format is Invalid Date or Format. Required Format: <?echo strtolower($hc_popDateFormat);?>';
		} else if(document.frmEventSearch.endDate.value == ''){
			dirty = 1;
			warn = warn + "\n*End Date is Required";
		}//end if
		
		if(compareDates(startDate, '<?echo $hc_popDateValid;?>', endDate, '<?echo $hc_popDateValid;?>') == 1){
			dirty = 1;
			warn = warn + "\n*Start Date Cannot Occur After End Date";
		}//end if
	
		if(validateCheckArray('frmEventSearch','catID[]',1,'Category') > 0){
			dirty = 1;
			warn = warn + '\n*Category Selection is Required';
		}//end if
		
		if(dirty > 0){
			alert(warn + '\n\nPlease make the required changes and submit your search again.');
			return false;
		} else {
			return true;
		}//end if
	}//end chkFrm
	
	var calx = new CalendarPopup();
	//-->
	</script>
<?	if (isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1, "Event(s) Deleted Successfully!");
				break;
				
			case "2" :
				feedback(1, "Events Updated Successfully!");
				break;
				
			case "3" :
				feedback(1, "Events Series Created Successfully!");
		}//end switch
	}//end if
	
	switch($sID){
		case 1:
			appInstructions(0, "Editing_Events", "Edit Event Search", "Please use the form below to search for the event(s) you wish to edit.");
			break;
		case 2:
			appInstructions(0, "Delete_Event", "Delete Event Search", "Please use the form below to search for the event(s) you wish to delete.");
			break;
		case 3:
			appInstructions(0, "Create_Series", "Create Series Search", "Please use the form below to search for the events you wish to include in your series.");
			break;
		default:
			appInstructions(0, "Event_Activity_Report", "Event Report Search", "Please use the form below to search the events for which you wish to generate a report.");
			break;
	}//end switch	?>
	<br />
	<form name="frmEventSearch" id="frmEventSearch" method="post" action="<?echo CalAdminRoot . "/index.php?com=searchresults";?>" onsubmit="return chkFrm();">
	<input type="hidden" name="sID" id="sID" value="<?echo $sID;?>" />
	<fieldset>
		<legend>Events Occurring From</legend>
		<div class="frmReq">
			<input size="12" maxlength="10" type="text" name="startDate" id="startDate" value="<?echo date($hc_popDateFormat);?>" />
			&nbsp;<a href="javascript:;" onclick="calx.select(document.frmEventSearch.startDate,'anchor1','<?echo $hc_popDateValid;?>'); return false;" name="anchor1" id="anchor1"><img src="<?echo CalAdminRoot;?>/images/icons/iconCalendar.gif" width="16" height="16" border="0" alt="" align="middle" /></a>&nbsp;
			&nbsp;to&nbsp;&nbsp;
			<input size="12" maxlength="10" type="text" name="endDate" id="endDate" value="<?echo date($hc_popDateFormat, mktime(0, 0, 0, date("m"), date("d") + 7, date("Y")));?>" />
			&nbsp;<a href="javascript:;" onclick="calx.select(document.frmEventSearch.endDate,'anchor2','<?echo $hc_popDateValid;?>'); return false;" name="anchor2" id="anchor2"><img src="<?echo CalAdminRoot;?>/images/icons/iconCalendar.gif" width="16" height="16" border="0" alt="" align="middle" /></a>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend>Having the Keyword / Phrase (<i>optional</i>)</legend>
		<div class="frmOpt">
			<input size="25" maxlength="50" type="text" name="keyword" id="keyword" value="" />
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend>Happening in the City of</legend>
		<div class="frmOpt">
			<select name="city" id="city" class="eventInput">
				<option value="">All Cities</option>
			<?	$result = doQuery("	SELECT DISTINCT LocationCity
									FROM " . HC_TblPrefix . "events
									WHERE IsActive = 1 AND IsApproved = 1 AND StartDate >= NOW() AND LocationCity != ''
									ORDER BY LocationCity");
				while($row = mysql_fetch_row($result)){?>
					<option value="<?echo $row[0];?>"><?echo $row[0];?></option>
			<?	}//end while
			?>
			</select>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend>In the Following Categories</legend>
		<div class="frmReg">
			<?getCategories('frmEventSearch', 3);?>
		</div>
	</fieldset>
	<br />
	<input type="submit" name="submit" id="submit" value=" Begin Search " class="button" />
	</form>