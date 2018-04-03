<html>
    <head>
        <title>Show cell Trajectory</title>
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

            $showLabel = $_POST["show_label"] == 'on' ? true : false;

            for ($i = 1; $i <= 5; $i++) {
                $userId = $_POST["user_id"];
                $date = "2016-08-0{$i}";
                if ($_POST["data"] == "cell") {
                    $coordiantes = Utils::getUserCellCoordinates($userId, $date);
                } else if ($_POST["data"] == "block") {
                    $coordiantes = Utils::getUserBlockCoordinates($userId, $date);
                }
                $color = array(1 - $i / 5, $i / 5, $i / 5);
                echo Utils::coor2PointString($coordiantes, '', $showLabel, $color, null, $i / 10000);
                echo Utils::coor2LineScript($coordiantes, $color, $i / 10000);
            }
            // $coordinates1 = Utils::getUserCellCoordinates($_POST["user_id"], "2016-08-01");
            // $color1 = array(1, 1, 0.1);
            // echo Utils::coor2PointString($coordinates1, '', $showLabel, $color1, null, 0);
        ?>

        <script>
            viewer.zoomTo(viewer.entities);
        </script>
    </body>
</html>