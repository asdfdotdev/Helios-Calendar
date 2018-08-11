<?
/*	Future Global Variables
*/
	$mapCenter = "42.9629, -85.6606";
	$zoomLevel = "11";
/*
	1 = world
	3 = country
	5 = state
	10 = city
	15 = street
	17
*/
	$googleID = "ABQIAAAA5hIZ_lzjmgh5m0PrXOUgsBSfRUUQlL-1i9XUIIxOKZTG35WtchQD4YAwRgnnQ2dMNrCSW_UlBcVSTw";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>Untitled</title>
	<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<?echo $googleID;?>"
            type="text/javascript"></script>
    <script type="text/javascript">
    //<![CDATA[
	var emarkers = [];
	var msgs = [];
	var i = 0;
	var map;
	var marker;
	
	function goHome(){
		map.setCenter(new GLatLng(<?echo $mapCenter;?>), <?echo $zoomLevel;?>);
	}//end goHome
	
	function showEvent(i){
		map.setZoom(16);
		map.setMapType(G_NORMAL_MAP);
		emarkers[i].openInfoWindowHtml(msgs[i]);
	}//end showEvent()
	
	function createMarker(i, point, msg){
		marker = new GMarker(point);
		GEvent.addListener(marker, "click", function() {
		marker.openInfoWindowHtml(msg);
		});
		emarkers[i] = marker;
 		msgs[i] = msg;
		return marker;
	}//end createMarker()
	
    function load(){
		if (GBrowserIsCompatible()) {
			map = new GMap2(document.getElementById("map"));
			goHome();
			map.addControl(new GSmallMapControl());
			map.addControl(new GMapTypeControl());
			
			var point = new GLatLng(42.963226, -85.668130);
			var msg = "<b>1 Division Ave S Grand Rapids MI 49503</b>";
			map.addOverlay(createMarker(0, point, msg));
			
			var point = new GLatLng(42.964576, -85.672380);
			var msg = "<b>60 Monroe Center AVE NW, Grand Rapids, MI 49503</b>";
			map.addOverlay(createMarker(1, point, msg));
			
			var point = new GLatLng(42.963226, -85.671680);
			var msg = "<b>130 Fulton West, Grand Rapids, MI 49503</b>";
			map.addOverlay(createMarker(2, point, msg));
			
			var point = new GLatLng(42.964926, -85.672130);
			var msg = "<b>168 Louis Campau Promenade NW, Grand Rapids, MI 49503</b>";
			map.addOverlay(createMarker(3, point, msg));
		
			//marker.openInfoWindowHtml(msg);
		}//end if
    }//end load()
	
    //]]>
    </script>
</head>
<body onload="load()" onunload="GUnload()">
	<div id="map" style="width: 500px; height: 300px"></div>
	<a href="javascript:goHome();">go home</a> |
	<a href="javascript:showEvent(0);">link 1</a> |
	<a href="javascript:showEvent(1);">link 2</a> |
	<a href="javascript:showEvent(2);">link 3</a> |
	<a href="javascript:showEvent(3);">link 4</a>
	
<?	$address = "10115 Sharon Road";
	$city = "Saint Charles";
	$state = "MI";
	$zip = "48655";
	$country = "US";
	
	if(!($fp = fsockopen("api.local.yahoo.com", 80, $errno, $errstr, 1)) ){
		//	Cannot Connect
	} else {
		$read = "";
		$request = "GET /MapsService/V1/geocode?appid=HeliosCalendar&street=" . urlencode($address) . "&city=" . urlencode($city) . "&state=" . urlencode($state) . "&zip=" . urlencode($zip) . " HTTP/1.1\r\n";
		$request .= "Host: api.local.yahoo.com\r\n";
		$request .= "Connection: Close\r\n\r\n";
		fwrite($fp, $request);
		
		while (!feof($fp)) {
			$read .= fread($fp,1024);
		}//end while
		
		$output = explode('<?', $read);
		$output = "<?" . $output[1];
		
		$getLat = explode('<Latitude>', $output);
		$getLat = explode('</Latitude>', $getLat[1]);
		$theLat = $getLat[0];
		
		$getLon = explode('<Longitude>', $output);
		$getLon = explode('</Longitude>', $getLon[1]);
		$theLon = $getLon[0];
		
		echo "<br />-- New Lat: " . $theLat . " --";
		echo "<br />-- New Lon: " . $theLon . " --";
		fclose($fp);
	}//end if	?>
</body>
</html>