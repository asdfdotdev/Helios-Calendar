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
	$browseCatIDs = "0";
	
	include($hc_langPath . $_SESSION['LangSet'] . '/public/filter.php');
	
	if(isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1,$hc_lang_filter['Feed01']);
				break;
			case "2" :
				feedback(1,$hc_lang_filter['Feed02']);
				break;
		}//end switch
	}//end if
	
	echo "<br />";
	if(isset($_SESSION["hc_favCat"]) || isset($_SESSION["hc_favCity"])){
		echo $hc_lang_filter['NoticeSet'];
	} else {
		echo $hc_lang_filter['Notice'];
	}//end if	
	echo "<br /><br />" . $hc_lang_filter['InstructSet'] . "<br />" . $hc_lang_filter['InstructClear'];?>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Checkboxes.js"></script>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function chkFrm() {
	dirty = 0;
	warn = "<?php echo $hc_lang_filter['Valid01'];?>\n";
		
		if(validateCheckArray('frmEventFilter','catID[]',1) > 0 && validateCheckArray('frmEventFilter','cityName[]',1) > 0){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_filter['Valid02'];?>';
		}//end if
		
		if(dirty > 0){
			alert(warn + '\n\n<?php echo $hc_lang_filter['Valid03'];?>');
			return false;
		} else {
			return true;
		}//end if
		
	}//end chkFrm
	//-->
	</script>
	<br /><br />
	<form name="frmEventFilter" id="frmEventFilter" method="post" action="<?php echo CalRoot;?>/components/FilterAction.php" onsubmit="return chkFrm();">
	<fieldset>
		<legend><?php echo $hc_lang_filter['FilterSettings'];?></legend>
		<div class="frmOpt">
			<label>&nbsp;</label>
			<label class="radioWide"><input name="cookieme" id="cookieme" type="checkbox" <?php if(isset($_COOKIE["hc_favCat"]) || isset($_COOKIE['hc_favCity'])){echo "checked=\"checked\"";}?> class="noBorderIE" />&nbsp;<?php echo $hc_lang_filter['Remember'];?></label>
		</div>
		<br />
	<?php	$cities = getCities();
			if(count($cities) > 0){	?>
				<br /><div class="frmOpt">
				<label><?php echo $hc_lang_filter['Cities'];?></label>
				<table cellpadding="0" cellspacing="0" border="0"><tr>
		<?php	$cnt = 0;
				$favCities = array();
				if(isset($_SESSION['hc_favCity'])){$favCities = explode(',', $_SESSION['hc_favCity']);}//end if
				foreach($cities as $val){
					if(($cnt % 2 == 0) && ($cnt > 0) ){echo "</tr><tr>";}//end if
					if($val != ''){	
						$findMe = "'" . $val . "'";	?>
						<td><label for="cityName_<?php echo $val;?>" class="category"><input <?php if(in_array($findMe, $favCities)){echo "checked=\"checked\"";}//end if?> name="cityName[]" id="cityName_<?php echo $val;?>" type="checkbox" value="<?php echo $val;?>" class="noBorderIE" /><?php echo $val;?></label></td>
		<?php		}//end if
					$cnt++;
				}//end foreach	?>
				</tr></table>
				</div>
		<?php	
			}//end if	?>
		<br />
		<div class="frmOpt">
			<label><?php echo $hc_lang_filter['Categories'];?></label>
	<?php	if(isset($_SESSION["hc_favCat"])){$browseCatIDs = $_SESSION["hc_favCat"];}//end if
			$query = "SELECT a.*, b.PkID as Selected
						FROM " . HC_TblPrefix . "categories a
							LEFT JOIN " . HC_TblPrefix . "categories b ON (a.PkID = b.PkID AND b.PkID IN (" . cIn($browseCatIDs) . ")) 
						WHERE a.IsActive = 1
						ORDER BY CategoryName";
			getCategories('frmEventFilter', 2, $query);	?>
		</div>
	</fieldset>
	<br />	
	<input name="submit" id="submit" type="submit" value="<?php echo $hc_lang_filter['SetFilter'];?>" class="button" />&nbsp;
	<input onclick="window.location.href='<?php echo CalRoot;?>/components/FilterAction.php?clear=1';return false;" name="clear" id="clear" type="button" value="<?php echo $hc_lang_filter['ClearFilter'];?>" class="button" />
	</form>