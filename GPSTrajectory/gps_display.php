<html>
    <head>
        <title>Show GPS Trajectory</title>
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

        $start_time = null;
        $end_time = null;
        if (strlen($_POST["start_time"]) > 0) {
            $start_time = $_POST["start_time"];
        }
        if (strlen($_POST["end_time"]) > 0) {
            $end_time = $_POST["end_time"];
        }

        $coorArray = Utils::getUserGPSCoordinates($_POST['user_id'], $_POST['date'], $start_time, $end_time);
        
        $showLabel = $_POST["show_label"] == 'on' ? true : false;
        echo Utils::coor2PointString($coorArray, '', $showLabel);
        
        if ($_POST["show_staypoint"] == 'on') {
            $staypoint_id = intval($_POST['user_id']);
            $staypointArray = Utils::getUserStaypointCoordinates("{$staypoint_id}", $_POST['date'], $start_time, 
                $_POST['date'], $end_time);
            echo Utils::coor2PointString($staypointArray, "Staypoint-", true, array(1, 0, 0));
        }
        ?>

        <script>
            viewer.zoomTo(viewer.entities);
        </script>
    </body>
</html>