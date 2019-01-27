<?php

//on definier les user agent dans array via la varaible bots

$bots = array("Googlebot/2.1 (+http://www.google.com/bot.html)","msnbot-media/1.0 (+http://search.msn.com/msnbot.htm)",
"Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)",
"Googlebot/2.1","msnbot/1.0","msnbot/0.3",
"Yahoo! Slurp",
"VoilaBot BETA 1.2",
"ZyBorg/1.0","Mozilla/4.0 compatible ZyBorg/1.0 (wn.zyborg@looksmart.net; http://www.WISEnutbot.com)",
"Mozilla/4.0 compatible ZyBorg/1.0 (wn.zyborg@looksmart.net http://www.WISEnutbot.com)",
"FAST-WebCrawler/3.6 ","FAST-WebCrawler/3.7/FirstPage",
" FAST-WebCrawler/3.8",
"DeepIndex"," Mozilla/2.0 (compatible; Ask Jeeves/Teoma)
","Ask Jeeves/Teoma",
"appie 1.1 ","Gigabot/1.0","HenriLeRobotMirago
","psbot","Szukacz/1.5 ",
"Openbot/3.0","Openfind data gatherer","dloader(NaverRobot)/1.0","
Googlebot/2.1","msnbot/1.0 (+http://search.msn.com/msnbot.htm)",
"Mozilla/5.0 (compatible; Yahoo! Slurp; http://help.yahoo.com/help/us/ysearch/slurp)",
"Mozilla/5.0 (compatible; Yahoo! DE Slurp; http://help.yahoo.com/help/us/ysearch/slurp)","
Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0; SLCC1; .NET CLR 2.0.50727; .NET CLR 3.0.04506)","Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322)",
"Octora Beta - www.octora.com","Mozilla/5.0 (compatible; Yahoo! Slurp China; http://misc.yahoo.com.cn/help.html)","Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0; Google Wireless Transcoder;)","Mozilla/5.0 (Windows; U; Windows NT 5.1; fr; rv:1.8.0.4) Gecko/20060508 Firefox/1.5.0.4","Nokia6820/2.0 (4.83) Profile/MIDP-1.0 Configuration/CLDC-1.0 (compatible; Googlebot-Mobile/2.1; +http://www.google.com/bot.html)","Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; Crazy Browser 1.0.5)","Googlebot-Image/1.0","Evaal/0.7.1 (Evaal; http://search.evaal.com/bot.html; bot@evaal.com)",
"Gigabot/2.0");
$user = $_SERVER['HTTP_USER_AGENT'];
if (in_array($user, $bots)) //on chercher ses $user et dans le $bot on cherche si le user agent et dans la liste
{
exit;  //si il et dans la liste un quit le script
}
else{
// si il nais pas dans la liste on le bannie
$ip_derproxy = (getenv("HTTP_X_FORWARDED_FOR") ? getenv("HTTP_X_FORWARDED_FOR") : getenv("REMOTE_ADDR")); 
// pour savoir l'ip meme derriere proxy
$fichier = fopen(".htaccess", "a");
fputs($fichier,"deny from ");              // on ecrit deny from dans le .htaccess//
fputs($fichier, $ip_derproxy."\n");         // on inscrit ladresse ip 
fclose($fichier);                          //on ferme le fichier
$host = gethostbyaddr($_SERVER[REMOTE_ADDR]);        // on declare les variable
$ladate  =  date ("  d M Y, G:i:s ");      // on declare la variable pour la date
$fichier = fopen("ip_block.txt", "a");
fputs($fichier,"This IP : $ip_derproxy Just got Catched the $ladate \n");        // puis la ladresse ip 
fputs($fichier, "dns $host \n ");        //on recupere variable $host pour l'inscrit dans le fichier 
fputs($fichier, "Navigator use : $user \n");        //on recupere variable $host pour l'inscrit dans le fichier
fclose($fichier);                       //ferme le fichier
//include ("MaiL.php");             //on include le fichier mail
}                   
?>