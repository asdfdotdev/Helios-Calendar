<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2007 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	$lID = 0;
	$whereAmI = "Add";
	$name = "";
	$address = "";
	$address2 = "";
	$city = "";
	$state = "";
	$country = "";
	$zip = "";
	$website = "http://";
	$phone = "";
	$email = "";
	$descript = "";
	$public = 0;
	$status = 0;
	$lat = "";
	$lon = "";
	$helpDoc = "Adding_Locations";
	$helpText = "Use the form below to add a location to your calendar.";
	
	if(isset($_GET['lID']) && is_numeric($_GET['lID'])){
		$lID = $_GET['lID'];
	}//end if
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "locations WHERE PkID = " . $lID);
	
	if(hasRows($result)){
		$whereAmI = "Edit";
		$name = mysql_result($result,0,1);
		$address = mysql_result($result,0,2);
		$address2 = mysql_result($result,0,3);
		$city = mysql_result($result,0,4);
		$state = mysql_result($result,0,5);
		$country = mysql_result($result,0,6);
		$zip = mysql_result($result,0,7);
		$website = mysql_result($result,0,8);
		$phone = mysql_result($result,0,9);
		$email = mysql_result($result,0,10);
		$descript = mysql_result($result,0,11);
		$status = mysql_result($result,0,12);
		$lat = mysql_result($result,0,15);
		$lon = mysql_result($result,0,16);
		$helpDoc = "Editing_Locations";
		$helpText = "Use the form below to edit the location.";
	}//end if
	
	appInstructions(0, $helpDoc, $whereAmI . " Location", $helpText);	?>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function toggleMe(who){
		who.style.display == 'none' ? who.style.display = 'block':who.style.display = 'none';
		return false;
	}//end toggleMe()
	
	function chkFrm(){
	dirty = 0;
	warn = "Location could not be saved for the following reason(s):";
		
		if(document.frmLocEdit.lName.value == ''){
			dirty = 1;
			warn = warn + '\n*Location Name is Required';
		}//end if
		
		if(document.frmLocEdit.updateMap.checked){
			if(document.frmLocEdit.address.value == ''){
				dirty = 1;
				warn = warn + '\n*Address is Required to Update Map Data';
			}//end if
			if(document.frmLocEdit.city.value == ''){
				dirty = 1;
				warn = warn + '\n*City is Required to Update Map Data';
			}//end if
			if(document.frmLocEdit.zip.value == ''){
				dirty = 1;
				warn = warn + '\n*Postal Code is Required to Update Map Data';
			}//end if
		}//end if
		
		if(isNaN(document.frmLocEdit.lat.value)){
			dirty = 1;
			warn = warn + '\n*Geocode Latitude Must Be Numeric';
		}//end if
		
		if(isNaN(document.frmLocEdit.lon.value)){
			dirty = 1;
			warn = warn + '\n*Geocode Longitude Must Be Numeric';
		}//end if
		
		if(dirty > 0){
			alert(warn + '\n\nPlease make the required changes and try again.');
			return false;
		} else {
			if(document.frmLocEdit.updateMap.checked){
				if(!confirm('This will update Latitude and Longitude data with a new download from Yahoo.\nAre you sure you want to download new map data for this Location?\n\n          Ok = YES, Save Location and Download New Data\n          Cancel = NO, Stop Save and DO NOT Download New Data')){
					return false;
				}//end if
				return true;
			}//end if
		}//end if
	}//end chkFrm()
	//-->
	</script>
	<br />
	<form name="frmLocEdit" id="frmLocEdit" method="post" action="<?php echo CalAdminRoot . "/components/ToolLocationEditAction.php";?>" onsubmit="return chkFrm();">
	<input name="lID" id="lID" type="hidden" value="<?php echo $lID;?>" />
	<fieldset>
		<legend>Location Details</legend>
		<div class="frmReq">
			<label for="lName">Name:</label>
			<input name="lName" id="lName" value="<?php echo $name;?>" type="text" maxlength="50" size="40" /><span style="color: #DC143C">*</span>
		</div>
		<div class="frmOpt">
			<label for="address">Address:</label>
			<input name="address" id="address" value="<?php echo $address;?>" type="text" maxlength="75" size="30" /><span style="color: #0000FF">*</span>
		</div>
		<div class="frmOpt">
			<label for="address2">&nbsp;</label>
			<input name="address2" id="address2" value="<?php echo $address2;?>" type="text" maxlength="75" size="25" />
		</div>
		<div class="frmOpt">
			<label for="city">City:</label>
			<input name="city" id="city" value="<?php echo $city;?>" type="text" maxlength="50" size="20" /><span style="color: #0000FF">*</span>
		</div>
		<div class="frmOpt">
			<label for="locState"><?php echo HC_StateLabel;?>:</label>
	<?php 	if($lID == 0){
				$state = $hc_defaultState;
			}//end if
			include('../events/includes/' . HC_StateInclude);?><span style="color: #0000FF;">*</span>
		</div>
		<div class="frmOpt">
			<label for="zip">Postal Code:</label>
			<input name="zip" id="zip" value="<?php echo $zip;?>" type="text" maxlength="11" size="11" /><span style="color: #0000FF">*</span>
		</div>
		<div class="frmOpt">
			<label for="country">Country:</label>
			<input name="country" id="country" value="<?php echo $country;?>" type="text" maxlength="50" size="5" />
		</div>
		<div class="frmOpt">
			<label for="email">Email:</label>
			<input name="email" id="email" type="text" value="<?php echo $email;?>" size="35" maxlength="75" />
		</div>
		<div class="frmOpt">
			<label for="phone">Phone:</label>
			<input name="phone" id="phone" type="text" value="<?php echo $phone;?>" size="20" maxlength="25" />
		</div>
		<div class="frmOpt">
			<label for="url">Website:</label>
			<input name="url" id="url" type="text" value="<?php echo $website;?>" size="40" maxlength="100" />
	<?php 	if($website != 'http://'){	?>
				<a href="<?php echo $website;?>" target="_blank"><img src="<?php echo CalAdminRoot;?>/images/icons/iconWebsite.gif" width="16" height="16" alt="" border="0" /></a>
	<?php 	}//end if	?>
		</div>
		<div class="frmOpt">
			<label for="status">Status:</label>
			<select name="status" id="status">
				<option <?php if($status == 0){echo "selected=\"selected\"";}?> value="0">Admin Only</option>
				<option <?php if($status == 1){echo "selected=\"selected\"";}?> value="1">Public</option>
			</select>
		</div>
		<div class="frmOpt">
			<label>Map Data:</label>
			Latitude:&nbsp;<input name="lat" id="lat" type="text" value="<?php echo $lat;?>" size="10" maxlength="25" />&nbsp;&nbsp;Longitude:&nbsp;<input name="lon" id="lon" type="text" value="<?php echo $lon;?>" size="10" maxlength="25" />
		</div>
		<div class="frmOpt">
			<label>&nbsp;</label>
			<label for="updateMap" class="category"><input name="updateMap" id="updateMap" type="checkbox" <?php if($lID == 0){echo "checked=\"checked\"";}?> class="noBorderIE" />Update Map Data</label>&nbsp;
		</div>
		<div class="frmOpt">
			<label>&nbsp;</label>
			<a href="http://www.google.com/search?q=free+geocoding" class="main" target="_blank">Manual Geocode Lookup</a>
		</div>
		<div class="frmOpt">
			<label for="descript">Description:<br />(Optional)</label>
		<?php
			makeTinyMCE("descript", $hc_WYSIWYG, "435px", "advanced", $descript);?>
		</div>
	</fieldset>
	<?php
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "settings WHERE PkID IN(36,37,38,39);");
	if(hasRows($result)){
		if(mysql_result($result,0,0) == '36' && mysql_result($result,0,1) != ''){	?>
		<br />
		<fieldset>
		<?php
			$resultEF = doQuery("SELECT * FROM " . HC_TblPrefix . "locationnetwork WHERE LocationID = '" . $lID . "' AND IsActive = 1");
			if(hasRows($resultEF)){	?>
				<legend>Update This Location on eventful</legend>
				<a href="http://eventful.com/venues/<?php echo mysql_result($resultEF,0,1)?>" class="eventMain" target="_blank">View this Location on eventful</a><br /><br />
				<input type="hidden" name="efSetting" id="efSetting" value="modify" />
				<label for="doEventful" class="radioWide"><input name="doEventful" id="doEventful" type="checkbox" onclick="toggleMe(document.getElementById('eventful'));" />&nbsp;Check to Update on <b><span style="color:#0043FF;">event</span><span style="color:#66CC33;">ful</span></b></label>
		<?php
			} else {	?>
				<legend>Add This Location to eventful</legend>
				<input type="hidden" name="efSetting" id="efSetting" value="new" />
				<label for="doEventful" class="radioWide"><input name="doEventful" id="doEventful" type="checkbox" onclick="toggleMe(document.getElementById('eventful'));" />&nbsp;Check to Add to <b><span style="color:#0043FF;">event</span><span style="color:#66CC33;">ful</span></b></label>
		<?php
			}//end if	?>
			<div id="eventful" style="display:none;clear:both;">
		<?php
			if(mysql_result($result,1,1) == '' || mysql_result($result,2,1) == ''){	?>
			<div style="width:70%;padding:5px;border:solid 1px #0043FF;background:#EFEFEF;">
			<b>eventful Username &amp; Password Required</b><br />
			Enter your eventful Username &amp; Password to submit this event.<br /><br />
			To skip this step in the future save your eventful account info in your <a href="<?php echo CalAdminRoot;?>/index.php?com=generalset" target="_blank" class="main">Helios Calendar Settings</a><br /><br />
				<div class="frmOpt">
					<label for="efUser" class="settingsLabel">Username:</label>
					<input name="efUser" id="efUser" type="text" value="" size="20" maxlength="150" />
				</div>
				<div class="frmOpt">
					<label for="efPass" class="settingsLabel">Password:</label>
					<input name="efPass" id="efPass" type="password" value="" size="15" maxlength="30" />
				</div>
				<div class="frmOpt">
					<label for="efPass2" class="settingsLabel">Confirm Password:</label>
					<input name="efPass2" id="efPass2" type="password" value="" size="15" maxlength="30" />
				</div>
			</div>
		<?php			
			}//end if	?>
				The following information about this location will be submitted:
				<ul>
					<li>Name</li>
					<li>Address</li>
					<li>City</li>
					<li>
				<?php
					echo HC_StateLabel;	?></li>
					<li>Country</li>
					<li>Postal Code</li>
					<li>Description</li>
					<li>Website URL</li>
				</ul>
			</div>
		</fieldset>
<?php
		}//end if
	}//end if	?>
	<br />
	<input name="submit" id="submit" type="submit" value=" Save Location " class="button" />
	</form>