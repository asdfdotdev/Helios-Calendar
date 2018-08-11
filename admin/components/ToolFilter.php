<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2007 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	appInstructions(0, "Filter_Link", "Filter Link Generator", "Use the form below to generate your filter link. This link will allow you to send trafic to your calendar with the filter pre-set for visitors.<br /><br />To create the link click the categories you want to be <b>active</b> for your filter. The link is generated in the text box. Use this to link to your calendar to activate the filter.");	?>
	<br />
	<script language="JavaScript" type="text/JavaScript" src="<?echo CalRoot;?>/includes/java/Checkboxes.js"></script>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
		function updateLink(){
			document.frmToolLink.filterLink.value = '<?echo CalRoot;?>/?l=' + checkUpdateString('frmToolLink', 'catID[]') + '&c=' + checkUpdateString('frmToolLink', 'cityName[]');
		}//end updateLink()
	//-->
	</script>
	<form name="frmToolLink" id="frmToolLink" method="post" action="" onsubmit="return false;">
	<fieldset>
		<legend>Filter Link Generator</legend>
		<div class="frmOpt">
			<label>Cities:<br />(Optional)</label>
			<table cellpadding="0" cellspacing="0" border="0"><tr>
		<?	$cities = getCities();
			$cnt = 0;
			$favCities = array();
			foreach($cities as $val){
				if(($cnt % 2 == 0) && ($cnt > 0) ){echo "</tr><tr>";}//end if
				if($val != ''){	
					$findMe = "'" . $val . "'";	?>
					<td><label for="cityName_<?echo $val;?>" class="category"><input onclick="updateLink();" name="cityName[]" id="cityName_<?echo $val;?>" type="checkbox" value="<?echo $val;?>" class="noBorderIE" /><?echo $val;?></label></td>
		<?		}//end if
				$cnt++;
			}//end foreach	?>
			</tr></table>
		</div>
		<div class="frmOpt">
			<label>Categories:</label>
			<table cellpadding="0" cellspacing="0" border="0">
			<?	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "categories WHERE IsActive = 1 AND PkID > 0 ORDER BY CategoryName");
				$cnt = 0;
				
				while($row = mysql_fetch_row($result)){
					if(($cnt % 2 == 0) && ($cnt > 0) ){echo "</tr><tr>";}	?>
					<td><label for="catID_<?echo $row[0];?>" class="category"><input onclick="updateLink();" name="catID[]" id="catID_<?echo $row[0];?>" type="checkbox" value="<?echo $row[0];?>" class="noBorderIE" /><?echo cOut($row[1]);?></label></td>
				<?	$cnt = $cnt + 1;
				}//end while?>
			</table>
		</div>
		<div class="frmReq">
			<label for="filterLink">Link:</label>
			<input name="filterLink" id="filterLink" type="text" size="70" maxlength="200" value="<?echo CalRoot;?>/" />
		</div>
	</fieldset>
	<br />
	<input name="reset" id="reset" type="reset" value=" Reset Link " class="button" />
	</form>
