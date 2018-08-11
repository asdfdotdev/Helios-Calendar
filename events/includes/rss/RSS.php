<?php
/*  PHP RSS Reader v1.1
    Original Author: Richard James Kendall 
    Free to use, please acknowledge me
*/

set_time_limit(0);

$rss_channel = array();
$currently_writing = "";
$main = "";
$item_counter = 0;

function elementStart($parser, $name, $attrs) {
   	global $rss_channel, $currently_writing, $main;
   	switch($name) {
   		case "RSS":
   		case "RDF:RDF":
   		case "ITEMS":
   			$currently_writing = "";
   			break;
   		case "CHANNEL":
   			$main = "CHANNEL";
   			break;
   		case "IMAGE":
   			$main = "IMAGE";
   			$rss_channel["IMAGE"] = array();
   			break;
   		case "ITEM":
   			$main = "ITEMS";
   			break;
   		default:
   			$currently_writing = $name;
   			break;
   	}
}

function elementStop($parser, $name) {
   	global $rss_channel, $currently_writing, $item_counter;
   	$currently_writing = "";
   	if ($name == "ITEM") {
   		$item_counter++;
   	}
}

function dataCharacter($parser, $data) {
	global $rss_channel, $currently_writing, $main, $item_counter;
	if ($currently_writing != "") {
		switch($main) {
			case "CHANNEL":
				if (isset($rss_channel[$currently_writing])) {
					$rss_channel[$currently_writing] .= $data;
				} else {
					$rss_channel[$currently_writing] = $data;
				}
				break;
			case "IMAGE":
				if (isset($rss_channel[$main][$currently_writing])) {
					$rss_channel[$main][$currently_writing] .= $data;
				} else {
					$rss_channel[$main][$currently_writing] = $data;
				}
				break;
			case "ITEMS":
				if (isset($rss_channel[$main][$item_counter][$currently_writing])) {
					$rss_channel[$main][$item_counter][$currently_writing] .= $data;
				} else {
					//print ("rss_channel[$main][$item_counter][$currently_writing] = $data<br>");
					$rss_channel[$main][$item_counter][$currently_writing] = $data;
				}
				break;
		}
	}
}

$xml_parser = xml_parser_create();
xml_set_element_handler($xml_parser, "elementStart", "elementStop");
xml_set_character_data_handler($xml_parser, "dataCharacter");
if (!($fp = fopen($file, "r"))) {
	die("could not open XML input");
}

while ($data = fread($fp, 4096)) {
	if (!xml_parse($xml_parser, $data, feof($fp))) {
		die(sprintf("XML error: %s at line %d",
					xml_error_string(xml_get_error_code($xml_parser)),
					xml_get_current_line_number($xml_parser)));
	}
}
xml_parser_free($xml_parser);
?>
