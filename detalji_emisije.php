<?php
error_reporting(-1);
ini_set('display_errors', 'On');
ob_start();
include_once './baza.class.php';
include_once './virtualTime.php';
$baza = new Baza();

$greske = '';




if (isset($_SESSION['userType']) && $_SESSION['userType'] > 1) {
    work();
} else {
    $time = getPomak();
    $id_user = $_GET['id'];
    $log_user = $_SESSION['userId'];
    $query = "select korisnik.username from korisnik where id = $log_user";
    $result = $baza->selectDB($query);
    $arrayzsz = $result->fetch_array();
    $query = "insert into log values(default,'$log_user','$time','Neuspjeli pokušaj pristupa korisnika $arrayzsz[0] na stranicu detalji_emisije.php', 3)";
    $baza->updateDB($query);
    header("Location:failedToAccess.php");
}

function work() {
    $baza = new Baza();

    if (isset($_POST['hiddenId'])) {
        $id_emisija = $_POST['hiddenId'];
    } else {
        $id_emisija = $_GET['id'];
    }


    $query = "select emisija.*, producentskaKuca.ime, postaja.naziv from postaja,producentskaKuca,emisija where producentskaKuca.id = emisija.kuca_id and emisija.id = $id_emisija and postaja.id = emisija.postaja_id";
    $result = $baza->selectDB($query);
    $arr = $result->fetch_array();


    global $emisijaID;
    global $emisijaNaziv;
    global $producentskaKuca;
    global $trajanje;
    global $emisijaOpis;
    global $emisijaOcjena;
    global $postaja;
    global $validnost;
    global $dan;

    $emisijaID = $arr[0];
    $producentskaKuca = $arr[11];
    $emisijaNaziv = $arr[2];
    $trajanje = $arr[3];
    $emisijaOpis = $arr[4];
    $emisijaOcjena = $arr[5];
    $postaja = $arr[12];

    if ($arr[8] == 0) {
        $validnost = "false";
    } else {
        $validnost = "true";
    }
    $dan = $arr[10];


    if (isset($_POST['subButton'])) {

        $id = $_POST['idEmisije'];
        $naziv = $_POST['nazivEmisije'];
        $kuca = $_POST['producentskaKuca'];
        $trajanje = $_POST['trajanje'];
        $opis = $_POST['opis'];
        $ocjena = $_POST['ocjena'];
        $validnost = $_POST['validnost'];
        $dan = $_POST['dan'];
        $termin = $_POST['termin'];
        $stationChoice = $_POST['postaja'];

        $query = "select termin from emisija where termin = '$termin' and zaDan = '$dan' and emisija.postaja_id = '$stationChoice'";
        $result = $baza->selectDB($query);
        $arr = $result->fetch_array();
        if ($arr) {
            header("Location:showDateOccupied.php");
        } else {

            if ($validnost == "true") {
                $validnost = "1";
            } else {
                $validnost = "0";
            }


            $query = "update emisija set naziv = '$naziv', kuca_id = '$kuca', trajanje = '$trajanje', opis = '$opis', ocjena = '$ocjena', postaja_id = '$stationChoice', validShow = '$validnost', termin = '$termin', zaDan = '$dan' where id = $id";
            $baza->updateDB($query);


            $ur = $_SESSION['username'];
            $id = $_SESSION['userId'];
            $time = getPomak();

            $query = "insert into log values(default, '$id', '$time', 'Korisnik $ur je promijenio detalje emisije $emisijaNaziv', 2)";
            $baza->updateDB($query);

            header("Location:moderatorPanel.php");
        }
    } // ako je hittan submit button


    if (isset($_POST['deactivate'])) {

        $id = $_POST['idEmisije'];

        $query = "update emisija set zaDan = '0000-00-00' where id = $id";
        $baza->updateDB($query);


        $ur = $_SESSION['username'];
        $id = $_SESSION['userId'];
        $time = getPomak();

        $query = "insert into log values(default, '$id', '$time', 'Korisnik $ur je deaktivirao emisiju $emisijaNaziv', 2)";
        $baza->updateDB($query);
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Vjezba - Stefano Kliba</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="author" content="Stefano Kliba">
        <meta name="description" content="Prva zadaca iz kolegija WebDiP">
        <meta name="dcterms.created" content="10.03.2015.">
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
                        <li><a href="createTvStation.php">Dodaj tv postaju</a></li>
                        <li><a href="stationList.php">Lista postaja</a></li>
                        <li><a href="config.php">Konfiguracija stranice</a></li>
                        <li><a href="pregled_dnevnika.php">Pregled dnevnika</a></li>
                        <li><a href="statistika.php">Statistika</a></li>
                        <li><a href="adminpanel.php">Admin panel</a></li>
                        <li><a href="dokumentacija.html">Dokumentacija</a></li>
                        <li><a href="o_autoru.html">O autoru</a></li>

                    </ul>
                </nav>
            </div>       
            <div id="right">
                <section id="sadrzaj">

                    <h2>Detalji o emisiji</h2>
                    <hr>
                    <div id="greske">

                        <?php
                        echo $greske;
                        ?>

                    </div>


                    <div id="regcontent">
                        <form method="post" action="detalji_emisije.php" id="myForm">

                            <label for="idEmisije">Id: </label>
                            <input type="text"  name="idEmisije"  class="highlight-input input-modified" id="idEmisije" value="<?php echo $emisijaID; ?>" readonly="readonly"><br>

                            <label for="nazivEmisije">Naziv: </label>
                            <input type="text"  name="nazivEmisije"  class="highlight-input input-modified" id="nazivEmisije" value="<?php echo $emisijaNaziv; ?>"><br>

                            <label for="producentskaKuca">Prod kuća:</label>
                            <select name='producentskaKuca' class='highlight-input input-modified'>
                                <?php
                                $query = "select producentskaKuca.id, producentskaKuca.ime from producentskaKuca, emisija where emisija.kuca_id = producentskaKuca.id and emisija.id = $emisijaID ";
                                $rez = $baza->selectDB($query);
                                while ($arrayzs = $rez->fetch_array()) {
                                    echo "<option value='" . $arrayzs[0] . "'>" . $arrayzs[1] . " trenutna kuća</option>";
                                }
                                $query = "select producentskaKuca.id, producentskaKuca.ime from producentskaKuca where producentskaKuca.ime not in ('$producentskaKuca')";
                                $rez = $baza->selectDB($query);
                                while ($arrayzsz = $rez->fetch_array()) {
                                    echo "<option value='" . $arrayzsz[0] . "'>" . $arrayzsz[1] . "</option>";
                                }
                                ?>
                            </select><br>

                            <label for="trajanje">Trajanje: </label>
                            <input id="trajanje" name="trajanje" class="highlight-input input-modified" value="<?php echo $trajanje; ?>"><br>

                            <label for="opis">Opis:</label>
                            <input type="text" name="opis" id="opis" class="highlight-input input-modified" size="500" value="<?php echo $emisijaOpis; ?>"><br>

                            <label for="ocjena">Ocjena:</label>
                            <input type="text" name="ocjena" class="highlight-input input-modified" value="<?php echo $emisijaOcjena; ?>"><br>

                            <label for="postaja">Postaja:</label>
                            <select name='postaja' class='highlight-input input-modified'>
                                <?php
                                $modder = $_SESSION['userId'];
                                $query = "select postaja.id, postaja.naziv from postaja where valid = 1 and postaja.moderira = $modder";
                                $rez = $baza->selectDB($query);
                                while ($arrayzs = $rez->fetch_array()) {
                                    echo "<option value='" . $arrayzs[0] . "'>" . $arrayzs[1] . "</option>";
                                }
                                ?>
                            </select><br>

                            <label for="validnost">Validnost:</label>
                            <input type="text" name="validnost" class="highlight-input input-modified" id="validnost" value="<?php echo $validnost; ?>"><br>

                            <label for="dan">Dan:</label>
                            <input type="text" name="dan" class="highlight-input input-modified" id="dan" value="<?php echo $dan; ?>"><br>

                            <label for="termin">Termin:</label>
                            <select name='termin' class='highlight-input input-modified'>
                                <?php
                                $query = "select emisija.id, emisija.termin from emisija where zaDan = '$dan' and emisija.id = $emisijaID";
                                $rez = $baza->selectDB($query);
                                while ($arrayzs = $rez->fetch_array()) {
                                    echo "<option value='" . $arrayzs[1] . "'>" . $arrayzs[1] . " trenutno vrijeme</option>";
                                }
                                ?>
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

                            <input type='hidden' name='hiddenId' value='<?php echo $_GET['id'] ?>'>


                            <input type="submit" id="subButton" name="subButton" value="Osvježi postaju">
                            <input type="submit" name="deactivate" value="<?php 
                            $id = $_GET['id'];
                            $qu = "select zaDan from emisija where id = '$id'";
                            $rez1 = $baza->selectDB($qu);
                            $arr = $rez1->fetch_array();
                            if($arr[0] == '0000-00-00'){
                                echo "Aktiviraj emisiju";
                            }
                            else {
                                echo "Deaktiviraj emisiju";
                            }?>" class="buttonz">
                            <input type="button" id="generateContent" name="materialz" value="Dodaj materijal" class="buttonz">



                        </form>

                        <div id="materijali">

                        </div>
                    </div>


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


        <script>


            $("#generateContent").click(function () {


                var mate = $("<form method='post' enctype='multipart/form-data' action='upload_file.php'>");
                mate.append("<input type='file' name='uploadedFile' id='answer'><br>");
                mate.append("<input type='hidden' name='hiddenId' value='<?php echo $_GET['id'] ?>'>");
                mate.append("<input type='submit' name='subBtn' id='subBtn' value='Upload' class='buttonz'> </form>");

                $("#materijali").html(mate);


            });



        </script>

    </body>

</html>