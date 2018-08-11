<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(!defined('isHC')){exit(-1);}
	
	/**
	 * Output hidden form inputs named for fields commonly targeted by spammers.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @return void
	 */
	function fakeFormFields(){
		echo '
		<input name="username" id="username" type="text" value="" style="display:none;" />
		<input name="password" id="password" type="text" value="" style="display:none;" />
		<input name="name" id="name" type="text" value="" style="display:none;" />
		<input name="subject" id="subject" type="text" value="" style="display:none;" />
		<input name="email" id="email" type="text" value="" style="display:none;" />
		<input name="phone" id="phone" type="text" value="" style="display:none;" />
		<input name="message" id="message" type="text" value="" style="display:none;" />';
	}
	/**
	 * Output obfuscated mailto: link.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @param string $email email address for link
	 * @param string $subject [optional] subject of email
	 * @param string $label [optional] text to precede link
	 * @return datatype description
	 */
	function cleanEmailLink($email,$subject = '',$label = ''){
		$var1 = preg_replace('/[0-9]*/', '', md5(rand(0,10845)));
		$var2 = preg_replace('/[0-9]*/', '', md5(rand(10846,20795)));
		$var3 = preg_replace('/[0-9]*/', '', md5(rand(20796,31794)));
		$var4 = preg_replace('/[0-9]*/', '', md5(rand(31794,42847)));
		$eParts = explode("@", $email);
		
		if(!isset($eParts[1])){
			echo $label;
			return;
		}
		
		$eEnds = explode(".", $eParts[1]);
		$eEnds = implode('" + "&#46;" + "',$eEnds);
		$subject = ($subject != '') ? " + '?subject=" . cIn($subject) : " + '";
		echo '
		<script>
		//<!--
			var '.$var2.' = "'.$eEnds.'";var '.$var1.' = "'.$eParts[0].'";var '.$var3.' = '.$var1.';var '.$var4.' = '.$var2.';
			document.write(\''.$label.'<a href="\' + \'ma\' + \'ilt\' + \'o:\' + '.$var3.' + \'&#64;\' + '.$var4.' + \'">\' + '.$var3.' + \'&#64;\' + '.$var4.' + \'</a>\');
		//-->
		</script>';
	}
?>