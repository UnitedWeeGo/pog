<?

ini_set('soap.wsdl_cache_ttl', 1);

global $configuration;
$configuration['soapEngine'] = "phpsoap"; //other value is "nusoap"
$configuration['soap'] = "http://pog.weegoapp.com/services/soap.php?wsdl";
$configuration['homepage'] = "http://pog.weegoapp.com";
$configuration['revisionNumber']="";
$configuration['versionNumber'] = "3.0d";
$configuration['author'] = "Php Object Generator";
$configuration['copyright'] = "Free for personal & commercial use. (Offered under the BSD license)";
?>
