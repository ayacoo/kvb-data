<?php
/**
 * Abfrage der aktuellen Fahrplandaten - examples/station.php?id=36 für den Hansaring
 * Die IDs der Haltestellen wir dmit der Datei stations.json abgeglichen - DB Abfrage wäre sinnvoller
 */
require_once __DIR__ . '/../phpQuery.php';
require_once __DIR__ . '/../generator/curlHelper.php';

// Haltestellen ID Abfrage
if (isset($_GET['id'])) {
    $uid = (int) $_GET['id'];
}
$choosenLine = null;

if ($uid > 0) {
    // Prüfe Gültigkeit der Haltestelle
    $stationJson = file_get_contents('../json/stations.json');
    $arrStations = json_decode($stationJson);
    $stationName = '';
    if (isset($arrStations->$uid)) {
        $stationName = $arrStations->$uid;
    }

    // Abruf der aktuellen Werte
    $content = getData('https://www.kvb.koeln/qr/' . $uid . '/');
    if ($content !== false) {
        $content = utf8_encode(str_replace('&nbsp;', '', $content));
        $doc = phpQuery::newDocumentHTML($content);

        $plan = ['stand' => pq('span.stand')->html(), 'name' => $stationName];
        $i = -1;
        $rows = pq('#qr_ergebnis > tr');
        foreach ($rows as $row) {
            $line = clean(pq($row)->find('td:eq(0)')->text());
            if ($line === $choosenLine || $choosenLine === null) {
                $i++;
                $plan[$i]['line'] = $line;
                $plan[$i]['direction'] = clean(pq($row)->find('td:eq(1)')->text());
                $plan[$i]['time'] = clean(pq($row)->find('td:eq(2)')->text());
            }
        }

        echo json_encode($plan);
    }
}

/**
 * Content säubern
 *
 * @param $value
 *
 * @return string
 */
function clean($value)
{
    return trim(strip_tags($value));
}