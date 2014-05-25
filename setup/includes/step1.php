<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
?>
	<form name="frm" id="frm" method="post" action="index.php?step=2">
	<fieldset>
		<iframe src="../LICENSE" width="100%" height="400" frameborder="0"></iframe>
		<label for="agree" class="agreed"><input onclick="if(document.frm.agree.checked == true){document.frm.submit.disabled = false;}else{document.frm.submit.disabled = true;}" type="checkbox" name="agree" id="agree" />&nbsp;I have read, understand and agree to the terms of the license.</label>
	</fieldset>
	<br />
	<input disabled="disabled" type="submit" name="submit" id="submit" value="Accept License Agreement &amp; Begin Setup" />
	</form>