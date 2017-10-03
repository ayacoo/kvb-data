<?php
include_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>KVB Daten</title>
</head>
<body>

<p>Beispiele</p>
<ul>
    <li><a href="<?= BASE_URL; ?>examples/mapWithKey.php">Karte mit Stadtbahn Haltestellen und P+R inkl. Abfahrtszeiten</a></li>
    <li><a href="<?= BASE_URL; ?>examples/linemapWithKey.php">Karte mit Stadtbahn Linienwegen</a></li>
    <li><a href="<?= BASE_URL; ?>examples/station.php?id=36">Abfahrtszeit im JSON Format für eine Haltestelle (Beispiel Hansaring)</a></li>
    <li><a href="<?= BASE_URL; ?>generator/mofi.php">Generator Mofi Meldungen im JSON Format</a></li>
    <li><a href="<?= BASE_URL; ?>mofistats">Mofi Stats Ausgabe Beispiel</a></li>
</ul>

Github Base mit Dokumentation: <a target="_blank" href="https://github.com/ayacoo/kvb-data">https://github.com/ayacoo/kvb-data</a>
<br/>
<a href="https://www.ayacoo.de/">Impressum</a>

<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<small>Stand 03.10.2017</small>

</body>
</html>

<?php

include_once 'phpQuery.php';

function getData($url)
{
    $curl = curl_init();
    if (!$curl) {
        return false;
    }

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($curl, CURLOPT_TIMEOUT, 30);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (compatible; MSIE 10.6; Windows NT 6.1; Trident/5.0; InfoPath.2; SLCC1; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729; .NET CLR 2.0.50727) 3gpp-gba UNTRUSTED/1.0");
    $headers = array(
        "Cache-Control: no-cache",
        "Pragma: no-cache"
    );
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    return curl_exec($curl);
}

$content = getData(BASE_URL  . 'json/koelntakt.json');
$koelnTakt = json_decode($content);
$numberOfFiles = 0;
$counter = 0;
$output = 'Test';

foreach ($koelnTakt as $file) {
    if ($numberOfFiles === 0 || $counter <= $numberOfFiles) {
        $output .= '<a href="' . $file->link . '">Download ' . $file->title . '</a><br/>';
    }
    $counter++;
}

echo $output;

?>