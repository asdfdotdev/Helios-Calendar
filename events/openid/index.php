<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
     $isAction = 1;
	include('../includes/include.php');
	
     header('X-XRDS-Location: ' . CalRoot . '/openid/xrds.php');
?>
<html><head>
<meta http-equiv="X-XRDS-Location" content="<?php echo CalRoot;?>/openid/xrds.php"/>
<meta http-equiv="Refresh" CONTENT="0; URL=../">
</head><body></body></html>