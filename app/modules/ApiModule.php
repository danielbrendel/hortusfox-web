<?php

/**
 * This class represents your module
 */
class ApiModule {
    /**
     * @param $asset
     * @param $title
     * @param $type
     * @return mixed
     * @throws \Exception
     */
    public static function sharePhoto($asset, $title, $type)
    {
        try {
            $file = null;

            if ($type === 'preview') {
                $file = public_path() . '/img/' . str_replace('_thumb', '', PlantsModel::getDetails($asset)->get('photo'));
            } else if ($type === 'gallery') {
                $file = public_path() . '/img/' . PlantPhotoModel::getItem($asset)->get('original');
            }
            
            $ch = curl_init(env('APP_SERVICE_URL') . '/api/photo/share');

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, [
                'title' => $title,
                'hortusfox_photo' => curl_file_create($file)
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

    /**
     * @param $ident
     * @return mixed
     * @throws \Exception
     */
    public static function removePhoto($ident)
    {
        try {
            $ch = curl_init(env('APP_SERVICE_URL') . '/api/photo/remove');

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, [
                'ident' => $ident
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
