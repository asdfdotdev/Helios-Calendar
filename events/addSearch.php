<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2007 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	include('includes/include.php');
	
	header ('Content-Type:text/xml; charset=utf-8');
	echo "<?xml version=\"1.0\"?>\n";
	echo "<OpenSearchDescription xmlns=\"http://a9.com/-/spec/opensearch/1.1/\">\n\t";
	echo "<ShortName>" . CalName . "</ShortName>\n\t";
	echo "<Description>Use " . CalName . " to search our event calendar.</Description>\n\t";
	echo "<Image height=\"16\" width=\"16\" type=\"image/x-icon\">" . CalRoot . "/images/favicon.png</Image>\n\t";
	echo "<Url type=\"text/html\" method=\"get\" template=\"" . CalRoot . "/index.php?com=searchresult&amp;k={searchTerms}\" />\n";
	echo "</OpenSearchDescription>";