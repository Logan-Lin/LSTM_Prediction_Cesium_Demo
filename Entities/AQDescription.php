<?php
class AQDescription
{
    private $time;
    private $PM2;
    private $PM10;
    private $NO2;
    private $CO;
    private $O3;
    private $SO2;

    public function __construct($time, $PM2, $PM10, $NO2, $CO, $O3, $SO2)
    {
        $this->time = $time;
        $this->PM2 = $PM2;
        $this->PM10 = $PM10;
        $this->NO2 = $NO2;
        $this->CO = $CO;
        $this->O3 = $O3;
        $this->SO2 = $SO2;
    }

    public function toHTML()
    {
        $string = "\<p></p>\
            UTC Time: {$this->time}<br>\
            <table>\
            <tr><td>PM2.5</td><td>PM10</td><td>NO2</td><td>CO</td><td>O3</td><td>SO2</td></tr>\
            <tr><td>{$this->PM2}</td><td>{$this->PM10}</td><td>{$this->NO2}</td><td>{$this->CO}</td><td>{$this->O3}</td><td>{$this->SO2}</td></tr>\
            </table>";
        return $string;
    }
}