<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
?>
<script language="JavaScript"/>
function chkFrm(){
	var dirty = 0;
	var warn = 'Plugin could not be generated for the following reasons:';
	
	if(document.frm.display.value == ''){
		dirty = 1;
		warn = warn + '\n*Max Display Value is Required';
	} else {
		if(isNaN(document.frm.display.value) == true){
			dirty = 1;
			warn = warn + '\n*Max Display Value Must Be Numeric';
		} else {
			if(document.frm.display.value < 1){
				dirty = 1;
				warn = warn + '\n*Max Display Value Must Be Greater Than 0';
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
				feedback(1,"Billboard Settings Updated Successfully!");
				break;
				
		}//end switch
	}//end if
	
	appInstructions(0, "Billboard", "Billboard Display Settings", "Use the form below to adjust the settings for the calendar billboard.");
?>
<br>
<form name="frm" id="frm" method="post" action="<?echo CalAdminRoot . "/" . HC_BillboardAction;?>" onSubmit="return chkFrm();">
<?php
	$result = doQuery("Select SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID IN (12,13,14,15) ORDER BY PkID");
	$maxShow = cOut(mysql_result($result,0,0));
	$fillMax = cOut(mysql_result($result,1,0));
	$format = cOut(mysql_result($result,2,0));
	$showTime = cOut(mysql_result($result,3,0));
?>
<table cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td class="eventMain" colspan="2">
			<b>Billboard Settings</b>
		</td>
	</tr>
	<tr><td colspan="3"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
	<tr><td colspan="3" class="eventSeparator"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
	<tr><td colspan="3"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
	<tr>
		<td class="eventMain" width="105">Max Display:</td>
		<td>
			<input type="text" name="display" id="display" value="<?echo $maxShow;?>" class="input" size="2" maxlength="2">
		</td>
	</tr>
	<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
	<tr>
		<td class="eventMain">Auto Fill to Max:</td>
		<td>
			<select name="fill" id="fill" class="input">
				<option <?if($fillMax == 1){echo "SELECTED";}//end if?> value="1">Yes</option>
				<option <?if($fillMax == 0){echo "SELECTED";}//end if?> value="0">No</option>
			</select>
		</td>
	</tr>
	<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
	<tr>
		<td class="eventMain">Date Layout:</td>
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
						<label for="showtime">Show Event Start Time</label>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td colspan="3"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
	<tr><td colspan="3" class="eventSeparator"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
	<tr><td colspan="3"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<input type="submit" name="submit" id="submit" value=" Save Settings " class="button">
		</td>
	</tr>
</table>
</form>