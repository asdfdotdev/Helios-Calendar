<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
	
	~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	|	Modifying or in anyway altering source code contained in this file is 		|
	|	not permitted and violates the Helios Calendar Software License Agreement	|
	|	DO NOT edit or reverse engineer any source code or files with this notice	|
	~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
*/
	$_SESSION['license'] = (((isset($_POST['agree']) && $_POST['agree'] == 'on')) || (isset($_SESSION['license']) && ($_SESSION['license'] == true))) ? true : false;
	
	if(!isset($_SESSION['license']) || $_SESSION['license'] == false){
		fail();
	} else {
		$stop = false;
		$warn = false;
		echo '<br />';
		
		if($stop == false && !is_writable('../cache')){
			$stop = true;
			echo '<div class="error">Cannot Write to Cache Directory.</div>';
		}//end if
		if($stop == false && !is_writable('../uploads')){
			$stop = true;
			echo '<div class="error">Cannot Write to Uploads Directory.</div>';
		}//end if
		if($stop == false && !is_writable('../includes/phpopenid/_HeliosCalendar_consumers')){
			$stop = true;
			echo '<div class="error">Cannot Write to OpenID Consumers Directory.</div>';
		}//end if
		if($stop == false && DATABASE_HOST == ''){
			$stop = true;
			echo '<div class="error">Database Hostname Required</div>';
		}//end if
		if($stop == false && DATABASE_NAME == ''){
			$stop = true;
			echo '<div class="error">Database Name Required</div>';
		}//end if
		if($stop == false && DATABASE_USER == ''){
			$stop = true;
			echo '<div class="error">Database Username Required</div>';
		}//end if
		if($stop == false && DATABASE_PASS == ''){
			$stop = true;
			echo '<div class="error">Database Password Required</div>';
		}//end if
		if($stop == false && $rootURL == ''){
			$stop = true;
			echo '<div class="error">$rootURL Setting Required</div>';
		}//end if
		if($stop == false && (strpos($rootURL,'http://') != 0 || strpos($rootURL,'http://') === false)){
			$stop = true;
			echo '<div class="error">$rootURL Must Begin With http://</div>';
		}//end if
		if($stop == false && CalAdmin == ''){
			$stop = true;
			echo '<div class="error">Admin Contact Name (CalAdmin) Required</div>';
		}//end if
		if($stop == false && CalAdminEmail == ''){
			$stop = true;
			echo '<div class="error">Admin Contact Email (CalAdminEmail) Required</div>';
		}//end if
		if($stop == false){
			$dbconnection = mysql_connect(DATABASE_HOST, DATABASE_USER, DATABASE_PASS);
			$result = mysql_query("SELECT version()");
			if(!mysql_select_db(DATABASE_NAME,$dbconnection)){
				$stop = true;
				echo '<div class="error">Database Connection Failed - Verify your MySQL password &amp; user privileges.</div>';
			}//end if
		}//end if
		if($stop == false && HC_TblPrefix == ''){
			$_SESSION['good'] = true;
			$warn = true;
			echo '<div class="warning">Table Prefix is optional but strongly recommended. Consider changing it before continuing.</div>';
		}//end if
		if($stop == false && $warn == false){
			$_SESSION['good'] = true;
			$_SESSION['mysqlversion'] = mysql_result($result,0,0);
			echo '<div class="info">Your global.php configuration is complete, READ THIS PAGE then click Continue below.</div>';
		}//end if
			
		echo '<br /><fieldset>';
		echo '<b>Globals File Current Settings:</b> [ <i>Your_Install_Path</i>/events/includes/globals.php ]';
		
		echo '<br /><br />';
		echo '<div style="float:left;width:170px;padding-left:25px;"><b>Table Prefix:</b></div>';
		echo '<div style="float:left;width:400px;">' . HC_TblPrefix . '</div>';
		
		echo '<br /><br />';
		echo '<div style="float:left;width:170px;padding-left:25px;"><b>MySQL Hostname:</b></div>';
		echo '<div style="float:left;width:400px;">' . DATABASE_HOST . '</div>';
		
		echo '<br /><br />';
		echo '<div style="float:left;width:170px;padding-left:25px;"><b>MySQL Database:</b></div>';
		echo '<div style="float:left;width:400px;">' . DATABASE_NAME . '</div>';
		
		echo '<br /><br />';
		echo '<div style="float:left;width:170px;padding-left:25px;"><b>MySQL User:</b></div>';
		echo '<div style="float:left;width:400px;">' . DATABASE_USER . '</div>';
		
		echo '<br /><br />';
		echo '<div style="float:left;width:170px;padding-left:25px;"><b>MySQL Password:</b></div>';
		echo '<div style="float:left;width:400px;">';
		$disPass = '';
		for($i=0; $i < strlen(DATABASE_PASS); $i++){
			$disPass .= "x";
		}//end for
		echo $disPass;
		echo '</div>';
		
		echo '<br /><br />';
		echo '<div style="float:left;width:170px;padding-left:25px;"><b>Public Calendar:</b></div>';
		echo '<div style="float:left;width:400px;">' . CalRoot . '/</div>';
		
		echo '<br /><br />';
		echo '<div style="float:left;width:170px;padding-left:25px;"><b>Mobile Calendar:</b></div>';
		echo '<div style="float:left;width:400px;">' . MobileRoot . '</div>';
		
		echo '<br /><br />';
		echo '<div style="float:left;width:170px;padding-left:25px;"><b>Administration Console:</b></div>';
		echo '<div style="float:left;width:400px;">' . CalAdminRoot . '/</div>';
		
		echo '<br /><br />';
		echo '<div style="float:left;width:170px;padding-left:25px;"><b>Admin Contact:</b></div>';
		echo '<div style="float:left;width:400px;"><a href="mailto:' . CalAdminEmail . '" class="main">' . CalAdmin . '</a></div>';
		
		echo '<br /><br />';
		echo 'To make changes update your globals.php and reupload it before continuing.';
		echo '<br /><br />';
		echo '<b>IMPORTANT:</b> Confirm the following, or setup will not be successful.';
		echo '<ol>';
		echo '<li>The MySQL User has Select/Insert/Update/Delete/Create/Alter privileges granted for the database listed above.</li>';
		echo '<li>The Public Calendar URL listed above matches your current location minus "setup/index.php?step=2".</li>';
		echo '</ol></fieldset><br />';
		
		echo ($stop == true) ?
			'<input type="button" name="button" id="button" onclick="window.location.href=\'index.php?step=2\';return false;" value="Refresh" class="button" />' :
			'<input type="button" name="button" id="button" onclick="window.location.href=\'' . CalRoot . '/setup/index.php?step=3\';return false;" value="Continue" class="button" />';
	}//end if?>