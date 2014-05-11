# PHP cURL SOAP Client Library

With this library, you can make requests to SOAP server without using native PHP SOAP Client.

It uses PHP and cURL Library.

# Usage
Create request file from SOAP Service WSDL (Can Use [SoapUI](http://www.soapui.org/)) and save xml code to "requests" folder with method name: GetGeoIP(.xml).
```xml
<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:web="http://www.webservicex.net/">
    <soap:Header/>
    <soap:Body>
        <web:GetGeoIP>
            <!--Optional:-->
            <web:IPAddress>{IPAddress}</web:IPAddress>
        </web:GetGeoIP>
    </soap:Body>
</soap:Envelope>
```


Define option parameters array:
```PHP 
$option = array(
    //SOAP Server Address
    'address' => 'http://www.webservicex.net/geoipservice.asmx',
    //Folder where located Request files
    'folder' => 'requests',
    //Request data file extension
    'ext' => 'xml',
);
```
Create Client Instance and make request:
```php
$Client = CurlSOAPClient::getInstance($option);
//Define Service method parameters
$Params = array(
    'IPAddress' => '72.52.91.14'
);
//Call Soap method 'GetGeoIP' with defined parameters
$Client->MakeRequest('GetGeoIP', $Params);
```
You can get result data with several methods:

### Get Full XML data:
```php
$Client->GetXML();
```
Result:
```xml
<?xml version="1.0" encoding="utf-8"?>
<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope"
               xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
    <soap:Body>
        <GetGeoIPResponse xmlns="http://www.webservicex.net/">
            <GetGeoIPResult>
                <ReturnCode>1</ReturnCode>
                <IP>72.52.91.14</IP>
                <ReturnCodeDetails>Success</ReturnCodeDetails>
                <CountryName>United States</CountryName>
                <CountryCode>USA</CountryCode>
            </GetGeoIPResult>
        </GetGeoIPResponse>
    </soap:Body>
</soap:Envelope>
```


### Get XML data By Tag - "GetGeoIPResult":
```php
$Client->GetXML('GetGeoIPResult');
```
Result:
```xml
<GetGeoIPResult>
    <ReturnCode>1</ReturnCode>
    <IP>72.52.91.14</IP>
    <ReturnCodeDetails>Success</ReturnCodeDetails>
    <CountryName>United States</CountryName>
    <CountryCode>USA</CountryCode>
</GetGeoIPResult>
```


### Get Array data By Tag - "GetGeoIPResult":
```php
$Client->GetArray('GetGeoIPResult');
```
Result:
```
Array
(
    [ReturnCode] => 1
    [IP] => 72.52.91.14
    [ReturnCodeDetails] => Success
    [CountryName] => United States
    [CountryCode] => USA
)
```


### Get Object data By Tag - "GetGeoIPResult":
```php
$Client->GetObject('GetGeoIPResult');
```
Result:
```
stdClass Object
(
    [ReturnCode] => 1
    [IP] => 72.52.91.14
    [ReturnCodeDetails] => Success
    [CountryName] => United States
    [CountryCode] => USA
)
```


### Get Tag Value:
```php
$Client->GetTagValue('CountryName');
$Client->GetTagValue('CountryCode');
```
Result:
```
United States
USA
```
