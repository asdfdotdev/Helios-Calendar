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

	echo '<img src="' . CalRoot . '/images/openid/logo.png" width="200px" height="61" alt="OpenID Logo" style="float:right;padding:10px;" />';
	echo $hc_lang_login['LoginAbout1'];
	echo '<br /><br />';?>