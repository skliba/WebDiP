<?php
ob_start();
include_once './baza.class.php';
include_once './virtualTime.php';
$baza = new Baza();

$key = $_GET['kljuc'];

date_default_timezone_set('Europe/Zagreb');
$date = getPomak();

$upit = "select vrijemeAktivacijskogLinka,username,id from korisnik where aktivacijskiLink = '$key'";

if ($rezultat = $baza->selectDB($upit)) {
    
    $arr = $rezultat->fetch_array();
    
    if((strtotime($arr[0]) + 86400) < strtotime($date)){
        echo "<p> Link za aktivaciju je istekao </p>";
        
    }
    else{
        $query = "update korisnik set vrstaStatusa_id=1 where aktivacijskiLink = '$key'";
        $upit = "insert into log values(default, '$arr[2]', '$date', 'aktivirao aktivacijski link', 3)";
        $baza->updateDB($upit);
        $baza->updateDB($query, 'index.php');
        
        
    }
    
}

