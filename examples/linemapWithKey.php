<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Simple Polylines</title>
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

    // This example creates a 2-pixel-wide red polyline showing the path of William
    // Kingsford Smith's first trans-Pacific flight between Oakland, CA, and
    // Brisbane, Australia.

    function initMap() {
        var myLatLng = {lat: 50.938056, lng: 6.956944};
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 12,
            center: myLatLng,
            mapTypeId: google.maps.MapTypeId.TERRAIN
        });

        <?php
        echo 'var flightPlanCoordinates = [';

        $json = file_get_contents('../json/linemap.json');
        $arrStations = json_decode($json);

        $json = file_get_contents('../json/linemap.json');
        $arrStations = json_decode($json);

        $i = 0;
        foreach ($arrStations->features as $key => $station) {
            $i++;
            foreach ($station->geometry->paths[0] as $loc) {
                echo '{lat: '.$loc[1].', lng: '.$loc[0].'},';
            }
            if ($i > 2) {
                break;
            }
        }

        echo '];';
        ?>

        var flightPath = new google.maps.Polyline({
            path: flightPlanCoordinates,
            geodesic: true,
            strokeColor: '#FF0000',
            strokeOpacity: 1.0,
            strokeWeight: 2
        });

        flightPath.setMap(map);
    }

</script>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDJffyj3Xj86QLSB0SXKeXcNxlIiUI1Xas&signed_in=true&callback=initMap"></script>
</body>
</html>