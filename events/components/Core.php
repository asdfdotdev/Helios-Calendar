<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2009 Refresh Web Development [www.RefreshMy.com]
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	$com = "components/EventList.php";
	
	if(isset($_GET['com'])){
		switch ($_GET['com']){
			case 'detail':
				$com = "components/EventDetail.php";
				break;
			case 'register':
				$com = "components/EventRegister.php";
				break;
			case 'location':
				$com = "components/Location.php";
				break;
			case 'submit':
				define("HC_FormSubmitAction", CalRoot . "/components/EventSubmitAction.php");
				$com = "components/EventSubmit.php";
				break;
			case 'search':
				define("HC_SearchAction", CalRoot . "/index.php?com=searchresult");
				$com = "components/Search.php";
				break;
			case 'signup':
				define("HC_SignupAction", CalRoot . "/components/SignUpAction.php");
				$com = "components/SignUp.php";
				break;
			case 'send':
				$com = "components/SendToFriend.php";
				break;
			case 'searchresult':
				$com = "components/SearchResults.php";
				break;
			case 'serieslist':
				$com = "components/SeriesList.php";
				break;
			case 'tools':
				$com = "components/Tools.php";
				break;
			case 'rss':
				$com = "components/RSS.php";
				break;
			case 'mobile':
				$com = "components/Mobile.php";
				break;
			case 'editreg':
				define("HC_EditRegisterAction", CalRoot . "/components/EditRegistrationAction.php");
				$com = "components/EditRegistration.php";
				break;
			case 'unsubscribe':
				define("HC_UnsubscribeAction", CalRoot . "/components/UnsubscribeAction.php");
				$com = "components/Unsubscribe.php";
				break;
			case 'filter':
				define("HC_FilterAction", CalRoot . "/components/FilterAction.php");
				$com = "components/Filter.php";
				break;
			case 'login':
				$com = "components/LoginForm.php";
				break;
			case 'about':
				$com = "components/LoginAbout.php";
				break;
			case 'ocomm':
				$com = (isset($_SESSION[$hc_cfg00 . 'hc_OpenIDLoggedIn'])) ? "components/OIDComments.php" : $com;
				break;
			case 'report':
				$com = "components/CommentReport.php";
		}//end switch
	}//end if
	
	include($com);?>