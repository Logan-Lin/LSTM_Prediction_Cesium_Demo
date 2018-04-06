<?php
/**
 * Created by PhpStorm.
 * User: loganlin
 * Date: 2018/4/5
 * Time: 下午1:53
 */

class MeoDescription
{
    private $time;
    private $temperature;
    private $pressure;
    private $humidity;
    private $wind_direction;
    private $wind_speed;

    /**
     * MeoDescription constructor.
     * @param $time
     * @param $temperature
     * @param $pressure
     * @param $humidity
     * @param $wind_direction
     * @param $wind_speed
     */
    public function __construct($time, $temperature, $pressure, $humidity, $wind_direction, $wind_speed)
    {
        $this->time = $time;
        $this->temperature = $temperature;
        $this->pressure = $pressure;
        $this->humidity = $humidity;
        $this->wind_direction = $wind_direction;
        $this->wind_speed = $wind_speed;
    }

    public function toHTML()
    {
        $string = "\<p></p>\
            UTC Time: {$this->time}<br>\
            <table>\
            <tr><td>Temperature</td><td>Pressure</td><td>Humidity</td><td>Wind Direction</td><td>Wind Speed</td></tr>\
            <tr><td>{$this->temperature}</td><td>{$this->pressure}</td><td>{$this->humidity}</td><td>{$this->wind_direction}</td><td>{$this->wind_speed}</td></tr>\
            </table>";
        return $string;
    }
}