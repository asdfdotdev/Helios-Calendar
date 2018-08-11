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
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "adminpermissions WHERE AdminID = " . $_SESSION['AdminPkID'] . " and IsActive = 1");
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
		$adminLocations = mysql_result($result,0,"Locations");	?>
		<ul class="menu">
			<li><a href="<?php echo CalAdminRoot;?>/" class="menuNoSub"><span><?php echo $hc_lang_menu['Home'];?></span></a></li>
		
<?php	if($adminEventEdit == 1 OR $adminEventPending == 1 OR $adminEventCategory == 1){?>
			<li onmouseover="subMenu(true,1)" onmouseout="subMenu(false,1);">
				<a href="#" class="MenuLink"><span><?php echo $hc_lang_menu['Events'];?></span></a>
				<ul id="subMenu1" class="subMenu">
		<?php	if($adminEventEdit == 1){?>
					<li><a href="#" class="subMenuHeader"><?php echo $hc_lang_menu['Edits'];?></a></li>
					<li><a href="<?php echo CalAdminRoot;?>/index.php?com=eventadd" class="subMenuLink"><?php echo $hc_lang_menu['AddE'];?></a></li>
					<li><a href="<?php echo CalAdminRoot;?>/index.php?com=eventsearch&amp;sID=1" class="subMenuLink"><?php echo $hc_lang_menu['EditE'];?></a></li>
					<li><a href="<?php echo CalAdminRoot;?>/index.php?com=eventsearch&amp;sID=2" class="subMenuLink"><?php echo $hc_lang_menu['Delete'];?></a></li>
					<li><a href="<?php echo CalAdminRoot;?>/index.php?com=eventsearch&amp;sID=3" class="subMenuLink"><?php echo $hc_lang_menu['Create'];?></a></li>
		<?php 	}
				if($adminEventEdit == 1 && ($adminEventEdit == 1 OR $adminEventPending == 1 OR $adminEventCategory == 1)){?>
					<li><a href="#" class="subMenuHeader"><?php echo $hc_lang_menu['Manage'];?></a></li>
		<?php	}
				if($adminEventPending == 1){?>
					<li><a href="<?php echo CalAdminRoot;?>/index.php?com=eventpending" class="subMenuLink"><?php echo $hc_lang_menu['Pending'];?></a></li>
		<?php 	}
				if($adminEventEdit == 1){?>
					<li><a href="<?php echo CalAdminRoot;?>/index.php?com=eventbillboard" class="subMenuLink"><?php echo $hc_lang_menu['Billboard'];?></a></li>
					<li><a href="<?php echo CalAdminRoot;?>/index.php?com=eventorphan" class="subMenuLink"><?php echo $hc_lang_menu['Orphan'];?></a></li>
		<?php 	}
				if($adminEventCategory == 1){?>
					<li><a href="<?php echo CalAdminRoot;?>/index.php?com=categorymanage" class="subMenuLink"><?php echo $hc_lang_menu['Category'];?></a></li>
		<?php	}?>
				</ul>
			</li>
<?php	}//end if
		if($adminLocations == 1){?>
			<li onmouseover="subMenu(true,2)" onmouseout="subMenu(false,2);">
				<a href="#" class="MenuLink"><span><?php echo $hc_lang_menu['Locations'];?></span></a>
				<ul id="subMenu2" class="subMenu">
					<li><a href="#" class="subMenuHeader"><?php echo $hc_lang_menu['EditsM'];?></a></li>
					<li><a href="<?php echo CalAdminRoot;?>/index.php?com=addlocation" class="subMenuLink"><?php echo $hc_lang_menu['AddL'];?></a></li>
					<li><a href="<?php echo CalAdminRoot;?>/index.php?com=location" class="subMenuLink"><?php echo $hc_lang_menu['EditL'];?></a></li>
					<li><a href="#" class="subMenuHeader"><?php echo $hc_lang_menu['ManageM'];?></a></li>
					<li><a href="<?php echo CalAdminRoot;?>/index.php?com=locsearch" class="subMenuLink"><?php echo $hc_lang_menu['MergeL'];?></a></li>
				</ul>
			</li>
<?php	}
		if($adminAdminEdit == 1 || $adminUserEdit == 1){?>
			<li onmouseover="subMenu(true,3)" onmouseout="subMenu(false,3)">
			<a href="javascript:;" class="MenuLink"><span><?php echo $hc_lang_menu['Users'];?></span></a>
				<ul id="subMenu3" class="subMenu">
			<?php 	if($adminAdminEdit == 1){?>
					<li><a href="#" class="subMenuHeader"><?php echo $hc_lang_menu['Admin'];?></a></li>
					<li><a href="<?php echo CalAdminRoot;?>/index.php?com=adminedit" class="subMenuLink"><?php echo $hc_lang_menu['AddA'];?></a></li>
					<li><a href="<?php echo CalAdminRoot;?>/index.php?com=adminbrowse" class="subMenuLink"><?php echo $hc_lang_menu['EditA'];?></a></li>
			<?php 	}
					if($adminUserEdit == 1){?>
					<li><a href="#" class="subMenuHeader"><?php echo $hc_lang_menu['Newsletter'];?></a></li>
					<li><a href="<?php echo CalAdminRoot;?>/index.php?com=useredit" class="subMenuLink"><?php echo $hc_lang_menu['AddR'];?></a></li>
					<li><a href="<?php echo CalAdminRoot;?>/index.php?com=userbrowse" class="subMenuLink"><?php echo $hc_lang_menu['EditR'];?></a></li>
					<li><a href="<?php echo CalAdminRoot;?>/index.php?com=usersearch" class="subMenuLink"><?php echo $hc_lang_menu['SearchR'];?></a></li>
			<?php 	}?>
				</ul>
			</li>
<?php	}//end if
		
		if($adminTools == 1 || $adminNewsletter == 1){?>
			<li onmouseover="subMenu(true,4)" onmouseout="subMenu(false,4)">
			<a href="#" class="MenuLink"><span><?php echo $hc_lang_menu['Tools'];?></span></a>
				<ul id="subMenu4" class="subMenu">
			<?php 	if($adminTools == 1){?>
					<li><a href="<?php echo CalAdminRoot;?>/index.php?com=filter" class="subMenuLink"><?php echo $hc_lang_menu['Filter'];?></a></li>
					<li><a href="<?php echo CalAdminRoot;?>/index.php?com=db" class="subMenuLink"><?php echo $hc_lang_menu['Prune'];?></a></li>
					<li><a href="#" class="subMenuHeader"><?php echo $hc_lang_menu['Data'];?></a></li>
					<li><a href="<?php echo CalAdminRoot;?>/index.php?com=export" class="subMenuLink"><?php echo $hc_lang_menu['Export'];?></a></li>
					<li><a href="<?php echo CalAdminRoot;?>/index.php?com=import" class="subMenuLink"><?php echo $hc_lang_menu['Import'];?></a></li>
			<?php 	}
					if($adminNewsletter == 1){?>	
					<li><a href="#" class="subMenuHeader"><?php echo $hc_lang_menu['Newsletter'];?></a></li>
					<li><a href="<?php echo CalAdminRoot;?>/index.php?com=newsletter" class="subMenuLink"><?php echo $hc_lang_menu['Send'];?></a></li>
					<li><a href="<?php echo CalAdminRoot;?>/index.php?com=newsletteredit" class="subMenuLink"><?php echo $hc_lang_menu['EditT'];?></a></li>
			<?php 	}?>
				</ul>
			</li>
<?php	}//end if
		
		if($adminSettings == 1){?>
			<li onmouseover="subMenu(true,5)" onmouseout="subMenu(false,5)">
			<a href="#" class="MenuLink"><span><?php echo $hc_lang_menu['Settings'];?></span></a>
				<ul id="subMenu5" class="subMenu">
					<li><a href="<?php echo CalAdminRoot;?>/index.php?com=generalset" class="subMenuLink"><?php echo $hc_lang_menu['Configure'];?></a></li>
					<li><a href="<?php echo CalAdminRoot;?>/index.php?com=optimize" class="subMenuLink"><?php echo $hc_lang_menu['SearchO'];?></a></li>
				</ul>
			</li>
<?php	}//end if
					
		if($adminReports == 1){?>
			<li onmouseover="subMenu(true,6)" onmouseout="subMenu(false,6)">
			<a href="#" class="MenuLink"><span><?php echo $hc_lang_menu['Reports'];?></span></a>
				<ul id="subMenu6" class="subMenu">
					<li><a href="<?php echo CalAdminRoot;?>/index.php?com=eventsearch" class="subMenuLink"><?php echo $hc_lang_menu['Activity'];?></a></li>
					<li><a href="<?php echo CalAdminRoot;?>/index.php?com=reportrecent" class="subMenuLink"><?php echo $hc_lang_menu['Recent'];?></a></li>
					<li><a href="<?php echo CalAdminRoot;?>/index.php?com=reportoverview" class="subMenuLink"><?php echo $hc_lang_menu['Overview'];?></a></li>
					<li><a href="#" class="subMenuHeader"><?php echo $hc_lang_menu['MostPopular'];?></a></li>
					<li><a href="<?php echo CalAdminRoot;?>/index.php?com=reportpopular" class="subMenuLink"><?php echo $hc_lang_menu['Popular'];?></a></li>
					<li><a href="<?php echo CalAdminRoot;?>/index.php?com=reportpopular&amp;mID=1" class="subMenuLink"><?php echo $hc_lang_menu['PopularM'];?></a></li>		
				</ul>
			</li>
<?php	}//end if?>			
			
			<li onmouseover="subMenu(true,7)" onmouseout="subMenu(false,7);">
				<a href="#" class="MenuLink"><span><?php echo $hc_lang_menu['Help'];?></span></a>
				<ul id="subMenu7" class="subMenu">
					<li><a href="<?php echo CalAdminRoot;?>/index.php?com=update" class="subMenuLink"><?php echo $hc_lang_menu['Update'];?></a></li>
					<li><a href="#" onclick="javascript:popAbout();" class="subMenuLink"><?php echo $hc_lang_menu['About'];?> Helios <?php echo HC_Version;?></a></li>
					<li><a href="#" class="subMenuHeader"><?php echo $hc_lang_menu['OnlineResources'];?></a></li>
					<li><a href="http://www.refreshmy.com/documentation/?title=Helios" target="_blank" class="subMenuLink"><?php echo $hc_lang_menu['Documentation'];?></a></li>
					<li><a href="http://www.refreshmy.com/forum/" target="_blank" class="subMenuLink"><?php echo $hc_lang_menu['Forum'];?></a></li>
				</ul>
			</li>
			
			<li><a href="<?php echo CalAdminRoot . "/" . HC_LogOut;?>" class="menuNoSub"><span><?php echo $hc_lang_menu['Logout'];?></span></a></li>
		</ul>
<?php
	} else {	?>
	<ul class="menu">
		<li><a href="<?php echo CalAdminRoot . "/" . HC_LogOut;?>">Permissions Not Found</a></li>
	</ul>
<?php
	}//end if?>
<script language="JavaScript" type="text/JavaScript">
//<!--
function subMenu(show,menuNumber) {
	var sMenu = document.getElementById('subMenu' + menuNumber);
	show ? sMenu.style.display = "block" : sMenu.style.display = "none";
}//end subMenu()

function popAbout(){
	var windowW = 460;
	var windowH = 485;
	var windowX = (screen.width / 2) - (windowW / 2);
	var windowY = (screen.height / 2) - (windowH / 2);
	aboutWin = window.open('<?php echo CalAdminRoot;?>/components/About.php', 'about', 'width='+windowW+', height='+windowH+', scrollbars=yes');
	aboutWin.resizeTo(windowW, windowH);
	aboutWin.moveTo(windowX, windowY);
	aboutWin.focus();
}//end popAbout()
//-->
</script>