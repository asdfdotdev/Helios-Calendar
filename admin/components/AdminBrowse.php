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
		if(confirm('Admin Delete Is Perminant!\nAre you sure you want to delete this admin?\n\n          Ok = YES Delete Admin\n          Cancel = NO Don\'t Delete Admin')){
			document.location.href = '<?php echo CalAdminRoot . "/components/AdminEditAction.php";?>?dID=' + dID;
		}//end if
	}//end doDelete
	//-->
	</script>
<?php
	if (isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1, "Admin Deleted Successfully!");
				break;
				
			case "2" :
				feedback(3, "Admin Delete Failed. You Are Using This Account.");
				break;
				
			case "3" :
				feedback(1, "Admin Updated Successfully!");
				break;
				
			case "4" :
				feedback(1, "Admin Added Successfully! They Will Receive A Welcome Email Shortly.");
				break;
				
		}//end switch
	}//end if
	
	appInstructions(0, "Editing_Admin_Users", "Admin Accounts", "Select the Admin account below you wish to edit or delete.");
	
	$result = doQuery("SELECT * FROM " . HC_TblPrefix  . "admin WHERE SuperAdmin = 0 AND IsActive = 1 ORDER BY LastName, FirstName");
	if(hasRows($result)){	?>
		<div class="adminList">
			<div class="adminName">Name</div>
			<div class="adminEmail">Email</div>
			&nbsp;
		</div>
<?php 	$cnt = 0;
		while($row = mysql_fetch_row($result)){	?>
			<div class="adminName<?php if($cnt % 2 == 1){echo "HL";}?>"><?php echo $row[1] . " " . $row[2];?></div>
			<div class="adminEmail<?php if($cnt % 2 == 1){echo "HL";}?>"><?php echo $row[3];?></div>
			<div class="adminTools<?php if($cnt % 2 == 1){echo "HL";}?>"><a href="<?php echo CalAdminRoot;?>/index.php?com=adminedit&amp;uID=<?php echo $row[0];?>" class="main"><img src="<?php echo CalAdminRoot;?>/images/icons/iconEdit.gif" width="15" height="15" alt="" border="0" align="absmiddle" /></a>&nbsp;<a href="javascript:;" onclick="doDelete('<?php echo $row[0];?>');return false;" class="main"><img src="<?php echo CalAdminRoot;?>/images/icons/iconDelete.gif" width="15" height="15" alt="" border="0" align="absmiddle" /></a></div>
	<?php 	$cnt++;
		}//end while
	} else {	?>
		<br />
		There are currently no administrators available.
<?php
	}//end if	?>