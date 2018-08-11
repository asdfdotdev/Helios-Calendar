<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/public/login.php');

	echo '<div class="login">';
	if(!isset($_SESSION[$hc_cfg00 . 'hc_OpenIDLoggedIn'])){
        echo '<a href="' . CalRoot . '/index.php?com=login" class="oidMenu">' . $hc_lang_login['Login'] . '</a> | ';
		echo '<a href="' . CalRoot . '/index.php?com=about" class="oidMenu">' . $hc_lang_login['SignUp'] . '</a>';
	} else {
		echo '<a href="' . CalRoot . '/index.php?com=oacc" class="oidMenu">' . $hc_lang_login['MyAccount'] . '</a> |';
		echo ' <a href="' . CalRoot . '/index.php?com=ocomm" class="oidMenu">' . $hc_lang_login['Comments'] . '</a> | ';
		echo '[ <b><a href="' . CalRoot . '/openid/LogOut.php" class="oidMenu">' . $hc_lang_login['SignOut'] . '</a> ]</b>';
	}//end if
	echo '</div>';?>