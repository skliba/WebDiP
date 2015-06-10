<?php
error_reporting(-1);
ini_set('display_errors', 'On');
ob_start();

include_once './baza.class.php';
include_once './virtualTime.php';
$baza = new Baza();


if (isset($_SESSION['userType']) && $_SESSION['userType'] > 1) {

    $mod = $_SESSION['userId'];
    $id_postaje = $_GET['id'];

    $upit = "select emisija.id, emisija.naziv, emisija.trajanje, emisija.ocjena, korisnik.username, producentskaKuca.ime, emisija.validShow from korisnik,emisija,postaja, producentskaKuca where emisija.postaja_id = postaja.id and emisija.korisnik_id = korisnik.id and korisnik.id = $mod and postaja_id = $id_postaje and producentskaKuca.id = emisija.kuca_id";
    $rezultat = $baza->selectDB($upit);

    $xml = new DomDocument("1.0", "UTF-8");

    $emisija = $xml->createElement("emisija");
    $emisija = $xml->appendChild($emisija);


    while ($red = $rezultat->fetch_array()) {
        $podaci = $xml->createElement("podaci");
        $podaci = $emisija->appendChild($podaci);
        
        $id = $xml->createElement("id", $red[0]);
        $id = $podaci->appendChild($id);

        $naziv = $xml->createElement("naziv", $red[1]);
        $naziv = $podaci->appendChild($naziv);
        
        $trajanje = $xml->createElement("trajanje", $red[2]);
        $trajanje = $podaci->appendChild($trajanje);
        
        $ocjena = $xml->createElement("ocjena", $red[3]);
        $ocjena = $podaci->appendChild($ocjena);
        
        $producentskaKuca = $xml->createElement("producentskaKuca", $red[5]);
        $producentskaKuca = $podaci->appendChild($producentskaKuca);
        

        $designatedMod = $xml->createElement("moderator", $red[4]);
        $designatedMod = $podaci->appendChild($designatedMod);
        
        if($red[6] == 1){
            $validness = "true";
        }
        else{
            $validness = "false";
        }

        $validna = $xml->createElement("validna", $validness);
        $validna = $podaci->appendChild($validna);
        
    }

    $xml->formatOutput = true;
    $string_value = $xml->saveXML();
    $xml->save("showList.xml");
    
    
} else {
    $time = getPomak();
    $log_user = $_SESSION['userId'];
    $query = "select korisnik.username from korisnik where id = $log_user";
    $result = $baza->selectDB($query);
    $arrayzsz = $result->fetch_array();
    $query = "insert into log values(default,'$log_user','$time','Neuspjeli pokušaj pristupa korisnika $arrayzsz[0] na stranicu showList.php', 3)";
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
                        <li><a href="index.php">Početna stranica</a></li>
                        <li><a href="createShow.php">Kreiraj emisiju</a></li>
                        <li><a href="stationModeratorList.php" id="curr">Moderirane postaje</a></li>
                        <li><a href="sendInfo.php">Pošalji obavijest</a></li>
                        <li><a href="moderatorPanel.php" >Moderator panel</a></li>
                        <li><a href="dokumentacija.html">Dokumentacija</a></li>
                        <li><a href="o_autoru.html">O autoru</a></li>
                    </ul>
                    </ul>
                </nav>
            </div>       
            
                <section id="sadrzaj">
                    <h3 id="popisKorisnikaTitle">Popis emisija za postaju </h3>
                    <div id="userList">

                    </div>

                </section>    
              
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
                myTable.append("<thead><tr><th>Naziv</th><th>Trajanje</th><th>Ocjena</th><th>Producentska kuća</th><th>Validnost</th><th>Moderator</th></tr></thead>");
                $(document).ready(function () {
                $.ajax({
                    type: 'GET',
                    url: './showList.xml',
                    dataType: 'xml',
                    success: function (data) {

                        var tbody = $("<tbody>");

                        $(data).find('podaci').each(function () {
                            var red = "<tr>";

                            red += "<td>" + $(this).find('naziv').text() + "</td>";
                            red += "<td>" + $(this).find('trajanje').text() + "</td>";
                            red += "<td>" + $(this).find('ocjena').text() + "</td>";
                            red += "<td>" + $(this).find('producentskaKuca').text() + "</td>";
                            red += "<td>" + $(this).find('validna').text() + "</td>";
                            red += "<td><a href='detalji_emisije.php?id=" + $(this).find('id').text() + "'>" + $(this).find('moderator').text() + "</a></td>";

                            red += "</tr>";
                            tbody.append(red);
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


