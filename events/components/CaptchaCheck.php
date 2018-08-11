<?php
/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development, LLC.
 * @link http://www.refreshmy.com
 * @copyright (C) 2004-2011 Refresh Web Development
 * @license http://www.helioscalendar.com/license.html
 * @package Helios Calendar
 */
	$isAction = 1;
	include('../includes/include.php');
	include('../' . $hc_langPath . $_SESSION['LangSet'] . '/public/register.php');
		
	if(isset($_SESSION['hc_cap'])){
		$capEntered = isset($_GET['capEntered']) ? cIn($_GET['capEntered']) : '';
		
		echo $_SESSION['hc_cap'] == md5($capEntered) ?
			'<div class="hc_align" style="color:#008000;">&nbsp;' . $hc_lang_register['Correct'] . '&nbsp;</div>' :
			'<div class="hc_align" style="color:#DC143C;">&nbsp;' . $hc_lang_register['Incorrect'] . '&nbsp;</div> <a href="javascript:;" onclick="testCAPTCHA();" class="eventMain">' . $hc_lang_register['ConfirmAgain'] . '</a>';
	} else {
		echo $hc_lang_register['RefreshPage'];
	}//end if?>