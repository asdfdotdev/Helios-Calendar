<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(!defined('hcAdmin')){header("HTTP/1.1 403 No Direct Access");exit();}

	include(HCLANG.'/admin/tools.php');
	
	appInstructions(0, "Manage_Database", $hc_lang_tools['TitlePrune'], $hc_lang_tools['InstructPrune']);
		
	$doOpt = (isset($_GET['opt']) && is_numeric($_GET['opt'])) ? cIn($_GET['opt']) : 0;
	$deleted = $optimized = array();
	
	$result = doQuery("SELECT COUNT(PkID) FROM " . HC_TblPrefix . "events WHERE IsActive = 0 OR IsApproved = 0 OR StartDate = '0000-00-00'");
	$deleted[HC_TblPrefix.'events'] = (hasRows($result)) ? mysql_result($result,0,0) : 0;

	$result = doQuery("SELECT COUNT(EventID) FROM " . HC_TblPrefix . "eventnetwork en LEFT JOIN " . HC_TblPrefix . "events e ON (e.PkID = en.EventID) WHERE e.PkID IS NULL OR e.IsActive = 0");
	$deleted[HC_TblPrefix.'eventnetwork'] = (hasRows($result)) ? mysql_result($result,0,0) : 0;

	$result = doQuery("SELECT COUNT(DISTINCT f.PkID)
					FROM " . HC_TblPrefix . "followup f
						LEFT JOIN " . HC_TblPrefix . "events e ON (f.EntityID = e.PkID AND f.EntityType = 1 AND e.IsActive = 1)
						LEFT JOIN " . HC_TblPrefix . "events e2 ON (f.EntityID = e2.SeriesID AND f.EntityType = 2 AND e2.IsActive = 1)
						LEFT JOIN " . HC_TblPrefix . "locations l ON (f.EntityID = l.PkID AND f.EntityType = 3 AND l.IsActive = 1)
					WHERE e.PkID IS NULL AND e2.SeriesID IS NULL AND l.PkID IS NULL");
	$deleted[HC_TblPrefix.'followup'] = (hasRows($result)) ? mysql_result($result,0,0) : 0;
	
	$result = doQuery("SELECT COUNT(PkID) FROM " . HC_TblPrefix . "locations WHERE IsActive = 0");
	$deleted[HC_TblPrefix.'locations'] = (hasRows($result)) ? mysql_result($result,0,0) : 0;
	
	$result = doQuery("SELECT COUNT(LocationID) FROM " . HC_TblPrefix . "locationnetwork ln LEFT JOIN " . HC_TblPrefix . "locations l ON (l.PkID = ln.LocationID) WHERE l.PkID IS NULL OR l.IsActive = 0");
	$deleted[HC_TblPrefix.'locationnetwork'] = (hasRows($result)) ? mysql_result($result,0,0) : 0;

	$result = doQuery("SELECT COUNT(PkID) FROM " . HC_TblPrefix . "categories WHERE IsActive = 0");
	$deleted[HC_TblPrefix.'categories'] = (hasRows($result)) ? mysql_result($result,0,0) : 0;
	
	$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "eventcategories ec LEFT JOIN " . HC_TblPrefix . "events e ON (ec.EventID = e.PkID) WHERE e.PkID is NULL OR e.IsActive = 0 OR e.IsApproved = 0");
	$deleted[HC_TblPrefix.'eventcategories'] = (hasRows($result)) ? mysql_result($result,0,0) : 0;
	
	$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "eventrsvps er LEFT JOIN " . HC_TblPrefix . "events e ON (er.EventID = e.PkID) WHERE e.PkID is NULL OR e.IsActive = 0 OR e.IsApproved = 0");
	$deleted[HC_TblPrefix.'eventrsvps'] = (hasRows($result)) ? mysql_result($result,0,0) : 0;
	
	$result = doQuery("SELECT COUNT(PkID) FROM " . HC_TblPrefix . "templates WHERE IsActive = 0");
	$deleted[HC_TblPrefix.'templates'] = (hasRows($result)) ? mysql_result($result,0,0) : 0;

	$result = doQuery("SELECT COUNT(PkID) FROM " . HC_TblPrefix . "templatesnews WHERE IsActive = 0");
	$deleted[HC_TblPrefix.'templatesnews'] = (hasRows($result)) ? mysql_result($result,0,0) : 0;

	$result = doQuery("SELECT COUNT(PkID) FROM " . HC_TblPrefix . "mailers WHERE IsActive = 0");
	$deleted[HC_TblPrefix.'mailers'] = (hasRows($result)) ? mysql_result($result,0,0) : 0;

	$result = doQuery("SELECT COUNT(PkID) FROM " . HC_TblPrefix . "mailgroups WHERE IsActive = 0");
	$deleted[HC_TblPrefix.'mailgroups'] = (hasRows($result)) ? mysql_result($result,0,0) : 0;

	$result = doQuery("SELECT COUNT(PkID) FROM " . HC_TblPrefix . "newsletters WHERE IsActive = 0");
	$deleted[HC_TblPrefix.'newsletters'] = (hasRows($result)) ? mysql_result($result,0,0) : 0;
	
	$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "subscriberscategories sc LEFT JOIN " . HC_TblPrefix . "subscribers s ON (s.PkID = sc.UserID) WHERE s.PkID IS NULL");
	$deleted[HC_TblPrefix.'subscriberscategories'] = (hasRows($result)) ? mysql_result($result,0,0) : 0;
	
	$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "subscribersgroups sg LEFT JOIN " . HC_TblPrefix . "subscribers s ON (s.PkID = sg.UserID) WHERE s.PkID IS NULL");
	$deleted[HC_TblPrefix.'subscribersgroups'] = (hasRows($result)) ? mysql_result($result,0,0) : 0;
	
	if($doOpt == 1){
		$result = doQuery("OPTIMIZE TABLE `".HC_TblPrefix."admin`,
						`".HC_TblPrefix."adminloginhistory`,`".HC_TblPrefix."adminnotices`,`".HC_TblPrefix."adminpermissions`,`".HC_TblPrefix."categories`,
						`".HC_TblPrefix."eventcategories`,`".HC_TblPrefix."eventnetwork`,`".HC_TblPrefix."eventrsvps`,`".HC_TblPrefix."events`,`".HC_TblPrefix."followup`,
						`".HC_TblPrefix."locationnetwork`,`".HC_TblPrefix."locations`,`".HC_TblPrefix."mailers`,`".HC_TblPrefix."mailersgroups`,`".HC_TblPrefix."mailgroups`,
						`".HC_TblPrefix."newsletters`,`".HC_TblPrefix."newssubscribers`,`".HC_TblPrefix."registrants`,`".HC_TblPrefix."sendtofriend`,`".HC_TblPrefix."settings`,
						`".HC_TblPrefix."settingsmeta`,`".HC_TblPrefix."subscribers`,`".HC_TblPrefix."subscriberscategories`,`".HC_TblPrefix."subscribersgroups`,
						`".HC_TblPrefix."templates`,`".HC_TblPrefix."templatesnews`,`".HC_TblPrefix."users`");
		if(hasRows($result)){
			while($row = mysql_fetch_row($result)){
				$optimized[$row[0]] = $row[3];
			}
		}
	}
	
	$result = doQuery("SHOW TABLE STATUS
					WHERE Name IN ('".HC_TblPrefix."admin',
					'".HC_TblPrefix."adminloginhistory','".HC_TblPrefix."adminnotices','".HC_TblPrefix."adminpermissions','".HC_TblPrefix."categories',
					'".HC_TblPrefix."eventcategories','".HC_TblPrefix."eventnetwork','".HC_TblPrefix."eventrsvps','".HC_TblPrefix."events','".HC_TblPrefix."followup',
					'".HC_TblPrefix."locationnetwork','".HC_TblPrefix."locations','".HC_TblPrefix."mailers','".HC_TblPrefix."mailersgroups','".HC_TblPrefix."mailgroups',
					'".HC_TblPrefix."newsletters','".HC_TblPrefix."newssubscribers','".HC_TblPrefix."registrants','".HC_TblPrefix."sendtofriend','".HC_TblPrefix."settings',
					'".HC_TblPrefix."settingsmeta','".HC_TblPrefix."subscribers','".HC_TblPrefix."subscriberscategories','".HC_TblPrefix."subscribersgroups',
					'".HC_TblPrefix."templates','".HC_TblPrefix."templatesnews','".HC_TblPrefix."users')");
	echo '
	<ul class="data">
		<li class="row header uline">
			<div style="width:30%;">'.$hc_lang_tools['Table'].'</div>
			<div style="width:25%;">&nbsp;'.(($doOpt == 1) ? $hc_lang_tools['Message'] : '&nbsp;').'</div>
			<div class="number" style="width:10%;">'.$hc_lang_tools['Rows'].'</div>
			<div class="number" style="width:15%;">'.$hc_lang_tools['Deleted'].'</div>
			<div class="number" style="width:15%;">'.$hc_lang_tools['Overhead'].'</div>
		</li>';
	$cnt = 0;
	while($row = mysql_fetch_assoc($result)){
		$hl = ($cnt % 2 == 1) ? ' hl' : '';
		$optResult = (array_key_exists($row['Name'],$optimized)) ? $optimized[$row['Name']] : '';
		$optResult = (array_key_exists(DB_NAME.'.'.$row['Name'],$optimized)) ? $optimized[DB_NAME.'.'.$row['Name']] : $optResult;
		
		echo '
		<li class="row'.$hl.'">
			<div style="width:30%;">'.$row['Name'].'</div>
			<div style="width:25%;">'.(($optResult != '') ? $optResult : '&nbsp;').'</div>
			<div class="number" style="width:10%;">'.number_format($row['Rows'],0,'.',',').'</div>
			<div class="number" style="width:15%;">'.((isset($deleted[$row['Name']]) && $deleted[$row['Name']] > 0) ? '<span style="color:crimson">'.number_format($deleted[$row['Name']],0,'.',',').'</span>' : '0').'</div>
			<div class="number" style="width:15%;">'.number_format(($row['Data_free'] * 0.0009765625),1).' KB</div>
		</li>';
		++$cnt;
	}
	echo '
	</ul>	
	<form name="frmToolPrune" id="frmToolPrune" method="post" action="'.AdminRoot.'/components/ToolDBAction.php" onsubmit="return validate();">';
	set_form_token();
	echo '
		<input'.((array_sum($deleted) == 0) ? ' disabled="disabled"':'').' name="submit" id="submit" type="submit" value="'.$hc_lang_tools['DoPrune'].'" />
		<input'.(($doOpt > 0) ? ' disabled="disabled"' : '').' name="optimize" id="optimize" type="button" value="'.$hc_lang_tools['DoOptimize'].'" onclick="window.location.href=\''.AdminRoot.'/index.php?com=db&amp;opt=1\';return false;" />
	</form>
	<script>
	//<!--
	function validate(){
		if(confirm("'.$hc_lang_tools['Valid13'].'\\n\\n          '.$hc_lang_tools['Valid14'].'\\n          '.$hc_lang_tools['Valid15'].'"))
			return true;
		else
			return false;
	}
	//-->
	</script>';
?>
	