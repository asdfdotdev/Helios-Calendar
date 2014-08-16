<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(!isset($_SESSION['license']) || $_SESSION['license'] == false || !isset($_SESSION['review']) || $_SESSION['review'] == false){
		hc_fail();
	} else {
		if(!isset($_POST['firstname'])){?>
			<script src="../inc/javascript/validation.js"></script>
			<script language="JavaScript">
			//<!--
			function validate(){var err = "";err += reqField(document.getElementById("firstname"),"Admin First Name is Required\n");err += reqField(document.getElementById("lastname"),"Admin Last Name is Required\n");err += reqField(document.getElementById("email"),"Admin Email Address is Required\n");if(document.getElementById("email").value != '')err +=validEmail(document.getElementById("email"),"Email Address Format is Invalid\n");err += reqField(document.getElementById("password"),"Admin Password is Required\n");if(err != ""){alert(err);return false;} else {valid_ok(document.getElementById("submit"),"Please Wait...");return true;}}
			//-->
			</script>
			<form name="frm" id="frm" method="post" action="<?php echo CalRoot;?>/setup/index.php?step=3" onsubmit="return validate();">
			<fieldset>
				<b>First Admin Account Settings</b>
				<p>
					Complete the following form to configure your first admin account.
					<br />When setup is complete <b>use this account to sign in to your admin console the first time</b>.
				</p>
				<p>
					<b>NOTE:</b> The <b>email address</b> entered will be your username.
				</p>
				<label for="firstname">First Name:</label>
				<input name="firstname" id="firstname" type="text" size="20" maxlength="50" value="" autofocus="autofocus" required="required" />
				<label for="lastname">Last Name:</label>
				<input name="lastname" id="lastname" type="text" size="25" maxlength="50" value="" required="required" />
				<label for="email">Email (Username):</label>
				<input name="email" id="email" type="email" size="35" maxlength="100" value="" required="required" />
				<label for="password">Password:</label>
				<input name="password" id="password" type="password" size="15" maxlength="15" value="" required="required" />
			</fieldset>
			<input name="submit" id="submit" type="submit" value="Setup Database" />
			</form>
<?php	} else {
			//	Setup Default Database
			echo '
				<p>
					Setup will now attempt to create your Helios Calendar database...
				</p>
				<fieldset>';
			$status = 0;
			$dbc = mysql_connect(DB_HOST, DB_USER, DB_PASS);
			mysql_select_db(DB_NAME,$dbc);

			function doInstall($status, $msg, $query){
				echo '<div style="padding-left:5px;line-height:15px;">' . $msg;
				if(mysql_query($query)){
					echo '<b>Finished</b>';
				} else {
					++$status;
					echo '<span style="color:#DC1461;font-weight:bold;">Failed</span>';
				}
				echo '</div>';
				return $status;
			}

			$status = doInstall($status,"Creating <i>" . HC_TblPrefix . "admin</i> table...",
								"CREATE TABLE " . HC_TblPrefix . "admin (PkID int(11) unsigned NOT NULL auto_increment,FirstName varchar(50) default NULL,LastName varchar(50) default NULL,Email varchar(100) default NULL,Passwrd varchar(32) default NULL,IsActive tinyint(3) unsigned NOT NULL default '1',LoginCnt int(11) unsigned NOT NULL default '0',LastLogin datetime default NULL,SuperAdmin tinyint(3) unsigned NOT NULL default '0',ShowInstructions tinyint(3) unsigned NOT NULL default '1',PCKey varchar(32) default NULL,Access varchar(32) default NULL,PAge date default NULL,PRIMARY KEY (PkID)) Engine=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci");
			$status = doInstall($status,"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Adding First Admin Account...",
								"INSERT INTO " . HC_TblPrefix . "admin VALUES('1','" . $_POST['firstname'] . "','" . $_POST['lastname'] . "','" . $_POST['email'] . "','" . md5(md5($_POST['password']) . $_POST['email']) . "','1', '0', NULL, '0', '1', NULL, NULL, NOW())");
			$status = doInstall($status,"Creating <i>" . HC_TblPrefix . "adminloginhistory</i> table...",
								"CREATE TABLE " . HC_TblPrefix . "adminloginhistory (PkID int(11) unsigned NOT NULL auto_increment,AdminID int(11) unsigned default NULL,IP varchar(40) default NULL,Client TEXT default NULL,LoginTime datetime default NULL,`IsFail` TINYINT(3) UNSIGNED NULL DEFAULT '0',PRIMARY KEY (PkID)) Engine=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci");
			$status = doInstall($status,"Creating <i>" . HC_TblPrefix . "adminnotices</i> table...",
								"CREATE TABLE `" . HC_TblPrefix . "adminnotices` (`PkID` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,`AdminID` INT(11) UNSIGNED NOT NULL DEFAULT '0',`TypeID` INT(11) UNSIGNED NOT NULL DEFAULT '0',`IsActive` INT(11) UNSIGNED NOT NULL DEFAULT '0',PRIMARY KEY (`PkID`))Engine=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci");
			$status = doInstall($status,"Creating <i>" . HC_TblPrefix . "adminpermissions</i> table...",
								"CREATE TABLE " . HC_TblPrefix . "adminpermissions (PkID int(11) unsigned NOT NULL auto_increment,EventEdit int(3) unsigned NOT NULL default '0',EventPending int(3) unsigned NOT NULL default '0',EventCategory int(3) unsigned NOT NULL default '0',UserEdit int(3) unsigned NOT NULL default '0',AdminEdit int(3) unsigned NOT NULL default '0',Newsletter int(3) unsigned NOT NULL default '0',Settings int(3) unsigned NOT NULL default '0', Tools int(3) UNSIGNED DEFAULT '0' NOT NULL, Reports int(3) unsigned NOT NULL default '0', Locations INT(3) UNSIGNED DEFAULT '0' NOT NULL, Comments INT(3) UNSIGNED NOT NULL DEFAULT '0', Pages INT(3) UNSIGNED NOT NULL DEFAULT '0', AdminID int(11) unsigned default NULL,IsActive int(3) unsigned NOT NULL default '0',PRIMARY KEY (PkID)) Engine=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci");
			$status = doInstall($status,"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Adding First Admin Account Privileges...",
								"INSERT INTO " . HC_TblPrefix . "adminpermissions VALUES('1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1')");
			$status = doInstall($status,"Creating <i>" . HC_TblPrefix . "categories</i> table...",
								"CREATE TABLE " . HC_TblPrefix . "categories (PkID int(11) unsigned NOT NULL auto_increment,CategoryName varchar(200) default NULL,ParentID int(11) unsigned NOT NULL default '0',IsActive tinyint(3) unsigned NOT NULL default '1',PRIMARY KEY (PkID)) Engine=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci");
			$status = doInstall($status,"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Adding Default Category...",
								"INSERT INTO " . HC_TblPrefix . "categories VALUES('1', 'Events', '0', '1')");
			$status = doInstall($status,"Creating <i>" . HC_TblPrefix . "eventcategories</i> table...",
								"CREATE TABLE " . HC_TblPrefix . "eventcategories (EventID int(11) unsigned default NULL,CategoryID int(11) unsigned default NULL,KEY EventID (EventID),KEY CategoryID (CategoryID)) Engine=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci");
			$status = doInstall($status,"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Adding Default Event/Category Pairing...",
								"INSERT INTO " . HC_TblPrefix . "eventcategories VALUES('1', '1')");
			$status = doInstall($status,"Creating <i>" . HC_TblPrefix . "eventnetwork</i> table...",
								"CREATE TABLE " . HC_TblPrefix . "eventnetwork (`EventID` int(11) unsigned NOT NULL default '0',`NetworkID` varchar(150) default NULL,`NetworkType` int(11) unsigned default NULL,`IsActive` tinyint(3) unsigned NOT NULL default '0',KEY `NetworkID` (`NetworkID`),KEY `EventID` (`EventID`)) Engine=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci");
			$status = doInstall($status,"Creating <i>" . HC_TblPrefix . "eventrsvps</i> table...",
								"CREATE TABLE `" . HC_TblPrefix . "eventrsvps` (`PkID` int(11) unsigned NOT NULL AUTO_INCREMENT,`Type` int(3) unsigned NOT NULL DEFAULT '0',`EventID` int(11) DEFAULT NULL,`OpenDate` date DEFAULT NULL,`CloseDate` date DEFAULT NULL,`Space` int(11) unsigned DEFAULT '0',`RegOption` int(3) unsigned DEFAULT '0',`Notices` int(3) unsigned NOT NULL DEFAULT '0',PRIMARY KEY (`PkID`),KEY `EventID` (`EventID`)) Engine=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci");
			$status = doInstall($status,"Creating <i>" . HC_TblPrefix . "events</i> table...",
								"CREATE TABLE `" . HC_TblPrefix . "events` (`PkID` int(11) unsigned NOT NULL AUTO_INCREMENT,`Title` varchar(255) DEFAULT NULL,`LocationName` text,`LocationAddress` varchar(75) DEFAULT NULL,`LocationAddress2` varchar(75) DEFAULT NULL,`LocationCity` varchar(50) DEFAULT NULL,`LocationState` varchar(30) DEFAULT NULL,`LocationZip` varchar(50) DEFAULT NULL,`Description` mediumtext,`StartDate` date DEFAULT NULL,`StartTime` time DEFAULT NULL,`TBD` int(3) DEFAULT NULL,`EndTime` time DEFAULT NULL,`ContactName` varchar(50) DEFAULT NULL,`ContactEmail` varchar(75) DEFAULT NULL,`ContactPhone` varchar(25) DEFAULT NULL,`IsActive` tinyint(3) unsigned NOT NULL DEFAULT '1',`IsApproved` tinyint(3) unsigned NOT NULL DEFAULT '0',`IsBillboard` tinyint(3) unsigned NOT NULL DEFAULT '0',`SeriesID` varchar(20) DEFAULT NULL,`SubmittedByName` varchar(50) DEFAULT NULL,`SubmittedByEmail` varchar(75) DEFAULT NULL,`SubmittedAt` datetime DEFAULT NULL,`AlertSent` tinyint(3) unsigned NOT NULL DEFAULT '0',`ContactURL` varchar(100) DEFAULT NULL,`PublishDate` datetime DEFAULT NULL,`Views` int(11) unsigned NOT NULL DEFAULT '0',`Message` mediumtext,`Directions` int(11) unsigned NOT NULL DEFAULT '0',`Downloads` int(11) unsigned NOT NULL DEFAULT '0',`EmailToFriend` int(11) unsigned NOT NULL DEFAULT '0',`URLClicks` int(11) unsigned NOT NULL DEFAULT '0',`MViews` int(11) unsigned NOT NULL DEFAULT '0',`LocID` int(11) unsigned DEFAULT '0',`Cost` varchar(50) DEFAULT NULL,`LocCountry` varchar(50) DEFAULT NULL,`ShortURL` varchar(50) DEFAULT NULL,`LastMod` datetime DEFAULT NULL,`Image` varchar(100) DEFAULT NULL,`OwnerID` int(11) unsigned NOT NULL DEFAULT '0',`IsFeature` tinyint(3) unsigned NOT NULL DEFAULT '0',`HideDays` int(11) unsigned NOT NULL DEFAULT '0',PRIMARY KEY (`PkID`),Key `Title` (`Title`),KEY `StartDate` (`StartDate`),Key `StartTime` (`StartTime`),KEY `SeriesID` (`SeriesID`),KEY `LocID` (`LocID`),KEY `PublishDate` (`PublishDate`),FULLTEXT KEY `Search` (`Title`,`LocationName`,`Description`)) Engine=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci");
			$status = doInstall($status,"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Adding First Event...",
								"INSERT INTO " . HC_TblPrefix . "events VALUES('1','" . CalName . " Opens',NULL,NULL,NULL,NULL,NULL,NULL,' Welcome to your new Helios Calendar powered event website.<br />The following links will help you as you use Helios Calendar:<br /><br /><a href=\"http://www.helioscalendar.org\" class=\"eventMain\" target=\"new\">Helios Calendar Website</a><br /><a href=\"http://www.refreshmy.com/documentation/?title=Helios\" class=\"eventMain\" target=\"new\">Helios Calendar Documentation</a><br /><a href=\"http://www.refreshmy.com/forum/\" class=\"eventMain\" target=\"new\">Refresh Community Forum</a><br /><br />Thank you for using Helios Calendar.','" . date("Y-m-d") . "',NULL,'1',NULL,NULL,NULL,NULL,'1','1','1',NULL,NULL,NULL,NULL,'0','" . CalRoot . "','" . date("Y-m-d") . " 00:00:00','0',NULL,'0','0','0','0','0','1', NULL, NULL, NULL, NOW(), NULL, '0', '0', '14')");
			$status = doInstall($status,"Creating <i>" . HC_TblPrefix . "followup</i> table...",
								"CREATE TABLE " . HC_TblPrefix . "followup (`PkID` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,`EntityID` VARCHAR(25) NOT NULL DEFAULT '0',`EntityType` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',`Note` TEXT NOT NULL,PRIMARY KEY (`PkID`),INDEX `EntityID_EntityType` (`EntityID`, `EntityType`)) Engine=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci");
			$status = doInstall($status,"Creating <i>" . HC_TblPrefix . "locationnetwork</i> table...",
								"CREATE TABLE " . HC_TblPrefix . "locationnetwork (`LocationID` int(11) unsigned default NULL,`NetworkID` varchar(150) default NULL,`NetworkType` int(11) unsigned default NULL,`IsDownload` TINYINT(3) UNSIGNED NOT NULL DEFAULT '1',`IsActive` tinyint(3) unsigned NOT NULL default '0',KEY `LocationID` (`LocationID`),KEY `NetworkID` (`NetworkID`)) Engine=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci");
			$status = doInstall($status,"Creating <i>" . HC_TblPrefix . "locations</i> table...",
								"CREATE TABLE " . HC_TblPrefix . "locations (PkID int(11) unsigned NOT NULL auto_increment,Name text default null,Address varchar(75),Address2 varchar(75),City varchar(50),State varchar(30),Country varchar(50),Zip varchar(50),URL varchar(100),Phone varchar(25) DEFAULT '0',Email varchar(75),Descript longtext,IsPublic tinyint(3) unsigned NOT NULL DEFAULT '0',IsActive tinyint(3) unsigned NOT NULL DEFAULT '0',URLClicks int(11) unsigned NOT NULL DEFAULT '0',Lat varchar(25) default NULL,Lon varchar(25) default NULL,`GoogleAcc` INT(11) UNSIGNED DEFAULT '0',`ShortURL` VARCHAR(50) NULL DEFAULT NULL,`Views` INT(11) UNSIGNED NOT NULL DEFAULT '0', `LastMod` datetime DEFAULT NULL, `Image` varchar(100) DEFAULT NULL, PRIMARY KEY (PkID),FULLTEXT KEY `LocName` (`Name`)) Engine=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci");
			$status = doInstall($status,"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Adding First Location...",
								"INSERT INTO " . HC_TblPrefix . "locations VALUES('1', 'My First Location', 'Pacific Ave at Main St', NULL, 'Forest Grove', 'OR', 'USA', '97116', 'http://www.helioscalendar.org',NULL,NULL, 'Downtown Forest Grove, Oregon the home of Helios Calendar.', '1', '1', '0', '45.5196666', '-123.1120799', 0, NULL, 0, NOW(), NULL)");
			$status = doInstall($status,"Creating <i>" . HC_TblPrefix . "mailers</i> table...",
								"CREATE TABLE `" . HC_TblPrefix . "mailers` (`PkID` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,`Title` TEXT NULL,`Subject` TEXT NULL,`StartDate` DATE NULL DEFAULT NULL,`EndDate` DATE NULL DEFAULT NULL,`TemplateID` INT(10) UNSIGNED NOT NULL DEFAULT '0',`Message` MEDIUMTEXT NULL,`CreatedDate` DATE NULL DEFAULT NULL,`LastModDate` DATE NULL DEFAULT NULL,`IsArchive` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',`IsActive` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',PRIMARY KEY (`PkID`)) Engine=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci");
			$status = doInstall($status,"Creating <i>" . HC_TblPrefix . "mailersgroups</i> table...",
								"CREATE TABLE `" . HC_TblPrefix . "mailersgroups` (`MailerID` INT(11) UNSIGNED NULL DEFAULT NULL,`GroupID` INT(11) UNSIGNED NULL DEFAULT NULL,INDEX `GroupID` (`GroupID`),INDEX `MailerID` (`MailerID`))Engine=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci");
			$status = doInstall($status,"Creating <i>" . HC_TblPrefix . "mailgroups</i> table...",
								"CREATE TABLE `" . HC_TblPrefix . "mailgroups` (`PkID` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,`Name` VARCHAR(250) NULL DEFAULT NULL COLLATE 'latin1_swedish_ci',`Description` TEXT NULL COLLATE 'latin1_swedish_ci',`IsPublic` TINYINT(3) UNSIGNED NULL DEFAULT '0',`IsActive` TINYINT(3) UNSIGNED NULL DEFAULT '0',PRIMARY KEY (`PkID`))Engine=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci");
			echo '<div style="padding-left:5px;line-height:15px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Creating Default Subscriber Groups...';
			mysql_query("INSERT INTO `" . HC_TblPrefix . "mailgroups` (`PkID`, `Name`, `Description`, `IsPublic`, `IsActive`) VALUES (1, 'All Subscribers', '', 0, 1)");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "mailgroups` (`PkID`, `Name`, `Description`, `IsPublic`, `IsActive`) VALUES (2, 'Test Subscribers', 'Internal email addresses for various mail services. Used for test mailings.', 0, 1)");
			echo '<b>Finished</b></div>';
			$status = doInstall($status,"Creating <i>" . HC_TblPrefix . "newsletters</i> table...",
								"CREATE TABLE `" . HC_TblPrefix . "newsletters` (`PkID` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,`Subject` TEXT NULL,`StartDate` DATE NULL DEFAULT NULL,`EndDate` DATE NULL DEFAULT NULL,`TemplateID` INT(10) UNSIGNED NOT NULL DEFAULT '0',`Message` MEDIUMTEXT NULL,`ArchiveContents` LONGTEXT NULL,`SentDate` DATE NULL DEFAULT NULL,`SendCount` INT(11) UNSIGNED NOT NULL DEFAULT '0',`Status` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',`SendingAdminID` INT(11) UNSIGNED NOT NULL DEFAULT '0',`MailerID` INT(11) UNSIGNED NOT NULL DEFAULT '0',`IsArchive` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',`IsActive` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',`Views` INT(11) UNSIGNED NOT NULL DEFAULT '0',`ArchViews` INT(11) UNSIGNED NOT NULL DEFAULT '0',PRIMARY KEY (`PkID`),INDEX `SendDate` (`SentDate`))Engine=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci");
			$status = doInstall($status,"Creating <i>" . HC_TblPrefix . "newssubscribers</i> table...",
								"CREATE TABLE `" . HC_TblPrefix . "newssubscribers` (`NewsletterID` INT(11) UNSIGNED NULL DEFAULT NULL,`SubscriberID` INT(11) UNSIGNED NULL DEFAULT NULL,INDEX `NewsletterID` (`NewsletterID`),INDEX `SubscriberID` (`SubscriberID`))Engine=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci");
			$status = doInstall($status,"Creating <i>" . HC_TblPrefix . "registrants</i> table...",
								"CREATE TABLE " . HC_TblPrefix . "registrants (PkID int(11) unsigned NOT NULL auto_increment,Name varchar(200) default NULL,Email varchar(75) default NULL,Phone varchar(25) default NULL,Address varchar(75) default NULL,Address2 varchar(75) default NULL,City varchar(50) default NULL,State varchar(50) default NULL,Zip varchar(50) default NULL,EventID int(11) unsigned default NULL,IsActive tinyint(3) unsigned NOT NULL default '1',RegisteredAt datetime default NULL,`GroupID` VARCHAR(50) NULL,PRIMARY KEY (PkID),INDEX `EventID` (`EventID`)) Engine=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci");
			$status = doInstall($status,"Creating <i>" . HC_TblPrefix . "sendtofriend</i> table...",
								"CREATE TABLE " . HC_TblPrefix . "sendtofriend (PkID int(11) unsigned NOT NULL auto_increment,MyName varchar(100) default NULL,MyEmail varchar(100) default NULL,RecipientName varchar(100) default NULL,RecipientEmail varchar(100) default NULL,Message text,EntityID int(11) unsigned default NULL,IPAddress varchar(40) default NULL,SendDate datetime default NULL,`TypeID` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',PRIMARY KEY (PkID)) Engine=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci");
			$status = doInstall($status,"Creating <i>" . HC_TblPrefix . "settings</i> table...",
								"CREATE TABLE " . HC_TblPrefix . "settings (PkID int(11) unsigned NOT NULL auto_increment, SettingValue MEDIUMTEXT, `Name` varchar(50) DEFAULT NULL, PRIMARY KEY (PkID)) Engine=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci");
			echo '<div style="padding-left:5px;line-height:15px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Configuring Default Settings...';
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('1', '1', 'submit_on')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('2', '20', 'rss_max_size')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('3', 'Your event has been approved and is now available on our public calendar. We hope you continue to use our calendar and submit more events in the future. Thank you for using our calendar and if you have any questions please feel free to contact us.', '')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('4', 'Your event has been declined and will not be available on our website. Please do not let this discourage you from submiting more events in the future. Thank you for using our calendar and if you have any questions please feel free to contact us.', '')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('5', NULL, '')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('6', NULL, '')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('7', '1', 'meta_index')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('8', 'https://www.google.com/maps/place/[address],%20[city],%20[region]%20[postalcode]%20[country]', 'link_map')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('9', 'http://www.weather.com/weather/local/[postalcode]', 'link_weather')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('10', '10', 'list_popular_size')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('11', '1', 'browse_past_on')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('12', '10', 'list_billboard_size')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('13', '0', 'list_billboard_autofill')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('14', '%A, %d %B, %Y', 'date_format_long')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('15', '1', 'list_billboard_show_time')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('16', NULL, '')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('17', NULL, '')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('18', NULL, '')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('19', NULL, '')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('20', NULL, '')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('21', NULL, 'region_default')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('22', '0', 'minical_start_day')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('23', '%I:%M %p', 'time_format')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('24', '%m/%d/%Y', 'date_format_short')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('25', NULL, 'comments_disqus_shortname')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('26', '90', '')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('27', '13', 'map_single_zoom')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('28', 'english', 'language_default')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('29', '1', 'submit_allow_categories')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('30', '1', 'editor_on')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('31', '12', 'time_1224')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('32', '0', '')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('33', '1', 'list_rss_combine_series')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('34', '0', 'browse_default_size')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('35', '0', 'timezone_offset')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('36', NULL, '')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('37', NULL, '')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('38', NULL, '')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('39', 'Submitted by Helios Calendar', '')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('40', '75', 'submit_session_limit')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('41', '8', 'map_multi_zoom')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('42', NULL, 'map_multi_center_lat')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('43', NULL, 'map_multi_center_lon')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('44', NULL, '')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('45', '0', 'map_multi_on')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('46', NULL, '')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('47', NULL, '')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('48', '0', 'browse_float_on')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('49', '".$curVersion."', '')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('50', '0', '')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('51', 'MM/dd/yyyy', '')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('52', 'http://maps.google.com', 'map_google_url')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('53', '14', 'search_default_window')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('54', '1', 'newsletter_on')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('55', '1', 'map_library')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('56', '0', 'comments_on')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('57', NULL, '')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('58', NULL, '')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('59', '#helioscal', 'tweet_hashtag')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('60', '50', 'syndication_list_cache_max')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('61', '3.13', '')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('62', NULL, '')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('63', NULL, '')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('64', NULL, '')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('65', '0', 'captcha_on')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('66', '10', 'list_newest_size')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('67', NULL, 'captcha_recaptcha_public')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('68', NULL, 'captcha_recaptcha_private')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('69', '0', 'syndication_map_on')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('70', '1', 'location_select_type')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('71', '0', '')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('72', NULL, '')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('73', NULL, '')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('74', NULL, '')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('75', NULL, '')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('76', NULL, '')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('77', '0', '')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('78', NULL, '')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('79', NULL, '')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('80', '5', '')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('81', '25', '')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES ('82', '2', '')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES (83, 'default', 'theme_default_desktop')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES (84, 'mobile', 'theme_default_mobile')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES (85, '/(bot|crawler|indexing|checker|sleuth|seeker)/', 'filter_bots')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES (86, '/android|astel|avantgo|audiovox|blackberry|blazer|cldc|compal|droid|docomo|ddipocket|elaine|epoc|fennec|hiptop|ibisbrowser|iemobile|ip(hone|od)|j-phone|j2me|htc|kindle|maemo|midp|minimo|mmp|mobile|netfront|nokia|o2|opera m(ob|in)i|opwv|palm|phonifier|plink|plucker|pocket|polaris|playstation|regking|scope|smartphone|symbian(os)|tear|teleca|treo|up.(browser|link)|vodafone|wap|wii|windows (ce|phone)|wireless|xiino/', 'filter_mobile')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES (87, '500', '')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES (88, '99', 'ical_size_max')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES (89, '360', 'ical_refresh')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES (90, 'clean', 'captcha_recaptcha_style')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES (91, '1', '')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES (92, '%b %Y', 'date_format_minical_select')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES (93, '%d', 'date_format_minical_day')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES (94, '1', 'map_olayers_provider')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES (95, NULL, 'map_yahoo_api_key')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES (96, NULL, 'map_bing_api_key')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES (97, '1', 'page_digest_on')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES (98, '<p>This welcome message can be edited within the admin console.</p>', 'page_digest_welcome')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES (99, '7', 'page_digest_new_days')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES (100, '5', 'comments_facebook_posts')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES (101, '685', 'comments_facebook_width')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES (102, NULL, 'comments_livefyre_id')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES (103, NULL, '')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES (104, NULL, '')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES (105, NULL, '')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES (106, '1', 'rss_on')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES (107, '200', 'rss_description_trim')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES (108, '1', 'ical_on')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES (109, '200', 'ical_description_trim')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES (110, NULL, '')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES (111, NULL, '')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES (112, NULL, '')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES (113, '0', 'signin_twitter_on')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES (114, '0', 'signin_facebook_on')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES (115, '0', 'signin_google_on')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES (116, '0', '')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES (117, NULL, '')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES (118, NULL, '')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES (119, NULL, '')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES (120, NULL, '')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES (121, NULL, '')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES (122, NULL, '')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES (123, NULL, '')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES (124, NULL, '')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES (125, NULL, '')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES (126, '0', 'send_past_to_friend')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES (127, '1', 'api_on_off')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES (128, '1', 'api_cache_type')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES (129, '25', 'api_list_size_events')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES (130, '10', 'api_cache_user_limit')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES (131, '10', 'api_cache_user_log_time')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES (132, '10', 'api_list_size_newsletters')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES (133, '1.0', 'api_version')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settings` (`PkID`,`SettingValue`,`Name`) VALUES (134, '14', 'meta_expire_days')");
			echo '<b>Finished</b></div>';
			$status = doInstall($status, "Creating <i>" . HC_TblPrefix . "settingsmeta</i> table...",
								"CREATE TABLE `" . HC_TblPrefix . "settingsmeta` (`PkID` INT(11) NOT NULL AUTO_INCREMENT,`Keywords` TEXT NULL,`Description` TEXT NULL,`Title` TEXT NULL,`Sort` INT(11) UNSIGNED NULL DEFAULT '0',PRIMARY KEY (`PkID`)) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci");
			echo '<div style="padding-left:5px;line-height:15px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Configuring Default Settings...';
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settingsmeta` (`PkID`, `Keywords`,`Description`,`Title`,`Sort`) VALUES ('1',NULL,NULL,NULL,'2')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settingsmeta` (`PkID`, `Keywords`,`Description`,`Title`,`Sort`) VALUES ('2',NULL,NULL,NULL,'3')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settingsmeta` (`PkID`, `Keywords`,`Description`,`Title`,`Sort`) VALUES ('3',NULL,NULL,NULL,'4')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settingsmeta` (`PkID`, `Keywords`,`Description`,`Title`,`Sort`) VALUES ('4',NULL,NULL,NULL,'5')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settingsmeta` (`PkID`, `Keywords`,`Description`,`Title`,`Sort`) VALUES ('5',NULL,NULL,NULL,'6')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settingsmeta` (`PkID`, `Keywords`,`Description`,`Title`,`Sort`) VALUES ('6',NULL,NULL,NULL,'8')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settingsmeta` (`PkID`, `Keywords`,`Description`,`Title`,`Sort`) VALUES ('7',NULL,NULL,NULL,'10')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settingsmeta` (`PkID`, `Keywords`,`Description`,`Title`,`Sort`) VALUES ('8',NULL,NULL,NULL,'11')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settingsmeta` (`PkID`, `Keywords`,`Description`,`Title`,`Sort`) VALUES ('9',NULL,NULL,NULL,'12')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settingsmeta` (`PkID`, `Keywords`,`Description`,`Title`,`Sort`) VALUES ('10',NULL,NULL,NULL,'13')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settingsmeta` (`PkID`, `Keywords`,`Description`,`Title`,`Sort`) VALUES ('11',NULL,NULL,NULL,'7')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settingsmeta` (`PkID`, `Keywords`,`Description`,`Title`,`Sort`) VALUES ('12',NULL,NULL,NULL,'9')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settingsmeta` (`PkID`, `Keywords`,`Description`,`Title`,`Sort`) VALUES ('13',NULL,NULL,NULL,'14')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settingsmeta` (`PkID`, `Keywords`,`Description`,`Title`,`Sort`) VALUES ('14',NULL,NULL,NULL,'1')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settingsmeta` (`PkID`, `Keywords`,`Description`,`Title`,`Sort`) VALUES ('15',NULL,NULL,NULL,'15')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "settingsmeta` (`PkID`, `Keywords`,`Description`,`Title`,`Sort`) VALUES ('16',NULL,NULL,NULL,'16')");

			echo '<b>Finished</b></div>';
			$status = doInstall($status,"Creating <i>" . HC_TblPrefix . "subscribers</i> table...",
								"CREATE TABLE " . HC_TblPrefix . "subscribers (PkID int(11) unsigned NOT NULL auto_increment,FirstName varchar(150) default NULL,LastName varchar(150) default NULL,Email varchar(75) default NULL,OccupationID int(11) default NULL,Zip varchar(10) default NULL,IsConfirm tinyint(3) unsigned NOT NULL default '0',GUID varchar(50) default NULL,AddedBy tinyint(3) unsigned NOT NULL default '0',RegisteredAt datetime default NULL,RegisterIP varchar(40) default NULL,`BirthYear` INT(4) DEFAULT '0' NOT NULL,`Gender` TINYINT(3) UNSIGNED DEFAULT '0' NOT NULL,`Referral` INT(11) UNSIGNED DEFAULT '0' NOT NULL,`Format` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',PRIMARY KEY (PkID)) Engine=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci");
			$status = doInstall($status,"Creating <i>" . HC_TblPrefix . "subscriberscategories</i> table...",
								"CREATE TABLE " . HC_TblPrefix . "subscriberscategories (UserID int(11) unsigned default NULL,CategoryID int(11) unsigned default NULL,KEY CategoryID (CategoryID),KEY UserID (UserID)) Engine=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci");
			$status = doInstall($status,"Creating <i>" . HC_TblPrefix . "subscribersgroups</i> table...",
								"CREATE TABLE `" . HC_TblPrefix . "subscribersgroups` (`UserID` INT(11) UNSIGNED NULL DEFAULT NULL,`GroupID` INT(11) UNSIGNED NULL DEFAULT NULL,INDEX `GroupID` (`GroupID`),INDEX `UserID` (`UserID`)) Engine=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci");
			$status = doInstall($status, "Creating <i>" . HC_TblPrefix . "templates</i> table...",
								"CREATE TABLE `" . HC_TblPrefix . "templates` (`PkID` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,`Name` VARCHAR(255) NULL DEFAULT '',`Content` LONGTEXT NULL DEFAULT NULL,`Header` MEDIUMTEXT NULL DEFAULT NULL,`Footer` MEDIUMTEXT NULL DEFAULT NULL,`Extension` VARCHAR(15) NULL DEFAULT NULL,`TypeID` INT(11) UNSIGNED NULL DEFAULT '1',`GroupBy` SMALLINT(3) UNSIGNED NOT NULL DEFAULT '1',`SortBy` SMALLINT(3) UNSIGNED NOT NULL DEFAULT '1',`CleanUp` MEDIUMTEXT NULL DEFAULT NULL, `DateFormat` TINYINT(3) NOT NULL DEFAULT '0', `IsActive` SMALLINT(3) UNSIGNED NOT NULL DEFAULT '0',PRIMARY KEY (`PkID`))Engine=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci");
			echo '<div style="padding-left:5px;line-height:15px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Creating Default Export Templates...';
			mysql_query("INSERT INTO `" . HC_TblPrefix . "templates` (`PkID`, `Name`, `Content`, `Header`, `Footer`, `Extension`, `TypeID`, `GroupBy`, `SortBy`, `CleanUp`, `IsActive`) VALUES ('1', 'InDesign - By Category', '|N\r\n$$[category_unique]|N\r\n$$[date_unique]|N\r\n[event_title]. [event_time_start]. [loc_name], [loc_address], [loc_city]. [desc_notags] [contact_url] [event_cost]', '', '', '.txt', '1', '1', '0', '\$\$BLANK|N\r\n BLANK,\r\n BLANK.\r\nBLANK', '1')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "templates` (`PkID`, `Name`, `Content`, `Header`, `Footer`, `Extension`, `TypeID`, `GroupBy`, `SortBy`, `CleanUp`, `IsActive`) VALUES ('2', 'InDesign - By Date', '|N\r\n##[date_unique]|N\r\n##[category_unique]|N\r\n[event_title]. [event_time_start]. [loc_name], [loc_address], [loc_city]. [desc_notags] [contact_url] [event_cost]', '', '', '.txt', '1', '0', '1', '##BLANK|N\r\n BLANK,\r\n BLANK.\r\nBLANK', '1')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "templates` (`PkID`, `Name`, `Content`, `Header`, `Footer`, `Extension`, `TypeID`, `GroupBy`, `SortBy`, `CleanUp`, `IsActive`) VALUES ('3', 'CSV - All Variables', '[event_id],[event_title],[desc_notags],[event_date],[event_time_start],[event_time_end],[event_cost],[event_billboard],[contact_name],[contact_email],[contact_phone],[space],[space_used],[space_left],[loc_name],[loc_address],[loc_address2],[loc_city],[loc_region],[loc_postal],[loc_country]\r\n', 'event_id,event_title,event_desc,event_date,event_time_start,event_time_end,event_cost,event_billboard,contact_name,contact_email,contact_phone,space,space_used,space_left,loc_name,loc_address,loc_address2,loc_city,loc_region,loc_postal,loc_country\r\n', '\r\n/eof', '.csv', '1', '1', '1', 'BLANK', '1')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "templates` (`PkID`, `Name`, `Content`, `Header`, `Footer`, `Extension`, `TypeID`, `GroupBy`, `SortBy`, `CleanUp`, `IsActive`) VALUES ('4', 'Quark - Custom Layout', '\r\n@event head:[category_unique]\r\n@date head:[date_series]\r\n@calendar copy:[desc_notags]\r\n\r\n', '<Helios Output>', '', '.txt', '1', '3', '0', '@event head:BLANK\r\n@date head:BLANK\r\n@calendar copy:BLANK', '1')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "templates` (`PkID`, `Name`, `Content`, `Header`, `Footer`, `Extension`, `TypeID`, `GroupBy`, `SortBy`, `CleanUp`, `IsActive`) VALUES ('5', 'Custom XML File', '   <event id=\'[event_id]\'>\r\n      <description title=\'[event_title]\'>[desc_notags]</description>\r\n      <date format=\'m/d/y\'>[event_date]</date>\r\n      <time>\r\n         <start hours=\'12\' format=\'H:M:S\'>[event_time_start]</start>\r\n         <end hours=\'12\' format=\'H:M:S\'>[event_time_end]</end>\r\n      </time>\r\n      <cost currency=\'$\'>[event_cost]</cost>\r\n      <contact>\r\n         <name>[contact_name]</name>\r\n         <email>[contact_email]</email>\r\n         <phone prefix=\'+1\'>[contact_phone]</phone>\r\n         <website url=\'[contact_url]\' />\r\n      </contact>\r\n      <location>\r\n         <name>[loc_name]</name>\r\n         <address>[loc_address]</address>\r\n         <address2>[loc_address2]</address2>\r\n         <city>[loc_city]</city>\r\n         <state>[loc_city]</state>\r\n         <zip>[loc_postal]</zip>\r\n         <country>[loc_country]</country>\r\n         <website url=\'[loc_url]\' />\r\n      </location>\r\n   </event>\r\n', '<?xml version=\'1.0\'?>\r\n<calendar>\r\n   <website url=\'[cal_url]\' />\r\n', '</calendar>', '.xml', '1', '1', '1', 'BLANK', '1')");
			mysql_query("INSERT INTO `" . HC_TblPrefix . "templates` (`PkID`, `Name`, `Content`, `Header`, `Footer`, `Extension`, `TypeID`, `GroupBy`, `SortBy`, `CleanUp`, `IsActive`) VALUES ('7', 'CSV - Import Format', '[event_title],[event_description],[event_cost],[event_date],[event_time_start],[event_time_end],NULL,NULL,[loc_name],[loc_address],[loc_address2],[loc_city],[loc_region],[loc_postal],[loc_country],[contact_name],[contact_email],[contact_phone],[event_billboard],[event_seriesid],[space],[space_left]\r\n', 'EventTitle,Description,Cost,EventDate,StartTime,EndTime,AllDay,LocationID,LocatioName,LocationAddress,LocationAddress2,LocationCity,LocationState,LocationZip,LocationCountry,ContactName,ContactEmail,ContactPhone,ContactURL,Billboard,SeriesID,Registration,SpaceAvailable\r\n', '/eof', '.csv', '1', '1', '1', NULL, '1')");
			echo '<b>Finished</b></div>';
			$status = doInstall($status,"Creating <i>" . HC_TblPrefix . "templatesnews</i> table...",
								"CREATE TABLE " . HC_TblPrefix . "templatesnews (PkID int(11) unsigned NOT NULL auto_increment,TemplateName varchar(250) default NULL,TemplateSource longtext,IsActive tinyint(3) unsigned NOT NULL default '0',`Mailings` INT(11) UNSIGNED NULL DEFAULT '0',PRIMARY KEY (PkID)) Engine=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci");
			$status = doInstall($status,"Creating <i>" . HC_TblPrefix . "users</i> table...",
								"CREATE TABLE `" . HC_TblPrefix . "users` (`PkID` int(11) unsigned NOT NULL AUTO_INCREMENT,`NetworkType` int(3) unsigned DEFAULT NULL,`NetworkName` varchar(200) DEFAULT NULL,`NetworkID` varchar(50) DEFAULT NULL,`Email` varchar(100) DEFAULT NULL,`SignIns` int(11) NOT NULL DEFAULT '0',`FirstSignIn` datetime DEFAULT NULL,`LastSignIn` datetime DEFAULT NULL,`LastIP` varchar(40) DEFAULT NULL,`Level` tinyint(3) unsigned NOT NULL DEFAULT '0',`IsBanned` tinyint(3) unsigned NOT NULL DEFAULT '0',`Categories` text,`Location` mediumtext,`Birthdate` date DEFAULT NULL,`Facebook` varchar(100) DEFAULT NULL,`Twitter` varchar(100) DEFAULT NULL,`GooglePlus` varchar(100) DEFAULT NULL,`IsPrivate` tinyint(3) unsigned NOT NULL DEFAULT '0',`Points` int(11) unsigned NOT NULL DEFAULT '0',`APIKey` varchar(100) DEFAULT NULL,`APIAccess` tinyint(3) unsigned NOT NULL DEFAULT '0',`APICnt` int(11) unsigned NOT NULL DEFAULT '0',PRIMARY KEY (`PkID`),KEY `NetworkID` (`NetworkID`),KEY `APIKey` (`APIKey`)) Engine=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci");

			echo '</fieldset>';
			if($status == 0){
				$_SESSION['done'] = true;
				echo '<input name="button" id="button" type="button" onclick="window.location.href=\'index.php?step=0\';return false;" value="Finish Setup" />';
			} else {
				echo '
					<div style="padding:15px;">

						Setup was unable to fully create your Helios Calendar database. Depending on which steps failed above you may wish to re-run this setup. Please verify the privileges granted to the MySQL user configured for Helios.
						<br /><br />You can <a href="'.CalRoot.'/setup/index.php?step=4">try this step again</a>, however, you should drop any partially created tables before doing so.
					</div>';
			}
		}
	}
?>