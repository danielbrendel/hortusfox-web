<?php

/**
 * This class represents your module
 */
class WeatherModule {
    const WEATHER_API_ENDPOINT = 'http://api.openweathermap.org';
    const WEATHER_ICON_ENDPOINT = 'https://openweathermap.org/img/';
    const WEATHER_CACHE_TIME = 300;

    /**
     * @return mixed
     * @throws \Exception
     */
    public static function today()
    {
        try {
            return static::request('/data/2.5/weather?appid=' . app('owm_api_key') . '&lat=' . app('owm_latitude') . '&lon=' . app('owm_longitude') . '&units=metric');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public static function cachedToday()
    {
        try {
            return json_decode(CacheModel::remember('weather_today', self::WEATHER_CACHE_TIME, function() { 
                return WeatherModule::today();
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
        return static::request('/data/2.5/forecast?appid=' . app('owm_api_key') . '&lat=' . app('owm_latitude') . '&lon=' . app('owm_longitude') . '&units=metric');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public static function cachedForecast()
    {
        try {
            return json_decode(CacheModel::remember('weather_forecast', self::WEATHER_CACHE_TIME, function() { 
                return WeatherModule::forecast();
            }));
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
