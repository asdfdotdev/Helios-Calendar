<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(!defined('isHC')){exit(-1);}
	
	/**
	 * Output user account menu.
	 * @since 2.1.0
	 * @version 2.1.0
	 * @return void
	 */
	function user_menu(){
		global $hc_cfg, $hc_lang_user;
		
		echo '<nav>
		<ul id="user_nav">
			<li><a href="'.CalRoot.'/index.php?com=acc" class="user_menu">'.$hc_lang_user['MenuHome'].'</a></li>
			<li><a href="'.CalRoot.'/index.php?com=acc&amp;sec=edit" class="user_menu">'.$hc_lang_user['MenuEdit'].'</a></li>
			<li><a href="'.CalRoot.'/index.php?com=acc&amp;sec=list" class="user_menu">'.$hc_lang_user['MenuEvents'].'</a></li>
		</ul>
		</nav>';
	}
	/**
	 * Output user account tools based on current status & location.
	 * @since 2.1.0
	 * @version 2.1.0
	 * @return void
	 */
	function user_account(){
		global $hc_cfg, $hc_lang_user;
				
		if(user_is_new()){
			echo '
			<p class="new_user_notice">'.$hc_lang_user['NewUserNotice'].'</p>';
			
			user_manage_account();
		} else {
			$user_com = (isset($_GET['sec'])) ? cIn(strip_tags($_GET['sec'])) : '';
			
			switch($user_com){
				case 'list':
					user_manage_events();
					break;
				case 'edit':
					user_manage_account();
					break;
				default:
					user_manage_welcome();
					break;
			}
		}
	}
	/**
	 * Output user account welcome message.
	 * @since 2.1.0
	 * @version 2.1.0
	 * @return void 
	 */
	function user_manage_welcome(){
		global $hc_lang_user;
		
		echo '
		<fieldset class="user">
			<legend>'.$hc_lang_user['UserLabel'].'</legend>
			<p>'.$hc_lang_user['UserWelcome'].'</p>
			<p>'.$hc_lang_user['UserAccNotice1'].' '.$hc_lang_user['Network'.$_SESSION['UserNetType']].' '.$hc_lang_user['UserAccNotice2'].'</p>
		</fieldset>';
	}
	/**
	 * Output user account settings form.
	 * @since 2.1.0
	 * @version 2.1.0
	 * @return void
	 */
	function user_manage_account(){
		global $hc_cfg, $hc_lang_user;
		
		if(!user_check_status())
			return -1;
		
		$uID = cIn($_SESSION['UserPkID']);
		$result = doQuery("SELECT NetworkType, NetworkName, NetworkID, Email, FirstSignIn, Level, Location, Birthdate, APIKey, APIAccess
						FROM " . HC_TblPrefix . "users WHERE PkID = '" . $uID . "'");
		
		if(!hasRows($result) or !user_check_status())
			return -1;
		
		if(isset($_GET['msg'])){
			switch ($_GET['msg']){
				case "1" :
					feedback(1,$hc_lang_user['Feed01']);
					break;
			}
		}
		
		$network = cOut(mysql_result($result,0,0));
		$network_name = cOut(mysql_result($result,0,1));
		$network_id = cOut(mysql_result($result,0,2));
		$email = (isset($_SESSION['new_user_email']) && $_SESSION['new_user_email'] != '') ? cIn(strip_tags($_SESSION['new_user_email'])) : cOut(mysql_result($result,0,3));
		$signin_first = cOut(mysql_result($result,0,4));
		$level = cOut(mysql_result($result,0,5));
		$location = cOut(mysql_result($result,0,6));
		$api_key = cOut(mysql_result($result,0,8));
		$api_access = cOut(mysql_result($result,0,9));
		$birthdate = (isset($_SESSION['new_user_birthdate']) && $_SESSION['new_user_birthdate'] != '') ? cIn(strip_tags($_SESSION['new_user_birthdate'])) : stampToDate(mysql_result($result,0,7), $hc_cfg[24]);
		
		echo '
		<form name="user_edit" id="user_edit" method="post" action="'.CalRoot.'/user-edit.php" onsubmit="return validate();">
		<fieldset class="user">
			<legend>'.$hc_lang_user['UserEditLabel'].'</legend>
			<label>'.$hc_lang_user['Network'].'</label>
			<span class="output">'.$hc_lang_user['Network'.$network].'</span>
			<label>'.$hc_lang_user['Level'].'</label>
			<span class="output">'.$hc_lang_user['Level'.$level].'</span>
			<label>'.$hc_lang_user['Name'].'</label>
			<span class="output">'.$network_name.'</span>
			<label for="email"><b>'.$hc_lang_user['Email'].'</b></label>
			<input name="email" id="email" type="email" maxlength="75" size="35" value="'.$email.'" required="required" />
			<label for="birthdate"><b>'.$hc_lang_user['Birthdate'].'</b></label>
			<input name="birthdate" id="birthdate" type="text" size="12" maxlength="10" value="'.$birthdate.'" required="required" />
			<a href="javascript:;" onclick="calx.select(document.getElementById(\'birthdate\'),\'cal1\',\''.$hc_cfg[51].'\');return false;" id="cal1" class="ds calendar" tabindex="-1"></a>
			<label for="user_loc">'.$hc_lang_user['Location'].'</label>
			<input name="user_loc" id="user_loc" type="text" maxlength="250" size="50" value="'.$location.'" />
		</fieldset>';
		
		if($hc_cfg[127] == 1){
			echo '
		<fieldset>
			<legend>'.$hc_lang_user['API'].'</legend>';
			
			echo ($api_access == 1 && $email != '' && $birthdate != '') ? '
			<label>&nbsp;</label>
			<span class="output">'.$hc_lang_user['APIHelp'].'</span>
			<label>'.$hc_lang_user['APIEndpoint'].'</label>
			<input size="50" maxlength="200" type="text" readonly="readonly" value="'.CalRoot.'/api/" onfocus="this.select();" />
			<label>'.$hc_lang_user['Username'].'</label>
			<input size="20" maxlength="200" type="text" readonly="readonly" value="'.$network_name.'" onfocus="this.select();" />
			<label>'.$hc_lang_user['Key'].'</label>
			<input size="45" maxlength="100" type="text" readonly="readonly" value="'.$api_key.'" onfocus="this.select();" />
			<label>&nbsp;</label>
			<span class="frm_ctrls">
				<label for="regen_apik"><input name="regen_apik" id="regen_apik" type="checkbox" onclick="" />'.$hc_lang_user['RegenKey'].'</label>
			</span>':'
			<label>&nbsp;</label>
			<span class="output">'.$hc_lang_user['APIUnavailable'].($api_access == 1 ? ' '.$hc_lang_user['ActivateAPI']:'').'</span>';
		
			echo '
		</fieldset>';
		}
		
		echo '
		<input type="submit" name="submit" id="submit" value="'.$hc_lang_user['Save'].'" />
		<input type="reset" name="reset" id="reset" value="'.$hc_lang_user['Reset'].'" />
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
			
			err += reqField(document.getElementById("email"),"'.$hc_lang_user['Valid14'].'\n");
			if(document.getElementById("email").value != "")
				err +=validEmail(document.getElementById("email"),"'.$hc_lang_user['Valid10'].'\n");
			
			err += reqField(document.getElementById("birthdate"),"'.$hc_lang_user['Valid13'].'\n");
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
		//-->
		</script>';
	}
	/**
	 * Output user's list of submitted events.
	 * @since 2.1.0
	 * @version 2.2.1
	 * @return void
	 */
	function user_manage_events(){
		global $hc_cfg, $hc_lang_user;
		
		if(!user_check_status())
			return -1;
		
		$date = (isset($_GET['d']) && is_numeric($_GET['d'])) ? strftime("%Y-%m-%d",cIn(strip_tags($_GET['d']))) : SYSDATE;
		$d = explode('-',$date);
		$year = (isset($d[0]) && is_numeric($d[0])) ? $d[0] : NULL;
		$month = (isset($d[1]) && is_numeric($d[1])) ? $d[1] : NULL;
		$day = (isset($d[2]) && is_numeric($d[2])) ? $d[2] : NULL;

		if(!checkdate($month, $day, $year)){
			$date = SYSDATE;
			$day = date('d', strtotime(SYSDATE));
			$month = date('m', strtotime(SYSDATE));
			$year = date('Y', strtotime(SYSDATE));}
		
		$sqlStart = $year.'-'.$month.'-1';
		$sqlEnd = strftime("%Y-%m-%d",mktime(0,0,0,($month+1),0,$year));
		
		$result = doQuery("SELECT DISTINCT e.PkID, e.Title, e.StartDate, e.StartTime, e.EndTime, e.TBD, e.IsApproved, e.SeriesID, er.Type, er.Space,
							(SELECT COUNT(PkID) FROM " . HC_TblPrefix . "registrants r WHERE EventID = e.PkID) as SpacesTaken
						FROM " . HC_TblPrefix . "events e
							LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (ec.EventID = e.PkID)
							LEFT JOIN " . HC_TblPrefix . "eventrsvps er ON (er.EventID = e.PkID)
							LEFT JOIN " . HC_TblPrefix . "locations l ON (e.LocID = l.PkID)
						WHERE e.StartDate BETWEEN '" . cIn($sqlStart) . "' AND '" . cIn($sqlEnd) . "' 
							AND e.IsActive = 1 AND e.OwnerID = '" . cIn($_SESSION['UserPkID']) . "'
						GROUP BY e.PkID, e.Title, e.StartDate, e.StartTime, e.EndTime, e.TBD, e.IsApproved, e.SeriesID, er.Type, er.Space
						ORDER BY e.StartDate, e.TBD, e.StartTime, e.Title");
		$i = 0;
		$jmp = 12;
		$stop = $jmp + 12;
		$jumpMonth = date("n", mktime(0,0,0,$month-$jmp,1,$year));
		$jumpYear = date("Y", mktime(0,0,0,$month-$jmp,1,$year));
		$actJump = date("Y-m-d",mktime(0,0,0,$month,1,$year));
		$opts = '';
		
		while($i <= 24){
			$jmpDate = date("Y-m-d", mktime(0,0,0,$jumpMonth+$i,1,$jumpYear));
			$select = ($jmpDate == $actJump) ? ' selected="selected"' : '';
			$opts .= '
					<option value="'.CalRoot.'/index.php?com=acc&sec=list&d='.date("U", mktime(0,0,0,$jumpMonth + $i,1,$jumpYear)).'"'.$select.'>'.strftime($hc_cfg[92], mktime(0,0,0,$jumpMonth + $i,1,$jumpYear)).'</option>';
			++$i;
		}
		
		echo '
		<fieldset class="user">
			<legend>'.$hc_lang_user['UserEventLabel'].'</legend>
			<ul id="user_events">
				<li id="ue_prev"><a href="'.CalRoot.'/index.php?com=acc&sec=list&d='.strtotime(strftime("%Y-%m-%d",mktime(0,0,0,($month-1),1,$year))).'">&lt;</a></li>
				<li id="ue_jump">
				<select name="ue_jump_select" id="ue_jump_select" onchange="window.location.href=this.value;">'.$opts.'	
				</select>	
				</li>
				<li id="ue_next"><a href="'.CalRoot.'/index.php?com=acc&sec=list&d='.strtotime(strftime("%Y-%m-%d",mktime(0,0,0,($month+1),1,$year))).'">&gt;</a></li>
			</ul>';
		
		if(!hasRows($result)){
			echo '<p>'.$hc_lang_user['NoEventNotice'].'</p>';
			return -1;}
			
		echo '
			<ul class="data">';
		
		$cnt = 1;
		while($row = mysql_fetch_row($result)){
			
			echo '
				<li class="row'.(($cnt % 2 == 0) ? ' hl' : '').'">
					<div class="ue_title txt" title="'.cOut($row[1]).'">'.clean_truncate(cOut($row[1]),100).'</div>
					<div class="ue_date">'.stampToDate($row[2], $hc_cfg[24]).'</div>
					<div class="ue_time txt">';
					if($row[5] == 0){
						$time = ($row[3] != '') ? stampToDate($row[3], $hc_cfg[23]) : '';
					} else {
						$time = ($row[5] == 1) ? $hc_lang_user['AllDay'] : $hc_lang_user['TBD'];
					}
					
					$rsvp = '';
					if($row[8] == 1)
						$rsvp = ($row[10] > 0 && $row[6] != 2) ? 
								'<a href="'.CalRoot.'/download-rsvp.php?eID='.cOut($row[0]).'"><img src="'.CalRoot.'/img/icons/rsvp_download.png" width="16" height="16" alt="" title="'.$hc_lang_user['TitleRSVP'].'" /></a>':
								'<img src="'.CalRoot.'/img/icons/rsvp_download_o.png" width="16" height="16" alt="" title="'.$hc_lang_user['TitleRSVP0'].'" />';
					
					echo $time.'</div>
					<div class="ue_status txt">
						'.$rsvp.$hc_lang_user['Status'.$row[6]].'
					</div>
					<div class="ue_tools tools">
						'.(($row[6] == 2) ? '<img src="'.CalRoot.'/img/icons/single_o.png" width="16" height="16" alt="" title="'.$hc_lang_user['TitleView'].'" />' : '<a href="'.CalRoot.'/index.php?eID='.$row[0].'"><img src="'.CalRoot.'/img/icons/single.png" width="16" height="16" alt="" title="'.$hc_lang_user['TitleView'].'" /></a>').'
						<a href="'.CalRoot.'/index.php?com=submit&amp;eID='.$row[0].'"><img src="'.CalRoot.'/img/icons/edit.png" width="16" height="16" alt="" title="'.$hc_lang_user['TitleEdit'].'" /></a>';
					
					if($row[7] != ''){
						echo (($row[6] == 2) ? '
						<img src="'.CalRoot.'/img/icons/series_o.png" width="16" height="16" alt="" title="'.$hc_lang_user['TitleViewSeries'].'" />' : '<a href="'.CalRoot.'/index.php?com=series&amp;sID='.$row[7].'"><img src="'.CalRoot.'/img/icons/series.png" width="16" height="16" alt="" title="'.$hc_lang_user['TitleViewSeries'].'" /></a>').'
						<a href="'.CalRoot.'/index.php?com=submit&amp;sID='.$row[7].'"><img src="'.CalRoot.'/img/icons/edit_series.png" width="16" height="16" alt="" title="'.$hc_lang_user['TitleEditSeries'].'" /></a>';
					}
					
					echo '
					</div>
				</li>';
			
			++$cnt;
		}
		
		echo '
		</fieldset>';
	}
	/**
	 * Output all available sign in buttons based on current settings.
	 * @since 2.1.0
	 * @version 2.1.0
	 * @return void
	 */
	function signin_options(){
		global $hc_cfg;
		
		if(!($hc_cfg[113]+$hc_cfg[114]+$hc_cfg[115]) > 0)
			return -1;
		
		twitter_signin_button();
		facebook_signin_button();
		google_signin_button();
	}
	/**
	 * Output Sign in with Twitter button, if current settings permit.
	 * @since 2.1.0
	 * @version 2.1.0
	 * @return void
	 */
	function twitter_signin_button(){
		global $hc_cfg, $hc_lang_user;
		
		if($hc_cfg[113] == 0)
			return -1;
		
		echo '
		<a class="btn-auth btn-twitter" id="btn-twitter" href="'.CalRoot.'/signin/twitter.php">'.$hc_lang_user['SigninTwitter'].'</a>';
	}
	/**
	 * Output Sign in with Facebook, if current settings permit.
	 * @since 2.1.0
	 * @version 2.1.0
	 * @return void
	 */
	function facebook_signin_button(){
		global $hc_cfg, $hc_lang_user;
		
		if($hc_cfg[114] == 0)
			return -1;
		
		echo '
		<a class="btn-auth btn-facebook" id="btn-facebook" href="'.CalRoot.'/signin/facebook.php">'.$hc_lang_user['SigninFacebook'].'</a>';
		
	}
	/**
	 * Output Sign in with Google, if current settings permit.
	 * @since 2.1.0
	 * @version 2.1.0
	 * @return void
	 */
	function google_signin_button(){
		global $hc_cfg, $hc_lang_user;
		
		if($hc_cfg[115] == 0)
			return -1;
		
		echo '
		<a class="btn-auth btn-google" id="btn-google" href="'.CalRoot.'/signin/google.php">'.$hc_lang_user['SigninGoogle'].'</a>';
	}
	/**
	 * Get users signed in status for the current session.
	 * @since 2.1.0
	 * @version 2.1.0
	 * @return boolean User's current signed in status: True/False
	 */
	function user_check_status(){
		return (isset($_SESSION['UserLoggedIn']) && $_SESSION['UserLoggedIn'] == 1) ? true : false;
	}
	/**
	 * Destroy user session variables as part of the sign out process.
	 * @since 2.1.0
	 * @version 2.1.0
	 * @return void
	 */
	function user_kill_session(){
		global $session;
		
		$session->end();
	}
	/**
	 * Regenerate the user's session id, end old session & expire old session cookie if present.
	 * @since 2.1.0
	 * @version 2.1.0
	 * @return void
	 */
	function user_new_session(){
		global $session, $hc_cfg;
		
		$session->start(true);
		
		$_SESSION['UserLoginTime'] = date("U");
	}
	/**
	 * "Register" a new user by inserting a new record for the user within the users datatable. Only done on their first sign in, created record is referenced for future sign ins and user administration.
	 * @since 2.1.0
	 * @version 2.1.0
	 * @param int $network Network ID (internal) 1 = Twitter, 2 = Facebook, 3 = Google
	 * @param string $net_name User's name provided by the network API.
	 * @param string $net_id Users's id # provided by the network API.
	 * @return int PkID for newly created record within the users table.
	 */
	function user_register_new($network,$net_name,$net_id){
		global $hc_cfg;
				
		doQuery("INSERT INTO " . HC_TblPrefix . "users(NetworkType, NetworkName, NetworkID, FirstSignin, LastSignIn, LastIP, Level, IsPrivate, APIKey, APIAccess, APICnt)
				Values('".$network."','".cIn(utf8_decode($net_name))."','".cIn($net_id)."','".SYSDATE.' '.SYSTIME."','".SYSDATE.' '.SYSTIME."',
					'".cIn(strip_tags($_SERVER["REMOTE_ADDR"]))."','0','1','".cIn(md5(sha1($network.$net_name.$net_id.(rand()*date("U")))))."',1,0)");
		$result = doQuery("SELECT LAST_INSERT_ID() FROM " . HC_TblPrefix . "users");
		
		return (hasRows($result)) ? mysql_result($result,0,0) : NULL;
	}
	/**
	 * Update sign in history for the user.
	 * @since 2.1.0
	 * @version 2.1.0
	 * @param int $local_id PkID from the users table within the Helios Calendar database for the user. The local user record to be updated.
	 * @return void
	 */
	function user_update_history($local_id){	
		if(!is_numeric($local_id) || $local_id < 1)
			return -1;
		
		doQuery("UPDATE " . HC_TblPrefix . "users SET LastSignIn = '".SYSDATE." ".SYSTIME."', LastIP = '".cIn(strip_tags($_SERVER["REMOTE_ADDR"]))."', SignIns = (SignIns+1) WHERE PkID = '".cIn($local_id)."'");
	}
	/**
	 * Update status, and variables, for user's current session. Called at regular intervals to rebuild the session id (user_new_session()) & update the user's status (incase of deletion or banning by admin).
	 * @since 2.1.0
	 * @version 2.1.0
	 * @param int $network Network ID (internal) 1 = Twitter, 2 = Facebook, 3 = Google
	 * @param string $net_name User's name provided by the network API.
	 * @param string $net_id Users's id # provided by the network API.
	 * @param integer $signed_in User's current signed in status.
	 * @return void
	 */
	function user_update_status($network,$net_name,$net_id,$signed_in){
		$result = doQuery("SELECT PkID, Level, IsBanned FROM " . HC_TblPrefix . "users WHERE NetworkType = '".cIn($network)."' AND NetworkID = '".cIn($net_id)."'");
		
		if($signed_in == 1 && hasRows($result) && mysql_result($result,0,2) == 0){
			user_new_session();
			$_SESSION['UserLoggedIn'] = 1;
			$_SESSION['UserNetType'] = cIn($network);
			$_SESSION['UserNetName'] = cIn($net_name);
			$_SESSION['UserNetID'] = cIn($net_id);
			$_SESSION['UserPkID'] = mysql_result($result,0,0);
			$_SESSION['UserLevel'] = mysql_result($result,0,1);
		} else {
			session_destroy();
		}
	}
	/**
	 * Check if user is new or if they've previously signed in and completed registration.
	 * @since 2.1.0
	 * @version 2.1.0
	 * return boolean Is user new? true/false
	 */
	function user_is_new(){
		return isset($_SESSION['new_user']) ? true : false;
	}
?>
