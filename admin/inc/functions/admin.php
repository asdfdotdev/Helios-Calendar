<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(!defined('hcAdmin')){header("HTTP/1.1 403 No Direct Access");exit();}
	
	function admin_logged_in(){
		if(!defined('SAFE_REFER') && (!isset($_SERVER['HTTP_REFERER']) || (isset($_SERVER['HTTP_REFERER']) && !stristr($_SERVER['HTTP_REFERER'], CalRoot)))){
			killAdminSession();
		}			
		if(!isset($_SESSION['AdminLoggedIn']) || $_SESSION['AdminLoggedIn'] != true){
			header('Location: '.AdminRoot);
			exit();
		}
	}
	function killAdminSession(){
		global $session_a;
		
		if(isset($_SESSION['AdminPkID']))
			doQuery("UPDATE " . HC_TblPrefix . "admin SET Access = NULL WHERE PkID = '" . cIn($_SESSION['AdminPkID']) . "'");

		$session_a->end();
	}
	function startNewSession(){
		global $session_a, $hc_cfg;
		$aUser = (isset($_SESSION['AdminPkID'])) ? cIn($_SESSION['AdminPkID']) : 0;
		
		$resultAS = doQuery("SELECT Access FROM " . HC_TblPrefix . "admin WHERE PkID = '" . $aUser . "'");
		$knownSession = (hasRows($resultAS)) ? mysql_result($resultAS,0,0) : NULL;
		
		if($knownSession != md5(session_id()))
			killAdminSession();
		
		$session_a->start(true);
		
		doQuery("UPDATE " . HC_TblPrefix . "admin SET Access = '" . cIn(md5(session_id())) . "' WHERE PkID = '" . $aUser . "'");
	}
	function appInstructions($noSwitch, $codex, $title, $message){
		$style = $msgTxt = '';
		if($_SESSION['Instructions'] == 1){
			$mvIcon = 'collapse';
			$msgTxt = '<p>' . $message . '</p>';
		} else {
			$mvIcon = 'expand';
			$style = ' i_close';
		}
		echo '
		<div class="instruction'.$style.'">
			<h5>' . $title . '</h5>
			<div class="links">
				'.(($codex != '') ? '<a href="http://helioscalendar.org/documentation/index.php?title=Helios:_'.$codex.'" target="_blank"><img src="'.AdminRoot.'/img/icons/help.png" width="16" height="16" alt="" /></a>' : '').'
				'.(($noSwitch == 0) ? '<a href="' . AdminRoot . '/dialog.php"><img src="'.AdminRoot.'/img/icons/switch_'.$_SESSION['Instructions'].'.png" width="16" height="16" alt="" /></a>' : '').'
			</div>
			'.$msgTxt.'
		</div>';
	}
	function stampToDateAP($timeStamp,$useYear = 1){
          $stampParts = explode(" ", $timeStamp);
		$dateParts = explode("-", $stampParts[0]);
		$dateFormat = ($useYear == 1) ? ' %#d, %Y' : ' %#d';

		switch($dateParts[1]){
			case 1:
			case 2:
			case 8:
			case 9:
			case 10:
			case 11:
			case 12:
				$dateFormat = '%b.' . $dateFormat;
				break;
			default:
				$dateFormat = '%B' . $dateFormat;
				break;
		}

		switch(stampToDate($timeStamp,'%w')){
			case 0:
				$dateFormat = 'Sun., ' . $dateFormat;
				break;
			case 1:
				$dateFormat = 'Mon., ' . $dateFormat;
				break;
			case 2:
				$dateFormat = 'Tues., ' . $dateFormat;
				break;
			case 3:
				$dateFormat = 'Wed., ' . $dateFormat;
				break;
			case 4:
				$dateFormat = 'Thurs., ' . $dateFormat;
				break;
			case 5:
				$dateFormat = 'Fri., ' . $dateFormat;
				break;
			case 6:
				$dateFormat = 'Sat., ' . $dateFormat;
				break;
		}

		$theDate = strftime($dateFormat, mktime(0, 0, 0, $dateParts[1], $dateParts[2], $dateParts[0]));

		if(isset($stampParts[1]))
			$theDate .= ' ' . timeToAp($stampParts[1]);
		
		return $theDate;
	}
	function timeToAP($timeStamp){
		global $hc_cfg;
		$timeFormat = ($hc_cfg[31] == 12) ? '%#I:%M' : '%H:%M';
		$timeParts = explode(":", $timeStamp);
		switch($timeParts[0]){
			case 12:
			case 00:
				if($timeParts[1] == 00){
					$time = ($timeParts[0] == 12) ? 'Noon' : 'Midnight';
					break;
				}
			default:
				$time = strftime($timeFormat, mktime($timeParts[0], $timeParts[1], 0, date("m"), date("d"), date("Y")));
				if($hc_cfg[31] == 12)
					$time .= ($timeParts[0] >= 12) ? ' p.m.' : ' a.m.';
		}
		return $time;
	}
	function daysDiff($date1, $date2, $swap = 1){
		$date1 = date("U", strtotime($date1));
		$date2 = date("U", strtotime($date2));
		if($swap == 1)
			$secs = ($date1 > $date2) ? $date1 - $date2 : $date2 - $date1;
		else
			$secs = $date1 - $date2;
		$days = number_format(((($secs / 60) / 60) / 24) + 1,0,',','');
		
		return $days;
	}
	function location_select(){
		global $hc_cfg, $hc_lang_core, $hc_lang_submit;
		
		if($hc_cfg[70] == 1){
			echo '
			<label for="locSearchText">'.$hc_lang_core['LocSearch'].'</label>
			<input type="text" name="locSearchText" id="locSearchText" onkeyup="searchLocations();" size="30" maxlength="100" placeholder="'.$hc_lang_submit['PlaceLocSearch'].'" value="" autocomplete="off" x-webkit-speech onwebkitspeechchange="searchLocations();" />
				<span class="output">&nbsp;<a href="javascript:;" onclick="setLocation(0,\'\',1);">'.$hc_lang_core['ClearSearch'].'</a></span>
			<label class="blank">&nbsp;</label>
			<div id="loc_results">'.$hc_lang_core['CheckLocInst'].'</div>';
		} else {
			$NewAll = $hc_lang_submit['PlaceLocSelect'];
			echo '
			<div class="locSelect"><label for="locListI">'.$hc_lang_core['Preset'].'</label>';	
				if(!file_exists(HCPATH.'/cache/locLista.php'))
					buildCache(2,1);
				include(HCPATH.'/cache/locLista.php');
				echo '
			</div>';
		}
	}
	function has_pending(){
		$pending = 0;
		if(isset($_SESSION['AdminLoggedIn']) && $_SESSION['AdminLoggedIn'] == true){
			$result = doQuery("SELECT COUNT(PkID) FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 2");
			$pending = (mysql_result($result,0,0) > 0) ? 1 : 0;
		}
		return $pending;
	}
	function eventbrite_get_organizers(){
		global $hc_cfg, $found_organizers;
				
		$found_organizers = array();
		
		if(!is_writable(HCPATH.'/cache/'))
			exit("Cache directory cannot be written to.");
		
		if(!file_exists(HCPATH.'/cache/eb_org.php')){
			include_once(HCPATH.HCINC.'/api/eventbrite/OrganizerGet.php');
			
			ob_start();
			$fp = fopen(HCPATH.'/cache/eb_org.php', 'w');
			
			echo "<?php\n\$found_organizers = array(";

			foreach($found_organizers as $id => $name){
				echo "\n\t'".$id."'\t=>\t'".$name."',";
			}
			echo ");\n?>";
			
			fwrite($fp, ob_get_contents());
			fclose($fp);
			ob_end_clean();
		}
		
		include(HCPATH.'/cache/eb_org.php');
		
		return $found_organizers;
	}
?>