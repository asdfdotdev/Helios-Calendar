<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(!defined('hcAdmin')){header("HTTP/1.1 403 No Direct Access");exit();}

	include(HCLANG.'/admin/locations.php');

	$hc_Side[] = array(CalRoot . '/index.php?com=location','map.png',$hc_lang_locations['LinkMap'],1);
	
	if(!isset($_POST['locName']) && !isset($_GET['l'])){
		appInstructions(0, "Merging_Locations", $hc_lang_locations['TitleMerge'], $hc_lang_locations['InstructMerge1']);
		
		echo '
		<form name="frmLocationSearch" id="frmLocationSearch" method="post" action="'.AdminRoot.'/index.php?com=locsearch" onsubmit="return validate();">';
		set_form_token();
		echo '
		<fieldset>
			<legend>'.$hc_lang_locations['SearchLabel'].'</legend>
			<label for="locName">'.$hc_lang_locations['LocName'].'</label>
			<input name="locName" id="locName" type="text" size="35" maxlength="100" value="" required="required" />
		</fieldset>
		<input type="submit" name="submit" id="submit" value="'.$hc_lang_locations['Search'].'" />
		</form>
		<script>
		//<!--
		function validate(){
			var err = "";
			err +=reqField(document.getElementById("locName"),"error");
			err +=validMinLength(document.getElementById("locName"),4,"error");
			
			if(err != ""){
				alert("'.$hc_lang_locations['Valid19'].'");
				return false;
			} else {
				return true;
			}
		}
		//-->
		</script>';
	} else {
		$token = '';
		$token = ($token == '' && isset($_POST['token'])) ? cIn(strip_tags($_POST['token'])) : $token;
		$token = ($token == '' && isset($_GET['tkn'])) ? cIn(strip_tags($_GET['tkn'])) : $token;
		
		appInstructions(1, "Merging_Locations", $hc_lang_locations['TitleMerge'], $hc_lang_locations['InstructMerge2']);
	
		$locName = '';
		if(isset($_GET['l']))
			$locName = (isset($_GET['l'])) ? strip_tags(str_replace("'","\"",$_GET['l'])) : '';
		elseif(isset($_POST['locName']))
			$locName = (isset($_POST['locName'])) ? strip_tags(str_replace("'","\"",$_POST['locName'])) : '';
		
		
		
		$result = doQuery("SELECT PkID, Name, IsPublic, 
							(SELECT COUNT(PkID) FROM " . HC_TblPrefix. "events e WHERE e.StartDate >= '" . SYSDATE . "' AND e.LocID = l.PkID) AS EventCnt
						FROM " . HC_TblPrefix . "locations l
						WHERE IsActive = 1 AND
							(MATCH(Name,Address,Address2) AGAINST('".cIn($locName,0)."' IN BOOLEAN MODE) OR NAME LIKE('%" . cIn($locName) . "%'))
						ORDER BY IsPublic, Name");
		
		if(!hasRows($result) || $locName == '' || !check_form_token($token)){
			echo '
			<p>'.$hc_lang_locations['NoLoc'].'</p>
			<p><a href="'.AdminRoot.'/index.php?com=locsearch">'.$hc_lang_locations['NoLocLink'].'</a></p>';
		} else {
			echo '
			<form name="frmMergeLocation" id="frmMergeLocation" method="post" action="'.AdminRoot.'/index.php?com=location&amp;m=1" onsubmit="return validate();">';
			set_form_token();
			echo '
			<div class="catCtrl">
				[ <a href="javascript:;" onclick="checkAllArray(\'frmMergeLocation\',\'locID[]\');">'.$hc_lang_core['SelectAll'].'</a>
				&nbsp;|&nbsp; <a href="javascript:;" onclick="uncheckAllArray(\'frmMergeLocation\',\'locID[]\');">'.$hc_lang_core['DeselectAll'].'</a> ]
			</div>
			<ul class="data">
				<li class="row header uline">
					<div style="width:65%;">'.$hc_lang_locations['NameLabel'].'</div>
					<div style="width:15%;">'.$hc_lang_locations['StatusLabel'].'</div>
					<div class="number" style="width:10%;">'.$hc_lang_locations['Events'].'</div>
					<div style="width:10%;">&nbsp;</div>
				</li>';

			$cnt = 0;
			while($row = mysql_fetch_row($result)){
				$hl = ($cnt % 2 == 1) ? ' hl' : '';

				echo '
				<li class="row'.$hl.'">
					<div class="txt" style="width:65%;">'.cOut($row[1]).'</div>
					<div class="txt" style="width:15%;">'.(($row[2] == 1) ? $hc_lang_locations['Public'] : $hc_lang_locations['AdminOnly']).'</div>
					<div class="number" style="width:10%;">'.number_format(cOut($row[3]),0,'',',').'</div>
					<div class="tools" style="width:10%;">
						<a href="'.AdminRoot.'/index.php?com=addlocation&amp;lID='.$row[0].'" target="_blank"><img src="'.AdminRoot.'/img/icons/edit.png" width="16" height="16" alt="" /></a>
						<input name="locID[]" id="locID_'.$row[0].'" type="checkbox" value="'.$row[0].'" />
					</div>
				</li>';
				++$cnt;
			}
			echo '
			</ul>
			<div class="catCtrl">
				[ <a href="javascript:;" onclick="checkAllArray(\'frmMergeLocation\',\'locID[]\');">'.$hc_lang_core['SelectAll'].'</a>
				&nbsp;|&nbsp; <a href="javascript:;" onclick="uncheckAllArray(\'frmMergeLocation\',\'locID[]\');">'.$hc_lang_core['DeselectAll'].'</a> ]
			</div>
			<input name="submit" id="submit" type="submit" value="'.$hc_lang_locations['MergeLoc'].'" />
			</form>
			
			<script src="'.CalRoot.'/inc/javascript/validation.js"></script>
			<script>
			//<!--
			function validate(){
				if(validCheckArray("frmMergeLocation","locID[]",2,"error") != ""){
					alert("'.$hc_lang_locations['Valid04'].'");
					return false;
				}
				return true;
			}
			//-->
			</script>';
		}
	}
?>