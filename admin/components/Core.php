<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	$com = "components/Home.php";
	
	if(isset($_GET['com'])){
		switch ($_GET['com']){
			
			case 'member' :
				$com = "Members/index.php";
				break;
				
			case 'memberadd' :
				$com = "Members/edit.php";
				break;
				
			case 'about' :
				$com = "components/About.php";
				break;
				
			case 'eventadd' :
				if($adminEventEdit == 1){
					define("HC_EventAddAction", "components/EventAddAction.php");
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
					define("HC_EventEditAction", "components/EventEditAction.php");
					$com = "components/EventEdit.php";
				}//end if
					break;
				
			case 'eventregister' :
				if($adminEventEdit == 1){
					define("HC_EventRegisterAction", "components/EventRegisterAction.php");
					$com = "components/EventRegister.php";
				}//end if
					break;
				
			case 'eventpending' :
				if($adminEventPending == 1){
					define("HC_EventPendingAction", "components/EventPendingAction.php");
					$com = "components/EventPending.php";
				}//end if
					break;
				
			case 'eventbillboard' :
				if($adminEventEdit == 1){
					define("HC_EventBillboardAction", "components/EventBillboardAction.php");
					$com = "components/EventBillboard.php";
				}//end if
					break;
				
			case 'eventcategorymanage' :
				if($adminEventCategory == 1){
					define("HC_EventCategoryManageAction", "components/EventCategoryManageAction.php");
					$com = "components/EventCategoryManage.php";
				}//end if
					break;
				
			case 'eventview' :
				if($adminReports == 1){
					$com = "components/EventView.php";
				}//end if
					break;
				
			case 'eventviewdetail' :
				if($adminReports == 1){
					$com = "components/EventViewDetail.php";
				}//end if
					break;
				
			case 'eventorphan' :
				if($adminEventEdit == 1){
					$com = "components/EventOrphan.php";
				}//end if
					break;
				
			case 'mostpopular' :
				if($adminReports == 1){
					$com = "components/EventMostPopular.php";
				}//end if
					break;
				
			case 'recentadd' :
				if($adminReports == 1){
					$com = "components/EventRecentAdd.php";
				}//end if
					break;
				
			case 'useredit' :
				if($adminUserEdit == 1){
					define("HC_UserEditAction", "components/UserEditAction.php");
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
					define("HC_UserEditAction", "components/UserEditAction.php");
					$com = "components/UserSearchResult.php";
				}//end if
					break;
				
			case 'adminedit' :
				if($adminAdminEdit == 1){
					define("HC_AdminEditAction", "components/AdminEditAction.php");
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
					define("HC_NewsletterAction", "components/NewsletterAction.php");
					$com = "components/Newsletter.php";
				}//end if
					break;
				
			case 'newsletteredit' :
				if($adminNewsletter == 1){
					define("HC_NewsletterEditAction", "components/NewsletterEditAction.php");
					$com = "components/NewsletterEdit.php";
				}//end if
					break;
				
			case 'optimize' :
				if($adminSettings == 1){
					define("HC_SearchOptimizeAction", "components/SearchOptimizeAction.php");
					$com = "components/SearchOptimize.php";
				}//end if
					break;
				
			case 'generalset' :
				if($adminSettings == 1){
					define("HC_GeneralSetAction", "components/SettingsGeneralAction.php");
					$com = "components/SettingsGeneral.php";
				}//end if
					break;
				
			case 'billboard' :
				if($adminSettings == 1){
					define("HC_BillboardAction", "components/BillboardAction.php");
					$com = "components/Billboard.php";
				}//end if
					break;
				
			case 'link' :
				if($adminLinks == 1){
					define("HC_LinkAction", "components/LinksAction.php");
					$com = "components/Links.php";
				}//end if
					break;
				
			case 'update' :
				$com = "components/VersionCheck.php";
				break;
				
			case 'contact' :
				define("HC_ContactAction", "components/ContactAction.php");
				$com = "components/Contact.php";
				break;
				
		}//end switch
	}//end if
	
	include($com);
?>