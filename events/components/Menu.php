<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/public/menu.php');
	
	echo '<div id="menu"><ul>';
	echo '<li><a href="' . CalRoot . '/index.php" class="eventMenu">' . $hc_lang_menu['Events'] . '</a></li>';
	echo ($hc_cfg45 == 1 && $hc_cfg26 != '')?'<li><a href="' . CalRoot . '/index.php?com=location" class="eventMenu">' . $hc_lang_menu['Locations'] . '</a></li>':'';
	echo ($hc_cfg1 == 1)?'<li><a href="' . CalRoot . '/index.php?com=submit" class="eventMenu">' . $hc_lang_menu['Submit'] . '</a></li>':'';
	echo '<li><a href="' . CalRoot . '/index.php?com=search" class="eventMenu">' . $hc_lang_menu['Search'] . '</a></li>';
	echo '<li><a href="' . CalRoot . '/index.php?com=signup" class="eventMenu">' . $hc_lang_menu['Newsletter'] . '</a></li>';
	echo '<li><a href="' . CalRoot . '/index.php?com=tools" class="eventMenu">' . $hc_lang_menu['Tools'] . '</a></li>';
	echo '<li><a href="' . CalRoot . '/index.php?com=rss" class="eventMenu">' . $hc_lang_menu['RSS'] . '</a></li>';
	echo '</ul></div>';?>