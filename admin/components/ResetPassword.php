<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	define('hcAdmin',true);
	include('../loader.php');
	
	action_headers();
	post_only();
	
	$token = (isset($_POST['token'])) ? cIn(strip_tags($_POST['token'])) : '';
	if(!check_form_token($token))
		go_home();
	
	$a = (isset($_POST['a'])) ? cIn(strip_tags($_POST['a'])) : 0;
	$b = (isset($_POST['b'])) ? cIn(strip_tags($_POST['b'])) : 0;
	$pass1 = (isset($_POST['pass1'])) ? cIn(strip_tags($_POST['pass1'])) : '';
	$pass2 = (isset($_POST['pass2'])) ? cIn(strip_tags($_POST['pass2'])) : '';
	$valid = ($hc_cfg[91] == 1) ? validPassword($pass1) : true;
	$target = (!$valid) ? 'Location: ' . AdminRoot . '/index.php?lp=2&k='.$b.'&lmsg=6' : 'Location: ' . AdminRoot . '/';
	
	$result = doQuery("SELECT PkID, Email, LoginCnt, Passwrd FROM " . HC_TblPrefix . "admin WHERE PCKey = '" . $b . "'");
	if(hasRows($result) && $valid){
		if(md5(mysql_result($result,0,0).mysql_result($result,0,1).mysql_result($result,0,2)) == $a){
			$target = 'Location: ' . AdminRoot . '/index.php?lp=2&k='.$b.'&lmsg=5';
			if($pass1 == $pass2){
				if(md5(md5($pass1) . mysql_result($result,0,1)) == mysql_result($result,0,3)){
					$target = 'Location: ' . AdminRoot . '/index.php?lp=2&k='.$b.'&lmsg=6';
				} else {
					doQuery("UPDATE " . HC_TblPrefix . "admin SET Passwrd = '" . md5(md5($pass1) . mysql_result($result,0,1)) . "', PCKey = NULL, PAge = '". date("Y-m-d") ."' WHERE PkID = '" . mysql_result($result,0,0) . "'");
					$target = 'Location: ' . AdminRoot . '/index.php?lmsg=4';
				}
			}
		} else {
			doQuery("UPDATE " . HC_TblPrefix . "admin SET PCKey = NULL WHERE PkID = '" . mysql_result($result,0,0) . "'");
		}
	}
	header($target);
?>