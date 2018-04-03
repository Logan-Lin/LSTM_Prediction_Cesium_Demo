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
    <script src="../../Build/Cesium/Cesium.js"></script>
    <style>
        @import url(../../Build/Cesium/Widgets/widgets.css);
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
include "Point.php";
include "Utils.php";

$colors = array("GREEN", "RED", "BLUE", "PINK", "WHITE", "BLACK");

echo "<script>";

//$locationString = "160,352,164,134,233,142,143,240,145,178,147,148,149,246,344,152,410,154,447,351";
//$locationArray = explode($locationString, ',');
//$scriptString = "";
//for ($i = 0; $i < count($locationArray); $i++) {
//    $coordinate = Utils::location2coo($locationArray[$i]);
//    $point = new Point($i, $coordinate[1], $coordinate[0], 'RED', false);
//    $scriptString .= $point->toCesiumScript();
//}
//echo $scriptString;

if ($_FILES["file1"]["type"] == "text/plain" && $_FILES["file2"]["type"] == "text/plain") {
    if ($_FILES["file1"]["error"] > 0 || $_FILES["file2"]["error"] > 0) {
        echo "File Error: " . $_FILES["file1"]["error"] . $_FILES["file2"]["error"];
    } else {
        $dataString1 = file_get_contents($_FILES["file1"]["tmp_name"]) or
        exit("Unable to read the file");
        $dataString2 = file_get_contents($_FILES["file2"]["tmp_name"]) or
        exit("Unable to read the file");
        $dataStringArray = array($dataString1, $dataString2);
        echo Utils::file2PointString($dataStringArray, $colors);
    }
} else {
    exit("Invalid file");
}

echo "</script>";
?>
<script>
    viewer.zoomTo(viewer.entities);
</script>
</body>
</html>
