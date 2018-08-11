<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	if(isset($_GET['com'])){
		switch ($_GET['com']){
			
			case "detail" :
					include('components/EventDetail.php');
				break;
				
			case "register" :
					include('components/Register.php');
				break;
				
			case "submit" :
					define("HC_FormSubmitAction", CalRoot . "/components/EventSubmitAction.php");
					include('components/EventSubmit.php');
				break;
				
			case "search" :
					define("HC_SearchAction", CalRoot . "/index.php?com=searchresult");
					include('components/Search.php');
				break;
				
			case "signup" :
					define("HC_SignupAction", CalRoot . "/components/SignUpAction.php");
					include('components/SignUp.php');
				break;
				
			case "send" :
					include('components/SendToFriend.php');
				break;
				
			case "searchresult" :
					include('components/SearchResults.php');
				break;
				
			case "serieslist" :
					include('components/SeriesList.php');
				break;
				
			case "rss" :
					include('components/RSS.php');
				break;
				
			case "mobile" :
					include('components/Mobile.php');
				break;
				
			case "editreg" :
					define("HC_EditRegisterAction", CalRoot . "/components/EditRegistrationAction.php");
					include('components/EditRegistration.php');
				break;
				
			case "unsubscribe" :
					define("HC_UnsubscribeAction", CalRoot . "/components/UnsubscribeAction.php");
					include('components/Unsubscribe.php');
				break;
				
			case "filter" :
					define("HC_FilterAction", CalRoot . "/components/FilterAction.php");
					include('components/Filter.php');
				break;
				
			default :
					include('components/EventList.php');
				break;
		}//end switch
		
	} else {
		include('components/EventList.php');
		
	}//end if
?>