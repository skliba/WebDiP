<?php
include_once './baza.class.php';
$baza = new Baza();
include_once './fetch.php';
session_start();
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
                        <?php
                        if (isset($_SESSION['userType']) && $_SESSION['userType'] == 3 && $_SESSION['loggedin'] == true) {
                            echo "<li><a href='adminpanel.php'>Admin panel</a></li>";
                        }
                        ?>
                        <?php
                        if (isset($_SESSION['userType']) && $_SESSION['userType'] > 1  && $_SESSION['loggedin'] == true) {
                            echo "<li><a href='moderatorPanel.php'>Moderator panel</a></li>";
                        }
                        ?>
                        <li><a href="registracija.php">Registriraj se</a></li>
                        <li><a href="dokumentacija.html">Dokumentacija</a></li>
                        <li><a href="o_autoru.html">O autoru</a></li>
                    </ul>
                </nav>
            </div>    
            <div id="right">
                <section id="sadrzaj">


                    <div id="generatedTableStations">

                    </div>

                </section>
            </div>    

            <footer id="podnozje">
                <p><strong>Vrijeme potrebno za rješavanje aktivnog dokumenta:</strong> 40 minuta</p>
                <p><strong>Vrijeme potrebno za rješavanje cijelog rješenja: </strong> 11h i 20 minuta </p>
                <div class="htmlval">
                    <figure>
                        <a href="http://validator.w3.org/check/referer" target="_blank">
                            <img src="http://blog.boyet.com/blog/files/media/image/valid-html5-blue.png" alt="Valid HTML5!">
                        </a>
                        <figcaption>HTML5 Validator</figcaption>
                    </figure>


                    <figure>
                        <a href="http://jigsaw.w3.org/css-validator/check/referer" target="_blank">
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

                var myTable = $("<table id='userListTable' class='display'>");
                myTable.append("<thead><tr><th>Logo postaje</th><th>Ime postaje</th><th>Opis</th></tr></thead>");
                $.ajax({
                    type: 'GET',
                    url: './listaTvPostaja.xml',
                    dataType: 'xml',
                    success: function (data) {

                        var tbody = $("<tbody>");

                        $(data).find('postaja').each(function () {
                            var red = "<tr>";
                            red += "<td id='picTd'><a href='postajaInfo.php?id=" + $(this).find('postajaId').text() + "'><img src=" + $(this).find('postajaLogo').text() + "></a></td>";
                            red += "<td>" + $(this).find('postajaNaziv').text() + "</td>";
                            red += "<td>" + $(this).find('postajaOpis').text() + "</td>";
                            

                            red += "</tr>";
                            tbody.append(red);
                        });

                        tbody.append("</tbody>");
                        myTable.append(tbody);

                        $("#generatedTableStations").html(myTable);
                        dataTablez();

                    }
                });
                
                
                

            });


        </script>
        <script src='js/fetchStatistics.js'></script>
    </body>
</html>
