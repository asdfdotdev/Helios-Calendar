<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2008 Refresh Web Development [www.RefreshMy.com]
	
	Developed By: Chris Carlevato <support@refreshmy.com>
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar EUL found at www.HeliosCalendar.com/license.pdf
*/
	include($hc_langPath . $_SESSION['LangSet'] . '/admin/locations.php');
	
	$lID = 0;
	$whereAmI = $hc_lang_locations['Add'];
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
	$geoAccuracy = "";
	$helpDoc = "Adding_Locations";
	$helpText = $hc_lang_locations['InstructAdd'];
	
	if(isset($_GET['lID']) && is_numeric($_GET['lID'])){
		$lID = $_GET['lID'];
	}//end if
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "locations WHERE PkID = " . $lID);
	
	if(hasRows($result)){
		$whereAmI = $hc_lang_locations['Edit'];
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
		$geoAccuracy = mysql_result($result,0,17);
		$helpDoc = "Editing_Locations";
		$helpText = $hc_lang_locations['InstructEdit'];
	}//end if
	
	appInstructions(0, $helpDoc, $whereAmI . " Location", $helpText);	?>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Email.js"></script>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function toggleMe(who){
		who.style.display == 'none' ? who.style.display = 'block':who.style.display = 'none';
		return false;
	}//end toggleMe()
	
	function chkFrm(){
	dirty = 0;
	warn = "<?php echo $hc_lang_locations['Valid05'];?>";
		
		if(document.frmLocEdit.lName.value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_locations['Valid06'];?>';
		}//end if
		
		if(document.frmLocEdit.email.value != '' && chkEmail(document.frmLocEdit.email) == 0){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_locations['Valid17'];?>';
		}//end if
		
		if(document.frmLocEdit.updateMap.checked){
			if(document.frmLocEdit.address.value == ''){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_locations['Valid07'];?>';
			}//end if
			if(document.frmLocEdit.city.value == ''){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_locations['Valid08'];?>';
			}//end if
			if(document.frmLocEdit.zip.value == ''){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_locations['Valid09'];?>';
			}//end if
		}//end if
		
		if(isNaN(document.frmLocEdit.lat.value)){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_locations['Valid10'];?>';
		}//end if
		
		if(isNaN(document.frmLocEdit.lon.value)){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_locations['Valid11'];?>';
		}//end if
		
		if(document.frmLocEdit.doEventful.checked && document.frmLocEdit.country.value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_locations['Valid20'];?>';
		}//end if
		
		if(dirty > 0){
			alert(warn + '\n\n<?php echo $hc_lang_locations['Valid12'];?>');
			return false;
		} else {
			if(document.frmLocEdit.updateMap.checked){
				if(!confirm('<?php echo $hc_lang_locations['Valid13'] . "\\n\\n          " . $hc_lang_locations['Valid14'] . "\\n          " . $hc_lang_locations['Valid15'];?>')){
					return false;
				}//end if
				return true;
			}//end if
		}//end if
	}//end chkFrm()
	//-->
	</script>
	<br />
	<form name="frmLocEdit" id="frmLocEdit" method="post" action="<?php echo CalAdminRoot . "/components/LocationEditAction.php";?>" onsubmit="return chkFrm();">
	<input name="lID" id="lID" type="hidden" value="<?php echo $lID;?>" />
	<input name="gStatus" id="gStatus" type="hidden" value="<?php echo $geoAccuracy;?>" />
	<fieldset>
		<legend><?php echo $hc_lang_locations['Details'];?></legend>
		<div class="frmReq">
			<label for="lName"><?php echo $hc_lang_locations['Name'];?></label>
			<input name="lName" id="lName" value="<?php echo $name;?>" type="text" maxlength="50" size="40" /><span style="color: #DC143C">  *</span>
		</div>
		<div class="frmOpt">
			<label for="address"><?php echo $hc_lang_locations['Address'];?></label>
			<input name="address" id="address" value="<?php echo $address;?>" type="text" maxlength="75" size="30" /><span style="color: #0000FF"> *</span>
		</div>
		<div class="frmOpt">
			<label for="address2">&nbsp;</label>
			<input name="address2" id="address2" value="<?php echo $address2;?>" type="text" maxlength="75" size="25" />
		</div>
		<div class="frmOpt">
			<label for="city"><?php echo $hc_lang_locations['City'];?></label>
			<input name="city" id="city" value="<?php echo $city;?>" type="text" maxlength="50" size="20" /><span style="color: #0000FF"> *</span>
		</div>
		<div class="frmOpt">
			<label for="locState"><?php echo $hc_lang_config['RegionLabel'];?></label>
	<?php 	if($lID == 0){
				$state = $hc_defaultState;
			}//end if
			include($hc_langPath . $_SESSION['LangSet'] . '/' . $hc_lang_config['RegionFile']);?><span style="color: #0000FF;"> *</span>
		</div>
		<div class="frmOpt">
			<label for="zip"><?php echo $hc_lang_locations['Postal'];?></label>
			<input name="zip" id="zip" value="<?php echo $zip;?>" type="text" maxlength="11" size="11" /><span style="color: #0000FF"> *</span>
		</div>
		<div class="frmOpt">
			<label for="country"><?php echo $hc_lang_locations['Country'];?></label>
			<input name="country" id="country" value="<?php echo $country;?>" type="text" maxlength="50" size="5" /><span style="color: #008000;"> *</span>
		</div>
		<div class="frmOpt">
			<label for="email"><?php echo $hc_lang_locations['Email'];?></label>
			<input name="email" id="email" type="text" value="<?php echo $email;?>" size="35" maxlength="75" />
		</div>
		<div class="frmOpt">
			<label for="phone"><?php echo $hc_lang_locations['Phone'];?></label>
			<input name="phone" id="phone" type="text" value="<?php echo $phone;?>" size="20" maxlength="25" />
		</div>
		<div class="frmOpt">
			<label for="url"><?php echo $hc_lang_locations['Website'];?></label>
			<input name="url" id="url" type="text" value="<?php echo $website;?>" size="40" maxlength="100" />
	<?php 	if($website != 'http://'){	?>
				<a href="<?php echo $website;?>" target="_blank"><img src="<?php echo CalAdminRoot;?>/images/icons/iconWebsite.png" width="16" height="16" alt="" border="0" /></a>
	<?php 	}//end if	?>
		</div>
		<div class="frmOpt">
			<label for="status"><?php echo $hc_lang_locations['Status'];?></label>
			<select name="status" id="status">
				<option <?php if($status == 0){echo "selected=\"selected\"";}?> value="0"><?php echo $hc_lang_locations['Status0'];?></option>
				<option <?php if($status == 1){echo "selected=\"selected\"";}?> value="1"><?php echo $hc_lang_locations['Status1'];?></option>
			</select>
		</div>
		<div class="frmOpt">
			<label><?php echo $hc_lang_locations['Map'];?></label>
			<?php echo $hc_lang_locations['Latitude'];?>&nbsp;<input name="lat" id="lat" type="text" value="<?php echo $lat;?>" size="10" maxlength="25" />&nbsp;&nbsp;<?php echo $hc_lang_locations['Longitude'];?>&nbsp;<input name="lon" id="lon" type="text" value="<?php echo $lon;?>" size="10" maxlength="25" />
		</div>
		<div class="frmOpt">
			<label>&nbsp;</label>
			<label for="updateMap" class="category"><input name="updateMap" id="updateMap" type="checkbox" <?php if($lID == 0){echo "checked=\"checked\"";}?> class="noBorderIE" /><?php echo $hc_lang_locations['Update'];?></label>&nbsp;
		</div>
		<div class="frmOpt">
			<label>&nbsp;</label>
			<a href="http://www.google.com/search?q=free+geocoding" class="main" target="_blank"><?php echo $hc_lang_locations['ManualUpdate'];?></a>
		</div>
		<div class="frmOpt">
			<label for="descript"><?php echo $hc_lang_locations['Description'];?><br />(<?php echo $hc_lang_locations['Optional'];?>)</label>
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
				<legend><?php echo $hc_lang_locations['UpdateE'];?></legend>
				<a href="http://eventful.com/venues/<?php echo mysql_result($resultEF,0,1)?>" class="eventMain" target="_blank"><?php echo $hc_lang_locations['ViewEventful'];?></a><br /><br />
				<input type="hidden" name="efSetting" id="efSetting" value="modify" />
				<label for="doEventful" class="radioWide"><input name="doEventful" id="doEventful" type="checkbox" onclick="toggleMe(document.getElementById('eventful'));" />&nbsp;<?php echo $hc_lang_locations['EventfulUpdate'];?> <b><span style="color:#0043FF;">event</span><span style="color:#66CC33;">ful</span></b></label>
		<?php
			} else {	?>
				<legend><?php echo $hc_lang_locations['AddE'];?></legend>
				<input type="hidden" name="efSetting" id="efSetting" value="new" />
				<label for="doEventful" class="radioWide"><input name="doEventful" id="doEventful" type="checkbox" onclick="toggleMe(document.getElementById('eventful'));" />&nbsp;<?php echo $hc_lang_locations['EventfulAdd'];?> <b><span style="color:#0043FF;">event</span><span style="color:#66CC33;">ful</span></b></label>
		<?php
			}//end if	?>
			<div id="eventful" style="display:none;clear:both;">
		<?php
			if(mysql_result($result,1,1) == '' || mysql_result($result,2,1) == ''){	?>
			<div style="width:70%;padding:5px;border:solid 1px #0043FF;background:#EFEFEF;">
			<?php echo $hc_lang_locations['EventfulReq'];?>
			<br /><br />
				<div class="frmOpt">
					<label for="efUser" class="settingsLabel"><?php echo $hc_lang_locations['Username'];?></label>
					<input name="efUser" id="efUser" type="text" value="" size="20" maxlength="150" />
				</div>
				<div class="frmOpt">
					<label for="efPass" class="settingsLabel"><?php echo $hc_lang_locations['Passwrd1'];?></label>
					<input name="efPass" id="efPass" type="password" value="" size="15" maxlength="30" />
				</div>
				<div class="frmOpt">
					<label for="efPass2" class="settingsLabel"><?php echo $hc_lang_locations['Passwrd2'];?></label>
					<input name="efPass2" id="efPass2" type="password" value="" size="15" maxlength="30" />
				</div>
			</div>
		<?php			
			}//end if	
			echo $hc_lang_locations['EventfulSubmit'];	?>
			</div>
		</fieldset>
<?php
		}//end if
	}//end if	?>
	<br />
	<input name="submit" id="submit" type="submit" value=" <?php echo $hc_lang_locations['SaveLocation'];?> " class="button" />
	</form>