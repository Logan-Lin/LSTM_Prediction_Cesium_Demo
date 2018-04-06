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

            function getStationNameArray($sql) {
                $result = Utils::getSqlResult($sql);
                $stationNameArray = array();
                if ($result->num_rows > 0){
                    while ($row = $result->fetch_assoc()) {
                        $stationNameArray[] = $row["stationName"];
                    }
                }
                return $stationNameArray;
            }
            
//            $sql = "select stationName as name, latitude, longitude from KDD.bj_grid_location";
            $stationSql = "select id as stationName, latitude, longitude from KDD.bj_station_location";
            $gridSql = "select stationName as stationName, latitude, longitude from KDD.bj_grid_location";
            $stationCoordinates = Utils::sqlToCoordinates($stationSql);
            $gridCoordinates = Utils::sqlToCoordinates($gridSql);

            $stationNameArray = getStationNameArray($stationSql);
            $gridNameArray = getStationNameArray($gridSql);
            $stationColor = array(1, 0, 0);
            $gridColor = array(0, 1, 1);

            $aqDescriptionArray = array();
            foreach ($stationNameArray as $stationName) {
                $aqDescriptionArray[] = Utils::getAQDescriptions($stationName, $_POST["start_date"], $_POST["end_date"]);
            }
//            $meoDescriptionArray = array();
//            foreach ($gridNameArray as $gridName) {
//                $meoDescriptionArray[] = Utils::getMeoDescriptions($gridName, $_POST["start_date"], $_POST["end_date"]);
//            }

            echo Utils::coor2PointString($stationCoordinates, '', false, $stationColor, $stationNameArray, 0, $aqDescriptionArray);
            echo Utils::coor2PointString($gridCoordinates, '', false, $gridColor, $gridNameArray, 0, null);
        ?>
        <script>
            viewer.zoomTo(viewer.entities);
        </script>
    </body>
</html>