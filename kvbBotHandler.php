<?php
include_once 'phpQuery.php';

function getData($url)
{
    $curl = curl_init();
    if (!$curl) {
        return false;
    }

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($curl, CURLOPT_TIMEOUT, 30);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (compatible; MSIE 10.6; Windows NT 6.1; Trident/5.0; InfoPath.2; SLCC1; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729; .NET CLR 2.0.50727) 3gpp-gba UNTRUSTED/1.0");
    $headers = array(
        "Cache-Control: no-cache",
        "Pragma: no-cache"
    );
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    return curl_exec($curl);
}

function processMessage($message)
{
    $welcomeMessage = 'Willkommen beim inoffiziellen KVB Telegram Bot.

Hier könnt ihr folgende Abfragen durchführen:

/haltestelle Name oder ID - Liefert dir die aktuellen Abfahrtszeiten
/linienweg Nummer - Zeigt dir den Linienweg der ausgewählten Linie
/stoerung - Zeigt dir die aktuellen Betriebsstörungen an
/koelntakt - Zeigt dir die letzten drei KölnTakt Links an. Optional mit Zahl bestimmen, wieviel Links angezeigt werden sollen

TODO
/parkandride - Zeigt dir ParkAndRide Plätze an. Optional kann man einen Namen angeben

Bei Rückmeldungen oder Fragen einfach eine E-Mail an info@ayacoo.de';

    $message_id = $message['message_id'];
    $chat_id = $message['chat']['id'];
    if (isset($message['text'])) {
        $text = $message['text'];
        $arrRequest = explode(' ', $text);
        $request = $arrRequest[0];

        if ($request === "/start") {
            apiRequestJson("sendMessage", array(
                'chat_id' => $chat_id,
                "text" => $welcomeMessage
            ));
        }
        if ($request === "/haltestelle") {
            handleHaltestelle($message, $chat_id);
        }
        if ($request === "/stoerung") {
            handleStoerung($message, $arrRequest, $chat_id);
        }
        if ($request === "/linienweg") {
            handleLinienweg($message, $chat_id);
        }
        if ($request === "/koelntakt") {
            handleKoelntakt($message, $chat_id);
        }

    } else {
        apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => 'I understand only text messages'));
    }
}

/**
 * @param $message
 * @param $chat_id
 * @return string
 */
function handleHaltestelle($message, $chat_id)
{
    $arrRequest = explode(' ', $message['text']);
    array_shift($arrRequest);
    $haltestelle = trim(strtolower(implode(' ', $arrRequest)));
    $uid = 0;
    $suggestionList = '';
    $stations = getData(BASE_URL . 'json/stations.json');

    if (!is_numeric($haltestelle) && trim($haltestelle) !== '') {
        $resultJsonList = json_decode($stations);
        foreach ($resultJsonList as $stationUid => $stationName) {
            if ($haltestelle === strtolower($stationName)) {
                $uid = $stationUid;
            }
        }

        if ($uid === 0) {
            foreach ($resultJsonList as $stationUid => $originalStationName) {
                $stationName = strtolower($originalStationName);
                $search = [' ', 'str.', 'Str.'];
                $replace = ['', 'Strasse', 'Strasse'];
                $stationName = str_replace($search, $replace, $stationName);
                if (preg_match('/' . $haltestelle . '/i', $stationName)) {
                    $suggestionList .= $originalStationName . ' [' . $stationUid . ']' . "\n";
                }
            }
        }
    } else {
        $uid = (int)$haltestelle;
    }

    if ($uid > 0) {
        $result = getData(BASE_URL . 'examples/station.php?id=' . $uid);
        $jsonArr = json_decode($result);

        $i = -1;
        $time = '';
        foreach ($jsonArr as $entry) {
            $i++;
            if ($i === 0) {
                $stand = $entry;
            }
            if ($i === 1) {
                $haltestelle = $entry;
            }

            if (is_object($entry)) {

                if ($i < 20) {
                    $time .= ' ' . $entry->line . ' ' . $entry->direction . ' ' . $entry->time . "\n";
                }
            }
        }

        $output = '<b>' . $haltestelle . ' ' . $stand . '</b>' . "\n";
        $output .= $time;
    } else {
        if ($suggestionList !== '') {
            $output = 'Es konnte keine genaue Übereinstimmung gefunden werden. Meintest Du vielleicht...' . "\n\n";
            $output .= $suggestionList . "\n\n";
            $output .= 'Dann gib nun "/haltestelle ID" ein';
        } else {
            $output = 'Bitte gebe einen Haltestellennamen an';
        }
    }

    apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => $output, 'parse_mode' => 'HTML'));
}

/**
 * @param $message
 * @param $arrRequest
 * @param $chat_id
 *
 * @see https://ayacoo.bellatrix.uberspace.de/kvb/generator/mofi.php
 */
function handleStoerung($message, $arrRequest, $chat_id)
{
    unset($arrRequest[0]);
    $choosenLine = (int)implode(' ', $arrRequest);

    $result = getData(BASE_URL . 'json/busDisruptions.json');
    $jsonArr = json_decode($result);
    $output = handleDisruptions($jsonArr, $choosenLine);

    $result = getData(BASE_URL . 'json/tramDisruptions.json');
    $jsonArr = json_decode($result);
    $output .= handleDisruptions($jsonArr, $choosenLine);

    if ($output === '') {
        if ($choosenLine > 0) {
            $output = 'Aktuell sind keine Störungen auf der Linie ' . $choosenLine . ' bekannt. Stand: ' . date('d.m.Y H:i') . ' Uhr';
        } else {
            $output = 'Aktuell sind keine Störungen bekannt. Stand: ' . date('d.m.Y H:i') . ' Uhr';
        }
    }

    apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => $output, 'parse_mode' => 'HTML'));
}

/**
 * @param $jsonArr
 * @param $choosenLine
 * @return string
 */
function handleDisruptions($jsonArr, $choosenLine)
{
    $output = '';
    foreach ($jsonArr as $entry) {
        $arrLines = explode(',', $entry->lines);
        if ($choosenLine === 0 || in_array($choosenLine, $arrLines)) {
            $delay = null;
            $type = null;

            switch ($entry->state) {
                case 'delay':
                    $type = 'Verspätung';
                    break;
                case 'hightraffic':
                    $type = 'Hohes Verkehrsaufkommen';
                    break;
                case 'cancellation':
                    $type = 'Folgende Fahrt entfällt';
                    break;
                default:
                    $type = $entry->state;
                    break;
            }

            if ($entry->delay > 0) {
                $delay = ' - ' . $entry->delay . ' Minuten';
            }

            $output .= $type . ' - Linie(n) ' . $entry->lines;
            $output .= $delay . ' - ' . implode(' ', $entry->stations) . "\n\n";
        }
    }
    return $output;
}

function handleLinienweg($message, $chat_id)
{
    $arrRequest = explode(' ', $message['text']);
    array_shift($arrRequest);
    $line = (int)trim(strtolower(implode(' ', $arrRequest)));

    if ($line > 0) {
        $url = 'http://www.kvb-koeln.de/german/hst/showline/36/' . $line . '/';
        $content = getData($url);
        if ($content !== false) {
            $content = str_replace('&nbsp;', '', $content);
            $doc = phpQuery::newDocumentHTML($content);

            $arrStations = [];
            $stations = pq('#content > div.fliesstext.mobile100pc > div > div:nth-child(2) > table > tr > td > a');
            foreach ($stations as $station) {
                $arrStations[] = trim(pq($station)->text());
            }

            $message = implode(' - ', $arrStations);

            apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => $message, 'parse_mode' => 'HTML'));
        }
    }
}

function handleKoelntakt($message, $chat_id)
{
    $arrRequest = explode(' ', $message['text']);
    array_shift($arrRequest);
    $numberOfFiles = (int) trim(strtolower(implode(' ', $arrRequest)));
    if ($numberOfFiles === 0) {
        $numberOfFiles = 3;
    }

    $counter = 0;

    $content = getData(BASE_URL . 'json/koelntakt.json');
    $koelnTakt = json_decode($content);

    $output = '';

    foreach ($koelnTakt as $file) {
        if ($counter < $numberOfFiles) {
            $output .= '<a href="' . $file->link . '">Download ' . $file->title . '</a>' . "\n";
        }
        $counter++;
    }

    apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => $output, 'parse_mode' => 'HTML'));
}