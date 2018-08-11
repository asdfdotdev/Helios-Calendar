<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	$com = "components/EventList.php";
	
	if(isset($_GET['com'])){
		switch ($_GET['com']){
			
			case 'detail' :
				$com = "components/EventDetail.php";
				break;
				
			case 'register' :
				$com = "components/Register.php";
				break;
				
			case 'submit' :
				define("HC_FormSubmitAction", CalRoot . "/components/EventSubmitAction.php");
				$com = "components/EventSubmit.php";
				break;
				
			case 'search' :
				define("HC_SearchAction", CalRoot . "/index.php?com=searchresult");
				$com = "components/Search.php";
				break;
				
			case 'signup' :
				define("HC_SignupAction", CalRoot . "/components/SignUpAction.php");
				$com = "components/SignUp.php";
				break;
				
			case 'send' :
				$com = "components/SendToFriend.php";
				break;
				
			case 'searchresult' :
				$com = "components/SearchResults.php";
				break;
				
			case 'serieslist' :
				$com = "components/SeriesList.php";
				break;
				
			case 'rss' :
				$com = "components/RSS.php";
				break;
				
			case 'mobile' :
				$com = "components/Mobile.php";
				break;
				
			case 'editreg' :
				define("HC_EditRegisterAction", CalRoot . "/components/EditRegistrationAction.php");
				$com = "components/EditRegistration.php";
				break;
				
			case 'unsubscribe' :
				define("HC_UnsubscribeAction", CalRoot . "/components/UnsubscribeAction.php");
				$com = "components/Unsubscribe.php";
				break;
				
			case 'filter' :
				define("HC_FilterAction", CalRoot . "/components/FilterAction.php");
				$com = "components/Filter.php";
				break;
				
			case 'togo' :
				$com = "components/ToGo.php";
				break;
				
		}//end switch
	}//end if
	
	include($com);
?>