# KVB Daten

Ziel ist die Bereitstellung von KVB Daten in JSON Form um diese in anderen Applikationen nutzen zu können. Als Hilfe 
zur Visualisierung dienen die Beispiele. Als Unterstützung zur Datenabfrage wird phpQuery genutzt. Teilweise werden 
offene Daten genutzt, aber größtenteils findet eine Abfrage auf die KVB Seite statt. **Daher sollten die Daten im 
richtigen Betrieb gecached werden.** Das Repo dient ausschließlich als Hilfestellung für eure Ideenfindung und Daten die eben noch nicht als OpenData zur Verfügung stehen.

## Update März 2019

Die KVB erweitert nach und nach ihr OpenData PortFolio, so dass dieses Repo nicht zwingend mehr den Nutzen haben muss. Daher unbedingt vor Nutzung immer diese Seite prüfen:
https://kvb.koeln/service/open_data.html 

- Das Repo wurde nur minimal erweitert. PHP 7 ist nun Pflicht.
- Liniengenerator an den aktuellen Stand angepasst
- Daten aktualisiert

## Datenbasis

#### generator/stations.php
*Erzeugt eine Liste der aktuellen Haltestellen (gilt auch für Bushaltestellen) => json/stations.json*

#### generator/lines.php
*Erzeugt eine Liste der aktuellen Linien. Getrennt nach Bus und Bahn => json/lines.json*

#### generator/koelntakt.php
*Erzeugt eine Download Liste des KölnTakt Archives => json/koelntakt.json*

#### generator/mofi.php
*Erzeugt eine Liste der aktuellen Bus oder Bahn Störungen => json/tramDisruptions.json / json/busDisruptions.json*

#### linecolors.json
*Die Farbcodes wurden basierend auf dem aktuellen Liniennetzplan der KVB und mit Hilfe von Colorzilla ausgelesen*

Aktuelle Daten vom 24.03.2019
- linecolors.json (selbst generiert)
- linemap.json (Quelle: OpenData, Link siehe unten)
- lines.json (selbst generiert)
- parkandride.json (Quelle: OpenData, Link siehe unten)
- stations.json (selbst generiert)
- tramStations.json (Quelle: OpenData, Link siehe unten)

# Beispiele

#### examples/station.php
*Holt alle Abfahrtszeiten einer ausgewählten Haltestellen via UID*
=> <code>Hansaring station.php?id=36</code>
=> https://ayacoo.bellatrix.uberspace.de/kvb/examples/station.php?id=36

#### examples/linepath.php
*Erzeugt eine Liste des Linienweges anhand einer übergebenen Linie*
=> <code>linepath.php?line=7&direction=1 oder linepath.php?line=7&direction=2</code>
=> https://ayacoo.bellatrix.uberspace.de/kvb/examples/linepath.php?line=7&direction=2

#### examples/map.php
*Zeigt die aktuellen Stadtbahnhaltestellen mit Fahrplaninfos auf einer Karte an. Und zusätzlich noch die Park and Ride Anlagen von Köln. Google Maps Key wird benötigt*
=> https://ayacoo.bellatrix.uberspace.de/kvb/examples/mapWithKey.php

#### examples/linemap.php
*Zeigt den aktuellen Linienplan der KVB ab. Google Maps Key wird benötigt*
=> <code>Einzelne Linien können so abgerufen werden linemap.php?line=1</code>
=> https://ayacoo.bellatrix.uberspace.de/kvb/examples/linemapWithKey.php

# Mögliche Anwendungsfälle
- Telegram Bot anbinden (Gerade begonnen) ("/haltestelle Neumarkt" oder "/haltestelle [ID]")
- Auswertungen der Störungen
- Abfahrtsmonitor für Orte / Geschäfte / Firmen

## Services
- https://www.offenedaten-koeln.de/dataset/haltestellen-stadtbahn-u-bahn-koeln
- https://www.offenedaten-koeln.de/dataset/strecke-stadtbahn-u-bahn-koeln
- https://www.offenedaten-koeln.de/dataset/park-and-ride-anlagen-koeln
- https://www.offenedaten-koeln.de/dataset/aufzuege-kvb-koeln
- [KVB Webseite](https://www.kvb-koeln.de/)
- [KVB OpenData](https://kvb.koeln/service/open_data.html)
- [phpQuery](https://github.com/bariew/phpquery)
- Google Maps API

## Entwicklungsstand

Der Code geht auf jeden Fall schöner, aber kann natürlich nach Belieben benutzt und verändert werden. Eine ToDo wäre phpQuery durch DOM Abfragen zu ersetzen. 
Es gilt: Der Code ist nicht für einen Livebetrieb gedacht und keine Gewähr für den Code.


# Telegram Bot v1
- /stoerung "(Optional Linie)"
- /haltestelle "(Pflicht: Name oder ID)"
  Gefunden oder Vorschlag
  
Meintest du…

[437] Poll Salmstraße
[25] Hansaring

Dann gib nun "/haltestelle ID" ein  
  
- /linienweg "(Pflicht: Linie)"
- /koelntakt 

## License

<a rel="license" href="http://creativecommons.org/licenses/by-sa/4.0/"><img alt="Creative Commons Lizenzvertrag" style="border-width:0" src="https://i.creativecommons.org/l/by-sa/4.0/88x31.png" /></a><br />Dieses Werk ist lizenziert unter einer <a rel="license" href="http://creativecommons.org/licenses/by-sa/4.0/">Creative Commons Namensnennung - Weitergabe unter gleichen Bedingungen 4.0 International Lizenz</a>.