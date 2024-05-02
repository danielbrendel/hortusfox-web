<?php

/**
 * This class represents your module
 */
class WeatherModule {
    const WEATHER_API_ENDPOINT = 'http://api.openweathermap.org';
    const WEATHER_ICON_ENDPOINT = 'https://openweathermap.org/img/';
    const WEATHER_CACHE_TIME = 300;

    /**
     * @return array
     */
    public static function getUnitTypes()
    {
        return [
            'default' => 'Kelvin',
            'imperial' => 'Fahrenheit',
            'metric' => 'Celsius'
        ];
    }

    /**
     * @return string
     */
    public static function getUnitChar($unit)
    {
        foreach (static::getUnitTypes() as $type => $value) {
            if ($type === $unit) {
                return strtoupper(substr($value, 0, 1));
            }
        }

        return '';
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public static function today()
    {
        try {
            return json_decode(CacheModel::remember('weather_today', app('owm_cache', self::WEATHER_CACHE_TIME), function() { 
                return static::request('/data/2.5/weather?appid=' . app('owm_api_key') . '&lat=' . app('owm_latitude') . '&lon=' . app('owm_longitude') . '&units=' . app('owm_unittype'));
            }));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public static function forecast()
    {
        try {
            $forecast = json_decode(CacheModel::remember('weather_forecast', app('owm_cache', self::WEATHER_CACHE_TIME), function() { 
                return static::request('/data/2.5/forecast?appid=' . app('owm_api_key') . '&lat=' . app('owm_latitude') . '&lon=' . app('owm_longitude') . '&units=' . app('owm_unittype'));
            }));

            if ((!isset($forecast->cod)) && (!$forecast->cod != 200)) {
                throw new \Exception('Forecast query failed.');
            }

            $forecast->is_filled = false;

            $time = date('H:00', $forecast->list[0]->dt);
            if ($time !== '02:00') {
                $forecast->is_filled = true;
            }
            
            $first_date = date('H:i', $forecast->list[0]->dt);
            
            $fills = [];
            $count = 2;
            $sc = 0;

            foreach ($forecast->list as $key => $item) {
                $timeStr = (($count < 10) ? '0' : '') . strval($count) . ':00';
                if ($timeStr !== $first_date) {
                    $obj = new stdClass();
                    $obj->filled = true;
                    $obj->timeStr = $timeStr;
                    $obj->dt = strtotime(date('Y-m-d ' . $timeStr));

                    $count += 3;
                    $sc++;

                    $fills[] = $obj;
                } else {
                    break;
                }
            }

            if ($forecast->is_filled) {
                for ($i = count($fills) - 1; $i >= 0; $i--) {
                    array_unshift($forecast->list, $fills[$i]);
                }
            }
            
            return $forecast;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public static function clearCache()
    {
        try {
            CacheModel::reset('weather_today');
            CacheModel::reset('weather_forecast');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $resource
     * @return mixed
     * @throws \Exception
     */
    private static function request($resource)
    {
        try {
            $ch = curl_init(self::WEATHER_API_ENDPOINT . $resource);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, false);

            $response = curl_exec($ch);

            $error = curl_error($ch);
            if ((is_string($error)) && (strlen($error) > 0)) {
                throw new \Exception($error);
            }

            curl_close($ch);

            return $response;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
