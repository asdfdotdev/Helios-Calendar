<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/admin/locations.php');
	
	$hc_Side[] = array(CalRoot . '/index.php?com=location','iconMap.png',$hc_lang_locations['LinkMap'],1);

	if(isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1, $hc_lang_locations['Feed01']);
				break;
			case "2" :
				feedback(1, $hc_lang_locations['Feed02']);
				break;
		}//end switch
	}//end if

	$lID = (isset($_GET['lID']) && is_numeric($_GET['lID'])) ? cIn($_GET['lID']) : 0;
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
	
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "locations WHERE PkID = " . $lID);
	if(hasRows($result)){
		$whereAmI = $hc_lang_locations['Edit'];
		$name = cOut(mysql_result($result,0,1));
		$address = cOut(mysql_result($result,0,2));
		$address2 = cOut(mysql_result($result,0,3));
		$city = cOut(mysql_result($result,0,4));
		$state = mysql_result($result,0,5);
		$country = cOut(mysql_result($result,0,6));
		$zip = mysql_result($result,0,7);
		$website = mysql_result($result,0,8);
		$phone = mysql_result($result,0,9);
		$email = mysql_result($result,0,10);
		$descript = cOut(mysql_result($result,0,11));
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
		
		if(document.frmLocEdit.doEventbrite.checked){
			if(document.getElementById('selCountry').value == ''){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_locations['Valid21'];?>';
			}//end if
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
			<label for="address2"><?php echo $hc_lang_locations['Address2'];?></label>
			<input name="address2" id="address2" value="<?php echo $address2;?>" type="text" maxlength="75" size="25" />
		</div>
	<?php
		$inputs = array(1 => array('City','city',$city),2 => array('Postal','zip',$zip));

		echo '<div class="frmOpt">';
		$first = ($hc_lang_config['AddressType'] == 1) ? 1 : 2;
		$second = ($first == 1) ? 2 : 1;

		echo '<label for="' . $inputs[$first][1] . '">' . $hc_lang_locations[$inputs[$first][0]] . '</label>';
		echo '<input name="' . $inputs[$first][1] . '" id="' . $inputs[$first][1] . '" value="' . $inputs[$first][2] . '" type="text" maxlength="50" size="20" /><span style="color: #0000FF">*</span>';
		echo '</div>';

		if($hc_lang_config['AddressRegion'] != 0){
			echo '<div class="frmOpt">';
			echo '<label for="locState">' . $hc_lang_config['RegionLabel'] . '</label>';
			include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/' . $hc_lang_config['RegionFile']);
			echo '<span style="color: #0000FF;">*</span></div>';
		}//end if

		echo '<div class="frmOpt">';
		echo '<label for="' . $inputs[$second][1] . '">' . $hc_lang_locations[$inputs[$second][0]] . '</label>';
		echo '<input name="' . $inputs[$second][1] . '" id="' . $inputs[$second][1] . '" value="' . $inputs[$second][2] . '" type="text" maxlength="50" size="20" /><span style="color: #0000FF">*</span>';
		echo '</div>';
		?>
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
			<div class="hc_align">&nbsp;<?php echo $hc_lang_locations['Latitude'];?>&nbsp;</div><input name="lat" id="lat" type="text" value="<?php echo $lat;?>" size="12" maxlength="25" /><div class="hc_align">&nbsp;<?php echo $hc_lang_locations['Longitude'];?>&nbsp;</div><input name="lon" id="lon" type="text" value="<?php echo $lon;?>" size="12" maxlength="25" />
		<?php
			if($lat != '' && $lon != ''){
				echo '<div class="hc_align">&nbsp;<a href="' . $hc_cfg52 . '/maps?q=' . $lat . ',' . $lon . '" target="_blank" title="' . $hc_lang_locations['Preview'] . '"><img src="' . CalRoot . '/images/share/google.png" width="16" height="16" alt="' . $hc_lang_locations['Preview'] . '" border="0" style="vertical-align:middle;" /></a>&nbsp;</div>';
			}//end if?>
		</div>
		<div class="frmOpt">
			<label>&nbsp;</label>
			<label for="updateMap" class="captcha"><input name="updateMap" id="updateMap" type="checkbox" <?php if($hc_cfg26 == ''){echo 'disabled="disabled"';} if($lID == 0){echo 'checked="checked"';}?> class="noBorderIE" /><?php echo $hc_lang_locations['Update'];?></label>&nbsp;
		</div>
		<div class="frmOpt">
			<label>&nbsp;</label>
			<a href="http://www.google.com/search?q=free+geocoding" class="main" target="_blank"><?php echo $hc_lang_locations['ManualUpdate'];?></a>
		</div>
		<div class="frmOpt">
			<label for="descript"><?php echo $hc_lang_locations['Description'];?><br />(<?php echo $hc_lang_locations['Optional'];?>)</label>
		<?php
			makeTinyMCE('descript', '565px', 'advanced', $descript);?>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_locations['DistPub'];?></legend>
	<?php
		$efID = $ebID = $efDownload = '';
		$resultD = doQuery("SELECT * FROM " . HC_TblPrefix . "locationnetwork WHERE LocationID = '" . cIn($lID) . "'");
		if(hasRows($resultD)){
			while($row = mysql_fetch_row($resultD)){
				switch($row[2]){
					case 1:
						$efID = $row[1];
						$efDownload = $row[3];
						break;
					case 2:
						$ebID = $row[1];
						break;
				}//end if
			}//end while
		}//end if
		echo '<i>' . $hc_lang_locations['DistPubNotice'] . '</i><br /><br />';
		$result = doQuery("SELECT * FROM " . HC_TblPrefix . "settings WHERE PkID IN(5,6,36,37,38);");
		$goEventful = (mysql_result($result,2,1) != '' && mysql_result($result,3,1) != '' && mysql_result($result,4,1) != '') ? 1 : 0;
		$goEventbrite = (mysql_result($result,0,1) != '' && mysql_result($result,1,1) != '' && $hc_cfg62 != '') ? 1 : 0;
		
		if($efID != '' || $ebID != ''){
			echo '<b>' . $hc_lang_locations['DistPubLinks'] . '</b>';
			echo ($efID != '') ? ' <a href="http://www.eventful.com/venues/' . $efID . '" class="eventMain" target="_blank">' . $hc_lang_locations['EventfulView'] . '</a>' : '';
			echo ($ebID != '') ? ' <a href="http://www.eventbrite.com/venues/' . $ebID . '" class="eventMain" target="_blank">' . $hc_lang_locations['EventbriteView'] . '</a>' : '';
			echo '<br /><br />';
		}//end if

		echo '<div class="frmOpt"><label for="doEventful" class="radioDistPub"><input name="doEventful" id="doEventful" type="checkbox" onclick="toggleMe(document.getElementById(\'eventful\'));" class="noBorderIE"';
		echo ($goEventful == 1 && $efDownload != 1) ?  ' />&nbsp;' : ' disabled="disabled" />&nbsp;';
		if($efID == ''){
			echo $hc_lang_locations['EventfulLabelA'] . '</label></div>';
		} else {
			echo ($efDownload != 1) ? $hc_lang_locations['EventfulLabelU'] . '</label></div>' : $hc_lang_locations['EventfulLabelNE'] . '</label></div>';
		}//end if
		echo '<div id="eventful" style="display:none;clear:both;">' . $hc_lang_locations['EventfulNotice'] . '</div>';

		echo '<div class="frmOpt" style="border-top:solid 1px #CCCCCC;"><label for="doEventbrite" class="radioDistPub"><input name="doEventbrite" id="doEventbrite" type="checkbox" onclick="toggleMe(document.getElementById(\'eventbrite\'));" class="noBorderIE"';
		echo ($goEventbrite == 1) ?  ' />&nbsp;' : ' disabled="disabled" />&nbsp;';
		echo ($ebID != '') ? $hc_lang_locations['EventbriteLabelU'] . '</label></div>' : $hc_lang_locations['EventbriteLabelA'] . '</label></div>';
		echo '<div id="eventbrite" style="display:none;clear:both;">';
		echo '<div class="frmOpt" style="clear:both;padding-top:10px;"><label for="selCountry"><b>' . $hc_lang_locations['CountryCode'] . '</b></label>';
		include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/selectCountry.php');
		echo '</div>';

		echo '<div style="clear:both;"><br />' . $hc_lang_locations['EventbriteNotice'] . '</div>';
		echo '</div>';?>
	</fieldset>
	<br />
	<input name="submit" id="submit" type="submit" value=" <?php echo $hc_lang_locations['SaveLocation'];?> " class="button" />
	</form>