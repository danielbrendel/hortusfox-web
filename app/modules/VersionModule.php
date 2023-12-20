<?php

/**
 * This class represents your module
 */
class VersionModule {
    /**
     * @return string
     * @throws \Exception
     */
    public static function getVersion()
    {
        try {
            $ch = curl_init(env('APP_SERVICE_URL') . '/software/version');

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
            throw $e;
        }
    }
}
