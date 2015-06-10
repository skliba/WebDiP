<?php
error_reporting(-1);
ini_set('display_errors', 'On');
ob_start();
include_once './baza.class.php';
include_once './virtualTime.php';
$baza = new Baza();

$greske = '';

if ($_SESSION['userType'] < 2) {
    $time = getPomak();
    $log_user = $_SESSION['userId'];
    $query = "select korisnik.username from korisnik where id = $log_user";
    $result = $baza->selectDB($query);
    $arrayzsz = $result->fetch_array();
    $query = "insert into log values(default,'$log_user','$time','Neuspjeli pokušaj pristupa korisnika $arrayzsz[0] na stranicu createShow.php', 3)";
    $baza->updateDB($query);
    header("Location: failedToAccess.php");
} else {
    work();
}

function work() {

    $baza = new Baza();
    $id_moderator = $_SESSION['userId'];
    $time = getPomak();

    if (isset($_POST['subButton'])) {

        $nazivEmisije = $_POST['nazivEmisije'];
        $trajanje = $_POST['trajanje'];
        $desc = $_POST['desc'];
        $houseChoice = $_POST['houseChoice'];
        $stationChoice = $_POST['stationChoice'];
        $termin = $_POST['termin'];
        $dan = $_POST['dan'];


        $mod = $_SESSION['userId'];

        $query = "select termin from emisija where termin = '$termin' and zaDan = '$dan' and emisija.postaja_id = '$stationChoice'";
        $rez = $baza->selectDB($query);
        $arr = $rez->fetch_array();
        if ($arr) {
            header("Location:showDateOccupied.php");
        } else {

            $query = "insert into emisija values(default, '$houseChoice', '$nazivEmisije', '$trajanje', '$desc',0, '$mod', '$stationChoice', 1, '$termin', '$dan')";
            $baza->updateDB($query);

            $time = getPomak();
            $query = "select korisnik.username from korisnik where id = $mod";
            $result = $baza->selectDB($query);
            $arrayzsz = $result->fetch_array();
            $query = "insert into log values(default,'$mod','$time','Moderator $arrayzsz[0] je dodao emisiju $nazivEmisije', 3)";
            $baza->updateDB($query);


            header("Location:moderatorPanel.php");
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
                        <li><a href="createShow.php" id="curr">Kreiraj emisiju</a></li>
                        <li><a href="stationModeratorList.php" >Moderirane postaje</a></li>
                        <li><a href="sendInfo.php">Pošalji obavijest</a></li>
                        <li><a href="moderatorPanel.php" >Moderator panel</a></li>
                        <li><a href="dokumentacija.html">Dokumentacija</a></li>
                        <li><a href="o_autoru.html">O autoru</a></li>
                    </ul>
                </nav>
            </div>       
            <div id="right">
                <section id="sadrzaj">

                    <h3>Dodavanje emisije</h3>
                    <hr>
                    
                    <div>
                        <?php
                        
                        echo $greske;
                        
                        ?>
                    </div>

                    <section id="regcontent">

                        <form method="post" action="createShow.php" enctype="multipart/form-data">

                            <label for="nazivEmisije">Naziv: </label>
                            <input type="text"  name="nazivEmisije"  class="highlight-input input-modified" id="nazivEmisije"><br>

                            <label for="trajanje">Trajanje: </label>
                            <input type="text"  name="trajanje" class="highlight-input input-modified" id="trajanje"><br>

                            <label for="ocjena">Ocjena: </label>
                            <input id="ocjena" type="text" name="ocjena" value="0" class="highlight-input input-modified" readonly="readonly"><br>

                            <label for="desc">Opis: </label>
                            <textarea id="desc" rows="2" name="desc" class="highlight-input input-modified"></textarea><br>

                            <label for="houseChoice">Prod. kuća:</label>
                            <select name='houseChoice' class='highlight-input input-modified'>
                                <?php
                                $query = "select producentskaKuca.id, producentskaKuca.ime from producentskaKuca";
                                $rez = $baza->selectDB($query);
                                while ($arrayzs = $rez->fetch_array()) {
                                    echo "<option value='" . $arrayzs[0] . "'>" . $arrayzs[1] . "</option>";
                                }
                                ?>
                            </select><br>

                            <label for="stationChoice">Postaja:</label>
                            <select name='stationChoice' class='highlight-input input-modified'>
                                <?php
                                $modder = $_SESSION['userId'];
                                $query = "select postaja.id, postaja.naziv from postaja where valid = 1 and postaja.moderira = $modder";
                                $rez = $baza->selectDB($query);
                                while ($arrayzs = $rez->fetch_array()) {
                                    echo "<option value='" . $arrayzs[0] . "'>" . $arrayzs[1] . "</option>";
                                }
                                ?>
                            </select><br>


                            <label for="termin">Termin:</label>
                            <select name="termin" class='highlight-input input-modified'>
                                <option value="08:00:00">08:00:00</option>
                                <option value="09:00:00">09:00:00</option>
                                <option value="10:00:00">10:00:00</option>
                                <option value="11:00:00">11:00:00</option>
                                <option value="12:00:00">12:00:00</option>
                                <option value="13:00:00">13:00:00</option>
                                <option value="14:00:00">14:00:00</option>
                                <option value="15:00:00">15:00:00</option>
                                <option value="16:00:00">16:00:00</option>
                                <option value="17:00:00">17:00:00</option>
                                <option value="18:00:00">18:00:00</option>
                                <option value="19:00:00">19:00:00</option>
                            </select><br>

                            <label for="dan">Odabir dana:</label>
                            <input id="dan" type="text" name="dan" placeholder="yyyy-mm-dd" class="highlight-input input-modified"><br>

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


    </body>
</html>