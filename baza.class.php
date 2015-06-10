<?php
    class Baza {
        
        const server = "localhost";
        const korisnik = "WebDiP2014x031";
        const lozinka = "admin_VnxR";
        const baza = "WebDiP2014x031";

        private function spojiDB(){
            $mysqli = new mysqli(self::server,self::korisnik,self::lozinka,self::baza);
            $mysqli->set_charset("utf8");
            if($mysqli->connect_errno){
                echo "Neuspješno spajanje na bazu: ".$mysqli->connect_errno.", ".
                    $mysqli->connect_error;
            }
            return $mysqli;
        }
        
        function selectDB($upit){
            $veza = $this->spojiDB();
            $rezultat = $veza->query($upit) or trigger_error("Greška kod upita: {$upit} - ".
                    "Greška: ".$veza->error . " " . E_USER_ERROR);
            
            if(!$rezultat){
                $rezultat = null;
            }        
            $veza->close();
            return $rezultat;
        }
        
        function updateDB($upit,$skripta=''){
            $veza = $this->spojiDB();
            if($rezultat = $veza->query($upit)){
                $veza->close();
                
                if($skripta != ''){
                    header("Location: $skripta");
                }
                
                return $rezultat;
                
            }else{
                echo "Pogreška: ".$veza->error;
                $veza->close();
                return $rezultat; 
            }
        }
        
        function closeDB() {
            $this->mysqli->close();
        }
    }
?>