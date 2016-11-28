<?php
/**
 * Abfrage um den Weg einer Linie auszulesen
 * examples/linepath.php?line=9&direction=1
 */
require_once('../phpQuery.php');
require_once('../generator/curlHelper.php');

$line = 0;
if (isset($_GET['line'])) {
    $line = intval($_GET['line']);
}
$direction = 1;
if (isset($_GET['direction'])) {
    $direction = intval($_GET['direction']);
}

if ($line > 0) {
    $url = 'http://www.kvb-koeln.de/german/hst/showline/36/' . $line . '/';
    $content = getData($url);
    if ($content !== false) {
        $content = str_replace('&nbsp;', '', $content);
        $doc = phpQuery::newDocumentHTML($content);

        $arrStations = array();
        if ($direction === 1) {
            $stations = pq('#content > div.fliesstext.mobile100pc > div > div:nth-child(2) > table > tr > td > a');
        } else {
            $stations = pq('#content > div.fliesstext.mobile100pc > div > div:nth-child(3) > table > tr > td > a');
        }
        foreach ($stations as $station) {
            $arrStations[] = trim(pq($station)->text());
        }

        echo json_encode($arrStations);
    }
}