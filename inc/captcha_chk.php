<?php
/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development LLC
 * @link http://www.refreshmy.com
 * @copyright (C) 2004-2011 Refresh Web Development
 * @license http://www.helioscalendar.com/license.html
 * @package Helios Calendar
 */
	define('isHC',true);
	define('isAction',true);
	include('../loader.php');
	action_headers();
		
	if(isset($_SESSION['hc_cap'])){
		$capEntered = isset($_GET['capEntered']) ? cIn($_GET['capEntered']) : '';
		
		echo $_SESSION['hc_cap'] == md5($capEntered) ?
			'<span style="color:#008000;">'.$hc_lang_core['Correct'].'</span>' :
			'<span style="color:#DC143C;">'.$hc_lang_core['Incorrect'].' <a href="javascript:;" onclick="testCAPTCHA();"  tabindex="-1">'.$hc_lang_core['ConfirmAgain'].'</a>';
	} else {
		echo $hc_lang_core['RefreshPage'];
	}
?>