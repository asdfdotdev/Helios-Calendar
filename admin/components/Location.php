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
	include($hc_langPath . $_SESSION['LangSet'] . '/admin/locations.php');
	
	$resDiff = 6;
	$resLimit = 25;
	$resOffset = 0;
	
	if(isset($_GET['p']) && is_numeric($_GET['p'])){
		$resOffset = abs($_GET['p']);
	}//end if
	
	if(isset($_GET['a']) && is_numeric($_GET['a']) && abs($_GET['a']) <= 100 && $_GET['a'] % 25 == 0){
		$resLimit = abs($_GET['a']);
	}//end if
	
	if (isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1, $hc_lang_locations['Feed01']);
				break;
				
			case "2" :
				feedback(1, $hc_lang_locations['Feed02']);
				break;
				
			case "3" :
				feedback(1, $hc_lang_locations['Feed03']);
				break;
				
			case "4" :
				feedback(2, $hc_lang_locations['Feed04']);
				break;
			
			case "5" :
				feedback(2, $hc_lang_locations['Feed05']);
				break;
				
			case "6" :
				feedback(1, $hc_lang_locations['Feed06']);
				break;
				
			case "7" :
				feedback(2, $hc_lang_locations['Feed07']);
				break;
			
			case "8" :
				feedback(1, $hc_lang_locations['Feed08']);
				break;
				
			case "9" :
				feedback(1, $hc_lang_locations['Feed09']);
				break;
				
			case "10" :
				feedback(2, $hc_lang_locations['Feed10']);
				break;
				
			case "11" :
				feedback(2, $hc_lang_locations['Feed11']);
				break;
			
			case "12" :
				feedback(1, $hc_lang_locations['Feed12']);
				break;
		}//end switch
	}//end if
	
	appInstructions(0, "Editing_Locations", $hc_lang_locations['TitleBrowse'], $hc_lang_locations['InstructBrowse']);
	$resultC = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "locations WHERE IsActive = 1");
	$pages = ceil(mysql_result($resultC,0,0)/$resLimit);
	if($pages <= $resOffset && $pages > 0){$resOffset = ($pages - 1);}
	
	$result = doQuery("SELECT * FROM " . HC_TblPrefix  . "locations WHERE IsActive = 1 ORDER BY IsPublic, Name LIMIT " . $resLimit . " OFFSET " . ($resOffset * $resLimit));
	if(hasRows($result)){
		echo "<div style=\"clear:both;padding-top:10px;\"><b>" . $hc_lang_locations['ResPer'] . "</b>&nbsp;";
		for($x = 25;$x <= 100;$x = $x + 25){
			if($x > 25){echo "&nbsp;|&nbsp;";}
			if($x != $resLimit){
				echo "<a href=\"" . CalAdminRoot . "/index.php?com=location&p=" . $resOffset . "&a=" . $x . "\" class=\"eventMain\">" . $x . "</a>";
			} else {
				echo "<b>" . $x . "</b>";
			}//end if
		}//end for
		
		echo "</div>";
		echo "<div style=\"clear:both;padding-top:10px;\"><b>" . $hc_lang_locations['Page'] . "</b>&nbsp;";
		
		$x = $resOffset - $resDiff;
		$cnt = 0;
		
		if($x < 0){$x = 0;}
		if($resOffset > $resDiff){
			echo "<a href=\"" . CalAdminRoot . "/index.php?com=location&p=0&a=" . $resLimit . "\" class=\"eventMain\">1</a>&nbsp;...&nbsp;";
		}//end if
		
		while($cnt <= ($resDiff * 2) && $x < $pages){
			if($cnt > 0){echo " | ";}
			if($resOffset != $x){
				echo "<a href=\"" . CalAdminRoot . "/index.php?com=location&p=" . $x . "&a=" . $resLimit . "\" class=\"eventMain\">" . ($x + 1) . "</a>";
			} else {
				echo "<b>" . ($x + 1) . "</b>";
			}//end if
			$x++;
			$cnt++;
		}//end while
		
		if($resOffset < ($pages - ($resDiff + 1))){
			echo "&nbsp;...&nbsp;<a href=\"" . CalAdminRoot . "/index.php?com=location&p=" . ($pages - 1) . "&a=" . $resLimit . "\" class=\"eventMain\">" . $pages . "</a>";
		}//end if
		
		echo "</div><br />";	?>
		<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Checkboxes.js"></script>
		<script language="JavaScript" type="text/JavaScript">
		//<!--
		function doDelete(dID){
			if(confirm('<?php echo $hc_lang_locations['Valid01'] . "\\n\\n          " . $hc_lang_locations['Valid02'] . "\\n          " . $hc_lang_locations['Valid03'];?>')){
				document.location.href = '<?php echo CalAdminRoot . "/components/LocationEditAction.php";?>?dID=' + dID;
			}//end if
		}//end doDelete
		//-->
		</script>
		<div class="locList">
			<div class="locName"><?php echo $hc_lang_locations['NameLabel'];?></div>
			<div class="locStatus"><?php echo $hc_lang_locations['StatusLabel'];?></div>
			&nbsp;
		</div>
<?php 	$cnt = 0;
		while($row = mysql_fetch_row($result)){	?>
			<div class="locName<?php if($cnt % 2 == 1){echo "HL";}?>"><?php echo $row[1];?></div>
			<div class="locStatus<?php if($cnt % 2 == 1){echo "HL";}?>">
		<?php 	if($row[12] == 1){
					echo $hc_lang_locations['Public'];
				} else {
					echo $hc_lang_locations['AdminOnly'];
				}//end if	?>
			</div>
			<div class="locTools<?php if($cnt % 2 == 1){echo "HL";}?>">
				<a href="<?php echo CalAdminRoot;?>/index.php?com=addlocation&amp;lID=<?php echo $row[0];?>" class="main"><img src="<?php echo CalAdminRoot;?>/images/icons/iconEdit.png" width="15" height="15" alt="" border="0" style="vertical-align:middle;" /></a>&nbsp;
				<a href="javascript:;" onclick="doDelete('<?php echo $row[0];?>');return false;" class="main"><img src="<?php echo CalAdminRoot;?>/images/icons/iconDelete.png" width="15" height="15" alt="" border="0" style="vertical-align:middle;" /></a>&nbsp;
			</div>
	<?php 	$cnt++;
		}//end while
	} else {
		echo "<br />" . $hc_lang_locations['NoLoc'];
	}//end if	?>