<?php
include "../Entities/Point.php";
include "../Entities/Line.php";
include "../Entities/AQDescription.php";
include "../Entities/MeoDescription.php";

class Utils
{
    // The base function for SQL query.
    public static function getSqlResult($sql) {
        $conn = new mysqli("localhost:3306", "root", "094213");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $result = $conn->query($sql);
        $conn->close();
        return $result;
    }

    // Transform mapped location id into original id.
    public static function getOriginalId($mapId) {
        $map_result = Utils::getSqlResult(
            "select original_id from NextPre.optics_map where map_id like '{$mapId}'");
        $map_row = $map_result->fetch_assoc();
        $original_id = $map_row["original_id"];
        $map_result->close();
        return $original_id;
    } 

    // Returns given user's history sequence, in the form of location id array.
    public static function getSequenceArray($userId) {
        $sequence_sql = "select location_id from NextPre.user_sequence where user_id = {$userId}";
        $sequence_result = Utils::getSqlResult($sequence_sql);
        $seq_id_array = array();
        if ($sequence_result->num_rows > 0) {
            while ($seq_row = $sequence_result->fetch_assoc()) {
                $sequence_id = Utils::getOriginalId($seq_row["location_id"]);
                $seq_id_array[] = $sequence_id;
            }
        }
        return $seq_id_array;
    }

    // Returns given user's prediction sequence, in the form of location id array.
    public static function getPredictArray($userId) {
        $predict_sql = "select location_id from NextPre.user_prediction where user_id = {$userId}";
        $predict_result = Utils::getSqlResult($predict_sql);
        $pre_id_array = array();
        if ($predict_result->num_rows > 0) {
            while ($pre_row = $predict_result->fetch_assoc()) {
                $predict_id = Utils::getOriginalId($pre_row["location_id"]);
                $pre_id_array[] = $predict_id;
            }
        }
        return $pre_id_array;
    }

    // Transform coordinates array into CesiumJS point defination string.
    // $coorArray is a n*2 matrix. Each row contains (latitude, longitude).
    // $prefix is the prefix used in points' name. Points' names will be $prefix with points' indexes.
    // $label is a boolean value to determine whether to display the points' names.
    // $color is used to assign points' color. Set to null to autoassign it.
    public static function coor2PointString($coorArray, $prefix, $label, $color=null,
                                            $labelArray=null, $offset=0, $descriptionArray=null) {
        $string = "";
        for ($n = 0; $n < count($coorArray); $n++) {
            $pointNum = count($coorArray);
            $row = $coorArray[$n];
            $index = $n + 1;
            $point_color = $color === null ? self::getRgbArray($index, $pointNum) : $color;

            $size = 8;
            if ($prefix == "Staypoint-") {
                $size = 30;
            }

            $point = new Point($labelArray !== null ? $labelArray[$n] : "{$prefix}{$index}",
                $offset + $row[0], $offset + $row[1],
                $size, $point_color, $label, $descriptionArray !== null ? $descriptionArray[$n] : "");
            
            $string .= $point->toCesiumScript();
        }
        return "<script>" . $string . "</script>";
    }

    // Transform coordiantes array into CesiumJS polyline defination string.
    // The lines will be connecting the point corresponding to first row of coordinate array to the second one,
    // then second one to the third one, and so on.
    public static function coor2LineScript($coordinates, $color=null, $offset=0) {
        $pointNum = count($coordinates);
        $string = "";
        for ($i = 0; $i < $pointNum - 1; $i++) {
            $lineColor = $color === null ? self::getRgbArray($i + 1, $pointNum) : $color;
            
            $start = array($coordinates[$i][0] + $offset, $coordinates[$i][1] + $offset);
            $end = array($coordinates[$i + 1][0] + $offset, $coordinates[$i + 1][1] + $offset);
            $line = new Line($i + 1, $start, $end, 3, $lineColor);
            $string .= $line->toCesiumScript();
        }
        return "<script>" . $string . "</script>";
    }

    // Input a n*2 matrix representing the coordinates of n points, and return an array that contains the 
    // center coordinate of these points.
    public static function coor2Center($coorArray) {
        $latitudes = array_column($coorArray, 0);
        $longitudes = array_column($coorArray, 1);
        $latitude = array_sum($latitudes) / count($latitudes);
        $longitude = array_sum($longitudes) / count($longitudes);
        return array($latitude, $longitude);
    }

    // Get a color in the form of (r, g, b) array according to the total points number and this point's index.
    // If implemented to a sequence of points, can create a rainbow effect
    // and the points' color will guadually change along with points' sequence.
    public static function getRgbArray($index, $pointNum) {
        $r = 0.5 * sin(5 * $index / $pointNum - 4) + 0.5;
        $g = 0.5 * sin(5 * $index / $pointNum - 0.5) + 0.5;
        $b = 0.5 * sin(5 * $index / $pointNum - 1.2) + 0.5;
        $color = array($r, $g, $b); // The RGB value of color
        return $color;
    }

    public static function getBlockCoordinate($blockId) {
        $lng_start = 116.210;
        $lng_end = 116.560;
        $lat_start = 39.764;
        $lat_end = 40.034;
        $x_range = ($lng_end - $lng_start) / 32.0;
        $y_range = ($lat_end - $lat_start) / 32.0;

        $blockId = (int)(trim($blockId));
        $y_grid = (int)($blockId / 32);
        $x_grid = $blockId - $y_grid * 32;

        $longitude = $lng_start + $x_grid * $x_range;
        $latitude = $lat_start + $y_grid * $y_range;
        
        return array($latitude, $longitude);
    }

    public static function getUserStaypointCoordinates($user_id, $start_date=null, $start_time=null, $end_date=null, $end_time=null) {
        $sql = "select latitude, longitude from NextPre.OPTICS_Sequence " .
        "where user_id like '{$user_id}'";
        if ($start_date !== null) {
            $sql .= " and arvDT >= '{$start_date} ";
            if ($start_time !== null) {
                $sql .= "{$start_time}'";
            } else {
                $sql .= "00:00:00'";
            }
        }
        if ($end_date !== null) {
            $sql .= " and levDT <= '{$end_date} ";
            if ($end_time !== null) {
                $sql .= "{$end_time}'";
            } else {
                $sql .= "24:00:00'";
            } 
        }

        return self::sqlToCoordinates($sql);
    }

    public static function getUserGPSCoordinates($user_id, $date, $start_time=null, $end_time=null) {
        $sql = "select Latitude as latitude, Longitude as longitude from NextPre.GPS " .
        "where Id like '{$user_id}' and ".
        "Date like '{$date}'";
        if ($start_time !== null) {
            $sql .= " and Time >= '{$start_time}'";
        }
        if ($end_time !== null) {
            $sql .= " and Time <= '{$end_time}'";
        }
        
        return self::sqlToCoordinates($sql);
    }

    public static function getUserCellCoordinates($user_id, $date=null) {
        $sql = "select latitude, longitude from NextPre.cell_coordinate, NextPre.user_cell_data
        where user_cell_data.user_id like '{$user_id}'
        and user_cell_data.cell_id = cell_coordinate.cell_id";
        if ($date !== null) {
            $sql .= " and user_cell_data.arrive_time like '{$date}%'";
        }
        return self::sqlToCoordinates($sql);
    }

    public static function getUserBlockCoordinates($user_id, $date=null) {
        $sql = "select block_sequence as value from NextPre.user_cell_data 
        where user_id like '{$user_id}'";
        if ($date !== null) {
            $sql .= " and arrive_time like '{$date}%'";
        }
        $result = self::getSqlResult($sql);
        $coorArray = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $coorArray[] = self::getBlockCoordinate($row["value"]);
            }
        }
        return $coorArray;
    }

    public static function getLocationCoordinates($level, $location_id) {
        $sql = "select latitude, longitude from NextPre.OPTICS_Sequence 
        where level_{$level} like '{$location_id}';";
        return self::sqlToCoordinates($sql);
    }

    public static function locationsToCenters($level, $location_id_array) {
        $centers = array();
        foreach ($location_id_array as $location_id) {
            $centers[] = self::getLocationCenter($level, $location_id);
        }
        return $centers;
    }

    public static function getAQDescriptions($stationName, $start, $end) {
        $sql = "select * from KDD.bj_17_18_aq where 
                stationid like '{$stationName}' and utctime > '{$start} 00:00:00'
                and utctime < '{$end} 23:59:59'";
        $result = self::getSqlResult($sql);
        $descriptionHtml = "";
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $description = new AQDescription($row["utctime"], $row["PM2.5"],
                    $row["PM10"], $row["NO2"], $row["CO"], $row["O3"], $row["SO2"]);
                $descriptionHtml .= $description->toHTML();
            }
        }
        $result->close();
        return $descriptionHtml;
    }

    public static function getMeoDescriptions($gridName, $start, $end) {
        $sql = "select * from KDD.bj_historical_meo_grid where 
                stationName like '{$gridName}' and utctime > '{$start} 00:00:00'
                and utctime < '{$end} 23:59:59'";
        $result = self::getSqlResult($sql);
        $descriptionHTML = "";
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $description = new MeoDescription($row["utctime"], $row["temperature"],
                    $row["pressure"], $row["humidity"], $row["wind_direction"], $row["wind_speed"]);
                $descriptionHTML .= $description->toHTML();
            }
        }
        $result->close();
        return $descriptionHTML;
    }

    // Query an SQL statement and return a n*2 matrix with each row corresponding (latitude, longitude).
    // Note that rows of the SQL statement's result must have a form of ('latitude', 'longitude').
    // In most circumstances, this function is meant to be use in function getUser%Coordinates.
    public static function sqlToCoordinates($sql) {
        $result = self::getSqlResult($sql);

        $coordinates_full = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $coordinate = array($row["latitude"], $row["longitude"]);
                $coordinates_full[] = $coordinate;
            }
        }

        $maxPointNum = 500;
        $interval = (count($coordinates_full) / $maxPointNum);
        $interval = ($interval - intval($interval) == 0) ? intval($interval) : intval($interval) + 1;
        $coordinates = array();
        $n = 0;
        while ($n < count($coordinates_full)) {
            $coordinates[] = $coordinates_full[$n];
            $n += $interval;
        }

        return $coordinates;
    }

    public static function getLocationCenter($level, $location_id) {
        $sql = "select latitude, longitude from NextPre.OPTICS_Sequence 
        where level_{$level} like '{$location_id}';";
        $coordinates = self::sqlToCoordinates($sql);
        return self::coor2Center($coordinates);
    }

    // Query an SQL statement and return html <select> options.
    // The rows of the SQL statement's result must have a form of ('value').
    public static function sqlToSelections($sql, $selected=null) {
        $result = self::getSqlResult($sql);

        $string = "";
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $value = $row["value"];
                $string .= "<option value='{$value}'";
                if ($selected == $value) {
                    $string .= " selected";
                }
                $string .= ">{$value}</option>";
            }
        }

        return $string;
    }

    public static function sqlToCheckboxs($sql, $name) {
        $result = self::getSqlResult($sql);

        $string = "";
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $value = $row["value"];
                $string .= "<input type='checkbox' value='{$value}' name='{$name}'> {$value}<br>";
            }
        }

        return $string;
    }

    public static function sqlToMaxMin($sql) {
        $result = self::getSqlResult($sql);
    
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $minValue = explode(' ', $row["minValue"])[0];
            $maxValue = explode(' ', $row["maxValue"])[0];
        }

        return array($minValue, $maxValue);
    }
}