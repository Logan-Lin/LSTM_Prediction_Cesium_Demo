<?php

class Utils
{
    public static function location2coo($locationID)
    {
        $conn = new mysqli('localhost:3306', 'root', '094213', 'NextPre');
        if (!$conn) {
            die('Cannot connect to SQL.');
        }
        $sql = "select latitude, longitude from NextPre.OPTICS_Sequence " .
            "WHERE level_3 like {$locationID}";
        $returnValue = $conn->query($sql);
        if (!$returnValue) {
            die("Can't get data.");
        }
        $rows = $returnValue->fetch_all();
        $latitudes = array();
        $longitudes = array();
        foreach ($rows as $row) {
            $latitudes[] = $row[0];
            $longitudes[] = $row[1];
        }
        $avg_latitude = array_sum($latitudes) / count($latitudes);
        $avg_longitude = array_sum($longitudes) / count($longitudes);
        $conn->close();
        return array($avg_latitude, $avg_longitude);
    }

    public static function file2PointString($dataStringArray, $colorArray)
    {
        $string = "";
        $fileNum = 0;
        foreach ($dataStringArray as $dataString) {
            $dataLines = explode("\n", $dataString);
            for ($n = 0; $n < count($dataLines); $n++) {
                $pointNum = count($dataLines);
                $split = explode(",", $dataLines[$n]);
                $index = $n + 1;
                $r = 0.5 * sin(5 * $index / $pointNum - 4) + 0.5;
                $g = 0.5 * sin(5 * $index / $pointNum - 0.5) + 0.5;
                $b = 0.5 * sin(5 * $index / $pointNum - 1.2) + 0.5;
                $color = array($r, $g, $b); // The RGB value of color
                $point = new Point($n + 1, .0 + $split[1], .0 + $split[0],
                    $color, false);
                $string .= $point->toCesiumScript();
            }
            $fileNum++;
        }
        return $string;
    }
}