<html>
    <head>
        <title>Show similar pairs</title>
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

            $user_id1 = trim(explode('-', $_POST["pair_id"])[0]);
            $user_id2 = trim(explode('-', $_POST["pair_id"])[1]);
            
            $coorArray1 = Utils::getUserStaypointCoordinates($user_id1);
            $coorArray2 = Utils::getUserStaypointCoordinates($user_id2);

            $color1 = array(0, 1, 0);
            $color2 = array(1, 0, 0);

            $showLabels = ($_POST["show_labels"] == "on") ? true : false;
            echo Utils::coor2PointString($coorArray1, 'A-', $showLabels, $color1);
            echo Utils::coor2PointString($coorArray2, 'B-', $showLabels, $color2);
        ?>
        <script>
            viewer.zoomTo(viewer.entities);
        </script>
    </body>
</html>