<?php
class Line {
    private $name;
    private $start;
    private $end;
    private $width;
    private $color;
    
    public function __construct($name, $start, $end, $width, $color) {
        $this->name = $name;
        $this->start = $start;
        $this->end = $end;
        $this->width = $width;
        $this->color = $color;
    }

    public function toCesiumScript() {
        $string = "
            var rbgColor = new Cesium.Color(
                {$this->color[0]}, {$this->color[1]}, {$this->color[2]}, 1);
            var line_material = new Cesium.PolylineOutlineMaterialProperty({
                color : rbgColor
            });
            viewer.entities.add({
                polyline : {
                    positions : Cesium.Cartesian3.fromDegreesArray([{$this->start[1]}, {$this->start[0]},
                        {$this->end[1]}, {$this->end[0]}]),
                    width : {$this->width},
                    material : line_material
                }
            });";
        return $string;
    }
}