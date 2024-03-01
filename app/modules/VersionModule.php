<?php

/**
 * This class represents your module
 */
class VersionModule {
    const CACHED_VERSION_TIME = 86400;

    /**
     * @return string
     */
    public static function getVersion()
    {
        try {
            $serviceUrl = env('APP_SERVICE_URL');

            if ((!is_string($serviceUrl)) || (!(strlen($serviceUrl) > 0))) {
                throw new \Exception('No service URL defined.');
            }

            $ch = curl_init($serviceUrl . '/software/version');

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

            $response = curl_exec($ch);
            if (curl_errno($ch)) {
                throw new \Exception(curl_error($ch));
            }

            $json = json_decode($response);

            curl_close($ch);

            if ($json->code != 200) {
                throw new \Exception($json->msg);
            }

            return $json->version;
        } catch (\Exception $e) {
            return '';
        }
    }

    /**
     * @return string
     * @throws \Exception
     */
    public static function getCachedVersion()
    {
        try {
            return CacheModel::remember('software_version', self::CACHED_VERSION_TIME, function() {
                return VersionModule::getVersion();
            });
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
