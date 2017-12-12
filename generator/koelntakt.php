<?php
require_once __DIR__ . '/../phpQuery.php';
require_once __DIR__ . '/curlHelper.php';

$content = getData('https://www.kvb.koeln/unternehmen/presse/publikationen/koelntakt.html');
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
            $magazines[$i]['link'] = 'https://www.kvb.koeln' . $link;
        }
    }
    $json = json_encode($magazines);
    file_put_contents(__DIR__ . '/../json/koelntakt.json', $json);
}