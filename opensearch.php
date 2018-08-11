<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	define('isHC',true);
	define('isAction',true);
	include(dirname(__FILE__).'/loader.php');
	
	header ('Content-Type:text/xml; charset=utf-8');
	echo '<?xml version="1.0"?>
<OpenSearchDescription xmlns="http://a9.com/-/spec/opensearch/1.1/">
	<ShortName>'.CalName.'</ShortName>
	<Description>'.$hc_lang_core['OpenSearch'].'</Description>
	<Image height="16" width="16" type="image/x-icon">'.CalRoot.'/favicon.ico</Image>
	<Url type="text/html" method="get" template="'.CalRoot.'/index.php?com=searchresult&amp;k={searchTerms}" />
</OpenSearchDescription>';