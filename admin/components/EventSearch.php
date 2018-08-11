<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2007 Refresh Web Development [http://www.refreshwebdev.com]
	
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
	
	$hourOffset = date("G");
	if($hc_timezoneOffset > 0){
		$hourOffset = $hourOffset + abs($hc_timezoneOffset);
	} else {
		$hourOffset = $hourOffset - abs($hc_timezoneOffset);
	}//end if	?>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Checkboxes.js"></script>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Dates.js"></script>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/DateSelect.js"></script>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function toggleMe(show,hide1,hide2,hide3){
		document.getElementById(show).style.display = 'block';
		document.frmEventSearch.elements[show].disabled = false;
		document.getElementById(hide1).style.display = 'none';
		document.frmEventSearch.elements[hide1].disabled = true;
		document.getElementById(hide2).style.display = 'none';
		document.frmEventSearch.elements[hide2].disabled = true;
		document.getElementById(hide3).style.display = 'none';
		document.frmEventSearch.elements[hide3].disabled = true;
		return false;
	}//end toggleMe()
	
	function chkFrm(){
	dirty = 0;
	warn = "Your search could not be completed because of the following reasons:";
	startDate = document.frmEventSearch.startDate.value;
	endDate = document.frmEventSearch.endDate.value;
		
		if(!isDate(document.frmEventSearch.startDate.value, '<?php echo $hc_popDateValid;?>')){
			dirty = 1;
			warn = warn + '\n*Start Date Format is Invalid Date or Format. Required Format: <?php echo strtolower($hc_popDateValid);?>';
		} else if(document.frmEventSearch.startDate.value == ''){
			dirty = 1;
			warn = warn + "\n*Start Date is Required";
		}//end if 
		
		if(!isDate(document.frmEventSearch.endDate.value, '<?php echo $hc_popDateValid;?>')){
			dirty = 1;
			warn = warn + '\n*End Date Format is Invalid Date or Format. Required Format: <?php echo strtolower($hc_popDateValid);?>';
		} else if(document.frmEventSearch.endDate.value == ''){
			dirty = 1;
			warn = warn + "\n*End Date is Required";
		}//end if
		
		if(compareDates(startDate, '<?php echo $hc_popDateValid;?>', endDate, '<?php echo $hc_popDateValid;?>') == 1){
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
	
	var calx = new CalendarPopup("dsCal");
	document.write(calx.getStyles());
	//-->
	</script>
<?php
	if (isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1, "Event(s) Deleted Successfully!");
				break;
				
			case "2" :
				feedback(1, "Events Updated Successfully!");
				break;
				
			case "3" :
				feedback(1, "Events Series Created Successfully!");
				break;
				
			case "4" :
				feedback(1, "Events Updated &amp; Submitted to Eventful Successfully!");
				break;
			
			case "5" :
				feedback(2, "Event(s) Deleted Successfully. Eventful Remove Failed: Connection Failed");
				break;
				
			case "6" :
				feedback(1, "Event(s) Deleted and Removed From Eventful Successfully!");
				break;
				
		}//end switch
	}//end if
	
	switch($sID){
		case 1:
			appInstructions(0, "Editing_Events", "Edit Event Search", "Please use the form below to search for the event(s) you wish to edit.");
			break;
		case 2:
			appInstructions(0, "Deleting_Events", "Delete Event Search", "Please use the form below to search for the event(s) you wish to delete.");
			break;
		case 3:
			appInstructions(0, "Event_Series", "Create Series Search", "Please use the form below to search for the events you wish to include in your series.");
			break;
		default:
			appInstructions(0, "Reports", "Event Report Search", "Please use the form below to search the events for which you wish to generate a report.");
			break;
	}//end switch	?>
	<br />
	<form name="frmEventSearch" id="frmEventSearch" method="post" action="<?php echo CalAdminRoot . "/index.php?com=searchresults";?>" onsubmit="return chkFrm();">
	<input type="hidden" name="sID" id="sID" value="<?php echo $sID;?>" />
	<fieldset>
		<legend>Date Range</legend>
		<div class="frmReq">
			<label>Date Range:</label>
			<input size="12" maxlength="10" type="text" name="startDate" id="startDate" value="<?php echo date($hc_popDateFormat,mktime($hourOffset,date("i"),date("s"),date("m"),date("d"),date("Y")));?>" />&nbsp;<a href="javascript:;" onclick="calx.select(document.frmEventSearch.startDate,'anchor1','<?php echo $hc_popDateValid;?>'); return false;" name="anchor1" id="anchor1"><img src="<?php echo CalAdminRoot;?>/images/icons/iconCalendar.gif" width="16" height="16" border="0" alt="" /></a>&nbsp;
			&nbsp;to&nbsp;&nbsp;
			<input size="12" maxlength="10" type="text" name="endDate" id="endDate" value="<?php echo date($hc_popDateFormat, mktime(0, 0, 0, date("m"), date("d") + 7, date("Y")));?>" />&nbsp;<a href="javascript:;" onclick="calx.select(document.frmEventSearch.endDate,'anchor2','<?php echo $hc_popDateValid;?>'); return false;" name="anchor2" id="anchor2"><img src="<?php echo CalAdminRoot;?>/images/icons/iconCalendar.gif" width="16" height="16" border="0" alt="" /></a>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend>Keywords</legend>
		<div class="frmOpt">
			<label for="keyword">Keywords:</label>
			<input size="25" maxlength="50" type="text" name="keyword" id="keyword" value="" />
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend><a href="javascript:;" onclick="toggleMe('location','city','locState','postal');" class="main">Location</a> | <a href="javascript:;" onclick="toggleMe('city','location','locState','postal');" class="main">City</a> | <a href="javascript:;" onclick="toggleMe('locState','city','postal','location')" class="main"><?php echo HC_StateLabel;?></a> | <a href="javascript:;" onclick="toggleMe('postal','city','locState','location')" class="main">Postal Code</a></legend>
		<div id="location">
			<div class="frmOpt">
				<label for="location">Location:</label>
				<select name="location" id="location">
					<option value="">All Locations</option>
			<?php 	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "locations WHERE IsActive = 1 ORDER BY Name");
					while($row = mysql_fetch_row($result)){	?>
						<option value="<?php echo $row[0];?>"><?php echo $row[1];?></option>
			<?php 	}//end while	?>
				</select>
			</div>
		</div>
		<div id="city" style="display:none;">
			<div class="frmOpt">
				<label for="city">City:</label>
				<select name="city" id="city" disabled="disabled">
					<option value="">All Cities</option>
			<?php 	$cities = getCities($hc_browsePast); 
					foreach($cities as $val){
						if($val != ''){?>
						<option value="<?php echo $val;?>"><?php echo $val;?></option>
			<?php 		}//end if
					}//end foreach	?>
				</select>
			</div>
		</div>
		<div id="locState" style="display:none;">
			<div class="frmOpt">
				<label for="locState"><?php echo HC_StateLabel;?>:</label>
				<?php
					$state = $hc_defaultState;
					$stateDisabled = 1;
					include('../events/includes/' . HC_StateInclude);	?>
			</div>
		</div>
		<div id="postal" style="display:none;">
			<div class="frmOpt">
				<label for="postal">Postal Code:</label>
				<select name="postal" id="postal" disabled="disabled">
					<option value="">All Postal Codes</option>
			<?php 	$codes = getPostal($hc_browsePast); 
					foreach($codes as $val){
						if($val != ''){?>
						<option value="<?php echo $val;?>"><?php echo $val;?></option>
			<?php 		}//end if
					}//end foreach	?>
				</select>
			</div>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend>Categories</legend>
		<div class="frmOpt">
			<label>Categories:</label>
		<?php getCategories('frmEventSearch', 2);?>
		</div>
	</fieldset>
	<br />
	<input type="submit" name="submit" id="submit" value=" Begin Search " class="button" />
	</form>
	<div id="dsCal" class="datePicker"></div>