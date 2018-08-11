<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html

	This file includes source code released by JanRain Inc. that has been
	modify by Refresh Web Development, LLC. for use in Helios Calendar
	Under License: http://www.apache.org/licenses/LICENSE-2.0 Apache
	Original Available At: http://openidenabled.com/php-openid/
*/
	$isAction = 1;
	include('../includes/include.php');
	checkIt();
	include('LoginShared.php');

	if($hc_cfg65 == 1){
		$proof = isset($_POST['proof']) ? $_POST['proof'] : NULL;
		$challenge = isset($_SESSION[$hc_cfg00 . 'hc_cap']) ? $_SESSION[$hc_cfg00 . 'hc_cap'] : NULL;
	} elseif($hc_cfg65 == 2){
		$proof = isset($_POST["recaptcha_response_field"]) ? $_POST["recaptcha_response_field"] : NULL;
		$challenge = isset($_POST["recaptcha_challenge_field"]) ? $_POST["recaptcha_challenge_field"] : NULL;
	}//end if
	spamIt($proof,$challenge,5);

	$openid = $_POST['myOID'];
	$policy_uris = $_POST['policies'];
	$consumer = getConsumer();

	$auth_request = $consumer->begin($openid);
	if (!$auth_request) {
		header('Location: ' . CalRoot . '/index.php?com=login&msg=1');
		exit();
	}//end if

    $sreg_request = Auth_OpenID_SRegRequest::build(
                                     array('nickname'),
                                     array('fullname', 'email'));
    if($sreg_request) {
        $auth_request->addExtension($sreg_request);
    }//end if
	
    $pape_request = new Auth_OpenID_PAPE_Request($policy_uris);
    if($pape_request) {
        $auth_request->addExtension($pape_request);
    }//end if
    
    if($auth_request->shouldSendRedirect()) {
        $redirect_url = $auth_request->redirectURL(getTrustRoot(),
                                                   getReturnTo());
		if(Auth_OpenID::isFailure($redirect_url)) {
			header('Location: ' . CalRoot . '/index.php?com=login&msg=2');
    		exit();
        } else {
            header("Location: ".$redirect_url);
        }//end if
    } else {
        $form_id = 'openid_message';
        $form_html = $auth_request->formMarkup(getTrustRoot(), getReturnTo(),
                                               false, array('id' => $form_id))
		;
		if(Auth_OpenID::isFailure($form_html)) {
            header('Location: ' . CalRoot . '/index.php?com=login&msg=2');
    		exit();
        } else {
        	$page_contents = array(
               "<html><head><title>",
               "OpenID transaction in progress",
               "</title></head>",
               "<body onload='document.getElementById(\"".$form_id."\").submit()'>",
               $form_html,
               "</body></html>");
            print implode("\n", $page_contents);
        }//end if
    }//end if	?>