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
	
     header('Content-Type:application/xrds+xml; charset=UTF-8');
     echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";?>
<xrds:XRDS
     xmlns:xrds="xri://$xrds"
     xmlns:openid="http://openid.net/xmlns/1.0"
     xmlns="xri://$xrd*($v*2.0)">
     <XRD>
          <Service priority="1">
               <Type>http://specs.openid.net/auth/2.0/return_to</Type>
               <URI><?php echo CalRoot;?>/openid/LoginCatch.php</URI>
          </Service>
     </XRD>
</xrds:XRDS>