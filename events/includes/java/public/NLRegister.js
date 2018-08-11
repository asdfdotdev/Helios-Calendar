function chkFrm()
{
dirty = 0;
warn = "Your registration could not be completed for the following reason(s):";

	if(document.frmNewsletterReg.firstname.value == ''){
		dirty = 1;
		warn = warn + '\n*First Name is Required';
	}//end if

	if(document.frmNewsletterReg.lastname.value == ''){
		dirty = 1;
		warn = warn + '\n*Last Name is Required';
	}//end if
	
	if(validateCheckArray('frmNewsletterReg','catID[]',1,'Category') > 0){
		dirty = 1;
		warn = warn + '\n*Category Selection is Required';
	}//end if
	
	if(document.frmNewsletterReg.occupation.value == 0){
		dirty = 1;
		warn = warn + '\n*Occupation is Required';
	}//end if
	
	if(document.frmNewsletterReg.zip.value == ''){
		dirty = 1;
		warn = warn + '\n*Zip Code is Required';
	}//end if
	
	if(dirty > 0){
		alert(warn + '\n\nPlease complete the form and try again.');
		return false;
	} else {
		return true;
	}//end if
	
}//end chkFrm()