<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	include('../../events/includes/include.php');
	hookDB();
	
	$eID = $_POST['eventID'];
	
	if(!isset($_POST['dID'])){
		$name = $_POST['name'];
		$email = $_POST['email'];
		$phone = $_POST['phone'];
		$address = $_POST['address'];
		$address2 = $_POST['address2']; 
		$city = $_POST['city'];
		$state = $_POST['locState'];
		$zip = $_POST['zip'];
		
		$oldemail = $_POST['oldemail'];
		
		$result = doQuery("SELECT * FROM " . HC_TblPrefix . "registrants WHERE Email = '" . $email . "' AND EventID = " . $eID);
		$row_cnt = mysql_num_rows($result);
		
		if($row_cnt > 0){
			header("Location: " . CalAdminRoot . "/index.php?com=eventregister&msg=2&eID=" . $eID . "&aID=1&name=" . urlencode($name) . "&email=" . urlencode($email) . "&phone=" . urlencode($phone) . "&address=" . urlencode($address) . "&address2=" . urlencode($address2) . "&city=" . urlencode($city) . "&state=" . urlencode($state) . "&zip=" . urlencode($zip));
			
		} else {
			
			$query = "	INSERT into " . HC_TblPrefix . "registrants(Name, Email, Phone, Address, Address2, City, State, Zip, EventID, IsActive, RegisteredAt)
						Values('" . $name . "',
								'" . $email . "',
								'" . $phone . "',
								'" . $address . "',
								'" . $address2 . "',
								'" . $city . "',
								'" . $state . "',
								'" . $zip . "',
								" . $eID . ",
								1,
								now() )";
			
			doQuery($query);
			
			$result = doQuery("SELECT * FROM " . HC_TblPrefix . "events WHERE PkID = " . $eID);
			$eventTitle = mysql_result($result,0,1);
			$eventContact = mysql_result($result,0,13);
			$eventEmail = mysql_result($result,0,14);
			
			$headers = "From: " . CalAdmin . " <" . CalAdminEmail . ">\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Reply-To: " . CalAdmin . " <" . CalAdminEmail . ">\r\n";
			$headers .= "Content-Type: text/html; charset=iso-8859-1;\r\n";
			
			/*
				r = Resgistrant (Person Signing Up)
				c = Contact (Event Organizer)
			*/
			
			$Rsubject = "Event Registration Confirmation :: " . CalName;
			$Csubject = "New User Event Registration :: " . CalName;
			
			$result = doQuery("	SELECT " . HC_TblPrefix . "events.SpacesAvailable, registrants.*
								FROM " . HC_TblPrefix . "registrants
									LEFT JOIN " . HC_TblPrefix . "events ON (" . HC_TblPrefix . "registrants.EventID = " . HC_TblPrefix . "events.PkID)
								WHERE " . HC_TblPrefix . "registrants.EventID = " . $eID);
			$row_cnt = mysql_num_rows($result);
			
			if($row_cnt == mysql_result($result,0,0)){
				//	Registration Full
				$Rmsg = $name . ",<br><br>Your registration for the <b>" . $eventTitle . "</b> event has been received. If neccessary the event coordinator will contact you regarding
						your registration.<br><br>
						Thank you for using the " . CalName . ". If you have any questions please contact me.<br><br>
						" . CalAdmin . "<br>" . CalAdminEmail;
				
				mail($email, $Rsubject, $Rmsg, $headers);
				
				$Cmsg = $eventContact . ",<br><br> Registration for the " . $eventTitle . " event has reached its limit of " . mysql_result($result,0,0) . ".
						The following is your event roster for all registered users.";
						
				$result = doQuery("SELECT * FROM " . HC_TblPrefix . "registrants WHERE EventID = " . $eID);
				
					//	Output Event Roster
					while($row = mysql_fetch_row($result)){
						
						$Cmsg = $Cmsg . "<br><br>" . $row[1] . "<br>" . $row[2] . "<br>" . $row[3] . "<br>" . $row[4];
						if($row[5] != ""){
							$Cmsg = $Cmsg . "<br>" . $row[5];
						}//end if
						
						$Cmsg = $Cmsg . "<br>" . $row[6] . ", " . $row[7] . " " . $row[8];
						
					}//end while
				
				$Cmsg = $Cmsg . "The " . CalName . " will continue to send you overflow registration information on a per registrant basis.
						If you decide to extent the limit or spaces open up you can contact the new registrants regarding their attendance.<br><br>If you have any additional information regarding the event please contact your registrants directly.
						<br><br>" . CalAdmin . "<br>" . CalAdminEmail;
				
				mail(CalAdminEmail, $Csubject, $Cmsg, $headers);
				
			} elseif($row_cnt > mysql_result($result,0,0)){
				//	Overflow Registration
				$Rmsg = $name . ",<br><br>Your registration for the <b>" . $eventTitle . "</b> event has been received. Because the limited number
						of registrants has already been passed there is a chance you may not be able to attend the event. Please confirm your
						registration with the event organizer(s). If neccessary the event coordinator will contact you regarding
						your registration.<br><br>
						Thank you for using the " . CalName . ". If you have any questions please contact me.<br><br>
						" . CalAdmin . "<br>" . CalAdminEmail;
				
				mail($email, $Rsubject, $Rmsg, $headers);
				
				$Cmsg = $eventContact . ",<br><br> There has been a new registration for the " . $eventTitle . " event.<br><br>
						" . $name . "
						<br>" . $email . "
						<br>" . $phone . "
						<br>" . $email . "<br>
						<br>" . $address . "
						<br>" . $address2 . "
						<br>" . $city . ", " . $state . " " . $zip . "
						<br><br>
						If you have any additional information regarding the event please contact your registrants directly.
						<br><br>" . CalAdmin . "<br>" . CalAdminEmail;
				mail(CalAdminEmail, $Csubject, $Cmsg, $headers);
				
			} else {
				//	Registration Spaces Left
				$Rmsg = $name . ",<br><br>Your registration for the <b>" . $eventTitle . "</b> event has been received. If neccessary the event coordinator will contact you regarding
						your registration.<br><br>
						Thank you for using the " . CalName . ". If you have any questions please contact me.<br><br>
						" . CalAdmin . "<br>" . CalAdminEmail;
				
				mail($email, $Rsubject, $Rmsg, $headers);
				
				$Cmsg = $eventContact . ",<br><br> There has been a new registration for the " . $eventTitle . " event.<br><br>
						" . $name . "
						<br>" . $email . "
						<br>" . $phone . "
						<br>" . $email . "<br>
						<br>" . $address . "
						<br>" . $address2 . "
						<br>" . $city . ", " . $state . " " . $zip . "
						<br><br>
						If you have any additional information regarding the event please contact your registrants directly.
						<br><br>" . CalAdmin . "<br>" . CalAdminEmail;
				mail(CalAdminEmail, $Csubject, $Cmsg, $headers);
				
			}//end if
			
			header("Location: " . CalAdminRoot . "/index.php?com=eventregister&eID=" . $eID . "&aID=0&msg=1");
			
		}//end if
	} else {
		$dID = $_POST['dID'];
		$deleteString = "0";
		foreach ($dID as $val){
			$deleteString .= ", " . $val;
		}//end while
		
		doQuery("DELETE FROM " . HC_TblPrefix . "registrants WHERE PkID IN(" . $deleteString . ")");
		
		header("Location: " . CalAdminRoot . "/index.php?com=eventregister&eID=" . $eID . "&msg=3");
		
	}//end if
?>
