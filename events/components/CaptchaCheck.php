<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	$isAction = 1;
	include('../includes/include.php');
	include('../' . $hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/public/register.php');
		
	if(isset($_SESSION[$hc_cfg00 . 'hc_cap'])){
		$capEntered = "";
		if(isset($_GET['capEntered'])){
			$capEntered = $_GET['capEntered'];
		}//end if
		
		echo $_SESSION[$hc_cfg00 . 'hc_cap'] == md5($capEntered) ? 
			"<span style=\"color:#008000;\">" . $hc_lang_register['Correct'] . "</span>&nbsp;&nbsp;" :
			"<span style=\"color:#DC143C;\">" . $hc_lang_register['Incorrect'] . "</span>&nbsp;<a href=\"javascript:;\" onclick=\"testCAPTCHA();\" class=\"eventMain\">" . $hc_lang_register['ConfirmAgain'] . "</a>";
	} else {
		echo $hc_lang_register['RefreshPage'];
	}//end if?>