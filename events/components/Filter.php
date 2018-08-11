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
	include($hc_langPath . $_SESSION['LangSet'] . '/public/filter.php');
	$browseCatIDs = '0';

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
	
	echo '<br />';
	echo (isset($_SESSION['hc_favCat']) || isset($_SESSION['hc_favCity'])) ? $hc_lang_filter['NoticeSet'] : $hc_lang_filter['Notice'];
	echo '<br /><br />' . $hc_lang_filter['InstructSet'] . '<br />' . $hc_lang_filter['InstructClear'];?>
	
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
	<form name="frmEventFilter" id="frmEventFilter" method="post" action="<?php echo CalRoot . '/components/FilterAction.php';?>" onsubmit="return chkFrm();">
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
		<?php 	$cnt = 1;
				$columns = 3;
				$colWidth = number_format((100 / $columns), 0);
				$colLimit = ceil(count($cities) / $columns);
				$actCities = (isset($_SESSION['hc_favCity'])) ? urldecode($_SESSION['hc_favCity']) : '';
				echo '<table cellpadding="0" cellspacing="0" border="0"><tr><td valign="top" width="' . $colWidth . '%">';
				foreach($cities as $val){
					if($cnt > $colLimit){
						echo '</td><td valign="top" width="' . $colWidth . '%">';
						$cnt = 1;
					}//end if
					
					if($val != ''){	
						echo '<label for="cityName_' . str_replace(" ","_",$val) . '" class="category">';
						echo '<input onclick="updateLink();" name="cityName[]" id="cityName_' . str_replace(" ","_",$val) . '" type="checkbox" value="' . $val . '" class="noBorderIE"';
						echo (strpos($actCities,$val) === false) ? '' : ' checked="checked"';
						echo ' />' . $val . '</label>';
					}//end if
					++$cnt;
				}//end foreach
				echo '</td></tr></table>';
			}//end if	?>
			</div>
		<br />
		<div class="frmOpt">
			<label><?php echo $hc_lang_filter['Categories'];?></label>
	<?php	$query = NULL;
			if(isset($_SESSION['hc_favCat'])){
				$query = "SELECT c.PkID, c.CategoryName, c.ParentID, c.CategoryName as Sort, c2.PkID as Selected
							FROM " . HC_TblPrefix . "categories c 
								LEFT JOIN " . HC_TblPrefix . "categories c2 ON (c.PkID = c2.PkID AND c.PkID IN (" . $_SESSION['hc_favCat'] . "))
								LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (c.PkID = ec.CategoryID)
							WHERE c.ParentID = 0 AND c.IsActive = 1
							GROUP BY c.PkID
							UNION 
							SELECT c.PkID, c.CategoryName, c.ParentID, c2.CategoryName as Sort, c3.PkID as Selected
							FROM " . HC_TblPrefix . "categories c 
								LEFT JOIN " . HC_TblPrefix . "categories c2 ON (c.ParentID = c2.PkID)
								LEFT JOIN " . HC_TblPrefix . "categories c3 ON (c.PkID = c3.PkID AND c.PkID IN (" . $_SESSION['hc_favCat'] . "))
								LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (c.PkID = ec.CategoryID)
							WHERE c.ParentID > 0 AND c.IsActive = 1
							GROUP BY c.PkID 
							ORDER BY Sort, ParentID, CategoryName";
			}//end if
			getCategories('frmEventFilter', 3, $query);	?>
		</div>
	</fieldset>
	<br />	
	<input name="submit" id="submit" type="submit" value="<?php echo $hc_lang_filter['SetFilter'];?>" class="button" />&nbsp;
	<input onclick="window.location.href='<?php echo CalRoot;?>/components/FilterAction.php?clear=1';return false;" name="clear" id="clear" type="button" value="<?php echo $hc_lang_filter['ClearFilter'];?>" class="button" />
	</form>