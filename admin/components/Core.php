<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2009 Refresh Web Development [www.RefreshMy.com]
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	$com = "components/Home.php";
	
	if(isset($_GET['com'])){
		switch ($_GET['com']){
			case 'eventadd':
				$com = ($adminEventEdit == 1) ? 'components/EventAdd.php' : $com;
				break;
			case 'eventsearch':
				$com = (($adminEventEdit == 1) || ($adminReports == 1)) ? 'components/EventSearch.php' : $com;
				break;
			case 'searchresults':
				$com = ($adminEventEdit == 1) ? 'components/EventSearchResults.php' : $com;
				break;
			case 'eventedit':
				$com = ($adminEventEdit == 1) ? 'components/EventEdit.php' : $com;
				break;
			case 'eventregister':
				$com = ($adminEventEdit == 1) ? 'components/RegisterAdd.php' : $com;
				break;
			case 'eventpending':
				$com = ($adminEventPending == 1) ? 'components/EventPending.php' : $com;
				break;
			case 'eventbillboard':
				$com = ($adminEventEdit == 1) ? 'components/EventBillboard.php' : $com;
				break;
			case 'categorymanage':
				$com = ($adminEventCategory == 1)  ? 'components/CategoryManage.php' : $com;
				break;
			case 'eventview':
				$com = ($adminReports == 1) ? 'components/EventView.php' : $com;
				break;
			case 'eventorphan':
				$com = ($adminEventEdit == 1) ? 'components/EventOrphan.php' : $com;
				break;
			case 'useredit':
				$com = ($adminUserEdit == 1) ? 'components/UserEdit.php' : $com;
				break;
			case 'userbrowse':
				$com = ($adminUserEdit == 1) ? 'components/UserBrowse.php' : $com;
				break;
			case 'usersearch':
				$com = ($adminUserEdit == 1) ? 'components/UserSearch.php' : $com;
				break;
			case 'usersearchresult':
				$com = ($adminUserEdit == 1) ? 'components/UserSearchResult.php' : $com;
				break;
			case 'adminedit':
				$com = ($adminAdminEdit == 1) ? 'components/AdminEdit.php' : $com;
				break;
			case 'adminbrowse':
				$com = ($adminAdminEdit == 1) ? 'components/AdminBrowse.php' : $com;
				break;
			case 'newsletter':
				$com = ($adminNewsletter == 1) ? 'components/Newsletter.php' : $com;
				break;
			case 'newsletteredit':
				$com = ($adminNewsletter == 1) ? 'components/NewsletterEdit.php' : $com;
				break;
			case 'optimize':
				$com = ($adminSettings == 1) ? 'components/SearchOptimize.php' : $com;
				break;
			case 'generalset':
				$com = ($adminSettings == 1) ? 'components/SettingsGeneral.php' : $com;
				break;
			case 'reportactivity':
				$com = ($adminReports == 1) ? 'components/ReportActivity.php' : $com;
				break;
			case 'reportpopular':
				$com = ($adminReports == 1) ? 'components/ReportPopular.php' : $com;
				break;
			case 'reportrecent':
				$com = ($adminReports == 1) ? 'components/ReportRecent.php' : $com;
				break;
			case 'reportoverview':
				$com = ($adminReports == 1) ? 'components/ReportOverview.php' : $com;
				break;
			case 'update':
				$com = "components/VersionCheck.php";
				break;
			case 'export':
				$com = ($adminTools == 1) ? 'components/ToolExport.php' : $com;
				break;
			case 'import':
				$com = ($adminTools == 1) ? 'components/ToolImport.php' : $com;
				break;
			case 'filter':
				$com = ($adminTools == 1) ? 'components/ToolFilter.php' : $com;
				break;
			case 'searchlink':
				$com = ($adminTools == 1) ? 'components/ToolSearch.php' : $com;
				break;
			case 'location':
				if($adminLocations == 1){
					$com = (isset($_GET['m']) && $_GET['m'] == 1) ? 'components/LocationMerge.php' : 'components/Location.php';
				}//end if
				break;
			case 'locsearch':
				$com = ($adminLocations == 1) ? 'components/LocationSearch.php' : $com;
				break;
			case 'addlocation':
				$com = ($adminLocations == 1) ? 'components/LocationEdit.php' : $com;
				break;
			case 'db':
				$com = ($adminTools == 1) ? 'components/ToolDB.php' : $com;
				break;
			case 'oidedit':
				$com = ($adminUserEdit == 1) ? 'components/OIDEdit.php' : $com;
				break;
			case 'oiduser':
				$com = ($adminUserEdit == 1) ? 'components/OIDBrowse.php' : $com;
				break;
			case 'cmntmgt':
				$com = ($adminComments == 1) ? 'components/CommentBrowse.php' : $com;
				break;
			case 'cmntrep':
				$com = ($adminComments == 1) ? 'components/CommentReported.php' : $com;
				break;
			case 'words':
				$com = ($adminSettings == 1) ? 'components/SettingsWords.php' : $com;
				break;
			case 'about':
				$com = 'components/About.php';
				break;
		}//end switch
	}//end if
	
	include($com);?>