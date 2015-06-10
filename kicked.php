<?php
error_reporting(-1);
ini_set('display_errors', 'On');
ob_start();
session_start();

header('Refresh: 3; url=prijava.php');

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
    </head>
    <body>

        <div id="wrapper">
            <header id="zaglavlje">
                
                    <div id="headerDiv">
                        <h2>Online TV postaje</h2>
                    </div>
                </header>
                <div id="left">
                    <img src="img/icons/crashIcon.jpg" style="height:100px; width:100px;">
                </div>    
                <div id="right">
                    <section id="sadrzaj">
                        
                        
                        <h4>Vaš račun je bannan ste s ove stranice i nemate više pristup na njoj</h4>
                        <h4>Povratak na stranicu za prijavu za 3 sekunde</h4>

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


    </body>

</html>
