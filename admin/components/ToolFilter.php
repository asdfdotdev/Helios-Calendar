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
	include($hc_langPath . $_SESSION['LangSet'] . '/admin/tools.php');
	
	appInstructions(0, "Filter_Link", $hc_lang_tools['TitleFilter'], $hc_lang_tools['InstructFilter']);	?>
	<br />
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Checkboxes.js"></script>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
		function updateLink(){
			var catStr = "";
			var cityStr = "";
			var doBoth = false;
			if(!validateCheckArray('frmToolLink','catID[]',1) > 0){
				catStr = 'l=' + checkUpdateString('frmToolLink', 'catID[]');
				doBoth = true;
			}//end if
			if(!validateCheckArray('frmToolLink','cityName[]',1) > 0){
				cityStr = doBoth == true ? '&' : '';
				cityStr += 'c=' + checkUpdateString('frmToolLink', 'cityName[]');
			}//end if
			document.frmToolLink.filterLink.value = '<?php echo CalRoot;?>/?' + catStr + cityStr;
		}//end updateLink()
	//-->
	</script>
	<form name="frmToolLink" id="frmToolLink" method="post" action="" onsubmit="return false;">
	<fieldset>
		<legend><?php echo $hc_lang_tools['FilterLabel'];?></legend>
		<div class="frmOpt">
			<label><?php echo $hc_lang_tools['Cities'];?></label>
			<table cellpadding="0" cellspacing="0" border="0"><tr>
	<?php 	$cities = getCities();
			$cnt = 0;
			$favCities = array();
			foreach($cities as $val){
				if(($cnt % 2 == 0) && ($cnt > 0) ){echo "</tr><tr>";}//end if
				if($val != ''){	
					$findMe = "'" . $val . "'";	?>
					<td><label for="cityName_<?php echo str_replace(" ","_",$val);?>" class="category"><input onclick="updateLink();" name="cityName[]" id="cityName_<?php echo str_replace(" ","_",$val);?>" type="checkbox" value="<?php echo urlencode($val);?>" class="noBorderIE" /><?php echo $val;?></label></td>
	<?php 		}//end if
				$cnt++;
			}//end foreach	?>
			</tr></table>
		</div>
		<div class="frmOpt">
			<label><?php echo $hc_lang_tools['Categories'];?></label>
			<table cellpadding="0" cellspacing="0" border="0">
			<tr>
		<?php 	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "categories WHERE IsActive = 1 AND PkID > 0 ORDER BY CategoryName");
				$cnt = 0;
				
				while($row = mysql_fetch_row($result)){
					if(($cnt % 2 == 0) && ($cnt > 0) ){echo "</tr><tr>";}	?>
					<td><label for="catID_<?php echo $row[0];?>" class="category"><input onclick="updateLink();" name="catID[]" id="catID_<?php echo $row[0];?>" type="checkbox" value="<?php echo $row[0];?>" class="noBorderIE" /><?php echo cOut($row[1]);?></label></td>
			<?php 	$cnt = $cnt + 1;
				}//end while?>
			</tr>
			</table>
		</div>
		<div class="frmReq">
			<label for="filterLink"><?php echo $hc_lang_tools['Link'];?></label>
			<input name="filterLink" id="filterLink" type="text" size="65" maxlength="200" value="<?php echo CalRoot;?>/" />
		</div>
	</fieldset>
	<br />
	<input name="reset" id="reset" type="reset" value=" <?php echo $hc_lang_tools['ResetLink'];?> " class="button" />
	</form>
