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
	/**
	 * Regex pattern used to match mobile browser user agent strings for mobile browser redirect.
	 * @link http://www.user-agents.org/
	 */
	$hc_mobile = '/android|astel|avantgo|audiovox|blackberry|blazer|cldc|compal|droid|docomo|ddipocket|elaine|epoc|fennec|hiptop|ibisbrowser|iemobile|ip(hone|od)|j-phone|j2me|htc|kindle|maemo|midp|minimo|mmp|mobile|netfront|nokia|o2|opera m(ob|in)i|opwv|palm|phonifier|plink|plucker|pocket|polaris|playstation|regking|scope|smartphone|symbian(os)|tear|teleca|treo|up.(browser|link)|vodafone|wap|wii|windows (ce|phone)|wireless|xiino/i';
	/**
	 * Regex pattern used to match common bot user agents to prevent their event and newsletter views from being counted.
	 * @link http://www.user-agents.org/
	 */
	$hc_bots = '/(bot|crawler|indexing|checker|sleuth|seeker)/';
?>