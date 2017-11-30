<?php
require_once __DIR__ . '/../phpQuery.php';
require_once __DIR__ . '/curlHelper.php';

$content = getData('http://www.kvb-koeln.de/german/aktuelles/betriebslage/aufzuege.html');
if ($content !== false) {
    $doc = phpQuery::newDocumentHTML($content);

    $arrElevators = [];
    $arrElevators['date'] = date('d.m.Y');

    $elevators = pq('#content .fliesstext div.rot');
    foreach ($elevators as $elevator) {
        $arrElevators['elevators'][] = strip_tags(pq($elevator)->text());
    }

    $json = json_encode($arrElevators);
    file_put_contents('../json/elevators.json', $json);
}