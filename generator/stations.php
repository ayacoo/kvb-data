<?php
/**
 * Generiert den aktuellen Listenbestand aller Haltestellen und speichert diese als JSON ab
 */

require_once '../phpQuery.php';
require_once 'curlHelper.php';

$content = getData('https://www.kvb.koeln/haltestellen/index.html');
if ($content !== false) {
    $content = str_replace('&nbsp;', '', $content);
    $doc = phpQuery::newDocumentHTML($content);

    $rows = pq('a[href*="/haltestellen/overview"]');
    $haltestellen = array();
    foreach ($rows as $row) {
        $uid = (int) str_replace('/haltestellen/overview/', '', pq($row)->attr('href'));
        $haltestellen[$uid] = pq($row)->text();
    }

    $json = json_encode($haltestellen);
    file_put_contents(__DIR__ . '/../json/stations.json', $json);
}