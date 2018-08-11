<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2007 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
	
	~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	|	Modifying Helios Setup files is not permitted and violates the Helios EUL.	|
	|	Please do not edit this or any of the setup files							|
	~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
*/
		if(isset($_POST['agree']) && $_POST['agree'] == 'on'){
			$_SESSION['license'] = true;
		}//end if
		
		if(!isset($_SESSION['license'])){?>
			<br /><a href="<?php echo CalRoot;?>/setup/" class="eventMain">Click here to begin Helios setup.</a>
<?php	} else {	?>
			<br />
			<div style="float:left;width:380px;">
				Before continuing please review your globals.php configuration.
				<br /><br />
				If these settings are not correct please
				edit them. Incorrect settings in your globals.php file will cause Helios to work improperly, or not at all.
				<br /><br />
			
				[ <i>Helios_Directory</i>/includes/globals.php ] current settings:
				<br /><br />
				
				<div style="float:left;width:120px;padding-left:25px;"><b>Table Prefix:</b></div>
				<div style="float:left;width:200px;"><?php echo HC_TblPrefix;?></div>
				<br /><br />
				<div style="float:left;width:120px;padding-left:25px;"><b>DB Host:</b></div>
				<div style="float:left;width:200px;"><?php echo DATABASE_HOST;?></div>
				<br /><br />
				<div style="float:left;width:120px;padding-left:25px;"><b>Database:</b></div>
				<div style="float:left;width:200px;"><?php echo DATABASE_NAME;?></div>
				<br /><br />
				<div style="float:left;width:120px;padding-left:25px;"><b>DB User:</b></div>
				<div style="float:left;width:200px;"><?php echo DATABASE_USER;?></div>
				<br /><br />
				<div style="float:left;width:120px;padding-left:25px;"><b>DB Password:</b></div>
				<div style="float:left;width:200px;">
		<?php	$disPass = "";
				for($i=0; $i < strlen(DATABASE_PASS); $i++){
					$disPass .= "x";
				}//end for
				echo $disPass;	?>
				</div>
				<br /><br />
				<div style="float:left;width:120px;padding-left:25px;"><b>Helios Root:</b></div>
				<div style="float:left;width:200px;"><?php echo CalRoot;?>/</div>
				<br /><br />
				<div style="float:left;width:120px;padding-left:25px;"><b>Helios Admin:</b></div>
				<div style="float:left;width:200px;"><?php echo CalAdminRoot;?>/</div>
				<br /><br />
				<div style="float:left;width:120px;padding-left:25px;"><b>Mobile Root:</b></div>
				<div style="float:left;width:200px;"><?php echo MobileRoot;?></div>
				<br /><br />
				<div style="float:left;width:120px;padding-left:25px;"><b>Admin Contact:</b></div>
				<div style="float:left;width:200px;"><a href="mailto:<?php echo CalAdminEmail;?>" class="eventMain"><?php echo CalAdmin;?></a></div>
				<br /><br />
				If any of this information is incorrect update the globals.php before continuing. Database Username 
				and Password may not contain null values.<br /><br />
				<b>Please make sure</b> that the database account you are using has create &amp; alter permissions.
				<br /><br />
			</div>
			<div style="float:left;width:125px;text-align:center;">
		<?php	$dbconnection = mysql_connect(DATABASE_HOST, DATABASE_USER, DATABASE_PASS);
				mysql_select_db(DATABASE_NAME,$dbconnection);
				
				$result = mysql_query("SELECT version()");
				if(DATABASE_HOST == ''){
					$stop = true;	?>
					<span style="color: crimson;font-weight:bold;">Database Hostname Required</span><br /><br /><?php
				}//end if
				if(DATABASE_NAME == ''){
					$stop = true;	?>
					<span style="color: crimson;font-weight:bold;">Database Name Required</span><br /><br /><?php
				}//end if
				if(DATABASE_USER == ''){
					$stop = true;	?>
					<span style="color: crimson;font-weight:bold;">Database Username Required</span><br /><br /><?php
				}//end if
				if(DATABASE_PASS == ''){
					$stop = true;	?>
					<span style="color: crimson;font-weight:bold;">Database Password Required</span><br /><br /><?php
				}//end if
				if($rootURL == ''){
					$stop = true;	?>
					<span style="color: crimson;font-weight:bold;">Helios URL Root Required</span><br /><br /><?php
				}//end if
				if(CalAdmin == ''){
					$stop = true;	?>
					<span style="color: crimson;font-weight:bold;">Admin Contact Name Required</span><br /><br /><?php
				}//end if
				if(CalAdminEmail == ''){
					$stop = true;	?>
					<span style="color: crimson;font-weight:bold;">Admin Contact Email Required</span><br /><br /><?php
				}//end if
				
				if(hasRows($result) && DATABASE_HOST != '' && DATABASE_NAME != '' && DATABASE_USER != '' && DATABASE_PASS != '' && $rootURL != '' && CalAdmin != '' && CalAdminEmail != ''){
					$_SESSION['mysqlversion'] = mysql_result($result,0,0);
			?>		<span style="color: green;font-weight:bold;">global.php Configuration Complete</span>	<?php
				} elseif(DATABASE_HOST != ''&& DATABASE_NAME != '' && DATABASE_USER != '' && DATABASE_PASS != '' && $rootURL != '' && CalAdmin != '' && CalAdminEmail != ''){
					$stop = true;	?>
					<span style="color: crimson;font-weight:bold;">Database Connection Failed</span>
		<?php	}//end if	?>
		</div>
		<div align="right">
	<?php	if(isset($stop)){?>
			<input type="button" name="refresh" id="refresh" onclick="window.location.href='<?php echo CalRoot;?>/setup/index.php?step=2';return false;" value="Refresh" class="eventButton" />
	<?php	} else {	?>
			<input type="button" name="refresh" id="refresh" onclick="window.location.href='<?php echo CalRoot;?>/setup/?step=3';return false;" value="Continue" class="eventButton" />
	<?php	}//end if	?>
		</div>
<?php	}//end if?>