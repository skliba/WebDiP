<?php
error_reporting(-1);
ini_set('display_errors', 'On');
ob_start();
include_once './baza.class.php';
include_once './virtualTime.php';
$baza = new Baza();

$greske = '';

if ($_SESSION['userType'] != 3) {
    $time = getPomak();
    $id_user = $_GET['id'];
    $log_user = $_SESSION['userId'];
    $query = "select korisnik.username from korisnik where id = $log_user";
    $result = $baza->selectDB($query);
    $arrayzsz = $result->fetch_array();
    $query = "insert into log values(default,'$log_user','$time','Neuspjeli pokušaj pristupa korisnika $arrayzsz[0] na stranicu createTvStation.php', 3)";
    $baza->updateDB($query);
    header("Location: failedToAccess.php");
} else {
    work();
}

function work() {

    $baza = new Baza();
    $id_admin = $_SESSION['userId'];
    $time = getPomak();

    if (isset($_POST['subButton'])) {

        $nazivPostaje = $_POST['nazivPostaje'];
        $puniNaziv = $_POST['prodNaziv'];
        $adresa = $_POST['adresa'];
        $logo = $_POST['logoLink'];
        $vrsta = $_POST['vrstaPostaje'];
        if (isset($vrsta) && $vrsta == 1) {
            $vrstaPostaje = "T";
        } else {
            $vrstaPostaje = "R";
        }
        $fakeModerator = $_POST['moderatorChoice'];

        $query = "insert into postaja values(default, '$nazivPostaje', '$puniNaziv', '$adresa', '$vrstaPostaje', '$logo', 1, $fakeModerator)";
        $baza->updateDB($query);

        $time = getPomak();
        $admin = $_SESSION['userId'];
        $query = "select korisnik.username from korisnik where id = $admin";
        $result = $baza->selectDB($query);
        $arrayzsz = $result->fetch_array();
        $query = "insert into log values(default,'$mod','$time','Admin $arrayzsz[0] je dodao postaju $nazivPostaje', 3)";
        $baza->updateDB($query);


        header("Location:adminpanel.php");
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
                        <li><a href="index.php">Početna stranica</a></li>
                        <li><a href="userList.php">Popis korisnika</a></li>
                        <li><a href="createTvStation.php" id="curr">Dodaj tv postaju</a></li>
                        <li><a href="stationList.php">Lista postaja</a></li>
                        <li><a href="config.php">Konfiguracija stranice</a></li>
                        <li><a href="pregled_dnevnika.php">Pregled dnevnika</a></li>
                        <li><a href="statistika.php">Statistika</a></li>
                        <li><a href="adminpanel.php" id="curr">Admin panel</a></li>
                        <li><a href="dokumentacija.html">Dokumentacija</a></li>
                        <li><a href="o_autoru.html">O autoru</a></li>
                    </ul>
                </nav>
            </div>       
            <div id="right">
                <section id="sadrzaj">

                    <h3>Dodavanje postaja</h3>
                    <hr>

                    <section id="regcontent">

                        <form method="post" action="createTvStation.php" enctype="multipart/form-data">

                            <label for="nazivPostaje">Naziv: </label>
                            <input type="text"  name="nazivPostaje"  class="highlight-input input-modified" id="nazivPostaje"><br>


                            <label for="prodNaziv">Puni naziv: </label>
                            <input type="text"  name="prodNaziv" class="highlight-input input-modified" id="prodNaziv"><br>

                            <label for="adresa">Adresa: </label>
                            <textarea id="adresa" rows="2" name="adresa" class="highlight-input input-modified"></textarea><br>

                            <label for="logoLink">Logo link:</label>
                            <input type="text" name="logoLink" id="logoLink" class="highlight-input input-modified" size="500"><br>

                            <label for="vrstaPostaje">Vrsta postaje:</label>
                            <select name="vrstaPostaje" class="highlight-input input-modified">
                                <option value="-1">-- Odabir vrste postaje --</option>
                                <option value="1">T</option>
                                <option value="2">R</option>
                            </select><br>

                            <label for="moderatorChoice">Odabir moderatora:</label>
                            <select name='moderatorChoice' class='highlight-input input-modified'>
                                <?php
                                $query = "select korisnik.id, korisnik.username from korisnik where tipKorisnika_id = 2";
                                $rez = $baza->selectDB($query);
                                while ($arrayzs = $rez->fetch_array()) {
                                    echo "<option value='" . $arrayzs[0] . "'>" . $arrayzs[1] . "</option>";
                                }
                                ?>
                            </select><br>

                            <input type="submit" id="subButton" name="subButton" value="Dodaj postaju">
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