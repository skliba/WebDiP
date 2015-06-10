<?php
error_reporting(-1);
ini_set('display_errors', 'On');
ob_start();
include_once './baza.class.php';
include_once './virtualTime.php';
$baza = new Baza();

$greske = '';




if (isset($_SESSION['userType']) && $_SESSION['userType'] == 3) {
    work();
} else {
    $time = getPomak();
    $id_user = $_GET['id'];
    $log_user = $_SESSION['userId'];
    $query = "select korisnik.username from korisnik where id = $log_user";
    $result = $baza->selectDB($query);
    $arrayzsz = $result->fetch_array();
    $query = "insert into log values(default,'$log_user','$time','Neuspjeli pokušaj pristupa korisnika $arrayzsz[0] na stranicu detalji_postaje.php', 3)";
    $baza->updateDB($query);
    header("Location:failedToAccess.php");
}

function work() {
    $baza = new Baza();

    if (isset($_POST['hiddenId'])) {
        $id_postaja = $_POST['hiddenId'];
    } else {
        $id_postaja = $_GET['id'];
    }


    $query = "select postaja.*, korisnik.username from postaja,korisnik where postaja.id = $id_postaja and korisnik.id = postaja.moderira";
    $result = $baza->selectDB($query);
    $arr = $result->fetch_array();


    global $postajaID;
    global $postajaNaziv;
    global $postajaOpis;
    global $postajaAdresa;
    global $postajaVrsta;
    global $postajaLink;
    global $postajaValid;
    global $postajaModerator;

    $postajaID = $arr[0];
    $postajaNaziv = $arr[1];
    $postajaOpis = $arr[2];
    $postajaAdresa = $arr[3];
    $postajaVrsta = $arr[4];
    $postajaLink = $arr[5];
    if ($arr[6] == 0) {
        $postajaValid = "false";
    } else {
        $postajaValid = "true";
    }
    $postajaModerator = $arr[8];


    if (isset($_POST['subButton'])) {

        if ($_POST['vrstaPostaje'] == 'T' || $_POST['vrstaPostaje'] == 'R') {
            $greske = "";
        } else {
            $greske .= "<p><img src='http://png-2.findicons.com/files/icons/1609/ose_png/256/warning.png'> </p><p> Vrsta postaje može biti samo T ili R (televizija ili radio)</p>";
        }

        if (empty($greske)) {


            if (isset($_POST['change'])) {
                $postajaIdent2 = $_POST['idPostaje'];
                $postajaNaziv2 = $_POST['nazivPostaje'];
                $postajaOpis2 = $_POST['prodNaziv'];
                $postajaAdresa2 = $_POST['adresa'];
                $postajaVrsta2 = $_POST['vrstaPostaje'];
                $postajaLink2 = $_POST['logoLink'];
                $postajaValid2 = $_POST['validnostPostaje'];
                if ($postajaValid2 == "true") {
                    $postajaValid2 = "1";
                } else {
                    $postajaValid2 = "0";
                }
                $postajaModerator2 = $_POST['newModerator'];
                
                $query = "select korisnik.username from korisnik where id = $postajaModerator2";
                $result = $baza->selectDB($query);
                $info = $result->fetch_array();



                $query = "update postaja set naziv = '$postajaNaziv2', opis = '$postajaOpis2', adresa = '$postajaAdresa2', vrstaPostaje = '$postajaVrsta2', logoLink = '$postajaLink2', valid = '$postajaValid2', moderira = '$postajaModerator2' where id = $postajaIdent2";
                $baza->updateDB($query);
                
                $ur = $_SESSION['username'];
                $id = $_SESSION['userId'];
                $time = getPomak();

                $query = "insert into log values(default, '$id', '$time', 'Korisnik $ur je promijenio moderatora i detalje postaje $postajaNaziv2 novi moderator je: $info[0] ', 2)";
                $baza->updateDB($query);


                header("Location:stationList.php");
            } else {
                $postajaIdent1 = $_POST['idPostaje'];

                $postajaNaziv1 = $_POST['nazivPostaje'];

                $postajaOpis1 = $_POST['prodNaziv'];

                $postajaAdresa1 = $_POST['adresa'];

                $postajaVrsta1 = $_POST['vrstaPostaje'];

                $postajaLink1 = $_POST['logoLink'];

                $postajaValid1 = $_POST['validnostPostaje'];
                if ($postajaValid1 == "true") {
                    $postajaValid1 = "1";
                } else {
                    $postajaValid1 = "0";
                }

                $postajaModerator1 = $_POST['moderatorPostaje'];



                $query = "select korisnik.id from korisnik where korisnik.username = '$postajaModerator'";
                $result = $baza->selectDB($query);
                $arrayz = $result->fetch_array();

                $realModerator = $arrayz[0];


                $query = "update postaja set naziv = '$postajaNaziv1', opis = '$postajaOpis1', adresa = '$postajaAdresa1', vrstaPostaje = '$postajaVrsta1', logoLink = '$postajaLink1', valid = $postajaValid1, moderira = $realModerator where id = $id_postaja";
                $baza->updateDB($query);

                $ur = $_SESSION['username'];
                $id = $_SESSION['userId'];
                $time = getPomak();

                $query = "insert into log values(default, '$id', '$time', 'Korisnik $ur je promijenio detalje postaje $postajaNaziv1 ', 2)";
                $baza->updateDB($query);

                header("Location:stationList.php");
            }
        } // ako su greske empty
    } // ako je hittan submit button
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

                    <h2>Detalji o postaji</h2>
                    <hr>
                    <div id="greske">

                        <?php
                        echo $greske;
                        ?>

                    </div>


                    <div id="regcontent">
                        <form method="post" action="detalji_postaje.php" id="myForm">

                            <label for="idPostaje">Id: </label>
                            <input type="text"  name="idPostaje"  class="highlight-input input-modified" id="idPostaje" value="<?php echo $postajaID; ?>" readonly="readonly"><br>

                            <label for="nazivPostaje">Naziv: </label>
                            <input type="text"  name="nazivPostaje"  class="highlight-input input-modified" id="nazivPostaje" value="<?php echo $postajaNaziv; ?>"><br>

                            <label for="prodNaziv">Puni naziv: </label>
                            <input type="text"  name="prodNaziv" class="highlight-input input-modified" id="prodNaziv" value="<?php echo $postajaOpis; ?>"><br>

                            <label for="adresa">Adresa: </label>
                            <input id="adresa" rows="2" name="adresa" class="highlight-input input-modified" value="<?php echo $postajaAdresa; ?>"><br>

                            <label for="logoLink">Logo link:</label>
                            <input type="text" name="logoLink" id="logoLink" class="highlight-input input-modified" size="500" value="<?php echo $postajaLink; ?>"><br>

                            <label for="vrstaPostaje">Vrsta postaje:</label>
                            <input type="text" name="vrstaPostaje" class="highlight-input input-modified" value="<?php echo $postajaVrsta; ?>"><br>

                            <label for="validnostPostaje">Validnost:</label>
                            <input type="text" name="validnostPostaje" class="highlight-input input-modified" value="<?php echo $postajaValid; ?>"><br>

                            <label for="moderatorPostaje">Curr moderator:</label>
                            <input type="text" name="moderatorPostaje" class="highlight-input input-modified" id="moderatorPostaje" value="<?php echo $postajaModerator; ?>" readonly="readonly"><br>

                            <input type="checkbox" name="change" value="yes" id="chk">Promjena moderatora<br>

                            <div id="content"></div>

                            <input type='hidden' name='hiddenId' value='<?php echo $_GET['id'] ?>'>

                            <input type="submit" id="subButton" name="subButton" value="Osvježi postaju">


                        </form>
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

            $("#chk").change(function () {

                if ($("#chk").is(":checked")) {


                    $.ajax({
                        type: 'GET',
                        url: 'moderatorList.php',
                        dataType: 'xml',
                        success: function (data) {

                            var choose = $("<select name='newModerator' id='newModerator' class='highlight-input input-modified'>");

                            $(data).find('korisnik').each(function () {

                                myOption = "<option value='" + $(this).find('id').text() + "'>" + $(this).find('name').text() + "</option><br>";

                                choose.append(myOption);
                            });


                            choose.append("</select>");
                            $("#content").html(choose);



                        }
                    });

                    $("#moderatorPostaje").prop('disabled', true);

                }
                else {
                    $("#content").html("");
                    $("#moderatorPostaje").prop('disabled', false);
                }
            });



        </script>

    </body>

</html>