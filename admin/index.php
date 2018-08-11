<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
	
	NOTE: You may not alter any logos, legends, branding elements or copyright notices withing the
	Helios Calendar admin console (/admin) unless you have been granted express written permission
	by Refresh Web Development to do so.
	
	Visit: http://www.helioscalendar.com/developers.php for details.
*/
	include('includes/header.php');
	echo '</head>';
	echo (isset($_SESSION[$hc_cfg00 . 'AdminLoggedIn'])) ? '<body>' : '<body onload="document.frm.elements[0].focus();">';

	if(isset($_SESSION[$hc_cfg00 . 'AdminLoggedIn'])){
		echo '<div id="container"><div id="menu"><div class="menuLock">';
		include(HC_AdminMenu);
		echo '</div></div>';
		echo '<div id="content"><div id="cMain">';
		echo '<div style="color: #222222; padding: 10px 0px 7px 0px;">' . strftime($hc_cfg14) . '</div>';
		include(HC_Core);
		echo '</div>';
		echo '<div id="cSub"><div class="cSubLock">';
		echo '<img src="' . CalAdminRoot . '/images/logo.png" width="235" height="56" alt="" border="0" />';
		include(HC_Side);
		echo '</div></div></div>';
	} else {
		echo '<div>';
		include(HC_Login);
	}//end if
	
	echo '<div class="footer" align="center">';
	echo 'Helios Calendar ' . $hc_cfg49 . '<br />Copyright &copy; 2004-' . date("Y") . '<br />';
	echo '<a href="http://www.refreshmy.com" class="copyright" target="_blank">Refresh Web Development, LLC.</a><br />ALL RIGHTS RESERVED';
	echo '</div></div>';
	echo '<script language="JavaScript" type="text/JavaScript" src="' . CalRoot . '/includes/java/wz_tooltip.js"></script>';
	echo '</body></html>';
?>