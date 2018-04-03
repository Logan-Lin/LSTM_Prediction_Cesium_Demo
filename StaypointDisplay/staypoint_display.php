<html>
    <head>
        <title>Staypoint Display</title>
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

        $start_date = null;
        $end_date = null;
        if (strlen($_POST["start_date"]) > 0) {
            $start_date = $_POST["start_date"];
        }
        if (strlen($_POST["end_date"]) > 0) {
            $end_date = $_POST["end_date"];
        }
        $coordinates = Utils::getUserStaypointCoordinates($_POST['user_id'], $start_date, null, $end_date, null);
        
        $showLabel = $_POST["show_label"] == "on" ? true : false;
        echo Utils::coor2PointString($coordinates, '', $showLabel);
        ?>

        <script>
            viewer.zoomTo(viewer.entities);
        </script>
    </body>
</html>