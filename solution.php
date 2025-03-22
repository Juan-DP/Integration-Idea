<?php

require_once(__DIR__ . "/helpers.php");
require_once(__DIR__ . "../Resource.php");
require_once(__DIR__ . "../Connector.php");
require_once(__DIR__ . "../APIConnector.php");


$env = parse_ini_file('.env');
$a = new APIConnector(
    "{$env['CONNECTION_STRING']}/{$env['COMPANY_ID']}",
    $env['SECRET']
);

$jsonData = $a->categories()->list();
jsonToCsv($jsonData, 'categories.csv');

$jsonData = $a->products()->addCustomQueryParameters(["count" => 2])->list();

jsonToCsv($jsonData, 'products.csv');
