<?php
/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development LLC
 * @link http://www.refreshmy.com
 * @copyright (C) 2004-2012 Refresh Web Development
 * @license http://www.helioscalendar.com/license.html
 * @package Helios Calendar
 */
	define('hcAdmin',true);
	include('../loader.php');
	
	admin_logged_in();
	action_headers();
	post_only();
	
	$result = doQuery("SELECT GROUP_CONCAT(PkID) FROM " . HC_TblPrefix . "events WHERE IsActive = 0 OR IsApproved = 0 OR StartDate = '0000-00-00'");
	if(hasRows($result) & mysql_result($result,0,0) != '')
		doQuery("DELETE FROM " . HC_TblPrefix . "eventnetwork WHERE EventID IN (" . mysql_result($result,0,0) . ")");
	
	doQuery("DELETE FROM " . HC_TblPrefix . "events WHERE IsActive = 0 OR IsApproved = 0 OR StartDate = '0000-00-00'");
	doQuery("DELETE en FROM " . HC_TblPrefix . "eventnetwork en LEFT JOIN " . HC_TblPrefix . "events e ON (e.PkID = en.EventID) WHERE e.PkID IS NULL OR e.IsActive = 0 OR e.IsApproved = 0");
	doQuery("DELETE FROM " . HC_TblPrefix . "locations WHERE IsActive = 0");
	doQuery("DELETE ln FROM " . HC_TblPrefix . "locationnetwork ln LEFT JOIN " . HC_TblPrefix . "locations l ON (l.PkID = ln.LocationID) WHERE l.PkID IS NULL OR l.IsActive = 0");
	doQuery("DELETE ec FROM " . HC_TblPrefix . "eventcategories ec LEFT JOIN " . HC_TblPrefix . "events e ON (ec.EventID = e.PkID) WHERE e.PkID is NULL OR e.IsActive = 0 OR e.IsApproved = 0");
	doQuery("DELETE FROM " . HC_TblPrefix . "categories WHERE IsActive = 0");
	doQuery("DELETE FROM " . HC_TblPrefix . "templates WHERE IsActive = 0");
	doQuery("DELETE FROM " . HC_TblPrefix . "templatesnews WHERE IsActive = 0");
	doQuery("DELETE FROM " . HC_TblPrefix . "mailers WHERE IsActive = 0");
	doQuery("DELETE mg FROM " . HC_TblPrefix . "mailersgroups mg LEFT JOIN " . HC_TblPrefix . "mailers m ON (m.PkID = mg.MailerID) WHERE m.PkID IS NULL OR m.IsActive = 0");
	doQuery("DELETE FROM " . HC_TblPrefix . "mailgroups WHERE IsActive = 0");
	doQuery("DELETE FROM " . HC_TblPrefix . "newsletters WHERE IsActive = 0");
	doQuery("DELETE ns FROM " . HC_TblPrefix . "newssubscribers ns LEFT JOIN " . HC_TblPrefix . "newsletters n ON (n.PkID = ns.NewsletterID) WHERE n.PkID IS NULL OR n.IsActive = 0");
	doQuery("DELETE sc FROM " . HC_TblPrefix . "subscriberscategories sc LEFT JOIN " . HC_TblPrefix . "subscribers s ON (s.PkID = sc.UserID) WHERE s.PkID IS NULL");
	doQuery("DELETE sg FROM " . HC_TblPrefix . "subscribersgroups sg LEFT JOIN " . HC_TblPrefix . "subscribers s ON (s.PkID = sg.UserID) WHERE s.PkID IS NULL");
	doQuery("DELETE f FROM " . HC_TblPrefix . "followup f
			LEFT JOIN " . HC_TblPrefix . "events e ON (f.EntityID = e.PkID AND f.EntityType = 1 AND e.IsActive = 1)
			LEFT JOIN " . HC_TblPrefix . "events e2 ON (f.EntityID = e2.SeriesID AND f.EntityType = 2 AND e2.IsActive = 1)
			LEFT JOIN " . HC_TblPrefix . "locations l ON (f.EntityID = l.PkID AND f.EntityType = 3 AND l.IsActive = 1)
			WHERE e.PkID IS NULL AND e2.SeriesID IS NULL AND l.PkID IS NULL");
	
	header('Location: ' . AdminRoot . '/index.php?com=db');
?>