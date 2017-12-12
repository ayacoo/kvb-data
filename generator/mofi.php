<?php
/**
 * Generiert die aktuellen Störungsmeldungen aller Bahnlinien => json/tramDisruptions.json
 * Bei Bedarf der Buslinien muss generator/mofi.php?type=bus aufgerufen werden
 */

require_once __DIR__ . '/../phpQuery.php';
require_once __DIR__ . '/curlHelper.php';

$kvbUrl = 'https://www.kvb.koeln/fahrtinfo/betriebslage/bahn/';
$targetFile = 'tramDisruptions.json';
getDisruptions($kvbUrl, $targetFile);

/*
$kvbUrl = 'http://www.kvb-koeln.de/german/home/mofis_bus.html';
$targetFile = 'busDisruptions.json';
getDisruptions($kvbUrl, $targetFile);
*/

function getDisruptions($kvbUrl, $targetFile)
{
    $content = getData($kvbUrl);
    if ($content !== false) {
        $content = str_replace('&nbsp;', '', $content);
        $content = utf8_encode($content);
        $content = html_entity_decode($content);
        phpQuery::newDocumentHTML($content);

        $m = -1;
        $disruptions = pq('table > tr');
        foreach ($disruptions as $disruption) {
            $m++;
            $line = pq($disruption)->find('.number')->text();
            pq($disruption)->find('.info-list')->remove();
            $desc = pq($disruption)->find('td')->text();
            $arrKvbDisruptions = explode('*', $desc);

            $disruptionList[$m]['lines'] = trim($line);
            list($state, $delay) = handleState(trim($arrKvbDisruptions[0]));
            $disruptionList[$m]['state'] = $state;
            $disruptionList[$m]['delay'] = $delay;
            $disruptionList[$m]['stations'] = array_map('trim', explode('-', $arrKvbDisruptions[1]));
        }

        // Ausgabe erstellen
        $jsonDisruptions = json_encode($disruptionList);
        file_put_contents(__DIR__ . '/../json/' . $targetFile, $jsonDisruptions);
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
    if (strpos($state, 'Der Fahrleitungsschaden') !== false) {
        $state = 'catenaryDamage';
    }

    return [$state, $delay];
}