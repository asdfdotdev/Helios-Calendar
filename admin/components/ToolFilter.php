<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2007 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	appInstructions(0, "Filter_Link", "Filter Link Generator", "Use the form below to generate your filter link. This link will allow you to send traffic to your calendar with the filter pre-set for visitors.<br /><br />To create the link click the categories you want to be <b>active</b> for your filter. The link is generated in the text box. Use this to link to your calendar to activate the filter.");	?>
	<br />
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Checkboxes.js"></script>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
		function updateLink(){
			document.frmToolLink.filterLink.value = '<?php echo CalRoot;?>/?l=' + checkUpdateString('frmToolLink', 'catID[]') + '&c=' + checkUpdateString('frmToolLink', 'cityName[]');
		}//end updateLink()
	//-->
	</script>
	<form name="frmToolLink" id="frmToolLink" method="post" action="" onsubmit="return false;">
	<fieldset>
		<legend>Filter Link Generator</legend>
		<div class="frmOpt">
			<label>Cities:</label>
			<table cellpadding="0" cellspacing="0" border="0"><tr>
	<?php 	$cities = getCities();
			$cnt = 0;
			$favCities = array();
			foreach($cities as $val){
				if(($cnt % 2 == 0) && ($cnt > 0) ){echo "</tr><tr>";}//end if
				if($val != ''){	
					$findMe = "'" . $val . "'";	?>
					<td><label for="cityName_<?php echo $val;?>" class="category"><input onclick="updateLink();" name="cityName[]" id="cityName_<?php echo $val;?>" type="checkbox" value="<?php echo $val;?>" class="noBorderIE" /><?php echo $val;?></label></td>
	<?php 		}//end if
				$cnt++;
			}//end foreach	?>
			</tr></table>
		</div>
		<div class="frmOpt">
			<label>Categories:</label>
			<table cellpadding="0" cellspacing="0" border="0">
		<?php 	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "categories WHERE IsActive = 1 AND PkID > 0 ORDER BY CategoryName");
				$cnt = 0;
				
				while($row = mysql_fetch_row($result)){
					if(($cnt % 2 == 0) && ($cnt > 0) ){echo "</tr><tr>";}	?>
					<td><label for="catID_<?php echo $row[0];?>" class="category"><input onclick="updateLink();" name="catID[]" id="catID_<?php echo $row[0];?>" type="checkbox" value="<?php echo $row[0];?>" class="noBorderIE" /><?php echo cOut($row[1]);?></label></td>
			<?php 	$cnt = $cnt + 1;
				}//end while?>
			</table>
		</div>
		<div class="frmReq">
			<label for="filterLink">Link:</label>
			<input name="filterLink" id="filterLink" type="text" size="70" maxlength="200" value="<?php echo CalRoot;?>/" />
		</div>
	</fieldset>
	<br />
	<input name="reset" id="reset" type="reset" value=" Reset Link " class="button" />
	</form>
