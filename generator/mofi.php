<?php
/**
 * Generiert die aktuellen Störungsmeldungen aller Bahnlinien => json/tramDisruptions.json
 * Bei Bedarf der Buslinien muss generator/mofi.php?type=bus aufgerufen werden
 */

require_once('/var/www/virtual/ayacoo/html/kvb/phpQuery.php');
require_once('curlHelper.php');

$kvbUrl = 'http://www.kvb-koeln.de/german/home/mofis_bahn.html';
$targetFile = 'tramDisruptions.json';
getDisruptions($kvbUrl, $targetFile);

$kvbUrl = 'http://www.kvb-koeln.de/german/home/mofis_bus.html';
$targetFile = 'busDisruptions.json';
getDisruptions($kvbUrl, $targetFile);

function getDisruptions($kvbUrl, $targetFile)
{
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
            // Daten holen
            $kvbDisruption = pq('#content > div.fliesstext.mobile100pc > div > table:nth-child(' . $i
                . ') > tr:nth-child(3) > td')->html();
            $arrKvbDisruptions = explode('*', $kvbDisruption);

            // Daten verarbeiten
            $line = $arrKvbDisruptions[0];
            $line = str_replace('Linien', '', $line);
            $line = str_replace('Linie', '', $line);
            $line = str_replace(' und ', ',', $line);

            // Ausgabe erstellen
            $disruptionList[$m]['lines'] = trim($line);
            list($state, $delay) = handleState(trim($arrKvbDisruptions[1]));
            $disruptionList[$m]['state'] = $state;
            $disruptionList[$m]['delay'] = $delay;
            $disruptionList[$m]['stations'] = array_map('trim', explode('-', $arrKvbDisruptions[2]));
        }

        echo $jsonDisruptions = json_encode($disruptionList);
        file_put_contents('/var/www/virtual/ayacoo/html/kvb/json/' . $targetFile, $jsonDisruptions);
    }
}

/**
 * Meldung der KVB verarbeiten
 *
 * @param string $state KVB Meldung
 *
 * @return array($state, $delay) Status und Verspätung
 */
function handleState($state)
{
    $delay = 0;
    if (strpos($state, 'Folgende Fahrt entfällt') !== false) {
        $state = 'cancellation';
    }
    if (strpos($state, 'Folgende Fahrt erfolgt') !== false) {
        $delay = str_replace('Folgende Fahrt erfolgt ca. ', '', $state);
        $delay = trim(str_replace(' Min. später', '', $delay));
        $state = 'delay';
    }
    if (strpos($state, 'Hohes Verkehrsaufkommen') !== false) {
        $state = 'hightraffic';
    }

    return array($state, $delay);
}