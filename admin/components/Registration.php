<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	if (isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "3" :
				feedback(1,"User Deleted Successfully.");
				break;
				
		}//end switch
	}//end if
?>
<table cellpadding="0" cellspacing="0" border="0">
	<?php
	if(isset($_GET['aID']) && is_numeric($_GET['aID'])){
	?>
	<tr>
		<td>
			<?php
				include('Register.php');
			?>
		</td>
	</tr>
	<?php
	} else {
	?>
	<tr>
		<td>
			<form name="frm" id="frm" method="post" action="<?echo CalAdminRoot;?>/components/RegisterAction.php">
			<input type="hidden" name="eventID" id="eventID" value="<?echo $_GET['eID'];?>">
			<?php
				$result = doQuery("SELECT * FROM " . HC_TblPrefix . "events WHERE PkID = " . cIn($_GET['eID']));
				$maxAllowed = mysql_result($result,0,26);
				
				$result = doQuery("SELECT * FROM " . HC_TblPrefix . "registrants WHERE EventID = " . cIn($_GET['eID']) . " ORDER BY RegisteredAt");
				$row_cnt = mysql_num_rows($result);
				
				if($row_cnt > 0){
				echo "<b>" . $row_cnt . " of " . $maxAllowed . " spaces taken.</b><br><br>";
				?>
				<table cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td width="125"><b>Name</b></td>
						<td width="175"><b>Email</b></td>
						<td width="115"><b>Phone</b></td>
						<td width="50" align="center"><b>Delete?</b></td>
					</tr>
				<?
					$cnt = 0;
					$shown = 0;
					while($row = mysql_fetch_row($result)){
						if($cnt >= $maxAllowed && $shown == 0){
							$shown = 1;
							$cnt = 0;
						?>
							<tr>
								<td colspan="4">
									<img src="<?echo CalAdminRoot;?>images/spacer.gif" width="1" height="20" alt="" border="0"><br>
									<b>Overflow Registrants</b>
								</td>
							</tr>
						<?
						}//end if
					?>
						<tr>
							<td <?if($cnt % 2 == 0){echo "bgcolor=\"#EEEEEE\"";}?>><?echo cOut($row[1]);?></td>
							<td <?if($cnt % 2 == 0){echo "bgcolor=\"#EEEEEE\"";}?>><?echo cOut($row[2]);?></td>
							<td <?if($cnt % 2 == 0){echo "bgcolor=\"#EEEEEE\"";}?>><?echo cOut($row[3]);?></td>
							<td align="center" <?if($cnt % 2 == 0){echo "bgcolor=\"#EEEEEE\"";}?>><input type="checkbox" name="dID[]" id="dID_<?echo cOut($row[0]);?>" value="<?echo cOut($row[0]);?>"></td>
						</tr>
					<?
						$cnt++;
					}//end while
				?>
					<tr>
						<td colspan="2">&nbsp;</td>
						<td colspan="2" align="right">
							<img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"><br>
							<input type="submit" name="submit" id="submit" value=" Delete Marked " class="button">
						</td>
					</tr>
				</table>
				</form>
				<?
				} else {
				?>
					There are currently no registrants available for this event.
					<br><br>
					<a href="<?echo CalAdminRoot;?>/Events/event.php?eID=<?echo $_GET['eID'];?>&rID=<?echo $_GET['rID'];?>&aID=0" class="main">Click here to add a registrant.</a>
					<br><br><br><br><br><br><br><br><br><br><br><br>
				<?
				}//end if
			?>
		</td>
	</tr>
	<?php
	}//end if
	?>
	
</table>