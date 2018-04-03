<html>
    <head>
        <title>Prediction Display</title>
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

            $seq_id_array = Utils::getSequenceArray($_POST["user_id"]);
            $pre_id_array = Utils::getPredictArray($_POST["user_id"]);

            $sequence_coors = Utils::locationsToCenters(3, $seq_id_array);
            $predict_coors = Utils::locationsToCenters(3, $pre_id_array);

            $color1 = array(0, 1, 0);
            $color2 = array(1, 0, 0);

            $showLabels = ($_POST["show_labels"] == "on") ? true : false;
            echo Utils::coor2PointString($sequence_coors, 'Seq-', $showLabels, $color1);
            echo Utils::coor2PointString($predict_coors, 'Pre-', $showLabels, $color2, null, 0.0001);
            echo Utils::coor2LineScript($sequence_coors, $color1);
            echo Utils::coor2LineScript($predict_coors, $color2, 0.0001);
        ?>

        <script>
            viewer.zoomTo(viewer.entities);
        </script>
    </body>
</html>