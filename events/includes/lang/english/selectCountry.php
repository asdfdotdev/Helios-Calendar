<?php
/*
	This list uses ISO 3166-1-alpha-2 Country Codes
	http://www.iso.org/iso/country_codes/iso_3166_code_lists/english_country_names_and_code_elements.htm
 */?>
<select <?php if(isset($countryDisabled)){echo 'disabled="disabled"';}?> name="selCountry" id="selCountry">
	<option value="">&nbsp;</option>
	<option <?php if(isset($country) && ($country=="AF")){echo 'selected="selected"';}?> value="AF">AFGHANISTAN</option>
	<option <?php if(isset($country) && ($country=="AX")){echo 'selected="selected"';}?> value="AX">&#197;LAND ISLANDS</option>
	<option <?php if(isset($country) && ($country=="AL")){echo 'selected="selected"';}?> value="AL">ALBANIA</option>
	<option <?php if(isset($country) && ($country=="DZ")){echo 'selected="selected"';}?> value="DZ">ALGERIA</option>
	<option <?php if(isset($country) && ($country=="AS")){echo 'selected="selected"';}?> value="AS">AMERICAN SAMOA</option>
	<option <?php if(isset($country) && ($country=="AD")){echo 'selected="selected"';}?> value="AD">ANDORRA</option>
	<option <?php if(isset($country) && ($country=="AO")){echo 'selected="selected"';}?> value="AO">ANGOLA</option>
	<option <?php if(isset($country) && ($country=="AI")){echo 'selected="selected"';}?> value="AI">ANGUILLA</option>
	<option <?php if(isset($country) && ($country=="AQ")){echo 'selected="selected"';}?> value="AQ">ANTARCTICA</option>
	<option <?php if(isset($country) && ($country=="AG")){echo 'selected="selected"';}?> value="AG">ANTIGUA AND BARBUDA</option>
	<option <?php if(isset($country) && ($country=="AR")){echo 'selected="selected"';}?> value="AR">ARGENTINA</option>
	<option <?php if(isset($country) && ($country=="AM")){echo 'selected="selected"';}?> value="AM">ARMENIA</option>
	<option <?php if(isset($country) && ($country=="AW")){echo 'selected="selected"';}?> value="AW">ARUBA</option>
	<option <?php if(isset($country) && ($country=="AU")){echo 'selected="selected"';}?> value="AU">AUSTRALIA</option>
	<option <?php if(isset($country) && ($country=="AT")){echo 'selected="selected"';}?> value="AT">AUSTRIA</option>
	<option <?php if(isset($country) && ($country=="AZ")){echo 'selected="selected"';}?> value="AZ">AZERBAIJAN</option>
	<option <?php if(isset($country) && ($country=="BS")){echo 'selected="selected"';}?> value="BS">BAHAMAS</option>
	<option <?php if(isset($country) && ($country=="BH")){echo 'selected="selected"';}?> value="BH">BAHRAIN</option>
	<option <?php if(isset($country) && ($country=="BD")){echo 'selected="selected"';}?> value="BD">BANGLADESH</option>
	<option <?php if(isset($country) && ($country=="BB")){echo 'selected="selected"';}?> value="BB">BARBADOS</option>
	<option <?php if(isset($country) && ($country=="BY")){echo 'selected="selected"';}?> value="BY">BELARUS</option>
	<option <?php if(isset($country) && ($country=="BE")){echo 'selected="selected"';}?> value="BE">BELGIUM</option>
	<option <?php if(isset($country) && ($country=="BZ")){echo 'selected="selected"';}?> value="BZ">BELIZE</option>
	<option <?php if(isset($country) && ($country=="BJ")){echo 'selected="selected"';}?> value="BJ">BENIN</option>
	<option <?php if(isset($country) && ($country=="BM")){echo 'selected="selected"';}?> value="BM">BERMUDA</option>
	<option <?php if(isset($country) && ($country=="BT")){echo 'selected="selected"';}?> value="BT">BHUTAN</option>
	<option <?php if(isset($country) && ($country=="BO")){echo 'selected="selected"';}?> value="BO">BOLIVIA, PLURINATIONAL STATE OF</option>
	<option <?php if(isset($country) && ($country=="BA")){echo 'selected="selected"';}?> value="BA">BOSNIA AND HERZEGOVINA</option>
	<option <?php if(isset($country) && ($country=="BW")){echo 'selected="selected"';}?> value="BW">BOTSWANA</option>
	<option <?php if(isset($country) && ($country=="BV")){echo 'selected="selected"';}?> value="BV">BOUVET ISLAND</option>
	<option <?php if(isset($country) && ($country=="BR")){echo 'selected="selected"';}?> value="BR">BRAZIL</option>
	<option <?php if(isset($country) && ($country=="IO")){echo 'selected="selected"';}?> value="IO">BRITISH INDIAN OCEAN TERRITORY</option>
	<option <?php if(isset($country) && ($country=="BN")){echo 'selected="selected"';}?> value="BN">BRUNEI DARUSSALAM</option>
	<option <?php if(isset($country) && ($country=="BG")){echo 'selected="selected"';}?> value="BG">BULGARIA</option>
	<option <?php if(isset($country) && ($country=="BF")){echo 'selected="selected"';}?> value="BF">BURKINA FASO</option>
	<option <?php if(isset($country) && ($country=="BI")){echo 'selected="selected"';}?> value="BI">BURUNDI</option>
	<option <?php if(isset($country) && ($country=="KH")){echo 'selected="selected"';}?> value="KH">CAMBODIA</option>
	<option <?php if(isset($country) && ($country=="CM")){echo 'selected="selected"';}?> value="CM">CAMEROON</option>
	<option <?php if(isset($country) && ($country=="CA")){echo 'selected="selected"';}?> value="CA">CANADA</option>
	<option <?php if(isset($country) && ($country=="CV")){echo 'selected="selected"';}?> value="CV">CAPE VERDE</option>
	<option <?php if(isset($country) && ($country=="KY")){echo 'selected="selected"';}?> value="KY">CAYMAN ISLANDS</option>
	<option <?php if(isset($country) && ($country=="CF")){echo 'selected="selected"';}?> value="CF">CENTRAL AFRICAN REPUBLIC</option>
	<option <?php if(isset($country) && ($country=="TD")){echo 'selected="selected"';}?> value="TD">CHAD</option>
	<option <?php if(isset($country) && ($country=="CL")){echo 'selected="selected"';}?> value="CL">CHILE</option>
	<option <?php if(isset($country) && ($country=="CN")){echo 'selected="selected"';}?> value="CN">CHINA</option>
	<option <?php if(isset($country) && ($country=="CX")){echo 'selected="selected"';}?> value="CX">CHRISTMAS ISLAND</option>
	<option <?php if(isset($country) && ($country=="CC")){echo 'selected="selected"';}?> value="CC">COCOS (KEELING) ISLANDS</option>
	<option <?php if(isset($country) && ($country=="CO")){echo 'selected="selected"';}?> value="CO">COLOMBIA</option>
	<option <?php if(isset($country) && ($country=="KM")){echo 'selected="selected"';}?> value="KM">COMOROS</option>
	<option <?php if(isset($country) && ($country=="CG")){echo 'selected="selected"';}?> value="CG">CONGO</option>
	<option <?php if(isset($country) && ($country=="CD")){echo 'selected="selected"';}?> value="CD">CONGO, THE DEMOCRATIC REPUBLIC OF THE</option>
	<option <?php if(isset($country) && ($country=="CK")){echo 'selected="selected"';}?> value="CK">COOK ISLANDS</option>
	<option <?php if(isset($country) && ($country=="CR")){echo 'selected="selected"';}?> value="CR">COSTA RICA</option>
	<option <?php if(isset($country) && ($country=="CI")){echo 'selected="selected"';}?> value="CI">C&#212;TE D'IVOIRE</option>
	<option <?php if(isset($country) && ($country=="HR")){echo 'selected="selected"';}?> value="HR">CROATIA</option>
	<option <?php if(isset($country) && ($country=="CU")){echo 'selected="selected"';}?> value="CU">CUBA</option>
	<option <?php if(isset($country) && ($country=="CY")){echo 'selected="selected"';}?> value="CY">CYPRUS</option>
	<option <?php if(isset($country) && ($country=="CZ")){echo 'selected="selected"';}?> value="CZ">CZECH REPUBLIC</option>
	<option <?php if(isset($country) && ($country=="DK")){echo 'selected="selected"';}?> value="DK">DENMARK</option>
	<option <?php if(isset($country) && ($country=="DJ")){echo 'selected="selected"';}?> value="DJ">DJIBOUTI</option>
	<option <?php if(isset($country) && ($country=="DM")){echo 'selected="selected"';}?> value="DM">DOMINICA</option>
	<option <?php if(isset($country) && ($country=="DO")){echo 'selected="selected"';}?> value="DO">DOMINICAN REPUBLIC</option>
	<option <?php if(isset($country) && ($country=="EC")){echo 'selected="selected"';}?> value="EC">ECUADOR</option>
	<option <?php if(isset($country) && ($country=="EG")){echo 'selected="selected"';}?> value="EG">EGYPT</option>
	<option <?php if(isset($country) && ($country=="SV")){echo 'selected="selected"';}?> value="SV">EL SALVADOR</option>
	<option <?php if(isset($country) && ($country=="GQ")){echo 'selected="selected"';}?> value="GQ">EQUATORIAL GUINEA</option>
	<option <?php if(isset($country) && ($country=="ER")){echo 'selected="selected"';}?> value="ER">ERITREA</option>
	<option <?php if(isset($country) && ($country=="EE")){echo 'selected="selected"';}?> value="EE">ESTONIA</option>
	<option <?php if(isset($country) && ($country=="ET")){echo 'selected="selected"';}?> value="ET">ETHIOPIA</option>
	<option <?php if(isset($country) && ($country=="FK")){echo 'selected="selected"';}?> value="FK">FALKLAND ISLANDS (MALVINAS)</option>
	<option <?php if(isset($country) && ($country=="FO")){echo 'selected="selected"';}?> value="FO">FAROE ISLANDS</option>
	<option <?php if(isset($country) && ($country=="FJ")){echo 'selected="selected"';}?> value="FJ">FIJI</option>
	<option <?php if(isset($country) && ($country=="FI")){echo 'selected="selected"';}?> value="FI">FINLAND</option>
	<option <?php if(isset($country) && ($country=="FR")){echo 'selected="selected"';}?> value="FR">FRANCE</option>
	<option <?php if(isset($country) && ($country=="GF")){echo 'selected="selected"';}?> value="GF">FRENCH GUIANA</option>
	<option <?php if(isset($country) && ($country=="PF")){echo 'selected="selected"';}?> value="PF">FRENCH POLYNESIA</option>
	<option <?php if(isset($country) && ($country=="TF")){echo 'selected="selected"';}?> value="TF">FRENCH SOUTHERN TERRITORIES</option>
	<option <?php if(isset($country) && ($country=="GA")){echo 'selected="selected"';}?> value="GA">GABON</option>
	<option <?php if(isset($country) && ($country=="GM")){echo 'selected="selected"';}?> value="GM">GAMBIA</option>
	<option <?php if(isset($country) && ($country=="GE")){echo 'selected="selected"';}?> value="GE">GEORGIA</option>
	<option <?php if(isset($country) && ($country=="DE")){echo 'selected="selected"';}?> value="DE">GERMANY</option>
	<option <?php if(isset($country) && ($country=="GH")){echo 'selected="selected"';}?> value="GH">GHANA</option>
	<option <?php if(isset($country) && ($country=="GI")){echo 'selected="selected"';}?> value="GI">GIBRALTAR</option>
	<option <?php if(isset($country) && ($country=="GR")){echo 'selected="selected"';}?> value="GR">GREECE</option>
	<option <?php if(isset($country) && ($country=="GL")){echo 'selected="selected"';}?> value="GL">GREENLAND</option>
	<option <?php if(isset($country) && ($country=="GD")){echo 'selected="selected"';}?> value="GD">GRENADA</option>
	<option <?php if(isset($country) && ($country=="GP")){echo 'selected="selected"';}?> value="GP">GUADELOUPE</option>
	<option <?php if(isset($country) && ($country=="GU")){echo 'selected="selected"';}?> value="GU">GUAM</option>
	<option <?php if(isset($country) && ($country=="GT")){echo 'selected="selected"';}?> value="GT">GUATEMALA</option>
	<option <?php if(isset($country) && ($country=="GG")){echo 'selected="selected"';}?> value="GG">GUERNSEY</option>
	<option <?php if(isset($country) && ($country=="GN")){echo 'selected="selected"';}?> value="GN">GUINEA</option>
	<option <?php if(isset($country) && ($country=="GW")){echo 'selected="selected"';}?> value="GW">GUINEA-BISSAU</option>
	<option <?php if(isset($country) && ($country=="GY")){echo 'selected="selected"';}?> value="GY">GUYANA</option>
	<option <?php if(isset($country) && ($country=="HT")){echo 'selected="selected"';}?> value="HT">HAITI</option>
	<option <?php if(isset($country) && ($country=="HM")){echo 'selected="selected"';}?> value="HM">HEARD ISLAND AND MCDONALD ISLANDS</option>
	<option <?php if(isset($country) && ($country=="VA")){echo 'selected="selected"';}?> value="VA">HOLY SEE (VATICAN CITY)</option>
	<option <?php if(isset($country) && ($country=="HN")){echo 'selected="selected"';}?> value="HN">HONDURAS</option>
	<option <?php if(isset($country) && ($country=="HK")){echo 'selected="selected"';}?> value="HK">HONG KONG</option>
	<option <?php if(isset($country) && ($country=="HU")){echo 'selected="selected"';}?> value="HU">HUNGARY</option>
	<option <?php if(isset($country) && ($country=="IS")){echo 'selected="selected"';}?> value="IS">ICELAND</option>
	<option <?php if(isset($country) && ($country=="IN")){echo 'selected="selected"';}?> value="IN">INDIA</option>
	<option <?php if(isset($country) && ($country=="ID")){echo 'selected="selected"';}?> value="ID">INDONESIA</option>
	<option <?php if(isset($country) && ($country=="IR")){echo 'selected="selected"';}?> value="IR">IRAN, ISLAMIC REPUBLIC OF</option>
	<option <?php if(isset($country) && ($country=="IQ")){echo 'selected="selected"';}?> value="IQ">IRAQ</option>
	<option <?php if(isset($country) && ($country=="IE")){echo 'selected="selected"';}?> value="IE">IRELAND</option>
	<option <?php if(isset($country) && ($country=="IM")){echo 'selected="selected"';}?> value="IM">ISLE OF MAN</option>
	<option <?php if(isset($country) && ($country=="IL")){echo 'selected="selected"';}?> value="IL">ISRAEL</option>
	<option <?php if(isset($country) && ($country=="IT")){echo 'selected="selected"';}?> value="IT">ITALY</option>
	<option <?php if(isset($country) && ($country=="JM")){echo 'selected="selected"';}?> value="JM">JAMAICA</option>
	<option <?php if(isset($country) && ($country=="JP")){echo 'selected="selected"';}?> value="JP">JAPAN</option>
	<option <?php if(isset($country) && ($country=="JE")){echo 'selected="selected"';}?> value="JE">JERSEY</option>
	<option <?php if(isset($country) && ($country=="JO")){echo 'selected="selected"';}?> value="JO">JORDAN</option>
	<option <?php if(isset($country) && ($country=="KZ")){echo 'selected="selected"';}?> value="KZ">KAZAKHSTAN</option>
	<option <?php if(isset($country) && ($country=="KE")){echo 'selected="selected"';}?> value="KE">KENYA</option>
	<option <?php if(isset($country) && ($country=="KI")){echo 'selected="selected"';}?> value="KI">KIRIBATI</option>
	<option <?php if(isset($country) && ($country=="KP")){echo 'selected="selected"';}?> value="KP">KOREA, DEMOCRATIC PEOPLE'S REPUBLIC OF</option>
	<option <?php if(isset($country) && ($country=="KR")){echo 'selected="selected"';}?> value="KR">KOREA, REPUBLIC OF</option>
	<option <?php if(isset($country) && ($country=="KW")){echo 'selected="selected"';}?> value="KW">KUWAIT</option>
	<option <?php if(isset($country) && ($country=="KG")){echo 'selected="selected"';}?> value="KG">KYRGYZSTAN</option>
	<option <?php if(isset($country) && ($country=="LA")){echo 'selected="selected"';}?> value="LA">LAO PEOPLE'S DEMOCRATIC REPUBLIC</option>
	<option <?php if(isset($country) && ($country=="LV")){echo 'selected="selected"';}?> value="LV">LATVIA</option>
	<option <?php if(isset($country) && ($country=="LB")){echo 'selected="selected"';}?> value="LB">LEBANON</option>
	<option <?php if(isset($country) && ($country=="LS")){echo 'selected="selected"';}?> value="LS">LESOTHO</option>
	<option <?php if(isset($country) && ($country=="LR")){echo 'selected="selected"';}?> value="LR">LIBERIA</option>
	<option <?php if(isset($country) && ($country=="LY")){echo 'selected="selected"';}?> value="LY">LIBYAN ARAB JAMAHIRIYA</option>
	<option <?php if(isset($country) && ($country=="LI")){echo 'selected="selected"';}?> value="LI">LIECHTENSTEIN</option>
	<option <?php if(isset($country) && ($country=="LT")){echo 'selected="selected"';}?> value="LT">LITHUANIA</option>
	<option <?php if(isset($country) && ($country=="LU")){echo 'selected="selected"';}?> value="LU">LUXEMBOURG</option>
	<option <?php if(isset($country) && ($country=="MO")){echo 'selected="selected"';}?> value="MO">MACAO</option>
	<option <?php if(isset($country) && ($country=="MK")){echo 'selected="selected"';}?> value="MK">MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF</option>
	<option <?php if(isset($country) && ($country=="MG")){echo 'selected="selected"';}?> value="MG">MADAGASCAR</option>
	<option <?php if(isset($country) && ($country=="MW")){echo 'selected="selected"';}?> value="MW">MALAWI</option>
	<option <?php if(isset($country) && ($country=="MY")){echo 'selected="selected"';}?> value="MY">MALAYSIA</option>
	<option <?php if(isset($country) && ($country=="MV")){echo 'selected="selected"';}?> value="MV">MALDIVES</option>
	<option <?php if(isset($country) && ($country=="ML")){echo 'selected="selected"';}?> value="ML">MALI</option>
	<option <?php if(isset($country) && ($country=="MT")){echo 'selected="selected"';}?> value="MT">MALTA</option>
	<option <?php if(isset($country) && ($country=="MH")){echo 'selected="selected"';}?> value="MH">MARSHALL ISLANDS</option>
	<option <?php if(isset($country) && ($country=="MQ")){echo 'selected="selected"';}?> value="MQ">MARTINIQUE</option>
	<option <?php if(isset($country) && ($country=="MR")){echo 'selected="selected"';}?> value="MR">MAURITANIA</option>
	<option <?php if(isset($country) && ($country=="MU")){echo 'selected="selected"';}?> value="MU">MAURITIUS</option>
	<option <?php if(isset($country) && ($country=="YT")){echo 'selected="selected"';}?> value="YT">MAYOTTE</option>
	<option <?php if(isset($country) && ($country=="MX")){echo 'selected="selected"';}?> value="MX">MEXICO</option>
	<option <?php if(isset($country) && ($country=="FM")){echo 'selected="selected"';}?> value="FM">MICRONESIA, FEDERATED STATES OF</option>
	<option <?php if(isset($country) && ($country=="MD")){echo 'selected="selected"';}?> value="MD">MOLDOVA, REPUBLIC OF</option>
	<option <?php if(isset($country) && ($country=="MC")){echo 'selected="selected"';}?> value="MC">MONACO</option>
	<option <?php if(isset($country) && ($country=="MN")){echo 'selected="selected"';}?> value="MN">MONGOLIA</option>
	<option <?php if(isset($country) && ($country=="ME")){echo 'selected="selected"';}?> value="ME">MONTENEGRO</option>
	<option <?php if(isset($country) && ($country=="MS")){echo 'selected="selected"';}?> value="MS">MONTSERRAT</option>
	<option <?php if(isset($country) && ($country=="MA")){echo 'selected="selected"';}?> value="MA">MOROCCO</option>
	<option <?php if(isset($country) && ($country=="MZ")){echo 'selected="selected"';}?> value="MZ">MOZAMBIQUE</option>
	<option <?php if(isset($country) && ($country=="MM")){echo 'selected="selected"';}?> value="MM">MYANMAR</option>
	<option <?php if(isset($country) && ($country=="NA")){echo 'selected="selected"';}?> value="NA">NAMIBIA</option>
	<option <?php if(isset($country) && ($country=="NR")){echo 'selected="selected"';}?> value="NR">NAURU</option>
	<option <?php if(isset($country) && ($country=="NP")){echo 'selected="selected"';}?> value="NP">NEPAL</option>
	<option <?php if(isset($country) && ($country=="NL")){echo 'selected="selected"';}?> value="NL">NETHERLANDS</option>
	<option <?php if(isset($country) && ($country=="AN")){echo 'selected="selected"';}?> value="AN">NETHERLANDS ANTILLES</option>
	<option <?php if(isset($country) && ($country=="NC")){echo 'selected="selected"';}?> value="NC">NEW CALEDONIA</option>
	<option <?php if(isset($country) && ($country=="NZ")){echo 'selected="selected"';}?> value="NZ">NEW ZEALAND</option>
	<option <?php if(isset($country) && ($country=="NI")){echo 'selected="selected"';}?> value="NI">NICARAGUA</option>
	<option <?php if(isset($country) && ($country=="NE")){echo 'selected="selected"';}?> value="NE">NIGER</option>
	<option <?php if(isset($country) && ($country=="NG")){echo 'selected="selected"';}?> value="NG">NIGERIA</option>
	<option <?php if(isset($country) && ($country=="NU")){echo 'selected="selected"';}?> value="NU">NIUE</option>
	<option <?php if(isset($country) && ($country=="NF")){echo 'selected="selected"';}?> value="NF">NORFOLK ISLAND</option>
	<option <?php if(isset($country) && ($country=="MP")){echo 'selected="selected"';}?> value="MP">NORTHERN MARIANA ISLANDS</option>
	<option <?php if(isset($country) && ($country=="NO")){echo 'selected="selected"';}?> value="NO">NORWAY</option>
	<option <?php if(isset($country) && ($country=="OM")){echo 'selected="selected"';}?> value="OM">OMAN</option>
	<option <?php if(isset($country) && ($country=="PK")){echo 'selected="selected"';}?> value="PK">PAKISTAN</option>
	<option <?php if(isset($country) && ($country=="PW")){echo 'selected="selected"';}?> value="PW">PALAU</option>
	<option <?php if(isset($country) && ($country=="PS")){echo 'selected="selected"';}?> value="PS">PALESTINIAN TERRITORY, OCCUPIED</option>
	<option <?php if(isset($country) && ($country=="PA")){echo 'selected="selected"';}?> value="PA">PANAMA</option>
	<option <?php if(isset($country) && ($country=="PG")){echo 'selected="selected"';}?> value="PG">PAPUA NEW GUINEA</option>
	<option <?php if(isset($country) && ($country=="PY")){echo 'selected="selected"';}?> value="PY">PARAGUAY</option>
	<option <?php if(isset($country) && ($country=="PE")){echo 'selected="selected"';}?> value="PE">PERU</option>
	<option <?php if(isset($country) && ($country=="PH")){echo 'selected="selected"';}?> value="PH">PHILIPPINES</option>
	<option <?php if(isset($country) && ($country=="PN")){echo 'selected="selected"';}?> value="PN">PITCAIRN</option>
	<option <?php if(isset($country) && ($country=="PL")){echo 'selected="selected"';}?> value="PL">POLAND</option>
	<option <?php if(isset($country) && ($country=="PT")){echo 'selected="selected"';}?> value="PT">PORTUGAL</option>
	<option <?php if(isset($country) && ($country=="PR")){echo 'selected="selected"';}?> value="PR">PUERTO RICO</option>
	<option <?php if(isset($country) && ($country=="QA")){echo 'selected="selected"';}?> value="QA">QATAR</option>
	<option <?php if(isset($country) && ($country=="RE")){echo 'selected="selected"';}?> value="RE">RÉUNION</option>
	<option <?php if(isset($country) && ($country=="RO")){echo 'selected="selected"';}?> value="RO">ROMANIA</option>
	<option <?php if(isset($country) && ($country=="RU")){echo 'selected="selected"';}?> value="RU">RUSSIAN FEDERATION</option>
	<option <?php if(isset($country) && ($country=="RW")){echo 'selected="selected"';}?> value="RW">RWANDA</option>
	<option <?php if(isset($country) && ($country=="BL")){echo 'selected="selected"';}?> value="BL">SAINT BARTHÉLEMY</option>
	<option <?php if(isset($country) && ($country=="SH")){echo 'selected="selected"';}?> value="SH">SAINT HELENA</option>
	<option <?php if(isset($country) && ($country=="KN")){echo 'selected="selected"';}?> value="KN">SAINT KITTS AND NEVIS</option>
	<option <?php if(isset($country) && ($country=="LC")){echo 'selected="selected"';}?> value="LC">SAINT LUCIA</option>
	<option <?php if(isset($country) && ($country=="MF")){echo 'selected="selected"';}?> value="MF">SAINT MARTIN</option>
	<option <?php if(isset($country) && ($country=="PM")){echo 'selected="selected"';}?> value="PM">SAINT PIERRE AND MIQUELON</option>
	<option <?php if(isset($country) && ($country=="VC")){echo 'selected="selected"';}?> value="VC">SAINT VINCENT AND THE GRENADINES</option>
	<option <?php if(isset($country) && ($country=="WS")){echo 'selected="selected"';}?> value="WS">SAMOA</option>
	<option <?php if(isset($country) && ($country=="SM")){echo 'selected="selected"';}?> value="SM">SAN MARINO</option>
	<option <?php if(isset($country) && ($country=="ST")){echo 'selected="selected"';}?> value="ST">SAO TOME AND PRINCIPE</option>
	<option <?php if(isset($country) && ($country=="SA")){echo 'selected="selected"';}?> value="SA">SAUDI ARABIA</option>
	<option <?php if(isset($country) && ($country=="SN")){echo 'selected="selected"';}?> value="SN">SENEGAL</option>
	<option <?php if(isset($country) && ($country=="RS")){echo 'selected="selected"';}?> value="RS">SERBIA</option>
	<option <?php if(isset($country) && ($country=="SC")){echo 'selected="selected"';}?> value="SC">SEYCHELLES</option>
	<option <?php if(isset($country) && ($country=="SL")){echo 'selected="selected"';}?> value="SL">SIERRA LEONE</option>
	<option <?php if(isset($country) && ($country=="SG")){echo 'selected="selected"';}?> value="SG">SINGAPORE</option>
	<option <?php if(isset($country) && ($country=="SK")){echo 'selected="selected"';}?> value="SK">SLOVAKIA</option>
	<option <?php if(isset($country) && ($country=="SI")){echo 'selected="selected"';}?> value="SI">SLOVENIA</option>
	<option <?php if(isset($country) && ($country=="SB")){echo 'selected="selected"';}?> value="SB">SOLOMON ISLANDS</option>
	<option <?php if(isset($country) && ($country=="SO")){echo 'selected="selected"';}?> value="SO">SOMALIA</option>
	<option <?php if(isset($country) && ($country=="ZA")){echo 'selected="selected"';}?> value="ZA">SOUTH AFRICA</option>
	<option <?php if(isset($country) && ($country=="GS")){echo 'selected="selected"';}?> value="GS">SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS</option>
	<option <?php if(isset($country) && ($country=="ES")){echo 'selected="selected"';}?> value="ES">SPAIN</option>
	<option <?php if(isset($country) && ($country=="LK")){echo 'selected="selected"';}?> value="LK">SRI LANKA</option>
	<option <?php if(isset($country) && ($country=="SD")){echo 'selected="selected"';}?> value="SD">SUDAN</option>
	<option <?php if(isset($country) && ($country=="SR")){echo 'selected="selected"';}?> value="SR">SURINAME</option>
	<option <?php if(isset($country) && ($country=="SJ")){echo 'selected="selected"';}?> value="SJ">SVALBARD AND JAN MAYEN</option>
	<option <?php if(isset($country) && ($country=="SZ")){echo 'selected="selected"';}?> value="SZ">SWAZILAND</option>
	<option <?php if(isset($country) && ($country=="SE")){echo 'selected="selected"';}?> value="SE">SWEDEN</option>
	<option <?php if(isset($country) && ($country=="CH")){echo 'selected="selected"';}?> value="CH">SWITZERLAND</option>
	<option <?php if(isset($country) && ($country=="SY")){echo 'selected="selected"';}?> value="SY">SYRIAN ARAB REPUBLIC</option>
	<option <?php if(isset($country) && ($country=="TW")){echo 'selected="selected"';}?> value="TW">TAIWAN, PROVINCE OF CHINA</option>
	<option <?php if(isset($country) && ($country=="TJ")){echo 'selected="selected"';}?> value="TJ">TAJIKISTAN</option>
	<option <?php if(isset($country) && ($country=="TZ")){echo 'selected="selected"';}?> value="TZ">TANZANIA, UNITED REPUBLIC OF</option>
	<option <?php if(isset($country) && ($country=="TH")){echo 'selected="selected"';}?> value="TH">THAILAND</option>
	<option <?php if(isset($country) && ($country=="TL")){echo 'selected="selected"';}?> value="TL">TIMOR-LESTE</option>
	<option <?php if(isset($country) && ($country=="TG")){echo 'selected="selected"';}?> value="TG">TOGO</option>
	<option <?php if(isset($country) && ($country=="TK")){echo 'selected="selected"';}?> value="TK">TOKELAU</option>
	<option <?php if(isset($country) && ($country=="TO")){echo 'selected="selected"';}?> value="TO">TONGA</option>
	<option <?php if(isset($country) && ($country=="TT")){echo 'selected="selected"';}?> value="TT">TRINIDAD AND TOBAGO</option>
	<option <?php if(isset($country) && ($country=="TN")){echo 'selected="selected"';}?> value="TN">TUNISIA</option>
	<option <?php if(isset($country) && ($country=="TR")){echo 'selected="selected"';}?> value="TR">TURKEY</option>
	<option <?php if(isset($country) && ($country=="TM")){echo 'selected="selected"';}?> value="TM">TURKMENISTAN</option>
	<option <?php if(isset($country) && ($country=="TC")){echo 'selected="selected"';}?> value="TC">TURKS AND CAICOS ISLANDS</option>
	<option <?php if(isset($country) && ($country=="TV")){echo 'selected="selected"';}?> value="TV">TUVALU</option>
	<option <?php if(isset($country) && ($country=="UG")){echo 'selected="selected"';}?> value="UG">UGANDA</option>
	<option <?php if(isset($country) && ($country=="UA")){echo 'selected="selected"';}?> value="UA">UKRAINE</option>
	<option <?php if(isset($country) && ($country=="AE")){echo 'selected="selected"';}?> value="AE">UNITED ARAB EMIRATES</option>
	<option <?php if(isset($country) && ($country=="GB")){echo 'selected="selected"';}?> value="GB">UNITED KINGDOM</option>
	<option <?php if(isset($country) && ($country=="US")){echo 'selected="selected"';}?> value="US">UNITED STATES</option>
	<option <?php if(isset($country) && ($country=="UM")){echo 'selected="selected"';}?> value="U,">UNITED STATES MINOR OUTLYING ISLANDS</option>
	<option <?php if(isset($country) && ($country=="UY")){echo 'selected="selected"';}?> value="UY">URUGUAY</option>
	<option <?php if(isset($country) && ($country=="UZ")){echo 'selected="selected"';}?> value="UZ">UZBEKISTAN</option>
	<option <?php if(isset($country) && ($country=="VU")){echo 'selected="selected"';}?> value="VU">VANUATU</option>
	<option <?php if(isset($country) && ($country=="VE")){echo 'selected="selected"';}?> value="VE">VENEZUELA, BOLIVARIAN REPUBLIC OF</option>
	<option <?php if(isset($country) && ($country=="VN")){echo 'selected="selected"';}?> value="VN">VIET NAM</option>
	<option <?php if(isset($country) && ($country=="VG")){echo 'selected="selected"';}?> value="VG">VIRGIN ISLANDS, BRITISH</option>
	<option <?php if(isset($country) && ($country=="VI")){echo 'selected="selected"';}?> value="VI">VIRGIN ISLANDS, U.S.</option>
	<option <?php if(isset($country) && ($country=="WF")){echo 'selected="selected"';}?> value="WF">WALLIS AND FUTUNA</option>
	<option <?php if(isset($country) && ($country=="EH")){echo 'selected="selected"';}?> value="EH">WESTERN SAHARA</option>
	<option <?php if(isset($country) && ($country=="YE")){echo 'selected="selected"';}?> value="YE">YEMEN</option>
	<option <?php if(isset($country) && ($country=="ZM")){echo 'selected="selected"';}?> value="ZM">ZAMBIA</option>
	<option <?php if(isset($country) && ($country=="ZW")){echo 'selected="selected"';}?> value="ZW">ZIMBABWE</option>
</select>