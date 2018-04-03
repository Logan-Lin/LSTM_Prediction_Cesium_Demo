<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Use correct character set. -->
    <meta charset="utf-8">
    <!-- Tell IE to use the latest, best version. -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Make the application on mobile take up the full browser screen and disable user scaling. -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
    <title>Prediction Demo</title>
    <script src="../Build/Cesium/Cesium.js"></script>
    <style>
        @import url(../Build/Cesium/Widgets/widgets.css);
        html, body, #cesiumContainer {
            width: 100%; height: 100%; margin: 0; padding: 0; overflow: hidden;
        }
    </style>
    <link rel="icon" href="icon.png">
</head>
<body>
<div id="cesiumContainer"></div>
<script>
    var viewer = new Cesium.Viewer('cesiumContainer');
</script>
<?php
include "Line.php";

$colors = array("RED", "YELLOW", "BLUE", "PINK", "WHITE", "BLACK");

echo "<script>";

$testLine = new Line("Test Line", 0, 0, 50, 50, "RED", true);
echo $testLine->toCesiumScript();

echo "</script>";
?>
<script>
    viewer.zoomTo(viewer.entities);
</script>
</body>
</html>
