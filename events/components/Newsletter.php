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
	include($hc_langPath . $_SESSION['LangSet'] . '/public/register.php');

	echo '<br />';
	echo '<div class="newsTools">';
	echo '<a href="' . CalRoot . '/index.php?com=archive" class="archive">' . $hc_lang_register['NewsOpt0'] . '</a>';
	echo '</div>';
	echo $hc_lang_register['Welcome'];
	echo '<br /><fieldset class="newsletter"><legend>' . $hc_lang_register['WelcomeLabel'] . '</legend>';
	echo '<div class="newsLinks">';
	echo '<a href="' . CalRoot . '/index.php?com=signup" class="newreg">' . $hc_lang_register['NewsOpt1'] . '</a>';
	echo $hc_lang_register['Or'];
	echo '<a href="' . CalRoot . '/index.php?com=signup&s=1" class="editreg">' . $hc_lang_register['NewsOpt2'] . '</a><br />';
	echo '</div></fieldset><br />'
?>