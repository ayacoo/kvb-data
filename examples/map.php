<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>KVB Map mit Abfahrtszeiten</title>
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
</head>
<body>
<div id="map"></div>
<script>


    function initMap() {
        var myLatLng = {lat: 50.938056, lng: 6.956944};
        var marker = [];
        var infowindow = new google.maps.InfoWindow();
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 10,
            center: myLatLng
        });
        var bounds = new google.maps.LatLngBounds();

        <?php
        $json = file_get_contents('../json/tramStations.json');
        $arrStations = json_decode($json);
        foreach ($arrStations->features as $key => $station) {
            // Koordinaten sichern
            $uid = $station->attributes->KNOTENNUMMER;
            $latitude = $station->geometry->y;
            $longitude = $station->geometry->x;

            // Marker erstellen
            echo 'marker[' . $uid . '] = new google.maps.Marker({
                position: {lat: ' . $latitude . ', lng: ' . $longitude . '},
                map: map,
                uid : ' . $uid . ',
                title: "' . $station->attributes->NAME . '"
            });' . "\n";

            // Infowindow öffnen
            echo 'marker[' . $uid . '].addListener("click", function() {
                loadContent(marker[' . $uid . ']);
            });' . "\n";

            echo 'bounds.extend(marker[' . $uid . '].position);' . "\n";

        }
        ?>
        map.fitBounds(bounds);

        /**
         * Inhalt einer Haltestelle laden
         * @param marker
         */
        function loadContent(marker) {
            $.ajax({
                url: '/kvb/examples/station.php?id=' + marker.uid,
                success: function (data) {
                    var obj = jQuery.parseJSON( data );
                    var content = '';
                    var title = [];
                    $.each( obj, function( key, val ) {
                        if (typeof val.line == "undefined") {
                            title.push(val);
                        } else {
                            content += '<tr><td>' + val.line + '</td><td>' + val.direction + '</td><td>' + val.time + '</td></tr>';
                        }
                    });

                    infowindow.setContent('<h3>' + title.join(' - ') + '</h3><table cellpadding="2">' + content + '</table>');
                    infowindow.open(map, marker);
                }
            });
        }
    }

</script>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=[GOOGLE_MAPS_KEY]&callback=initMap"></script>
</body>
</html>