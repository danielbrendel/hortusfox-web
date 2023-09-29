<?php

    /*
        Asatru PHP - Model for plants
    */

    /**
     * This class extends the base model class and represents your associated table
     */ 
    class PlantsModel extends \Asatru\Database\Model {
        /**
         * @param $location
         * @return mixed
         * @throws \Exception
         */
        public static function getAll($location)
        {
            try {
                return static::raw('SELECT * FROM `' . self::tableName() . '` WHERE location = ? ORDER BY name ASC', [$location]);
            } catch (\Exception $e) {
                throw $e;
            }
        }

        /**
         * @param $id
         * @return mixed
         * @throws \Exception
         */
        public static function getDetails($id)
        {
            try {
                return static::raw('SELECT * FROM `' . self::tableName() . '` WHERE id = ?', [$id])->first();
            } catch (\Exception $e) {
                throw $e;
            }
        }

        /**
         * @return int
         * @throws \Exception
         */
        public static function addPlant($name, $location, $perennial, $cutting_month, $date_of_purchase, $humidity, $light_level)
        {
            try {
                if ((!isset($_FILES['photo'])) || ($_FILES['photo']['error'] !== UPLOAD_ERR_OK)) {
                    throw new \Exception('Errorneous file');
                }

                $file_ext = static::getImageType($_FILES['photo']['tmp_name']);

                if ($file_ext === null) {
                    throw new \Exception('File is not a  valid image');
                }

                $file_name = md5(random_bytes(55) . date('Y-m-d H:i:s')) . '.' . $file_ext;

                move_uploaded_file($_FILES['photo']['tmp_name'], public_path('/img/' . $file_name));

                static::raw('INSERT INTO `' . self::tableName() . '` (name, location, photo, perennial, cutting_month, date_of_purchase, humidity, light_level) VALUES(?, ?, ?, ?, ?, ?, ?, ?)', [
                    $name, $location, $file_name, $perennial, $cutting_month, $date_of_purchase, $humidity, $light_level
                ]);

                $query = static::raw('SELECT * FROM `' . self::tableName() . '` ORDER BY id DESC LIMIT 1')->first();

                return $query->get('id');
            } catch (\Exception $e) {
                throw $e;
            }
        }

        /**
         * @param $ext
         * @param $file
         * @return mixed|null
         */
        private static function getImageType($file)
        {
            $imagetypes = [
                'png' => IMAGETYPE_PNG,
                'jpg' => IMAGETYPE_JPEG,
                'gif' => IMAGETYPE_GIF
            ];

            foreach ($imagetypes as $ext => $type) {
                if (exif_imagetype($file) === $type) {
                    return $ext;
                }
            }

            return null;
        }

        /**
         * Return the associated table name of the migration
         * 
         * @return string
         */
        public static function tableName()
        {
            return 'plants';
        }
    }