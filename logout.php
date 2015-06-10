<?php

include_once './baza.class.php';
include_once './virtualTime.php';
$baza = new Baza();

$time = getPomak();
$uId = $_SESSION['userId'];
$username = $_SESSION['username'];
$upit = "insert into log values(default,'$uId', '$time', 'Korisnik $username se odjavio', 1)";
$baza->updateDB($upit);

header("Location: index.php");

session_destroy();
?>
