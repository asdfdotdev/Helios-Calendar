<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	hookDB();
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "adminpermissions WHERE AdminID = " . $_SESSION['AdminPkID'] . " and IsActive = 1");
	if(hasRows($result)){
		$adminEventEdit = mysql_result($result,0,"EventEdit");
		$adminEventPending = mysql_result($result,0,"EventPending");
		$adminEventCategory = mysql_result($result,0,"EventCategory");
		$adminUserEdit = mysql_result($result,0,"UserEdit");
		$adminAdminEdit = mysql_result($result,0,"AdminEdit");
		$adminNewsletter = mysql_result($result,0,"Newsletter");
		$adminSettings = mysql_result($result,0,"Settings");
		$adminReports = mysql_result($result,0,"Reports");
?>
		<ul class="menu">
			<li><a href="<?echo CalAdminRoot;?>/">Home</a></li>
			
			<?if($adminEventEdit == 1 OR $adminEventPending == 1 OR $adminEventCategory == 1){?>
				<li onmouseover="subMenu( true, 0 )" onmouseout="subMenu( false, 0 )">
				<a href="javascript:;">Edit Events</a>
					<ul id="subMenu0" class="subMenu">
						<?if($adminEventEdit == 1){?>
							<li><a href="<?echo CalAdminRoot;?>/index.php?com=eventadd">Add&nbsp;Event</a></li>
							<li><a href="<?echo CalAdminRoot;?>/index.php?com=eventsearch&sID=1">Edit&nbsp;Event</a></li>
							<li><a href="<?echo CalAdminRoot;?>/index.php?com=eventsearch&sID=2">Delete&nbsp;Event</a></li>
							<li><a href="<?echo CalAdminRoot;?>/index.php?com=eventsearch&sID=3">Create&nbsp;Series</a></li>
						<?}//end if?>
					</ul>
				</li>
			<?}//end if?>
			
			
			<?if($adminEventEdit == 1 OR $adminEventPending == 1 OR $adminEventCategory == 1){?>
				<li onmouseover="subMenu( true, 1 )" onmouseout="subMenu( false, 1 )">
				<a href="javascript:;">Manage Events</a>
					<ul id="subMenu1" class="subMenu">
						<?if($adminEventPending == 1){?>
						<li><a href="<?echo CalAdminRoot;?>/index.php?com=eventpending">Pending&nbsp;Events</a></li>
						<?}//end if?>
						
						<?if($adminEventEdit == 1){?>
						<li><a href="<?echo CalAdminRoot;?>/index.php?com=eventbillboard">Billboard&nbsp;Events</a></li>
						<li><a href="<?echo CalAdminRoot;?>/index.php?com=eventorphan">Orphan&nbsp;Events</a></li>
						<?}//end if?>
						
						<?if($adminEventCategory == 1){?>
						<li><a href="<?echo CalAdminRoot;?>/index.php?com=eventcategorymanage">Category&nbsp;Management</a></li>
						<?}//end if?>
					</ul>
				</li>
			<?}//end if?>
			
			<?if($adminUserEdit == 1 OR $adminNewsletter == 1){?>
				<li onmouseover="subMenu( true, 2 )" onmouseout="subMenu( false, 2 )">
				<a href="javascript:;">Newsletter</a>
					<ul id="subMenu2" class="subMenu">
						<?if($adminUserEdit == 1){?>
						<li><a href="<?echo CalAdminRoot;?>/index.php?com=useredit">Add&nbsp;Recipient</a></li>
						<li><a href="<?echo CalAdminRoot;?>/index.php?com=userbrowse">Edit&nbsp;Recipient</a></li>
						<li><a href="<?echo CalAdminRoot;?>/index.php?com=usersearch">Search&nbsp;Recipients</a></li>
						<?}//end if?>
						
						<?if($adminNewsletter == 1){?>
						<li><a href="<?echo CalAdminRoot;?>/index.php?com=newsletter">Send&nbsp;Newsletter</a></li>
						<li><a href="<?echo CalAdminRoot;?>/index.php?com=newsletteredit">Edit&nbsp;Templates</a></li>
						<?}//end if?>
					</ul>
				</li>
			<?}//end if?>
			
			<?if($adminAdminEdit == 1){?>
				<li onmouseover="subMenu( true, 3 )" onmouseout="subMenu( false, 3 )">
				<a href="javascript:;">Admin</a>
					<ul id="subMenu3" class="subMenu">
						<?if($adminAdminEdit == 1){?>
						<li><a href="<?echo CalAdminRoot;?>/index.php?com=adminedit">Add&nbsp;Administrator</a></li>
						<li><a href="<?echo CalAdminRoot;?>/index.php?com=adminbrowse">Edit&nbsp;Administrator</a></li>
						<?}//end if?>
						
						
					</ul>
				</li>
			<?}//end if?>
			
			<?if($adminSettings == 1){?>
				<li onmouseover="subMenu( true, 4 )" onmouseout="subMenu( false, 4 )">
				<a href="javascript:;">Settings</a>
					<ul id="subMenu4" class="subMenu">
						<li><a href="<?echo CalAdminRoot;?>/index.php?com=generalset">Configure&nbsp;Helios</a></li>
						<li><a href="<?echo CalAdminRoot;?>/index.php?com=optimize">Search&nbsp;Optimization</a></li>
					</ul>
				</li>
			<?}//end if?>
			
			<?if($adminReports == 1){?>
				<li onmouseover="subMenu( true, 5 )" onmouseout="subMenu( false, 5 )">
				<a href="javascript:;">Reports</a>
					<ul id="subMenu5" class="subMenu">
						<li><a href="<?echo CalAdminRoot;?>/index.php?com=eventsearch">Event&nbsp;Activity&nbsp;Report</a></li>
						<li><a href="<?echo CalAdminRoot;?>/index.php?com=mostpopular">Most&nbsp;Popular&nbsp;Calendar&nbsp;Events</a></li>
						<li><a href="<?echo CalAdminRoot;?>/index.php?com=mostpopular&mID=1">Most&nbsp;Popular&nbsp;Mobile&nbsp;Events</a></li>
						<li><a href="<?echo CalAdminRoot;?>/index.php?com=recentadd">Recently&nbsp;Added&nbsp;Events</a></li>
					</ul>
				</li>
			<?}//end if?>
			
			<li onmouseover="subMenu( true, 6 )" onmouseout="subMenu( false, 6 )">
			<a href="javascript:;">Help</a>
				<ul id="subMenu6" class="subMenu">
					<li><a href="http://codex.helioscalendar.com" target="_blank">Documentation</a></li>
					<li><a href="http://forum.helioscalendar.com" target="_blank">Support&nbsp;Forums</a></li>
					<li><a href="<?echo CalAdminRoot;?>/index.php?com=update">Update&nbsp;Check</a></li>
					<li><a href="http://codex.helioscalendar.com/index.php?title=Versions" target="_blank">Change&nbsp;Log</a></li>
					<li><a href="javascript:;" onclick="javascript:popAbout();">About&nbsp;Helios&nbsp;<?echo HC_Version;?></a></li>
				</ul>
			</li>
			<li><a href="<?echo CalAdminRoot . "/" . HC_LogOut;?>">[&nbsp;Logout:&nbsp;<?echo $_SESSION['AdminFirstName'];?>&nbsp;]</a></li>
		</ul>
<?php
	} else {
?>
	<ul class="menu">
		<li><a href="<?echo CalAdminRoot . "/" . HC_LogOut;?>">Permissions Not Found</a></li>
	</ul>
<?php
	}//end if
?>
<script langauge="JavaScript">
var ua = navigator.userAgent;
var ual = ua.toLowerCase();
var brokenHover = (ual.indexOf("msie") != -1);

function subMenu( show, menuNumber ) {
   if (show) {
      if (brokenHover) {
         var sMenu = document.getElementById('subMenu' + menuNumber);
         sMenu.style.display = "block";
      }
   } else {
      if (brokenHover) {
         var sMenu = document.getElementById('subMenu' + menuNumber);
         sMenu.style.display = "none";
      }
   }
}//end subMenu()

function popAbout(){
	var windowW = 460;
	var windowH = 440;
	
	var windowX = (screen.width / 2) - (windowW / 2);
	var windowY = (screen.height / 2) - (windowH / 2);

	aboutWin = window.open('<?echo CalAdminRoot;?>/components/About.php', 'about', 'width='+windowW+', height='+windowH+', scrollbars=yes');
	
	aboutWin.resizeTo(windowW, windowH);
	aboutWin.moveTo(windowX, windowY);
	
	aboutWin.focus();
}//end popAbout()
</script>