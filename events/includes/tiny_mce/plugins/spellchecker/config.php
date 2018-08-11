<?php
	$spellCheckerConfig = array();

	// Spellchecker class use
	require_once("classes/TinyGoogleSpell.class.php"); // Google web service

	// General settings
	$spellCheckerConfig['enabled'] = true;

	// Default settings
	$spellCheckerConfig['default.language'] = 'en';
	$spellCheckerConfig['default.mode'] = PSPELL_FAST;

	// Normaly not required to configure
	$spellCheckerConfig['default.spelling'] = "";
	$spellCheckerConfig['default.jargon'] = "";
	$spellCheckerConfig['default.encoding'] = "";

	// Pspell shell specific settings
	$spellCheckerConfig['tinypspellshell.aspell'] = '/usr/bin/aspell';
	$spellCheckerConfig['tinypspellshell.tmp'] = '/tmp';
?>
