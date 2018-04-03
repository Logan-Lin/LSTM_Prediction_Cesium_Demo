<?php
class Line
{
    private $name = '';
    # The point array means (longitude, latitude).
    // private $startPoint = array(0.0, 0.0);
    // private $endPoint = array(0.0, 0.0);
    private $startLon = 0.0;
    private $startLat = 0.0;
    private $endLon = 0.0;
    private $endLat = 0.0;
    private $color = 'RED';
    private $label = false;

    public function __construct($name, $startLon, $startLat, $endLon, $endLat, $color, $label)
    {
        $this->name = $name;
        $this->startLon = $startLon;
        $this->startLat = $startLat;
        $this->endLon = $endLon;
        $this->endLat = $endLat;
        $this->color = $color;
        $this->label = $label;
    }

    # This script will draw a line filled in $color with a black outline, from 
    # point ($startLon, $startLat) to point ($endLon, $endLat)
    # Of course you have to put these scripts into a ceisum viewer page...
    public function toCesiumScript()
    {
        return $this->__toString();
    }

    public function __toString()
    {
        $string = "viewer.entities.add({
            name : '$this->name',
            polyline : {
                positions : Cesium.Cartesian3.fromDegreesArray(
                    [$this->startLon, $this->startLat, $this->endLon, $this->endLat]),
                width : 3,
                material : new Cesium.PolylineOutlineMaterialProperty({
                    color : Cesium.Color.$this->color,
                    outlineWidth : 1,
                    outlineColor : Cesium.Color.BLACK })
                    ";
        if ($this->label)
        {
            $string .= ", label: {
                text: '$this->name', 
                font: '14pt monospace',
                style: Cesium.LabelStyle.FILL_AND_OUTLINE, 
                outlineWidth : 2,
                verticalOrigin : Cesium.VerticalOrigin.BOTTOM,
                pixelOffset : new Cesium.Cartesian2(0, -9)
            }";
        }
        $string .= "}});";
        return $string;
    }
}

// Draw a ployline join all the points user inputs.
// Or make it clear, produce Cesium script that can draw ployline...
function drawPolyline(array $points, $name, $color, $label)
{
    $lineScript = '';
    for ($i = 1; $i < count($points); $i++)
    {
        if ($label && $i >= 2)
        {
            $label = false;
        }
        $line = new Line($name, $points[$i - 1][0], $points[$i - 1][1], $points[$i][0], $points[$i][1], $color, $label);
        $lineScript .= $line->toCesiumScript();
    }
    return $lineScript;
}