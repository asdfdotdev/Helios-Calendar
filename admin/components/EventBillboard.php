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
	include($hc_langPath . $_SESSION['LangSet'] . '/admin/manage.php');	?>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function doDelete(eID){
		if(confirm('<?php echo $hc_lang_manage['Valid01'] . "\\n\\n          " . $hc_lang_manage['Valid02'] . "\\n          " . $hc_lang_manage['Valid03'];?>')){
			window.location.href='<?php echo CalAdminRoot . "/components/EventBillboardAction.php";?>?eID=' + eID;
		}//end if
	}//end doDelete
	//-->
	</script>
<?php
	if (isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1, $hc_lang_manage['Feed01']);
				break;
				
		}//end switch
	}//end if
	
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsBillboard = 1 AND StartDate >= '" . date("Y-m-d") . "' ORDER BY StartDate, Views DESC");
	if(hasRows($result)){
		appInstructions(0, "Billboard_Events", $hc_lang_manage['TitleBillboard'], $hc_lang_manage['InstructBillboard']);	?>
		<div class="billboardList">
			<div class="billboardListTitle"><b><?php echo $hc_lang_manage['Title'];?></b></div>
			<div class="billboardListDate"><b><?php echo $hc_lang_manage['Date'];?></b></div>
			<div class="billboardListViews"><b><?php echo $hc_lang_manage['Views'];?></b></div>
			<div class="billboardListTools">&nbsp;</div>&nbsp;
		</div>
<?php 	$cnt = 0;
		while($row = mysql_fetch_row($result)){	?>
			<div class="billboardListTitle<?php if($cnt % 2 == 1){echo "HL";}?>"><a href="<?php echo CalAdminRoot;?>/index.php?com=eventedit&amp;eID=<?php echo $row[0];?>" class="main"><?php echo cOut($row[1]);?></a></div>
			<div class="billboardListDate<?php if($cnt % 2 == 1){echo "HL";}?>"><?php echo stampToDate($row[9], $hc_popDateFormat);?></div>
			<div class="billboardListViews<?php if($cnt % 2 == 1){echo "HL";}?>"><?php echo cOut($row[28]);?></div>
			<div class="billboardListTools<?php if($cnt % 2 == 1){echo "HL";}?>">
				<a href="<?php echo CalAdminRoot;?>/index.php?com=eventedit&amp;eID=<?php echo $row[0];?>" class="main"><img src="<?php echo CalAdminRoot;?>/images/icons/iconEdit.png" width="15" height="15" alt="" border="0" style="vertical-align:middle;" /></a>&nbsp;<a href="javascript:;" onclick="doDelete('<?php echo $row[0];?>');return false;" class="main"><img src="<?php echo CalAdminRoot;?>/images/icons/iconDelete.png" width="15" height="15" alt="" border="0" style="vertical-align:middle;" /></a>
			</div>
	<?php 	$cnt++;
		}//end while	?>
<?php
	} else {
		echo "<br />";
		echo "<b>" . $hc_lang_manage['NoBillboard'] . "</b>";
	}//end if	?>