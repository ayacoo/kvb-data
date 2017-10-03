# KVB Daten

Ziel ist die Bereitstellung von KVB Daten in JSON Form um diese in anderen Applikationen nutzen zu können. Als Hilfe 
zur Visualisierung dienen die Beispiele. Als Unterstützung zur Datenabfrage wird phpQuery genutzt. Teilweise werden 
offene Daten genutzt, aber größtenteils findet eine Abfrage auf die KVB Seite statt. **Daher sollten die Daten im 
richtigen Betrieb gecached werden.**

## Datenbasis

####generator/stations.php
*Erzeugt eine Liste der aktuellen Haltestellen (gilt auch für Bushaltestellen) => json/stations.json*

####generator/lines.php
*Erzeugt eine Liste der aktuellen Linien. Getrennt nach Bus und Bahn => json/lines.json*

####generator/koelntakt.php
*Erzeugt eine Download Liste des KölnTakt Archives => json/koelntakt.json*

####generator/mofi.php
*Erzeugt eine Liste der aktuellen Bus oder Bahn Störungen => json/tramDisruptions.json / json/busDisruptions.json*

###linecolors.json
*Die Farbcodes wurden basierend auf dem aktuellen Liniennetzplan der KVB und mit Hilfe von Colorzilla ausgelesen*

Aktuelle Daten vom 03.10.2017
- linecolors.json (selbst generiert)
- linemap.json (Quelle: OpenData, Link siehe unten)
- lines.json (selbst generiert)
- parkandride.json (Quelle: OpenData, Link siehe unten)
- stations.json (selbst generiert)
- tramStations.json (Quelle: OpenData, Link siehe unten)

# Beispiele

####examples/station.php
*Holt alle Abfahrtszeiten einer ausgewählten Haltestellen via UID*
=> <code>Hansaring station.php?id=36</code>

####examples/linepath.php
*Erzeugt eine Liste des Linienweges anhand einer übergebenen Linie*
=> <code>linepath.php?line=7&direction=1 oder linepath.php?line=7&direction=2</code>
=> https://ayacoo.bellatrix.uberspace.de/kvb/kvb/examples/linepath.php

####examples/map.php
*Zeigt die aktuellen Stadtbahnhaltestellen mit Fahrplaninfos auf einer Karte an. Und zusätzlich noch die Park and Ride Anlagen von Köln. Google Maps Key wird benötigt*
=> https://ayacoo.bellatrix.uberspace.de/kvb/kvb/examples/mapWithKey.php

####examples/linemap.php
*Zeigt den aktuellen Linienplan der KVB ab. Google Maps Key wird benötigt*
=> <code>Einzelne Linien können so abgerufen werden linemap.php?line=1</code>
=> https://ayacoo.bellatrix.uberspace.de/kvb/kvb/examples/linemapWithKey.php

# Mögliche Anwendungsfälle
- Telegram Bot anbinden (Gerade begonnen) ("/haltestelle Neumarkt" oder "/haltestelle [ID]")
- Auswertungen der Störungen
- Abfahrtsmonitor für Orte / Geschäfte / Firmen

## Services
- https://offenedaten-koeln.de/dataset/haltestellen-stadtbahn-u-bahn-koeln
- https://offenedaten-koeln.de/dataset/strecke-stadtbahn-u-bahn-koeln
- https://offenedaten-koeln.de/dataset/park-and-ride-anlagen-koeln
- [KVB Webseite](https://www.kvb-koeln.de/)
- [phpQuery](https://github.com/bariew/phpquery)
- Google Maps API

## Entwicklungsstand

Dieser Service ist gerade in Entwicklung (Beta). Der Code geht sicherlich schöner, aber kann natürlich nach Belieben benutzt und verändert werden. Es gilt: Keine Gewähr für den Code ;-)


# Telegram Bot v1
- /stoerung "(Optional Linie)"
- /haltestelle "(Pflicht: Name oder ID)"
  Gefunden oder Vorschlag
  
Meintest du…

[437] Poll Salmstraße
[25] Hansaring

Dann gib nun "/haltestelle ID" ein  
  
- /linienweg "(Pflicht: Linie)"
- /parkandride "(Optional: Name)"
- /koelntakt

# Telegram Bot v2 (Ideenfindung)
- /kvbrad Zeige das nächstgelegene Fahrrad an. (Abhängig davon ob Telegram GPS Daten übermitteln kann) 

## License

<a rel="license" href="http://creativecommons.org/licenses/by-sa/4.0/"><img alt="Creative Commons Lizenzvertrag" style="border-width:0" src="https://i.creativecommons.org/l/by-sa/4.0/88x31.png" /></a><br />Dieses Werk ist lizenziert unter einer <a rel="license" href="http://creativecommons.org/licenses/by-sa/4.0/">Creative Commons Namensnennung - Weitergabe unter gleichen Bedingungen 4.0 International Lizenz</a>.