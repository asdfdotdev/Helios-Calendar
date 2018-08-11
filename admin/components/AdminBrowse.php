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
	include($hc_langPath . $_SESSION['LangSet'] . '/admin/admin.php');	?>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function doDelete(dID){
		if(confirm('<?php echo $hc_lang_admin['Valid07'] . "\\n\\n          " . $hc_lang_admin['Valid08'] . "\\n          " . $hc_lang_admin['Valid09'];?>')){
			document.location.href = '<?php echo CalAdminRoot . "/components/AdminEditAction.php";?>?dID=' + dID;
		}//end if
	}//end doDelete
	//-->
	</script>
<?php
	if (isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1, $hc_lang_admin['Feed03']);
				break;
				
			case "2" :
				feedback(3, $hc_lang_admin['Feed04']);
				break;
				
			case "3" :
				feedback(1, $hc_lang_admin['Feed05']);
				break;
				
			case "4" :
				feedback(1, $hc_lang_admin['Feed06']);
				break;
		}//end switch
	}//end if
	
	appInstructions(0, "Editing_Admin_Users", $hc_lang_admin['TitleBrowseA'], $hc_lang_admin['InstructBrowseA']);
	
	$result = doQuery("SELECT * FROM " . HC_TblPrefix  . "admin WHERE SuperAdmin = 0 AND IsActive = 1 ORDER BY LastName, FirstName");
	if(hasRows($result)){	?>
		<div class="adminList">
			<div class="adminName"><?php echo $hc_lang_admin['Name'];?></div>
			<div class="adminEmail"><?php echo $hc_lang_admin['EmailLabel'];?></div>
			&nbsp;
		</div>
<?php 	$cnt = 0;
		while($row = mysql_fetch_row($result)){	?>
			<div class="adminName<?php if($cnt % 2 == 1){echo "HL";}?>"><?php echo $row[1] . " " . $row[2];?></div>
			<div class="adminEmail<?php if($cnt % 2 == 1){echo "HL";}?>"><?php echo $row[3];?></div>
			<div class="adminTools<?php if($cnt % 2 == 1){echo "HL";}?>"><a href="<?php echo CalAdminRoot;?>/index.php?com=adminedit&amp;uID=<?php echo $row[0];?>" class="main"><img src="<?php echo CalAdminRoot;?>/images/icons/iconEdit.png" width="15" height="15" alt="" border="0" style="vertical-align:middle;" /></a>&nbsp;<a href="javascript:;" onclick="doDelete('<?php echo $row[0];?>');return false;" class="main"><img src="<?php echo CalAdminRoot;?>/images/icons/iconDelete.png" width="15" height="15" alt="" border="0" style="vertical-align:middle;" /></a></div>
	<?php 	$cnt++;
		}//end while
	} else {	?>
		<br />
		There are currently no administrators available.
<?php
	}//end if	?>