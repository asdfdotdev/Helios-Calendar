<?php
/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development LLC
 * @link http://www.refreshmy.com
 * @copyright (C) 2004-2011 Refresh Web Development
 * @license http://www.helioscalendar.com/license.html
 * @package Helios Calendar
 */
	define('isHC',true);
	define('isAction',true);
	include_once(dirname(__FILE__).'/loader.php');
	require_once(HCPATH . HCINC . '/functions/theme.php');

	/**
	 * Output wall calendar style monthly mini-calendar to a page outside of Helios Calendar.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @param string $date [optional] calendar month to create (Default: current system date)
	 * @return void
	 */
	function int_mini_calendar($date = SYSDATE){
		mini_cal_month(SYSDATE);
	}
	/**
	 * Output an event list to a page outside of Helios Calendar.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @param integer $type list to output, 0 = Billboard Events, 1 = Most Popular Events, 2 = Newest Events (Default:0)
	 * @param integer $eTime [optional] 0 = do NOT include start time, 1 = include event start time in list (Default:0)
	 * @return void
	 */
	function int_event_list($type, $eTime){
		event_list($type, $eTime);
	}
	/**
	 * Output RSS feed and iCalendar subscription links to a page outside of Helios Calendar.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @return void
	 */
	function int_links(){
		theme_links();
	}
	/**
	 * Output mini search form (keyword only search) to a page outside of Helios Calendar.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @param string $holder placeholder for input textbox
	 * @param integer $button [optional] 0 = do NOT output button, 1 = include "search evetns" form submitt button (Default:0)
	 * @return void
	 */
	function int_mini_search($holder = '',$button = 0){
		mini_search($holder, $button);
	}
?>