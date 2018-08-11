<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	define('hcAdmin',true);
	include('../loader.php');
	
	admin_logged_in();
	action_headers();
	
	$token = '';
	$token = ($token == '' && isset($_POST['token'])) ? cIn(strip_tags($_POST['token'])) : $token;
	$token = ($token == '' && isset($_GET['tkn'])) ? cIn(strip_tags($_GET['tkn'])) : $token;
	
	if(!check_form_token($token))
		go_home();
	
	$msgID = 1;
	$delIDs = array();
	$apiFail = false;
	$uID = (isset($_GET['uID']) && is_numeric($_GET['uID'])) ? cIn(strip_tags($_GET['uID'])) : 0;
	
	if(isset($_GET['series'])){
		$result = doQuery("SELECT GROUP_CONCAT(PkID) FROM " . HC_TblPrefix . "events WHERE SeriesID = '".cIn(strip_tags($_GET['series']))."'");
		if(hasRows($result))
			$delIDs = explode(',',mysql_result($result,0,0));
	} elseif(isset($_POST['eventID'])) {
		$delIDs = (isset($_POST['eventID'])) ? $_POST['eventID'] : array();
	} elseif(isset($_GET['dID'])){
		$delIDs[] = $_GET['dID'];
	}
	
	$delIDs = cIn(implode(',',array_filter($delIDs,'is_numeric')));
	
	if($delIDs != ''){
		doQuery("UPDATE " . HC_TblPrefix . "events SET IsActive = 0 WHERE PkID IN(" . $delIDs . ")");
		clearCache();

		$resultD = doQuery("SELECT NetworkID, NetworkType FROM " . HC_TblPrefix . "eventnetwork WHERE EventID IN (" . $delIDs . ") ORDER BY NetworkType");
		if(hasRows($resultD)){
			while($row = mysql_fetch_row($resultD)){
				$netID = $row[0];
				switch($row[1]){
					case 1:
						//	Nothing
						break;
					case 2:
						include(HCPATH.HCINC.'/api/eventbrite/EventDelete.php');
						break;
					case 5:
						include(HCPATH.HCINC.'/api/facebook/EventDelete.php');
						break;
				}
			}
		}
	}
		
	if($apiFail != false)
		exit();
	
	if(isset($_GET['oID']) OR isset($_POST['oID']))
		header("Location: " . AdminRoot . "/index.php?com=eventorphan&msg=1");
	elseif(isset($_POST['pID']) || isset($_GET['pID']))
		header("Location: " . AdminRoot . "/index.php?com=eventpending&msg=5");
	elseif(isset($_GET['dpID']) || isset($_POST['dpID']))
		header("Location: " . AdminRoot . "/index.php?com=reportdup&msg=1");
	elseif(isset($_GET['rpID']))
		header("Location: " . AdminRoot . "/index.php?com=reportdup&msg=1");
	elseif(isset($_GET['uID']))
		header("Location: " . AdminRoot . "/index.php?com=useredit&uID=".$uID."&msg=1");
	else
		header("Location: " . AdminRoot . "/index.php?com=eventsearch&sID=2&msg=".$msgID);
?>