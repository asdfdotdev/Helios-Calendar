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
	include($hc_langPath . $_SESSION['LangSet'] . '/admin/newsletter.php');	?>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function doDelete(dID){
		if(confirm('<?php echo $hc_lang_news['Valid08'] . "\\n\\n          " . $hc_lang_news['Valid09'] . "\\n          " . $hc_lang_news['Valid10'];?>')){
			document.location.href = '<?php echo CalAdminRoot . "/components/UserEditAction.php";?>?dID=' + dID;
		}//end if
	}//end doDelete
	//-->
	</script>
<?php
	if (isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1, $hc_lang_news['Feed05']);
				break;
		}//end switch
	}//end if
	
	appInstructions(0, "Editing_Recipients", $hc_lang_news['TitleBrowseR'], $hc_lang_news['InstructBrowseR']);
	
	if(isset($_POST['search'])){
		$query = "	SELECT *
				FROM " . HC_TblPrefix . "users
				WHERE IsRegistered = 1";
		
		if(isset($_POST['name'])){
			$query = $query . " AND LastName LIKE('%" . cIn($_POST['name']) . "%')";
		}//end if
		
		if(isset($_POST['email'])){
			$query = $query . " AND Email LIKE('%" . cIn($_POST['email']) . "%')";
		}//end if
		
		$query = $query . " ORDER BY LastName, FirstName";
	} else {
		$query = "SELECT * FROM " . HC_TblPrefix  . "users WHERE IsRegistered = 1 ORDER BY LastName, FirstName";
	}//end if
	
	$result = doQuery($query);
	if(hasRows($result)){	?>
		<div class="userList">
			<div class="userListName"><b><?php echo $hc_lang_news['Name'];?></b></div>
			<div class="userListEmail"><b><?php echo $hc_lang_news['Emailb'];?></b></div>
			<div class="userListTools">&nbsp;</div>&nbsp;
		</div>
<?php 	$cnt = 0;
		while($row = mysql_fetch_row($result)){
			if($cnt >0){echo "<br />";}?>
			<div style="clear:both;" class="userListName<?php if($cnt % 2 == 1){echo "HL";}?>"><?php echo cOut($row[1]) . " " . cOut($row[2]);?></div>
			<div class="userListEmail<?php if($cnt % 2 == 1){echo "HL";}?>">&nbsp;<?php echo cOut($row[3]);?></div>
			<div class="userListTools<?php if($cnt % 2 == 1){echo "HL";}?>"><a href="<?php echo CalAdminRoot;?>/?com=useredit&amp;uID=<?php echo cOut($row[0]);?>" class="main"><img src="<?php echo CalAdminRoot;?>/images/icons/iconEdit.png" width="15" height="15" alt="" border="0" style="vertical-align:middle;" /></a>&nbsp;<a href="javascript:;" onclick="javascript:doDelete('<?php echo cOut($row[0]);?>'); return false;" class="main"><img src="<?php echo CalAdminRoot;?>/images/icons/iconDelete.png" width="15" height="15" alt="" border="0" style="vertical-align:middle;" /></a></div>
	<?php 	$cnt++;
		}//end while
	} else {
		echo "<br />" . $hc_lang_news['NoRecip'];
	}//end if	?>