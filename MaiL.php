<?php
$ladate  =  date ("   d M Y, G:i:s ");  //*****declare variable*/
$to = "admin@yourmail.com";  //ici mettre votre adresse e-mail
$remaddr = $_SERVER['REMOTE_ADDR'];
$remreq = $_SERVER['REQUEST_URI'];
$remmeth = $_SERVER['REQUEST_METHOD'];
$usragnt = $_SERVER['HTTP_USER_AGENT'];
$host = $_SERVER[REMOTE_ADDR];
$provenance = $HTTP_REFERER;
$lang = $HTTP_ACCEPT_LANGUAGE;
$from = "no-reply@yourmail.com"; /*votre email*/
$msg .= "Request URL : $remreq \n";         
$msg = "Subject: A Fucker just got owned on Sweetbox V4 Central\n";
        $msg .= "His IP : $remaddr Just got Catched the $ladate \n";
        $msg .= "Request URL : $remreq \n";
        $msg .= "Navigator use : $usragnt \n";
        $msg .= "Hack USe : $remmeth \n";
        $msg .= "DNS : $host \n";
        $msg .= "Country : $provenance \n";
        $msg .= "Language : $lang \n";
mail($to, "Sweetbox is under attack", $msg);
?>