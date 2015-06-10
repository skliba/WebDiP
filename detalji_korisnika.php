<?php
error_reporting(-1);
ini_set('display_errors', 'On');
ob_start();
include_once './baza.class.php';
include_once './virtualTime.php';
$baza = new Baza();


if (isset($_SESSION['userType']) && $_SESSION['userType'] == 3) {

    $id_user = $_GET['id'];
    $upit = "SELECT * FROM korisnik where username='$id_user'";

    $rezultat = $baza->selectDB($upit);
    $arr = $rezultat->fetch_array();
    $kor_email;
    $kor_ime;
    $kor_prezime;
    $kor_datum;
    $kor_adresa;
    $kor_grad;
    $kor_spol;
    $kor_username;
    $kor_password;


    if ($arr) {
        $kor_email = $arr[1];
        $kor_ime = $arr[2];
        $kor_prezime = $arr[3];
        $kor_datum = $arr[4];
        $kor_adresa = $arr[5];
        $kor_grad = $arr[6];
        $kor_spol = $arr[7];
        $kor_username = $arr[8];
        $kor_password = $arr[9];
        $kor_phone = $arr[16];
    }





    if (isset($_POST['btnUpdate'])) {

        $uname = $_POST['userFirstName'];
        $lastname = $_POST['userLastName'];
        $mail = $_POST['userMail'];
        $date = $_POST['date'];
        $adress = $_POST['userAdress'];
        $city = $_POST['userCity'];
        $sex = $_POST['mbox'];
        $username = $_POST['username'];
        $password = $_POST['userPassword'];

        $upit = "UPDATE korisnik SET ime='$uname', mail='$mail', prezime='$lastname', datumRodenja='$date', adresa='$adress', grad='$city', spol='$sex', password='$password' WHERE username='$username' ";
        $result = $baza->updateDB($upit);


        $time = getPomak();
        $id_user = $_GET['id'];
        $log_user = $_SESSION['userId'];
        $query = "select korisnik.username from korisnik where id = $log_user";
        $result = $baza->selectDB($query);
        $arrayzsz = $result->fetch_array();
        $query = "insert into log values(default,'$log_user','$time','Promjena detalja korisnika $username od strane admina $arrayzsz[0]', 2)";
        $baza->updateDB($query);



        if ($_SESSION['userType'] == 3) {
            header("Location:userList.php");
        } else {

            header("Location:detalji_korisnika.php?id=$username");
        }
    }

    if (isset($_POST['btnActivate'])) {
        $name = $_POST['userFirstName'];
        $lastname = $_POST['userLastName'];
        $mail = $_POST['userMail'];
        $date = $_POST['date'];
        $adress = $_POST['userAdress'];
        $city = $_POST['userCity'];
        $sex = $_POST['mbox'];
        $username = $_POST['username'];
        $password = $_POST['userPassword'];

        $query = "select * from korisnik where username='$username'";

        $rez = $baza->selectDB($query);
        $arr1 = $rez->fetch_array();
        if ($arr1[13] == '1') {
            $query = "update korisnik set vrstaStatusa_id = 3 where username = '$username'";
            $rez = $baza->updateDB($query);

            $time = getPomak();
            $id_user = $_GET['id'];
            $log_user = $_SESSION['userId'];
            $query = "select korisnik.username from korisnik where id = $log_user";
            $result = $baza->selectDB($query);
            $arrayzsz = $result->fetch_array();
            $query = "insert into log values(default,'$log_user','$time','Bannan korisnik $username od strane admina $arrayzsz[0]', 2)";
            $baza->updateDB($query);

            header("Location:detalji_korisnika.php?id=$username");
        } else {
            $query = "update korisnik set vrstaStatusa_id = 1 where username = '$username'";
            $rez = $baza->updateDB($query);
            header("Location:detalji_korisnika.php?id=$username");

            $time = getPomak();
            $id_user = $_GET['id'];
            $log_user = $_SESSION['userId'];
            $query = "select korisnik.username from korisnik where id = $log_user";
            $result = $baza->selectDB($query);
            $arrayzsz = $result->fetch_array();
            $query = "insert into log values(default,'$log_user','$time','Aktiviran korisnik $username od strane admina $arrayzsz[0]', 2)";
            $baza->updateDB($query);
        }
    }
    if (isset($_POST['setModeratorBtn'])) {

        $username = $_POST['username'];

        $query = "select tipKorisnika_id from korisnik where username = '$username'";
        $result = $baza->selectDB($query);
        $arr = $result->fetch_array();

        if ($arr[0] == '2') {
            $query = "update korisnik set tipKorisnika_id = 1 where username = '$username'";
            $rez = $baza->updateDB($query);

            $time = getPomak();
            $id_user = $_GET['id'];
            $log_user = $_SESSION['userId'];
            $query = "select korisnik.username from korisnik where id = $log_user";
            $result = $baza->selectDB($query);
            $arrayzsz = $result->fetch_array();
            $query = "insert into log values(default,'$log_user','$time','Oduzeta moderatorska prava korisniku $username od strane admina $arrayzsz[0]', 2)";
            $baza->updateDB($query);



            header("Location:detalji_korisnika.php?id=$username");
        } else {
            $query = "update korisnik set tipKorisnika_id = 2 where username = '$username'";
            $rez = $baza->updateDB($query);

            $time = getPomak();
            $id_user = $_GET['id'];
            $log_user = $_SESSION['userId'];
            $query = "select korisnik.username from korisnik where id = $log_user";
            $result = $baza->selectDB($query);
            $arrayzsz = $result->fetch_array();
            $query = "insert into log values(default,'$log_user','$time','Dana moderatorska prava korisniku $username od strane admina $arrayzsz[0]', 2)";
            $baza->updateDB($query);


            header("Location: detalji_korisnika.php?id=$username");
        }
    }


    $id = $_SESSION['userId'];
    $upit1 = "select * from korisnik where id = '$id'";
    $result = $baza->selectDB($upit1);
    $arr2 = $result->fetch_array();
    $name = $arr2[8];
    $time = $_SESSION['time'];
} else {

    $time = getPomak();
    $id_user = $_GET['id'];
    $log_user = $_SESSION['userId'];
    $query = "select korisnik.username from korisnik where id = $log_user";
    $result = $baza->selectDB($query);
    $arrayzsz = $result->fetch_array();
    $query = "insert into log values(default,'$log_user','$time','Neuspjeli pokušaj pristupa korisnika $arrayzsz[0] na stranicu detalji_korisnika.php', 3)";
    
    $baza->updateDB($query);


    header("Location:failedToAccess.php");
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

                    <h2>Detalji o korisniku</h2>
                    <hr>

                    <div id="greske">

                        <?php
                        if (!empty($greska)) {
                            echo $greska;
                        }
                        ?>
                    </div>

                    <div id="regcontent">
                        <form method="post" action="detalji_korisnika.php">

                            <label for="ime">Ime: </label>
                            <input type="text" name="userFirstName"  id="ime" class="highlight-input" value="<?php echo $kor_ime ?>" ><br>

                            <label for="prezime">Prezime: </label>
                            <input type="text" name="userLastName"  id="prezime" class="highlight-input" value="<?php echo $kor_prezime ?>"><br>

                            <label for="adresa">Adresa: </label>
                            <input id="adresa" name="userAdress" class="highlight-input" value="<?php echo $kor_adresa ?>"><br>

                            <label for="grad">Grad:</label>
                            <input type="text" name="userCity" id="grad" class="highlight-input" value="<?php echo $kor_grad ?>"><br>


                            <label for="mail">Email:</label>
                            <input type="email" name="userMail" id="mail" class="highlight-input" value="<?php echo $kor_email ?>"><br>


                            <label for="uname">Korisnčko ime: </label>
                            <input type="text" name="username" id="uname" class="highlight-input" value="<?php echo $kor_username ?>" readonly="readonly"><br>


                            <label for="pw">Lozinka: </label>
                            <input id="pw" type="text" name="userPassword" class="highlight-input" value="<?php echo $kor_password ?>"><br>

                            <label for="datum">Datum rođenja: </label>
                            <input type="text" name="date" id="datum" class="highlight-input" value="<?php echo $kor_datum ?>"> <br>

                            <label for="mbox">Spol: </label>
                            <input type="text" name="mbox" class="highlight-input" value="<?php echo $kor_spol; ?>" ><br>

                            <input type="submit" id="subButton" name="btnUpdate" value="Update user">
                            <input type="<?php
                            if ($_SESSION['userType'] == '3') {
                                echo "submit";
                            } else {
                                echo "hidden";
                            }
                            ?>" 
                                   name="btnActivate" id="subButton" value="<?php
                                   $qu = "select * from korisnik where username='$id_user'";
                                   $quu = $baza->selectDB($qu);
                                   $rr = $quu->fetch_array();
                                   if ($rr[13] == '3') {
                                       echo "Aktiviraj";
                                   } else {
                                       echo "Deaktiviraj";
                                   }
                                   ?>">
                            <input type="submit" value="<?php
                            $query = "select * from korisnik where username = '$id_user'";
                            $rez = $baza->selectDB($query);
                            $arrays = $rez->fetch_array();
                            if ($arrays[12] == '2') {
                                echo "Registrirani korisnik";
                            } else {
                                echo "Moderator";
                            }
                            ?>" id="setModeratorBtn" name="setModeratorBtn">
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


    </body>

</html>