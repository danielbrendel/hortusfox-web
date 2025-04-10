<?php

/**
 * Class WeatherModule
 * 
 * Manages weather data from OpenWeatherMap
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
            $data = json_decode(CacheModel::remember('weather_today', app('owm_cache', self::WEATHER_CACHE_TIME), function() { 
                return static::request('/data/2.5/weather?appid=' . app('owm_api_key') . '&lat=' . app('owm_latitude') . '&lon=' . app('owm_longitude') . '&units=' . app('owm_unittype'));
            }));

            if ((isset($data->cod)) && ($data->cod != 200)) {
                if (isset($data->message)) {
                    throw new \Exception($data->message);
                } else {
                    throw new \Exception('Unknown exception occurred.');
                }
            }

            return $data;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $data
     * @return int
     * @throws \Exception
     */
    public static function findBaseTime($entries)
    {
        try {
            foreach ($entries as $entry) {
                $time = convert_date('H:00', $entry->dt);

                if ($time === '00:00') {
                    return 0;
                } else if ($time === '01:00') {
                    return 1;
                } else if ($time === '02:00') {
                    return 2;
                } else if ($time === '03:00') {
                    return 3;
                }
            }

            throw new \Exception('Base time not found');
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

            if ((isset($forecast->cod)) && ($forecast->cod != 200)) {
                if (isset($forecast->message)) {
                    throw new \Exception($forecast->message);
                } else {
                    throw new \Exception('Unknown exception occurred.');
                }
            }

            $forecast->is_filled = false;
            $btnum = static::findBaseTime($forecast->list);
            $fillcheck = '0' . $btnum . ':00';

            $time = convert_date('H:00', $forecast->list[0]->dt);
            if ($time !== $fillcheck) {
                $forecast->is_filled = true;
            }
            
            $first_date = convert_date('H:i', $forecast->list[0]->dt);
            
            $fills = [];
            $count = $btnum;
            $sc = 0;

            foreach ($forecast->list as $key => $item) {
                $timeStr = (($count < 10) ? '0' : '') . strval($count) . ':00';
                if ($timeStr !== $first_date) {
                    $obj = new stdClass();
                    $obj->filled = true;
                    $obj->timeStr = $timeStr;
                    $obj->dt = strtotime(convert_date('Y-m-d ' . $timeStr));

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
