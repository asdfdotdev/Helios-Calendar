<?php
/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development, LLC.
 * @link http://www.refreshmy.com
 * @copyright (C) 2004-2011 Refresh Web Development
 * @license http://www.helioscalendar.com/license.html
 * @package Helios Calendar
 */
	if(!isset($hc_cfg00)){header("HTTP/1.1 403 No Direct Access");exit();}
	
	include($hc_langPath . $_SESSION['LangSet'] . '/admin/search.php');
	
	$hc_browsePast = 1;
	$hourOffset = date("G") + ($hc_cfg35);
	$sID = (isset($_GET['sID']) && is_numeric($_GET['sID'])) ? cIn($_GET['sID']) : 0;?>
	
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Checkboxes.js"></script>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot . "/" . $hc_langPath . $_SESSION['LangSet'] . "/popCal.js";?>"></script>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/DateSelect.js"></script>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/ajxOutput.js"></script>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function toggleMe(show,hide1,hide2,hide3){
		document.getElementById(show+'div').style.display = 'block';
		document.frmEventSearch.elements[show].disabled = false;
		document.getElementById(hide1+'div').style.display = 'none';
		document.frmEventSearch.elements[hide1].disabled = true;
		document.getElementById(hide2+'div').style.display = 'none';
		document.frmEventSearch.elements[hide2].disabled = true;
		document.getElementById(hide3+'div').style.display = 'none';
		document.frmEventSearch.elements[hide3].disabled = true;
		return false;
	}//end toggleMe()
	
	function chkFrm(){
	dirty = 0;
	warn = "<?php echo $hc_lang_search['Valid01'];?>";
	startDate = document.frmEventSearch.startDate.value;
	endDate = document.frmEventSearch.endDate.value;
		
		if(!isDate(document.frmEventSearch.startDate.value, '<?php echo $hc_cfg51;?>')){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_search['Valid02'] . " " . strtolower($hc_cfg51);?>';
		} else if(document.frmEventSearch.startDate.value == ''){
			dirty = 1;
			warn = warn + "\n<?php echo $hc_lang_search['Valid03'];?>";
		}//end if 
		
		if(!isDate(document.frmEventSearch.endDate.value, '<?php echo $hc_cfg51;?>')){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_search['Valid04'] . " " . strtolower($hc_cfg51);?>';
		} else if(document.frmEventSearch.endDate.value == ''){
			dirty = 1;
			warn = warn + "\n<?php echo $hc_lang_search['Valid05'];?>";
		}//end if
		
		if(compareDates(startDate, '<?php echo $hc_cfg51;?>', endDate, '<?php echo $hc_cfg51;?>') == 1){
			dirty = 1;
			warn = warn + "\n<?php echo $hc_lang_search['Valid06'];?>";
		}//end if

		if(document.frmEventSearch.keyword.value != '' && document.frmEventSearch.keyword.value.length < 4){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_search['Valid19'];?>';
		}//end if
		
		if(validateCheckArray('frmEventSearch','catID[]',1,'Category') > 0){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_search['Valid07'];?>';
		}//end if
		
		if(dirty > 0){
			alert(warn + '\n\n<?php echo $hc_lang_search['Valid08'];?>');
			return false;
		} else {
			return true;
		}//end if
	}//end chkFrm
	
	function searchLocations($page){
		if(document.frmEventSearch.locSearch.value.length > 3){
			var qStr = 'LocationSearch.php?np=1&q=' + escape(document.frmEventSearch.locSearch.value) + '&o=' + $page;
			ajxOutput(qStr, 'locSearchResults', '<?php echo CalRoot;?>');
		}//end if
	}//end searchLocations()
	
	function setLocation(id,name,search){
		document.getElementById('location').value = id;
		if((id == 0) && (search == 1)){
			document.getElementById('locSearch').value = '';
			document.getElementById('locSearchResults').innerHTML = '<?php echo $hc_lang_search['CheckLocInst'];?>';
			document.getElementById('locSearchText').value = '';
		}//end if
	}//end setLocation()

	function splitLocation(str){
		var locParts = str.split("|");
		setLocation(locParts[0],'',0);
	}//end splitLocation()
	
	var calx = new CalendarPopup("dsCal");
	calx.showNavigationDropdowns();
	calx.setCssPrefix("hc_");
	//-->
	</script>
<?php
	if(isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1, $hc_lang_search['Feed01']);
				break;
			case "2" :
				feedback(1, $hc_lang_search['Feed02']);
				break;
			case "3" :
				feedback(1, $hc_lang_search['Feed03']);
				break;
			case "4" :
				feedback(1, $hc_lang_search['Feed04']);
				break;
			case "5" :
				feedback(2, $hc_lang_search['Feed05']);
				break;
			case "6" :
				feedback(1, $hc_lang_search['Feed06']);
				break;
		}//end switch
	}//end if
	
	switch($sID){
		case 1:
			appInstructions(0, "Editing_Events", $hc_lang_search['TitleEdit'], $hc_lang_search['InstructEdit']);
			break;
		case 2:
			appInstructions(0, "Deleting_Events", $hc_lang_search['TitleDelete'], $hc_lang_search['InstructDelete']);
			break;
		case 3:
			appInstructions(0, "Event_Series", $hc_lang_search['TitleSeries'], $hc_lang_search['InstructSeries']);
			break;
		default:
			appInstructions(0, "Reports", $hc_lang_search['TitleReport'], $hc_lang_search['InstructReport']);
			break;
	}//end switch	?>
	<br />
	<form name="frmEventSearch" id="frmEventSearch" method="post" action="<?php echo CalAdminRoot . "/index.php?com=searchresults";?>" onsubmit="return chkFrm();">
	<input type="hidden" name="sID" id="sID" value="<?php echo $sID;?>" />
	<fieldset>
		<legend><?php echo $hc_lang_search['DateTitle'];?></legend>
		<div class="frmReq">
			<label><?php echo $hc_lang_search['DateRange'];?></label>
			<input size="12" maxlength="10" type="text" name="startDate" id="startDate" value="<?php echo strftime($hc_cfg24,mktime($hourOffset,date("i"),date("s"),date("m"),date("d"),date("Y")));?>" /><div class="hc_align">&nbsp;<a href="javascript:;" onclick="calx.select(document.frmEventSearch.startDate,'anchor1','<?php echo $hc_cfg51;?>'); return false;" name="anchor1" id="anchor1"><img src="<?php echo CalAdminRoot;?>/images/icons/iconCalendar.png" width="16" height="16" border="0" alt="" /></a>&nbsp;</div>
			<div class="hc_align">&nbsp;<?php echo $hc_lang_search['To'];?>&nbsp;</div>
			<input size="12" maxlength="10" type="text" name="endDate" id="endDate" value="<?php echo strftime($hc_cfg24, mktime(0, 0, 0, date("m"), date("d") + 7, date("Y")));?>" /><div class="hc_align">&nbsp;<a href="javascript:;" onclick="calx.select(document.frmEventSearch.endDate,'anchor2','<?php echo $hc_cfg51;?>'); return false;" name="anchor2" id="anchor2"><img src="<?php echo CalAdminRoot;?>/images/icons/iconCalendar.png" width="16" height="16" border="0" alt="" /></a>&nbsp;</div>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_search['KeywordTitle'];?></legend>
		<div class="frmOpt">
			<label for="keyword"><?php echo $hc_lang_search['Keywords'];?></label>
			<input size="25" maxlength="50" type="text" name="keyword" id="keyword" value="" />
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend>
	<?php
		echo '<a href="javascript:;" onclick="toggleMe(\'location\',\'city\',\'locState\',\'postal\');" class="legend">' . $hc_lang_search['LinkLocation'] . '</a>';
		echo ' | <a href="javascript:;" onclick="toggleMe(\'city\',\'location\',\'locState\',\'postal\');" class="legend">' . $hc_lang_search['LinkCity'] . '</a>';
		echo ($hc_lang_config['AddressRegion'] != 0) ? ' | <a href="javascript:;" onclick="toggleMe(\'locState\',\'city\',\'postal\',\'location\')" class="legend">' . $hc_lang_config['RegionTitle'] . '</a>' : '';
		echo ' | <a href="javascript:;" onclick="toggleMe(\'postal\',\'city\',\'locState\',\'location\')" class="legend">' . $hc_lang_search['LinkPostal'] . '</a>';
		?>
		</legend>
		<div id="locationdiv">
			<input type="hidden" id="location" name="location" value="0" />
<?php	if($hc_cfg70 == 1){	?>
			<div class="frmOpt">
				<label for="locSearch"><?php echo $hc_lang_search['Location']?></label>
				<input type="text" name="locSearch" id="locSearch" onkeyup="searchLocations();" value = "" size="25" maxlength="100" />
				&nbsp;<a href="javascript:;" onclick="setLocation(0);" class="eventMain"><?php echo $hc_lang_search['ResetSearch']?></a>&nbsp;
			</div>
			<div class="frmOpt">
				<label>&nbsp;</label>
				<div id="locSearchResults"><?php echo $hc_lang_search['CheckLocInst'];?></div>
			</div>
<?php	} else {
			$NewAll = $hc_lang_search['Location0'];
			echo '<div class="locSelect">';
			echo '<label for="locListI">' . $hc_lang_search['Location'] . '</label>';
			include('../events/components/LocationSelect.php');
			echo '</div>';
		}//end if?>
		</div>
		<div id="citydiv" style="display:none;">
			<div class="frmOpt">
				<label for="city"><?php echo $hc_lang_search['City'];?></label>
		<?php 	if(!file_exists(realpath($incPrefix . '../events/cache/selCity.php'))){
					rebuildCache(4,1);
				}//end if
				include($incPrefix . '../events/cache/selCity.php');?>
			</div>
		</div>
		<div id="locStatediv" style="display:none;">
			<div class="frmOpt">
				<label for="locState"><?php echo $hc_lang_config['RegionLabel'];?></label>
				<?php
					$state = $hc_cfg21;
					$regSelect = $hc_lang_search['RegSelect'];
					$stateDisabled = 1;
					include($hc_langPath . $_SESSION['LangSet'] . '/' . $hc_lang_config['RegionFile']);?>
			</div>
		</div>
		<div id="postaldiv" style="display:none;">
			<div class="frmOpt">
				<label for="postal"><?php echo $hc_lang_search['Postal'];?></label>
		<?php 	if(!file_exists(realpath($incPrefix . '../events/cache/selPostal.php'))){
					rebuildCache(5,1);
				}//end if
				include($incPrefix . '../events/cache/selPostal.php');?>
			</div>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_search['CategoryTitle'];?></legend>
		<div class="frmOpt">
			<label><?php echo $hc_lang_search['Categories'];?></label>
		<?php getCategories('frmEventSearch', 3);?>
		</div>
	</fieldset>
<?php
	if($sID == 1){
		echo '<br />';
		echo '<fieldset>';
		echo '<legend>' . $hc_lang_search['SeriesTitle'];
		appInstructionsIcon($hc_lang_search['Tip01A'],$hc_lang_search['Tip01B']);
		echo '</legend>';
		echo '<div class="frmOpt">';
		echo '<label for="seriesonly" class="radioDistPub"><input type="checkbox" name="seriesonly" id="seriesonly" value="1" />' . $hc_lang_search['SeriesOnly'] . '</label>';
		echo '</div></fieldset>';
	}//end if?>
	<br />
	<input type="submit" name="submit" id="submit" value=" <?php echo $hc_lang_search['Search'];?> " class="button" />
	</form>
	<div id="dsCal" class="datePicker"></div>