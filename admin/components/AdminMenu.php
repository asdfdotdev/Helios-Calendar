<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(!defined('hcAdmin')){header("HTTP/1.1 403 No Direct Access");exit();}
	
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "adminpermissions WHERE AdminID = '" . cIn($_SESSION['AdminPkID']) . "' and IsActive = 1");
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
		$adminPages = mysql_result($result,0,"Pages");
		
		$_SESSION['APIAuth'] = $adminSettings;
		
		echo '
	<ul>
		<li class="nosub"><a href="' . AdminRoot . '/index.php">' . $hc_lang_menu['Home'] . '</a></li>';
		
		if($adminEventEdit == 1 || $adminEventPending == 1 || $adminEventCategory == 1){
			echo '
		<li><a href="#">' . $hc_lang_menu['Events'] . '</a>
			<ul>';			
			if($adminEventEdit == 1){
				echo '
				<li><a href="#" class="sub_header">' . $hc_lang_menu['Edits'] . '</a></li>
				<li><a href="' . AdminRoot . '/index.php?com=eventadd">' . $hc_lang_menu['AddE'] . '</a></li>
				<li><a href="' . AdminRoot . '/index.php?com=eventsearch&amp;sID=1">' . $hc_lang_menu['EditE'] . '</a></li>
				<li><a href="' . AdminRoot . '/index.php?com=eventsearch&amp;sID=2">' . $hc_lang_menu['Delete'] . '</a></li>';
			}
			if($adminEventEdit == 1 || $adminEventPending == 1){
				echo '
				<li><a href="#" class="sub_header">' . $hc_lang_menu['Manage'] . '</a></li>';
			}
			if($adminEventPending == 1){
				echo '
				<li><a href="' . AdminRoot . '/index.php?com=eventpending">' . $hc_lang_menu['Pending'] . '</a></li>';
			}
			if($adminEventEdit == 1){
				echo '
				<li><a href="' . AdminRoot . '/index.php?com=eventbillboard">' . $hc_lang_menu['Billboard'] . '</a></li>
				<li><a href="' . AdminRoot . '/index.php?com=eventorphan">' . $hc_lang_menu['Orphan'] . '</a></li>
				<li><a href="' . AdminRoot . '/index.php?com=eventsearch&amp;sID=3">' . $hc_lang_menu['Create'] . '</a></li>';
			}
			if($adminEventCategory == 1){
				echo '
				<li><a href="#" class="sub_header">' . $hc_lang_menu['CatLabel'] . '</a></li>
				<li><a href="' . AdminRoot . '/index.php?com=categorymanage">' . $hc_lang_menu['Category'] . '</a></li>';
			}
			echo '
			</ul>
		</li>';
		}
		if($adminLocations == 1){
			echo '
		<li><a href="#">' . $hc_lang_menu['Locations'] . '</a>
			<ul>
				<li><a href="#" class="sub_header">' . $hc_lang_menu['EditsM'] . '</a></li>
				<li><a href="' . AdminRoot . '/index.php?com=addlocation">' . $hc_lang_menu['AddL'] . '</a></li>
				<li><a href="' . AdminRoot . '/index.php?com=location">' . $hc_lang_menu['EditL'] . '</a></li>
				<li><a href="#" class="sub_header">' . $hc_lang_menu['ManageM'] . '</a></li>
				<li><a href="' . AdminRoot . '/index.php?com=locsearch">' . $hc_lang_menu['MergeL'] . '</a></li>
			</ul>
		</li>';
		}
		if($adminPages == 1){
			echo '
		<li><a href="#">' . $hc_lang_menu['Pages'] . '</a>
			<ul>
				<li><a href="' . AdminRoot . '/index.php?com=digest">'.$hc_lang_menu['Digest'].'</a></li>
			</ul>
		</li>';
		}
		if($adminAdminEdit == 1 || $adminUserEdit == 1){
			echo '
		<li><a href="#">' . $hc_lang_menu['Users'] . '</a>
			<ul>';
			
			if($adminAdminEdit == 1){
				echo '
				<li><a href="#" class="sub_header">' . $hc_lang_menu['Admin'] . '</a></li>
				<li><a href="' . AdminRoot . '/index.php?com=adminedit">' . $hc_lang_menu['AddA'] . '</a></li>
				<li><a href="' . AdminRoot . '/index.php?com=adminbrowse">' . $hc_lang_menu['EditA'] . '</a></li>';
			}
			if($adminUserEdit == 1){
				echo '
				<li><a href="#" class="sub_header">' . $hc_lang_menu['Public'] . '</a></li>
				<li><a href="' . AdminRoot . '/index.php?com=user">' . $hc_lang_menu['ManageU'] . '</a></li>';
			}
			echo '
			</ul>
		</li>';
		}
		if($adminTools == 1){
			echo '
		<li><a href="#">' . $hc_lang_menu['Tools'] . '</a>
			<ul>
				<li><a href="' . AdminRoot . '/index.php?com=filter">' . $hc_lang_menu['Filter'] . '</a></li>
				<li><a href="' . AdminRoot . '/index.php?com=db">' . $hc_lang_menu['DbaseMgt'] . '</a></li>
				<li><a href="' . AdminRoot . '/index.php?com=import">' . $hc_lang_menu['Import'] . '</a></li>
				<li><a href="#" class="sub_header">' . $hc_lang_menu['ExportLabel'] . '</a></li>
				<li><a href="' . AdminRoot . '/index.php?com=export">' . $hc_lang_menu['Export'] . '</a></li>
				<li><a href="' . AdminRoot . '/index.php?com=exporttmplts">' . $hc_lang_menu['ManageTemplates'] . '</a></li>
			</ul>
		</li>';
		}
		if($adminNewsletter == 1 || $adminUserEdit == 1){
			echo '
		<li><a href="#">' . $hc_lang_menu['Newsletter'] . '</a>
			<ul>';
			if($adminNewsletter == 1){
				echo '
				<li><a href="' . AdminRoot . '/index.php?com=newsdraft">' . $hc_lang_menu['DraftCreate'] . '</a></li>
				<li><a href="' . AdminRoot . '/index.php?com=newscreate">' . $hc_lang_menu['NewsCreate'] . '</a></li>
				<li><a href="' . AdminRoot . '/index.php?com=newsqueue">' . $hc_lang_menu['NewsQueue'] . '</a></li>
				<li><a href="#" class="sub_header">' . $hc_lang_menu['ManageN'] . '</a></li>
				<li><a href="' . AdminRoot . '/index.php?com=mailtmplt">' . $hc_lang_menu['Template'] . '</a></li>
				<li><a href="' . AdminRoot . '/index.php?com=subgrps">' . $hc_lang_menu['Groups'] . '</a></li>';
			}
			if($adminUserEdit == 1){
				echo '
				<li><a href="#" class="sub_header">' . $hc_lang_menu['Subscribers'] . '</a></li>
				<li><a href="' . AdminRoot . '/index.php?com=subedit">' . $hc_lang_menu['AddS'] . '</a></li>
				<li><a href="' . AdminRoot . '/index.php?com=submngt">' . $hc_lang_menu['EditS'] . '</a></li>';
			}
			echo '
			</ul>
		</li>';
		}
		if($adminSettings == 1){
			echo '
		<li><a href="#">' . $hc_lang_menu['Settings'] . '</a>
			<ul>
				<li><a href="' . AdminRoot . '/index.php?com=generalset">' . $hc_lang_menu['Configure'] . '</a></li>
				<li><a href="' . AdminRoot . '/index.php?com=seo">' . $hc_lang_menu['SearchO'] . '</a></li>
				<li><a href="' . AdminRoot . '/index.php?com=themes">' . $hc_lang_menu['Themes'] . '</a></li>
				<li><a href="#" class="sub_header">' . $hc_lang_menu['API'] . '</a></li>
				<li><a href="' . AdminRoot . '/index.php?com=api">' . $hc_lang_menu['Local'] . '</a></li>				
				<li><a href="' . AdminRoot . '/index.php?com=apiset">' . $hc_lang_menu['TPS'] . '</a></li>
			</ul>
		</li>';
		}			
		if($adminReports == 1){
			echo '
		<li><a href="#">' . $hc_lang_menu['Reports'] . '</a>
			<ul>
				<li><a href="' . AdminRoot . '/index.php?com=reportoverview">' . $hc_lang_menu['Overview'] . '</a></li>
				<li><a href="' . AdminRoot . '/index.php?com=eventsearch">' . $hc_lang_menu['Activity'] . '</a></li>
				<li><a href="' . AdminRoot . '/index.php?com=reportrecent">' . $hc_lang_menu['Recent'] . '</a></li>
				<li><a href="' . AdminRoot . '/index.php?com=reportpopular">' . $hc_lang_menu['MostPopular'] . '</a></li>
				<li><a href="#" class="sub_header">' . $hc_lang_menu['Maintenance'] . '</a></li>
				<li><a href="' . AdminRoot . '/index.php?com=reportfollow">' . $hc_lang_menu['FollowUp'] . '</a></li>
				<li><a href="' . AdminRoot . '/index.php?com=reportdup">' . $hc_lang_menu['DuplicateEvents'] . '</a></li>
				<li><a href="' . AdminRoot . '/index.php?com=reportdupl">' . $hc_lang_menu['DuplicateLocations'] . '</a></li>
				<li><a href="' . AdminRoot . '/index.php?com=reportfail">' . $hc_lang_menu['FailedLogins'] . '</a></li>
			</ul>
		</li>';
		}
		echo '
		<li>
			<a href="#">' . $hc_lang_menu['Help'] . '</a>
			<ul id="subMenu9" class="subMenu">
				<li><a href="' . AdminRoot . '/index.php?com=about">' . $hc_lang_menu['About'] . ' Helios Calendar</a></li>
			</ul>
		</li>
		<li class="nosub"><a href="' . AdminRoot . '/signout.php" onclick="return confirm(\''.$hc_lang_menu['LogoutConfirm'].'\');">' . $hc_lang_menu['Logout'] . '</a></li>
	</ul>';
	} else {
		echo '
	<ul>
		<li><a href="' . AdminRoot . '/signout.php">Permissions Not Found</a></li>
	</ul>';
	}
?>