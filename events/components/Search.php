<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/public/search.php');
	
	$hourOffset = date("G") + ($hc_cfg35);	?>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot . "/" . $hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . "/popCal.js";?>"></script>
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
	
	function searchLocations($page){
		if(document.eventSearch.locSearch.value.length > 3){
			var qStr = 'LocationSearch.php?q=' + escape(document.eventSearch.locSearch.value) + '&a=1&o=' + $page;
			ajxOutput(qStr, 'locSearchResults', '<?php echo CalRoot;?>');
		}//end if
	}//end searchLocations()
	
	function setLocation($id){
		document.eventSearch.location.value = $id;
		if($id == 0){
			document.getElementById('locSearchResults').innerHTML = '<?php echo $hc_lang_search['CheckLocInst'];?>';
			document.eventSearch.locSearch.value = '';
		}//end if
	}//end setLocation
	
	var calx = new CalendarPopup("dsCal");
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
			<input name="startDate" id="startDate" type="text" size="12" maxlength="10" value="<?php echo strftime($hc_cfg24,mktime($hourOffset,date("i"),date("s"),date("m"),date("d"),date("Y")));?>" />&nbsp;<a href="javascript:;" onclick="calx.select(document.eventSearch.startDate,'anchor1','<?php echo $hc_cfg51;?>'); return false;" name="anchor1" id="anchor1"><img src="<?php echo CalRoot;?>/images/icons/iconCalendar.png" width="16" height="16" alt="<?php echo $hc_lang_search['ALTStart'];?>" /></a>
			&nbsp;&nbsp;<?php echo $hc_lang_search['To'];?>&nbsp;&nbsp;
			<input name="endDate" id="endDate" type="text" size="12" maxlength="10" value="<?php echo strftime($hc_cfg24, mktime(0, 0, 0, date("m"), date("d") + 7, date("Y")));?>" />&nbsp;<a href="javascript:;" onclick="calx.select(document.eventSearch.endDate,'anchor2','<?php echo $hc_cfg51;?>'); return false;" name="anchor2" id="anchor2"><img src="<?php echo CalRoot;?>/images/icons/iconCalendar.png" width="16" height="16" alt="<?php echo $hc_lang_search['ALTStop'];?>" /></a>
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
			<div class="frmOpt">
				<label for="locSearch"><?php echo $hc_lang_search['Location'];?></label>
				<input type="text" name="locSearch" id="locSearch" onkeyup="searchLocations();" value = "" size="25" maxlength="100" />
				<a href="javascript:;" onclick="setLocation(0);" class="eventMain"><?php echo $hc_lang_search['ResetSearch'];?></a>
			</div>
			<div class="frmOpt">
				<label>&nbsp;</label>
				<div id="locSearchResults"><?php echo $hc_lang_search['CheckLocInst'];?></div>
			</div>
		</div>
		<div id="citydiv" style="display:none;">
			<div class="frmOpt">
				<label for="city"><?php echo $hc_lang_search['City'];?></label>
				<select name="city" id="city" disabled="disabled">
					<option value=""><?php echo $hc_lang_search['City0'];?></option>
			<?php 	$cities = getCities($hc_cfg11); 
					foreach($cities as $val){
						if($val != ''){?>
						<option value="<?php echo $val;?>"><?php echo $val;?></option>
			<?php 		}//end if
					}//end foreach	?>
				</select>
			</div>
		</div>
		<div id="locStatediv" style="display:none;">
			<div class="frmOpt">
				<label for="locState"><?php echo $hc_lang_config['RegionLabel'];?></label>
				<?php
					$state = $hc_cfg21;
					$stateDisabled = 1;
					include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/' . $hc_lang_config['RegionFile']);?>
			</div>
		</div>
		<div id="postaldiv" style="display:none;">
			<div class="frmOpt">
				<label for="postal"><?php echo $hc_lang_search['Postal'];?></label>
				<select name="postal" id="postal" disabled="disabled">
					<option value=""><?php echo $hc_lang_search['Postal0'];?></option>
			<?php 	$codes = getPostal($hc_cfg11); 
					foreach($codes as $val){
						if($val != ''){?>
						<option value="<?php echo $val;?>"><?php echo $val;?></option>
			<?php 		}//end if
					}//end foreach	?>
				</select>
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