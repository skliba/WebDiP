<?php

include_once "./baza.class.php";

$baza = new Baza();

$query = "select * from postaja";
$result = $baza->selectDB($query);

$xml = new DomDocument("1.0","UTF-8");

$tvPostaje = $xml->createElement("tvpostaje");
$tvPostaje = $xml->appendChild($tvPostaje);

if ($result->num_rows != 0) {


    while($arrayPostaje = $result->fetch_array()){
        
        $postaja = $xml->createElement("postaja");
        $postaja = $tvPostaje->appendChild($postaja);
        
        $postajaId = $xml->createElement("postajaId", $arrayPostaje[0]);
        $postajaId = $postaja->appendChild($postajaId);
        
        $postajaNaziv = $xml->createElement("postajaNaziv", $arrayPostaje[1]);
        $postajaNaziv = $postaja->appendChild($postajaNaziv);
        
        $postajaLogo = $xml->createElement("postajaLogo", $arrayPostaje[5]);
        $postajaLogo = $postaja->appendChild($postajaLogo);
        
        $postajaOpis = $xml->createElement("postajaOpis", $arrayPostaje[2]);
        $postajaOpis = $postaja->appendChild($postajaOpis);

    }
    $xml->formatOutput = true;
    $string_value = $xml->saveXML();
    $xml->save("listaTvPostaja.xml");

    
}
?>