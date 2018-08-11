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
	
	include($hc_langPath . $_SESSION['LangSet'] . '/admin/tools.php');
	
	$tID = (isset($_GET['tID']) && is_numeric($_GET['tID'])) ? cIn($_GET['tID']) : 0;
	
	$whereAmI = $hc_lang_tools['AddT'];
	$helpDoc = "Export_Templates";
	$helpText = $hc_lang_tools['InstructAddTExp'];
	$name = '';
	$content = '';
	$header = '';
	$footer = '';
	$ext = '';
	$typeID = 1;
	$groupBy = 1;
	$sortBy = 2;
	$cleanup = "BLANK";
	$dateFormat = 0;
	
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "templates WHERE PkID = " . $tID . " AND IsActive = 1");
	if(hasRows($result)){
		$whereAmI = $hc_lang_tools['EditT'];
		$helpText = $hc_lang_tools['InstructEditTExp'];
		$name = cOut(mysql_result($result,0,1));
		$content = cOut(mysql_result($result,0,2));
		$header = cOut(mysql_result($result,0,3));
		$footer = cOut(mysql_result($result,0,4));
		$ext = cOut(mysql_result($result,0,5));
		$typeID = cOut(mysql_result($result,0,6));
		$groupBy = cOut(mysql_result($result,0,7));
		$sortBy = cOut(mysql_result($result,0,8));
		$cleanup = cOut(mysql_result($result,0,9));
		$dateFormat = cOut(mysql_result($result,0,10));
	}//end if
	
	appInstructions(0, $helpDoc, $whereAmI, $helpText);	?>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function togThis(doTog, doLink){
		if(document.getElementById(doTog).style.display == 'none'){
			document.getElementById(doTog).style.display = 'block';
			document.getElementById(doLink).innerHTML = '<?php echo $hc_lang_tools['HideVariables'];?>';
		} else {
			document.getElementById(doTog).style.display = 'none';
			document.getElementById(doLink).innerHTML = '<?php echo $hc_lang_tools['ShowVariables'];?>';
		}//end if
	}//end togThis()
	
	function chkFrm(){
	dirty = 0;
	warn = "<?php echo $hc_lang_tools['Valid19'];?>";
		
		if(document.frmExpTemp.name.value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_tools['Valid20'];?>';
		}//end if

		if(document.frmExpTemp.ext.value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_tools['Valid21'];?>';
		}//end if
		
		if(document.frmExpTemp.content.value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_tools['Valid22'];?>';
		}//end if

		if(dirty > 0){
			alert(warn + '\n\n<?php echo $hc_lang_tools['Valid08'];?>');
			return false;
		} else {
			return true;
		}//end if
	}//end chkFrm()
	//-->
	</script>
	<br />
	<form name="frmExpTemp" id="frmExpTemp" method="post" action="<?php echo CalAdminRoot . "/components/TemplatesEditAction.php";?>" onsubmit="return chkFrm();">
	<input type="hidden" name="tID" id="tID" value="<?php echo $tID;?>" />
	<input type="hidden" name="typeID" id="typeID" value="<?php echo $typeID;?>" />
	<fieldset>
		<legend><?php echo $hc_lang_tools['TempSettings'];?></legend>
		<div class="frmOpt">
			<label for="name"><?php echo $hc_lang_tools['Name'];?></label>
			<input name="name" id="name" value="<?php echo $name;?>" type="text" maxlength="255" size="50" />
		</div>
		<div class="frmOpt">
			<label for="ext"><?php echo $hc_lang_tools['Extension'];?></label>
			<input name="ext" id="ext" value="<?php echo $ext;?>" type="text" maxlength="15" size="5" />
		</div>
		<div class="frmOpt">
			<label for="sort"><?php echo $hc_lang_tools['Sort'];?></label>
			<select name="sort" id="sort">
				<option <?php if($sortBy == 0){echo "selected=\"selected\"";}?> value="0"><?php echo $hc_lang_tools['OptSort0'];?></option>
				<option <?php if($sortBy == 1){echo "selected=\"selected\"";}?> value="1"><?php echo $hc_lang_tools['OptSort1'];?></option>
				<option <?php if($sortBy == 2){echo "selected=\"selected\"";}?> value="2"><?php echo $hc_lang_tools['OptSort2'];?></option>
			</select>
			&nbsp;<?php appInstructionsIcon($hc_lang_tools['Tip01A'], $hc_lang_tools['Tip01B']);?>
		</div>
		<div class="frmOpt">
			<label for="group"><?php echo $hc_lang_tools['GroupBy'];?></label>
			<select name="group" id="group">
				<option <?php if($groupBy == 0){echo "selected=\"selected\"";}?> value="0"><?php echo $hc_lang_tools['OptCategory'];?></option>
				<option <?php if($groupBy == 1){echo "selected=\"selected\"";}?> value="1"><?php echo $hc_lang_tools['OptEvent'];?></option>
				<option <?php if($groupBy == 2){echo "selected=\"selected\"";}?> value="2"><?php echo $hc_lang_tools['OptEventS'];?></option>
				<option <?php if($groupBy == 3){echo "selected=\"selected\"";}?> value="3"><?php echo $hc_lang_tools['OptEventSC'];?></option>
			</select>
			&nbsp;<?php appInstructionsIcon($hc_lang_tools['Tip02A'], $hc_lang_tools['Tip02B']);?>
		</div>
		<div class="frmOpt">
			<label for="dateFormat"><?php echo $hc_lang_tools['DateFormat'];?></label>
			<select name="dateFormat" id="dateFormat">
				<option <?php if($dateFormat == 0){echo "selected=\"selected\"";}?> value="0"><?php echo $hc_lang_tools['Date0'] . ' (' . stampToDate(date("Y-m-d"),$hc_cfg14) . ')';?></option>
				<option <?php if($dateFormat == 1){echo "selected=\"selected\"";}?> value="1"><?php echo $hc_lang_tools['Date1'] . ' (' . stampToDate(date("Y-m-d"),$hc_cfg24) . ')';?></option>
				<option <?php if($dateFormat == 2){echo "selected=\"selected\"";}?> value="2"><?php echo $hc_lang_tools['Date2'] . ' (' . stampToDateAP(date("Y-m-d"),1) . ')';?></option>
			</select>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_tools['TempVarsOper'];?></legend>
		<div class="frmOpt">
			<label><?php echo $hc_lang_tools['Variables'];?></label>
			<a id="varLink" href="javascript:;" onclick="togThis('tempVars', 'varLink');" class="main"><?php echo $hc_lang_tools['ShowVariables'];?></a>
		</div>
		<div class="frmOpt" id="tempVars" style="display:none;">
			<div class="varKey">
			<?php 
				echo '<div class="varKeyHeader">' . $hc_lang_tools['VarLabelE'] . '</div>';
				echo '<div class="varKeyVariable">';
				appInstructionsIcon($hc_lang_tools['ExpVar'], "&lt;b&gt;" . $hc_lang_tools['Variable'] . "&lt;/b&gt; [event_id]&lt;br /&gt;" . $hc_lang_tools['Tip03B']);
				echo ' [event_id]</div>';
				echo '<div class="varKeyVariable">';
				appInstructionsIcon($hc_lang_tools['ExpVar'], "&lt;b&gt;" . $hc_lang_tools['Variable'] . "&lt;/b&gt; [event_title]&lt;br /&gt;" . $hc_lang_tools['Tip04B']);
				echo ' [event_title]</div>';
				echo '<div class="varKeyVariable">';
				appInstructionsIcon($hc_lang_tools['ExpVar'], "&lt;b&gt;" . $hc_lang_tools['Variable'] . "&lt;/b&gt; [event_desc]&lt;br /&gt;" . $hc_lang_tools['Tip05B']);
				echo ' [event_desc]</div>';
				echo '<div class="varKeyVariable">';
				appInstructionsIcon($hc_lang_tools['ExpVar'], "&lt;b&gt;" . $hc_lang_tools['Variable'] . "&lt;/b&gt; [event_date]&lt;br /&gt;" . $hc_lang_tools['Tip06B']);
				echo ' [event_date]</div>';
				echo '<div class="varKeyVariable">';
				appInstructionsIcon($hc_lang_tools['ExpVar'], "&lt;b&gt;" . $hc_lang_tools['Variable'] . "&lt;/b&gt; [event_time_start]&lt;br /&gt;" . $hc_lang_tools['Tip07B']);
				echo ' [event_time_start]</div>';
				echo '<div class="varKeyVariable">';
				appInstructionsIcon($hc_lang_tools['ExpVar'], "&lt;b&gt;" . $hc_lang_tools['Variable'] . "&lt;/b&gt; [event_time_end]&lt;br /&gt;" . $hc_lang_tools['Tip08B']);
				echo ' [event_time_end]</div>';
				echo '<div class="varKeyVariable">';
				appInstructionsIcon($hc_lang_tools['ExpVar'], "&lt;b&gt;" . $hc_lang_tools['Variable'] . "&lt;/b&gt; [event_cost]&lt;br /&gt;" . $hc_lang_tools['Tip09B']);
				echo ' [event_cost]</div>';
				echo '<div class="varKeyVariable">';
				appInstructionsIcon($hc_lang_tools['ExpVar'], "&lt;b&gt;" . $hc_lang_tools['Variable'] . "&lt;/b&gt; [event_billboard]&lt;br /&gt;" . $hc_lang_tools['Tip10B']);
				echo ' [event_billboard]</div>';
				
				echo '<div class="varKeyHeader">' . $hc_lang_tools['VarLabelC'] . '</div>';
				echo '<div class="varKeyVariable">';
				appInstructionsIcon($hc_lang_tools['ExpVar'], "&lt;b&gt;" . $hc_lang_tools['Variable'] . "&lt;/b&gt; [contact_name]&lt;br /&gt;" . $hc_lang_tools['Tip12B']);
				echo ' [contact_name]</div>';
				echo '<div class="varKeyVariable">';
				appInstructionsIcon($hc_lang_tools['ExpVar'], "&lt;b&gt;" . $hc_lang_tools['Variable'] . "&lt;/b&gt; [contact_email]&lt;br /&gt;" . $hc_lang_tools['Tip13B']);
				echo ' [contact_email]</div>';
				echo '<div class="varKeyVariable">';
				appInstructionsIcon($hc_lang_tools['ExpVar'], "&lt;b&gt;" . $hc_lang_tools['Variable'] . "&lt;/b&gt; [contact_phone]&lt;br /&gt;" . $hc_lang_tools['Tip14B']);
				echo ' [contact_phone]</div>';
				echo '<div class="varKeyVariable">';
				appInstructionsIcon($hc_lang_tools['ExpVar'], "&lt;b&gt;" . $hc_lang_tools['Variable'] . "&lt;/b&gt; [contact_url]&lt;br /&gt;" . $hc_lang_tools['Tip30B']);
				echo ' [contact_url]</div>';
				
				echo '<div class="varKeyHeader">' . $hc_lang_tools['VarLabelR'] . '</div>';
				echo '<div class="varKeyVariable">';
				appInstructionsIcon($hc_lang_tools['ExpVar'], "&lt;b&gt;" . $hc_lang_tools['Variable'] . "&lt;/b&gt; [space]&lt;br /&gt;" . $hc_lang_tools['Tip15B']);
				echo ' [space]</div>';
				
				echo '<div class="varKeyHeader">' . $hc_lang_tools['VarLabelL'] . '</div>';
				echo '<div class="varKeyVariable">';
				appInstructionsIcon($hc_lang_tools['ExpVar'], "&lt;b&gt;" . $hc_lang_tools['Variable'] . "&lt;/b&gt; [loc_name]&lt;br /&gt;" . $hc_lang_tools['Tip18B']);
				echo ' [loc_name]</div>';
				echo '<div class="varKeyVariable">';
				appInstructionsIcon($hc_lang_tools['ExpVar'], "&lt;b&gt;" . $hc_lang_tools['Variable'] . "&lt;/b&gt; [loc_address]&lt;br /&gt;" . $hc_lang_tools['Tip19B']);
				echo ' [loc_address]</div>';
				echo '<div class="varKeyVariable">';
				appInstructionsIcon($hc_lang_tools['ExpVar'], "&lt;b&gt;" . $hc_lang_tools['Variable'] . "&lt;/b&gt; [loc_address2]&lt;br /&gt;" . $hc_lang_tools['Tip20B']);
				echo ' [loc_address2]</div>';
				echo '<div class="varKeyVariable">';
				appInstructionsIcon($hc_lang_tools['ExpVar'], "&lt;b&gt;" . $hc_lang_tools['Variable'] . "&lt;/b&gt; [loc_city]&lt;br /&gt;" . $hc_lang_tools['Tip21B']);
				echo ' [loc_city]</div>';
				echo '<div class="varKeyVariable">';
				appInstructionsIcon($hc_lang_tools['ExpVar'], "&lt;b&gt;" . $hc_lang_tools['Variable'] . "&lt;/b&gt; [loc_region]&lt;br /&gt;" . $hc_lang_tools['Tip22B']);
				echo ' [loc_region]</div>';
				echo '<div class="varKeyVariable">';
				appInstructionsIcon($hc_lang_tools['ExpVar'], "&lt;b&gt;" . $hc_lang_tools['Variable'] . "&lt;/b&gt; [loc_postal]&lt;br /&gt;" . $hc_lang_tools['Tip23B']);
				echo ' [loc_postal]</div>';
				echo '<div class="varKeyVariable">';
				appInstructionsIcon($hc_lang_tools['ExpVar'], "&lt;b&gt;" . $hc_lang_tools['Variable'] . "&lt;/b&gt; [loc_country]&lt;br /&gt;" . $hc_lang_tools['Tip24B']);
				echo ' [loc_country]</div>';
				echo '<div class="varKeyVariable">';
				appInstructionsIcon($hc_lang_tools['ExpVar'], "&lt;b&gt;" . $hc_lang_tools['Variable'] . "&lt;/b&gt; [loc_url]&lt;br /&gt;" . $hc_lang_tools['Tip31B']);
				echo ' [loc_url]</div>';
				
				echo '<div class="varKeyHeader">' . $hc_lang_tools['VarLabelS'] . '</div>';
				echo '<div class="varKeyVariable">';
				appInstructionsIcon($hc_lang_tools['ExpVar'], "&lt;b&gt;" . $hc_lang_tools['Variable'] . "&lt;/b&gt; [date_series]&lt;br /&gt;" . $hc_lang_tools['Tip26B']);
				echo ' [date_series]</div>';
				echo '<div class="varKeyVariable">';
				appInstructionsIcon($hc_lang_tools['ExpVar'], "&lt;b&gt;" . $hc_lang_tools['Variable'] . "&lt;/b&gt; [date_unique]&lt;br /&gt;" . $hc_lang_tools['Tip27B']);
				echo ' [date_unique]</div>';
				echo '<div class="varKeyVariable">';
				appInstructionsIcon($hc_lang_tools['ExpVar'], "&lt;b&gt;" . $hc_lang_tools['Variable'] . "&lt;/b&gt; [category_unique]&lt;br /&gt;" . $hc_lang_tools['Tip28B']);
				echo ' [category_unique]</div>';
				echo '<div class="varKeyVariable">';
				appInstructionsIcon($hc_lang_tools['ExpVar'], "&lt;b&gt;" . $hc_lang_tools['Variable'] . "&lt;/b&gt; [desc_notags]&lt;br /&gt;" . $hc_lang_tools['Tip29B']);
				echo ' [desc_notags]</div>';
				
				
				echo '<div class="varKeyHeader">' . $hc_lang_tools['VarLabelHF'] . '</div>';
				echo '<div class="varKeyVariable">';
				appInstructionsIcon($hc_lang_tools['ExpVar'], "&lt;b&gt;" . $hc_lang_tools['Variable'] . "&lt;/b&gt; [cal_url]&lt;br /&gt;" . $hc_lang_tools['Tip25B']);
				echo ' [cal_url]</div>';?>
				<div style="clear:both;"></div>
			</div>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_tools['TempContent'];?></legend>
		<div class="frmOpt">
			<label for="header"><?php echo $hc_lang_tools['Header'] . '<br /> (' . $hc_lang_tools['Optional'] . ')';?></label>
			<textarea name="header" id="header" rows="10" cols="50" style="width:80%;"><?php echo $header;?></textarea>
		</div>
		<div class="frmReq">
			<label for="content"><?php echo $hc_lang_tools['Content'];?></label>
			<textarea name="data" id="data" rows="10" cols="50" style="width:80%;"><?php echo $content;?></textarea>
		</div>
		<div class="frmOpt">
			<label for="footer"><?php echo $hc_lang_tools['Footer'] . '<br />(' . $hc_lang_tools['Optional'] . ')';?></label>
			<textarea name="footer" id="footer" rows="10" cols="50" style="width:80%;"><?php echo $footer;?></textarea>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_tools['CleanUp'];?></legend>
		<div class="frmOpt">
			<label>&nbsp;</label>
			<?php echo $hc_lang_tools['CleanUpNotice'];?>
		</div>
		<div class="frmOpt">
			<label for="cleanup"><?php echo $hc_lang_tools['CleanList'] . '<br /> (' . $hc_lang_tools['Optional'] . ')';?></label>
			<textarea name="cleanup" id="cleanup" rows="10" cols="50" style="width:80%;"><?php echo $cleanup;?></textarea>
		</div>
	</fieldset>
	<br />
	<input name="submit" id="submit" type="submit" value=" <?php echo $hc_lang_tools['SaveTemplate'];?> " class="button" />
	<input onclick="window.location.href='<?php echo CalAdminRoot;?>/index.php?com=exporttmplts';return false;" name="cancel" id="cancel" type="button" value="    <?php echo $hc_lang_tools['Cancel'];?>    " class="button" />
	</form>