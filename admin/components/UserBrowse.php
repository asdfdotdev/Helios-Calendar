<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2007 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	if(isset($_GET['uID']) && is_numeric($_GET['uID'])){
		$uID = $_GET['uID'];
		$where = "Edit";
	} else {
		$uID = 0;
		$where = "Add";
		
		if(isset($_GET['name'])){
			$name = $_GET['name'];
		} else {
			$name = "";
		}//end if
		
		if(isset($_GET['email'])){
			$email = $_GET['email'];
		} else {
			$email = "";
		}//end if
		
	}//end if	?>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function doDelete(dID){
		if(confirm('Newsletter Recipient Delete Is Perminant!\nAre you sure you want to delete?\n\n          Ok = YES Delete Recipient\n          Cancel = NO Don\'t Delete Recipient')){
			document.location.href = '<?php echo CalAdminRoot . "/components/UserEditAction.php";?>?dID=' + dID;
		}//end if
	}//end doDelete
	//-->
	</script>
<?php
	if (isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1, "Newsletter Recipient Deleted Successfully!");
				break;
				
		}//end switch
	}//end if
	
	appInstructions(0, "Editing_Recipients", "Newsletter Recipients", "Select the recipient below you wish to edit or delete.");
	
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
			<div class="userListName"><b>Name</b></div>
			<div class="userListEmail"><b>Email</b></div>
			<div class="userListTools">&nbsp;</div>&nbsp;
		</div>
<?php 	$firstname = cOut(mysql_result($result,0,1));
		$lastname = cOut(mysql_result($result,0,2));
		$email = cOut(mysql_result($result,0,3));
		$password = cOut(mysql_result($result,0,4));
		$email = cOut(mysql_result($result,0,2));
		
		mysql_data_seek($result,0);
		$cnt = 0;
		while($row = mysql_fetch_row($result)){	?>
			<div style="clear:both;" class="userListName<?php if($cnt % 2 == 1){echo "HL";}?>"><?php echo cOut($row[1]) . " " . cOut($row[2]);?></div>
			<div class="userListEmail<?php if($cnt % 2 == 1){echo "HL";}?>"><?php echo cOut($row[3]);?></div>
			<div class="userListTools<?php if($cnt % 2 == 1){echo "HL";}?>"><a href="<?php echo CalAdminRoot;?>/?com=useredit&amp;uID=<?php echo cOut($row[0]);?>" class="main"><img src="<?php echo CalAdminRoot;?>/images/icons/iconEdit.gif" width="15" height="15" alt="" border="0" align="middle" /></a>&nbsp;<a href="javascript:;" onclick="javascript:doDelete('<?php echo cOut($row[0]);?>'); return false;" class="main"><img src="<?php echo CalAdminRoot;?>/images/icons/iconDelete.gif" width="15" height="15" alt="" border="0" align="middle" /></a></div>
	<?php 	$cnt++;
		}//end while
	} else {	?>
		<br />
		There are currently no registered newsletter recipients available.
<?php
	}//end if	?>