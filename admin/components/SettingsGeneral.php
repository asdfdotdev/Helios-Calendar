<script language="JavaScript"/>
function chkFrm(){
	var dirty = 0;
	var warn = 'RSS settings could not be saved for the following reasons:';
	
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
	
	appInstructions(0, "General Settings", "Use the form below to configure your Helios Calendar settings.");
	
	$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID IN (1,2,8,9,10,11,21) ORDER BY PkID");
	$submit = mysql_result($result,0,0);
	$maxDisplay = mysql_result($result,1,0);
	$driving = mysql_result($result,2,0);
	$weather = mysql_result($result,3,0);
	$mostPopular = mysql_result($result,4,0);
	$browsePast = mysql_result($result,5,0);
	$state = mysql_result($result,6,0);
?>
<br>
<form name="frm" id="frm" method="post" action="<?echo CalAdminRoot . "/" . HC_GeneralSetAction;?>" onSubmit="return chkFrm();">
<table cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td class="eventMain" colspan="2"><b>Helios General Settings</b></td>
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