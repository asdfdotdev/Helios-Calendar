<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(!defined('hcAdmin')){header("HTTP/1.1 403 No Direct Access");exit();}

	include(HCLANG.'/admin/user.php');
	
	$uID = (isset($_GET['uID']) && is_numeric($_GET['uID'])) ? cIn(strip_tags($_GET['uID'])) : 0;
	$id = $network_type = $network_name = $network_id = $email = $signin_cnt = $signin_first = $signin_last = 
		$signin_last_ip = $level = $cat_limited = $location = $birthday = $url_fb = $url_tw = $url_gp = $privacy = $points = '';
	
	if(isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1, $hc_lang_user['Feed04']);
				break;
		}
	}
	
	appInstructions(0, "Manage_Users", $hc_lang_user['TitleEdit'], $hc_lang_user['InstructEdit']);
	
	$result = doQuery("SELECT u.*,(SELECT COUNT(PkID) FROM " . HC_TblPrefix . "events e WHERE e.OwnerID = u.PkID AND e.IsActive = 1) as EventCnt
					FROM " . HC_TblPrefix . "users u WHERE PkID = '" . $uID . "'");
	
	if(!hasRows($result)){
		echo '<p>'.$hc_lang_user['NoUserEdit'].'</p>';
	} else {
		$id = cOut(mysql_result($result,0,0));
		$network = cOut(mysql_result($result,0,1));
		$network_name = cOut(mysql_result($result,0,2));
		$network_id = cOut(mysql_result($result,0,3));
		$email = cOut(mysql_result($result,0,4));
		$signin_cnt = cOut(mysql_result($result,0,5));
		$signin_first = cOut(mysql_result($result,0,6));
		$signin_last = cOut(mysql_result($result,0,7));
		$signin_last_ip = cOut(mysql_result($result,0,8));
		$level = cOut(mysql_result($result,0,9));
		$banned = cOut(mysql_result($result,0,10));
		$cat_limited = cOut(mysql_result($result,0,11));
		$location = cOut(mysql_result($result,0,12));
		$birthdate = stampToDate(mysql_result($result,0,13), $hc_cfg[24]);;
//		$url_fb = cOut(mysql_result($result,0,14));
//		$url_tw = cOut(mysql_result($result,0,15));
//		$url_gp = cOut(mysql_result($result,0,16));
		$privacy = cOut(mysql_result($result,0,17));
		$points = cOut(mysql_result($result,0,18));
		$api_key = cOut(mysql_result($result,0,19));
		$api_access = cOut(mysql_result($result,0,20));
		$api_cnt = cOut(mysql_result($result,0,21));
		$event_cnt = cOut(mysql_result($result,0,22));
		$url_fb = $url_tw = $url_gp = '';
		
		switch($network){
			case 1:
				$net_output = '<img src="'.AdminRoot.'/img/logos/twitter_icon.png" /> '.$hc_lang_user['Twitter'];
				$profile = 'https://twitter.com/'.$network_name;
				break;
			case 2:
				$net_output = '<img src="'.AdminRoot.'/img/logos/facebook_icon.png" /> '.$hc_lang_user['Facebook'];
				$profile = 'https://www.facebook.com/'.$network_id;
				break;
			case 3:
				$net_output = '<img src="'.AdminRoot.'/img/logos/google.png" /> '.$hc_lang_user['Google'];
				$profile = 'https://plus.google.com/'.$network_id;
				break;
		}
		
		echo '
	<form name="frmUserEdit" id="frmUserEdit" method="post" action="'.AdminRoot.'/components/UserEditAction.php" onsubmit="return validate();">';
	set_form_token();
	echo '
	<fieldset>
		<legend>'.$hc_lang_user['UserNetworkLabel'].'</legend>
		<label>'.$hc_lang_user['Network'].'</label>
		<span class="output">'.$net_output.'</span>
		<label>'.$hc_lang_user['NetName'].'</label>
		<span class="output">'.$network_name.'</span>
		<label>'.$hc_lang_user['NetID'].'</label>
		<span class="output">'.$network_id.'</span>
		<label>'.$hc_lang_user['NetProfile'].'</label>
		<span class="output"><a href="'.$profile.'" target="_blank">'.$profile.'</a></span>
	</fieldset>
	<fieldset>
		<legend>'.$hc_lang_user['UserLocalLabel'].'</legend>
		<label>'.$hc_lang_user['ID'].'</label>
		<span class="output">'.$id.'</span>
		<label>'.$hc_lang_user['SignIns'].'</label>
		<span class="output">'.number_format($signin_cnt,0,'.',',').'</span>
		<label>'.$hc_lang_user['FirstSignIn'].'</label>
		<span class="output">'.strftime($hc_cfg[24].' '.$hc_cfg[23],strtotime($signin_first)).'</span>
		<label>'.$hc_lang_user['LastSignIn'].'</label>
		<span class="output">'.strftime($hc_cfg[24].' '.$hc_cfg[23],strtotime($signin_last)).' ('.$signin_last_ip.')</span>
		<label>'.$hc_lang_user['Submissions'].'</label>
		<span class="output">'.$event_cnt.'</span>
		<label>'.$hc_lang_user['APICalls'].'</label>
		<span class="output">'.number_format($api_cnt,0,'.',',').'</span>
		<label>'.$hc_lang_user['APIKey'].'</label>
		<span class="output">'.$api_key.'</span>
		'.(($url_fb.$url_tw.$url_gp != '') ? '
		<label>'.$hc_lang_user['Social'].'</label>
		<span class="output">
			'.(($url_fb != '') ? '<a href="'.$url_fb.'" target="_blank"><img src="'.AdminRoot.'/img/logos/facebook_icon.png" width="16" height="16" alt="" /></a>&nbsp;&nbsp;' : '').'
			'.(($url_tw != '') ? '<a href="'.$url_tw.'" target="_blank"><img src="'.AdminRoot.'/img/logos/twitter_icon.png" width="16" height="16" alt="" /></a>&nbsp;&nbsp;' : '').'
			'.(($url_gp != '') ? '<a href="'.$url_gp.'" target="_blank"><img src="'.AdminRoot.'/img/logos/gplus_icon.png" width="16" height="16" alt="" /></a>&nbsp;&nbsp;' : '').'
		</span>':'').'
	</fieldset>';
		
		$resultE = doQuery("SELECT PkID, Title, StartDate, SeriesID, IsBillboard
						FROM " . HC_TblPrefix . "events
						WHERE IsActive = 1 AND IsApproved = 1 AND StartDate >= '" . cIn(SYSDATE) . "' AND SeriesID IS NULL AND OwnerID = '".$uID."'
						UNION
						SELECT SeriesID, Title, MIN(StartDate), SeriesID, IsBillboard
						FROM " . HC_TblPrefix . "events
						WHERE IsActive = 1 AND IsApproved = 1 AND StartDate >= '" . cIn(SYSDATE) . "' AND (SeriesID IS NOT NULL AND SeriesID != '') AND OwnerID = '".$uID."'
						GROUP BY SeriesID, Title, IsBillboard
						ORDER BY StartDate, SeriesID DESC
						LIMIT 150");
		if(hasRows($resultE)){
			echo '
	<fieldset>
		<legend>'.$hc_lang_user['UserEventLabel'].'</legend>
		<ul class="data">
			<li class="row header uline">
				<div class="txt" style="width:69%;">'.$hc_lang_user['Title'].'</div>
				<div style="width:15%;">'.$hc_lang_user['Occurs'].'</div>
				<div class="tools" style="width:15%;">&nbsp;</div>
			</li>
		</ul>
		<div class="uevent">
		<ul class="data">';
			$cnt = 0;
			while($row = mysql_fetch_row($resultE)){
				$hl = ($cnt % 2 == 1) ? ' hl':'';
				echo '
				<li class="row'.$hl.'">
					<div class="txt" title="'.cOut($row[1]).'" style="width:70%;">'.cOut($row[1]).'</div>
					<div style="width:15%;">'.stampToDate($row[2], $hc_cfg[24]).'</div>
					<div class="tools" style="width:15%;">
						'.(($row[3] != '') ? '
						<a href="' . AdminRoot . '/index.php?com=eventedit&amp;sID='.$row[0].'"><img src="' . AdminRoot . '/img/icons/edit_group.png" width="16" height="16" alt="" /></a>
						<a href="' . AdminRoot . '/index.php?com=searchresults&amp;srsID='.$row[0].'"><img src="' . AdminRoot . '/img/icons/view_series.png" width="16" height="16" alt="" /></a>
						':'
						<a href="'.AdminRoot.'/index.php?com=eventedit&amp;eID='.$row[0].'"><img src="'.AdminRoot.'/img/icons/edit.png" width="16" height="16" alt="" /></a>
						<img src="'.AdminRoot.'/img/spacer.gif" width="16" height="16" alt="" />').'
						'.(($row[4] == 1) ? '<a href="'.AdminRoot.'/index.php?com=eventbillboard"><img src="'.AdminRoot.'/img/icons/billboard.png" width="16" height="16" alt="" /></a>':'').'
					</div>
				</li>';
				++$cnt;
			}
			echo '
		</ul>
		</div>
		&nbsp;
	</fieldset>';
		}

		echo '
	<fieldset>
		<legend>'.$hc_lang_user['UserEditLabel'].'</legend>
		<label for="email">'.$hc_lang_user['Email'].'</label>
		<input name="email" id="email" type="email" maxlength="75" size="35" value="'.$email.'" />
		<label>'.$hc_lang_user['Level'].'</label>
		<select name="level" id="level" onchange="togLevel(this.options[this.selectedIndex].value);">
			<option'.(($level == 0) ? ' selected="selected"':'').' value="0">'.$hc_lang_user['Level0'].'</option>
			<option'.(($level == 2) ? ' selected="selected"':'').' value="2">'.$hc_lang_user['Level2'].'</option>
		</select>
		<div id="pub_notice" style="clear:both;'.(($level != 2) ? 'display:none;':'').'">
			<label>&nbsp;</label>
			<span class="output" style="width:82%;">'.$hc_lang_user['PubNotice'].'</span>
		</div>
		<label>'.$hc_lang_user['UserStatus'].'</label>
		<select name="banned" id="banned" onchange="togLevel(this.options[this.selectedIndex].value);">
			<option'.(($banned == 0) ? ' selected="selected"':'').' value="0">'.$hc_lang_user['UserStatus0'].'</option>
			<option'.(($banned == 1) ? ' selected="selected"':'').' value="1">'.$hc_lang_user['UserStatus1'].'</option>
		</select>
		<label>'.$hc_lang_user['UserAPI'].'</label>
		<select name="api" id="api">
			<option'.(($api_access == 0) ? ' selected="selected"':'').' value="0">'.$hc_lang_user['UserAPI0'].'</option>
			<option'.(($api_access == 1) ? ' selected="selected"':'').' value="1">'.$hc_lang_user['UserAPI1'].'</option>
		</select>
		<label for="location">'.$hc_lang_user['Location'].'</label>
		<input name="location" id="location" type="text" maxlength="250" size="50" value="'.$location.'" />
		<label for="birthdate">'.$hc_lang_user['Birthdate'].'</label>
		<input name="birthdate" id="birthdate" type="text" size="12" maxlength="10" value="'.$birthdate.'" />
		<a href="javascript:;" onclick="calx.select(document.getElementById(\'birthdate\'),\'cal1\',\''.$hc_cfg[51].'\');return false;" id="cal1" class="ds calendar" tabindex="-1"></a>
		<label>'.$hc_lang_user['Categories'].'</label>';
		
		$query = ($cat_limited != '') ? "
					SELECT c.PkID, c.CategoryName, c.ParentID, c.CategoryName as Sort, IF(c.PkID IN (".cIn($cat_limited)."),1,NULL) as Selected
					FROM " . HC_TblPrefix . "categories c 
						LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (c.PkID = ec.CategoryID)
					WHERE c.ParentID = 0 AND c.IsActive = 1
					GROUP BY c.PkID, c.CategoryName, c.ParentID
					UNION SELECT c.PkID, c.CategoryName, c.ParentID, c2.CategoryName as Sort, IF(c.PkID IN (".cIn($cat_limited)."),1,NULL) as Selected
					FROM " . HC_TblPrefix . "categories c 
						LEFT JOIN " . HC_TblPrefix . "categories c2 ON (c.ParentID = c2.PkID) 
						LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (c.PkID = ec.CategoryID) 
					WHERE c.ParentID > 0 AND c.IsActive = 1
					GROUP BY c.PkID, c.CategoryName, c.ParentID, c2.CategoryName
					ORDER BY Sort, ParentID, CategoryName" : NULL;
		getCategories('frmUserEdit', 3, $query,1);
		
		echo '
	</fieldset>
	<input type="submit" name="submit" id="submit" value="'.$hc_lang_user['Save'].'" />
	<input type="button" name="cancel" id="cancel" value="'.$hc_lang_user['Cancel'].'" onclick="window.location.href=\''.AdminRoot.'/index.php?com=user\'" />
	<input type="hidden" name="uID" id="uID" value="'.$uID.'" />
	</form>
	<div id="dsCal" class="datePicker"></div>
	
	<script src="'.CalRoot.'/inc/javascript/validation.js"></script>
	<script src="'.CalRoot.'/inc/lang/'.$_SESSION['LangSet'].'/popCal.js"></script>
	<script src="'.CalRoot.'/inc/javascript/DateSelect.js"></script>
	<script>
	//<!--
	var calx = new CalendarPopup("dsCal");
	calx.showYearNavigation();
	calx.showYearNavigationInput();
	calx.setCssPrefix("hc_");
	calx.offsetX = 30;
	calx.offsetY = -5;
	
	function validate(){
		var err = "";

		if(document.getElementById("email").value != "")
			err +=validEmail(document.getElementById("email"),"'.$hc_lang_user['Valid10'].'\n");
		
		if(document.getElementById("birthdate").value != ""){
			err += validDate(document.getElementById("birthdate"),"'.$hc_cfg[51].'","'.$hc_lang_user['Valid11'].' '.strtoupper($hc_cfg[51]).'\n");
			err += validDateBefore(document.getElementById("birthdate").value,"'.strftime($hc_cfg[24],strtotime("-13 years")).'","'.$hc_cfg[51].'","'.$hc_lang_user['Valid12'].'");
		}
		
		if(err != ""){
			alert(err);
			return false;
		} else {
			return true;
		}
	}
	function togLevel(active){
		document.getElementById("pub_notice").style.display = (active == 2) ? "block" : "none";
	}
	//-->
	</script>';
	}
?>