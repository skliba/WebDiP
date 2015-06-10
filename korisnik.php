<?php
include_once './baza.class.php';
$baza = new Baza();
header("Content-Type:application/xml");
echo '<?xml version="1.0" encoding="utf-8"?><korisnici>';

$korisnik = $_GET['korisnik'];
$query = "select * from korisnik where username = '$korisnik'";
$result = $baza->selectDB($query);
$found = 0;
while($arr = $result->fetch_array()){
    if($korisnik == $arr[8]){
        $found = 1;
    }
}
echo "<korisnik>$found</korisnik>";
echo "</korisnici>";

