<?php

/**
 * Class GbifModule
 * 
 * Manages interface to the Global Biodiversity Information Facility
 */
class GbifModule {
    const API_ENDPOINT = 'https://api.gbif.org/v1/';

    /**
     * @param $identifier
     * @param $information
     * @param $params
     * @return mixed
     * @throws \Exception
     */
    public static function species($identifier, $information, array $params = [])
    {
        try {
            $resource = "species/$identifier/$information";
            $params = http_build_query($params);

            if (strlen($params) > 0) {
                $params .= '?' . $params;
            }

            return static::request($resource . $params);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $section
     * @return bool
     * @throws \Exception
     */
    public static function hasSection($section)
    {
        try {
            if (method_exists(self::class, $section)) {
                $reflection = new ReflectionMethod(self::class, $section);

                return $reflection->isStatic();
            }

            return false;
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
            $ch = curl_init(self::API_ENDPOINT . $resource);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, false);

            $response = curl_exec($ch);

            $error = curl_error($ch);
            if ((is_string($error)) && (strlen($error) > 0)) {
                throw new \Exception($error);
            }

            curl_close($ch);

            return json_decode($response);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
