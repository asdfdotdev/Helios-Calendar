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

	echo '<div class="login">';
	if(!isset($_SESSION[$hc_cfg00 . 'hc_OpenIDLoggedIn'])){
        echo '<a href="' . CalRoot . '/index.php?com=login" class="oidMenu">' . $hc_lang_login['Login'] . '</a> | ';
		echo '<a href="' . CalRoot . '/index.php?com=about" class="oidMenu">' . $hc_lang_login['SignUp'] . '</a>';
	} else {
		echo '<a href="' . $_SESSION[$hc_cfg00 . 'hc_OpenID'] . '" class="oidLink" target="_blank">' . $_SESSION[$hc_cfg00 . 'hc_OpenIDShortName'] . '</a> ';
		echo '<a href="' . CalRoot . '/index.php?com=ocomm" class="oidMenu">' . $hc_lang_login['Comments'] . '</a> | ';
		echo '<b><a href="' . CalRoot . '/openid/LogOut.php" class="oidMenu">' . $hc_lang_login['LogOut'] . '</b>';
	}//end if
	echo '</div>';?>