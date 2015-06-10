<?php
error_reporting(-1);
ini_set('display_errors', 'On');
ob_start();
include_once './baza.class.php';
include_once './virtualTime.php';
$baza = new Baza();
$greske = '';



$_SESSION['loggedin'] = false;

$i;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $prijava_username = $_POST['userName'];
    $prijava_password = $_POST['userPassword'];

    $time = getPomak();


    if (empty($greske)) {
        $upit = "SELECT * from korisnik where username = '$prijava_username' and password = '$prijava_password'";
        $result = $baza->selectDB($upit);
        $arr = $result->fetch_array();
        if ($result->num_rows != 0) {
            if ($arr[13] != 3) {


                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $prijava_username;
                $_SESSION['email'] = $arr[1];
                $_SESSION ['time'] = $arr[16];
                $_SESSION['userType'] = $arr[12];
                $_SESSION['userId'] = $arr[0];
                $cookie_name = 'user';

                $upit = "insert into log values(default, '$arr[0]', '$time', 'log in u sustav $prijava_username', 1)";
                $baza->updateDB($upit);

                $query = "delete from logIn where korisnik_id = '$arr[0]'";
                $baza->updateDB($query);



                $query = "update korisnik set vrijemePrijave = '$time' where username = '$prijava_username' and password = '$prijava_password'";
                $baza->updateDB($query);
                $i = 0;
                if (isset($_POST['rememberMe'])) {
                    setcookie($cookie_name, $prijava_username, time() + 86400, "/");
                } else {
                    unset($_COOKIE['user']);
                    setcookie($cookie_name, null, -1, "/");
                }
                    header("Location: index.php");
                

                
            } else {
                header("Location:kicked.php");
            }
        } else {
            $greske = "<p><img src='http://png-2.findicons.com/files/icons/1609/ose_png/256/warning.png'></p><p> Pogrešni korisnički podaci</p>";
            date_default_timezone_set('Europe/Zagreb');
            $date = date('Y/m/d h:i:s');

            $query = "select * from korisnik where username = '$prijava_username'";
            $result = $baza->selectDB($query);
            $arr = $result->fetch_array();
            if ($arr) {
                handleUser($date, $prijava_username, $arr[0]);
            }
        }
    }
}

function handleUser($date, $prijava_username, $id) {
    $baza = new Baza();
    $query = "insert into logIn values(default,'$date',1,'$id')";
    $baza->updateDB($query);

    $query = "select count(korisnik_id) from logIn where korisnik_id = '$id'";
    $result = $baza->selectDB($query);
    $arr = $result->fetch_array();
    if ($arr[0] > 3) {
        blockUser($id);
    }
}

function blockUser($id) {
    $baza = new Baza();

    $query = "update korisnik set vrstaStatusa_id = 3 where id = $id";
    $baza->updateDB($query);

    $query = "delete from logIn where korisnik_id = '$id'";
    $baza->updateDB($query);

    header("Location:kicked.php");
}
?>


<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <title>Vjezba - Stefano Kliba</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="author" content="Stefano Kliba">
        <meta name="dcterms.created" content="10.03.2015.">
        <meta name="description" content="Prva zadaca iz kolegija WebDiP">
        <link href='http://fonts.googleapis.com/css?family=Raleway:600,400' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" type="text/css" href="css/srajkov.css">
        <link rel="stylesheet" type="text/css" href="css/srajkov_mobitel.css" media="screen and (max-width:450px)">
        <link rel="stylesheet" type="text/css" href="css/srajkov_tablet.css" media="screen and (min-width:450px) and (max-width:800px)">
        <link rel="stylesheet" type="text/css" href="css/srajkov_pc.css" media="screen and (min-width:800px) and (max-width:1000px)">
        <link rel="stylesheet" type="text/css" href="css/srajkov_tv.css" media="screen and (min-width:1000px)">


    </head>
    <body>
        <div id="wrapper">
            <header id="zaglavlje">
                <div id="headerDiv">
                    <h2>Online TV postaje</h2>
                </div>
                <?php
                if ($_SESSION['loggedin'] == true) {
                    $username = $_SESSION['username'];
                    echo "<a href='logout.php' id='signInLink'>Odjavi se " . $username . "</a>";
                } else {
                    echo "<a href = 'prijava.php' id = 'signInLink'>Prijavi se</a>";
                }
                ?>
            </header>
            <div id="left">    
                <nav id="navigacija">
                    <ul>
                        <li><a href="registracija.php" >registracija</a></li>
                        <li><a href="prijava.php" id="curr">prijava</a></li>
                        <li><a href="dokumentacija.html">Dokumentacija</a></li>
                        <li><a href="o_autoru.html">O autoru</a></li>
                    </ul>
                </nav>
            </div>       
            <div id="right">
                <section id="sadrzaj">
                    <h2>Prijava korisnika</h2>
                    <hr>
                    <div id="greske">

                        <?php
                        echo $greske;
                        ?>

                    </div>
                    <section id="inputcontent">
                        <form method="post" action="prijava.php">
                            <div>
                                <label for="puname">Korisničko ime: </label>
                                <input name="userName" type="text" id="puname" placeholder="Korisničko ime" value="<?php
                                if (isset($_COOKIE['user'])) {
                                    $user = $_COOKIE['user'];
                                    echo $user;
                                }
                                ?>"><br>

                                <label for="ppw">Lozinka: </label>
                                <input name="userPassword" id="ppw" type="password" placeholder="Lozinka"><br>

                                <label for="pcbox">Zapamti me</label>
                                <input name="rememberMe" type="checkbox" id="pcbox" value="Yes"><br>

                                <input name="logInButton" type="submit" value="Prijavi se" id="plogIn">
                            </div>
                        </form>
                    </section>
                    <div>
                    <p class="regHere">Registriraj se <a href="registracija.html">ovdje</a></p>
                    <p class="regHere">Zaboravio sam lozinku <a href="forgottenPassword.php">Klikni tu</a></p>
                    </div>

                </section>    
            </div>    
            <footer id="podnozje">
                <p><strong>Vrijeme potrebno za rješavanje aktivnog dokumenta:</strong> 10 minuta</p>
                <p><strong>Vrijeme potrebno za rješavanje cijelog rješenja: </strong> 11h i 20 minuta </p>
                <div class="htmlval">
                    <figure>
                        <a href="http://validator.w3.org/check/referer" target="_blank">
                            <img src="http://blog.boyet.com/blog/files/media/image/valid-html5-blue.png" alt="Valid HTML5!">
                        </a>
                        <figcaption>HTML5 Validator</figcaption>
                    </figure>


                    <figure>
                        <a href="https://jigsaw.w3.org/css-validator/check/referer" target="_blank">
                            <img src="http://jigsaw.w3.org/css-validator/images/vcss-blue" alt="Valid CSS!">
                        </a>
                        <figcaption>CSS Validator</figcaption>
                    </figure>
                </div>
                <p><a href="mailto:skliba@foi.hr">Kontaktirajte me</a></p>
                <p><i>Stefano Kliba - sva prava pridržana </i>&copy; Web Dizajn i programiranje - 2015</p>

            </footer>
        </div>
    </body>
</html>
