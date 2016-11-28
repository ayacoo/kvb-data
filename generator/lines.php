<?php
/**
 * Generiert den aktuellen Listenbestand der Linien und speichert diese als JSON json/lines.json ab
 */

require_once('../phpQuery.php');
require_once('curlHelper.php');

$content = getData('http://www.kvb-koeln.de/german/fahrplan/aushangfahrplaene6.html');
if ($content !== false) {
    $content = str_replace('&nbsp;', '', $content);
    $doc = phpQuery::newDocumentHTML($content);

    $options = pq('#miniplan option');
    $lines = array();
    foreach ($options as $option) {
        $uid = intval(pq($option)->attr('value'));
        $lines[$uid]['name'] = pq($option)->text();
        $lines[$uid]['type'] = 'bus';
        // Bahnen haben immer eine zweistellige Nummer
        if ($uid < 100) {
            $linien[$uid]['type'] = 'tram';
        }
    }

    $json = json_encode($lines);
    file_put_contents('../json/lines.json', $json);

    echo 'Die Liste der Linien wurde aktualisiert!';
}