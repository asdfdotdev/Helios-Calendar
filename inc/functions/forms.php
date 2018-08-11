<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(!defined('isHC')){exit(-1);}
	
	/**
	 * Wrapper to call required function(s) to generate current active form.
	 * @since 2.0.0
	 * @version 2.1.0
	 * @return void
	 */
	function get_form(){
		global $hc_cfg, $eID, $lID;
		
		if(HCCOM == '')
			return 0;
		
		switch(HCCOM){
			case 'send':
				send_to_friend();
				break;
			case 'signup':
				news_signup();
				break;
			case 'edit':
				news_edit();
				break;
			case 'filter':
				filter();
				break;
			case 'search':
				search();
				break;
			case 'searchresult':
				search_result();
				break;
			case 'submit':
				if($eID > 0 || isset($_GET['sID']))
					submit_update();
				else
					submit();
				break;
			case 'rsvp':
				rsvp();
				break;
		}
	}
	/**
	 * Wrapper to call required function(s) to generate JavaScript validation for current active form.
	 * @since 2.0.0
	 * @version 2.1.0
	 * @return void
	 */
	function get_form_validation(){
		global $eID;
		
		if(HCCOM == '')
			return 0;
		
		switch(HCCOM){
			case 'send':
				send_to_friend_valid();
				break;
			case 'signup':
				news_signup_valid();
				break;
			case 'edit':
				news_edit_valid();
				break;
			case 'filter':
				filter_valid();
				break;
			case 'search':
				search_valid();
				break;
			case 'submit':
				$sub_type = ($eID > 0 || isset($_GET['sID'])) ? '1' : '0';
				submit_valid_output($sub_type);
				break;
			case 'rsvp':
				rsvp_valid();
				break;
			case 'rss':
		}
	}
	/**
	 * Output Send to Friend JavaScript validation.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @return void
	 */
	function send_to_friend_valid(){
		global $eID, $lID, $hc_cfg, $hc_lang_sendtofriend, $hc_lang_core;
		echo '
	<script src="'.CalRoot.'/inc/javascript/validation.js"></script>
	<script>
	//<!--
	function validate(){
		var err = "";';
		captchaValidation('2');
		
		echo '
		err +=reqField(document.getElementById("hc_fx1"),"'.$hc_lang_sendtofriend['Valid03'].'\n");
		err +=reqField(document.getElementById("hc_fx2"),"'.$hc_lang_sendtofriend['Valid04'].'\n");
		if(document.getElementById("hc_fx2").value != "")
			err +=validEmail(document.getElementById("hc_fx2"),"'.$hc_lang_sendtofriend['Valid05'].'\n");
		err +=reqField(document.getElementById("hc_fx3"),"'.$hc_lang_sendtofriend['Valid06'].'\n");
		err +=reqField(document.getElementById("hc_fx4"),"'.$hc_lang_sendtofriend['Valid07'].'\n");
		err +=reqField(document.getElementById("hc_fx5"),"'.$hc_lang_sendtofriend['Valid10'].'\n");
		if(document.getElementById("hc_fx4").value != "")
			err +=validEmail(document.getElementById("hc_fx4"),"'.$hc_lang_sendtofriend['Valid08'].'\n");
		err +=validMaxLength(document.getElementById("message"),250,"'.$hc_lang_sendtofriend['Valid08'].'\n");

		if(err != ""){
			alert(err);
			return false;
		} else {
			valid_ok(document.getElementById("submit"),"'.$hc_lang_core['Sending'].'");
			return true;
		}
	}';
	testCaptcha();
	echo '
	//-->
	</script>';
	}
	/**
	 * Output Send to Friend Form
	 * @since 2.0.0
	 * @version 2.0.0
	 * @return void
	 */
	function send_to_friend(){
		global $eID, $lID, $hc_cfg, $hc_lang_sendtofriend, $hc_lang_config, $hc_lang_core, $hc_captchas, $result;
		
		if($eID > 0 && hasRows($result)){
			$tID = 0;
			$noticeTxt = $hc_lang_sendtofriend['SendLabelE'];
			$msgTxt = $hc_lang_sendtofriend['SentMessageE'];
			$url = CalRoot . '/index.php?eID=' . $eID;
		} elseif($lID > 0 && hasRows($result)) {
			$tID = 1;
			$eID = $lID;
			$noticeTxt = $hc_lang_sendtofriend['SendLabelL'];
			$msgTxt = $hc_lang_sendtofriend['SentMessageL'];
			$locAddress = cOut(mysql_result($result,0,1));
			$locAddress2 = cOut(mysql_result($result,0,2));
			$locCity = cOut(mysql_result($result,0,3));
			$locState = cOut(mysql_result($result,0,4));
			$locZip = cOut(mysql_result($result,0,5));
			$locCountry = cOut(mysql_result($result,0,6));
			$url = CalRoot . '/index.php?com=location&amp;lID=' . $lID;
		} else {
			echo '
			<p>'.$hc_lang_sendtofriend['NoEvent'].'</p>
			<p><a href="' . CalRoot . '/index.php">'.$hc_lang_sendtofriend['ThisWeekLink'].'</a></p>';
			return 0;
		}

		if(isset($_GET['msg'])){
			switch(cIn(strip_tags($_GET['msg']))){
				case 1 :
					feedback(1,$hc_lang_sendtofriend['Feed01']);
					break;	
			}
		}
		
		echo '
		<p>'.$noticeTxt.'</p>
		
		<form name="frmSendToFriend" id="frmSendToFriend" method="post" action="'.CalRoot.'/event-to-friend.php" onsubmit="return validate();">
		<input type="hidden" name="eID" id="eID" value="'.$eID.'" />
		<input type="hidden" name="tID" id="tID" value="'.$tID.'" />';
		
		if($hc_cfg[65] > 0 && in_array(2, $hc_captchas)){
			echo '
		<fieldset>
			<legend>' . $hc_lang_core['CapLegend'] . '</legend>';
			buildCaptcha();
		echo '
		</fieldset>';
		}
		
		if($tID == 0){
		echo '
		<fieldset>
			<legend>'.$hc_lang_sendtofriend['EventDetail'].'</legend>
			<label>'.$hc_lang_sendtofriend['Event'].'</label>
			<a href="'.$url.'" class="output" rel="nofollow">'.cOut(mysql_result($result,0,0)).'</a>
			<label>'.$hc_lang_sendtofriend['Date'].'</label>
			<span class="output">'.stampToDate(mysql_result($result,0,1), $hc_cfg[14]).'</span>			
			<label>'.$hc_lang_sendtofriend['Time'].'</label>';
		if(mysql_result($result,0,3) == 0)
			$time = (mysql_result($result,0,2) != '') ? stampToDate(mysql_result($result,0,2), $hc_cfg[23]) : '';
		else
			$time = (mysql_result($result,0,3) == 1) ? $hc_lang_sendtofriend['AllDay'] : $hc_lang_sendtofriend['TBA'];
		echo '
			<span class="output">'.$time.'</span>
		</fieldset>';
		
		} elseif($tID == 1) {
		echo '
		<fieldset>
			<legend>'.$hc_lang_sendtofriend['LocationDetail'].'</legend>
			<label>'.$hc_lang_sendtofriend['Name'].'</label>
			<a href="'.$url.'" class="output" rel="nofollow">'.cOut(mysql_result($result,0,0)).'</a>
			<label>'.$hc_lang_sendtofriend['Address'].'</label>
			<span class="output">'.buildAddress($locAddress,$locAddress2,$locCity,$locState,$locZip,$locCountry,$hc_lang_config['AddressType']).'</span>
		</fieldset>';}
		
		echo '
		<fieldset>
			<legend>'.$hc_lang_sendtofriend['CreateMsg'].'</legend>
			
			<label for="hc_fx1">'.$hc_lang_sendtofriend['MyName'].'</label>
			<input name="hc_fx1" id="hc_fx1" type="text" size="25" maxlength="100" placeholder="'.$hc_lang_sendtofriend['PlaceMName'].'" required="required" value="" />
			<label for="hc_fx2">'.$hc_lang_sendtofriend['MyEmail'].'</label>
			<input id="hc_fx2" name="hc_fx2" type="email" size="35" maxlength="100" placeholder="'.$hc_lang_sendtofriend['PlaceMEmail'].'" required="required" value="" />
			<label for="hc_fx3">'.$hc_lang_sendtofriend['FriendsName'].'</label>
			<input name="hc_fx3" id="hc_fx3" type="text" size="25" maxlength="100" placeholder="'.$hc_lang_sendtofriend['PlaceFName'].'" required="required" value="" />
			<label for="hc_fx4">'.$hc_lang_sendtofriend['FriendsEmail'].'</label>
			<input name="hc_fx4" id="hc_fx4" type="email" size="35" maxlength="100" placeholder="'.$hc_lang_sendtofriend['PlaceFEmail'].'" required="required" value="" />
			<label for="hc_fx5">'.$hc_lang_sendtofriend['Message'].'<br /><br />'.$hc_lang_sendtofriend['MessageLimit'].'</label>
			<textarea name="hc_fx5" id="hc_fx5" rows="10" cols="10" onkeyup="this.value=this.value.slice(0, 250)" required="required">'.$msgTxt.'</textarea>
		</fieldset>';
		fakeFormFields();
		
		echo '
		<input name="submit" id="submit" type="submit" value="'.$hc_lang_sendtofriend['SendMessage'].'" />
		<input name="cancel" id="cancel" type="button" value="'.$hc_lang_sendtofriend['Cancel'].'" onclick="window.location.href=\''.$url.'\';return false;" />
		</form>';
	}
	/**
	 * Output RSVP JavaScript validation.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @return void
	 */
	function rsvp_valid(){
		global $eID, $hc_cfg, $hc_lang_rsvp, $hc_lang_core;
		echo '
	<script src="'.CalRoot.'/inc/javascript/validation.js"></script>
	<script>
	//<!--
	function validate(){
		var err = "";';
		captchaValidation('3');
		
		echo '
		err +=reqField(document.getElementById("hc_f1"),"'.$hc_lang_rsvp['Valid09'].'\n");
		err +=reqField(document.getElementById("hc_f2"),"'.$hc_lang_rsvp['Valid10'].'\n");
		if(document.getElementById("hc_f2").value != "")
			err +=validEmail(document.getElementById("hc_f2"),"'.$hc_lang_rsvp['Valid11'].'\n");
		
		if(err != ""){
			alert(err);
			return false;
		} else {
			valid_ok(document.getElementById("submit"),"'.$hc_lang_core['Sending'].'");
			return true;
		}
	}';
	testCaptcha();
	echo '
	//-->
	</script>';
	}
	/**
	 * Output RSVP Form
	 * @since 2.0.0
	 * @version 2.2.0
	 * @return void
	 */
	function rsvp(){
		global $eID, $hc_cfg, $hc_lang_rsvp, $hc_captchas, $hc_lang_config, $hc_lang_core, $result;
		
		$rsvp_open = ((strtotime(SYSDATE) >= strtotime(mysql_result($result,0,7))) && (strtotime(SYSDATE) <= strtotime(mysql_result($result,0,8)))) ? 1 : 0;
		$is_series = mysql_result($result,0,9);
				
		if(!hasRows($result) || $hc_cfg['IsRSVP'] == 0 || $rsvp_open == 0){
			echo '
			<p>'.$hc_lang_rsvp['NoReg'].'</p>
			<p><a href="' . CalRoot . '/index.php">'.$hc_lang_rsvp['FindEvent'].'</a></p>';
			return 0;}
		
		if(isset($_GET['msg'])){
			switch(cIn(strip_tags($_GET['msg']))){
				case 1 :
					feedback(2, $hc_lang_rsvp['Feed02']);
					break;
				case 2 :
					feedback(1, $hc_lang_rsvp['Feed06']);
					break;
			}
		}
		
		echo '
		<p>'.$hc_lang_rsvp['RegNotice'].'</p>
		
		<form name="frmEventRSVP" id="frmEventRSVP" method="post" action="'.CalRoot.'/event-rsvp.php" onsubmit="return validate();">
		<input type="hidden" name="eID" id="eID" value="'.($is_series == 1 ? mysql_result($result,0,12) : $eID).'" />';
			
		if($hc_cfg[65] > 0 && in_array(3, $hc_captchas)){
			echo '
		<fieldset>
			<legend>' . $hc_lang_core['CapLegend'] . '</legend>';
			buildCaptcha();
		echo '
		</fieldset>';
		}
		
		echo '
		<fieldset>
			<legend>'.$hc_lang_rsvp['EventDetail'].'</legend>
			<label>'.$hc_lang_rsvp['Event'].'</label>
			<a href="'.CalRoot.($is_series == 1 ? '/index.php?com=series&amp;sID='.mysql_result($result,0,6) : '/index.php?eID='.$eID).'" class="output" rel="nofollow">'.cOut(mysql_result($result,0,0)).'</a>
			<label>'.$hc_lang_rsvp['Date'.$is_series].'</label>
			<span class="output">
				'.($is_series == 1 ? stampToDate(mysql_result($result,0,10), $hc_cfg[14]).' - '.stampToDate(mysql_result($result,0,11), $hc_cfg[14]) : stampToDate(mysql_result($result,0,1), $hc_cfg[14])).'
			</span>';
		
		if(mysql_result($result,0,3) == 0){
			$time = (mysql_result($result,0,2) != '') ? stampToDate(mysql_result($result,0,2), $hc_cfg[23]) : '';
		} else {
			$time = (mysql_result($result,0,3) == 1) ? $hc_lang_rsvp['AllDay'] : $hc_lang_rsvp['TBA'];}
		echo '
			<label>'.$hc_lang_rsvp['Time'].'</label>
			<span class="output">'.$time.'</span>
			<label>'.$hc_lang_rsvp['Contact'].'</label>
			<span class="output">';
		cleanEmailLink(cOut(mysql_result($result,0,5)),cOut(mysql_result($result,0,0)),cOut(mysql_result($result,0,4)).' - ');
		echo '
			</span>
		</fieldset>
		<fieldset>
			<legend>'.$hc_lang_rsvp['YourReg'].'</legend>
			<label for="hc_f1">'.$hc_lang_rsvp['Name'].'</label>
			<input name="hc_f1" id="hc_f1" type="text" size="25" maxlength="50" placeholder="'.$hc_lang_rsvp['PlaceName'].'" required="required" value="" />
			<label for="hc_f2">'.$hc_lang_rsvp['Email'].'</label>
			<input name="hc_f2" id="hc_f2" type="email" size="35" maxlength="75" placeholder="'.$hc_lang_rsvp['PlaceEmail'].'" required="required" value="" />
			<label for="hc_f3">'.$hc_lang_rsvp['Phone'].'</label>
			<input name="hc_f3" id="hc_f3" type="tel" size="20" maxlength="25" placeholder="'.$hc_lang_rsvp['PlacePhone'].'" value="" />
			<label for="hc_f7">'.$hc_lang_rsvp['PartySize'].'</label>
			<select name="hc_f7" id="hc_f7">
				<option value="0">'.$hc_lang_rsvp['Alone'].'</option>
				<option value="1">'.$hc_lang_rsvp['Myself'].' +1</option>
				<option value="2">'.$hc_lang_rsvp['Myself'].' +2</option>
				<option value="3">'.$hc_lang_rsvp['Myself'].' +3</option>
				<option value="4">'.$hc_lang_rsvp['Myself'].' +4</option>
				<option value="5">'.$hc_lang_rsvp['Myself'].' +5</option>
				<option value="6">'.$hc_lang_rsvp['Myself'].' +6</option>
				<option value="7">'.$hc_lang_rsvp['Myself'].' +7</option>
				<option value="8">'.$hc_lang_rsvp['Myself'].' +8</option>
				<option value="9">'.$hc_lang_rsvp['Myself'].' +9</option>
			</select>
			<label for="hc_f4">'.$hc_lang_rsvp['Address'].'</label>
			<input name="hc_f4" id="hc_f4" type="text" size="20" maxlength="75" placeholder="'.$hc_lang_rsvp['PlaceAddress'].'" value="" />
			<label for="hc_f5">'.$hc_lang_rsvp['Address2'].'</label>
			<input name="hc_f5" id="hc_f5" type="text" size="20" maxlength="75" placeholder="'.$hc_lang_rsvp['PlaceAddress2'].'" value="" />';
			
			$inputs = array(1 => array('City','hc_f6','PlaceCity'),2 => array('Postal','hc_f8','PlacePostal'));
			$first = ($hc_lang_config['AddressType'] == 1) ? 1 : 2;
			$second = ($first == 1) ? 2 : 1;
			
			echo '
			<label for="'.$inputs[$first][1].'">'.$hc_lang_rsvp[$inputs[$first][0]].'</label>
			<input name="'.$inputs[$first][1].'" id="'.$inputs[$first][1].'" value="" type="text" size="20" maxlength="50" placeholder="'.$hc_lang_rsvp[$inputs[$first][2]].'" />';
			
			if($hc_lang_config['AddressRegion'] != 0){
				$regSelect = $hc_lang_rsvp['PlaceRegion'];
				$state = $hc_cfg[21];
				echo '
			<label for="locState">'.$hc_lang_config['RegionLabel'].'</label>';
			include(HCLANG.'/'.$hc_lang_config['RegionFile']);}
			
			echo '
			<label for="'.$inputs[$second][1].'">'.$hc_lang_rsvp[$inputs[$second][0]].'</label>
			<input name="'.$inputs[$second][1].'" id="'.$inputs[$second][1].'" type="text" size="20" maxlength="50" placeholder="'.$hc_lang_rsvp[$inputs[$second][2]].'" value="" />
			<label for="hc_f9">'.$hc_lang_rsvp['Country'].'</label>
			<input name="hc_f9" id="hc_f9" type="text" size="10" maxlength="50" placeholder="'.$hc_lang_rsvp['PlaceCountry'].'" value="" />
		</fieldset>';
		
		fakeFormFields();
		
		echo '
		<input name="submit" id="submit" type="submit" value="'.$hc_lang_rsvp['RegisterNow'].'" />
		<input name="cancel" id="cancel" type="button" value="'.$hc_lang_rsvp['Cancel'].'" onclick="window.location.href=\''.CalRoot.'/index.php?eID='.$eID.'\';return false;" />
		</form>';
	}
	/**
	 * Output Submission Unique JavaScript validation.
	 * @since 2.0.0
	 * @version 2.2.0
	 * @return void
	 */
	function submit_valid(){
		global $eID, $hc_cfg, $hc_lang_submit, $hc_lang_core, $hc_time;
		
		echo '
	var recOpts = new Array("daily","weekly","monthly");
	
	function togRecur(){
		var inputs = (document.getElementById(\'recurCheck\').checked) ? false : true
		document.getElementById("recurType1").disabled = inputs;
		document.getElementById("recurType2").disabled = inputs;
		document.getElementById("recurType3").disabled = inputs;
		document.getElementById("recWeeklyDay_0").disabled = inputs;
		document.getElementById("recWeeklyDay_1").disabled = inputs;
		document.getElementById("recWeeklyDay_2").disabled = inputs;
		document.getElementById("recWeeklyDay_3").disabled = inputs;
		document.getElementById("recWeeklyDay_4").disabled = inputs;
		document.getElementById("recWeeklyDay_5").disabled = inputs;
		document.getElementById("recWeeklyDay_6").disabled = inputs;
		document.getElementById("recDaily1").disabled = inputs;
		document.getElementById("recDaily2").disabled = inputs;
		document.getElementById("dailyDays").disabled = inputs;
		document.getElementById("recWeekly").disabled = inputs;
		document.getElementById("monthlyOption1").disabled = inputs;
		document.getElementById("monthlyOption2").disabled = inputs;
		document.getElementById("monthlyDays").disabled = inputs;
		document.getElementById("recurEndDate").disabled = inputs;
		document.getElementById("monthlyMonths").disabled = inputs;
		document.getElementById("monthlyMonthDOW").disabled = inputs;
		document.getElementById("monthlyMonthRepeat").disabled = inputs;
		document.getElementById("monthlyMonthOrder").disabled = inputs;
	}
	function validate(){
		var err = "";';
		captchaValidation('1');
		
		echo '
		err +=reqField(document.getElementById("submitName"),"'.$hc_lang_submit['Valid15'].'\n");
		err +=reqField(document.getElementById("submitEmail"),"'.$hc_lang_submit['Valid16'].'\n");
		if(document.getElementById("submitEmail").value != "")
			err +=validEmail(document.getElementById("submitEmail"),"'.$hc_lang_submit['Valid17'].'\n");
		err +=reqField(document.getElementById("eventTitle"),"'.$hc_lang_submit['Valid18'].'\n");
		
		try{
			err +=chkTinyMCE(tinyMCE.get("eventDescription").getContent(),"'.$hc_lang_submit['Valid02'].'\n");}
		catch(error){
			err +=reqField(document.getElementById("eventDescription"),"'.$hc_lang_submit['Valid02'].'\n");}
		
		if(document.getElementById("rsvp_type").value == 1){
			err +=reqField(document.getElementById("rsvp_space"),"'.$hc_lang_submit['Valid04'].'\n");
			err +=validNumber(document.getElementById("rsvp_space"),"'.$hc_lang_submit['Valid04'].'\n");
			err +=validGreater(document.getElementById("rsvp_space"),-1,"'.$hc_lang_submit['Valid03'].'\n");
			err +=reqField(document.getElementById("openDate"),"'.$hc_lang_submit['Valid52'].'\n");
			err +=reqField(document.getElementById("closeDate"),"'.$hc_lang_submit['Valid53'].'\n");				
			if(document.getElementById("openDate").value != ""){
				err +=validDate(document.getElementById("openDate"),"'.$hc_cfg[51].'","'.$hc_lang_submit['Valid54'].' '.strtoupper($hc_cfg[51]).'\n");
				err +=validDateBefore(document.getElementById("openDate").value,document.getElementById("eventDate").value,"'.$hc_cfg[51].'","'.$hc_lang_submit['Valid55'].'\n")
			}
			if(document.getElementById("closeDate").value != ""){
				var closeLimit = document.getElementById("recurCheck").checked ? document.getElementById("recurEndDate").value : document.getElementById("eventDate").value;
				err +=validDate(document.getElementById("closeDate"),"'.$hc_cfg[51].'","'.$hc_lang_submit['Valid56'].' '.strtoupper($hc_cfg[51]).'\n");
				err +=validDateBefore(document.getElementById("closeDate").value,closeLimit,"'.$hc_cfg[51].'","'.$hc_lang_submit['Valid57'].'\n")
			}
			err +=validDateBefore(document.getElementById("openDate").value,document.getElementById("closeDate").value,"'.$hc_cfg[51].'","'.$hc_lang_submit['Valid58'].'\n")
			err +=reqField(document.getElementById("contactName"),"'.$hc_lang_submit['Valid05'].'\n");
			err +=reqField(document.getElementById("contactEmail"),"'.$hc_lang_submit['Valid06'].'\n");
		}
		
		err +=chkDate();
		
		err +=validNumber(document.getElementById("startTimeHour"),"'.$hc_lang_submit['Valid07'].'\n");
		err +=validNumberRange(document.getElementById("startTimeHour"),'.$hc_time['minHr'].','.$hc_time['input'].',"'.$hc_lang_submit['Valid08']." ".$hc_time['minHr']." - ".$hc_time['input'].'\n");
		err +=reqField(document.getElementById("startTimeMins"),"'.$hc_lang_submit['Valid10'].'\n");
		err +=validNumber(document.getElementById("startTimeMins"),"'.$hc_lang_submit['Valid09'].'\n");
		err +=validNumberRange(document.getElementById("startTimeMins"),0,59,"'.$hc_lang_submit['Valid10'].'\n");
		err +=validNumber(document.getElementById("endTimeHour"),"'.$hc_lang_submit['Valid11'].'\n");
		err +=validNumberRange(document.getElementById("endTimeHour"),'.$hc_time['minHr'].','.$hc_time['input'].',"'.$hc_lang_submit['Valid12']." ".$hc_time['minHr']." - ".$hc_time['input'].'\n");
		err +=reqField(document.getElementById("endTimeMins"),"'.$hc_lang_submit['Valid14'].'\n");
		err +=validNumber(document.getElementById("endTimeMins"),"'.$hc_lang_submit['Valid13'].'\n");
		err +=validNumberRange(document.getElementById("endTimeMins"),0,59,"'.$hc_lang_submit['Valid14'].'\n");
		
		if(document.getElementById("recurCheck").checked)
			err +=chkRecur();
		
		'.(($hc_cfg[29] == 1) ? 'err +=validCheckArray("frmEventSubmit","catID[]",1,"'.$hc_lang_submit['Valid51'].'\n");':'').'
			
		if(document.getElementById("locPreset").value == 0)
			err +=reqField(document.getElementById("locName"),"'.$hc_lang_submit['Valid19'].'\n");
		if(document.getElementById("contactEmail").value != "")
			err +=validEmail(document.getElementById("contactEmail"),"'.$hc_lang_submit['Valid21'].'\n");
		if(err != ""){
			alert(err);
			return false;
		} else {
			valid_ok(document.getElementById("submit"),"'.$hc_lang_core['Sending'].'");
			return true;
		}
	}
	function chkRecur(){
		var err = "";
		err += reqField(document.getElementById("recurEndDate"),"'.$hc_lang_submit['Valid25'].'\n");
		if(document.getElementById("recurEndDate").value != ""){
			err += validDate(document.getElementById("recurEndDate"),"'.$hc_cfg[51].'","'.$hc_lang_submit['Valid26'].' '.strtoupper($hc_cfg[51]).'\n");
			err += validDateBefore(document.getElementById("eventDate").value,document.getElementById("recurEndDate").value,"'.$hc_cfg[51].'","'.$hc_lang_submit['Valid27'].'\n");
			err += validNotEqual(document.getElementById("recurEndDate"),document.getElementById("eventDate"),"'.$hc_lang_submit['Valid28'].'\n");
		}
		if(document.getElementById("recurType1").checked){
			if(document.getElementById("recDaily1").checked){
				err += reqField(document.getElementById("dailyDays"),"'.$hc_lang_submit['Valid31'].'\n");
				err += validNumber(document.getElementById("dailyDays"),"'.$hc_lang_submit['Valid29'].'\n");
				err += validGreater(document.getElementById("dailyDays"),0,"'.$hc_lang_submit['Valid30'].'\n");
			}
		} else if(document.getElementById("recurType2").checked) {
			err += reqField(document.getElementById("recWeekly"),"'.$hc_lang_submit['Valid34'].'\n");
			err += validNumber(document.getElementById("recWeekly"),"'.$hc_lang_submit['Valid32'].'\n");
			err += validGreater(document.getElementById("recWeekly"),0,"'.$hc_lang_submit['Valid33'].'\n");
			err += validCheckArray("frmEventSubmit","recWeeklyDay[]",1,"'.$hc_lang_submit['Valid35'].'\n");
		} else if(document.getElementById("recurType3").checked) {
			if(document.getElementById("monthlyOption1").checked){
				err += reqField(document.getElementById("monthlyDays"),"'.$hc_lang_submit['Valid38'].'\n");
				err += validNumber(document.getElementById("monthlyDays"),"'.$hc_lang_submit['Valid36'].'\n");
				err += validGreater(document.getElementById("monthlyDays"),0,"'.$hc_lang_submit['Valid37'].'\n");
				err += reqField(document.getElementById("monthlyMonths"),"'.$hc_lang_submit['Valid41'].'\n");
				err += validNumber(document.getElementById("monthlyMonths"),"'.$hc_lang_submit['Valid39'].'\n");
				err += validGreater(document.getElementById("monthlyMonths"),0,"'.$hc_lang_submit['Valid40'].'\n");
			} else if(document.getElementById("monthlyOption2").checked){
				err += reqField(document.getElementById("monthlyMonthRepeat"),"'.$hc_lang_submit['Valid44'].'\n");
				err += validNumber(document.getElementById("monthlyMonthRepeat"),"'.$hc_lang_submit['Valid42'].'\n");
				err += validGreater(document.getElementById("monthlyMonthRepeat"),0,"'.$hc_lang_submit['Valid43'].'\n");
			}
		}
		return err;
	}
	function confirmRecurDates(){
		if(!document.getElementById("recurCheck").checked){
			alert("'.$hc_lang_submit['Valid47'].'");
			return false;}
		
		var err = "";	
		err += chkDate();
		err += chkRecur();
		
		if(err != ""){
			alert(err + "\n\n'.$hc_lang_submit['Valid47'].'");
		} else {
			var qStr = "";
			if(document.getElementById("recurType1").checked){
				qStr = qStr + "?recurType=daily";
				if(document.getElementById("recDaily1").checked)
					qStr = qStr + "&dailyOptions=EveryXDays&dailyDays=" + document.getElementById("dailyDays").value;
				else
					qStr = qStr + "&dailyOptions=WeekdaysOnly";
			} else if(document.getElementById("recurType2").checked) {
				var dArr = "";
				if(document.getElementById("recWeeklyDay_0").checked){dArr = dArr + ",0"}
				if(document.getElementById("recWeeklyDay_1").checked){dArr = dArr + ",1"}
				if(document.getElementById("recWeeklyDay_2").checked){dArr = dArr + ",2"}
				if(document.getElementById("recWeeklyDay_3").checked){dArr = dArr + ",3"}
				if(document.getElementById("recWeeklyDay_4").checked){dArr = dArr + ",4"}
				if(document.getElementById("recWeeklyDay_5").checked){dArr = dArr + ",5"}
				if(document.getElementById("recWeeklyDay_6").checked){dArr = dArr + ",6"}
				qStr = qStr + "?recurType=weekly&recWeekly=" + document.getElementById("recWeekly").value + "&recWeeklyDay=" + dArr.substring(1);
			} else if(document.getElementById("recurType3").checked) {
				qStr = qStr + "?recurType=monthly";
				if(document.getElementById("monthlyOption1").checked)
					qStr = qStr + "&monthlyOption=Day&monthlyDays=" + document.getElementById("monthlyDays").value + "&monthlyMonths=" + document.getElementById("monthlyMonths").value;
				else
					qStr = qStr + "&monthlyOption=Month&monthlyMonthOrder=" + document.getElementById("monthlyMonthOrder").value + "&monthlyMonthDOW=" + document.getElementById("monthlyMonthDOW").value + "&monthlyMonthRepeat=" + document.getElementById("monthlyMonthRepeat").value;
			}

			qStr = qStr + "&eventDate=" + document.getElementById("eventDate").value + "&recurEndDate=" + document.getElementById("recurEndDate").value;

			ajxOutput("'.CalRoot.'/event-recur-confirm.php" + qStr,"recur_chk","'.CalRoot.'");
		}
	}';
	}
	/**
	 * Output Event Submission Form
	 * @since 2.0.0
	 * @version 2.2.1
	 * @return void
	 */
	function submit(){
		global $hc_cfg, $hc_captchas, $hc_lang_submit, $hc_lang_config, $hc_lang_core, $hc_time;
		
		if(isset($_GET['msg'])){
			switch(cIn(strip_tags($_GET['msg']))){
				case 1 :
					feedback(1, $hc_lang_submit['Feed01']);
					echo '
		<p>' . $hc_lang_submit['ThankYou'] . '</p>
		<p><a href="' . CalRoot . '/index.php?com=submit">' . $hc_lang_submit['ClickSubmitAgain'] . '</a></p>
		<p><a href="' . CalRoot . '/">' . $hc_lang_submit['ClickToBrowse'] . '</a></p>';
					return -1;
					break;
			}
		}
		echo '
		<p>' . $hc_lang_submit['Notice'] . '</p>
		<p>(<span class="req2">*</span>) = '.$hc_lang_submit['Required2'].'<br />
		(<span class="req3">*</span>) = '.$hc_lang_submit['Required3'].'</p>
		
		<form id="frmEventSubmit" name="frmEventSubmit" method="post" action="'.CalRoot.'/event-submit.php" onsubmit="return validate();">';
		
		if($hc_cfg[65] > 0 && in_array(1, $hc_captchas)){
			echo '
		<fieldset>
			<legend>' . $hc_lang_core['CapLegend'] . '</legend>';
			buildCaptcha();
		echo '
		</fieldset>';}
		
		$user_id = 0;
		$user_net = $user_name = $user_email = $user_categories = '';
		if(user_check_status()){
			$resultU = doQuery("SELECT PkID, NetworkType, NetworkName, Email, Categories FROM " . HC_TblPrefix . "users WHERE PkID = '".cIn($_SESSION['UserPkID'])."'");
			if(hasRows($resultU)){
				$user_id = cOut(mysql_result($resultU,0,0));
				$user_net = cOut(mysql_result($resultU,0,1));
				$user_name = cOut(mysql_result($resultU,0,2));
				$user_email = cOut(mysql_result($resultU,0,3));
				$user_categories = cOut(mysql_result($resultU,0,4));
				
				switch($user_net){
					case 1:
						$user_net = 'twitter.png';
						break;
					case 2:
						$user_net = 'facebook.png';
						break;
					case 3:
						$user_net = 'google.png';
						break;
				}
			}
		}
		
		$si_notice = (($hc_cfg[113]+$hc_cfg[114]+$hc_cfg[115]) > 0 && !user_check_status()) ? '
			<label>&nbsp;</label>
			<p>'.$hc_lang_submit['SignInNotice'].'</p>' : '';
		
		echo (($user_id > 0 && $user_net != '' && $user_name != '' && $user_email != '') ? '
		<fieldset>
			<legend>'.$hc_lang_submit['ContactInfo'].'</legend>
			<label for="submitName">'.$hc_lang_submit['Name'].'</label>
			<span class="output submit_user">
				<img src="'.CalRoot.'/img/share/'.$user_net.'" width="16" height="16" alt="" /> '.$user_name.'
				<input name="submitName" id="submitName" type="hidden" value="'.$user_name.'" />
			</span>
			<label for="submitEmail">'.$hc_lang_submit['Email'].'</label>
			<span class="output submit_user">
				'.$user_email.'
				<input name="submitEmail" id="submitEmail" type="hidden" value="'.$user_email.'" />
			</span>
			<input name="submitID" id="submitID" type="hidden" value="'.$user_id.'" />
		</fieldset>' : '
		<fieldset>
			<legend>'.$hc_lang_submit['ContactInfo'].'</legend>
			'.$si_notice.'
			<label for="submitName">'.$hc_lang_submit['Name'].'</label>
			<input name="submitName" id="submitName" type="text" size="25" maxlength="50" required="required" placeholder="'.$hc_lang_submit['PlaceSubName'].'" value="" />
			<label for="submitEmail">'.$hc_lang_submit['Email'].'</label>
			<input name="submitEmail" id="submitEmail" type="email" size="35" maxlength="75" required="required" placeholder="'.$hc_lang_submit['PlaceSubEmail'].'" value="" />
			<input name="submitID" id="submitID" type="hidden" value="0" />
		</fieldset>');
		
		echo '
		<fieldset>
			<legend>'.$hc_lang_submit['EventDetails'].'</legend>
			<label for="eventTitle">'.$hc_lang_submit['Title'].'</label>
			<input name="eventTitle" id="eventTitle" type="text" size="65" maxlength="150" required="required" placeholder="'.$hc_lang_submit['PlaceTitle'].'" value="" />
			<label for="eventDescription">'.$hc_lang_submit['Description'].'</label>
			<textarea name="eventDescription" id="eventDescription" rows="20" placeholder="'.$hc_lang_submit['PlaceDesc'].'" class="mce_edit"></textarea>
			<label for="cost">'.$hc_lang_submit['Cost'].'</label>
			<input name="cost" id="cost" type="text" size="25" maxlength="50" placeholder="'.$hc_lang_submit['PlaceCost'].'" value="" />
		</fieldset>
		<fieldset>
			<legend>'.$hc_lang_submit['DateTime'].'</legend>
			<label for="eventDate">'.$hc_lang_submit['EventDate'].'</label>
			<input name="eventDate" id="eventDate" type="text" size="12" maxlength="10" required="required" value="'.strftime($hc_cfg[24],strtotime(SYSDATE)).'" />
			<a href="javascript:;" onclick="calx.select(document.getElementById(\'eventDate\'),\'cal1\',\''.$hc_cfg[51].'\');return false;" id="cal1" class="ds calendar" tabindex="-1"></a>
			<label>'.$hc_lang_submit['StartTime'].'</label>
			<input name="startTimeHour" id="startTimeHour" type="text" size="2" maxlength="2" required="required" value="'.date($hc_time['format'],strtotime(SYSDATE.' '.SYSTIME)).'" />
			<span class="frm_ctrls">
				<a href="javascript:;" onclick="chngClock(document.getElementById(\'startTimeHour\'),1,'.$hc_time['input'].')" class="time tup" tabindex="-1"></a>
				<a href="javascript:;" onclick="chngClock(document.getElementById(\'startTimeHour\'),-1,'.$hc_time['input'].')" class="time tdown" tabindex="-1"></a>
			</span>
			<input name="startTimeMins" id="startTimeMins" type="text" size="2" maxlength="2" required="required" value="00" />
			<span class="frm_ctrls">	
				<a href="javascript:;" onclick="chngClock(document.getElementById(\'startTimeMins\'),5,59)" class="time tup" tabindex="-1"></a>
				<a href="javascript:;" onclick="chngClock(document.getElementById(\'startTimeMins\'),-5,59)" class="time tdown" tabindex="-1"></a>
			</span>';
			if($hc_time['input'] == 12){
				echo '
			<select name="startTimeAMPM" id="startTimeAMPM">
				<option '.((date("A") == 'AM') ? 'selected="selected" ' : '').'value="AM">'.$hc_lang_submit['AM'].'</option>
				<option '.((date("A") == 'PM') ? 'selected="selected" ' : '').'value="PM">'.$hc_lang_submit['PM'].'</option>
			</select>';}
			echo '
			<label>'.$hc_lang_submit['EndTime'].'</label>
			<input name="endTimeHour" id="endTimeHour" type="text" size="2" maxlength="2" value="'.date($hc_time['format'],strtotime(SYSDATE.' '.SYSTIME." +1 hour")).'" required="required" />
			<span class="frm_ctrls">	
				<a href="javascript:;" onclick="chngClock(document.getElementById(\'endTimeHour\'),1,'.$hc_time['input'].')" class="time tup" tabindex="-1"></a>
				<a href="javascript:;" onclick="chngClock(document.getElementById(\'endTimeHour\'),-1,'.$hc_time['input'].')" class="time tdown" tabindex="-1"></a>
			</span>
			<input name="endTimeMins" id="endTimeMins" type="text" size="2" maxlength="2" value="00" required="required" />
			<span class="frm_ctrls">
				<a href="javascript:;" onclick="chngClock(document.getElementById(\'endTimeMins\'),5,59)" class="time tup" tabindex="-1"></a>
				<a href="javascript:;" onclick="chngClock(document.getElementById(\'endTimeMins\'),-5,59)" class="time tdown" tabindex="-1"></a>
			</span>';
			if($hc_time['input'] == 12){
				echo '
			<select name="endTimeAMPM" id="endTimeAMPM">
				<option '.((date("A") == 'AM') ? 'selected="selected" ' : '').'value="AM">'.$hc_lang_submit['AM'].'</option>
				<option '.((date("A") == 'PM') ? 'selected="selected" ' : '').'value="PM">'.$hc_lang_submit['PM'].'</option>
			</select>';}
			echo '
			<span class="frm_ctrls">
				<label for="ignoreendtime"><input name="ignoreendtime" id="ignoreendtime" type="checkbox" onclick="togEndTime(this.checked);" />'.$hc_lang_submit['NoEndTime'].'</label>
			</span>
			<label class="blank">&nbsp;</label>
			<span class="frm_ctrls">
				<label for="overridetime"><input type="checkbox" name="overridetime" id="overridetime" onclick="togOverride();" />'.$hc_lang_submit['Override'].'</label>
				<label for="specialtimeall"><input disabled="disabled" type="radio" name="specialtime" id="specialtimeall" value="allday" checked="checked" />'.$hc_lang_submit['AllDay'].'</label>
			</span>
			<label>'.$hc_lang_submit['Recur'].'</label>
			<span class="frm_ctrls">
				<label for="recurCheck"><input name="recurCheck" id="recurCheck" type="checkbox" onclick="togRecur();toggleMe(document.getElementById(\'recur_inpts\'));" /> '.$hc_lang_submit['RecurCheck'].'</label>
			</span>
			<div id="recur_inpts" style="display:none;">
				<label class="blank">&nbsp;</label>
				<span class="frm_ctrls">
					<label for="recurType1"><input name="recurType" id="recurType1" type="radio" value="daily" disabled="disabled" checked="checked" onclick="togArray(recOpts,\'daily\')" />'.$hc_lang_submit['RecDaily'].'</label>
					<label for="recurType2"><input name="recurType" id="recurType2" type="radio" value="weekly" disabled="disabled" onclick="togArray(recOpts,\'weekly\')" />'.$hc_lang_submit['RecWeekly'].'</label>
					<label for="recurType3"><input name="recurType" id="recurType3" type="radio" value="monthly" disabled="disabled" onclick="togArray(recOpts,\'monthly\')" />'.$hc_lang_submit['RecMonthly'].'</label>
				</span>
				<div id="daily" class="frm_ctrls">
					<label for="recDaily1"><input name="dailyOptions" id="recDaily1" type="radio" checked="checked" disabled="disabled" value="EveryXDays" />'.$hc_lang_submit['Every'].'</label><input id="dailyDays" name="dailyDays" type="number" min="1" max="31" size="3" maxlength="2" value="1" disabled="disabled" />'.$hc_lang_submit['xDays'].'<br />
					<label for="recDaily2"><input name="dailyOptions" id="recDaily2" type="radio" disabled="disabled" value="WeekdaysOnly" />'.$hc_lang_submit['Daily2'].'</label>
				</div>
				<div id="weekly" class="frm_ctrls" style="display:none;">
					'.$hc_lang_submit['Every'].'<input name="recWeekly" id="recWeekly" type="number" min="1" max="52" size="3" maxlength="2" value="1" />'.$hc_lang_submit['xWeeks'].'<br />
					<label for="recWeeklyDay_0"><input id="recWeeklyDay_0" name="recWeeklyDay[]" type="checkbox" value="0" />'.$hc_lang_submit['Sun'].'</label>
					<label for="recWeeklyDay_1"><input id="recWeeklyDay_1" name="recWeeklyDay[]" type="checkbox" value="1" />'.$hc_lang_submit['Mon'].'</label>
					<label for="recWeeklyDay_2"><input id="recWeeklyDay_2" name="recWeeklyDay[]" type="checkbox" value="2" />'.$hc_lang_submit['Tue'].'</label>
					<label for="recWeeklyDay_3"><input id="recWeeklyDay_3" name="recWeeklyDay[]" type="checkbox" value="3" />'.$hc_lang_submit['Wed'].'</label>
					<label for="recWeeklyDay_4"><input id="recWeeklyDay_4" name="recWeeklyDay[]" type="checkbox" value="4" />'.$hc_lang_submit['Thu'].'</label>
					<label for="recWeeklyDay_5"><input id="recWeeklyDay_5" name="recWeeklyDay[]" type="checkbox" value="5" />'.$hc_lang_submit['Fri'].'</label>
					<label for="recWeeklyDay_6"><input id="recWeeklyDay_6" name="recWeeklyDay[]" type="checkbox" value="6" />'.$hc_lang_submit['Sat'].'</label>
				</div>
				<div id="monthly" class="frm_ctrls" style="display:none;">
					<input name="monthlyOption" id="monthlyOption1" type="radio" checked="checked" value="Day" />'.$hc_lang_submit['Day'].'<input name="monthlyDays" id="monthlyDays" type="number" min="1" max="31" size="3" maxlength="2" value="'.date("d").'" />'.$hc_lang_submit['ofEvery'].'<input name="monthlyMonths" id="monthlyMonths" type="number" min="1" max="12" size="3" maxlength="2" value="1" />'.$hc_lang_submit['Months'].'
					<br />
					<span class="frm_ctrls">
						<input name="monthlyOption" id="monthlyOption2" type="radio" value="Month" />
						<select name="monthlyMonthOrder" id="monthlyMonthOrder">
							<option value="1">'.$hc_lang_submit['First'].'</option>
							<option value="2">'.$hc_lang_submit['Second'].'</option>
							<option value="3">'.$hc_lang_submit['Third'].'</option>
							<option value="4">'.$hc_lang_submit['Fourth'].'</option>
							<option value="0">'.$hc_lang_submit['Last'].'</option>
						</select>
						<select name="monthlyMonthDOW" id="monthlyMonthDOW">
							<option '.((date("w") == 0) ? 'selected="selected"' : '').'value="0">'.$hc_lang_submit['Sun'].'</option>
							<option '.((date("w") == 1) ? 'selected="selected"' : '').'value="1">'.$hc_lang_submit['Mon'].'</option>
							<option '.((date("w") == 2) ? 'selected="selected"' : '').'value="2">'.$hc_lang_submit['Tue'].'</option>
							<option '.((date("w") == 3) ? 'selected="selected"' : '').'value="3">'.$hc_lang_submit['Wed'].'</option>
							<option '.((date("w") == 4) ? 'selected="selected"' : '').'value="4">'.$hc_lang_submit['Thu'].'</option>
							<option '.((date("w") == 5) ? 'selected="selected"' : '').'value="5">'.$hc_lang_submit['Fri'].'</option>
							<option '.((date("w") == 6) ? 'selected="selected"' : '').'value="6">'.$hc_lang_submit['Sat'].'</option>
						</select>
						'.$hc_lang_submit['ofEvery'].'<input name="monthlyMonthRepeat" id="monthlyMonthRepeat" type="number" min="1" max="12" size="3" maxlength="2" value="1" />'.$hc_lang_submit['Months'].'
					</span>
				</div>
				<label for="recurEndDate">'.$hc_lang_submit['RecurUntil'].'</label>
				<input name="recurEndDate" id="recurEndDate" type="text" disabled="disabled" size="10" maxlength="10" required="required" value="" />
				<a href="javascript:;" onclick="calx.select(document.getElementById(\'recurEndDate\'),\'cal2\',\''.$hc_cfg[51].'\');return false;" id="cal2" class="ds calendar" tabindex="-1"></a>
				<label class="blank">&nbsp;</label>
				<div id="recur_chk">
					<a href="javascript:;" onclick="confirmRecurDates();">'.$hc_lang_submit['ConfirmDate'].'</a>
				</div>
			</div>
		</fieldset>';
		
		if($hc_cfg['IsRSVP'] == 1){
			echo '
		<fieldset>
			<legend>'.$hc_lang_submit['RegTitle'].'</legend>
			<label for="rsvp_type">'.$hc_lang_submit['Registration'].'</label>
			<select name="rsvp_type" id="rsvp_type" onchange="togRegistration();">
				<option value="0">'.$hc_lang_submit['Reg0'].'</option>
				<option value="1">'.$hc_lang_submit['Reg1'].'</option>
			</select>
			<div id="rsvp" style="display:none;">
				<label for="rsvp_space">'.$hc_lang_submit['Limit'].'</label>
				<input name="rsvp_space" id="rsvp_space" type="number" min="0" max="9999" size="5" maxlength="4" value="0" disabled="disabled" required="required" />
				<span class="output">'.$hc_lang_submit['LimitLabel'].'</span>
				<label>'.$hc_lang_submit['Allow'].'</label>
				<input name="openDate" id="openDate" type="text" size="12" maxlength="10" value="'.strftime($hc_cfg[24],strtotime(SYSDATE)).'" disabled="disabled" required="required" />
				<a href="javascript:;" onclick="calx.select(document.getElementById(\'openDate\'),\'cal3\',\''.$hc_cfg[51].'\');return false;" id="cal3" class="ds calendar" tabindex="-1"></a>
				<span class="output">&nbsp;&nbsp;'.$hc_lang_submit['To'].'&nbsp;&nbsp;</span>
				<input name="closeDate" id="closeDate" type="text" size="12" maxlength="10" value="'.strftime($hc_cfg[24],strtotime(SYSDATE)).'" disabled="disabled" required="required" />
				<a href="javascript:;" onclick="calx.select(document.getElementById(\'closeDate\'),\'cal4\',\''.$hc_cfg[51].'\');return false;" id="cal4" class="ds calendar" tabindex="-1"></a>
				'.((isset($_SESSION['UserLoggedIn']) && $_SESSION['UserLoggedIn'] == 1) ? '
				<label for="rsvpEmail">'.$hc_lang_submit['EmailNotice'].'</label>
				<select name="rsvpEmail" id="rsvpEmail">
					<option value="0">'.$hc_lang_submit['EmailNotice0'].'</option>
					<option value="1">'.$hc_lang_submit['EmailNotice1'].'</option>
				</select>
				<label>&nbsp;</label><span class="output onote">'.$hc_lang_submit['RSVPDownload'].'</span>':'<input type="hidden" name="rsvpEmail" id="rsvpEmail" value="1" /><label>&nbsp;</label><span class="output onote">'.$hc_lang_submit['RSVPDownloadNo'].'</span>').'
			</div>
		</fieldset>';
		} else {
			echo '
		<input type="hidden" name="rsvp_type" id="rsvp_type" value="0" />';}
		if($hc_cfg[29] == 1){
		echo '
		<fieldset>
			<legend>'.$hc_lang_submit['EventCat'].'</legend>
			<label>'.$hc_lang_submit['Categories'].'</label>';
		
			$query = (($user_categories != '') ? "SELECT c.PkID, c.CategoryName, c.ParentID, c.CategoryName as Sort, NULL as Selected
					FROM " . HC_TblPrefix . "categories c 
						LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (c.PkID = ec.CategoryID)
					WHERE c.ParentID = 0 AND c.IsActive = 1 AND c.PkID IN (".$user_categories.")
					GROUP BY c.PkID, c.CategoryName, c.ParentID
					UNION SELECT c.PkID, c.CategoryName, c.ParentID, c2.CategoryName as Sort, NULL as Selected
					FROM " . HC_TblPrefix . "categories c 
						LEFT JOIN " . HC_TblPrefix . "categories c2 ON (c.ParentID = c2.PkID) 
						LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (c.PkID = ec.CategoryID) 
					WHERE c.ParentID > 0 AND c.IsActive = 1 AND c.PkID IN (".$user_categories.")
					GROUP BY c.PkID, c.CategoryName, c.ParentID, c2.CategoryName
					ORDER BY Sort, ParentID, CategoryName" : NULL);
			
			getCategories('frmEventSubmit',$hc_cfg['CatCols'],$query,$hc_cfg['CatLinks']);
		echo '
		</fieldset>';}
		echo '
		<fieldset>
			<legend>'.$hc_lang_submit['LocationLabel'].'</legend>
			<input type="hidden" id="locPreset" name="locPreset" value="0" />
			<input type="hidden" id="locPresetName" name="locPresetName" value="" />';
			location_select();
		echo '
			<div id="custom_notice" style="display:none;">
				<label class="blank">&nbsp;</label>
				<b>'.$hc_lang_core['PresetLoc'].'</b>
			</div>
			<div id="custom">
				<label for="locName">'.$hc_lang_submit['Name'].'</label>
				<input name="locName" id="locName" type="text" size="25" maxlength="50" required="required" placeholder="'.$hc_lang_submit['PlaceLocName'].'" value="" />
				<label for="locAddress">'.$hc_lang_submit['Address'].'</label>
				<input name="locAddress" id="locAddress" type="text" size="30" maxlength="75" placeholder="'.$hc_lang_submit['PlaceLocAddress'].'" value="" /><span class="output req2">*</span>
				<label for="locAddress2">'.$hc_lang_submit['Address2'].'</label>
				<input name="locAddress2" id="locAddress2" type="text" size="25" maxlength="75" placeholder="'.$hc_lang_submit['PlaceLocAddress2'].'" value="" />';
			$inputs = array(1 => array('City','locCity','PlaceLocCity'),2 => array('Postal','locZip','PlaceLocPostal'));
			$first = ($hc_lang_config['AddressType'] == 1) ? 1 : 2;
			$second = ($first == 1) ? 2 : 1;
			echo '
				<label for="' . $inputs[$first][1] . '">' . $hc_lang_submit[$inputs[$first][0]] . '</label>
				<input name="' . $inputs[$first][1] . '" id="' . $inputs[$first][1] . '" type="text" size="20" maxlength="50" placeholder="'.$hc_lang_submit[$inputs[$first][2]].'" value="" /><span class="output req2">*</span>';
			if($hc_lang_config['AddressRegion'] != 0){
				echo '<div class="frmOpt">';
				echo '<label for="locState">' . $hc_lang_config['RegionLabel'] . '</label>';
				$regSelect = $hc_lang_submit['PlaceLocRegion'];
				$state = $hc_cfg[21];
				include(HCLANG.'/'.$hc_lang_config['RegionFile']);
				echo '<span class="output req2">*</span></div>';}
			
			echo '<label for="'.$inputs[$second][1].'">'.$hc_lang_submit[$inputs[$second][0]].'</label>
				<input name="'.$inputs[$second][1].'" id="'.$inputs[$second][1].'" type="text" size="20" maxlength="50" placeholder="'.$hc_lang_submit[$inputs[$second][2]].'" value="" /><span class="output req2">*</span>
				<label for="locCountry">'.$hc_lang_submit['Country'].'</label>
				<input name="locCountry" id="locCountry" type="text" size="10" maxlength="50" placeholder="'.$hc_lang_submit['PlaceLocCountry'].'" value="" />
			</div>
		</fieldset>
		<fieldset>
			<legend>'.$hc_lang_submit['ContactLabel'].'</legend>
			<label for="contactName">'.$hc_lang_submit['Name'].'</label>
			<input name="contactName" id="contactName" type="text" size="20" maxlength="50" placeholder="'.$hc_lang_submit['PlaceContactName'].'" value="" /><span class="output req3">*</span>
			<label for="contactEmail">'.$hc_lang_submit['Email'].'</label>
			<input name="contactEmail" id="contactEmail" type="text" size="30" maxlength="75" placeholder="'.$hc_lang_submit['PlaceContactEmail'].'" value="" /><span class="output req3">*</span>
			<label for="contactPhone">'.$hc_lang_submit['Phone'].'</label>
			<input name="contactPhone" id="contactPhone" type="tel" size="20" maxlength="25" placeholder="'.$hc_lang_submit['PlaceContactPhone'].'" value="" />
			<label for="contactURL">'.$hc_lang_submit['Website'].'</label>
			<input name="contactURL" id="contactURL" type="url" maxlength="100" placeholder="'.$hc_lang_submit['PlaceContactURL'].'" value="" />
		</fieldset>
		<fieldset>
			<legend>'.$hc_lang_submit['MessageLabel'].'</legend>
			<label for="goadminmessage">'.$hc_lang_submit['Include'].'</label>
			<input name="goadminmessage" id="goadminmessage" type="checkbox" value="" onclick="togThis(this,document.getElementById(\'adminmessage\'));" />
			<label for="adminmessage">'.$hc_lang_submit['Message'].'</label>
			<textarea name="adminmessage" id="adminmessage" rows="7" disabled="disabled" required="required" placeholder="'.$hc_lang_submit['PlaceMsgAdmin'].'"></textarea>
		</fieldset>
		<input name="submit" id="submit" type="submit" value="'.$hc_lang_submit['SubmitEvent'].'" />
		</form>
		<div id="dsCal"></div>';
		makeTinyMCE('',0,0,'eventDescription');
	}
	/**
	 * Output Event Search JavaScript validation.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @return void
	 */
	function search_valid(){
		global $hc_cfg, $hc_lang_core, $hc_lang_search;
		
		echo '
	<script src="'.CalRoot.'/inc/javascript/validation.js"></script>
	<script src="'.CalRoot.'/inc/lang/'.$_SESSION['LangSet'].'/popCal.js"></script>
	<script src="'.CalRoot.'/inc/javascript/DateSelect.js"></script>
	<script>
	//<!--
	var calx = new CalendarPopup("dsCal");
	calx.showNavigationDropdowns();
	calx.setCssPrefix("hc_");
	calx.offsetX = -100;
	calx.offsetY = 25;
	var srchInpts = ["locPreset","city","locState","postal"];
	var srchDivs = ["location_div","city_div","region_div","postal_div"];
	function toggleMe(show){
		for(var i in srchDivs){
			document.getElementById(srchDivs[i]).style.display = (i == show) ? "block" : "none";
			document.getElementById(srchInpts[i]).disabled = (i == show) ? false : true;
		}
	}
	function validate() {
		var err = "";
		err +=reqField(document.getElementById("startDate"),"'.$hc_lang_search['Valid04'].'\n");
		err +=validDate(document.getElementById("startDate"),"'.$hc_cfg[51].'","'.$hc_lang_search['Valid02'].' '.strtoupper($hc_cfg[51]).'\n");
		err +=reqField(document.getElementById("endDate"),"'.$hc_lang_search['Valid05'].'\n");
		err +=validDate(document.getElementById("endDate"),"'.$hc_cfg[51].'","'.$hc_lang_search['Valid03'].' '.strtoupper($hc_cfg[51]).'\n");	
		err +=validDateBefore(document.getElementById("startDate").value,document.getElementById("endDate").value,"'.$hc_cfg[51].'","'.$hc_lang_search['Valid06'].'\n");';
	if($hc_cfg[11] == 0)
		echo '
		err +=validDateBefore("'.strftime($hc_cfg[24],strtotime(SYSDATE)).'",document.getElementById("startDate").value,"'.$hc_cfg[51].'","'.$hc_lang_search['Valid07'].'\n");';
	echo '
		if(document.getElementById("keyword").value.length > 0)
			err +=validMinLength(document.getElementById("keyword"),4,"'.$hc_lang_search['Valid10'].'\n");
		err +=validCheckArray("frmEventSearch","catID[]",1,"'.$hc_lang_search['Valid08'].'\n");
			
		if(err != ""){
			alert(err);
			return false;
		} else {
			return true;
		}
	}';
	$pub_only = 0;
	$evnt_only = 1;
	include_once(HCPATH.'/inc/javascript/locations.php');
	echo '
	//-->
	</script>';
	}
	/**
	 * Output Event Search Form
	 * @since 2.0.0
	 * @version 2.0.0
	 * @return void
	 */
	function search(){
		global $hc_cfg, $hc_lang_core, $hc_lang_search, $hc_lang_config;
		
		$region = ($hc_lang_config['AddressRegion'] != 0) ? ' | <a tabindex="-1" href="javascript:;" onclick="toggleMe(2)" class="legend">'.$hc_lang_config['RegionTitle'] . '</a>' : '';
		
		echo '
		<p>'.$hc_lang_search['SearchLabel'].'</p>
		
		<form name="frmEventSearch" id="frmEventSearch" method="post" action="'.CalRoot.'/index.php?com=searchresult" onsubmit="return validate();">
		<input type="hidden" id="locPreset" name="locPreset" value="0" />
		<input type="hidden" id="locPresetName" name="locPresetName" value="" />
		<fieldset>
			<legend>'.$hc_lang_search['DateRange'].'</legend>
			<label>'.$hc_lang_search['Dates'].'</label>
			<input name="startDate" id="startDate" type="text" size="12" maxlength="10" value="'.strftime($hc_cfg[24],strtotime(SYSDATE)).'" required="required" />
			<a href="javascript:;" onclick="calx.select(document.getElementById(\'startDate\'),\'cal1\',\''.$hc_cfg[51].'\');return false;" id="cal1" class="ds calendar" tabindex="-1"></a>
			<span class="output">&nbsp;&nbsp;'.$hc_lang_search['To'].'&nbsp;&nbsp;</span>
			<input name="endDate" id="endDate" type="text" size="12" maxlength="10" value="'.strftime($hc_cfg[24], strtotime(SYSDATE)+($hc_cfg[53]*86400)).'" required="required" />
			<a href="javascript:;" onclick="calx.select(document.getElementById(\'endDate\'),\'cal2\',\''.$hc_cfg[51].'\');return false;" id="cal2" class="ds calendar" tabindex="-1"></a>
		</fieldset>
		<fieldset>
			<legend>'.$hc_lang_search['KeywordLabel'].'</legend>
			<label for="keyword">'.$hc_lang_search['Keywords'].'</label>
			<input name="keyword" id="keyword" type="text" size="50" maxlength="50" placeholder="'.$hc_lang_search['PlaceKeywords'].'" value="" speech x-webkit-speech />
		</fieldset>
		<fieldset>
			<legend>
				<a tabindex="-1" href="javascript:;" onclick="toggleMe(0);" class="legend">' . $hc_lang_search['LocationLabel'] . '</a>
				| <a tabindex="-1" href="javascript:;" onclick="toggleMe(1);" class="legend">' . $hc_lang_search['CityLabel'] . '</a>
				'.$region.'
				| <a tabindex="-1" href="javascript:;" onclick="toggleMe(3)" class="legend">' . $hc_lang_search['PostalLabel'] . '</a>
			</legend>
			<div id="location_div">';
				location_select(false);
		echo '
			</div>
			<div id="city_div" style="display:none;">
				<label for="city">'.$hc_lang_search['City'].'</label>';
		$f = ($hc_cfg[11] == 1) ? 'a':'';
		if(!file_exists(HCPATH.'/cache/selCity'.$f.'.php'))
			buildCache(4,$hc_cfg[11]);
		include(HCPATH.'/cache/selCity'.$f.'.php');
		echo '
			</div>
			<div id="region_div" style="display:none;">
				<label for="locState">'.$hc_lang_config['RegionLabel'].'</label>';	
		$state = $hc_cfg[21];
		$regSelect = $hc_lang_search['RegSelect'];
		$stateDisabled = 1;
		include(HCLANG.'/'.$hc_lang_config['RegionFile']);
		echo '
			</div>
			<div id="postal_div" style="display:none;">
				<label for="postal">'.$hc_lang_search['Postal'].'</label>';
		if(!file_exists(HCPATH.'/cache/selPostal'.$f.'.php'))
			buildCache(5,$hc_cfg[11]);
		include(HCPATH.'/cache/selPostal'.$f.'.php');
		echo '
			</div>
		</fieldset>
		<fieldset>
			<legend>'.$hc_lang_search['CategoryLabel'].'</legend>
			<label>'.$hc_lang_search['Categories'].'</label>';
		getCategories('frmEventSearch', $hc_cfg['CatCols'],NULL,$hc_cfg['CatLinks']);
		echo '
		</fieldset>
		<fieldset>
			<legend>'.$hc_lang_search['RecurringLabel'].'</legend>
			<label for="recurSet">'.$hc_lang_search['RecurSet'].'</label>
			<select name="recurSet" id="recurSet">
				<option value="0">'.$hc_lang_search['RecurSet0'].'</option>
				<option value="1">'.$hc_lang_search['RecurSet1'].'</option>
			</select>
		</fieldset>
		<input type="submit" name="submit" id="submit" value="'.$hc_lang_search['BeginSearch'].'" />
		</form>
		<div id="dsCal"></div>';
	}
	/**
	 * Output Event Search Results
	 * @since 2.0.0
	 * @version 2.0.0
	 * @return void
	 */
	function search_result(){
		global $hc_cfg, $hc_lang_search;
		
		$sQuery = $rQuery = $link = $date = '';
		
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$startDate = (isset($_POST['startDate'])) ? strtotime(dateToMySQL(cIn(strip_tags($_POST['startDate'])), $hc_cfg[24])) : strtotime(SYSDATE);
			$endDate = (isset($_POST['endDate'])) ? strtotime(dateToMySQL(cIn(strip_tags($_POST['endDate'])), $hc_cfg[24])) : (strtotime(SYSDATE) + (86400 * $hc_cfg[53]));
			$keyword = (isset($_POST['keyword'])) ? strip_tags(str_replace("'","\"",$_POST['keyword'])) : '';
			$keyword .= (isset($_POST['hc_search_keyword'])) ? strip_tags(str_replace("'","\"",urldecode($_POST['hc_search_keyword']))) : '';
			$location = (isset($_POST['locPreset'])) ? cIn(strip_tags($_POST['locPreset'])) : '';
			$city = (isset($_POST['city'])) ? cIn(strip_tags($_POST['city'])) : '';
			$state = (isset($_POST['locState'])) ? cIn(strip_tags($_POST['locState'])) : '';
			$postal = (isset($_POST['postal'])) ? cIn(strip_tags($_POST['postal'])) : '';
			$catIDs = (isset($_POST['catID'])) ? implode(',',array_filter($_POST['catID'],'is_numeric')) : '';
			$doRecur = (isset($_POST['recurSet']) && $_POST['recurSet'] == 1) ? 1 : 0;
		} else {
			$startDate = (isset($_GET['s'])) ? cIn(strip_tags(urldecode($_GET['s']))) : strtotime(SYSDATE);
			$endDate = (isset($_GET['e'])) ? cIn(strip_tags(urldecode($_GET['e']))) : (strtotime(SYSDATE) + (86400 * $hc_cfg[53]));
			$keyword = (isset($_GET['k'])) ? strip_tags(str_replace("\"","'",html_entity_decode(urldecode($_GET['k'])))) : '';
			$location = (isset($_GET['l'])) ? cIn(strip_tags(urldecode(cIn($_GET['l'])))) : '';
			$city = (isset($_GET['c'])) ? cIn(strip_tags(urldecode($_GET['c']))) : '';
			$state = (isset($_GET['st'])) ? cIn(strip_tags(urldecode($_GET['st']))) : '';
			$postal = (isset($_GET['p'])) ? cIn(strip_tags(urldecode($_GET['p']))) : '';
			$catIDs = (isset($_GET['t'])) ? implode(',',array_filter(explode(',',urldecode($_GET['t'])),'is_numeric')) : '';
			$doRecur = (isset($_GET['r']) && $_GET['r'] == 1) ? 1 : 0;
		}
		if($keyword != ''){
			$sQuery .= " AND MATCH(e.Title,e.LocationName,e.Description) AGAINST('" . cIn($keyword,0) . "' IN BOOLEAN MODE) ";
			$link .= "&amp;k=" . urlencode(cleanXMLChars(cOut($keyword),1));
		}
		if(is_numeric($location) && $location > 0){
			$sQuery .= " AND l.PkID = '" . $location  . "'";
			$link .= "&amp;l=" . urlencode($location);
		}
		if($city != ''){
			$sQuery .= " AND (l.IsActive = 1 OR l.IsActive is NULL) AND (e.LocationCity = '" . cIn($city) . "' OR l.City = '" . cIn($city) . "')";
			$link .= "&amp;c=" . urlencode($city);
		}
		if($state != ''){
			$sQuery .= " AND (e.LocationState = '" . cIn($state) . "' or l.State = '" . cIn($state) . "')";
			$link .= "&amp;st=" . urlencode($state);
		}
		if($postal != ''){
			$sQuery .= " AND (e.LocationZip = '" . cIn($postal) . "' or l.Zip = '" . cIn($postal) . "')";
			$link .= "&amp;p=" . urlencode(cIn($postal));
		}
		if($catIDs != ''){
			$sQuery .= " AND (ec.CategoryID In(" . cIn($catIDs) . "))";
			$link .= "&amp;t=" . urlencode($catIDs);
		}
		if($doRecur == 1)
			$rQuery = " AND SeriesID IS NULL 
						UNION
						SELECT DISTINCT e.PkID, e.Title, e.StartDate, e.StartTime, e.EndTime, e.TBD
						FROM " . HC_TblPrefix . "events e
							LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (ec.EventID = e.PkID)
							LEFT JOIN " . HC_TblPrefix . "events e2 ON (e.SeriesID = e2.SeriesID AND e2.StartDate BETWEEN '" . date("Y-m-d",$startDate) . "' AND '" . date("Y-m-d",$endDate) . "' AND e.StartDate > e2.StartDate)
							LEFT JOIN " . HC_TblPrefix . "locations l ON (e.LocID = l.PkID)
						WHERE
							e2.StartDate IS NULL AND e.StartDate BETWEEN '" . date("Y-m-d",$startDate) . "' AND '" . date("Y-m-d",$endDate) . "'"
							.$sQuery." AND e.IsActive = 1 AND e.IsApproved = 1 AND e.SeriesID IS NOT NULL 
						GROUP BY e.SeriesID, e.PkID, e.Title, e.StartDate, e.StartTime, e.EndTime, e.TBD";
			
		$startDate = ($startDate == '' || !is_numeric($startDate)) ? strtotime(SYSDATE) : $startDate;
		$endDate = ($endDate == '' || !is_numeric($endDate)) ? strtotime(SYSDATE)+($hc_cfg[53]*86400) : $endDate;
		
		$result = doQuery("SELECT DISTINCT e.PkID, e.Title, e.StartDate, e.StartTime, e.EndTime, e.TBD
						FROM " . HC_TblPrefix . "events e
							LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (ec.EventID = e.PkID)
							LEFT JOIN " . HC_TblPrefix . "locations l ON (e.LocID = l.PkID)
						WHERE e.StartDate BETWEEN '" . date("Y-m-d",$startDate) . "' AND '" . date("Y-m-d",$endDate) . "'"
						.$sQuery." AND e.IsActive = 1 AND e.IsApproved = 1"
						.$rQuery." ORDER BY StartDate, TBD, StartTime, Title");
		if(!hasRows($result)){
			echo '
			<p>' . $hc_lang_search['NoResults'] . '</p>
			<p><a href="'.CalRoot.'/index.php?com=search">'.$hc_lang_search['SearchAgain'].'</a></p>';
			return 0;}
		
		echo '<p>'.$hc_lang_search['ResultLabel']. ' [<a href="'.CalRoot.'/index.php?com=searchresult&amp;r='.$doRecur."&amp;s=".urlencode($startDate)."&amp;e=".urlencode($endDate).$link.'">'.$hc_lang_search['ResultLink'].'</a>]';
		
		$cnt = 0;
		while($row = mysql_fetch_row($result)){
			if(($date != $row[2])){
				$date = $row[2];
				echo ($cnt > 0) ? '
			</ul>' : '';
				echo '
			<header>' . stampToDate($row[2], $hc_cfg[14]) . '</header>
			<ul>';
				$cnt = 1;
			}

			$hl = ($cnt % 2 == 0) ? ' class="hl"' : '';
			if($row[5] == 0){
				$time = ($row[3] != '') ? stampToDate($row[3], $hc_cfg[23]) : '';
				$time .= ($row[4] != '') ? ' - ' . stampToDate($row[4], $hc_cfg[23]) : '';
				$stamp = date("Y-m-d\Th:i:00.0",strtotime($row[2] . trim(' '.$row[3]))) . HCTZ;
			} else {
				$time = ($row[5] == 1) ? $hc_lang_search['AllDay'] : $hc_lang_search['TBA'];
				$stamp = date("Y-m-d",strtotime($row[2]));}
			echo '
			<li'.$hl.'><time datetime="'.$stamp.'">'.$time.'</time><a href="'.CalRoot . '/index.php?eID='.$row[0].'">'.cOut($row[1]).'</a></li>';
			++$cnt;
		}
		echo '</ul>';
	}
	/**
	 * Output Newsletter Subscription Edit JavaScript validation.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @return void
	 */
	function news_edit_valid(){
		global $hc_cfg, $hc_lang_core, $hc_lang_news;

		echo '
	<script src="'.CalRoot.'/inc/javascript/validation.js"></script>
	<script>
	//<!--
	function validate(){
		var err = "";';
		captchaValidation('4');

		echo '
		err +=reqField(document.getElementById("hc_fz"),"'.$hc_lang_news['Valid16'].'\n");
		if(document.getElementById("hc_fz").value != "")
			err +=validEmail(document.getElementById("hc_fz"),"'.$hc_lang_news['Valid17'].'\n");

		if(err != ""){
			alert(err);
			return false;
		} else {
			valid_ok(document.getElementById("submit"),"'.$hc_lang_core['Sending'].'");
			return true;
		}
	}';
	testCaptcha();
	echo '
	//-->
	</script>';
	}
	/**
	 * Output Newsletter Subscription Edit Form
	 * @since 2.0.0
	 * @version 2.0.0
	 * @return void
	 */
	function news_edit(){
		global $hc_cfg, $hc_lang_news, $hc_captchas, $hc_lang_core;
		
		if(isset($_GET['msg'])){
			switch ($_GET['msg']){
				case "1" :
					feedback(1,$hc_lang_news['Feed03']);
					break;
				case "2" :
					feedback(2,$hc_lang_news['Feed05']);
					break;
			}
		}
		
		echo '<p>' . $hc_lang_news['EditInstruct'] . '</p>
		
		<form name="frmEventNewsletter" id="frmEventNewsletter" method="post" action="' . CalRoot . '/news-edit.php" onsubmit="return validate();">';
		
		if($hc_cfg[65] > 0 && in_array(4, $hc_captchas)){
			echo '
		<fieldset>
			<legend>' . $hc_lang_core['CapLegend'] . '</legend>';
			buildCaptcha();
		echo '
		</fieldset>';
		}
		echo '
		<fieldset>
			<legend>' . $hc_lang_news['RequestLink'] . '</legend>
			<label for="hc_fz">' . $hc_lang_news['Email'] . '</label>
			<input name="hc_fz" id="hc_fz" type="email" size="45" maxlength="50" placeholder="'.$hc_lang_news['PlaceEmailEdit'].'" value="" required="required" />
		
			<label for="hc_fy">' . $hc_lang_news['IWant'] . '</label>
			<select name="hc_fy" id="hc_fy">
				<option value="0">' . $hc_lang_news['IWant0'] . '</option>
				<option value="1">' . $hc_lang_news['IWant1'] . '</option>
			</select>
			</fieldset>
		'.fakeFormFields().'
		<input type="submit" name="submit" id="submit" value="'.$hc_lang_news['SendEditReg'].'" />
		</form>';
	}
	/**
	 * Output Newsletter Subscription Registration JavaScript validation.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @return void
	 */
	function news_signup_valid(){
		global $hc_cfg, $hc_lang_core, $hc_lang_news;

		echo '
	<script src="'.CalRoot.'/inc/javascript/validation.js"></script>
	<script>
	//<!--
	function validate(){
		var err = "";';
		captchaValidation('4');

		echo '
		err +=reqField(document.getElementById("hc_f1"),"'.$hc_lang_news['Valid14'].'\n");
		err +=reqField(document.getElementById("hc_f2"),"'.$hc_lang_news['Valid15'].'\n");
		err +=reqField(document.getElementById("hc_f3"),"'.$hc_lang_news['Valid16'].'\n");
		if(document.getElementById("hc_f3").value != "")
			err +=validEmail(document.getElementById("hc_f3"),"'.$hc_lang_news['Valid17'].'\n");
		err +=validGreater(document.getElementById("hc_fa"),0,"'.$hc_lang_news['Valid20'].'\n");
		err +=validCheckArray("frmEventNewsletter","catID[]",1,"'.$hc_lang_news['Valid18'].'\n");

		if(err != ""){
			alert(err);
			return false;
		} else {
			valid_ok(document.getElementById("submit"),"'.$hc_lang_core['Sending'].'");
			return true;
		}
	}';
	testCaptcha();
	echo '
	//-->
	</script>';
	}
	/**
	 * Output Newsletter Subscription Registration Form
	 * @since 2.0.0
	 * @version 2.0.0
	 * @return void
	 */
	function news_signup(){
		global $hc_cfg, $hc_lang_config, $hc_lang_news, $hc_captchas, $hc_lang_core;
		
		if(isset($_GET['d'])){
			$g = cIn(strip_tags($_GET['d']));
			$result = doQuery("SELECT PkID FROM " . HC_TblPrefix . "subscribers WHERE GUID = '".$g."' AND GUID != '' AND IsConfirm = 1");
			if(!hasRows($result))
				return 0;
			echo '
		<p>'.$hc_lang_news['DeleteNotice'].'</p>
		<form name="frmEventNewsletter" id="frmEventNewsletter" method="post" action="' . CalRoot . '/news-edit.php">
		<input name="dID" id="dID" type="hidden" value="'.$g.'" />
		<input type="submit" name="submit" id="submit" value="'.$hc_lang_news['CancelReg'].'" />
		</form>';
			return 0;}
		
		$t = (isset($_GET['t']) && is_numeric($_GET['t'])) ? cIn(strip_tags($_GET['t'])) : 0;
		if(isset($hc_lang_news['ThankYou'.$t])){
			echo $hc_lang_news['ThankYou'.$t];
			return 0;}
		
		$submit = $hc_lang_news['SubmitReg'];
		$uID = $occupation = 0;
		$format = 2;
		$firstname = $lastname = $email = $zipcode = $birthyear = $gender = $refer = $yrOpts = '';
		$query = NULL;
		
		$g = (isset($_GET['u']) && $_GET['u'] != '') ? cIn(strip_tags($_GET['u'])) : '';
		$result = doQuery("SELECT * FROM " . HC_TblPrefix . "subscribers WHERE GUID = '" . $g . "' AND GUID != '' AND IsConfirm = 1");
		$notice = $hc_lang_news['SubInstruct'];
		if(hasRows($result)){
			$notice = $hc_lang_news['SubInstruct2'];
			$submit = $hc_lang_news['UpdateReg'];
			$uID = mysql_result($result,0,0);
			$firstname = mysql_result($result,0,1);
			$lastname = mysql_result($result,0,2);
			$email = mysql_result($result,0,3);
			$occupation = mysql_result($result,0,4);
			$zipcode = mysql_result($result,0,5);
			$addedby = mysql_result($result,0,8);
			$birthyear = mysql_result($result,0,11);
			$gender = mysql_result($result,0,12);
			$refer = mysql_result($result,0,13);
			$format = mysql_result($result,0,14);
			$query = "SELECT c.PkID, c.CategoryName, c.ParentID, c.CategoryName as Sort, uc.UserID as Selected
					FROM " . HC_TblPrefix . "categories c
						LEFT JOIN " . HC_TblPrefix . "categories c2 ON (c.PkID = c2.PkID)
						LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (c.PkID = ec.CategoryID)
						LEFT JOIN " . HC_TblPrefix . "subscriberscategories uc ON (uc.CategoryID = c.PkID AND uc.UserID = '" . $uID . "')
					WHERE c.ParentID = 0 AND c.IsActive = 1
					GROUP BY c.PkID, c.CategoryName, c.ParentID, uc.UserID
					UNION
					SELECT c.PkID, c.CategoryName, c.ParentID, c2.CategoryName as Sort, uc.UserID as Selected
					FROM " . HC_TblPrefix . "categories c
						LEFT JOIN " . HC_TblPrefix . "categories c2 ON (c.ParentID = c2.PkID)
						LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (c.PkID = ec.CategoryID)
						LEFT JOIN " . HC_TblPrefix . "subscriberscategories uc ON (uc.CategoryID = c.PkID AND uc.UserID = '" . cIn($uID) . "')
					WHERE c.ParentID > 0 AND c.IsActive = 1
					GROUP BY c.PkID, c.CategoryName, c.ParentID, c2.CategoryName, uc.UserID
					ORDER BY Sort, ParentID, CategoryName";
		}
		
		if(isset($_GET['msg'])){
			switch ($_GET['msg']){
				case "1" :
					feedback(2,$hc_lang_news['Feed04']);
					break;
			}
		}
		
		$yearSU = date("Y") - 14;
		for($x=0;$x<=80;$x++){
			$yrOpts .= '<option'.(($yearSU == $birthyear) ? ' selected="selected"' : '').' value="'.$yearSU.'">'.$yearSU.'</option>';
			--$yearSU;}
		
		echo '
		'.$notice.'
		
		<form name="frmEventNewsletter" id="frmEventNewsletter" method="post" action="'.CalRoot.'/news-signup.php" onsubmit="return validate();">
		<input name="uID" id="uID" type="hidden" value="'.$uID.'" />
		<input name="gID" id="gID" type="hidden" value="'.$g.'" />';
		
		if($hc_cfg[65] > 0 && in_array(4, $hc_captchas)){
			echo '
		<fieldset>
			<legend>' . $hc_lang_core['CapLegend'] . '</legend>';
			buildCaptcha();
		echo '
		</fieldset>';
		}
		echo '
		<fieldset>
			<legend>'.$hc_lang_news['Subscriber'].'</legend>
			<label for="hc_f1">'.$hc_lang_news['FName'].'</label>
			<input name="hc_f1" id="hc_f1" type="text" size="20" maxlength="50" placeholder="'.$hc_lang_news['PlaceFName'].'" value="'.$firstname.'" required="required" />
			<label for="hc_f2">'.$hc_lang_news['LName'].'</label>
			<input name="hc_f2" id="hc_f2" type="text" size="30" maxlength="50" placeholder="'.$hc_lang_news['PlaceLName'].'" value="'.$lastname.'" required="required" />
			<label for="hc_f3">'.$hc_lang_news['Email'].'</label>'
			.(($email == '') ? '<input name="hc_f3" id="hc_f3" type="email" size="45" maxlength="75" placeholder="'.$hc_lang_news['PlaceEmail'].'" value="'.$email.'" required="required" />' : 
			'
			<span class="output">'.$email.'</span>
			<input type="hidden" name="hc_f3" id="hc_f3" value="'.$email.'" />').'
			<label for="hc_fa">'.$hc_lang_news['Birth'].'</label>
			<select name="hc_fa" id="hc_fa">
				<option value="0">'.$hc_lang_news['Birth0'].'</option>
				'.$yrOpts.'
			</select>
			<label for="occupation">'.$hc_lang_news['Occupation'].'</label>
			';
			include(HCLANG.'/'.$hc_lang_config['OccupationFile']);
			echo '
			<label for="hc_fb">'.$hc_lang_news['Gender'].'</label>
			<select name="hc_fb" id="hc_fb">
				<option value="0">'.$hc_lang_news['Gender0'].'</option>
				<option'.(($gender == 1) ? ' selected="selected"' : '').' value="1">'.$hc_lang_news['GenderF'].'</option>
				<option'.(($gender == 2) ? ' selected="selected"' : '').' value="2">'.$hc_lang_news['GenderM'].'</option>
			</select>
			<label for="hc_fc">'.$hc_lang_news['Referral'].'</label>
			<select name="hc_fc" id="hc_fc">
				<option value="0">'.$hc_lang_news['Referral0'].'</option>
				<option'.(($refer == 1) ? ' selected="selected"' : '').' value="1">'.$hc_lang_news['Referral1'].'</option>
				<option'.(($refer == 2) ? ' selected="selected"' : '').' value="2">'.$hc_lang_news['Referral2'].'</option>
				<option'.(($refer == 3) ? ' selected="selected"' : '').' value="3">'.$hc_lang_news['Referral3'].'</option>
				<option'.(($refer == 4) ? ' selected="selected"' : '').' value="4">'.$hc_lang_news['Referral4'].'</option>
				<option'.(($refer == 5) ? ' selected="selected"' : '').' value="5">'.$hc_lang_news['Referral5'].'</option>
				<option'.(($refer == 6) ? ' selected="selected"' : '').' value="6">'.$hc_lang_news['Referral6'].'</option>
				<option'.(($refer == 7) ? ' selected="selected"' : '').' value="7">'.$hc_lang_news['Referral7'].'</option>
			</select>
			<label for="hc_f4">'.$hc_lang_news['Postal'].'</label>
			<input name="hc_f4" id="hc_f4" type="text" size="12" maxlength="10" placeholder="'.$hc_lang_news['PlacePostal'].'" value="'.$zipcode.'" />
		</fieldset>
		<fieldset>
			<legend>'.$hc_lang_news['Subscription'].'</legend>
			<label class="blank">&nbsp;</label>
			<span class="output">'.$hc_lang_news['CategoriesLabel'].'</span>
			<label>'.$hc_lang_news['Categories'].'</label>';		
			getCategories('frmEventNewsletter', $hc_cfg['CatCols'], $query);
		echo '
			<label for="format">'.$hc_lang_news['LinkFormat'].'</label>
			<select name="format" id="format">
				<option'.(($format == 0) ? ' selected="selected"' : '').' value="0">'.$hc_lang_news['LinkFormat0'].'</option>
				<option'.(($format == 1) ? ' selected="selected"' : '').' value="1">'.$hc_lang_news['LinkFormat1'].'</option>
				<option'.(($format == 2) ? ' selected="selected"' : '').' value="2">'.$hc_lang_news['LinkFormat2'].'</option>
			</select>
		</fieldset>';
		$result = doQuery("SELECT mg.PkID, mg.Name, mg.Description, sg.UserID
						FROM " . HC_TblPrefix . "mailgroups mg
							LEFT JOIN " . HC_TblPrefix . "subscribersgroups sg ON (mg.PkID = sg.GroupID AND sg.UserID = '" . $uID . "')
						WHERE mg.IsActive = 1 AND mg.PkID > 1 AND mg.IsPublic = 1
						ORDER BY Name");
		if(hasRows($result)){
		echo '
		<fieldset class="frm_grp">
			<legend>'.$hc_lang_news['GroupLabel'].'</legend>
			<label for="grpID_1"><input disabled="disabled" checked="checked" name="grpID[]" id="grpID_1" type="checkbox" value="1" /><b>'.$hc_lang_news['GenericNews'].'</b><p>'.$hc_lang_news['GenericNewsDesc'].'</p></label>';
			$cnt = 0;
			while($row = mysql_fetch_row($result)){
				$hl = ($cnt % 2 == 0) ? ' class="hl"' : '';
				echo '
			<label for="grpID_'.$row[0].'"'.$hl.'><input name="grpID[]" id="grpID_'.$row[0].'" type="checkbox" value="'.$row[0].'"'.(($row[3] == $uID && $uID > 0) ? ' checked="checked"' : '').'/>'.cOut('<b>'.$row[1].'</b><p>'.$row[2]).'</p></label>';
				++$cnt;
			}
		echo '
		</fieldset>';
		}
		echo '
		<input type="submit" name="submit" id="submit" value="'.$submit.'" />
		</form>';
	}
	/**
	 * Output Calendar Filter JavaScript validation.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @return void
	 */
	function filter_valid(){
		global $hc_lang_filter, $hc_lang_core;
		echo '
	<script src="'.CalRoot.'/inc/javascript/validation.js"></script>
	<script>
	//<!--
	function validate(){
		var err = "";
		
		if(validCheckArray("frmEventFilter","catID[]",1,"error") != "" && validCheckArray("frmEventFilter","cityName[]",1,"error") != "")
			err = "'.$hc_lang_filter['Valid01'].'";
				
		if(err != ""){
			alert(err);
			return false;
		} else {
			return true;
		}
	}
	//-->
	</script>';
	}
	/**
	 * Output Calendar Filter Form
	 * @since 2.0.0
	 * @version 2.0.0
	 * @return void
	 */
	function filter(){
		global $hc_cfg, $hc_lang_filter;
		
		if(isset($_GET['msg'])){
			switch ($_GET['msg']){
				case "1" :
					feedback(1,$hc_lang_filter['Feed01']);
					break;
				case "2" :
					feedback(1,$hc_lang_filter['Feed02']);
					break;
			}
		}
		$cnt = 0;		
		$cookie = (isset($_COOKIE[$hc_cfg[201].'_fc']) || isset($_COOKIE[$hc_cfg[201].'_fn'])) ? ' checked="checked"' : '';
		$cities = getCities();
		$colWidth = number_format((100 / $hc_cfg['CatCols']), 0);
		$colLimit = ceil(count($cities) / $hc_cfg['CatCols']);
		$actCities = (isset($_SESSION['hc_favCity'])) ? $_SESSION['hc_favCity'] : array();
		$city = $category = '';
		$city .= '
		<div class="catCol">';
		foreach($cities as $val){
			if($cnt > ceil(count($cities) / $hc_cfg['CatCols']) && $cnt > 1){
				$city .= '
		</div>
		<div class="catCol">';
				$cnt = 1;}
			if($val != ''){
				$chk = (in_array(html_entity_decode($val,ENT_QUOTES), array_filter($actCities,'html_entity_decode')) === false) ? '' : ' checked="checked"';
				$city .= '
			<label for="cityName_'.$val.'"><input'.$chk.' name="cityName[]" id="cityName_'.$val.'" type="checkbox" value="'.$val.'" />'.cOut($val).'</label>';
			++$cnt;}
		}
		$city .= '
		</div>';
		
		$query = NULL;
		if(isset($_SESSION['hc_favCat']))
			$query = "SELECT c.PkID, c.CategoryName, c.ParentID, c.CategoryName as Sort, c2.PkID as Selected
						FROM " . HC_TblPrefix . "categories c 
							LEFT JOIN " . HC_TblPrefix . "categories c2 ON (c.PkID = c2.PkID AND c.PkID IN (" . $_SESSION['hc_favCat'] . "))
							LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (c.PkID = ec.CategoryID)
						WHERE c.ParentID = 0 AND c.IsActive = 1
						GROUP BY c.PkID, c.CategoryName, c.ParentID, c2.PkID
						UNION 
						SELECT c.PkID, c.CategoryName, c.ParentID, c2.CategoryName as Sort, c3.PkID as Selected
						FROM " . HC_TblPrefix . "categories c 
							LEFT JOIN " . HC_TblPrefix . "categories c2 ON (c.ParentID = c2.PkID)
							LEFT JOIN " . HC_TblPrefix . "categories c3 ON (c.PkID = c3.PkID AND c.PkID IN (" . $_SESSION['hc_favCat'] . "))
							LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (c.PkID = ec.CategoryID)
						WHERE c.ParentID > 0 AND c.IsActive = 1
						GROUP BY c.PkID, c.CategoryName, c.ParentID, c2.CategoryName, c3.PkID
						ORDER BY Sort, ParentID, CategoryName";
	echo '
		<form name="frmEventFilter" id="frmEventFilter" method="post" action="'.CalRoot . '/filter.php" onsubmit="return validate();">
		<input type="hidden" name="f" id="f" value="1" />
		<span class="frm_ctrls">
			<label><input name="cookieme" id="cookieme" type="checkbox"'.$cookie.'>'.$hc_lang_filter['Remember'].'</label>
		</span>
		<fieldset>
			<legend>'.$hc_lang_filter['Cities'].'</legend>
			'.$city.'
		</fieldset>
		<fieldset>
			<legend>'.$hc_lang_filter['Categories'].'</legend>';
			getCategories('frmEventFilter',$hc_cfg['CatCols'],$query,1);
		echo '
		</fieldset>
		<input name="submit" id="submit" type="submit" value="'.$hc_lang_filter['SetFilter'].'" />&nbsp;
		<input name="clear" id="clear" type="button" onclick="window.location.href=\''.CalRoot.'/filter.php?clear=1\';return false;" value="'.$hc_lang_filter['ClearFilter'].'" />
		</form>';
	}
	/**
	 * Output Location Select Option Form Inputs (Selection Method Varies Based on Settings: AJAX Searc/Select List)
	 * @since 2.0.0
	 * @version 2.0.0
	 * @return void
	 */
	function location_select($warn = true){
		global $hc_cfg, $hc_lang_core, $hc_lang_submit;
		
		if($hc_cfg[70] == 1){
			$warn = ($warn) ? '<label>&nbsp;</label>
			<span class="output">'.$hc_lang_core['CheckLocInst'].'</span>' : '';
			$notice = ($warn) ? $hc_lang_core['CheckLoc'] : $hc_lang_core['CheckLocInst'];
			
			echo '
			'.$warn.'
			<label for="locSearchText">'.$hc_lang_core['LocSearch'].'</label>
			<input type="text" name="locSearchText" id="locSearchText" onwebkitspeechchange="searchLocations();" onkeyup="searchLocations();" size="30" maxlength="100" placeholder="'.$hc_lang_submit['PlaceLocSearch'].'" value="" autocomplete="off" x-webkit-speech />
				<span class="output">&nbsp;<a href="javascript:;" onclick="setLocation(0,\'\',1);" tabindex="-1">'.$hc_lang_core['ClearSearch'].'</a></span>
			<label class="blank">&nbsp;</label>
			<div id="loc_results">'.$notice.'</div>';
		} else {
			$NewAll = $hc_lang_submit['PlaceLocSelect'];
			echo '
			<div class="locSelect"><label for="locListI">'.$hc_lang_core['Preset'].'</label>';	
				if(!file_exists(HCPATH.'/cache/locList.php'))
					buildCache(2,0);
				include(HCPATH.'/cache/locList.php');
				echo '
			</div>';
		}
	}
	/**
	 * Output Event Update Submission Form
	 * @since 2.1.0
	 * @version 2.2.1
	 * @return void
	 */
	function submit_update(){
		global $hc_cfg, $hc_captchas, $hc_lang_submit, $hc_lang_config, $hc_lang_core, $hc_time;
		
		if(isset($_GET['msg'])){
			switch(cIn(strip_tags($_GET['msg']))){
				case 1 :
					feedback(1, $hc_lang_submit['Feed02']);
					echo '
		<p>' . $hc_lang_submit['ThankYouUpdated'] . '</p>
		<p><a href="' . CalRoot . '/index.php?com=acc&amp;sec=list">' . $hc_lang_submit['ClickEvents'] . '</a></p>
		<p><a href="' . CalRoot . '/index.php?com=submit">' . $hc_lang_submit['ClickSubmitAgain'] . '</a></p>';
					return -1;
					break;
			}
		}
		$eID = $user_id = 0;
		$uID = (isset($_SESSION['UserPkID']) && is_numeric($_SESSION['UserPkID'])) ? $_SESSION['UserPkID'] : '0';
		$series = $editString = $regProgress = $dateOutput = '';
		$events = $dateString = array();
		$editSingle = false;
		$startTimeHour = date($hc_time['format'], strtotime(SYSDATE.' '.SYSTIME));
		$endTimeHour = date($hc_time['format'], strtotime(SYSDATE.' '.SYSTIME.' +1 hour'));
		$startTimeMins = $endTimeMins = '00';
		$startTimeAMPM = date("A",strtotime(SYSDATE.' '.SYSTIME));
		$endTimeAMPM = date("A",strtotime(SYSDATE.' '.SYSTIME.' +1 hour'));
		$user_net = $user_name = $user_email = $user_categories = '';
		
		if(isset($_GET['eID']) && is_numeric($_GET['eID'])){
			$editSingle = true;
			$eID = cIn($_GET['eID']);
		} else {
			if(isset($_GET['sID'])){
				$series = cIn(strip_tags($_GET['sID']));
				$resultS = doQuery("SELECT GROUP_CONCAT(DISTINCT PkID ORDER BY PkID SEPARATOR ',')
								FROM " . HC_TblPrefix . "events WHERE SeriesID = '".$series."'");
				$events = explode(',',mysql_result($resultS,0,0));
				$events = array_filter($events,'is_numeric');
			} elseif(isset($_POST['eventID'])){
				$events = array_filter($_POST['eventID'],'is_numeric');
			}
			$eID = (count($events) > 0) ? $events[0] : '0';
			$editString = (count($events) > 0) ? implode(',',$events) : 'NULL';
			$resultS = doQuery("SELECT GROUP_CONCAT(StartDate ORDER BY StartDate SEPARATOR ',')
							FROM " . HC_TblPrefix . "events WHERE PkID IN (".$editString.")");
			$dateString = (hasRows($resultS)) ? explode(',',mysql_result($resultS,0,0)) : array();
		}
		
		$result = doQuery("SELECT e.*, l.PkID, l.Name, l.Address, l.Address2, l.City, l.State, l.Zip, l.Country, er.*, u.PkID, u.NetworkType, u.NetworkName, u.Email, u.Categories
						FROM " . HC_TblPrefix . "events e
							LEFT JOIN " . HC_TblPrefix . "locations l ON (e.LocID = l.PkID)
							LEFT JOIN " . HC_TblPrefix . "eventrsvps er ON (er.EventID = e.PkID)
							LEFT JOIN " . HC_TblPrefix . "users u ON (e.OwnerID = u.PkID)
						WHERE e.PkID = '" . $eID . "' AND e.IsActive = 1 AND e.OwnerID = '" . cIn($uID) . "'");
		if(!hasRows($result) || $eID < 1 || mysql_result($result,0,0) < 1){
			echo '
		<p>' . $hc_lang_submit['EditWarning'] . '</p>
		<p><a href="' . CalRoot . '/index.php?com=acc&amp;sec=list">' . $hc_lang_submit['ClickEvents'] . '</a></p>
		<p><a href="' . CalRoot . '/index.php?com=submit">' . $hc_lang_submit['ClickSubmitAgain'] . '</a></p>';
			
			
		} else {
			$eventTitle = cOut(mysql_result($result,0,1));
			$eventDesc = cOut(mysql_result($result,0,8));
			$tbd = cOut(mysql_result($result,0,11));
			$eventDate = stampToDate(mysql_result($result,0,9), $hc_cfg[24]);
			$contactName = cOut(mysql_result($result,0,13));
			$contactEmail = cOut(mysql_result($result,0,14));
			$contactPhone = cOut(mysql_result($result,0,15));
			$contactURL = (mysql_result($result,0,24) != '') ? cOut(mysql_result($result,0,24)) : '';
			$views = cOut(mysql_result($result,0,26));
			$imageURL = cOut(mysql_result($result,0,38));
			$featured = cOut(mysql_result($result,0,40));
			$expire = (mysql_result($result,0,41) > 0) ? cOut(mysql_result($result,0,41)) : $hc_cfg[134];
			$locID = cOut(mysql_result($result,0,33));
			$locName = ($locID == 0) ? cOut(mysql_result($result,0,2)) : cOut(mysql_result($result,0,43));
			$locAddress = ($locID == 0) ? cOut(mysql_result($result,0,3)) : cOut(mysql_result($result,0,44));
			$locAddress2 = ($locID == 0) ? cOut(mysql_result($result,0,4)) : cOut(mysql_result($result,0,45));
			$locCity = ($locID == 0) ? cOut(mysql_result($result,0,5)) : cOut(mysql_result($result,0,46));
			$state = ($locID == 0) ? cOut(mysql_result($result,0,6)) : cOut(mysql_result($result,0,47));
			$locPostal = ($locID == 0) ? cOut(mysql_result($result,0,7)) : cOut(mysql_result($result,0,48));
			$locCountry = ($locID == 0) ? cOut(mysql_result($result,0,35)) : cOut(mysql_result($result,0,49));
			$cost = cOut(mysql_result($result,0,34));
			$rsvp_type = cOut(mysql_result($result,0,51));
			$rsvp_space = cOut(mysql_result($result,0,55));
			$rsvp_disp = cOut(mysql_result($result,0,56));
			$rsvp_notice = cOut(mysql_result($result,0,57));
			$rsvp_open = stampToDate(mysql_result($result,0,53), $hc_cfg[24]);
			$rsvp_close = stampToDate(mysql_result($result,0,54), $hc_cfg[24]);
			$eventStatus = cOut(mysql_result($result,0,17));
			$eventBillboard = cOut(mysql_result($result,0,18));
			$message = cOut(mysql_result($result,0,27));
			$user_id = cOut(mysql_result($result,0,58));
			$user_net = cOut(mysql_result($result,0,59));
			$user_name = cOut(mysql_result($result,0,60));
			$user_email = cOut(mysql_result($result,0,61));
			$user_categories = cOut(mysql_result($result,0,62));

			switch($user_net){
				case 1:
					$user_net = 'twitter.png';
					break;
				case 2:
					$user_net = 'facebook.png';
					break;
				case 3:
					$user_net = 'google.png';
					break;
			}
			
			if($tbd == 0){
				$startTimeHour = date($hc_time['format'], strtotime(mysql_result($result,0,9).' '.mysql_result($result,0,10)));
				$startTimeMins = date("i", strtotime(mysql_result($result,0,9).' '.mysql_result($result,0,10)));
				$startTimeAMPM = date("A", strtotime(mysql_result($result,0,9).' '.mysql_result($result,0,10)));
				if(mysql_result($result,0,12) != ''){
					$endTimeHour = date($hc_time['format'], strtotime(mysql_result($result,0,9).' '.mysql_result($result,0,12)));
					$endTimeMins = date("i", strtotime(mysql_result($result,0,9).' '.mysql_result($result,0,12)));
					$endTimeAMPM = date("A", strtotime(mysql_result($result,0,9).' '.mysql_result($result,0,12)));
				} else {
					$endTimeHour = date($hc_time['format'], strtotime(mysql_result($result,0,9).' '.mysql_result($result,0,10).' +1 hour'));
					$endTimeAMPM = date("A", strtotime(mysql_result($result,0,9).' '.mysql_result($result,0,10).' +1 hour'));
					$noEndTime = 1;
				}
			}
			$stime_disabled = ($tbd > 0) ? ' disabled="disabled"' : '';
			$etime_disabled = (isset($noEndTime) || $tbd > 0) ? ' disabled="disabled"' : '';		
			
			echo '
		<p>' . $hc_lang_submit['UpdateNotice'] . '</p>
		'.(($_SESSION['UserLevel'] != 2) ? '<p>'.$hc_lang_submit['NotPublisher'].'</p>':'').'
		<p>(<span class="req2">*</span>) = '.$hc_lang_submit['Required2'].'<br />
		(<span class="req3">*</span>) = '.$hc_lang_submit['Required3'].'</p>
		
		<form id="frmEventUpdate" name="frmEventUpdate" method="post" action="'.CalRoot.'/event-submit-update.php" onsubmit="return validate();">
		<input type="hidden" name="eID" id="eID" value="'.$eID.'" />
		<input type="hidden" name="editString" id="editString" value="'.$editString.'" />
		<input type="hidden" id="locPreset" name="locPreset" value="'.$locID.'" />
		<input type="hidden" id="locPresetName" name="locPresetName" value="'.$locName.'" />';
		
			if($editSingle == false){
				echo '
		<input type="hidden" name="grpDate" id="grpDate" value="'.stampToDate(min($dateString),$hc_cfg[24]).' - '.stampToDate(max($dateString),$hc_cfg[24]).'" />';
				$cnt = 1;
				foreach($dateString as $val){
					$dateOutput .= ($cnt % 8 == 0) ? stampToDate($val, $hc_cfg[24]).'<br />' : stampToDate($val, $hc_cfg[24]).', ';
					++$cnt;
				}
			}
			
			if($hc_cfg[65] > 0 && in_array(1, $hc_captchas)){
				echo '
		<fieldset>
			<legend>' . $hc_lang_core['CapLegend'] . '</legend>';
				buildCaptcha();
				echo '
		</fieldset>';}

			echo '
		<fieldset>
			<legend>'.$hc_lang_submit['ContactInfo'].'</legend>
			<label for="submitName">'.$hc_lang_submit['Name'].'</label>
			<span class="output submit_user">
				<img src="'.CalRoot.'/img/share/'.$user_net.'" width="16" height="16" alt="" /> '.$user_name.'
				<input name="submitName" id="submitName" type="hidden" value="'.$user_name.'" />
			</span>
			<label for="submitEmail">'.$hc_lang_submit['Email'].'</label>
			<span class="output submit_user">
				'.$user_email.'
				<input name="submitEmail" id="submitEmail" type="hidden" value="'.$user_email.'" />
			</span>
			<input name="submitID" id="submitID" type="hidden" value="'.$user_id.'" />
		</fieldset>
		<fieldset>
			<legend>'.$hc_lang_submit['EventDetails'].'</legend>
			<label for="eventTitle">'.$hc_lang_submit['Title'].'</label>
			<input name="eventTitle" id="eventTitle" type="text" size="65" maxlength="150" required="required" placeholder="'.$hc_lang_submit['PlaceTitle'].'" value="'.$eventTitle.'" />
			<label for="eventDescription">'.$hc_lang_submit['Description'].'</label>
			<textarea name="eventDescription" id="eventDescription" rows="20" placeholder="'.$hc_lang_submit['PlaceDesc'].'" class="mce_edit">'.$eventDesc.'</textarea>
			<label for="cost">'.$hc_lang_submit['Cost'].'</label>
			<input name="cost" id="cost" type="text" size="25" maxlength="50" placeholder="'.$hc_lang_submit['PlaceCost'].'" value="'.$cost.'" />
		</fieldset>
		<fieldset>
			<legend>'.$hc_lang_submit['DateTime'].'</legend>
			'.(($editSingle == true) ? '<label for="eventDate">'.$hc_lang_submit['EventDate'].'</label>
			<input name="eventDate" id="eventDate" type="text" size="12" maxlength="10" required="required" value="'.$eventDate.'" />
			<a href="javascript:;" onclick="calx.select(document.getElementById(\'eventDate\'),\'cal1\',\''.$hc_cfg[51].'\');return false;" id="cal1" class="ds calendar" tabindex="-1"></a>':
			'<label for="eventDate">'.$hc_lang_submit['Dates'].'</label>
			<span class="output">'.$dateOutput.'</span>').'
			<label>'.$hc_lang_submit['StartTime'].'</label>
			<input name="startTimeHour" id="startTimeHour" type="text" size="2" maxlength="2" required="required" value="'.$startTimeHour.'"'.$stime_disabled.' />
			<span class="frm_ctrls">
				<a href="javascript:;" onclick="chngClock(document.getElementById(\'startTimeHour\'),1,'.$hc_time['input'].')" class="time tup" tabindex="-1"></a>
				<a href="javascript:;" onclick="chngClock(document.getElementById(\'startTimeHour\'),-1,'.$hc_time['input'].')" class="time tdown" tabindex="-1"></a>
			</span>
			<input name="startTimeMins" id="startTimeMins" type="text" size="2" maxlength="2" required="required" value="'.$startTimeMins.'"'.$stime_disabled.' />
			<span class="frm_ctrls">	
				<a href="javascript:;" onclick="chngClock(document.getElementById(\'startTimeMins\'),5,59)" class="time tup" tabindex="-1"></a>
				<a href="javascript:;" onclick="chngClock(document.getElementById(\'startTimeMins\'),-5,59)" class="time tdown" tabindex="-1"></a>
			</span>';
			if($hc_time['input'] == 12){
				echo '
			<select name="startTimeAMPM" id="startTimeAMPM">
				<option '.(($startTimeAMPM == 'AM') ? 'selected="selected" ' : '').'value="AM">'.$hc_lang_submit['AM'].'</option>
				<option '.(($startTimeAMPM == 'PM') ? 'selected="selected" ' : '').'value="PM">'.$hc_lang_submit['PM'].'</option>
			</select>';}
			echo '
			<label>'.$hc_lang_submit['EndTime'].'</label>
			<input name="endTimeHour" id="endTimeHour" type="text" size="2" maxlength="2" required="required" value="'.$endTimeHour.'"'.$etime_disabled.' />
			<span class="frm_ctrls">	
				<a href="javascript:;" onclick="chngClock(document.getElementById(\'endTimeHour\'),1,'.$hc_time['input'].')" class="time tup" tabindex="-1"></a>
				<a href="javascript:;" onclick="chngClock(document.getElementById(\'endTimeHour\'),-1,'.$hc_time['input'].')" class="time tdown" tabindex="-1"></a>
			</span>
			<input name="endTimeMins" id="endTimeMins" type="text" size="2" maxlength="2" required="required" value="'.$endTimeMins.'"'.$etime_disabled.' />
			<span class="frm_ctrls">
				<a href="javascript:;" onclick="chngClock(document.getElementById(\'endTimeMins\'),5,59)" class="time tup" tabindex="-1"></a>
				<a href="javascript:;" onclick="chngClock(document.getElementById(\'endTimeMins\'),-5,59)" class="time tdown" tabindex="-1"></a>
			</span>';
			if($hc_time['input'] == 12){
				echo '
			<select name="endTimeAMPM" id="endTimeAMPM">
				<option '.(($endTimeAMPM == 'AM') ? 'selected="selected" ' : '').'value="AM">'.$hc_lang_submit['AM'].'</option>
				<option '.(($endTimeAMPM == 'PM') ? 'selected="selected" ' : '').'value="PM">'.$hc_lang_submit['PM'].'</option>
			</select>';}
			echo '
			<span class="frm_ctrls">
				<label for="ignoreendtime"><input name="ignoreendtime" id="ignoreendtime" type="checkbox"'.((isset($noEndTime)) ? ' checked="checked"':'').$stime_disabled.' onclick="togEndTime(this.checked);" />'.$hc_lang_submit['NoEndTime'].'</label>
			</span>
			<label class="blank">&nbsp;</label>
			<span class="frm_ctrls">
				<label for="overridetime"><input type="checkbox" name="overridetime" id="overridetime"'.(($tbd > 0) ? ' checked="checked"':'').' onclick="togOverride();" />'.$hc_lang_submit['Override'].'</label>
				<label for="specialtimeall"><input type="radio" name="specialtime" id="specialtimeall" value="allday"'.(($tbd == 0) ? ' disabled="disabled"':'').(($tbd < 2) ? ' checked="checked"':'').' />'.$hc_lang_submit['AllDay'].'</label>
			</span>
		</fieldset>
		
		'.(($hc_cfg['IsRSVP'] == 1) ? '
		<fieldset>
			<legend>'.$hc_lang_submit['RegTitle'].'</legend>
			<label for="rsvp_type">'.$hc_lang_submit['Registration'].'</label>
			<select name="rsvp_type" id="rsvp_type" onchange="togRegistration();">
				<option '.(($rsvp_type == 0) ? 'selected="selected" ' : '').'value="0">'.$hc_lang_submit['Reg0'].'</option>
				<option '.(($rsvp_type == 1) ? 'selected="selected" ' : '').'value="1">'.$hc_lang_submit['Reg1'].'</option>
			</select>
			<div id="rsvp"'.($rsvp_type != 1 ? ' style="display:none;"':'').'>
				<label for="rsvp_space">'.$hc_lang_submit['Limit'].'</label>
				<input name="rsvp_space" id="rsvp_space" type="number" min="0" max="9999" size="5" maxlength="4" value="'.$rsvp_space.'"'.($rsvp_type != 1 ? ' disabled="disabled"':'').' required="required" />
				<span class="output">'.$hc_lang_submit['LimitLabel'].'</span>
				<label>'.$hc_lang_submit['Allow'].'</label>
				<input name="openDate" id="openDate" type="text" size="12" maxlength="10" value="'.$rsvp_open.'"'.($rsvp_type != 1 ? ' disabled="disabled"':'').' required="required" />
				<a href="javascript:;" onclick="calx.select(document.getElementById(\'openDate\'),\'cal3\',\''.$hc_cfg[51].'\');return false;" id="cal3" class="ds calendar" tabindex="-1"></a>
				<span class="output">&nbsp;&nbsp;'.$hc_lang_submit['To'].'&nbsp;&nbsp;</span>
				<input name="closeDate" id="closeDate" type="text" size="12" maxlength="10" value="'.$rsvp_close.'"'.($rsvp_type != 1 ? ' disabled="disabled"':'').' required="required" />
				<a href="javascript:;" onclick="calx.select(document.getElementById(\'closeDate\'),\'cal4\',\''.$hc_cfg[51].'\');return false;" id="cal4" class="ds calendar" tabindex="-1"></a>
				'.((isset($_SESSION['UserLoggedIn']) && $_SESSION['UserLoggedIn'] == 1) ? '
				<label for="rsvpEmail">'.$hc_lang_submit['EmailNotice'].'</label>
				<select name="rsvpEmail" id="rsvpEmail">
					<option value="0">'.$hc_lang_submit['EmailNotice0'].'</option>
					<option value="1">'.$hc_lang_submit['EmailNotice1'].'</option>
				</select>
				<label>&nbsp;</label><span class="output onote">'.$hc_lang_submit['RSVPDownload'].'</span>':'<input type="hidden" name="rsvpEmail" id="rsvpEmail" value="1" /><label>&nbsp;</label><span class="output onote">'.$hc_lang_submit['RSVPDownloadNo'].'</span>').'
			</div>
		</fieldset>':'
		<input type="hidden" name="rsvp_type" id="rsvp_type" value="0" />');
				
			if($hc_cfg[29] == 1){
				$uQuery = ($user_categories != '') ? " AND c.PkID IN (".$user_categories.")" : "";
				
				echo '
		<fieldset>
			<legend>'.$hc_lang_submit['EventCat'].'</legend>
			<label>'.$hc_lang_submit['Categories'].'</label>';
				
				$query = ($eID > 0) ? "SELECT c.PkID, c.CategoryName, c.ParentID, c.CategoryName as Sort, ec.EventID as Selected
					FROM " . HC_TblPrefix . "categories c
						LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (c.PkID = ec.CategoryID AND ec.EventID = " . cIn($eID) . ")
					WHERE c.ParentID = 0 AND c.IsActive = 1".$uQuery."
					GROUP BY c.PkID, c.CategoryName, c.ParentID, ec.EventID
					UNION
					SELECT c.PkID, c.CategoryName, c.ParentID, c2.CategoryName as Sort, ec.EventID as Selected
					FROM " . HC_TblPrefix . "categories c
						LEFT JOIN " . HC_TblPrefix . "categories c2 ON (c.ParentID = c2.PkID)
						LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (c.PkID = ec.CategoryID AND ec.EventID = " . cIn($eID) . ")
					WHERE c.ParentID > 0 AND c.IsActive = 1".$uQuery."
					GROUP BY c.PkID, c.CategoryName, c.ParentID, c2.CategoryName, ec.EventID
					ORDER BY Sort, ParentID, CategoryName" : NULL;
				
				getCategories('frmEventUpdate',$hc_cfg['CatCols'],$query,$hc_cfg['CatLinks']);
				
				echo '
		</fieldset>';}
			echo '
		<fieldset>
			<legend>'.$hc_lang_submit['LocationLabel'].'</legend>';
			echo ($locID > 0) ? '
			<div id="locSetting" class="frm_ctrl">
				<label>' . $hc_lang_submit['CurLocation'] . '</label>
				<span class="output">
					<b>'.$locName.'</b><br />
					'.buildAddress($locAddress,$locAddress2,$locCity,$state,$locPostal,$locCountry,$hc_lang_config['AddressType']).'
				</span>
				<label>&nbsp;</label>
				<span class="output">
					<a href="javascript:;" onclick="setLocation(0,\'\',1);" class="locChange">' . $hc_lang_submit['ChngLocation'] . '</a>
				</span>
			</div>' : '';
			echo '
			<div id="locSearch" '.(($locID > 0) ? ' style="display:none;"' : '').'>';
			location_select();

			$inputs = array(1 => array('City','locCity',$locCity),2 => array('Postal','locZip',$locPostal));
			$first = ($hc_lang_config['AddressType'] == 1) ? 1 : 2;
			$second = ($first == 1) ? 2 : 1;
			
			echo '
			</div>
			<div id="custom"'.(($locID > 0) ? ' style="display:none;"' : '').'>
				<label for="locName">'.$hc_lang_submit['Name'].'</label>
				<input name="locName" id="locName" type="text" size="25" maxlength="50" value="'.(($locID < 1) ? $locName : '').'" />
				<label for="locAddress">'.$hc_lang_submit['Address'].'</label>
				<input name="locAddress" id="locAddress" type="text" size="30" maxlength="75" value="'.(($locID < 1) ? $locAddress : '').'" /><span class="output req2">*</span>
				<label for="locAddress2">'.$hc_lang_submit['Address2'].'</label>
				<input name="locAddress2" id="locAddress2" type="text" size="25" maxlength="75" value="'.(($locID < 1) ? $locAddress2 : '').'" />
				<label for="' . $inputs[$first][1] . '">' . $hc_lang_submit[$inputs[$first][0]] . '</label>
				<input name="' . $inputs[$first][1] . '" id="' . $inputs[$first][1] . '" type="text" size="20" maxlength="50" value="'.(($locID < 1) ? $inputs[$first][2] : '').'" /><span class="output req2">*</span>';

			if($hc_lang_config['AddressRegion'] != 0){	
				echo '<label for="locState">' . $hc_lang_config['RegionLabel'] . '</label>';
				$regSelect = $state;
				include(HCLANG.'/'.$hc_lang_config['RegionFile']);
				echo '<span class="output req2">*</span>';}

			echo '<label for="'.$inputs[$second][1].'">'.$hc_lang_submit[$inputs[$second][0]].'</label>
				<input name="'.$inputs[$second][1].'" id="'.$inputs[$second][1].'" type="text" size="20" maxlength="50" value="'.(($locID < 1) ? $inputs[$second][2] : '').'" /><span class="output req2">*</span>
				<label for="locCountry">'.$hc_lang_submit['Country'].'</label>
				<input name="locCountry" id="locCountry" type="text" size="10" maxlength="50" value="'.(($locID < 1) ? $locCountry : '').'" />
			</div>
			<div id="custom_notice" style="display:none;">
				<label>&nbsp;</label>
				<b>'.$hc_lang_core['PresetLoc'].'</b>
			</div>
		</fieldset>
		<fieldset>
			<legend>'.$hc_lang_submit['ContactLabel'].'</legend>
			<label for="contactName">'.$hc_lang_submit['Name'].'</label>
			<input name="contactName" id="contactName" type="text" size="20" maxlength="50" placeholder="'.$hc_lang_submit['PlaceContactName'].'" value="'.$contactName.'" /><span class="output req3">*</span>
			<label for="contactEmail">'.$hc_lang_submit['Email'].'</label>
			<input name="contactEmail" id="contactEmail" type="text" size="30" maxlength="75" placeholder="'.$hc_lang_submit['PlaceContactEmail'].'" value="'.$contactEmail.'" /><span class="output req3">*</span>
			<label for="contactPhone">'.$hc_lang_submit['Phone'].'</label>
			<input name="contactPhone" id="contactPhone" type="tel" size="20" maxlength="25" placeholder="'.$hc_lang_submit['PlaceContactPhone'].'" value="'.$contactPhone.'" />
			<label for="contactURL">'.$hc_lang_submit['Website'].'</label>
			<input name="contactURL" id="contactURL" type="url" maxlength="100" placeholder="'.$hc_lang_submit['PlaceContactURL'].'" value="'.$contactURL.'" />
		</fieldset>
		<fieldset>
			<legend>'.$hc_lang_submit['MessageLabel'].'</legend>
			<label for="goadminmessage">'.$hc_lang_submit['Include'].'</label>
			<input name="goadminmessage" id="goadminmessage" type="checkbox" value="" onclick="togThis(this,document.getElementById(\'adminmessage\'));"'.(($message != '') ? ' checked="checked"':'').' />
			<label for="adminmessage">'.$hc_lang_submit['Message'].'</label>
			<textarea name="adminmessage" id="adminmessage" rows="7" required="required" placeholder="'.$hc_lang_submit['PlaceMsgAdmin'].'"'.(($message == '') ? ' disabled="disabled"':'').' >'.$message.'</textarea>
		</fieldset>
		<input name="submit" id="submit" type="submit" value="'.$hc_lang_submit['SubmitEvent'].'" />
		</form>
		<div id="dsCal"></div>';
		makeTinyMCE('',0,0,'eventDescription');
		}
	}
	/**
	 * Output Submission Update Unique JavaScript validation.
	 * @since 2.1.0
	 * @version 2.2.0
	 * @return void
	 */
	function submit_update_valid(){
		global $eID, $hc_cfg, $hc_lang_submit, $hc_lang_core, $hc_time;
		
		echo '
	function validate(){
		var err = "";';
		captchaValidation('1');
		
		echo '
		err +=reqField(document.getElementById("submitName"),"'.$hc_lang_submit['Valid15'].'\n");
		err +=reqField(document.getElementById("submitEmail"),"'.$hc_lang_submit['Valid16'].'\n");
		if(document.getElementById("submitEmail").value != "")
			err +=validEmail(document.getElementById("submitEmail"),"'.$hc_lang_submit['Valid17'].'\n");
		err +=reqField(document.getElementById("eventTitle"),"'.$hc_lang_submit['Valid18'].'\n");
		
		try{
			err +=chkTinyMCE(tinyMCE.get("eventDescription").getContent(),"'.$hc_lang_submit['Valid02'].'\n");}
		catch(error){
			err +=reqField(document.getElementById("eventDescription"),"'.$hc_lang_submit['Valid02'].'\n");}
				
		if(document.getElementById("rsvp_type").value == 1){
			err +=reqField(document.getElementById("rsvp_space"),"'.$hc_lang_submit['Valid04'].'\n");
			err +=validNumber(document.getElementById("rsvp_space"),"'.$hc_lang_submit['Valid04'].'\n");
			err +=validGreater(document.getElementById("rsvp_space"),-1,"'.$hc_lang_submit['Valid03'].'\n");
			err +=reqField(document.getElementById("openDate"),"'.$hc_lang_submit['Valid52'].'\n");
			err +=reqField(document.getElementById("closeDate"),"'.$hc_lang_submit['Valid53'].'\n");				
			if(document.getElementById("openDate").value != ""){
				err +=validDate(document.getElementById("openDate"),"'.$hc_cfg[51].'","'.$hc_lang_submit['Valid54'].' '.strtoupper($hc_cfg[51]).'\n");
				err +=validDateBefore(document.getElementById("openDate").value,document.getElementById("eventDate").value,"'.$hc_cfg[51].'","'.$hc_lang_submit['Valid55'].'\n")
			}
			if(document.getElementById("closeDate").value != ""){
				var closeLimit = document.getElementById("eventDate").value;
				err +=validDate(document.getElementById("closeDate"),"'.$hc_cfg[51].'","'.$hc_lang_submit['Valid56'].' '.strtoupper($hc_cfg[51]).'\n");
				err +=validDateBefore(document.getElementById("closeDate").value,closeLimit,"'.$hc_cfg[51].'","'.$hc_lang_submit['Valid57'].'\n")
			}
			err +=validDateBefore(document.getElementById("openDate").value,document.getElementById("closeDate").value,"'.$hc_cfg[51].'","'.$hc_lang_submit['Valid58'].'\n")
			err +=reqField(document.getElementById("contactName"),"'.$hc_lang_submit['Valid05'].'\n");
			err +=reqField(document.getElementById("contactEmail"),"'.$hc_lang_submit['Valid06'].'\n");
		}

		if(document.getElementById("eventDate"))
			err +=chkDate();
		
		err +=validNumber(document.getElementById("startTimeHour"),"'.$hc_lang_submit['Valid07'].'\n");
		err +=validNumberRange(document.getElementById("startTimeHour"),'.$hc_time['minHr'].','.$hc_time['input'].',"'.$hc_lang_submit['Valid08']." ".$hc_time['minHr']." - ".$hc_time['input'].'\n");
		err +=reqField(document.getElementById("startTimeMins"),"'.$hc_lang_submit['Valid10'].'\n");
		err +=validNumber(document.getElementById("startTimeMins"),"'.$hc_lang_submit['Valid09'].'\n");
		err +=validNumberRange(document.getElementById("startTimeMins"),0,59,"'.$hc_lang_submit['Valid10'].'\n");
		err +=validNumber(document.getElementById("endTimeHour"),"'.$hc_lang_submit['Valid11'].'\n");
		err +=validNumberRange(document.getElementById("endTimeHour"),'.$hc_time['minHr'].','.$hc_time['input'].',"'.$hc_lang_submit['Valid12']." ".$hc_time['minHr']." - ".$hc_time['input'].'\n");
		err +=reqField(document.getElementById("endTimeMins"),"'.$hc_lang_submit['Valid14'].'\n");
		err +=validNumber(document.getElementById("endTimeMins"),"'.$hc_lang_submit['Valid13'].'\n");
		err +=validNumberRange(document.getElementById("endTimeMins"),0,59,"'.$hc_lang_submit['Valid14'].'\n");
		
		'.(($hc_cfg[29] == 1) ? 'err +=validCheckArray("frmEventUpdate","catID[]",1,"'.$hc_lang_submit['Valid51'].'\n");':'').'
			
		if(document.getElementById("locPreset").value == 0)
			err +=reqField(document.getElementById("locName"),"'.$hc_lang_submit['Valid19'].'\n");
		if(document.getElementById("contactEmail").value != "")
			err +=validEmail(document.getElementById("contactEmail"),"'.$hc_lang_submit['Valid21'].'\n");
		if(err != ""){
			alert(err);
			return false;
		} else {
			valid_ok(document.getElementById("submit"),"'.$hc_lang_core['Sending'].'");
			return true;
		}
	}';
	}
	/**
	 * Output Conditional Submission/Update JavaScript validation.
	 * @since 2.2.0
	 * @version 2.2.0
	 * @return void
	 */
	function submit_valid_output($sub_type){
		global $eID, $hc_cfg, $hc_lang_submit, $hc_lang_core, $hc_time;
		
		echo '
	<script src="'.CalRoot.'/inc/javascript/validation.js"></script>
	<script src="'.CalRoot.'/inc/lang/'.$_SESSION['LangSet'].'/popCal.js"></script>
	<script src="'.CalRoot.'/inc/javascript/DateSelect.js"></script>
	<script>
	//<!--
	var calx = new CalendarPopup("dsCal");
	calx.showNavigationDropdowns();
	calx.setCssPrefix("hc_");
	calx.offsetX = 30;
	calx.offsetY = -5;

	function toggleMe(who){who.style.display == "none" ? who.style.display = "block":who.style.display = "none";return false;}
	function chngClock(obj,inc,max){if(obj.disabled == false){var val = (!isNaN(obj.value)) ? parseInt(obj.value,10):0;val += inc;if(max == 59){if(val > max) val = 0;if(val < 0) val = max + 1 - Math.abs(val);} else {if(val > max) val = '.$hc_time['minHr'].';if(val < '.$hc_time['minHr'].') val = max;}obj.value = (val < 10) ? "0" + val : val;}}
	function togOverride(){
		var inputs = (document.getElementById("overridetime").checked) ? true : false;
		document.getElementById("startTimeHour").disabled = inputs;
		document.getElementById("startTimeMins").disabled = inputs;
		document.getElementById("ignoreendtime").disabled = inputs;
		document.getElementById("specialtimeall").disabled = (inputs == true) ? false : true;
		if('.$hc_time['input'].' == 12)
			document.getElementById("startTimeAMPM").disabled = inputs;
		if(document.getElementById("ignoreendtime").checked || inputs)
			togEndTime(true);
		else
			togEndTime(false);
	}
	function togEndTime(disable){
		document.getElementById("endTimeHour").disabled = disable;
		document.getElementById("endTimeMins").disabled = disable;
		if('.$hc_time['input'].' == 12)
			document.getElementById("endTimeAMPM").disabled = disable;
	}
	function chkDate(){
		var err = "";
		err += reqField(document.getElementById("eventDate"),"'.$hc_lang_submit['Valid23'].'\n");
		err += validDate(document.getElementById("eventDate"),"'.$hc_cfg[51].'","'.$hc_lang_submit['Valid24'].' '.strtoupper($hc_cfg[51]).'\n");
		err += validDateBefore("'.strftime($hc_cfg[24],strtotime(SYSDATE)).'",document.getElementById("eventDate").value,"'.$hc_cfg[51].'","'.$hc_lang_submit['Valid20'].'\n");
		return err;
	}
	function togRegistration(){
		var regChk = document.getElementById("rsvp_type").value; 
		document.getElementById("rsvp_space").disabled = (regChk == 1) ? false : true;
		document.getElementById("openDate").disabled = (regChk == 1) ? false : true;
		document.getElementById("closeDate").disabled = (regChk == 1) ? false : true;
		document.getElementById("rsvpEmail").disabled = (regChk == 1) ? false : true;
		document.getElementById("rsvp").style.display = (regChk == 1) ? "block" : "none";
	}';
		
	if($sub_type == 0)
		submit_valid();
	else
		submit_update_valid();
	
	testCaptcha();
	include_once(HCPATH.'/inc/javascript/locations.php');
	echo '
	//-->
	</script>';
	}
?>