<?php
/**
 * Generiert den aktuellen Listenbestand der Linien und speichert diese als JSON json/lines.json ab
 */

require_once __DIR__ . '/../phpQuery.php';
require_once __DIR__ . '/curlHelper.php';

$content = getData('https://www.kvb.koeln/fahrtinfo/minifahrplaene.html');
if ($content !== false) {
    $content = str_replace('&nbsp;', '', $content);
    $doc = phpQuery::newDocumentHTML($content);

    $options = pq('#filename option');
    $lines = [];
    foreach ($options as $option) {
        $values = explode('/', pq($option)->attr('value'));
        $lastElement = end($values);

        $uid = (int) str_replace('_mf.pdf','', $lastElement);
        $lines[$uid]['name'] = pq($option)->text();
        $lines[$uid]['type'] = 'bus';
        // Bahnen haben immer eine zweistellige Nummer
        if ($uid < 100) {
            $lines[$uid]['type'] = 'tram';
        }
    }

    $json = json_encode($lines);
    file_put_contents(__DIR__ . '/../json/lines.json', $json);
}