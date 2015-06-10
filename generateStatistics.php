<?php

error_reporting(-1);
ini_set('display_errors', 'On');
ob_start();
include_once './baza.class.php';
include_once './virtualTime.php';
$baza = new Baza();



if (!isset($_SESSION['userType']) || $_SESSION['userType'] != 3 || $_SESSION['loggedin'] == false) {
    $time = getPomak();
    $id_user = $_GET['id'];
    $log_user = $_SESSION['userId'];
    $query = "select korisnik.username from korisnik where id = $log_user";
    $result = $baza->selectDB($query);
    $arrayzsz = $result->fetch_array();
    $query = "insert into log values(default,'$log_user','$time','Neuspjeli pokušaj pristupa korisnika $arrayzsz[0] na stranicu generateStatistics.php', 3)";
    $baza->updateDB($query);
    header("Location: failedToAccess.php");
} else {
    work();
}

function work() {
    $baza = new Baza();


    if (isset($_POST['value']) && $_POST['value'] == 1) {
        $od = $_POST['od'];

        $query = "select log.*, korisnik.username, vrstaLog.naziv from log,korisnik,vrstaLog where log.korisnik_id = korisnik.id and log.vrstaLog_id = vrstaLog.id and korisnik.username = '$od' order by 1 ";
        $result = $baza->selectDB($query);

        $xml = new DomDocument("1.0", "UTF-8");
        $statistics = $xml->createElement("statistika");
        $statistics = $xml->appendChild($statistics);

        while ($arr = $result->fetch_array()) {

            $action = $xml->createElement("akcija");
            $action = $statistics->appendChild($action);

            $user = $xml->createElement("korisnik", $arr[5]);
            $user = $action->appendChild($user);

            $time = $xml->createElement("vrijeme", $arr[2]);
            $time = $action->appendChild($time);

            $desc = $xml->createElement("opis", $arr[3]);
            $desc = $action->appendChild($desc);

            $activityDesc = $xml->createElement("vrstaLog", $arr[6]);
            $activityDesc = $action->appendChild($activityDesc);
        }

        $xml->formatOutput = true;
        $string_value = $xml->saveXML();
        $xml->save("statistika.xml");


        $time = getPomak();
        $log_user = $_SESSION['userId'];
        $query = "select korisnik.username from korisnik where id = $log_user";
        $result = $baza->selectDB($query);
        $arrayzsz = $result->fetch_array();
        $query = "insert into log values(default,'$log_user','$time','Korisnik $arrayzsz[0] je zatražio statistiku prikaza po korisniku $od', 2)";
        $baza->updateDB($query);
    }
    if (isset($_POST['value']) && $_POST['value'] == 2) {

        $od = $_POST['od'];
        $do = $_POST['do'];

        $query = "select log.*, korisnik.username, vrstaLog.naziv from log,korisnik,vrstaLog where log.korisnik_id = korisnik.id and log.vrstaLog_id = vrstaLog.id and log.vrijeme between '$od' and '$do' order by 1";
        $result = $baza->selectDB($query);

        $xml = new DomDocument("1.0", "UTF-8");
        $statistics = $xml->createElement("statistika");
        $statistics = $xml->appendChild($statistics);

        while ($arr = $result->fetch_array()) {

            $action = $xml->createElement("akcija");
            $action = $statistics->appendChild($action);

            $user = $xml->createElement("korisnik", $arr[5]);
            $user = $action->appendChild($user);

            $time = $xml->createElement("vrijeme", $arr[2]);
            $time = $action->appendChild($time);

            $desc = $xml->createElement("opis", $arr[3]);
            $desc = $action->appendChild($desc);

            $activityDesc = $xml->createElement("vrstaLog", $arr[6]);
            $activityDesc = $action->appendChild($activityDesc);
        }

        $xml->formatOutput = true;
        $string_value = $xml->saveXML();
        $xml->save("statistika.xml");



        $time = getPomak();
        $log_user = $_SESSION['userId'];
        $query = "select korisnik.username from korisnik where id = $log_user";
        $result = $baza->selectDB($query);
        $arrayzsz = $result->fetch_array();
        $query = "insert into log values(default,'$log_user','$time','Korisnik $arrayzsz[0] je zatražio statistiku prikaza po vremenskom intervalu $od - $do', 2)";
        $baza->updateDB($query);
    }
    if (isset($_POST['value']) && $_POST['value'] == 3) {

        $od = $_POST['od'];

        $query = "select log.*, korisnik.username, vrstaLog.naziv from log,korisnik,vrstaLog where log.korisnik_id = korisnik.id and log.vrstaLog_id = vrstaLog.id and log.vrijeme = '$od' order by 1";
        $result = $baza->selectDB($query);

        $xml = new DomDocument("1.0", "UTF-8");
        $statistics = $xml->createElement("statistika");
        $statistics = $xml->appendChild($statistics);

        while ($arr = $result->fetch_array()) {

            $action = $xml->createElement("akcija");
            $action = $statistics->appendChild($action);

            $user = $xml->createElement("korisnik", $arr[5]);
            $user = $action->appendChild($user);

            $time = $xml->createElement("vrijeme", $arr[2]);
            $time = $action->appendChild($time);

            $desc = $xml->createElement("opis", $arr[3]);
            $desc = $action->appendChild($desc);

            $activityDesc = $xml->createElement("vrstaLog", $arr[6]);
            $activityDesc = $action->appendChild($activityDesc);
        }

        $xml->formatOutput = true;
        $string_value = $xml->saveXML();
        $xml->save("statistika.xml");




        $time = getPomak();
        $log_user = $_SESSION['userId'];
        $query = "select korisnik.username from korisnik where id = $log_user";
        $result = $baza->selectDB($query);
        $arrayzsz = $result->fetch_array();
        $query = "insert into log values(default,'$log_user','$time','Korisnik $arrayzsz[0] je zatražio statistiku prikaza po datumu za $od', 2)";
        $baza->updateDB($query);
    }
    if (isset($_POST['value']) && $_POST['value'] == 4) {

        $od = $_POST['od'];

        $query = "select log.*, korisnik.username, vrstaLog.naziv from log,korisnik,vrstaLog where log.korisnik_id = korisnik.id and log.vrstaLog_id = vrstaLog.id and log.vrstaLog_id = $od order by 1";

        $result = $baza->selectDB($query);

        $xml = new DomDocument("1.0", "UTF-8");
        $statistics = $xml->createElement("statistika");
        $statistics = $xml->appendChild($statistics);

        while ($arr = $result->fetch_array()) {

            $action = $xml->createElement("akcija");
            $action = $statistics->appendChild($action);

            $user = $xml->createElement("korisnik", $arr[5]);
            $user = $action->appendChild($user);

            $time = $xml->createElement("vrijeme", $arr[2]);
            $time = $action->appendChild($time);

            $desc = $xml->createElement("opis", $arr[3]);
            $desc = $action->appendChild($desc);

            $activityDesc = $xml->createElement("vrstaLog", $arr[6]);
            $activityDesc = $action->appendChild($activityDesc);
        }

        $xml->formatOutput = true;
        $string_value = $xml->saveXML();
        $xml->save("statistika.xml");




        $time = getPomak();
        $log_user = $_SESSION['userId'];
        $query = "select korisnik.username from korisnik where id = $log_user";
        $result = $baza->selectDB($query);
        $arrayzsz = $result->fetch_array();
        $query = "insert into log values(default,'$log_user','$time','Korisnik $arrayzsz[0] je zatražio statistiku prikaza po vrsti loga za $od', 2)";
        $baza->updateDB($query);
    }
}
