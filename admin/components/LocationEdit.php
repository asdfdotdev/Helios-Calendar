<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(!defined('hcAdmin')){header("HTTP/1.1 403 No Direct Access");exit();}

	include(HCLANG.'/admin/locations.php');
	
	$hc_Side[] = array(CalRoot . '/index.php?com=location','map.png',$hc_lang_locations['LinkMap'],1);

	$lID = (isset($_GET['lID']) && is_numeric($_GET['lID'])) ? cIn($_GET['lID']) : 0;
	$whereAmI = $hc_lang_locations['Add'];
	$name = $address = $address2 = $city = $country = $postal = $phone = $email = $descript = $lat = $lon = $geoAccuracy = $shortURL = $website = $imageURL = $followup = $fnote = '';
	$state = $hc_cfg[21];
	$status = 1;
	$helpDoc = "Adding_Locations";
	$helpText = $hc_lang_locations['InstructAdd'];
	
	$result = doQuery("SELECT l.*, f.EntityID, f.Note FROM " . HC_TblPrefix . "locations l LEFT JOIN " . HC_TblPrefix . "followup f ON (l.PkID = f.EntityID AND f.EntityType = 3) WHERE l.PkID = '" . $lID . "' AND l.IsActive = 1");
	if(hasRows($result)){
		$hc_Side[] = array(CalRoot . '/index.php?com=location&amp;lID=' . $lID,'card.png',$hc_lang_locations['LinkProfile'],1);
		$whereAmI = $hc_lang_locations['Edit'];
		$name = cOut(mysql_result($result,0,1));
		$address = cOut(mysql_result($result,0,2));
		$address2 = cOut(mysql_result($result,0,3));
		$city = cOut(mysql_result($result,0,4));
		$state = cOut(mysql_result($result,0,5));
		$country = cOut(mysql_result($result,0,6));
		$postal = cOut(mysql_result($result,0,7));
		$website = cOut(mysql_result($result,0,8));
		$phone = cOut(mysql_result($result,0,9));
		$email = cOut(mysql_result($result,0,10));
		$descript = cOut(mysql_result($result,0,11));
		$status = cOut(mysql_result($result,0,12));
		$lat = cOut(mysql_result($result,0,15));
		$lon = cOut(mysql_result($result,0,16));
		$geoAccuracy = cOut(mysql_result($result,0,17));
		$shortURL = cOut(mysql_result($result,0,18));
		$imageURL = cOut(mysql_result($result,0,21));
		$followup = (mysql_result($result,0,22) != '') ? 1 : 0;
		$fnote = cOut(mysql_result($result,0,23));
		$helpDoc = "Editing_Locations";
		$helpText = $hc_lang_locations['InstructEdit'];
	}
	if(isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1, $hc_lang_locations['Feed01']);
				break;
			case "2" :
				feedback(1, $hc_lang_locations['Feed02']);
				break;
		}
	}
	
	if($followup == 0)
		$hc_Side[] = array('javascript:;','followup.png',$hc_lang_core['LinkFollow'],0,'follow_up();return false;');
	
	appInstructions(0, $helpDoc, $whereAmI . " Location", $helpText);
	
	$inputs = array(1 => array('City','city',$city),2 => array('Postal','zip',$postal));
	$first = ($hc_lang_config['AddressType'] == 1) ? 1 : 2;
	$second = ($first == 1) ? 2 : 1;
	
	echo '
	<form name="frmLocEdit" id="frmLocEdit" method="post" action="'.AdminRoot.'/components/LocationEditAction.php" onsubmit="return validate();">';
	set_form_token();
	echo '
	<input name="lID" id="lID" type="hidden" value="'.$lID.'" />
	<fieldset id="follow-up"'.($followup == 0 ? ' style="display:none;"':'').'>
		<legend>'.$hc_lang_locations['FollowLabel'].'</legend>
		<label for="follow_up">'.$hc_lang_locations['Follow'].'</label>
		<select name="follow_up" id="follow_up"'.($followup == 0 ? ' disabled="disabled"':'').'>
			<option value="0">'.$hc_lang_locations['Follow0'].'</option>
			<option selected="selected" value="1">'.$hc_lang_locations['Follow1'].'</option>
		</select>
		<label for="follow_note">'.$hc_lang_locations['FollowNote'].'</label>
		<input name="follow_note" id="follow_note" type="text" size="90" maxlength="300" value="'.$fnote.'"'.($followup == 0 ? ' disabled="disabled"':'').' />
	</fieldset>
	<fieldset>
		<legend>'.$hc_lang_locations['Details'].'</legend>
		<label for="name">'.$hc_lang_locations['Name'].'</label>
		<input name="name" id="name" type="text" size="60" maxlength="150" required="required" value="'.$name.'" />
		<label for="descript">'.$hc_lang_locations['Description'].'</label>
		<textarea name="descript" id="descript" rows="20" class="mce_edit">'.$descript.'</textarea>
		<label for="status">'.$hc_lang_locations['Status'].'</label>
		<select name="status" id="status">
			<option'.(($status == 0) ? ' selected="selected"' : '').' value="0">'.$hc_lang_locations['Status0'].'</option>
			<option'.(($status == 1) ? ' selected="selected"' : '').' value="1">'.$hc_lang_locations['Status1'].'</option>
		</select>
		<label for="imageURL">'.$hc_lang_locations['Image'].'</label>
		<input name="imageURL" id="imageURL" type="url" size="85" maxlength="200" value="'.$imageURL.'" onblur="imgPreview();" />
		<div id="preview" style="display:none;">
			<label>'.$hc_lang_locations['ImagePreview'].'</label>
			<div title="'.$hc_lang_locations['ImageOpen'].'" id="image_preview" class="frm_ctrls"></div>
		</div>
	</fieldset>
	<fieldset>
		<legend>'.$hc_lang_locations['AddMap'].'</legend>
		<label for="address">'.$hc_lang_locations['Address'].'</label>
		<input name="address" id="address" type="text" size="30" maxlength="75" value="'.$address.'" /><span class="output req2">*</span>
		<label for="address2">'.$hc_lang_locations['Address2'].'</label>
		<input name="address2" id="address2" type="text" size="25" maxlength="75" value="'.$address2.'" />
		<label for="' . $inputs[$first][1] . '">' . $hc_lang_locations[$inputs[$first][0]] . '</label>
		<input name="' . $inputs[$first][1] . '" id="' . $inputs[$first][1] . '" type="text" size="20" maxlength="50" value="'.(($lID > 0) ? $inputs[$first][2] : '').'" /><span class="output req2">*</span>';
		
	if($hc_lang_config['AddressRegion'] != 0){	
		echo '<label for="locState">' . $hc_lang_config['RegionLabel'] . '</label>';
		$regSelect = '';
		include(HCLANG.'/'.$hc_lang_config['RegionFile']);
		echo '<span class="output req2">*</span>';}

	echo '
		<label for="'.$inputs[$second][1].'">'.$hc_lang_locations[$inputs[$second][0]].'</label>
		<input name="'.$inputs[$second][1].'" id="'.$inputs[$second][1].'" type="text" size="20" maxlength="50" value="'.(($lID > 0) ? $inputs[$second][2] : '').'" /><span class="output req2">*</span>	
		<label for="country">'.$hc_lang_locations['Country'].'</label>
		<input name="country" id="country" type="text" size="5" maxlength="50" value="'.$country.'" />
		<label>'.$hc_lang_locations['Map'].'</label>
		<span class="frm_ctrls_map">
			<label for="lat">'.$hc_lang_locations['Latitude'].'<input name="lat" id="lat" type="text" size="15" maxlength="25" value="'.$lat.'"  /></label>
			<label for="lon">'.$hc_lang_locations['Longitude'].'<input name="lon" id="lon" type="text" size="15" maxlength="25" value="'.$lon.'" /></label>
			'.(($lat != '' && $lon != '') ? '<a href="'.$hc_cfg[52].'maps?q='.$lat.','.$lon.'" target="_blank" title="'.$hc_lang_locations['Preview'].'" tabindex="-1"><img src="'.CalRoot.'/img/share/google.png" width="16" height="16" alt="'.$hc_lang_locations['Preview'].'" /></a>':'').'
		</span>
		<label>&nbsp;</label>
		<span class="frm_ctrls">
			<label for="updateMap"><input name="updateMap" id="updateMap" type="checkbox"'.(($hc_cfg[26] == '') ? ' disabled="disabled"':'').(($lID == 0) ? ' checked="checked"':'').' />'.$hc_lang_locations['Update'].' '.$hc_lang_locations['GeocodeNotice'].'</label>
		</span>
	</fieldset>
	<fieldset>
		<legend>'.$hc_lang_locations['Contact'].'</legend>
		<label for="email">'.$hc_lang_locations['Email'].'</label>
		<input name="email" id="email" type="email" size="35" maxlength="75" value="'.$email.'" />
		<label for="phone">'.$hc_lang_locations['Phone'].'</label>
		<input name="phone" id="phone" type="text" size="20" maxlength="25" value="'.$phone.'" />
		<label for="website">'.$hc_lang_locations['Website'].'</label>
		<input name="website" id="website" type="url" size="40" maxlength="100" value="'.$website.'" />
		'.(($website != 'http://' && $website != '') ? '<span class="frm_ctrls"><a href="'.$website.'" target="_blank"><img src="'.AdminRoot.'/img/icons/website.png" width="16" height="16" alt="" /></a></span>':'').'
	</fieldset>';
	
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "settings WHERE PkID IN(5,6,46,47,57,58);");
	$goEventbrite = (mysql_result($result,0,1) != '' && mysql_result($result,1,1) != '') ? 1 : 0;
	$goTwitter = (mysql_result($result,2,1) != '' && mysql_result($result,3,1) != '') ? 1 : 0;
	$goBitly = (mysql_result($result,4,1) && mysql_result($result,5,1)) ? 1 : 0;
	$ebID = $tweetLnks = '';
	$tweets = array();
	$resultD = doQuery("SELECT * FROM " . HC_TblPrefix . "locationnetwork WHERE LocationID = '" . $lID . "'");
	if(hasRows($resultD)){
		while($row = mysql_fetch_row($resultD)){
			switch($row[2]){
				case 1:
					//	Nothing
					break;
				case 2:
					$ebID = $row[1];
					break;
				case 3:
					$tweets[] = $row[1];
					break;
			}
		}
	}
	if(count($tweets) > 0){
		foreach($tweets as $val)
			$tweetLnks .=' <a href="http://twitter.com/'.$hc_cfg[63].'/status/'.$val.'" target="_blank">'.$val.'</a>';
	}
	$bitChk = '';
	$bitShow = ' style="display:none;"';
	$bitLabel = $hc_lang_locations['BitlyLabel'];
	$bitNotice = $hc_lang_locations['BitlyNotice'];
	if(strpos($shortURL,'http://') !== false){
		$bitChk = ' checked="checked"';
		$bitShow = '';
		$bitLabel = $hc_lang_locations['BitlyHasLink'];
		$bitNotice = $hc_lang_locations['BitlyTools'].'
			<ul>
				<li><a href="'.$shortURL.'+" target="_blank">' . $hc_lang_locations['BitlyReport'] . '</a></li>
				<li><a href="'.$shortURL.'" target="_blank">' . $hc_lang_locations['BitlyLink'] . '</a></li>
				<li><a href="'.$shortURL.'.qrcode" target="_blank">' . $hc_lang_locations['BitlyQRImage'] . '</a></li>
				<li><a href="'.$shortURL.'.qr" target="_blank">' . $hc_lang_locations['BitlyQRLink'] . '</a></li>
			</ul>';
	}
	$ebOrganziers = ($goEventbrite == 1) ? eventbrite_get_organizers() : array();
	$organizerID = $hc_cfg[62];
	$iso_country = $orgOpts = '';
	if($ebID != ''){
		include(HCPATH.HCINC.'/api/eventbrite/LocationGet.php');
	} else {
		$orgOpts = '
			<label for="ebOrgID"><b>'.$hc_lang_locations['EBOrganizer'].'</b></label>
			<select name="ebOrgID" id="ebOrgID">';

		foreach($ebOrganziers as $id => $name){
			$orgOpts .= '
				<option'.(($organizerID == $id) ? ' selected="selected"' : '').' value="'.$id.'">'.(($name != '') ? $name : '').' (#'.$id.')</option>';
		}

		$orgOpts .= '
			</select>';
	}

	echo '	
	<fieldset>
		<legend>'.$hc_lang_locations['DistPub'].'</legend>
		'.(($ebID != '') ? '<p>
			<b>'.$hc_lang_locations['PostedTo'].'</b>
			'.(($ebID != '') ? '<a href="https://www.eventbrite.com/venues-organizers" target="_blank">'.$hc_lang_locations['EventbriteView'].'</a>' : '').'
		</p>':'').'
		<label for="doEventbrite" class="distPub distPubTop">
			<input name="doEventbrite" id="doEventbrite" type="checkbox" onclick="toggleMe(document.getElementById(\'eventbrite\'));"'.(($goEventbrite == 0) ?  ' disabled="disabled"':'').' />
			'.(($ebID != '') ? $hc_lang_locations['EventbriteLabelU'] : $hc_lang_locations['EventbriteLabelA']).'
		</label>
		<div id="eventbrite" style="display:none;">
			'.$hc_lang_locations['EventbriteNotice'].'
			'.$orgOpts.'
			<label for="selCountry"><b>' . $hc_lang_locations['CountryCode'] . '</b></label>';
			include(HCLANG.'/selectCountry.php');
	echo '
		</div>
		<label for="doBitly" class="distPub">
			<input name="doBitly" id="doBitly" type="checkbox" onclick="toggleMe(document.getElementById(\'bitly\'));"'.($bitChk != '' ? ' checked="checked"':'').(($goBitly == 0 || $bitChk != '') ?  ' disabled="disabled"':'').' />
			'.$bitLabel.'
		</label>
		<div id="bitly"'.$bitShow.'>'.$bitNotice.'</div>
	</fieldset>
	<input name="submit" id="submit" type="submit" value="'.$hc_lang_locations['SaveLocation'].'" />
	</form>
	
	<script src="'.CalRoot.'/inc/javascript/validation.js"></script>
	<script>
	//<!--
	function validate(){
		var err = "";
		
		err +=reqField(document.getElementById("name"),"'.$hc_lang_locations['Valid06'].'\n");
		
		if(document.getElementById("email").value != "")
			err +=validEmail(document.getElementById("email"),"'.$hc_lang_locations['Valid17'].'\n");
				
		if(document.frmLocEdit.updateMap.checked){
			err +=reqField(document.getElementById("address"),"'.$hc_lang_locations['Valid07'].'\n");
			err +=reqField(document.getElementById("city"),"'.$hc_lang_locations['Valid08'].'\n");
			err +=reqField(document.getElementById("locState"),"'.$hc_lang_config['RegionTitle'].' '.$hc_lang_locations['Valid05'].'\n");
			err +=reqField(document.getElementById("zip"),"'.$hc_lang_locations['Valid09'].'\n");
		}
		
		err +=validNumber(document.getElementById("lat"),"'.$hc_lang_locations['Valid10'].'\n");
		err +=validNumber(document.getElementById("lon"),"'.$hc_lang_locations['Valid11'].'\n");
		
		if(document.frmLocEdit.doEventbrite.checked)
			err +=reqField(document.getElementById("selCountry"),"'.$hc_lang_locations['Valid21'].'\n");
		
		if(err != ""){
			alert(err);
			return false;
		} else {
			valid_ok(document.getElementById("submit"),"'.$hc_lang_core['Sending'].'");
			return true;
		}
	}
	function imgPreview(){
		var img = document.getElementById("imageURL");
		
		if(img.value == ""){
			document.getElementById("preview").style.display = "none";
			return false;
		}
		if(validURL(img,"nope") != ""){
			alert("'.$hc_lang_locations['Valid18'].'");
			return false;
		}
		document.getElementById("preview").style.display = "block";
		document.getElementById("image_preview").innerHTML = "<img src=\"" + img.value + "\" />";
		document.getElementById("image_preview").style.overflow = "scroll";
	}';
	
	if($imageURL != '')
		echo '
	imgPreview();';
	
	echo '
	//-->
	</script>';

	makeTinyMCE('82%',1,0,'descript');
?>