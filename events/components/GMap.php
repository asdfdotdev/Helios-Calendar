<?	if(isset($hc_googleKey) && $hc_googleKey != '' && isset($_GET['com']) && $_GET['com'] == 'detail'){	
		if($locLat != '' && $locLon != ''){?>
		<script language="JavaScript" type="text/JavaScript">
			buildGmap('<?echo $locLat;?>', '<?echo $locLon;?>', '<?echo $locTag . "<br />" . $locLink;?>');
			
			//document.onunload = GUnload();
		</script>
	<?	}//end if
	}//end if	?>