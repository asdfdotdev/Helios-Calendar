<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(!defined('HCSETUP')){exit(-1);}

	include('../inc/config.php');
	
	session_name('hc_setup_'.md5(HC_Install));
	session_start();
	
	$curVersion = "3.0";
	$setup = true;
	$sID = (isset($_GET['step']) && is_numeric($_GET['step'])) ? strip_tags($_GET['step']) : 1;
	$_SESSION['code_valid'] = ((isset($_POST['start']) && $_POST['start'] == HC_Install && HC_Install != '') || (isset($_SESSION['code_valid']) && $_SESSION['code_valid'] == 1)) ? true : false;
	$_SESSION['is_install'] = (!isset($_SESSION['is_install'])) ? is_install() : $_SESSION['is_install'];
	
	function hc_fail(){
		echo '
		Helios Calendar is unable to continue the Web Setup Wizard for one of the following reasons:
		<ol>
			<li>Sessions are not working correctly for your PHP install.</li>
			<li>You are trying to skip ahead without following each step in order.</li>
		</ol>
		<p style="padding-top:10px;">
			<a href="index.php">Click here to restart the Helios Calendar Web Setup Wizard.</a>
			<br /><br />If you see this message again submit a support ticket by clicking the "Refresh Members Site" link to the right.
		</p>';
	}	
	function php_current($version){
		return ($version != '' && $version >= '5.3') ? true : false;
	}
	function php_abort($version){
		return ($version != '' && $version < '5.2') ? true : false;
	}
	function mysql_current($version){
		return ($version != '' && $version >= '5.5') ? true : false;
	}
	function mysql_abort($version){
		return ($version != '' && $version < '5.0') ? true : false;
	}
	function progress(){
		global $sID;
		
		if(!isset($_SESSION['code_valid']) || $_SESSION['code_valid'] == false){
			echo 'Enter your install code to begin.';
			return;}
		
		echo ($_SESSION['is_install']) ? '
		<b>Step:</b>
		<span'.(($sID == 1) ? ' class="active"' : '').'>Accept License</span> &raquo;
		<span'.(($sID == 2) ? ' class="active"' : '').'>Configuration Review</span> &raquo;
		<span'.(($sID == 3) ? ' class="active"' : '').'>Account &amp; Database Setup</span> &raquo;
		<span'.(($sID == 0) ? ' class="active"' : '').'>Finished</span>' : '<b>Welcome to the Helios Calendar upgrade utility.</b>';
	}
	function get_step(){
		if(!isset($_SESSION['code_valid']) || $_SESSION['code_valid'] == false){
			include(HCSETUP.'/includes/start.php');
			return;}
		
		if($_SESSION['is_install'] == false)
			get_step_upgrade();
		else
			get_step_install();
	}
	function get_step_install(){
		global $sID, $curVersion;
		
		if(!file_exists(HCSETUP.'/includes/step1.php')){
			echo 'This version of Helios Calendar supports upgrades only.';
			return;}
		
		switch($sID){
			case 2:
				include(HCSETUP.'/includes/step2.php');
				break;
			case 3:
				include(HCSETUP.'/includes/step3.php');
				break;
			case 4:
				include(HCSETUP.'/includes/step4.php');
				break;
			case 0:
				include(HCSETUP.'/includes/finished.php');
				break;
			case 1:
			default:
				include(HCSETUP.'/includes/step1.php');
				break;
		}
	}
	function get_step_upgrade(){
		global $curVersion;
		
		if(!file_exists(HCSETUP.'/includes/upgrade.php')){
			echo 'This version of Helios Calendar supports new installation only.';
			return;}
			
		include(HCSETUP.'/includes/upgrade.php');
	}
	function is_install(){
		try {
			$dbc = mysql_connect(DB_HOST, DB_USER, DB_PASS);
			mysql_select_db(DB_NAME,$dbc);
			$result = mysql_query("SELECT COUNT(*) FROM ".HC_TblPrefix."settings");
			
			if($result && mysql_result($result,0,0) > 0)
				   return false;
			else
				return true;
		} catch(Exception $e) {
			return true;
		}
	}
?>