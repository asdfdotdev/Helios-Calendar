<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "adminpermissions WHERE AdminID = " . $_SESSION[$hc_cfg00 . 'AdminPkID'] . " and IsActive = 1");
	if(hasRows($result)){
		$adminEventEdit = mysql_result($result,0,"EventEdit");
		$adminEventPending = mysql_result($result,0,"EventPending");
		$adminEventCategory = mysql_result($result,0,"EventCategory");
		$adminUserEdit = mysql_result($result,0,"UserEdit");
		$adminAdminEdit = mysql_result($result,0,"AdminEdit");
		$adminNewsletter = mysql_result($result,0,"Newsletter");
		$adminSettings = mysql_result($result,0,"Settings");
		$adminTools = mysql_result($result,0,"Tools");
		$adminReports = mysql_result($result,0,"Reports");
		$adminLocations = mysql_result($result,0,"Locations");
		$adminComments = mysql_result($result,0,"Comments");
		
		echo '<ul class="menu">';
		echo '<li><a href="' . CalAdminRoot . '/index.php" class="menuNoSub"><span>' . $hc_lang_menu['Home'] . '</span></a></li>';
		
		if($adminEventEdit == 1 || $adminEventPending == 1 || $adminEventCategory == 1){
			echo '<li onmouseover="subMenu(true,1)" onmouseout="subMenu(false,1);">';
			echo '<a href="#" class="MenuLink"><span>' . $hc_lang_menu['Events'] . '</span></a>';
			echo '<ul id="subMenu1" class="subMenu">';
			if($adminEventEdit == 1){
				echo '<li><a href="#" class="subMenuHeader">' . $hc_lang_menu['Edits'] . '</a></li>';
				echo '<li><a href="' . CalAdminRoot . '/index.php?com=eventadd" class="subMenuLink">' . $hc_lang_menu['AddE'] . '</a></li>';
				echo '<li><a href="' . CalAdminRoot . '/index.php?com=eventsearch&amp;sID=1" class="subMenuLink">' . $hc_lang_menu['EditE'] . '</a></li>';
				echo '<li><a href="' . CalAdminRoot . '/index.php?com=eventsearch&amp;sID=2" class="subMenuLink">' . $hc_lang_menu['Delete'] . '</a></li>';
			}//end if
			if($adminEventEdit == 1 || $adminEventPending == 1){
				echo '<li><a href="#" class="subMenuHeader">' . $hc_lang_menu['Manage'] . '</a></li>';
			}//end if
			if($adminEventPending == 1){
				echo '<li><a href="' . CalAdminRoot . '/index.php?com=eventpending" class="subMenuLink">' . $hc_lang_menu['Pending'] . '</a></li>';
			}//end if
			if($adminEventEdit == 1){
				echo '<li><a href="' . CalAdminRoot . '/index.php?com=eventbillboard" class="subMenuLink">' . $hc_lang_menu['Billboard'] . '</a></li>';
				echo '<li><a href="' . CalAdminRoot . '/index.php?com=eventorphan" class="subMenuLink">' . $hc_lang_menu['Orphan'] . '</a></li>';
				echo '<li><a href="' . CalAdminRoot . '/index.php?com=eventsearch&amp;sID=3" class="subMenuLink">' . $hc_lang_menu['Create'] . '</a></li>';
			}//end if
			if($adminEventCategory == 1){
				echo '<li><a href="#" class="subMenuHeader">' . $hc_lang_menu['CatLabel'] . '</a></li>';
				echo '<li><a href="' . CalAdminRoot . '/index.php?com=categorymanage" class="subMenuLink">' . $hc_lang_menu['Category'] . '</a></li>';
			}//end if
			echo '</ul></li>';
		}//end if
		
		if($adminLocations == 1){
			echo '<li onmouseover="subMenu(true,2)" onmouseout="subMenu(false,2);">';
			echo '<a href="#" class="MenuLink"><span>' . $hc_lang_menu['Locations'] . '</span></a>';
			echo '<ul id="subMenu2" class="subMenu">';
			echo '<li><a href="#" class="subMenuHeader">' . $hc_lang_menu['EditsM'] . '</a></li>';
			echo '<li><a href="' . CalAdminRoot . '/index.php?com=addlocation" class="subMenuLink">' . $hc_lang_menu['AddL'] . '</a></li>';
			echo '<li><a href="' . CalAdminRoot . '/index.php?com=location" class="subMenuLink">' . $hc_lang_menu['EditL'] . '</a></li>';
			echo '<li><a href="#" class="subMenuHeader">' . $hc_lang_menu['ManageM'] . '</a></li>';
			echo '<li><a href="' . CalAdminRoot . '/index.php?com=locsearch" class="subMenuLink">' . $hc_lang_menu['MergeL'] . '</a></li>';
			echo '</ul></li>';
		}//end if
		
		if($adminAdminEdit == 1 || $adminUserEdit == 1){
			echo '<li onmouseover="subMenu(true,3)" onmouseout="subMenu(false,3)">';
			echo '<a href="javascript:;" class="MenuLink"><span>' . $hc_lang_menu['Users'] . '</span></a>';
			echo '<ul id="subMenu3" class="subMenu">';
			if($adminAdminEdit == 1){
				echo '<li><a href="#" class="subMenuHeader">' . $hc_lang_menu['Admin'] . '</a></li>';
				echo '<li><a href="' . CalAdminRoot . '/index.php?com=adminedit" class="subMenuLink">' . $hc_lang_menu['AddA'] . '</a></li>';
				echo '<li><a href="' . CalAdminRoot . '/index.php?com=adminbrowse" class="subMenuLink">' . $hc_lang_menu['EditA'] . '</a></li>';
		 	}//end if
			if($adminUserEdit == 1){
				echo '<li><a href="#" class="subMenuHeader">' . $hc_lang_menu['PublicUsers'] . '</a></li>';
				echo '<li><a href="' . CalAdminRoot . '/index.php?com=oiduser" class="subMenuLink">' . $hc_lang_menu['OIDUsers'] . '</a></li>';
				echo '<li><a href="#" class="subMenuHeader">' . $hc_lang_menu['Newsletter'] . '</a></li>';
				echo '<li><a href="' . CalAdminRoot . '/index.php?com=useredit" class="subMenuLink">' . $hc_lang_menu['AddR'] . '</a></li>';
				echo '<li><a href="' . CalAdminRoot . '/index.php?com=userbrowse" class="subMenuLink">' . $hc_lang_menu['EditR'] . '</a></li>';
				echo '<li><a href="' . CalAdminRoot . '/index.php?com=usersearch" class="subMenuLink">' . $hc_lang_menu['SearchR'] . '</a></li>';
			}//end if
			echo '</ul></li>';
		}//end if
		
		if($adminComments == 1){
			echo '<li onmouseover="subMenu(true,4)" onmouseout="subMenu(false,4)">';
			echo '<a href="javascript:;" class="MenuLink"><span>' . $hc_lang_menu['Comments'] . '</span></a>';
			echo '<ul id="subMenu4" class="subMenu">';
			echo '<li><a href="#" class="subMenuHeader">' . $hc_lang_menu['EventComments'] . '</a></li>';
			echo '<li><a href="' . CalAdminRoot . '/index.php?com=cmntmgt" class="subMenuLink">' . $hc_lang_menu['ManageCom'] . '</a></li>';
			echo '<li><a href="' . CalAdminRoot . '/index.php?com=cmntrep" class="subMenuLink">' . $hc_lang_menu['Reported'] . '</a></li>';
			echo '</ul></li>';
		}//end if
		
		if($adminTools == 1 || $adminNewsletter == 1){
			echo '<li onmouseover="subMenu(true,5)" onmouseout="subMenu(false,5)">';
			echo '<a href="#" class="MenuLink"><span>' . $hc_lang_menu['Tools'] . '</span></a>';
			echo '<ul id="subMenu5" class="subMenu">';
			if($adminTools == 1){
				echo '<li><a href="' . CalAdminRoot . '/index.php?com=filter" class="subMenuLink">' . $hc_lang_menu['Filter'] . '</a></li>';
				echo '<li><a href="' . CalAdminRoot . '/index.php?com=db" class="subMenuLink">' . $hc_lang_menu['DbaseMgt'] . '</a></li>';
				echo '<li><a href="' . CalAdminRoot . '/index.php?com=import" class="subMenuLink">' . $hc_lang_menu['Import'] . '</a></li>';
				echo '<li><a href="#" class="subMenuHeader">' . $hc_lang_menu['ExportLabel'] . '</a></li>';
				echo '<li><a href="' . CalAdminRoot . '/index.php?com=export" class="subMenuLink">' . $hc_lang_menu['Export'] . '</a></li>';
				echo '<li><a href="' . CalAdminRoot . '/index.php?com=exporttmplts" class="subMenuLink">' . $hc_lang_menu['ManageTemplates'] . '</a></li>';
			}//end if
			if($adminNewsletter == 1){
				echo '<li><a href="#" class="subMenuHeader">' . $hc_lang_menu['Newsletter'] . '</a></li>';
				echo '<li><a href="' . CalAdminRoot . '/index.php?com=newsletter" class="subMenuLink">' . $hc_lang_menu['Send'] . '</a></li>';
				echo '<li><a href="' . CalAdminRoot . '/index.php?com=newsletteredit" class="subMenuLink">' . $hc_lang_menu['ManageTemplates'] . '</a></li>';
			}//end if
			echo '</ul></li>';
		}//end if
		
		if($adminSettings == 1){
			echo '<li onmouseover="subMenu(true,6)" onmouseout="subMenu(false,6)">';
			echo '<a href="#" class="MenuLink"><span>' . $hc_lang_menu['Settings'] . '</span></a>';
			echo '<ul id="subMenu6" class="subMenu">';
			echo '<li><a href="' . CalAdminRoot . '/index.php?com=generalset" class="subMenuLink">' . $hc_lang_menu['Configure'] . '</a></li>';
			echo '<li><a href="' . CalAdminRoot . '/index.php?com=apiset" class="subMenuLink">' . $hc_lang_menu['API'] . '</a></li>';
			echo '<li><a href="' . CalAdminRoot . '/index.php?com=optimize" class="subMenuLink">' . $hc_lang_menu['SearchO'] . '</a></li>';
			echo '<li><a href="#" class="subMenuHeader">' . $hc_lang_menu['Comments'] . '</a></li>';
			echo '<li><a href="' . CalAdminRoot . '/index.php?com=words" class="subMenuLink">' . $hc_lang_menu['Censored'] . '</a></li>';
			echo '</ul></li>';
		}//end if
					
		if($adminReports == 1){
			echo '<li onmouseover="subMenu(true,7)" onmouseout="subMenu(false,7)">';
			echo '<a href="#" class="MenuLink"><span>' . $hc_lang_menu['Reports'] . '</span></a>';
			echo '<ul id="subMenu7" class="subMenu">';
			echo '<li><a href="' . CalAdminRoot . '/index.php?com=eventsearch" class="subMenuLink">' . $hc_lang_menu['Activity'] . '</a></li>';
			echo '<li><a href="' . CalAdminRoot . '/index.php?com=reportrecent" class="subMenuLink">' . $hc_lang_menu['Recent'] . '</a></li>';
			echo '<li><a href="' . CalAdminRoot . '/index.php?com=reportoverview" class="subMenuLink">' . $hc_lang_menu['Overview'] . '</a></li>';
			echo '<li><a href="#" class="subMenuHeader">' . $hc_lang_menu['MostPopular'] . '</a></li>';
			echo '<li><a href="' . CalAdminRoot . '/index.php?com=reportpopular" class="subMenuLink">' . $hc_lang_menu['Popular'] . '</a></li>';
			echo '<li><a href="' . CalAdminRoot . '/index.php?com=reportpopular&amp;mID=1" class="subMenuLink">' . $hc_lang_menu['PopularM'] . '</a></li>';
			echo '</ul></li>';
		}//end if
			
		echo '<li onmouseover="subMenu(true,8)" onmouseout="subMenu(false,8);">';
		echo '<a href="#" class="MenuLink"><span>' . $hc_lang_menu['Help'] . '</span></a>';
		echo '<ul id="subMenu8" class="subMenu">';
		echo '<li><a href="' . CalAdminRoot . '/index.php?com=about" class="subMenuLink">' . $hc_lang_menu['About'] . ' Helios Calendar</a></li>';
		echo '<li><a href="#" class="subMenuHeader">' . $hc_lang_menu['OnlineResources'] . '</a></li>';
		echo '<li><a href="http://www.refreshmy.com/documentation/?title=Helios" target="_blank" class="subMenuLink">' . $hc_lang_menu['Documentation'] . '</a></li>';
		echo '<li><a href="http://www.refreshmy.com/forum/" target="_blank" class="subMenuLink">' . $hc_lang_menu['Forum'] . '</a></li>';
		echo '</ul></li>';
		
		echo '<li><a href="' . CalAdminRoot . '/' . HC_LogOut . '" class="menuNoSub"><span>' . $hc_lang_menu['Logout'] . '</span></a></li>';
		echo '</ul>';
	} else {
	echo '<ul class="menu">';
	echo '<li><a href="' . CalAdminRoot . '/' . HC_LogOut . '">Permissions Not Found</a></li>';
	echo '</ul>';
	}//end if?>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function subMenu(show,menuNumber) {var sMenu = document.getElementById('subMenu' + menuNumber);show ? sMenu.style.display = "block" : sMenu.style.display = "none";}
	//-->
	</script>