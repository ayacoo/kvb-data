<?php
/**
 * Abfrage der aktuellen Fahrplandaten - examples/station.php?id=36 für den Hansaring
 * Die IDs der Haltestellen wird mit der Datei stations.json abgeglichen
 */
require_once __DIR__ . '/../phpQuery.php';
require_once __DIR__ . '/../generator/curlHelper.php';

// Haltestellen ID Abfrage
$uid = (int) ($_GET['id'] ?? 0);
$choosenLine = null;

$plan = ['status' => false];

if ($uid > 0) {
    // Prüfe Gültigkeit der Haltestelle
    $stationJson = file_get_contents('../json/stations.json');
    $arrStations = json_decode($stationJson);
    $stationName = $arrStations->$uid ?? '';

    // Abruf der aktuellen Werte
    $content = getData('https://www.kvb.koeln/qr/' . $uid . '/');
    if ($content !== false) {
        $i = -1;

        $content = utf8_encode(str_replace('&nbsp;', '', $content));
        $doc = phpQuery::newDocumentHTML($content);
        $rows = pq('#qr_ergebnis > tr');
        if (count($rows) > 0) {
            $plan = ['status' => true, 'stand' => pq('span.stand')->html(), 'name' => $stationName];
            foreach ($rows as $row) {
                $line = clean(pq($row)->find('td:eq(0)')->text());
                if ($line === $choosenLine || $choosenLine === null) {
                    $i++;
                    $plan[$i]['line'] = $line;
                    $plan[$i]['direction'] = clean(pq($row)->find('td:eq(1)')->text());
                    $plan[$i]['time'] = clean(pq($row)->find('td:eq(2)')->text());
                }
            }
        }
    }
}

echo json_encode($plan);

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