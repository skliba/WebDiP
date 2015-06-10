<?php

error_reporting(-1);
ini_set('display_errors', 'On');
ob_start();
session_start();
include_once './baza.class.php';
$baza = new Baza();


$query = "select korisnik.id, korisnik.username from korisnik where tipKorisnika_id = 2";
$result = $baza->selectDB($query);


header("Content-Type:application/xml");
echo '<?xml version="1.0" encoding="utf-8"?><moderator>';



while ($arr = $result->fetch_array()) {
    echo "<korisnik>";
    
    echo "<id>$arr[0]</id>";
    echo "<name>$arr[1]</name>";
    
    echo "</korisnik>";
}

echo "</moderator>";



