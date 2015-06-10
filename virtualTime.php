<?php

error_reporting(-1);
ini_set('display_errors', 'On');
ob_start();
session_start();

include_once './baza.class.php';
$baza = new Baza();

if (isset($_POST['value'])) {
    $change = $_POST['value'];

    $xml = new DomDocument("1.0", "UTF-8");

    

    $vrijeme = $xml->createElement("vrijeme", $change);
    $vrijeme = $xml->appendChild($vrijeme);

    $xml->formatOutput = true;
    $string_value = $xml->saveXML();
    $xml->save("vrijeme.xml");
}

function getPomak() {

    $xml = simplexml_load_file("./vrijeme.xml");
    $change = $xml;

    $addChange = new DateTime(date("Y-m-d H:i:s", strtotime(sprintf("+%d hours", $change))));
    $virtualTime = $addChange->format("Y-m-d H:i:s");

    return $virtualTime;
}
