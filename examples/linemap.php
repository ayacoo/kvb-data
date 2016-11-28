<?php
$choosenLine = 0;
if (isset($_GET['line'])) {
    $choosenLine = intval($_GET['line']);
}

$json = file_get_contents('../json/linecolors.json');
$arrColors = json_decode($json);

?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>KVB Liniennetzplan</title>
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        #map {
            height: 100%;
        }
    </style>
</head>
<body>
<div id="map"></div>
<script>
    function initMap() {
        var myLatLng = {lat: 50.938056, lng: 6.956944};
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 12,
            center: myLatLng,
            mapTypeId: google.maps.MapTypeId.TERRAIN
        });

        <?php
        $json = file_get_contents('../json/linemap.json');
        $arrStations = json_decode($json);

        $i = 0;
        foreach ($arrStations->features as $key => $station) {
            $line = trim(str_replace('Linie', '', $station->attributes->LINIENVERKEHR));
            $arrLines = explode(',', $line);
            // Prüfung ob die Linie korrekt gesetzt wurde, alternativ zeige alles an
            $tmpLine = intval($arrLines[0]);
            if ((in_array($choosenLine, $arrLines) || ($choosenLine === 0)) && ($tmpLine > 0)) {
                $i++;
                echo 'var linePathCoordinates' . $i . ' = [';
                foreach ($station->geometry->paths[0] as $loc) {
                    echo '{lat: ' . $loc[1] . ', lng: ' . $loc[0] . '},';
                }
                echo '];';

                // Farbe kommt entweder von der ausgewählten Linie oder vom ersten Parameter in der OpenData Datei
                if ($choosenLine > 0) {
                    $color = $arrColors->$choosenLine;
                } else {
                    $color = $arrColors->$tmpLine;
                }

                echo 'var linePath' . $i . ' = new google.maps.Polyline({
                    path: linePathCoordinates' . $i . ',
                    geodesic: true,
                    strokeColor: "' . $color . '",
                    strokeOpacity: 1.0,
                    strokeWeight: 3
                });
                linePath' . $i . '.setMap(map);';
            }
        }
        ?>
    }

</script>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=[GOOGLE_MAPS_KEY]&callback=initMap"></script>
</body>
</html>