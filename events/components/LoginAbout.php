<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2009 Refresh Web Development [www.RefreshMy.com]
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/

	include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/public/login.php');

	echo '<img src="' . CalRoot . '/images/openid/logo.png" width="200px" height="61" alt="" border="0" style="float:right;padding:10px;" />';
	echo $hc_lang_login['LoginAbout1'];
	echo '<br /><br />';?>