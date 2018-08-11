<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/admin/tools.php');
	
	appInstructions(0, "Manage_Database", $hc_lang_tools['TitlePrune'], $hc_lang_tools['InstructPrune']);
	echo '<br />';
	
	if (isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1, $hc_lang_tools['Feed03']);
				break;
		}//end switch
	}//end if	?>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Checkboxes.js"></script>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function chkFrm(){
		if(confirm('<?php echo $hc_lang_tools['Valid13'] . "\\n\\n          " . $hc_lang_tools['Valid14'] . "\\n          " . $hc_lang_tools['Valid15'];?>')){
			return true;
		} else {
			return false;
		}//end if
	}//end chkFrm()
	//-->
	</script>
<?php	
	echo '<fieldset>';
	echo '<legend>' . $hc_lang_tools['PurgeLabel'] . '</legend>';		
	echo $hc_lang_tools['PurgeDesc'];
	echo '<br /><br />';
	
	$result = doQuery("SELECT COUNT(PkID) FROM " . HC_TblPrefix . "events WHERE IsActive = 0 OR IsApproved = 0 OR StartDate = '0000-00-00'");
	$pEvents = (hasRows($result)) ? mysql_result($result,0,0) : 0;

	$result = doQuery("SELECT COUNT(EventID) FROM " . HC_TblPrefix . "eventnetwork en LEFT JOIN " . HC_TblPrefix . "events e ON (e.PkID = en.EventID) WHERE e.PkID IS NULL OR e.IsActive = 0");
	$pEventNetwork = (hasRows($result)) ? mysql_result($result,0,0) : 0;

	$result = doQuery("SELECT COUNT(PkID) FROM " . HC_TblPrefix . "locations WHERE IsActive = 0");
	$pLoc = (hasRows($result)) ? mysql_result($result,0,0) : 0;
	
	$result = doQuery("SELECT COUNT(LocationID) FROM " . HC_TblPrefix . "locationnetwork ln LEFT JOIN " . HC_TblPrefix . "locations l ON (l.PkID = ln.LocationID) WHERE l.PkID IS NULL OR l.IsActive = 0");
	$pLocationNetwork = (hasRows($result)) ? mysql_result($result,0,0) : 0;

	$result = doQuery("SELECT COUNT(PkID) FROM " . HC_TblPrefix . "categories WHERE IsActive = 0");
	$pCats = (hasRows($result)) ? mysql_result($result,0,0) : 0;
	
	$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "eventcategories ec LEFT JOIN " . HC_TblPrefix . "events e ON (ec.EventID = e.PkID) WHERE e.PkID is NULL OR e.IsActive = 0 OR e.IsApproved = 0");
	$pEventCat = (hasRows($result)) ? mysql_result($result,0,0) : 0;
	
	$result = doQuery("SELECT COUNT(PkID) FROM " . HC_TblPrefix . "oidusers WHERE IsActive = 0");
	$oidUsers = (hasRows($result)) ? mysql_result($result,0,0) : 0;
	
	$result = doQuery("SELECT COUNT(PkID) FROM " . HC_TblPrefix . "comments WHERE IsActive = 0");
	$pComments = (hasRows($result)) ? mysql_result($result,0,0) : 0;
	
	$result = doQuery("SELECT COUNT(PkID) FROM " . HC_TblPrefix . "commentsreportlog WHERE IsActive = 0");
	$pCommentLog = (hasRows($result)) ? mysql_result($result,0,0) : 0;

	$result = doQuery("SELECT COUNT(PkID) FROM " . HC_TblPrefix . "templates WHERE IsActive = 0");
	$pTemplates = (hasRows($result)) ? mysql_result($result,0,0) : 0;
	
	echo '<div style="line-height:15px;">';
	echo '<div class="toolsTitle">' . $hc_lang_tools['DeletedData'] . '</div>';
	echo HC_TblPrefix . 'events: <b>' . $pEvents . '</b><br />';
	echo HC_TblPrefix . 'eventnetwork: <b>' . $pEventNetwork . '</b><br />';
	echo HC_TblPrefix . 'locations: <b>' . $pLoc . '</b><br />';
	echo HC_TblPrefix . 'locationnetwork: <b>' . $pLocationNetwork . '</b><br />';
	echo HC_TblPrefix . 'categories: <b>' . $pCats . '</b><br />';
	echo HC_TblPrefix . 'eventcategories: <b>' . $pEventCat . '</b><br />';
	echo HC_TblPrefix . 'oidusers: <b>' . $oidUsers . '</b><br />';
	echo HC_TblPrefix . 'comments: <b>' . $pComments . '</b><br />';
	echo HC_TblPrefix . 'commentsreportlog: <b>' . $pCommentLog . '</b><br />';
	echo HC_TblPrefix . 'templates: <b>' . $pTemplates . '</b><br />';
	echo '</div></fieldset><br />';
	
	if($pEvents > 0 || $pEventNetwork > 0 || $pLoc > 0 || $pLocationNetwork > 0 || $pCats > 0 || $pEventCat > 0 || $oidUsers > 0 || $pComments > 0 || $pCommentLog > 0){
		echo '<form name="frmToolPrune" id="frmToolPrune" method="post" action="' . CalAdminRoot . '/components/ToolDBAction.php" onsubmit="return chkFrm();">';
		echo '<input name="submit" id="submit" type="submit" value="' . $hc_lang_tools['DoPrune'] . '" class="button" />';
		echo '</form><br />';
	}//end if
	
	echo '<br /><fieldset>';
	echo '<legend>' . $hc_lang_tools['OptimizeLabel'] . ' - [ <a href="http://dev.mysql.com/doc/refman/5.1/en/optimize-table.html" class="eventMain" target="_blank">' . $hc_lang_tools['OptimizeDoc'] . '</a> ]</legend>';
	echo $hc_lang_tools['OptimizeDesc'];
	
	$doOptimize = (isset($_GET['opt']) && is_numeric($_GET['opt'])) ? $_GET['opt'] : 0;
	if($doOptimize == 1){
		$result = doQuery("OPTIMIZE TABLE `".HC_TblPrefix."admin`,
							`".HC_TblPrefix."adminloginhistory`,
							`".HC_TblPrefix."adminnotices`,
							`".HC_TblPrefix."adminpermissions`,
							`".HC_TblPrefix."categories`,
							`".HC_TblPrefix."comments`,
							`".HC_TblPrefix."commentsreportlog`,
							`".HC_TblPrefix."eventcategories`,
							`".HC_TblPrefix."eventnetwork`,
							`".HC_TblPrefix."events`,
							`".HC_TblPrefix."locationnetwork`,
							`".HC_TblPrefix."locations`,
							`".HC_TblPrefix."newsletters`,
							`".HC_TblPrefix."oidusers`,
							`".HC_TblPrefix."recomndslog`,
							`".HC_TblPrefix."registrants`,
							`".HC_TblPrefix."sendtofriend`,
							`".HC_TblPrefix."settings`,
							`".HC_TblPrefix."settingsmeta`,
                                   `".HC_TblPrefix."templates`,
							`".HC_TblPrefix."usercategories`,
							`".HC_TblPrefix."users`");
		if(hasRows($result)){
			$cnt = 0;
			echo '<br /><br /><div class="toolsTitle" style="color:#DC143C;">' . $hc_lang_tools['OptimizeResult'] . '</div>';
			while($row = mysql_fetch_row($result)){
				echo ($cnt % 2 == 0) ? '<div class="toolsOpt">' : '<div class="toolsOptHL">';
				echo $row[0] . '</div>';
				echo ($cnt % 2 == 0) ? '<div class="toolsOpt">' : '<div class="toolsOptHL">';
				echo $row[3] . '</div>';
				++$cnt;
			}//end if
		}//end if
	}//end if
	echo '</fieldset>';
	echo ($doOptimize == 1) ? '' : '<br /><input name="optimize" id="optimize" type="button" value="' . $hc_lang_tools['DoOptimize'] . '" onclick="window.location.href=\'' . CalAdminRoot . '/index.php?com=db&opt=1\';return false;" class="button" />';?>