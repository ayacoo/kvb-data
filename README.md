# KVB Daten

Ziel ist die Bereitstellung von KVB Daten in JSON Form um diese in anderen Applikationen nutzen zu können. Als Hilfe 
zur Visualisierung dienen die Beispiele. Als Unterstützung zur Datenabfrage wird phpQuery genutzt. Teilweise werden 
offene Daten genutzt, aber größtenteils findet eine Abfrage auf die KVB Seite statt. Daher sollten die Daten im 
richtigen Betrieb gecached werden.

## Datenbasis

####generator/stations.php
*Erzeugt eine Liste der aktuellen Haltestellen (gilt auch für Bushaltestellen) => json/stations.json*

####generator/lines.php
*Erzeugt eine Liste der aktuellen Linien. Getrennt nach Bus und Bahn => json/lines.json*

####generator/mofi.php
*Erzeugt eine Liste der aktuellen Bus oder Bahn Störungen => json/tramDisruptions.json / json/busDisruptions.json*

###linecolors.json
*Die Farbcodes wurden basierend auf dem aktuellen Liniennetzplan der KVB und mit Hilfe von Colorzilla ausgelesen*

Aktuelle Daten vom 28.11.2016
- stations.json (selbst generiert)
- lines.json (selbst generiert)
- linecolors.json (selbst generiert)
- tramStations.json (Quelle: OpenData, Link siehe unten)

# Beispiele

####examples/station.php
*Holt alle Abfahrtszeiten einer ausgewählten Haltestellen via UID*

####examples/linepath.php
*Erzeugt eine Liste des Linienweges anhand einer übergebenen Linie*
=> <code>linepath.php?line=7&direction=1 oder linepath.php?line=7&direction=2</code>

####examples/map.php
*Zeigt die aktuellen Stadtbahnhaltestellen mit Fahrplaninfos auf einer Karte an. Google Maps Key wird benötigt*

####examples/linemap.php
*Zeigt den aktuellen Linienplan der KVB ab. Google Maps Key wird benötigt*

# Mögliche Anwendungsfälle
- Telegram Bot anbinden ("/haltestelle Neumarkt" oder "/haltestelle [ID]")
- Auswertungen der Störungen
- Abfahrtsmonitor für Orte / Geschäfte / Firmen

## Services
- https://offenedaten-koeln.de/dataset/haltestellen-stadtbahn-u-bahn-koeln
- https://offenedaten-koeln.de/dataset/strecke-stadtbahn-u-bahn-koeln
- [KVB Webseite](https://www.kvb-koeln.de/)
- [phpQuery](https://github.com/bariew/phpquery)
- Google Maps API

## Entwicklungsstand

Dieser Service ist gerade in Entwicklung (Beta). Der Code geht sicherlich schöner, aber kann natürlich nach Belieben benutzt und verändert werden. Es gilt: Keine Gewähr für den Code ;-)

## License

<a rel="license" href="http://creativecommons.org/licenses/by-sa/4.0/"><img alt="Creative Commons Lizenzvertrag" style="border-width:0" src="https://i.creativecommons.org/l/by-sa/4.0/88x31.png" /></a><br />Dieses Werk ist lizenziert unter einer <a rel="license" href="http://creativecommons.org/licenses/by-sa/4.0/">Creative Commons Namensnennung - Weitergabe unter gleichen Bedingungen 4.0 International Lizenz</a>.