<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/public/login.php');

	echo '<img src="' . CalRoot . '/images/openid/logo.png" width="200px" height="61" alt="OpenID Logo" style="float:right;padding:10px;" />';
	echo $hc_lang_login['LoginAbout1'];
	echo '<br /><br />';?>