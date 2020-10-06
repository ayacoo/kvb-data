<?php
include_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>KVB Daten</title>
</head>
<body>

<p>Beispiele</p>
<ul>
    <li><a href="<?= BASE_URL; ?>examples/mapWithKey.php">Karte mit Stadtbahn Haltestellen und P+R inkl. Abfahrtszeiten</a></li>
    <li><a href="<?= BASE_URL; ?>examples/linemapWithKey.php">Karte mit Stadtbahn Linienwegen</a></li>
    <li><a href="<?= BASE_URL; ?>examples/station.php?id=36">Abfahrtszeit im JSON Format für eine Haltestelle (Beispiel Hansaring)</a></li>
    <li><a href="<?= BASE_URL; ?>generator/mofi.php">Generator Mofi Meldungen im JSON Format</a></li>
    <li><a href="<?= BASE_URL; ?>mofistats">Mofi Stats Ausgabe Beispiel</a></li>
</ul>

<p>Telegram Bot</p>
<p>Einfach nach Benutzername @KVBKoeln_Bot suchen und Konversation starten.</p>

Github Base mit Dokumentation: <a target="_blank" href="https://github.com/ayacoo/kvb-data">https://github.com/ayacoo/kvb-data</a>
<br/>
<a href="https://www.ayacoo.de/">Impressum</a>

<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<small>Stand 06.10.2020</small>

</body>
</html>