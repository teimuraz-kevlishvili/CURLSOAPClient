<?php
/**
 * CURL SOAP Client Example
 */

require_once('CurlSOAPClient.php');

//GeoIPService SOAP Example
// Service Description: http://www.webservicex.net/geoipservice.asmx?WSDL

$option = array(
    //SOAP Server Address
    'address' => 'http://www.webservicex.net/geoipservice.asmx',
    //Folder where located Request files
    'folder' => 'requests',
    //Request data file extension
    'ext' => 'xml',

);

$Client = CurlSOAPClient::getInstance($option);

$Params = array(
    'IPAddress' => '72.52.91.14'
);
echo '<pre>';
if (!$Client->MakeRequest('GetGeoIP', $Params)) {
    echo $Client->getError();
    echo str_repeat(PHP_EOL, 3);
}
echo '<b>#Get Full XML data:</b>';
echo str_repeat(PHP_EOL, 2);
echo htmlspecialchars($Client->GetXML());
echo str_repeat(PHP_EOL, 4);
echo '<b>#Get XML data By Tag - "GetGeoIPResult":</b>';
echo str_repeat(PHP_EOL, 2);
echo htmlspecialchars($Client->GetXML('GetGeoIPResult'));
echo str_repeat(PHP_EOL, 4);
echo '<b>#Get Array data By Tag - "GetGeoIPResult":</b>';
echo str_repeat(PHP_EOL, 2);
print_r($Client->GetArray('GetGeoIPResult'));
echo str_repeat(PHP_EOL, 4);
echo '<b>#Get Object data By Tag - "GetGeoIPResult":</b>';
echo str_repeat(PHP_EOL, 2);
print_r($Client->GetObject('GetGeoIPResult'));
echo str_repeat(PHP_EOL, 4);
echo '<b>#Get Tag Value By Tag - "CountryName":</b>';
echo str_repeat(PHP_EOL, 2);
print_r($Client->GetTagValue('CountryName'));
echo str_repeat(PHP_EOL, 4);
echo '<b>#Get Tag Value By Tag - "IP":</b>';
echo str_repeat(PHP_EOL, 2);
print_r($Client->GetTagValue('IP'));
echo str_repeat(PHP_EOL, 4);
echo '</pre>';
