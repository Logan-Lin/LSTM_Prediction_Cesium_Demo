<?php
class Point
{
    private $name;
    private $longitude;
    private $latitude;
    private $color;
    private $label;

    public function __construct($name, $longitude, $latitude, $color, $label)
    {
        $this->name = $name;
        $this->longitude = $longitude;
        $this->latitude = $latitude;
        $this->color = $color;
        $this->label = $label;
    }

    public function toCesiumScript()
    {
        $string = "
            var rbgColor = new Cesium.Color(
                {$this->color[0]}, {$this->color[1]}, {$this->color[2]}, 1.0);
            viewer.entities.add({
                name: '$this->name',
                position: Cesium.Cartesian3.fromDegrees($this->longitude, $this->latitude),
                point: {
                    pixelSize: 6,
                    color: rbgColor,
                    outlineColor : Cesium.Color.BLACK,
                    outlineWidth : 1
            }";
        if ($this->label)
        {
            $string = $string . ", label: {
                text: '$this->name', 
                font: '10pt Times New Roman',
                style: Cesium.LabelStyle.FILL_AND_OUTLINE, 
                outlineWidth : 2,
                verticalOrigin : Cesium.VerticalOrigin.BOTTOM,
                pixelOffset : new Cesium.Cartesian2(0, -9)
            }";
        }
        $string = $string . "});";
        return $string;
    }
}

function createPointsScript(array $names, array $longitudes, array $latitudes, $color, $label)
{
    $pointScript = "";
    for ($i = 0; $i < count($names); $i++)
    {
        $point = new Point($names[$i], $longitudes[$i], $latitudes[$i], $color, $label);
        $pointScript = $pointScript . $point->toCesiumScript();
    }
    return $pointScript;
}