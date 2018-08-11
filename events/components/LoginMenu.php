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
	include($hc_langPath . $_SESSION['LangSet'] . '/public/login.php');

	echo '<div class="login">';
	if(!isset($_SESSION['hc_OpenIDLoggedIn'])){
        echo '<a href="' . CalRoot . '/index.php?com=login" class="oidMenu">' . $hc_lang_login['Login'] . '</a> | ';
		echo '<a href="' . CalRoot . '/index.php?com=about" class="oidMenu">' . $hc_lang_login['SignUp'] . '</a>';
	} else {
		echo '<a href="' . CalRoot . '/index.php?com=oacc" class="oidMenu">' . $hc_lang_login['MyAccount'] . '</a> |';
		echo ' <a href="' . CalRoot . '/index.php?com=ocomm" class="oidMenu">' . $hc_lang_login['Comments'] . '</a> | ';
		echo '[ <b><a href="' . CalRoot . '/openid/LogOut.php" class="oidMenu">' . $hc_lang_login['SignOut'] . '</a> ]</b>';
	}//end if
	echo '</div>';?>