<?php
require_once __DIR__ . '/../phpQuery.php';
require_once __DIR__ . '/curlHelper.php';

$content = getData('https://www.kvb.koeln/fahrtinfo/betriebslage/aufzuege/');
if ($content !== false) {
    $doc = phpQuery::newDocumentHTML($content);

    $arrElevators = [];
    $arrElevators['date'] = date('d.m.Y');

    $elevators = pq('.red-text');
    foreach ($elevators as $elevator) {
        $arrElevators['elevators'][] = strip_tags(pq($elevator)->text());
    }

    $json = json_encode($arrElevators);
    file_put_contents(__DIR__ . '/../json/elevators.json', $json);
}