<?php

/**
 * Class RecognitionModule
 * 
 * Manages plant identification through image recognition
 */
class RecognitionModule {
    const API_ENDPOINT = 'https://my-api.plantnet.org/v2/identify/all';

    /**
     * @param $file
     * @param $organs
     * @return mixed
     * @throws \Exception
     */
    public static function identify($file, $organs = 'all')
    {
        try {
            if (!app('plantrec_enable')) {
                throw new \Exception('Recognition feature is currently deactivated');
            }

            $ch = curl_init(self::API_ENDPOINT . '?api-key=' . app('plantrec_apikey'));

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);

            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: multipart/form-data'
            ]);

            curl_setopt($ch, CURLOPT_POSTFIELDS, [
                'images' => curl_file_create($file)
            ]);

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
