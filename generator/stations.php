<?php
/**
 * Generiert den aktuellen Listenbestand aller Haltestellen und speichert diese als JSON ab
 */

require_once('../phpQuery.php');
require_once('curlHelper.php');

$content = getData('http://www.kvb-koeln.de/german/hst/index.html');
if ($content !== false) {
    $content = str_replace('&nbsp;', '', $content);
    $doc = phpQuery::newDocumentHTML($content);

    $rows = pq('a[href*="/german/hst/overview"]');
    $haltestellen = array();
    foreach ($rows as $row) {
        $uid = intval(str_replace('/german/hst/overview/', '', pq($row)->attr('href')));
        $haltestellen[$uid] = pq($row)->text();
    }

    $json = json_encode($haltestellen);
    file_put_contents('../json/stations.json', $json);

    echo 'Die Liste der Haltestellen wurde aktualisiert!';
}