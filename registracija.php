<?php
error_reporting(-1);
ini_set('display_errors', 'On');
ob_start();
include_once './baza.class.php';
include_once './virtualTime.php';
$baza = new Baza();

$greske = '';

if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){
    header("Location:requestLogout.php");
}

if ($_SERVER["REQUEST_METHOD"] == 'POST') {

    $kor_ime = $_POST['ime'];
    $kor_prezime = $_POST['prezime'];
    $kor_adresa = $_POST['adresa'];
    $kor_grad = $_POST['grad'];
    $kor_mail = $_POST['mail'];
    $kor_username = $_POST['uname'];
    $kor_password = $_POST['pw'];
    $kor_datum = $_POST['datum'];
    $kor_secQuestion = $_POST['sigPitanje'];
    $kor_answer = $_POST['answer'];


    $email;
    $comment;
    $captcha;

    if (isset($_POST['g-recaptcha-response'])) {
        $captcha = $_POST['g-recaptcha-response'];

        $response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LeKXwYTAAAAAEPsabnxGgBLFn4aF4nF1ljVeRAY&response=" . $captcha . "&remoteip=" . $_SERVER['REMOTE_ADDR']), true);
        if (!$response['success']) {
            //$greske .= "<p><img src='http://png-2.findicons.com/files/icons/1609/ose_png/256/warning.png'> </p><p> Niste oznacili recaptchu</p>";
        }
    }



    if (!(strlen($kor_password) < 6)) {
        
    } else {
        $greske .= "<p><img src='http://png-2.findicons.com/files/icons/1609/ose_png/256/warning.png'> </p><p> Lozinka mora imati više od 6 znakova</p>";
    }

    if (!empty($kor_grad)) {
        if (ctype_upper($kor_grad[0])) {
            
        } else {
            $greske .= "<p><img src='http://png-2.findicons.com/files/icons/1609/ose_png/256/warning.png'> </p><p> Grad ne počinje velikim početnim slovom</p>";
        }
    } else {
        $greske .= "<p><img src='http://png-2.findicons.com/files/icons/1609/ose_png/256/warning.png'> </p><p> Niste unjeli niti jedan grad</p>";
    }

    if (empty($kor_username)) {
        $greske .= "<p><img src='http://png-2.findicons.com/files/icons/1609/ose_png/256/warning.png'> </p><p> Unesite korisničko ime</p>";
    }
    if (empty($kor_datum)) {
        $greske.="<p><img src='http://png-2.findicons.com/files/icons/1609/ose_png/256/warning.png'> </p><p> Unesite datum rođenja</p>";
    }

    if (!empty($kor_ime)) {
        if (ctype_upper($kor_ime[0])) {
            
        } else {
            $greske .= "<p><img src='http://png-2.findicons.com/files/icons/1609/ose_png/256/warning.png'> </p><p> Ime mora počinjati velikim početnim slovom</p>";
        }
    } else {
        $greske .= "<p><img src='http://png-2.findicons.com/files/icons/1609/ose_png/256/warning.png'> </p><p> Niste unijeli nikakvo ime</p>";
    }

    if (!empty($kor_prezime)) {
        if (ctype_upper($kor_prezime[0])) {
            
        } else {
            $greske .= "<p><img src='http://png-2.findicons.com/files/icons/1609/ose_png/256/warning.png'> </p><p> Prezime mora počinjati velikim početnim slovom</p>";
        }
    } else {
        $greske .= "<p><img src='http://png-2.findicons.com/files/icons/1609/ose_png/256/warning.png'> </p><p> Niste unijeli nikakvo prezime</p>";
    }

    if (!isset($_POST['radioB'])) {
        $greske .= "<p><img src='http://png-2.findicons.com/files/icons/1609/ose_png/256/warning.png'> </p><p> Odredite barem jedan spol</p>";
    } else {
        $kor_spol = $_POST['radioB'];
    }

    if (!$_POST['adresa']) {
        $greske .= "<p><img src='http://png-2.findicons.com/files/icons/1609/ose_png/256/warning.png'> </p><p> Unesite adresu</p>";
        
    }

    if (empty($kor_answer)) {
        $greske .= "<p><img src='http://png-2.findicons.com/files/icons/1609/ose_png/256/warning.png'> </p><p> Unesite odgovor na sigurnosno pitanje</p>";
    }

    if (empty($greske)) {
        $upit = "SELECT * FROM korisnik where mail = '$kor_mail'";
        $rezultat = $baza->selectDB($upit);
        if ($rezultat->num_rows != 0) {
            $greske .= "<p><img src='http://png-2.findicons.com/files/icons/1609/ose_png/256/warning.png'> </p><p> E-mail adresa <b>$kor_mail</b> je zauzeta</p>";
        }
        $query = "SELECT * FROM korisnik where username = '$kor_username'";
        $result = $baza->selectDB($query);
        if ($result->num_rows != 0) {
            $greske .= "<p><img src='http://png-2.findicons.com/files/icons/1609/ose_png/256/warning.png'> </p><p> Korisnicko ime <b>$kor_username</b> je zauzeto</p>";
        } else {

            do {
                $activationKey = bin2hex(openssl_random_pseudo_bytes(16));

                $query = "select * from korisnik where aktivacijskiLink = '$activationKey'";
                $rez = $baza->selectDB($query);
                $exists = true;

                if ($rez) {

                    $date = getPomak();
                    $upit = "INSERT INTO korisnik VALUES (default, '$kor_mail', '$kor_ime', '$kor_prezime','$kor_datum','$kor_adresa','$kor_grad','$kor_spol', '$kor_username', '$kor_password', '$kor_secQuestion','$kor_answer','1','2','$activationKey','$date', null)";

                    $exists = false;
                }
            } while ($exists);

            if ($baza->updateDB($upit)) {
                $primatelj = $kor_mail;
                $upit = "select * from korisnik where username='$kor_username'";
                $result = $baza->selectDB($upit);
                $arr = $result->fetch_array();
                $date = getPomak();

                $upit = "insert into log values(default, '$arr[0]', '$date', 'registriran i poslan aktivacijski link', 3)";
                $baza->updateDB($upit);

                $naslov = "WebDiP2015 - Aktivacija";
                $poruka = "Postovani aktivirajate svoj racun klikom na poveznicu \n"
                        . "<a href=\"http://arka.foi.hr/WebDiP/2014_projekti/WebDiP2014x031/aktivacija.php?kljuc=$activationKey\">Link</a>";
                mail($primatelj, $naslov, $poruka);
                header("Location: index.php");
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Projekt - Stefano Kliba</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="author" content="Stefano Kliba">
        <meta name="dcterms.created" content="31.05.2015.">
        <meta name="description" content="Projekt WebDiP">
        <link href='http://fonts.googleapis.com/css?family=Raleway:600,400' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" type="text/css" href="css/srajkov.css">
        <link rel="stylesheet" type="text/css" href="css/srajkov_mobitel.css" media="screen and (max-width:450px)">
        <link rel="stylesheet" type="text/css" href="css/srajkov_tablet.css" media="screen and (min-width:450px) and (max-width:800px)">
        <link rel="stylesheet" type="text/css" href="css/srajkov_pc.css" media="screen and (min-width:800px) and (max-width:1000px)">
        <link rel="stylesheet" type="text/css" href="css/srajkov_tv.css" media="screen and (min-width:1000px)">
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/angular_material/0.9.0/angular-material.min.css">
        <link rel="stylesheet" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">
        <script src='https://www.google.com/recaptcha/api.js'></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/angular-material/0.9.0/angular-material.min.css" type="text/javascript"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/angular-material-icons/0.4.0/angular-material-icons.min.js" type="text/javascript"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
        <script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
        <script src="js/getDataTables.js"></script>
    </head>
    <body>
        



        <div id="wrapper">
            <header id="zaglavlje">
                <div id="headerDiv">
                    <h2>Online TV postaje</h2>
                </div>
                <?php
                if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
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
                        <li><a href="registracija.php" id="curr">Registriraj se</a></li>
                        <li><a href="dokumentacija.html">Dokumentacija</a></li>
                        <li><a href="o_autoru.html">O autoru</a></li>
                    </ul>
                </nav>
            </div>       
            <div id="right">
                <section id="sadrzaj">
                    <h2>Registracijski formular</h2>
                    <hr>
                    <div id="greske">

                        <?php
                        echo $greske;
                        ?>

                    </div>
                    <section id="regcontent">

                        <form method="post" action="registracija.php" id="registracija" enctype="multipart/form-data">

                            <label for="ime">Ime: </label>
                            <input type="text"  name="ime" placeholder="Ime" class="highlight-input" id="ime"><br>


                            <label for="prezime">Prezime: </label>
                            <input type="text"  name="prezime" placeholder="Prezime" class="highlight-input" id="prezime"><br>

                            <label for="adresa">Adresa: </label>
                            <textarea id="adresa" rows="2" name="adresa" class="highlight-input"></textarea><br>

                            <label for="grad">Grad:</label>
                            <input type="text" name="grad" id="grad" class="highlight-input"><br>


                            <label for="mail">Email:</label>
                            <input type="email" name="mail" id="mail" class="highlight-input"><br>


                            <label for="uname">Korisnčko ime: </label>
                            <input type="text" name="uname" placeholder="Korisničko ime" id="uname" class="highlight-input"><br>


                            <label for="pw">Lozinka: </label>
                            <input id="pw" type="password" name="pw" placeholder="Lozinka" class="highlight-input"><br>


                            <label for="datum">Datum rođenja: </label>
                            <input type="date" name="datum" placeholder="yyyy.mm.dd" id="datum" class="highlight-input"> <br>


                            <label for="mbox">Spol: </label>
                            <fieldset id="radioBcontainer">
                                <input type="radio" name="radioB" id="mbox" value="M" class="highlight-input">Muško
                                <input type="radio"  name="radioB" id="fbox" value="F"class="highlight-input">Žensko
                                <input type="radio" name="radioB" id="ubox" value="U" class="highlight-input">?<br>
                            </fieldset>

                            <label for="sigPitanje">Sig pitanje: </label>
                            <select id="sigPitanje" name="sigPitanje" class="highlight-input">
                                <option value="-1">-- Odaberite sigurnosno pitanje -- </option>
                                <option value="1" selected>Ime Vašeg psa</option>
                                <option value="2">Ime Vašeg prvog psa</option>
                                <option value="3">Ime Vaše mačke</option>
                                <option value="4">Ime Vaše prve mačke</option>
                                <option value="5">Ime Vašeg oca</option>
                                <option value="6">Ime Vašeg bake</option>
                                <option value="7">Ime Vašeg prabake</option>
                                <option value="8">Ulica u kojoj ste odrasli</option>
                                <option value="9">Najdraza ulica</option>
                                <option value="10">Ime Vaše prve ribice</option>
                                <option value="11">Ime Vaše nastavnice iz matematike</option>
                                <option value="12">Ime Vašeg oca</option>
                                <option value="13">Ime Vašeg oca</option>
                            </select><br>

                            <label for="answer">Odgovor</label>
                            <input type="text" id="answer" name="answer" class="highlight-input"><br>

                            <div class="g-recaptcha" data-sitekey="6LeKXwYTAAAAAKvLuOiewqD8LLLETUVNJscp90pP"></div>

                            <input type="submit" name="subButton" value="Registriraj me" id="subButton">
                            <button type="reset" name="delForm" value="Delete form" id="delForm">Izbriši podatke</button>

                        </form>
                    </section>


                </section>    
            </div>    
            <footer id="podnozje">
                <p><strong>Vrijeme potrebno za rješavanje aktivnog dokumenta:</strong> 6h</p>
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
        <script src="js/regCheckAjax.js" type="text/javascript"></script>
    </body>
</html>