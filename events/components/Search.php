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
	include($hc_langPath . $_SESSION['LangSet'] . '/public/search.php');
	
	$hourOffset = date("G") + ($hc_cfg35);	?>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot . "/" . $hc_langPath . $_SESSION['LangSet'] . "/popCal.js";?>"></script>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/DateSelect.js"></script>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Checkboxes.js"></script>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/ajxOutput.js"></script>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function toggleMe(show,hide1,hide2,hide3){
		document.getElementById(show+'div').style.display = 'block';
		document.eventSearch.elements[show].disabled = false;
		document.getElementById(hide1+'div').style.display = 'none';
		document.eventSearch.elements[hide1].disabled = true;
		document.getElementById(hide2+'div').style.display = 'none';
		document.eventSearch.elements[hide2].disabled = true;
		document.getElementById(hide3+'div').style.display = 'none';
		document.eventSearch.elements[hide3].disabled = true;
		return false;
	}//end toggleMe()
	
	function chkFrm() {
	dirty = 0;
	warn = "<?php echo $hc_lang_search['Valid01'];?>\n";
	startDate = document.eventSearch.startDate.value;
	endDate = document.eventSearch.endDate.value;
		if(!isDate(document.eventSearch.startDate.value, '<?php echo $hc_cfg51;?>')){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_search['Valid02'] . " " . strtolower($hc_cfg51);?>';
		}//end if 

		if(!isDate(document.eventSearch.endDate.value, '<?php echo $hc_cfg51;?>')){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_search['Valid03'] . " " . strtolower($hc_cfg51);?>';
		}//end if 
		
		if(document.eventSearch.startDate.value == ''){
			dirty = 1;
			warn = warn + "\n<?php echo $hc_lang_search['Valid04'];?>";
		}//end if
		
		if(document.eventSearch.endDate.value == ''){
			dirty = 1;
			warn = warn + "\n<?php echo $hc_lang_search['Valid05'];?>";
		}//end if
		
		if(compareDates(startDate, '<?php echo $hc_cfg51;?>', endDate, '<?php echo $hc_cfg51;?>') == 1){
			dirty = 1;
			warn = warn + "\n<?php echo $hc_lang_search['Valid06'];?>";
		}//end if
		
<?php 	if($hc_cfg11 == 0){	?>
			if(compareDates(document.eventSearch.startDate.value, '<?php echo $hc_cfg51;?>', '<?php echo strftime($hc_cfg24, mktime(0, 0, 0, date("m"), date("d") - 1, date("Y")) );?>', 'MM/d/yyyy') == 0){
				dirty = 1;
				warn = warn + "\n<?php echo $hc_lang_search['Valid07'];?>";
			}//end if
<?php 	}//end if	?>

		if(document.eventSearch.keyword.value != '' && document.eventSearch.keyword.value.length < 4){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_search['Valid10'];?>';
		}//end if

		if(validateCheckArray('eventSearch','catID[]',1,'Category') > 0){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_search['Valid08'];?>';
		}//end if
		
		if(dirty > 0){
			alert(warn + '\n\n<?php echo $hc_lang_search['Valid09'];?>');
			return false;
		} else {
			return true;
		}//end if
	}//end chkFrm
	
	function searchLocations(page){
		if(document.eventSearch.locSearch.value.length > 3){
			var qStr = 'LocationSearch.php?q=' + escape(document.eventSearch.locSearch.value) + '&a=1&o=' + page;
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
	<br />
	<?php echo $hc_lang_search['SearchLabel'];?>
	<form name="eventSearch" id="eventSearch" method="post" action="<?php echo CalRoot . '/index.php?com=searchresult';?>" onsubmit="return chkFrm();">
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_search['DateRange'];?></legend>
		<div class="frmReq">
			<label><?php echo $hc_lang_search['Dates'];?></label>
			<input name="startDate" id="startDate" type="text" size="12" maxlength="10" value="<?php echo strftime($hc_cfg24,mktime($hourOffset,date("i"),date("s"),date("m"),date("d"),date("Y")));?>" /><div class="hc_align">&nbsp;<a href="javascript:;" onclick="calx.select(document.eventSearch.startDate,'anchor1','<?php echo $hc_cfg51;?>'); return false;" name="anchor1" id="anchor1"><img src="<?php echo CalRoot;?>/images/icons/iconCalendar.png" width="16" height="16" alt="<?php echo $hc_lang_search['ALTStart'];?>" /></a>&nbsp;</div>
			<div class="hc_align">&nbsp;&nbsp;<?php echo $hc_lang_search['To'];?>&nbsp;&nbsp;</div>
			<input name="endDate" id="endDate" type="text" size="12" maxlength="10" value="<?php echo strftime($hc_cfg24, mktime(0, 0, 0, date("m"), date("d") + 7, date("Y")));?>" /><div class="hc_align">&nbsp;<a href="javascript:;" onclick="calx.select(document.eventSearch.endDate,'anchor2','<?php echo $hc_cfg51;?>'); return false;" name="anchor2" id="anchor2"><img src="<?php echo CalRoot;?>/images/icons/iconCalendar.png" width="16" height="16" alt="<?php echo $hc_lang_search['ALTStop'];?>" /></a>&nbsp;</div>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_search['KeywordLabel'];?></legend>
		<div class="frmOpt">
			<label for="keyword"><?php echo $hc_lang_search['Keywords'];?></label>
			<input name="keyword" id="keyword" type="text" size="25" maxlength="50" value="" />
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend>
	<?php
		echo '<a href="javascript:;" onclick="toggleMe(\'location\',\'city\',\'locState\',\'postal\');" class="searchLoc">' . $hc_lang_search['LocationLabel'] . '</a>';
		echo ' | <a href="javascript:;" onclick="toggleMe(\'city\',\'location\',\'locState\',\'postal\');" class="searchLoc">' . $hc_lang_search['CityLabel'] . '</a>';
		echo ($hc_lang_config['AddressRegion'] != 0) ? ' | <a href="javascript:;" onclick="toggleMe(\'locState\',\'city\',\'postal\',\'location\')" class="searchLoc">' . $hc_lang_config['RegionTitle'] . '</a>' : '';
		echo ' | <a href="javascript:;" onclick="toggleMe(\'postal\',\'city\',\'locState\',\'location\')" class="searchLoc">' . $hc_lang_search['PostalLabel'] . '</a>';
		?>
		</legend>
		<div id="locationdiv">
			<input type="hidden" id="location" name="location" value="0" />
<?php	if($hc_cfg70 == 1){	?>
			<div class="frmOpt">
				<label for="locSearch"><?php echo $hc_lang_search['Location']?></label>
				<input type="text" name="locSearch" id="locSearch" onkeyup="searchLocations();" value = "" size="25" maxlength="100" />
				&nbsp;<a href="javascript:;" onclick="setLocation(0,'',1);" class="eventMain"><?php echo $hc_lang_search['ResetSearch']?></a>&nbsp;
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
		<?php 	if(!file_exists(realpath('cache/selCity.php'))){
					rebuildCache(4);
				}//end if
				include('cache/selCity.php');?>
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
		<?php 	if(!file_exists(realpath('cache/selPostal.php'))){
					rebuildCache(5);
				}//end if
				include('cache/selPostal.php');?>
			</div>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_search['CategoryLabel'];?></legend>
		<div class="frmOpt">
			<label><?php echo $hc_lang_search['Categories'];?></label>
		<?php getCategories('eventSearch', 3);?>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_search['RecurringLabel'];?></legend>
		<div class="frmOpt">
			<label for="recurSet"><?php echo $hc_lang_search['RecurSet'];?></label>
			<select name="recurSet" id="recurSet">
				<option value="0"><?php echo $hc_lang_search['RecurSet0'];?></option>
				<option value="1"><?php echo $hc_lang_search['RecurSet1'];?></option>
			</select>
		</div>
	</fieldset>
	<br />
	<input type="submit" name="submit" id="submit" value="<?php echo $hc_lang_search['BeginSearch'];?>" class="button" />
	</form>
	<div id="dsCal" class="datePicker"></div>