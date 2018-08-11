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
	}//end switch	?>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Dates.js"></script>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/DateSelect.js"></script>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Checkboxes.js"></script>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function togLocation(typ){
		if(typ == 1){
			document.eventSearch.loc.disabled = false;
			document.eventSearch.city.disabled = true;
		} else {
			document.eventSearch.loc.disabled = true;
			document.eventSearch.city.disabled = false;
		}//end if
	}//end togLocation()
	
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
	
	var calx = new CalendarPopup();
	var caly = new CalendarPopup();
	//-->
	</script>
	
	<br />
	Use the form below to select your event criteria then click 'Begin Search'.
	<form name="eventSearch" id="eventSearch" method="post" action="<?php echo HC_SearchAction;?>" onsubmit="return chkFrm();">
	<br>
	<fieldset>
		<legend>Search Dates</legend>
		<div class="frmReq">
			<label>Date Range:</label>
			<input name="startDate" id="startDate" type="text" size="12" maxlength="10" value="<?php echo date($hc_popDateFormat);?>" />
			&nbsp;<a href="javascript:;" onclick="caly.select(document.eventSearch.startDate,'anchor1','<?php echo $hc_popDateValid;?>'); return false;" name="anchor1" id="anchor1"><img src="<?php echo CalRoot;?>/images/icons/iconCalendar.gif" width="16" height="16" border="0" alt="" /></a>
			&nbsp;&nbsp;to&nbsp;&nbsp;
			<input name="endDate" id="endDate" type="text" size="12" maxlength="10" value="<?php echo date($hc_popDateFormat, mktime(0, 0, 0, date("m"), date("d") + 7, date("Y")));?>" />
			<a href="javascript:;" onclick="caly.select(document.eventSearch.endDate,'anchor2','<?php echo $hc_popDateValid;?>'); return false;" name="anchor2" id="anchor2"><img src="<?php echo CalRoot;?>/images/icons/iconCalendar.gif" width="16" height="16" border="0" alt="" /></a>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend>Search Keywords</legend>
		<div class="frmOpt">
			<label for="keyword">Keyword:</label>
			<input name="keyword" id="keyword" type="text" size="25" maxlength="50" value="" />
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend>Search Locations (<a href="javascript:;" onclick="togLocation(1);" class="eventMain">Click For Location</a> OR <a href="javascript:;" onclick="togLocation(2);" class="eventMain">Click For City</a>)</legend>
		<div class="frmOpt">
			<label for="location">Location:</label>
			<select name="loc" id="loc">
				<option value="">All Locations</option>
		<?php 	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "locations WHERE IsActive = 1 ORDER BY Name");
				while($row = mysql_fetch_row($result)){	?>
					<option value="<?php echo $row[0];?>"><?php echo $row[1];?></option>
		<?php 	}//end while	?>
			</select>
		</div>
		
		<div class="frmOpt">
			<label for="city">Event City:</label>
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
	</fieldset>
	<br />
	<fieldset>
		<legend>Search Categories</legend>
		<div class="frmOpt">
			<label>Categories:</label>
			<table cellpadding="0" cellspacing="0" border="0"><tr>
		<?php 	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "categories WHERE IsActive = 1 AND PkID > 0 ORDER BY CategoryName");
				$cnt = 0;
				
				while($row = mysql_fetch_row($result)){
					if(($cnt % 2 == 0) && ($cnt > 0) ){echo "</tr><tr>";}	?>
					<td><label for="catID_<?php echo $row[0];?>" class="category"><input name="catID[]" id="catID_<?php echo $row[0];?>" type="checkbox" value="<?php echo $row[0];?>" class="noBorderIE" /><?php echo cOut($row[1]);?></label></td>
			<?php 	$cnt = $cnt + 1;
				}//end while?>
			</tr></table>
	<?php 	if($cnt > 1){	?>
			<br />
			<label>&nbsp;</label>
			[ <a class="eventMain" href="javascript:;" onclick="checkAllArray('eventSearch', 'catID[]');">Select All Categories</a> 
			&nbsp;|&nbsp; <a class="eventMain" href="javascript:;" onclick="uncheckAllArray('eventSearch', 'catID[]');">Deselect All Categories</a> ]
	<?php 	}//end if	?>
		</div>
	</fieldset>
	<br />
	<input type="submit" name="submit" id="submit" value=" Begin Search " class="button" />
	</form>