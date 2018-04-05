<html>
    <head>
        <title>Simple Demo</title>
        <!-- Use correct character set. -->
        <meta charset="utf-8">
        <!-- Tell IE to use the latest, best version. -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- Make the application on mobile take up the full browser screen and disable user scaling. -->
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
        <script src="../../Build/Cesium/Cesium.js"></script>
        <style>
            @import url(../../Build/Cesium/Widgets/widgets.css);
            html, body, #cesiumContainer {
                width: 100%; height: 100%; margin: 0; padding: 0; overflow: hidden;
            }
        </style>
    </head>
    <body>
        <div id="cesiumContainer"></div>
        <script>
            var viewer = new Cesium.Viewer('cesiumContainer');
        </script>
        <?php
            include "../Entities/Utils.php";
            
//            $sql = "select stationName as name, latitude, longitude from KDD.bj_grid_location";
        $sql1 = "select latitude, longitude from KDD.bj_station_location";
        $sql2 = "select stationName as name, latitude, longitude from KDD.bj_grid_location";
            $coordiantes = Utils::sqlToCoordinates($sql1);
            $coordinates2 = Utils::sqlToCoordinates($sql2);
//            $result = Utils::getSqlResult($sql);
//            $nameArray = array();
//            if ($result->num_rows > 0){
//                while ($row = $result->fetch_assoc()) {
//                    $nameArray[] = $row["name"];
//                }
//            }
            $color = array(1, 0, 0);
            $color2 = array(0, 1, 1);
            echo Utils::coor2PointString($coordiantes, '', false, $color, null, 0);
            echo Utils::coor2PointString($coordinates2, '', false, $color2, null, 0);
        ?>
        <script>
            viewer.zoomTo(viewer.entities);
        </script>
    </body>
</html>