<?php
/**
 * Abfrage um den Weg einer Linie auszulesen
 * examples/linepath.php?line=9&direction=1
 */
require_once __DIR__ . '/../phpQuery.php';
require_once __DIR__ . '/../generator/curlHelper.php';

$line = (int) ($_GET['line'] ?? 0);
$direction = (int) ($_GET['direction'] ?? 1);

if ($line > 0) {
    $url = 'https://www.kvb.koeln/haltestellen/showline/36/' . $line . '/';
    $content = getData($url);
    if ($content !== false) {
        $doc = phpQuery::newDocumentHTML($content);

        $arrStations = [];
        if ($direction === 1) {
            $stations = pq('body > div.container.section > div.modul > div.row > div:nth-child(1) > table > tr > td');
        } else {
            $stations = pq('body > div.container.section > div.modul > div.row > div:nth-child(2) > table > tr > td');
        }
        foreach ($stations as $station) {
            $arrStations[] = trim(pq($station)->find('a')->text());
        }

        echo json_encode($arrStations);
    }
} else {
    echo 'No line defined. Example: linepath.php?line=9&direction=1';
}