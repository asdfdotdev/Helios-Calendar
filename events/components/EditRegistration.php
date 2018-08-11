<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2006 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	$uID = 0;
	$fname = "";
	$lname = "";
	$email = "";
	$oID = 0;
	$zip = "";
	$guid = 0;
	
	if(isset($_GET['guid'])){
		$result = doQuery("SELECT * FROM " . HC_TblPrefix . "users WHERE GUID = '" . cIn($_GET['guid']) . "'");
		
		if(hasRows($result)){
			$uID = cOut(mysql_result($result,0,0));
			$fname = cOut(mysql_result($result,0,1));
			$lname = cOut(mysql_result($result,0,2));
			$email = cOut(mysql_result($result,0,3));
			$oID = cOut(mysql_result($result,0,4));
			$zip = cOut(mysql_result($result,0,5));
			$guid = cOut($_GET['guid']);
		}//end if
	}//end if	?>
	<script language="JavaScript" type="text/JavaScript" src="<?echo CalRoot;?>/includes/java/Email.js"></script>
	<script language="JavaScript" type="text/JavaScript" src="<?echo CalRoot;?>/includes/java/public/NLRegister.js"></script>
<?	if (isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1,"Registration Update Successful.");
				break;
				
		}//end switch
	}//end if	?>

	Make the changes you'd like to your registration below.<br>Then click "Save Changes" to save your registration.<br><br>
	If you wish to change your email address unsubscribe with your current address and signup with a new one.<br><br>
	<table cellpadding="0" cellspacing="0" border="0">
	<form name="frmEventReg" id="frmEventReg" method="post" action="<?echo HC_EditRegisterAction;?>" onsubmit="return chkFrm();">
	<input type="hidden" name="guid" id="guid" value="<?echo $guid;?>">
		<tr>
			<td class="eventMain" width="80">First Name:</td>
			<td><input size="15" maxlength="50" type="text" name="firstname" id="firstname" value="<?echo $fname;?>" class="eventInput"></td>
		</tr>
		<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
		<tr>
			<td class="eventMain" width="80">Last Name:</td>
			<td><input size="15" maxlength="50" type="text" name="lastname" id="lastname" value="<?echo $lname;?>" class="eventInput"></td>
		</tr>
		<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
		<tr>
			<td class="eventMain">Occupation:</td>
			<td>
				<select name="occupation" id="occupation" class="eventInput">
					<option value="0">[Select an Occupation]</option>
				<?php
					$result = doQuery("SELECT * FROM " . HC_TblPrefix . "useroccupation WHERE IsActive = 1 ORDER BY Occupation");
					if(hasRows($result)){
						
						while($row = mysql_fetch_row($result)){
						?>
							<option<?if($oID == $row[0]){echo " SELECTED";}//end if?> value="<?echo $row[0];?>"><?echo cOut($row[1]);?></option>
						<?
						}//while
						
					}//end if
				?>
				</select>
			</td>
		</tr>
		<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
		<tr>
			<td class="eventMain">Zip Code:</td>
			<td><input maxlength="5" size="5" type="text" name="zip" id="zip" value="<?echo $zip;?>" class="eventInput"></td>
		</tr>
		<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
		<tr>
			<td valign="top" class="eventMain">Category:</td>
			<td class="eventMain">
				<table cellpadding="0" cellspacing="0" border="0">
					<tr>
				<?	$query = "	SELECT " . HC_TblPrefix . "categories.*, " . HC_TblPrefix . "usercategories.UserID
										FROM " . HC_TblPrefix . "categories 
											LEFT JOIN " . HC_TblPrefix . "usercategories ON (" . HC_TblPrefix . "categories.PkID =" . HC_TblPrefix . "usercategories.CategoryID AND " . HC_TblPrefix . "usercategories.UserID = " . cIn($uID) . ") 
										WHERE " . HC_TblPrefix . "categories.IsActive = 1
										ORDER BY CategoryName";
					$result = doQuery($query);
					$cnt = 0;
					$curCat = "";
					while($row = mysql_fetch_row($result)){
						if($row[3] > 0){
							if(($cnt % 3 == 0) && ($cnt > 0) ){echo "</tr><tr>";}//end if
						?>
							<td class="eventMain"><input <?if($row[6] != ''){echo "checked";}//end if?> type="checkbox" name="catID[]" id="catID_<?echo $row[0];?>" value="<?echo $row[0];?>"></td>
							<td class="eventMain"><label for="catID_<?echo $row[0];?>"><?echo $row[1];?></label>&nbsp;&nbsp;</td>
						<?
							$cnt = $cnt + 1;
						}//end if
					
					}//end while
				?>
					</tr>
				</table>
				<?	if($cnt > 1){	?>
					<img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="10" alt="" border="0"><br>
					[ <a class="eventMain" href="javascript:;" onclick="checkAllArray('frmEventReg', 'catID[]');">Select All Categories</a> 
					&nbsp;|&nbsp; <a class="eventMain" href="javascript:;" onclick="uncheckAllArray('frmEventReg', 'catID[]');">Deselect All Categories</a> ]
					<img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0">
				<?	}//end if	?>
			</td>
		</tr>
		<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
		<tr>
			<td>&nbsp;</td>
			<td>
				<input type="submit" name="submit" id="submit" value=" Save Changes " class="eventButton">
			</td>
		</tr>
	</form>
	</table>