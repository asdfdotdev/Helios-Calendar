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
	include($hc_langPath . $_SESSION['LangSet'] . '/public/menu.php');
	
	echo '<div id="menu"><ul>';
	echo '<li><a href="' . CalRoot . '/index.php" class="eventMenu">' . $hc_lang_menu['Events'] . '</a></li>';
	echo ($hc_cfg45 == 1 && $hc_cfg26 != '') ? '<li><a href="' . CalRoot . '/index.php?com=location" class="eventMenu">' . $hc_lang_menu['Locations'] . '</a></li>':'';
	echo ($hc_cfg1 == 1) ? '<li><a href="' . CalRoot . '/index.php?com=submit" class="eventMenu">' . $hc_lang_menu['Submit'] . '</a></li>':'';
	echo '<li><a href="' . CalRoot . '/index.php?com=search" class="eventMenu">' . $hc_lang_menu['Search'] . '</a></li>';
	echo ($hc_cfg54 == 1) ? '<li><a href="' . CalRoot . '/index.php?com=newsletter" class="eventMenu">' . $hc_lang_menu['Newsletter'] . '</a></li>':'';
	echo '<li><a href="' . CalRoot . '/index.php?com=tools" class="eventMenu">' . $hc_lang_menu['Tools'] . '</a></li>';
	echo '<li><a href="' . CalRoot . '/index.php?com=rss" class="eventMenu">' . $hc_lang_menu['RSS'] . '</a></li>';
	echo '</ul></div>';?>