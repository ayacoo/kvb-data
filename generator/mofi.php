<?php
/**
 * Generiert die aktuellen Störungsmeldungen aller Bahnlinien => json/tramDisruptions.json
 * Bei Bedarf der Buslinien muss generator/mofi.php?type=bus aufgerufen werden
 */

require_once('../phpQuery.php');
require_once('curlHelper.php');

$kvbUrl = 'http://www.kvb-koeln.de/german/home/mofis_bahn.html';
$targetFile = 'tramDisruptions.json';

if (isset($_GET['type'])) {
    $type = trim($_GET['type']);
    if ($type === "bus") {
        $kvbUrl = 'http://www.kvb-koeln.de/german/home/mofis_bus.html';
        $targetFile = 'busDisruptions.json';
    }
}

$content = getData($kvbUrl);
if ($content !== false) {
    $content = str_replace('&nbsp;', '', $content);
    $content = utf8_encode($content);
    $content = html_entity_decode($content);
    $doc = phpQuery::newDocumentHTML($content);

    // Anzahl der Meldungen berechnen
    $counter = pq('.top_head_rot_small')->count();
    $start = 3;
    $end = $start + ($counter * 2) - 2;

    // Gehe alle Meldungen durch
    $m = -1;
    $disruptionList = array();
    for ($i = $start; $i <= $end; $i = $i + 2) {
        $m++;
        $kvbDisruption = pq('#content > div.fliesstext.mobile100pc > div > table:nth-child(' . $i
            . ') > tr:nth-child(3) > td')->html();
        $arrKvbDisruptions = explode('*', $kvbDisruption);

        $line = $arrKvbDisruptions[0];
        $line = str_replace('Linien', '', $line);
        $line = str_replace('Linie', '', $line);
        $disruptionList[$m]['line'] = trim($line);
        $disruptionList[$m]['state'] = trim($arrKvbDisruptions[1]);
        $disruptionList[$m]['stations'] = array_map('trim', explode('-', $arrKvbDisruptions[2]));
    }

    echo $jsonDisruptions = json_encode($disruptionList);
    file_put_contents('../json/' . $targetFile, $jsonDisruptions);
}