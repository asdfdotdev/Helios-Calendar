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
		$capEntered = isset($_GET['capEntered']) ? cIn($_GET['capEntered']) : '';
		
		echo $_SESSION[$hc_cfg00 . 'hc_cap'] == md5($capEntered) ? 
			'<div class="hc_align" style="color:#008000;">&nbsp;' . $hc_lang_register['Correct'] . '&nbsp;</div>' :
			'<div class="hc_align" style="color:#DC143C;">&nbsp;' . $hc_lang_register['Incorrect'] . '&nbsp;</div> <a href="javascript:;" onclick="testCAPTCHA();" class="eventMain">' . $hc_lang_register['ConfirmAgain'] . '</a>';
	} else {
		echo $hc_lang_register['RefreshPage'];
	}//end if?>