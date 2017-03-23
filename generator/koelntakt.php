<?php
require_once __DIR__ . '/../phpQuery.php';
require_once __DIR__ . '/curlHelper.php';

$content = getData('http://www.kvb-koeln.de/german/aktuelles/koelntakt.html');
if ($content !== false) {
    $content = str_replace('&nbsp;', '', $content);
    $doc = phpQuery::newDocumentHTML($content);

    $i = 0;
    $magazines = [];
    $downloads = pq('div.grau_seiten.clear');
    foreach ($downloads as $download) {
        $i++;
        if ($i > 1) {
            $link = htmlspecialchars(trim(pq($download)->find('a')->attr('href')));
            $title = htmlspecialchars(str_replace(')(', ') (', trim(strip_tags(pq($download)->html()))));
            $magazines[$i]['title'] = $title;
            $magazines[$i]['link'] = 'http://www.kvb-koeln.de' . $link;
        }
    }
    $json = json_encode($magazines);
    file_put_contents('../json/koelntakt.json', $json);

    echo 'Köln Takt Liste wurde aktualisiert!';
}