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
     $isAction = 1;
	include('../includes/include.php');
	
     header('X-XRDS-Location: ' . CalRoot . '/openid/xrds.php');
?>
<html><head>
<meta http-equiv="X-XRDS-Location" content="<?php echo CalRoot;?>/openid/xrds.php"/>
<meta http-equiv="Refresh" CONTENT="0; URL=../">
</head><body></body></html>