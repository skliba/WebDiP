<?php
error_reporting(-1);
ini_set('display_errors', 'On');
ob_start();
session_start();
include_once './baza.class.php';
include_once './virtualTime.php';
$baza = new Baza();
$greske = '';

if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    $changePw_username = $_POST['username'];
    $activationPassword = bin2hex(openssl_random_pseudo_bytes(8));
    
    $query = "select * from korisnik where username = '$changePw_username'";
    $result = $baza->selectDB($query);
    $arr = $result->fetch_array();
    if($arr){
        $title = "WebDiP2014x031 - promjena lozinke";
        $msg = "Postovani Vasa nova lozinka je  \n$activationPassword \nPovratak na stranicu za prijavu: http://arka.foi.hr/WebDiP/2014_projekti/WebDiP2014x031/prijava.php";
        $recipient = $arr[1];
        
        $query = "update korisnik set password = '$activationPassword' where id = '$arr[0]'";
        $baza->updateDB($query);
        $time = getPomak();
        $query = "insert into log values(default, '$arr[0]','$time','Zatrazio novu lozinku',3)";
        $baza->updateDB($query);
        
        mail($recipient, $title, $msg);
        $greske = "<p><img src='./img/icons/checkIcon.png'> </p><p> E-mail je poslan! </p>";
        
    }
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
                        <form method="post" action="forgottenPassword.php">
                            <h4>Unesite ispravno korisničko ime, ukoliko je ispravno dobit ćete na mail novu lozinku</h4>
                            <div>
                                <label for="puname">Korisničko ime: </label>
                                <input name="username" type="text" id="puname" placeholder="Korisničko ime" value="<?php
                                if (isset($_COOKIE['user'])) {
                                    $user = $_COOKIE['user'];
                                    echo $user;
                                }
                                ?>"><br>
                                <input name="logInButton" type="submit" value="Pošalji mi novu lozinku" id="plogIn">
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