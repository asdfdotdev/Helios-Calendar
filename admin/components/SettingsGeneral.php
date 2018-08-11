<script language="JavaScript"/>
function chkFrm(){
	var dirty = 0;
	var warn = 'Settings could not be saved for the following reasons:';
	
	if(document.frm.maxRSS.value == ''){
		dirty = 1;
		warn = warn + '\n*RSS List Size Value is Required';
	} else {
		if(isNaN(document.frm.maxRSS.value) == true){
			dirty = 1;
			warn = warn + '\n*RSS List Size Value Must Be Numeric';
		} else if(document.frm.maxRSS.value < 1) {
			dirty = 1;
			warn = warn + '\n*RSS List Size Value Must Be Greater Than 0';
		}//end if
	}//end if
	
	if(document.frm.mostPopular.value == ''){
		dirty = 1;
		warn = warn + '\n*Most Popular List Size Value is Required';
	} else {
		if(isNaN(document.frm.mostPopular.value) == true){
			dirty = 1;
			warn = warn + '\n*Most Popular List Size Value Must Be Numeric';
		} else if(document.frm.mostPopular.value < 1) {
			dirty = 1;
			warn = warn + '\n*Most Popular List Size Value Must Be Greater Than 0';
		}//end if
	}//end if
	
	if(document.frm.display.value == ''){
		dirty = 1;
		warn = warn + '\n*Billboard List Size Value is Required';
	} else {
		if(isNaN(document.frm.display.value) == true){
			dirty = 1;
			warn = warn + '\n*Billboard List Size Value Must Be Numeric';
		} else {
			if(document.frm.display.value < 1){
				dirty = 1;
				warn = warn + '\n*Billboard List Size Value Must Be Greater Than 0';
			}//end if
		}//end if
	}//end if
	
	if(dirty > 0){
		alert(warn + '\nPlease make the required changes and try again.');
		return false;
	}//end if
	
}//end if
</script>
<?php 
	if (isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1,"Settings Updated Successfully!");
				break;
				
		}//end switch
	}//end if
	
	appInstructions(0, "Main_Page#Settings", "Your Helios Settings", "Use the form below to configure your Helios Calendar settings.");
	
	$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID IN (1,2,8,9,10,11,12,13,14,15,21) ORDER BY PkID");
	$submit = cOut(mysql_result($result,0,0));
	$maxDisplay = cOut(mysql_result($result,1,0));
	$driving = cOut(mysql_result($result,2,0));
	$weather = cOut(mysql_result($result,3,0));
	$mostPopular = cOut(mysql_result($result,4,0));
	$browsePast = cOut(mysql_result($result,5,0));
	$maxShow = cOut(mysql_result($result,6,0));
	$fillMax = cOut(mysql_result($result,7,0));
	$format = cOut(mysql_result($result,8,0));
	$showTime = cOut(mysql_result($result,9,0));
	$state = cOut(mysql_result($result,10,0));
?>
<br>
<form name="frm" id="frm" method="post" action="<?echo CalAdminRoot . "/" . HC_GeneralSetAction;?>" onSubmit="return chkFrm();">
<table cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td class="eventMain" colspan="2"><b>Configure Your Helios Settings</b></td>
	</tr>
	<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
	<tr><td colspan="2" class="eventSeparator"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
	<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
	<tr>
		<td class="eventMain" width="170">Public Event Submission:</td>
		<td><select name="allowsubmit" id="allowsubmit" class="input">
				<option <?if($submit == 1){echo "SELECTED";}//end if?> value="1">ON</option>
				<option <?if($submit == 0){echo "SELECTED";}//end if?> value="0">OFF</option>
			</select>
		</td>
	</tr>
	<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
	<tr>
		<td class="eventMain">RSS Max List Size:</td>
		<td>
			<input type="text" size="2" maxlength="2" name="maxRSS" id="maxRSS" value="<?echo $maxDisplay;?>" class="input">
		</td>
	</tr>
	<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
	<tr>
		<td class="eventMain">Most Popular Max List Size:</td>
		<td>
			<input type="text" size="2" maxlength="2" name="mostPopular" id="mostPopular" value="<?echo $mostPopular;?>" class="input">
		</td>
	</tr>
	<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
	<tr>
		<td class="eventMain" width="105">Billboard Max List Size:</td>
		<td>
			<input type="text" name="display" id="display" value="<?echo $maxShow;?>" class="input" size="2" maxlength="2">
		</td>
	</tr>
	<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
	<tr>
		<td class="eventMain">Auto Fill Billboard:</td>
		<td>
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td>
						<select name="fill" id="fill" class="input">
							<option <?if($fillMax == 1){echo "SELECTED";}//end if?> value="1">On</option>
							<option <?if($fillMax == 0){echo "SELECTED";}//end if?> value="0">Off</option>
						</select>
					</td>
					<td width="20" align="right"><?appInstructionsIcon("Auto Fill Billboard", "When <b>On</b> Helios will append a list of upcoming events to the bottom of your Billboard if there are fewer Billboard events then the \'Billboard Max List Size\' set above.");?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
	<tr>
		<td class="eventMain">Billboard Date Format:</td>
		<td>
			<select name="dateformat" id="dateformat" class="input">
				<option <?if($format == "F d, Y"){echo "SELECTED";}//end if?> value="F dS, Y"><?echo date("F d, Y");?></option>
				<option <?if($format == "d M y"){echo "SELECTED";}//end if?> value="d M y"><?echo date("d M y");?></option>
				<option <?if($format == "d M Y"){echo "SELECTED";}//end if?> value="d M Y"><?echo date("d M Y");?></option>
				<option <?if($format == "d F y"){echo "SELECTED";}//end if?> value="d F y"><?echo date("d F y");?></option>
				<option <?if($format == "d F Y"){echo "SELECTED";}//end if?> value="d F Y"><?echo date("d F Y");?></option>
				<option <?if($format == "m-d-y"){echo "SELECTED";}//end if?> value="m-d-y"><?echo date("m-d-y");?></option>
				<option <?if($format == "m-d-Y"){echo "SELECTED";}//end if?> value="m-d-Y"><?echo date("m-d-Y");?></option>
				<option <?if($format == "d-m-y"){echo "SELECTED";}//end if?> value="d-m-y"><?echo date("d-m-y");?></option>
				<option <?if($format == "d-m-Y"){echo "SELECTED";}//end if?> value="d-m-Y"><?echo date("d-m-Y");?></option>
				<option <?if($format == "Y-m-d"){echo "SELECTED";}//end if?> value="Y-m-d"><?echo date("Y-m-d");?></option>
				<option <?if($format == "y-m-d"){echo "SELECTED";}//end if?> value="y-m-d"><?echo date("y-m-d");?></option>
			</select>
		</td>
	</tr>
	<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<table cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td><input <?if($showTime == 1){echo "CHECKED";}//end if?> type="checkbox" name="showtime" id="showtime" value=""></td>
					<td class="eventMain">
						<label for="showtime">Show Event Start Time (Billboard &amp; Most Popular)</label>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
	<tr>
		<td class="eventMain">Driving Directions:</td>
		<td>
			<select name="driving" id="driving" class="input">
				<option <?if($driving == 0){echo "SELECTED";}//end if?> value="0">Google</option>
				<option <?if($driving == 1){echo "SELECTED";}//end if?> value="1">Mapquest</option>
				<option <?if($driving == 2){echo "SELECTED";}//end if?> value="2">Yahoo</option>
			</select>
		</td>
	</tr>
	<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
	<tr>
		<td class="eventMain">Weather Links:</td>
		<td>
			<select name="weather" id="weather" class="input">
				<option <?if($weather == 0){echo "SELECTED";}//end if?> value="0">Weather Channel</option>
				<option <?if($weather == 1){echo "SELECTED";}//end if?> value="1">AccuWeather</option>
				<option <?if($weather == 4){echo "SELECTED";}//end if?> value="4">AccuWeather (Canada)</option>
				<option <?if($weather == 2){echo "SELECTED";}//end if?> value="2">Weather Underground</option>
				<option <?if($weather == 3){echo "SELECTED";}//end if?> value="3">Yahoo Weather</option>
			</select>
		</td>
	</tr>
	<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
	<tr>
		<td class="eventMain">Event Browse:</td>
		<td>
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td>
						<select name="browsePast" id="browsePast" class="input">
							<option <?if($browsePast == 0){echo "SELECTED";}//end if?> value="0">Current Only</option>
							<option <?if($browsePast == 1){echo "SELECTED";}//end if?> value="1">All Dates</option>
						</select>
					</td>
					<td width="20" align="right"><?appInstructionsIcon("Event Browse Setting", "<b>Current Only:</b> Allow users to browse events that occur today or in the future.<br><br><b>All Dates:</b> Allow users to browse back across all past months and view individual past days events by clicking the day in the control panel.");?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
	<tr>
		<td class="eventMain">Default State:</td>
		<td>
			<?include('../events/includes/selectStates.php');?>
		</td>
	</tr>
	<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
	<tr><td colspan="2" class="eventSeparator"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
	<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<input type="submit" name="submit" id="submit" value=" Save Settings " class="button">
		</td>
	</tr>
</table>