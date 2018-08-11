<?php
/*
	This list uses ISO 3166-1-alpha-2 Country Codes
	http://en.wikipedia.org/wiki/ISO_3166-1_alpha-2
 */
	$iso_country = (isset($iso_country) && $iso_country != '') ? $iso_country : 'US';
?>
<select <?php echo (isset($iso_countryDisabled)) ? 'disabled="disabled"' : '';?> name="selCountry" id="selCountry">
	<option <?php echo ($iso_country=="AF") ? 'selected="selected"' : '';?> value="AF">AFGHANISTAN</option>
	<option <?php echo ($iso_country=="AX") ? 'selected="selected"' : '';?> value="AX">&#197;LAND ISLANDS</option>
	<option <?php echo ($iso_country=="AL") ? 'selected="selected"' : '';?> value="AL">ALBANIA</option>
	<option <?php echo ($iso_country=="DZ") ? 'selected="selected"' : '';?> value="DZ">ALGERIA</option>
	<option <?php echo ($iso_country=="AS") ? 'selected="selected"' : '';?> value="AS">AMERICAN SAMOA</option>
	<option <?php echo ($iso_country=="AD") ? 'selected="selected"' : '';?> value="AD">ANDORRA</option>
	<option <?php echo ($iso_country=="AO") ? 'selected="selected"' : '';?> value="AO">ANGOLA</option>
	<option <?php echo ($iso_country=="AI") ? 'selected="selected"' : '';?> value="AI">ANGUILLA</option>
	<option <?php echo ($iso_country=="AQ") ? 'selected="selected"' : '';?> value="AQ">ANTARCTICA</option>
	<option <?php echo ($iso_country=="AG") ? 'selected="selected"' : '';?> value="AG">ANTIGUA AND BARBUDA</option>
	<option <?php echo ($iso_country=="AR") ? 'selected="selected"' : '';?> value="AR">ARGENTINA</option>
	<option <?php echo ($iso_country=="AM") ? 'selected="selected"' : '';?> value="AM">ARMENIA</option>
	<option <?php echo ($iso_country=="AW") ? 'selected="selected"' : '';?> value="AW">ARUBA</option>
	<option <?php echo ($iso_country=="AU") ? 'selected="selected"' : '';?> value="AU">AUSTRALIA</option>
	<option <?php echo ($iso_country=="AT") ? 'selected="selected"' : '';?> value="AT">AUSTRIA</option>
	<option <?php echo ($iso_country=="AZ") ? 'selected="selected"' : '';?> value="AZ">AZERBAIJAN</option>
	<option <?php echo ($iso_country=="BS") ? 'selected="selected"' : '';?> value="BS">BAHAMAS</option>
	<option <?php echo ($iso_country=="BH") ? 'selected="selected"' : '';?> value="BH">BAHRAIN</option>
	<option <?php echo ($iso_country=="BD") ? 'selected="selected"' : '';?> value="BD">BANGLADESH</option>
	<option <?php echo ($iso_country=="BB") ? 'selected="selected"' : '';?> value="BB">BARBADOS</option>
	<option <?php echo ($iso_country=="BY") ? 'selected="selected"' : '';?> value="BY">BELARUS</option>
	<option <?php echo ($iso_country=="BE") ? 'selected="selected"' : '';?> value="BE">BELGIUM</option>
	<option <?php echo ($iso_country=="BZ") ? 'selected="selected"' : '';?> value="BZ">BELIZE</option>
	<option <?php echo ($iso_country=="BJ") ? 'selected="selected"' : '';?> value="BJ">BENIN</option>
	<option <?php echo ($iso_country=="BM") ? 'selected="selected"' : '';?> value="BM">BERMUDA</option>
	<option <?php echo ($iso_country=="BT") ? 'selected="selected"' : '';?> value="BT">BHUTAN</option>
	<option <?php echo ($iso_country=="BO") ? 'selected="selected"' : '';?> value="BO">BOLIVIA, PLURINATIONAL STATE OF</option>
	<option <?php echo ($iso_country=="BA") ? 'selected="selected"' : '';?> value="BA">BOSNIA AND HERZEGOVINA</option>
	<option <?php echo ($iso_country=="BW") ? 'selected="selected"' : '';?> value="BW">BOTSWANA</option>
	<option <?php echo ($iso_country=="BV") ? 'selected="selected"' : '';?> value="BV">BOUVET ISLAND</option>
	<option <?php echo ($iso_country=="BR") ? 'selected="selected"' : '';?> value="BR">BRAZIL</option>
	<option <?php echo ($iso_country=="IO") ? 'selected="selected"' : '';?> value="IO">BRITISH INDIAN OCEAN TERRITORY</option>
	<option <?php echo ($iso_country=="BN") ? 'selected="selected"' : '';?> value="BN">BRUNEI DARUSSALAM</option>
	<option <?php echo ($iso_country=="BG") ? 'selected="selected"' : '';?> value="BG">BULGARIA</option>
	<option <?php echo ($iso_country=="BF") ? 'selected="selected"' : '';?> value="BF">BURKINA FASO</option>
	<option <?php echo ($iso_country=="BI") ? 'selected="selected"' : '';?> value="BI">BURUNDI</option>
	<option <?php echo ($iso_country=="KH") ? 'selected="selected"' : '';?> value="KH">CAMBODIA</option>
	<option <?php echo ($iso_country=="CM") ? 'selected="selected"' : '';?> value="CM">CAMEROON</option>
	<option <?php echo ($iso_country=="CA") ? 'selected="selected"' : '';?> value="CA">CANADA</option>
	<option <?php echo ($iso_country=="CV") ? 'selected="selected"' : '';?> value="CV">CAPE VERDE</option>
	<option <?php echo ($iso_country=="KY") ? 'selected="selected"' : '';?> value="KY">CAYMAN ISLANDS</option>
	<option <?php echo ($iso_country=="CF") ? 'selected="selected"' : '';?> value="CF">CENTRAL AFRICAN REPUBLIC</option>
	<option <?php echo ($iso_country=="TD") ? 'selected="selected"' : '';?> value="TD">CHAD</option>
	<option <?php echo ($iso_country=="CL") ? 'selected="selected"' : '';?> value="CL">CHILE</option>
	<option <?php echo ($iso_country=="CN") ? 'selected="selected"' : '';?> value="CN">CHINA</option>
	<option <?php echo ($iso_country=="CX") ? 'selected="selected"' : '';?> value="CX">CHRISTMAS ISLAND</option>
	<option <?php echo ($iso_country=="CC") ? 'selected="selected"' : '';?> value="CC">COCOS (KEELING) ISLANDS</option>
	<option <?php echo ($iso_country=="CO") ? 'selected="selected"' : '';?> value="CO">COLOMBIA</option>
	<option <?php echo ($iso_country=="KM") ? 'selected="selected"' : '';?> value="KM">COMOROS</option>
	<option <?php echo ($iso_country=="CG") ? 'selected="selected"' : '';?> value="CG">CONGO</option>
	<option <?php echo ($iso_country=="CD") ? 'selected="selected"' : '';?> value="CD">CONGO, THE DEMOCRATIC REPUBLIC OF THE</option>
	<option <?php echo ($iso_country=="CK") ? 'selected="selected"' : '';?> value="CK">COOK ISLANDS</option>
	<option <?php echo ($iso_country=="CR") ? 'selected="selected"' : '';?> value="CR">COSTA RICA</option>
	<option <?php echo ($iso_country=="CI") ? 'selected="selected"' : '';?> value="CI">C&#212;TE D'IVOIRE</option>
	<option <?php echo ($iso_country=="HR") ? 'selected="selected"' : '';?> value="HR">CROATIA</option>
	<option <?php echo ($iso_country=="CU") ? 'selected="selected"' : '';?> value="CU">CUBA</option>
	<option <?php echo ($iso_country=="CY") ? 'selected="selected"' : '';?> value="CY">CYPRUS</option>
	<option <?php echo ($iso_country=="CZ") ? 'selected="selected"' : '';?> value="CZ">CZECH REPUBLIC</option>
	<option <?php echo ($iso_country=="DK") ? 'selected="selected"' : '';?> value="DK">DENMARK</option>
	<option <?php echo ($iso_country=="DJ") ? 'selected="selected"' : '';?> value="DJ">DJIBOUTI</option>
	<option <?php echo ($iso_country=="DM") ? 'selected="selected"' : '';?> value="DM">DOMINICA</option>
	<option <?php echo ($iso_country=="DO") ? 'selected="selected"' : '';?> value="DO">DOMINICAN REPUBLIC</option>
	<option <?php echo ($iso_country=="EC") ? 'selected="selected"' : '';?> value="EC">ECUADOR</option>
	<option <?php echo ($iso_country=="EG") ? 'selected="selected"' : '';?> value="EG">EGYPT</option>
	<option <?php echo ($iso_country=="SV") ? 'selected="selected"' : '';?> value="SV">EL SALVADOR</option>
	<option <?php echo ($iso_country=="GQ") ? 'selected="selected"' : '';?> value="GQ">EQUATORIAL GUINEA</option>
	<option <?php echo ($iso_country=="ER") ? 'selected="selected"' : '';?> value="ER">ERITREA</option>
	<option <?php echo ($iso_country=="EE") ? 'selected="selected"' : '';?> value="EE">ESTONIA</option>
	<option <?php echo ($iso_country=="ET") ? 'selected="selected"' : '';?> value="ET">ETHIOPIA</option>
	<option <?php echo ($iso_country=="FK") ? 'selected="selected"' : '';?> value="FK">FALKLAND ISLANDS (MALVINAS)</option>
	<option <?php echo ($iso_country=="FO") ? 'selected="selected"' : '';?> value="FO">FAROE ISLANDS</option>
	<option <?php echo ($iso_country=="FJ") ? 'selected="selected"' : '';?> value="FJ">FIJI</option>
	<option <?php echo ($iso_country=="FI") ? 'selected="selected"' : '';?> value="FI">FINLAND</option>
	<option <?php echo ($iso_country=="FR") ? 'selected="selected"' : '';?> value="FR">FRANCE</option>
	<option <?php echo ($iso_country=="GF") ? 'selected="selected"' : '';?> value="GF">FRENCH GUIANA</option>
	<option <?php echo ($iso_country=="PF") ? 'selected="selected"' : '';?> value="PF">FRENCH POLYNESIA</option>
	<option <?php echo ($iso_country=="TF") ? 'selected="selected"' : '';?> value="TF">FRENCH SOUTHERN TERRITORIES</option>
	<option <?php echo ($iso_country=="GA") ? 'selected="selected"' : '';?> value="GA">GABON</option>
	<option <?php echo ($iso_country=="GM") ? 'selected="selected"' : '';?> value="GM">GAMBIA</option>
	<option <?php echo ($iso_country=="GE") ? 'selected="selected"' : '';?> value="GE">GEORGIA</option>
	<option <?php echo ($iso_country=="DE") ? 'selected="selected"' : '';?> value="DE">GERMANY</option>
	<option <?php echo ($iso_country=="GH") ? 'selected="selected"' : '';?> value="GH">GHANA</option>
	<option <?php echo ($iso_country=="GI") ? 'selected="selected"' : '';?> value="GI">GIBRALTAR</option>
	<option <?php echo ($iso_country=="GR") ? 'selected="selected"' : '';?> value="GR">GREECE</option>
	<option <?php echo ($iso_country=="GL") ? 'selected="selected"' : '';?> value="GL">GREENLAND</option>
	<option <?php echo ($iso_country=="GD") ? 'selected="selected"' : '';?> value="GD">GRENADA</option>
	<option <?php echo ($iso_country=="GP") ? 'selected="selected"' : '';?> value="GP">GUADELOUPE</option>
	<option <?php echo ($iso_country=="GU") ? 'selected="selected"' : '';?> value="GU">GUAM</option>
	<option <?php echo ($iso_country=="GT") ? 'selected="selected"' : '';?> value="GT">GUATEMALA</option>
	<option <?php echo ($iso_country=="GG") ? 'selected="selected"' : '';?> value="GG">GUERNSEY</option>
	<option <?php echo ($iso_country=="GN") ? 'selected="selected"' : '';?> value="GN">GUINEA</option>
	<option <?php echo ($iso_country=="GW") ? 'selected="selected"' : '';?> value="GW">GUINEA-BISSAU</option>
	<option <?php echo ($iso_country=="GY") ? 'selected="selected"' : '';?> value="GY">GUYANA</option>
	<option <?php echo ($iso_country=="HT") ? 'selected="selected"' : '';?> value="HT">HAITI</option>
	<option <?php echo ($iso_country=="HM") ? 'selected="selected"' : '';?> value="HM">HEARD ISLAND AND MCDONALD ISLANDS</option>
	<option <?php echo ($iso_country=="VA") ? 'selected="selected"' : '';?> value="VA">HOLY SEE (VATICAN CITY)</option>
	<option <?php echo ($iso_country=="HN") ? 'selected="selected"' : '';?> value="HN">HONDURAS</option>
	<option <?php echo ($iso_country=="HK") ? 'selected="selected"' : '';?> value="HK">HONG KONG</option>
	<option <?php echo ($iso_country=="HU") ? 'selected="selected"' : '';?> value="HU">HUNGARY</option>
	<option <?php echo ($iso_country=="IS") ? 'selected="selected"' : '';?> value="IS">ICELAND</option>
	<option <?php echo ($iso_country=="IN") ? 'selected="selected"' : '';?> value="IN">INDIA</option>
	<option <?php echo ($iso_country=="ID") ? 'selected="selected"' : '';?> value="ID">INDONESIA</option>
	<option <?php echo ($iso_country=="IR") ? 'selected="selected"' : '';?> value="IR">IRAN, ISLAMIC REPUBLIC OF</option>
	<option <?php echo ($iso_country=="IQ") ? 'selected="selected"' : '';?> value="IQ">IRAQ</option>
	<option <?php echo ($iso_country=="IE") ? 'selected="selected"' : '';?> value="IE">IRELAND</option>
	<option <?php echo ($iso_country=="IM") ? 'selected="selected"' : '';?> value="IM">ISLE OF MAN</option>
	<option <?php echo ($iso_country=="IL") ? 'selected="selected"' : '';?> value="IL">ISRAEL</option>
	<option <?php echo ($iso_country=="IT") ? 'selected="selected"' : '';?> value="IT">ITALY</option>
	<option <?php echo ($iso_country=="JM") ? 'selected="selected"' : '';?> value="JM">JAMAICA</option>
	<option <?php echo ($iso_country=="JP") ? 'selected="selected"' : '';?> value="JP">JAPAN</option>
	<option <?php echo ($iso_country=="JE") ? 'selected="selected"' : '';?> value="JE">JERSEY</option>
	<option <?php echo ($iso_country=="JO") ? 'selected="selected"' : '';?> value="JO">JORDAN</option>
	<option <?php echo ($iso_country=="KZ") ? 'selected="selected"' : '';?> value="KZ">KAZAKHSTAN</option>
	<option <?php echo ($iso_country=="KE") ? 'selected="selected"' : '';?> value="KE">KENYA</option>
	<option <?php echo ($iso_country=="KI") ? 'selected="selected"' : '';?> value="KI">KIRIBATI</option>
	<option <?php echo ($iso_country=="KP") ? 'selected="selected"' : '';?> value="KP">KOREA, DEMOCRATIC PEOPLE'S REPUBLIC OF</option>
	<option <?php echo ($iso_country=="KR") ? 'selected="selected"' : '';?> value="KR">KOREA, REPUBLIC OF</option>
	<option <?php echo ($iso_country=="KW") ? 'selected="selected"' : '';?> value="KW">KUWAIT</option>
	<option <?php echo ($iso_country=="KG") ? 'selected="selected"' : '';?> value="KG">KYRGYZSTAN</option>
	<option <?php echo ($iso_country=="LA") ? 'selected="selected"' : '';?> value="LA">LAO PEOPLE'S DEMOCRATIC REPUBLIC</option>
	<option <?php echo ($iso_country=="LV") ? 'selected="selected"' : '';?> value="LV">LATVIA</option>
	<option <?php echo ($iso_country=="LB") ? 'selected="selected"' : '';?> value="LB">LEBANON</option>
	<option <?php echo ($iso_country=="LS") ? 'selected="selected"' : '';?> value="LS">LESOTHO</option>
	<option <?php echo ($iso_country=="LR") ? 'selected="selected"' : '';?> value="LR">LIBERIA</option>
	<option <?php echo ($iso_country=="LY") ? 'selected="selected"' : '';?> value="LY">LIBYAN ARAB JAMAHIRIYA</option>
	<option <?php echo ($iso_country=="LI") ? 'selected="selected"' : '';?> value="LI">LIECHTENSTEIN</option>
	<option <?php echo ($iso_country=="LT") ? 'selected="selected"' : '';?> value="LT">LITHUANIA</option>
	<option <?php echo ($iso_country=="LU") ? 'selected="selected"' : '';?> value="LU">LUXEMBOURG</option>
	<option <?php echo ($iso_country=="MO") ? 'selected="selected"' : '';?> value="MO">MACAO</option>
	<option <?php echo ($iso_country=="MK") ? 'selected="selected"' : '';?> value="MK">MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF</option>
	<option <?php echo ($iso_country=="MG") ? 'selected="selected"' : '';?> value="MG">MADAGASCAR</option>
	<option <?php echo ($iso_country=="MW") ? 'selected="selected"' : '';?> value="MW">MALAWI</option>
	<option <?php echo ($iso_country=="MY") ? 'selected="selected"' : '';?> value="MY">MALAYSIA</option>
	<option <?php echo ($iso_country=="MV") ? 'selected="selected"' : '';?> value="MV">MALDIVES</option>
	<option <?php echo ($iso_country=="ML") ? 'selected="selected"' : '';?> value="ML">MALI</option>
	<option <?php echo ($iso_country=="MT") ? 'selected="selected"' : '';?> value="MT">MALTA</option>
	<option <?php echo ($iso_country=="MH") ? 'selected="selected"' : '';?> value="MH">MARSHALL ISLANDS</option>
	<option <?php echo ($iso_country=="MQ") ? 'selected="selected"' : '';?> value="MQ">MARTINIQUE</option>
	<option <?php echo ($iso_country=="MR") ? 'selected="selected"' : '';?> value="MR">MAURITANIA</option>
	<option <?php echo ($iso_country=="MU") ? 'selected="selected"' : '';?> value="MU">MAURITIUS</option>
	<option <?php echo ($iso_country=="YT") ? 'selected="selected"' : '';?> value="YT">MAYOTTE</option>
	<option <?php echo ($iso_country=="MX") ? 'selected="selected"' : '';?> value="MX">MEXICO</option>
	<option <?php echo ($iso_country=="FM") ? 'selected="selected"' : '';?> value="FM">MICRONESIA, FEDERATED STATES OF</option>
	<option <?php echo ($iso_country=="MD") ? 'selected="selected"' : '';?> value="MD">MOLDOVA, REPUBLIC OF</option>
	<option <?php echo ($iso_country=="MC") ? 'selected="selected"' : '';?> value="MC">MONACO</option>
	<option <?php echo ($iso_country=="MN") ? 'selected="selected"' : '';?> value="MN">MONGOLIA</option>
	<option <?php echo ($iso_country=="ME") ? 'selected="selected"' : '';?> value="ME">MONTENEGRO</option>
	<option <?php echo ($iso_country=="MS") ? 'selected="selected"' : '';?> value="MS">MONTSERRAT</option>
	<option <?php echo ($iso_country=="MA") ? 'selected="selected"' : '';?> value="MA">MOROCCO</option>
	<option <?php echo ($iso_country=="MZ") ? 'selected="selected"' : '';?> value="MZ">MOZAMBIQUE</option>
	<option <?php echo ($iso_country=="MM") ? 'selected="selected"' : '';?> value="MM">MYANMAR</option>
	<option <?php echo ($iso_country=="NA") ? 'selected="selected"' : '';?> value="NA">NAMIBIA</option>
	<option <?php echo ($iso_country=="NR") ? 'selected="selected"' : '';?> value="NR">NAURU</option>
	<option <?php echo ($iso_country=="NP") ? 'selected="selected"' : '';?> value="NP">NEPAL</option>
	<option <?php echo ($iso_country=="NL") ? 'selected="selected"' : '';?> value="NL">NETHERLANDS</option>
	<option <?php echo ($iso_country=="AN") ? 'selected="selected"' : '';?> value="AN">NETHERLANDS ANTILLES</option>
	<option <?php echo ($iso_country=="NC") ? 'selected="selected"' : '';?> value="NC">NEW CALEDONIA</option>
	<option <?php echo ($iso_country=="NZ") ? 'selected="selected"' : '';?> value="NZ">NEW ZEALAND</option>
	<option <?php echo ($iso_country=="NI") ? 'selected="selected"' : '';?> value="NI">NICARAGUA</option>
	<option <?php echo ($iso_country=="NE") ? 'selected="selected"' : '';?> value="NE">NIGER</option>
	<option <?php echo ($iso_country=="NG") ? 'selected="selected"' : '';?> value="NG">NIGERIA</option>
	<option <?php echo ($iso_country=="NU") ? 'selected="selected"' : '';?> value="NU">NIUE</option>
	<option <?php echo ($iso_country=="NF") ? 'selected="selected"' : '';?> value="NF">NORFOLK ISLAND</option>
	<option <?php echo ($iso_country=="MP") ? 'selected="selected"' : '';?> value="MP">NORTHERN MARIANA ISLANDS</option>
	<option <?php echo ($iso_country=="NO") ? 'selected="selected"' : '';?> value="NO">NORWAY</option>
	<option <?php echo ($iso_country=="OM") ? 'selected="selected"' : '';?> value="OM">OMAN</option>
	<option <?php echo ($iso_country=="PK") ? 'selected="selected"' : '';?> value="PK">PAKISTAN</option>
	<option <?php echo ($iso_country=="PW") ? 'selected="selected"' : '';?> value="PW">PALAU</option>
	<option <?php echo ($iso_country=="PS") ? 'selected="selected"' : '';?> value="PS">PALESTINIAN TERRITORY, OCCUPIED</option>
	<option <?php echo ($iso_country=="PA") ? 'selected="selected"' : '';?> value="PA">PANAMA</option>
	<option <?php echo ($iso_country=="PG") ? 'selected="selected"' : '';?> value="PG">PAPUA NEW GUINEA</option>
	<option <?php echo ($iso_country=="PY") ? 'selected="selected"' : '';?> value="PY">PARAGUAY</option>
	<option <?php echo ($iso_country=="PE") ? 'selected="selected"' : '';?> value="PE">PERU</option>
	<option <?php echo ($iso_country=="PH") ? 'selected="selected"' : '';?> value="PH">PHILIPPINES</option>
	<option <?php echo ($iso_country=="PN") ? 'selected="selected"' : '';?> value="PN">PITCAIRN</option>
	<option <?php echo ($iso_country=="PL") ? 'selected="selected"' : '';?> value="PL">POLAND</option>
	<option <?php echo ($iso_country=="PT") ? 'selected="selected"' : '';?> value="PT">PORTUGAL</option>
	<option <?php echo ($iso_country=="PR") ? 'selected="selected"' : '';?> value="PR">PUERTO RICO</option>
	<option <?php echo ($iso_country=="QA") ? 'selected="selected"' : '';?> value="QA">QATAR</option>
	<option <?php echo ($iso_country=="RE") ? 'selected="selected"' : '';?> value="RE">RÉUNION</option>
	<option <?php echo ($iso_country=="RO") ? 'selected="selected"' : '';?> value="RO">ROMANIA</option>
	<option <?php echo ($iso_country=="RU") ? 'selected="selected"' : '';?> value="RU">RUSSIAN FEDERATION</option>
	<option <?php echo ($iso_country=="RW") ? 'selected="selected"' : '';?> value="RW">RWANDA</option>
	<option <?php echo ($iso_country=="BL") ? 'selected="selected"' : '';?> value="BL">SAINT BARTHÉLEMY</option>
	<option <?php echo ($iso_country=="SH") ? 'selected="selected"' : '';?> value="SH">SAINT HELENA</option>
	<option <?php echo ($iso_country=="KN") ? 'selected="selected"' : '';?> value="KN">SAINT KITTS AND NEVIS</option>
	<option <?php echo ($iso_country=="LC") ? 'selected="selected"' : '';?> value="LC">SAINT LUCIA</option>
	<option <?php echo ($iso_country=="MF") ? 'selected="selected"' : '';?> value="MF">SAINT MARTIN</option>
	<option <?php echo ($iso_country=="PM") ? 'selected="selected"' : '';?> value="PM">SAINT PIERRE AND MIQUELON</option>
	<option <?php echo ($iso_country=="VC") ? 'selected="selected"' : '';?> value="VC">SAINT VINCENT AND THE GRENADINES</option>
	<option <?php echo ($iso_country=="WS") ? 'selected="selected"' : '';?> value="WS">SAMOA</option>
	<option <?php echo ($iso_country=="SM") ? 'selected="selected"' : '';?> value="SM">SAN MARINO</option>
	<option <?php echo ($iso_country=="ST") ? 'selected="selected"' : '';?> value="ST">SAO TOME AND PRINCIPE</option>
	<option <?php echo ($iso_country=="SA") ? 'selected="selected"' : '';?> value="SA">SAUDI ARABIA</option>
	<option <?php echo ($iso_country=="SN") ? 'selected="selected"' : '';?> value="SN">SENEGAL</option>
	<option <?php echo ($iso_country=="RS") ? 'selected="selected"' : '';?> value="RS">SERBIA</option>
	<option <?php echo ($iso_country=="SC") ? 'selected="selected"' : '';?> value="SC">SEYCHELLES</option>
	<option <?php echo ($iso_country=="SL") ? 'selected="selected"' : '';?> value="SL">SIERRA LEONE</option>
	<option <?php echo ($iso_country=="SG") ? 'selected="selected"' : '';?> value="SG">SINGAPORE</option>
	<option <?php echo ($iso_country=="SK") ? 'selected="selected"' : '';?> value="SK">SLOVAKIA</option>
	<option <?php echo ($iso_country=="SI") ? 'selected="selected"' : '';?> value="SI">SLOVENIA</option>
	<option <?php echo ($iso_country=="SB") ? 'selected="selected"' : '';?> value="SB">SOLOMON ISLANDS</option>
	<option <?php echo ($iso_country=="SO") ? 'selected="selected"' : '';?> value="SO">SOMALIA</option>
	<option <?php echo ($iso_country=="ZA") ? 'selected="selected"' : '';?> value="ZA">SOUTH AFRICA</option>
	<option <?php echo ($iso_country=="GS") ? 'selected="selected"' : '';?> value="GS">SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS</option>
	<option <?php echo ($iso_country=="ES") ? 'selected="selected"' : '';?> value="ES">SPAIN</option>
	<option <?php echo ($iso_country=="LK") ? 'selected="selected"' : '';?> value="LK">SRI LANKA</option>
	<option <?php echo ($iso_country=="SD") ? 'selected="selected"' : '';?> value="SD">SUDAN</option>
	<option <?php echo ($iso_country=="SR") ? 'selected="selected"' : '';?> value="SR">SURINAME</option>
	<option <?php echo ($iso_country=="SJ") ? 'selected="selected"' : '';?> value="SJ">SVALBARD AND JAN MAYEN</option>
	<option <?php echo ($iso_country=="SZ") ? 'selected="selected"' : '';?> value="SZ">SWAZILAND</option>
	<option <?php echo ($iso_country=="SE") ? 'selected="selected"' : '';?> value="SE">SWEDEN</option>
	<option <?php echo ($iso_country=="CH") ? 'selected="selected"' : '';?> value="CH">SWITZERLAND</option>
	<option <?php echo ($iso_country=="SY") ? 'selected="selected"' : '';?> value="SY">SYRIAN ARAB REPUBLIC</option>
	<option <?php echo ($iso_country=="TW") ? 'selected="selected"' : '';?> value="TW">TAIWAN, PROVINCE OF CHINA</option>
	<option <?php echo ($iso_country=="TJ") ? 'selected="selected"' : '';?> value="TJ">TAJIKISTAN</option>
	<option <?php echo ($iso_country=="TZ") ? 'selected="selected"' : '';?> value="TZ">TANZANIA, UNITED REPUBLIC OF</option>
	<option <?php echo ($iso_country=="TH") ? 'selected="selected"' : '';?> value="TH">THAILAND</option>
	<option <?php echo ($iso_country=="TL") ? 'selected="selected"' : '';?> value="TL">TIMOR-LESTE</option>
	<option <?php echo ($iso_country=="TG") ? 'selected="selected"' : '';?> value="TG">TOGO</option>
	<option <?php echo ($iso_country=="TK") ? 'selected="selected"' : '';?> value="TK">TOKELAU</option>
	<option <?php echo ($iso_country=="TO") ? 'selected="selected"' : '';?> value="TO">TONGA</option>
	<option <?php echo ($iso_country=="TT") ? 'selected="selected"' : '';?> value="TT">TRINIDAD AND TOBAGO</option>
	<option <?php echo ($iso_country=="TN") ? 'selected="selected"' : '';?> value="TN">TUNISIA</option>
	<option <?php echo ($iso_country=="TR") ? 'selected="selected"' : '';?> value="TR">TURKEY</option>
	<option <?php echo ($iso_country=="TM") ? 'selected="selected"' : '';?> value="TM">TURKMENISTAN</option>
	<option <?php echo ($iso_country=="TC") ? 'selected="selected"' : '';?> value="TC">TURKS AND CAICOS ISLANDS</option>
	<option <?php echo ($iso_country=="TV") ? 'selected="selected"' : '';?> value="TV">TUVALU</option>
	<option <?php echo ($iso_country=="UG") ? 'selected="selected"' : '';?> value="UG">UGANDA</option>
	<option <?php echo ($iso_country=="UA") ? 'selected="selected"' : '';?> value="UA">UKRAINE</option>
	<option <?php echo ($iso_country=="AE") ? 'selected="selected"' : '';?> value="AE">UNITED ARAB EMIRATES</option>
	<option <?php echo ($iso_country=="GB") ? 'selected="selected"' : '';?> value="GB">UNITED KINGDOM</option>
	<option <?php echo ($iso_country=="US") ? 'selected="selected"' : '';?> value="US">UNITED STATES</option>
	<option <?php echo ($iso_country=="UM") ? 'selected="selected"' : '';?> value="U,">UNITED STATES MINOR OUTLYING ISLANDS</option>
	<option <?php echo ($iso_country=="UY") ? 'selected="selected"' : '';?> value="UY">URUGUAY</option>
	<option <?php echo ($iso_country=="UZ") ? 'selected="selected"' : '';?> value="UZ">UZBEKISTAN</option>
	<option <?php echo ($iso_country=="VU") ? 'selected="selected"' : '';?> value="VU">VANUATU</option>
	<option <?php echo ($iso_country=="VE") ? 'selected="selected"' : '';?> value="VE">VENEZUELA, BOLIVARIAN REPUBLIC OF</option>
	<option <?php echo ($iso_country=="VN") ? 'selected="selected"' : '';?> value="VN">VIET NAM</option>
	<option <?php echo ($iso_country=="VG") ? 'selected="selected"' : '';?> value="VG">VIRGIN ISLANDS, BRITISH</option>
	<option <?php echo ($iso_country=="VI") ? 'selected="selected"' : '';?> value="VI">VIRGIN ISLANDS, U.S.</option>
	<option <?php echo ($iso_country=="WF") ? 'selected="selected"' : '';?> value="WF">WALLIS AND FUTUNA</option>
	<option <?php echo ($iso_country=="EH") ? 'selected="selected"' : '';?> value="EH">WESTERN SAHARA</option>
	<option <?php echo ($iso_country=="YE") ? 'selected="selected"' : '';?> value="YE">YEMEN</option>
	<option <?php echo ($iso_country=="ZM") ? 'selected="selected"' : '';?> value="ZM">ZAMBIA</option>
	<option <?php echo ($iso_country=="ZW") ? 'selected="selected"' : '';?> value="ZW">ZIMBABWE</option>
</select>