<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2008 Refresh Web Development [www.RefreshMy.com]
	
	Developed By: Chris Carlevato <support@refreshmy.com>
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar EUL found at www.HeliosCalendar.com/license.pdf
*/
	$com = "components/Home.php";
	
	if(isset($_GET['com'])){
		switch ($_GET['com']){
			
			case 'eventadd' :
				if($adminEventEdit == 1){
					$com = "components/EventAdd.php";
				}//end if
					break;
				
			case 'eventsearch' :
				if(($adminEventEdit == 1) || ($adminReports == 1)){
					$com = "components/EventSearch.php";
				}//end if
					break;
				
			case 'searchresults' :
				if($adminEventEdit == 1){
					$com = "components/EventSearchResults.php";
				}//end if
					break;
				
			case 'eventedit' :
				if($adminEventEdit == 1){
					$com = "components/EventEdit.php";
				}//end if
					break;
				
			case 'eventregister' :
				if($adminEventEdit == 1){
					$com = "components/RegisterAdd.php";
				}//end if
					break;
				
			case 'eventpending' :
				if($adminEventPending == 1){
					$com = "components/EventPending.php";
				}//end if
					break;
				
			case 'eventbillboard' :
				if($adminEventEdit == 1){
					$com = "components/EventBillboard.php";
				}//end if
					break;
				
			case 'categorymanage' :
				if($adminEventCategory == 1){
					$com = "components/CategoryManage.php";
				}//end if
					break;
				
			case 'eventview' :
				if($adminReports == 1){
					$com = "components/EventView.php";
				}//end if
					break;
				
			case 'eventorphan' :
				if($adminEventEdit == 1){
					$com = "components/EventOrphan.php";
				}//end if
					break;
				
			case 'useredit' :
				if($adminUserEdit == 1){
					$com = "components/UserEdit.php";
				}//end if
					break;
				
			case 'userbrowse' :
				if($adminUserEdit == 1){
					$com = "components/UserBrowse.php";
				}//end if
					break;
				
			case 'usersearch' :
				if($adminUserEdit == 1){
					$com = "components/UserSearch.php";
				}//end if
					break;
				
			case 'usersearchresult' :
				if($adminUserEdit == 1){
					$com = "components/UserSearchResult.php";
				}//end if
					break;
				
			case 'adminedit' :
				if($adminAdminEdit == 1){
					$com = "components/AdminEdit.php";
				}//end if
					break;
				
			case 'adminbrowse' :
				if($adminAdminEdit == 1){
					$com = "components/AdminBrowse.php";
				}//end if
					break;
				
			case 'newsletter' :
				if($adminNewsletter == 1){
					$com = "components/Newsletter.php";
				}//end if
					break;
				
			case 'newsletteredit' :
				if($adminNewsletter == 1){
					$com = "components/NewsletterEdit.php";
				}//end if
					break;
				
			case 'optimize' :
				if($adminSettings == 1){
					$com = "components/SearchOptimize.php";
				}//end if
					break;
				
			case 'generalset' :
				if($adminSettings == 1){
					$com = "components/SettingsGeneral.php";
				}//end if
					break;
				
			case 'reportactivity' :
				if($adminReports == 1){
					$com = "components/ReportActivity.php";
				}//end if
					break;
				
			case 'reportpopular' :
				if($adminReports == 1){
					$com = "components/ReportPopular.php";
				}//end if
					break;
				
			case 'reportrecent' :
				if($adminReports == 1){
					$com = "components/ReportRecent.php";
				}//end if
					break;
				
			case 'reportoverview' :
				if($adminReports == 1){
					$com = "components/ReportOverview.php";
				}//end if
					break;
				
			case 'update' :
				$com = "components/VersionCheck.php";
				break;
				
			case 'export' :
				if($adminTools == 1){
					$com = "components/ToolExport.php";
				}//end if
					break;
			
			case 'import' :
				if($adminTools == 1){
					$com = "components/ToolImport.php";
				}//end if
					break;
						
			case 'filter' :
				if($adminTools == 1){
					$com = "components/ToolFilter.php";
				}//end if
					break;
				
			case 'searchlink' :
				if($adminTools == 1){
					$com = "components/ToolSearch.php";
				}//end if
					break;
					
			case 'location' :
				if($adminLocations == 1){
					if(isset($_GET['m']) && $_GET['m'] == 1){
						$com = "components/LocationMerge.php";
					} else {
						$com = "components/Location.php";
					}//end if
				}//end if
					break;
			
			case 'locsearch' :
				if($adminLocations == 1){
					$com = "components/LocationSearch.php";
				}//end if
					break;
					
			case 'addlocation' :
				if($adminLocations == 1){
					$com = "components/LocationEdit.php";
				}//end if
					break;
				
			case 'db' :
				if($adminTools == 1){
					$com = "components/ToolPruneDB.php";
				}//end if
					break;
				
		}//end switch
	}//end if
	
	include($com);
?>