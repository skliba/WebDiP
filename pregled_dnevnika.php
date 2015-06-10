<?php
error_reporting(-1);
ini_set('display_errors', 'On');
ob_start();
include_once './baza.class.php';
include_once './virtualTime.php';
$baza = new Baza();

$greske = '';

if (!isset($_SESSION['userType']) || $_SESSION['userType'] != 3 || $_SESSION['loggedin'] == false) {
    $time = getPomak();
    $id_user = $_GET['id'];
    $log_user = $_SESSION['userId'];
    $query = "select korisnik.username from korisnik where id = $log_user";
    $result = $baza->selectDB($query);
    $arrayzsz = $result->fetch_array();
    $query = "insert into log values(default,'$log_user','$time','Neuspjeli pokušaj pristupa korisnika $arrayzsz[0] na stranicu pregled_dnevnika.php', 3)";
    $baza->updateDB($query);
    header("Location: failedToAccess.php");
} else {
    work();
}

function work() {
    $baza = new Baza();
    $query = "select log.*, korisnik.username, vrstaLog.naziv from log,korisnik,vrstaLog where log.korisnik_id = korisnik.id and log.vrstaLog_id = vrstaLog.id order by 1";
    $result = $baza->selectDB($query);

    $xml = new DomDocument("1.0", "UTF-8");
    $log = $xml->createElement("log");
    $log = $xml->appendChild($log);

    while ($arr = $result->fetch_array()) {

        $action = $xml->createElement("akcija");
        $action = $log->appendChild($action);

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
    $xml->save("dnevnik.xml");
    
    
    
    $time = getPomak();
    $log_user = $_SESSION['userId'];
    $query = "select korisnik.username from korisnik where id = $log_user";
    $result = $baza->selectDB($query);
    $arrayzsz = $result->fetch_array();
    $query = "insert into log values(default,'$log_user','$time','Korisnik $arrayzsz[0] zatražio pregled dnevnika', 2)";
    $baza->updateDB($query);
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
                        <li><a href="pregled_dnevnika.php" id="curr">Pregled dnevnika</a></li>
                        <li><a href="statistika.php">Statistika</a></li>
                        <li><a href="adminpanel.php" >Admin panel</a></li>
                        <li><a href="dokumentacija.html">Dokumentacija</a></li>
                        <li><a href="o_autoru.html">O autoru</a></li>
                    </ul>
                </nav>
            </div>       
            <div id="right">
                <section id="sadrzaj">
                    
                  
                    <div id="userList">

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
        <script type="text/javascript">

            var myTable = $("<table id='userListTable' class='display'>");
            myTable.append("<thead><tr><th>Username</th><th>Vrijeme</th><th>Akcija</th><th>Vrsta loga</th></tr></thead>");

            $(document).ready(function () {

                $.ajax({
                    type: 'GET',
                    url: './dnevnik.xml',
                    dataType: 'xml',
                    success: function (data) {

                        var tbody = $("<tbody>");

                        $(data).find('akcija').each(function () {

                            var row = "<tr>";

                            row += "<td>" + $(this).find('korisnik').text() + "</td>";
                            row += "<td>" + $(this).find('vrijeme').text() + "</td>";
                            row += "<td>" + $(this).find('opis').text() + "</td>";
                            row += "<td>" + $(this).find('vrstaLog').text() + "</td>";

                            row += "</tr>";
                            tbody.append(row);

                        });

                        tbody.append("</tbody>");
                        myTable.append(tbody);
                        $("#userList").html(myTable);
                        dataTablez();
                    }
                });


            });


        </script>
        <script src='js/fetchStatistics.js'></script>
    </body>
</html>