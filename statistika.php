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
    $log_user = $_SESSION['userId'];
    $query = "select korisnik.username from korisnik where id = $log_user";
    $result = $baza->selectDB($query);
    $arrayzsz = $result->fetch_array();
    $query = "insert into log values(default,'$log_user','$time','Neuspjeli pokušaj pristupa korisnika $arrayzsz[0] na stranicu statistika.php', 3)";
    $baza->updateDB($query);
    header("Location: failedToAccess.php");
} else {
    work();
}

function work() {
    $baza = new Baza();

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
        <link rel="stylesheet" type="text/css" href="css/print.css" media="print">
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
                        <li><a href="pregled_dnevnika.php" >Pregled dnevnika</a></li>
                        <li><a href="statistika.php" id="curr">Statistika</a></li>
                        <li><a href="adminpanel.php" >Admin panel</a></li>
                        <li><a href="dokumentacija.html">Dokumentacija</a></li>
                        <li><a href="o_autoru.html">O autoru</a></li>
                    </ul>
                </nav>
            </div>       
            <div id="right">
                <section id="sadrzaj">
                    <div id="notShown">

                    Sortiraj po: <select id="sortBy" name="sortBy" class="highlight-input">
                        <option value="-1">-- Odaberite po čemu sortirati -- </option>
                        <option value="1">Korisniku</option>
                        <option value="2">Vremenskom intervalu</option>
                        <option value="3">Datumu</option>
                        <option value="4">Vrsti loga</option>    
                    </select>


                    <div id="content">

                    </div>
                    <input type='button' value='Prihvati' class='confirm' id='confirm'>
                    <input type='button' value='Prikaz za print' class='confirm' id='printBtn'>
                    
                    </div>
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


            $(document).ready(function () {


                $("#sortBy").change(function () {


                    if ($("#sortBy").val() == 1) {

                        $("#content").html("");
                        $("#content").append("Korisnik: <input type='text' id='od' name='od' class='highlight-input'><br>");
                    } else if ($("#sortBy").val() == 2) {

                        $("#content").html("");
                        $("#content").append("Od: <input type='text' id='od' name='od' class='highlight-input' placeholder='yyyy-mm-dd hh:mm:ss'><br> \n"
                                + "Do: <input type='text' id='do' name='do' class='highlight-input' placeholder='yyyy-mm-dd hh:mm:ss''><br>");
                    }
                    else if ($("#sortBy").val() == 3) {

                        $("#content").html("");
                        $("#content").append("Datum: <input type='text' id='od' name='od' class='highlight-input' placeholder='yyyy-mm-dd hh-mm-ss'><br>");
                    }
                    else if ($("#sortBy").val() == 4) {

                        $("#content").html("");
                        $("#content").append("Vrsta: <select id='typeLog' name='typeLog' class='highlight-input'><option value='1'>Log prijava/odjava</option><option value='2'>Log za bazu</option><option value='3'>Log ostalo</option> </select>");
                    } else {
                        $("#content").html("");
                    }


                });
                $("#confirm").click(function () {


                    var value = $("#sortBy").val();
                    if (value == 1) {
                        od = $("#od").val();
                        $.ajax({
                            type: 'POST',
                            url: './generateStatistics.php',
                            dataType: 'xml',
                            data: {
                                'value': value,
                                'od': od
                            },
                            success: function () {
                                fetch();
                            }


                        });
                    }
                    else if (value == 2) {
                        var od = $("#od").val();
                        var doo = $("#do").val();
                        $.ajax({
                            type: 'POST',
                            url: './generateStatistics.php',
                            dataType: 'xml',
                            data: {
                                'value': value,
                                'od': od,
                                'do': doo
                            },
                            success: function () {
                                fetch();
                            }

                        });
                    }
                    else if (value == 3) {
                        var od = $("#od").val();
                        $.ajax({
                            type: 'POST',
                            url: './generateStatistics.php',
                            dataType: 'xml',
                            data: {
                                'value': value,
                                'od': od
                            },
                            success: function () {
                                fetch();
                            }

                        });
                    }
                    else if (value == 4) {

                        var od = $("#typeLog").val();
                        $.ajax({
                            type: 'POST',
                            url: './generateStatistics.php',
                            dataType: 'xml',
                            data: {
                                'value': value,
                                'od': od
                            },
                            success: function () {
                                fetch();
                            }


                        });
                    }
                });



                $("#printBtn").click(function () {

                    //Popup($("#userList").html());
                    window.print();

                });

                function Popup(data) {
                    
                    var mywindow = window.open('', 'content', 'height=400,width=600');
                    mywindow.document.write('<html><head><title>Prikaz za print</title>');
                    mywindow.document.write('<link rel="stylesheet" type="text/css" href="css/srajkov.css">');
                    mywindow.document.write('<link rel="stylesheet" type="text/css" href="css/srajkov_mobitel.css" media="screen and (max-width:450px)">');
                    mywindow.document.write('<link rel="stylesheet" type="text/css" href="css/srajkov_tablet.css" media="screen and (min-width:450px) and (max-width:800px)">');
                    mywindow.document.write('<link rel="stylesheet" type="text/css" href="css/srajkov_pc.css" media="screen and (min-width:800px) and (max-width:1000px)">');
                    mywindow.document.write('<link rel="stylesheet" type="text/css" href="css/srajkov_tv.css" media="screen and (min-width:1000px)">');
                    mywindow.document.write('<link rel="stylesheet" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">');
        
                    
                    mywindow.document.write('</head><body >');
                    mywindow.document.write(data);
                    mywindow.document.write('</body></html>');

                    mywindow.document.close(); // necessary for IE >= 10
                    mywindow.focus(); // necessary for IE >= 10

                    mywindow.print();
                    mywindow.close();

                    return true;
                }

            });

        </script>
        <script src="js/fetchStatistics.js"></script>
        <script src="js/getDataTables.js"></script>

    </body>
</html>