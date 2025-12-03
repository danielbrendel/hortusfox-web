<?php

use chillerlan\QRCode\{QRCode, QROptions};

/**
 * Class InventoryModel
 * 
 * Manages inventory data
 */ 
class InventoryModel extends \Asatru\Database\Model {
    public static $supported_exports = [
        'json' => [
            'label' => 'JSON',
            'method' => 'exportItemsAsJson'
        ],

        'csv' => [
            'label' => 'CSV',
            'method' => 'exportItemsAsCsv'
        ],

        'pdf' => [
            'label' => 'PDF',
            'method' => 'exportItemsAsPdf'
        ]
    ];

    /**
     * @param $name
     * @param $description
     * @param $tags
     * @param $location
     * @param $amount
     * @param $group
     * @param $photo
     * @param $api
     * @return int
     * @throws \Exception
     */
    public static function addItem($name, $description, $tags, $location, $amount, $group, $photo, $api = false)
    {
        try {
            $user = UserModel::getAuthUser();
            if ((!$user) && (!$api)) {
                throw new \Exception('Invalid user');
            }

            if (!InvGroupModel::isValidGroupToken($group)) {
                throw new \Exception('Invalid group token: ' . $group);
            }

            static::raw('INSERT INTO `@THIS` (name, group_ident, description, tags, location, amount, last_edited_user, last_edited_date) VALUES(?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)', [
                $name, $group, $description, trim($tags), $location, $amount, (($user) ? $user->get('id') : 0)
            ]);

            $row = static::raw('SELECT * FROM `@THIS` ORDER BY id DESC LIMIT 1')->first();

            if ((isset($_FILES['photo'])) && ($_FILES['photo']['error'] === UPLOAD_ERR_OK)) {
                $file_ext = UtilsModule::getImageExt($_FILES['photo']['tmp_name']);

                if ($file_ext === null) {
                    throw new \Exception('File is not a valid image');
                }

                $file_name = md5(random_bytes(55) . date('Y-m-d H:i:s'));

                move_uploaded_file($_FILES['photo']['tmp_name'], public_path('/img/' . $file_name . '.' . $file_ext));

                if (!UtilsModule::createThumbFile(public_path('/img/' . $file_name . '.' . $file_ext), UtilsModule::getImageType($file_ext, public_path('/img/' . $file_name)), public_path('/img/' . $file_name), $file_ext)) {
                    throw new \Exception('createThumbFile failed');
                }

                static::raw('UPDATE `@THIS` SET photo = ? WHERE id = ?', [
                    $file_name . '_thumb.' . $file_ext, $row->get('id')
                ]);
            } else {
                if ((is_string($photo)) && ((strpos($photo, 'http://') === 0) || (strpos($photo, 'https://') === 0))) {
                    static::raw('UPDATE `@THIS` SET photo = ? WHERE id = ?', [
                        $photo, $row->get('id')
                    ]);
                }
            }

            if (!$api) {
                LogModel::addLog($user->get('id'), 'inventory', 'add_inventory_item', $name, url('/inventory?expand=' . $row->get('id') . '#anchor-item-' . $row->get('id')));
                TextBlockModule::createdInventoryItem($name, url('/inventory?expand=' . $row->get('id') . '#anchor-item-' . $row->get('id')));
            }

            return $row->get('id');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @param $name
     * @param $description
     * @param $tags
     * @param $location
     * @param $amount
     * @param $group
     * @param $photo
     * @param $api
     * @return void
     * @throws \Exception
     */
    public static function editItem($id, $name, $description, $tags, $location, $amount, $group, $photo, $api = false)
    {
        try {
            $user = UserModel::getAuthUser();
            if ((!$user) && (!$api)) {
                throw new \Exception('Invalid user');
            }

            $row = static::raw('SELECT * FROM `@THIS` WHERE id = ?', [$id])->first();
            if (!$row) {
                throw new \Exception('Invalid item: ' . $id);
            }

            static::raw('UPDATE `@THIS` SET name = ?, group_ident = ?, location = ?, description = ?, tags = ?, amount = ? WHERE id = ?', [
                $name, $group, $location, $description, $tags, $amount, $row->get('id')
            ]);

            if ((isset($_FILES['photo'])) && ($_FILES['photo']['error'] === UPLOAD_ERR_OK)) {
                $file_ext = UtilsModule::getImageExt($_FILES['photo']['tmp_name']);

                if ($file_ext === null) {
                    throw new \Exception('File is not a valid image');
                }

                $file_name = md5(random_bytes(55) . date('Y-m-d H:i:s'));

                move_uploaded_file($_FILES['photo']['tmp_name'], public_path('/img/' . $file_name . '.' . $file_ext));

                if (!UtilsModule::createThumbFile(public_path('/img/' . $file_name . '.' . $file_ext), UtilsModule::getImageType($file_ext, public_path('/img/' . $file_name)), public_path('/img/' . $file_name), $file_ext)) {
                    throw new \Exception('createThumbFile failed');
                }

                if (is_string($row->get('photo'))) {
                    $oldThumbFile = public_path('/img/' . $row->get('photo'));

                    if (file_exists($oldThumbFile)) {
                        unlink($oldThumbFile);
                    }

                    $oldOrigFile = public_path('/img/' . str_replace('_thumb', '', $row->get('photo')));

                    if (file_exists($oldOrigFile)) {
                        unlink($oldOrigFile);
                    }
                }

                static::raw('UPDATE `@THIS` SET photo = ? WHERE id = ?', [
                    $file_name . '_thumb.' . $file_ext, $row->get('id')
                ]);
            } else {
                if ((is_string($photo)) && ((strpos($photo, 'http://') === 0) || (strpos($photo, 'https://') === 0))) {
                    static::raw('UPDATE `@THIS` SET photo = ? WHERE id = ?', [
                        $photo, $row->get('id')
                    ]);
                }
            }

            static::raw('UPDATE `@THIS` SET last_edited_user = ?, last_edited_date = CURRENT_TIMESTAMP WHERE id = ?', [
                (($user) ? $user->get('id') : 0), $row->get('id')
            ]);

            if (!$api) {
                LogModel::addLog($user->get('id'), 'inventory', 'edit_inventory_item', $name, url('/inventory?expand=' . $row->get('id') . '#anchor-item-' . $row->get('id')));
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @param $api
     * @return int
     * @throws \Exception
     */
    public static function incAmount($id, $api = false)
    {
        try {
            $user = UserModel::getAuthUser();
            if ((!$user) && (!$api)) {
                throw new \Exception('Invalid user');
            }

            $row = static::raw('SELECT * FROM `@THIS` WHERE id = ?', [$id])->first();
            if (!$row) {
                throw new \Exception('Invalid item: ' . $id);
            }

            $amount = $row->get('amount') + 1;
            
            static::raw('UPDATE `@THIS` SET amount = ?, last_edited_user = ?, last_edited_date = CURRENT_TIMESTAMP WHERE id = ?', [
                $amount, (($user) ? $user->get('id') : 0), $row->get('id')
            ]);

            if (!$api) {
                LogModel::addLog($user->get('id'), 'inventory', 'increment_inventory_item', $row->get('name'), url('/inventory?expand=' . $row->get('id') . '#anchor-item-' . $row->get('id')));
            }

            return $amount;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @param $api
     * @return int
     * @throws \Exception
     */
    public static function decAmount($id, $api = false)
    {
        try {
            $user = UserModel::getAuthUser();
            if ((!$user) && (!$api)) {
                throw new \Exception('Invalid user');
            }

            $row = static::raw('SELECT * FROM `@THIS` WHERE id = ?', [$id])->first();
            if (!$row) {
                throw new \Exception('Invalid item: ' . $id);
            }

            $amount = $row->get('amount') - 1;
            if ($amount < 0) {
                $amount = 0;
            }
            
            static::raw('UPDATE `@THIS` SET amount = ?, last_edited_user = ?, last_edited_date = CURRENT_TIMESTAMP WHERE id = ?', [
                $amount, (($user) ? $user->get('id') : 0), $row->get('id')
            ]);

            if (!$api) {
                LogModel::addLog($user->get('id'), 'inventory', 'decrement_inventory_item', $row->get('name'), url('/inventory?expand=' . $row->get('id') . '#anchor-item-' . $row->get('id')));
            }

            return $amount;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public static function getInventory()
    {
        try {
            return static::raw('SELECT * FROM `@THIS` ORDER BY group_ident, name ASC');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @param $api
     * @return void
     * @throws \Exception
     */
    public static function removeItem($id, $api = false)
    {
        try {
            $user = UserModel::getAuthUser();
            if ((!$user) && (!$api)) {
                throw new \Exception('Invalid user');
            }

            $row = static::raw('SELECT * FROM `@THIS` WHERE id = ?', [$id])->first();
            if (!$row) {
                throw new \Exception('Invalid item: ' . $id);
            }

            if (is_string($row->get('photo'))) {
                $oldThumbFile = public_path('/img/' . $row->get('photo'));

                if (file_exists($oldThumbFile)) {
                    unlink($oldThumbFile);
                }

                $oldOrigFile = public_path('/img/' . str_replace('_thumb', '', $row->get('photo')));

                if (file_exists($oldOrigFile)) {
                    unlink($oldOrigFile);
                }
            }

            static::raw('DELETE FROM `@THIS` WHERE id = ?', [$row->get('id')]);

            if (!$api) {
                LogModel::addLog($user->get('id'), 'inventory', 'remove_inventory_item', $row->get('name'), url('/inventory'));
                TextBlockModule::removedInventoryItem($row->get('name'));
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $group_ident
     * @return bool
     * @throws \Exception
     */
    public static function isGroupInUse($group_ident)
    {
        try {
            $row = static::raw('SELECT COUNT(*) AS `count` FROM `@THIS` WHERE group_ident = ?', [$group_ident])->first();
            if (!$row) {
                return false;
            }

            return $row->get('count') > 0;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $old_token
     * @param $new_token
     * @return void
     * @throws \Exception
     */
    public static function renameGroupToken($old_token, $new_token)
    {
        try {
            static::raw('UPDATE `@THIS` SET group_ident = ? WHERE group_ident = ?', [$new_token, $old_token]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public static function generateQRCode($id)
    {
        try {
            $item = static::raw('SELECT * FROM `@THIS` WHERE id = ?', [$id])->first();
            if (!$item) {
                throw new \Exception('Invalid item: ' . $id);
            }

            $options = new QROptions();
            $options->invertMatrix = true;

            $oqr = new QRCode($options);
			return $oqr->render(url('/inventory?expand=' . $item->get('id') . '#anchor-item-' . $item->get('id')));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $items
     * @return string
     * @throws \Exception
     */
    public static function exportItemsAsJson($items)
    {
        try {
            $pretty_items = [];

            foreach ($items as $item) {
                $pretty_items[$item[3]][] = [
                    'id' => $item[0],
                    'name' => $item[1],
                    'description' => $item[2],
                    'group' => $item[3],
                    'amount' => $item[4],
                    'location' => $item[5],
                    'photo' => $item[6],
                    'created' => $item[7],
                    'updated' => $item[8]
                ];
            }

            $data = [
                'meta' => [
                    'workspace' => app('workspace'),
                    'url' => url('/inventory'),
                    'exported' => date('Y-m-d H:i:s')
                ],

                'items' => $pretty_items
            ];

            $file_name = 'inventory_export_' . md5(random_bytes(55) . date('Y-m-d H:i:s')) . '.json';
            file_put_contents(public_path() . '/exports/' . $file_name, json_encode($data));

            return $file_name;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $items
     * @return string
     * @throws \Exception
     */
    public static function exportItemsAsCsv($items)
    {
        try {
            $data = "id,name,description,group,amount,location,photo,created,updated;" . PHP_EOL;

            foreach ($items as $item) {
                $data .= "\"" . $item[0] . "\",\"" . $item[1] . "\",\"" . $item[2] . "\",\"" . $item[3] . "\",\"" . $item[4] . "\",\"" . $item[5] . "\",\"" . $item[6] . "\",\"" . $item[7] . "\",\"" . $item[8] . "\"," . PHP_EOL;
            }

            $file_name = 'inventory_export_' . md5(random_bytes(55) . date('Y-m-d H:i:s')) . '.csv';
            file_put_contents(public_path() . '/exports/' . $file_name, $data);

            return $file_name;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $items
     * @return string
     * @throws \Exception
     */
    public static function exportItemsAsPdf($items)
    {
        try {
            $file_name = 'inventory_export_' . md5(random_bytes(55) . date('Y-m-d H:i:s')) . '.pdf';

            $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);

            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor(env('APP_NAME'));
            $pdf->SetTitle(__('app.inventory') . ' | ' . __('app.export'));

            $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
            $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
            $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
            $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);

            $pdf->AddPage();
            $pdf->SetFont('helvetica', '', 12);

            $html = '
                <table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse; width: 100%;">
                    <thead>
                        <tr style="background-color: rgb(150, 150, 150); font-weight: bold; text-align: left;">
                            <td>ID</td>
                            <td>' . __('app.name') . '</td>
                            <td>' . __('app.description') . '</td>
                            <td>' . __('app.group') . '</td>
                            <td>' . __('app.amount') . '</td>
                            <td>' . __('app.location') . '</td>
                            <td>' . __('app.photo') . '</td>
                            <td>' . __('app.created_at') . '</td>
                            <td>' . __('app.updated_at') . '</td>
                        </tr>
                    </thead>
                    <tbody>
            ';

            foreach ($items as $item) {
                $html .= '
                    <tr>
                        <td>#' . $item[0] . '</td>
                        <td>' . $item[1] . '</td>
                        <td>' . $item[2] . '</td>
                        <td>' . $item[3] . '</td>
                        <td>' . $item[4] . '</td>
                        <td>' . $item[5] . '</td>
                        <td>' . $item[6] . '</td>
                        <td>' . $item[7] . '</td>
                        <td>' . $item[8] . '</td>
                    </tr>
                ';
            }

            $html .= '
                </tbody>
                </table>
            ';

            $pdf->writeHTML($html, true, false, true, false, '');
            $pdf->Output(public_path() . '/exports/' . $file_name, 'F');

            return $file_name;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $items
     * @param $format
     * @return string
     * @throws \Exception
     */
    public static function exportItems($items, $format)
    {
        try {
            $file = '';

            $exports = static::exports();

            if (isset($exports[$format])) {
                $file = static::{$exports[$format]['method']}($items);
            } else {
                throw new \Exception('Unsupported format: ' . $format);
            }

            return $file;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @return array
     */
    public static function exports()
    {
        return static::$supported_exports;
    }
}