<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2007 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	switch($hc_popDateFormat){
		case 'm/d/Y':
			$hc_popDateValid = "MM/dd/yyyy";
			break;
			
		case 'd/m/Y':
			$hc_popDateValid = "dd/MM/yyyy";
			break;
			
		case 'Y/m/d':
			$hc_popDateValid = "yyyy/MM/dd";
			break;
	}//end switch	
	
	$hourOffset = date("G");
	if($hc_timezoneOffset > 0){
		$hourOffset = $hourOffset + abs($hc_timezoneOffset);
	} else {
		$hourOffset = $hourOffset - abs($hc_timezoneOffset);
	}//end if	?>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Dates.js"></script>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/DateSelect.js"></script>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Checkboxes.js"></script>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function toggleMe(show,hide1,hide2,hide3){
		document.getElementById(show).style.display = 'block';
		document.eventSearch.elements[show].disabled = false;
		document.getElementById(hide1).style.display = 'none';
		document.eventSearch.elements[hide1].disabled = true;
		document.getElementById(hide2).style.display = 'none';
		document.eventSearch.elements[hide2].disabled = true;
		document.getElementById(hide3).style.display = 'none';
		document.eventSearch.elements[hide3].disabled = true;
		return false;
	}//end toggleMe()
	
	function chkFrm() {
	dirty = 0;
	warn = "Your search could not be completed because of the following reasons:\n";
	startDate = document.eventSearch.startDate.value;
	endDate = document.eventSearch.endDate.value;
		if(!isDate(document.eventSearch.startDate.value, '<?php echo $hc_popDateValid;?>')){
			dirty = 1;
			warn = warn + '\n*Start Date is Invalid Date or Format. Required Format: <?php echo strtolower($hc_popDateValid);?>';
		}//end if 

		if(!isDate(document.eventSearch.endDate.value, '<?php echo $hc_popDateValid;?>')){
			dirty = 1;
			warn = warn + '\n*End Date is Invalid Date or Format. Required Format: <?php echo strtolower($hc_popDateValid);?>';
		}//end if 
		
		if(document.eventSearch.startDate.value == ''){
			dirty = 1;
			warn = warn + "\n*Start Date is Required";
		}//end if
		
		if(document.eventSearch.endDate.value == ''){
			dirty = 1;
			warn = warn + "\n*End Date is Required";
		}//end if
		
		if(compareDates(startDate, '<?php echo $hc_popDateValid;?>', endDate, '<?php echo $hc_popDateValid;?>') == 1){
			dirty = 1;
			warn = warn + "\n*Start Date Cannot Occur After End Date";
		}//end if
		
<?php 	if($hc_browsePast == 0){	?>
			if(compareDates(document.eventSearch.startDate.value, '<?php echo $hc_popDateValid;?>', '<?php echo date($hc_popDateFormat, mktime(0, 0, 0, date("m"), date("d") - 1, date("Y")) );?>', 'MM/d/yyyy') == 0){
				dirty = 1;
				warn = warn + "\n*Cannot Search Past Events";
			}//end if
<?php 	}//end if	?>
		
		if(validateCheckArray('eventSearch','catID[]',1,'Category') > 0){
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
	<br />
	Use the form below to select your event criteria then click 'Begin Search'.
	<form name="eventSearch" id="eventSearch" method="post" action="<?php echo HC_SearchAction;?>" onsubmit="return chkFrm();">
	<br />
	<fieldset>
		<legend>Date Range</legend>
		<div class="frmReq">
			<label>Date Range:</label>
			<input name="startDate" id="startDate" type="text" size="12" maxlength="10" value="<?php echo date($hc_popDateFormat,mktime($hourOffset,date("i"),date("s"),date("m"),date("d"),date("Y")));?>" />&nbsp;<a href="javascript:;" onclick="calx.select(document.eventSearch.startDate,'anchor1','<?php echo $hc_popDateValid;?>'); return false;" name="anchor1" id="anchor1"><img src="<?php echo CalRoot;?>/images/icons/iconCalendar.gif" width="16" height="16" border="0" alt="" /></a>
			&nbsp;&nbsp;to&nbsp;&nbsp;
			<input name="endDate" id="endDate" type="text" size="12" maxlength="10" value="<?php echo date($hc_popDateFormat, mktime(0, 0, 0, date("m"), date("d") + 7, date("Y")));?>" />&nbsp;<a href="javascript:;" onclick="calx.select(document.eventSearch.endDate,'anchor2','<?php echo $hc_popDateValid;?>'); return false;" name="anchor2" id="anchor2"><img src="<?php echo CalRoot;?>/images/icons/iconCalendar.gif" width="16" height="16" border="0" alt="" /></a>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend>Keywords</legend>
		<div class="frmOpt">
			<label for="keyword">Keywords:</label>
			<input name="keyword" id="keyword" type="text" size="25" maxlength="50" value="" />
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend><a href="javascript:;" onclick="toggleMe('location','city','locState','postal');" class="searchLoc">Location</a> | <a href="javascript:;" onclick="toggleMe('city','location','locState','postal');" class="searchLoc">City</a> | <a href="javascript:;" onclick="toggleMe('locState','city','postal','location')" class="searchLoc"><?php echo HC_StateLabel;?></a> | <a href="javascript:;" onclick="toggleMe('postal','city','locState','location')" class="searchLoc">Postal Code</a></legend>
		<div id="location">
			<div class="frmOpt">
				<label for="location">Location:</label>
				<select name="location" id="location">
					<option value="">All Locations</option>
			<?php 	$result = doQuery("	SELECT distinct(l.PkID), l.Name
										FROM " . HC_TblPrefix . "locations l
											LEFT JOIN " . HC_TblPrefix . "events e ON (l.PkID = e.LocID)
										WHERE l.IsActive = 1 AND
											e.IsActive = 1 AND
											e.IsApproved = 1 AND
											e.StartDate >= '" . date("Y-m-d") . "'
										ORDER BY l.Name");
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
		<?php getCategories('eventSearch', 2);?>
		</div>
	</fieldset>
	<br />
	<input type="submit" name="submit" id="submit" value=" Begin Search " class="button" />
	</form>
	<div id="dsCal" class="datePicker"></div>