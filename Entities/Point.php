<?php
class Point
{
    private $name;
    private $longitude;
    private $latitude;
    private $size;
    private $color;
    private $label;
    private $description;

    public function __construct($name, $latitude, $longitude, $size, $color, $label, $description="<p>FUCK</p>")
    {
        $this->name = $name;
        $this->longitude = $longitude;
        $this->latitude = $latitude;
        $this->size = $size;
        $this->color = $color;
        $this->label = $label;
        $this->description = $description;
    }

    public function toCesiumScript()
    {
        $string = "
            var rbgColor = new Cesium.Color(
                {$this->color[0]}, {$this->color[1]}, {$this->color[2]}, 1.0);
            var point{$this->name} = viewer.entities.add({
                name: '{$this->name}, {$this->longitude}, {$this->latitude}',
                position: Cesium.Cartesian3.fromDegrees($this->longitude, $this->latitude),
                point: {
                    pixelSize: {$this->size},
                    color: rbgColor,
                    outlineColor : Cesium.Color.BLACK,
                    outlineWidth : 1
            }";
        if ($this->label) {
            $string = $string . ", label: {
                text: '$this->name', 
                font: '13pt Times New Roman',
                style: Cesium.LabelStyle.FILL_AND_OUTLINE, 
                outlineWidth : 3,
                verticalOrigin : Cesium.VerticalOrigin.BOTTOM,
                pixelOffset : new Cesium.Cartesian2(0, -8)
            }";
        }
        $string = $string . "});";
        if (strlen($this->description) > 0) {
            $string .= " point{$this->name}.description = '$this->description';";
        }
        return $string;
    }

    public function getColor() {
        return $this->color;
    }

    public function getLongitude() {
        return $this->longitude;
    }

    public function getLatitude() {
        return $this->latitude;
    }
}