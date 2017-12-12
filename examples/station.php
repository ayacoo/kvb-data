<?php
/**
 * Abfrage der aktuellen Fahrplandaten - examples/station.php?id=36 für den Hansaring
 * Die IDs der Haltestellen wir dmit der Datei stations.json abgeglichen - DB Abfrage wäre sinnvoller
 */
require_once('../phpQuery.php');
require_once('../generator/curlHelper.php');

// Haltestellen ID Abfrage
if (isset($_GET['id'])) {
    $uid = (int) $_GET['id'];
} else {
    return false;
}
$choosenLine = null;

// Prüfe Gültigkeit der Haltestelle
$stationJson = file_get_contents('../json/stations.json');
$arrStations = json_decode($stationJson);
if (isset($arrStations->$uid)) {
    $stationName = $arrStations->$uid;
} else {
    return false;
}

// Abruf der aktuellen Werte
$content = getData('http://www.kvb-koeln.de/qr/' . $uid . '/');
if ($content !== false) {
    $content = utf8_encode(str_replace('&nbsp;', '', $content));
    $doc = phpQuery::newDocumentHTML($content);

    $plan = array('stand' => pq('#content > div.fliesstext.grau_bg.grau_seiten > div:nth-child(4) > span')->html(), 'name' => $stationName);
    $i = -1;
    $rows = pq('.qr_table:eq(1) tr');
    foreach ($rows as $row) {
        $line = clean(pq($row)->find('td:eq(0)')->html());
        if ($line === $choosenLine || $choosenLine === null) {
            $i++;
            $plan[$i]['line'] = $line;
            $plan[$i]['direction'] = clean(pq($row)->find('td:eq(1)')->html());
            $plan[$i]['time'] = clean(pq($row)->find('td:eq(2)')->html());
        }
    }

    echo json_encode($plan);
} else {
    return false;
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