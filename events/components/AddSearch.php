<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2009 Refresh Web Development [www.RefreshMy.com]
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	$isAction = 1;
	include('../includes/include.php');
	
	header ('Content-Type:text/xml; charset=utf-8');
	echo "<?xml version=\"1.0\"?>\n";
	echo "<OpenSearchDescription xmlns=\"http://a9.com/-/spec/opensearch/1.1/\">\n\t";
	echo "<ShortName>" . CalName . "</ShortName>\n\t";
	echo "<Description>Use " . CalName . " to search our event calendar.</Description>\n\t";
	echo "<Image height=\"16\" width=\"16\" type=\"image/x-icon\">" . CalRoot . "/images/favicon.png</Image>\n\t";
	echo "<Url type=\"text/html\" method=\"get\" template=\"" . CalRoot . "/index.php?com=searchresult&amp;k={searchTerms}\" />\n";
	echo "</OpenSearchDescription>";