<?php
// install modules from a GitHub repository link should be made easy from here

include("vendor/autoload.php");

$string = "bennetgallein/modulemanager";

$string = explode("/", $string);

$client = new \Github\Client();
$readme = $client->api('repo')->contents()->readme('bennetgallein', 'PaladinsPHP', $client);
echo base64_decode($readme['content']);