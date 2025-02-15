<?php

/**
 * Class ApiController
 * 
 * Gateway to the workspace REST API
 */
class ApiController extends BaseController {
    public function __construct()
    {
        $token = null;
        
        if (isset($_GET['token'])) {
            $token = $_GET['token'];
        } else if ((isset($_POST)) && (isset($_POST['token']))) {
            $token = $_POST['token'];
        }

        try {
            ApiModel::validateKey($token);
        } catch (\Exception $e) {
            http_response_code(403);
            header('Content-Type: application/json');
            exit(json([
                'code' => 403,
                'invalid_token' => $token
            ])->out(true));
        }
    }

    /**
	 * Handles URL: /api/plants/get
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
    public function get_plant($request)
    {
        try {
            $plantId = $request->params()->query('plant', null);

            $plant = PlantsModel::getDetails($plantId);
            $cust_attr = CustPlantAttrModel::getForPlant($plantId);

            $data = [
                'default' => $plant?->asArray(),
                'custom' => $cust_attr
            ];

            return json([
                'code' => 200,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return json([
                'code' => 500,
                'msg' => $e->getMessage()
            ]);
        }
    }

    /**
	 * Handles URL: /api/plants/add
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
    public function add_plant($request)
    {
        try {
            $name = $request->params()->query('name', null);
            $location = $request->params()->query('location', null);
            
            $plant_id = PlantsModel::addPlant($name, $location, true);

            return json([
                'code' => 200,
                'plant' => $plant_id
            ]);
        } catch (\Exception $e) {
            return json([
                'code' => 500,
                'msg' => $e->getMessage()
            ]);
        }
    }

    /**
	 * Handles URL: /api/plants/update
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
    public function update_plant($request)
    {
        try {
            $plantId = $request->params()->query('plant', null);
            $attribute = $request->params()->query('attribute', null);
            $value = $request->params()->query('value', null);

            PlantsModel::editPlantAttribute((int)$plantId, $attribute, $value, true);

            return json([
                'code' => 200,
                'attribute' => $attribute,
                'value' => $value
            ]);
        } catch (\Exception $e) {
            return json([
                'code' => 500,
                'msg' => $e->getMessage()
            ]);
        }
    }

    /**
	 * Handles URL: /api/plants/remove
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
    public function remove_plant($request)
    {
        try {
            $plantId = $request->params()->query('plant', null);

            PlantsModel::removePlant($plantId);

            return json([
                'code' => 200,
                'plant' => $plantId
            ]);
        } catch (\Exception $e) {
            return json([
                'code' => 500,
                'msg' => $e->getMessage()
            ]);
        }
    }

    /**
	 * Handles URL: /api/plants/list
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
    public function get_plant_list($request)
    {
        try {
            $location = $request->params()->query('location', null);
            $limit = $request->params()->query('limit', null);
            $from = $request->params()->query('from', null);
            $sort = $request->params()->query('sort', null);

            $list = PlantsModel::getPlantList($location, $limit, $from, $sort);

            return json([
                'code' => 200,
                'list' => $list?->asArray()
            ]);
        } catch (\Exception $e) {
            return json([
                'code' => 500,
                'msg' => $e->getMessage()
            ]);
        }
    }

    /**
	 * Handles URL: /api/plants/search
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
    public function search_plants($request)
    {
        try {
            $expression = $request->params()->query('expression', null);
            $limit = $request->params()->query('limit', null);

            $list = PlantsModel::performSearch($expression, true, true, true, true);

            return json([
                'code' => 200,
                'list' => $list?->asArray()
            ]);
        } catch (\Exception $e) {
            return json([
                'code' => 500,
                'msg' => $e->getMessage()
            ]);
        }
    }

    /**
	 * Handles URL: /api/plants/attributes/add
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
    public static function add_attribute($request)
    {
        try {
            $plant = $request->params()->query('plant', null);
            $label = $request->params()->query('label', null);
            $datatype = $request->params()->query('datatype', null);
            $content = $request->params()->query('content', null);
            
            CustPlantAttrModel::addAttribute($plant, $label, $datatype, $content, true);

            return json([
                'code' => 200
            ]);
        } catch (\Exception $e) {
            return json([
                'code' => 500,
                'msg' => $e->getMessage()
            ]);
        }
    }

    /**
	 * Handles URL: /api/plants/attributes/edit
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
    public static function edit_attribute($request)
    {
        try {
            $plant = $request->params()->query('plant', null);
            $label = $request->params()->query('label', null);
            $datatype = $request->params()->query('datatype', null);
            $content = $request->params()->query('content', null);
            
            CustPlantAttrModel::editAttribute(CustPlantAttrModel::getAttrIdOfPlant($plant, $label), null, $label, $datatype, $content, true);

            return json([
                'code' => 200
            ]);
        } catch (\Exception $e) {
            return json([
                'code' => 500,
                'msg' => $e->getMessage()
            ]);
        }
    }

    /**
	 * Handles URL: /api/plants/attributes/remove
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
    public static function remove_attribute($request)
    {
        try {
            $plant = $request->params()->query('plant', null);
            $label = $request->params()->query('label', null);
            
            CustPlantAttrModel::removeAttribute(CustPlantAttrModel::getAttrIdOfPlant($plant, $label), true);

            return json([
                'code' => 200
            ]);
        } catch (\Exception $e) {
            return json([
                'code' => 500,
                'msg' => $e->getMessage()
            ]);
        }
    }

    /**
	 * Handles URL: /api/plants/photo/update
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
    public static function update_plant_photo($request)
    {
        try {
            $plantId = $request->params()->query('plant', null);
            $external = (bool)$request->params()->query('external', false);

            if (!$external) {
                PlantsModel::editPlantPhoto($plantId, 'photo', 'photo');
            } else {
                $photo = $request->params()->query('photo', null);

                PlantsModel::editPlantPhotoURL($plantId, 'photo', $photo);
            }

            return json([
                'code' => 200
            ]);
        } catch (\Exception $e) {
            return json([
                'code' => 500,
                'msg' => $e->getMessage()
            ]);
        }
    }

    /**
	 * Handles URL: /api/plants/gallery/add
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
    public static function add_plant_gallery_photo($request)
    {
        try {
            $plantId = $request->params()->query('plant', null);
            $label = $request->params()->query('label', null);
            $external = (bool)$request->params()->query('external', false);

            $result = 0;

            if (!$external) {
                $result = PlantPhotoModel::uploadPhoto($plantId, $label, true);
            } else {
                $photo = $request->params()->query('photo', null);

                $result = PlantPhotoModel::addPhotoURL($plantId, $label, $photo, true);
            }

            return json([
                'code' => 200,
                'item' => $result
            ]);
        } catch (\Exception $e) {
            return json([
                'code' => 500,
                'msg' => $e->getMessage()
            ]);
        }
    }

    /**
	 * Handles URL: /api/plants/gallery/edit
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
    public static function edit_plant_gallery_photo($request)
    {
        try {
            $plantId = $request->params()->query('plant', null);
            $item = $request->params()->query('item', null);
            $label = $request->params()->query('label', null);

            PlantPhotoModel::editLabel($item, $label, true);

            return json([
                'code' => 200
            ]);
        } catch (\Exception $e) {
            return json([
                'code' => 500,
                'msg' => $e->getMessage()
            ]);
        }
    }

    /**
	 * Handles URL: /api/plants/gallery/remove
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
    public static function remove_plant_gallery_photo($request)
    {
        try {
            $item = $request->params()->query('item', null);

            PlantPhotoModel::removePhoto($item, true);

            return json([
                'code' => 200
            ]);
        } catch (\Exception $e) {
            return json([
                'code' => 500,
                'msg' => $e->getMessage()
            ]);
        }
    }

    /**
	 * Handles URL: /api/plants/log/add
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
    public static function add_plant_log_entry($request)
    {
        try {
            $plantId = $request->params()->query('plant', null);
            $content = $request->params()->query('content', null);
            
            $logid = PlantLogModel::addEntry($plantId, $content);

            return json([
                'code' => 200,
                'logid' => $logid
            ]);
        } catch (\Exception $e) {
            return json([
                'code' => 500,
                'msg' => $e->getMessage()
            ]);
        }
    }

    /**
	 * Handles URL: /api/plants/log/edit
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
    public static function edit_plant_log_entry($request)
    {
        try {
            $logid = $request->params()->query('logid', null);
            $content = $request->params()->query('content', null);
            
            PlantLogModel::editEntry($logid, $content);

            return json([
                'code' => 200
            ]);
        } catch (\Exception $e) {
            return json([
                'code' => 500,
                'msg' => $e->getMessage()
            ]);
        }
    }

    /**
	 * Handles URL: /api/plants/log/remove
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
    public static function remove_plant_log_entry($request)
    {
        try {
            $logid = $request->params()->query('logid', null);
            
            PlantLogModel::removeEntry($logid);

            return json([
                'code' => 200
            ]);
        } catch (\Exception $e) {
            return json([
                'code' => 500,
                'msg' => $e->getMessage()
            ]);
        }
    }

    /**
	 * Handles URL: /api/plants/log/fetch
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
    public static function fetch_plant_log_entries($request)
    {
        try {
            $plantId = $request->params()->query('plant', null);
            $paginate = $request->params()->query('paginate', null);
            $limit = $request->params()->query('limit', 10);
			
			$data = PlantLogModel::getLogEntries($plantId, $paginate, $limit);

            return json([
                'code' => 200,
                'log' => $data?->asArray()
            ]);
        } catch (\Exception $e) {
            return json([
                'code' => 500,
                'msg' => $e->getMessage()
            ]);
        }
    }

    /**
	 * Handles URL: /api/locations/list
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
    public function fetch_location_list($request)
    {
        try {
            $only_active = $request->params()->query('only_active', false);

			$data = LocationsModel::getAll($only_active);

            return json([
                'code' => 200,
                'list' => $data?->asArray()
            ]);
        } catch (\Exception $e) {
            return json([
                'code' => 500,
                'msg' => $e->getMessage()
            ]);
        }
    }

    /**
	 * Handles URL: /api/locations/info
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
    public function fetch_location_info($request)
    {
        try {
            $location = $request->params()->query('location', null);
            $include_plants = $request->params()->query('include_plants', false);

			$data = LocationsModel::getLocationById($location)?->asArray();

            if (($include_plants) && ($data)) {
                $data['plants'] = [];

                $plants = PlantsModel::getAll($location);
                if ($plants) {
                    $data['plants'] = $plants->asArray();
                }
            }

            return json([
                'code' => 200,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return json([
                'code' => 500,
                'msg' => $e->getMessage()
            ]);
        }
    }

    /**
	 * Handles URL: /api/tasks/fetch
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
    public static function fetch_tasks_list($request)
    {
        try {
            $done = (bool)$request->params()->query('done', false);
            $limit = $request->params()->query('limit', 100);
			
			$data = TasksModel::getTasks($done, $limit);

            return json([
                'code' => 200,
                'data' => $data?->asArray()
            ]);
        } catch (\Exception $e) {
            return json([
                'code' => 500,
                'msg' => $e->getMessage()
            ]);
        }
    }

    /**
	 * Handles URL: /api/tasks/add
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
    public static function add_task($request)
    {
        try {
            $title = $request->params()->query('title', null);
            $description = $request->params()->query('description', null);
            $due_date = $request->params()->query('due_date', null);
			
			$itemid = TasksModel::addTask($title, $description, $due_date, true);

            return json([
                'code' => 200,
                'item' => $itemid
            ]);
        } catch (\Exception $e) {
            return json([
                'code' => 500,
                'msg' => $e->getMessage()
            ]);
        }
    }

    /**
	 * Handles URL: /api/tasks/edit
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
    public static function edit_task($request)
    {
        try {
            $taskid = $request->params()->query('task', null);
            $title = $request->params()->query('title', null);
            $description = $request->params()->query('description', null);
            $due_date = $request->params()->query('due_date', null);
            $done = $request->params()->query('done', null);
			
			TasksModel::editTask($taskid, $title, $description, $due_date, $done, true);

            return json([
                'code' => 200
            ]);
        } catch (\Exception $e) {
            return json([
                'code' => 500,
                'msg' => $e->getMessage()
            ]);
        }
    }

    /**
	 * Handles URL: /api/tasks/remove
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
    public static function remove_task($request)
    {
        try {
            $taskid = $request->params()->query('task', null);
			
			TasksModel::removeTask($taskid);

            return json([
                'code' => 200
            ]);
        } catch (\Exception $e) {
            return json([
                'code' => 500,
                'msg' => $e->getMessage()
            ]);
        }
    }

    /**
	 * Handles URL: /api/inventory/fetch
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
    public static function fetch_inventory($request)
    {
        try {
			$data = InventoryModel::getInventory();

            return json([
                'code' => 200,
                'data' => $data?->asArray()
            ]);
        } catch (\Exception $e) {
            return json([
                'code' => 500,
                'msg' => $e->getMessage()
            ]);
        }
    }

    /**
	 * Handles URL: /api/inventory/add
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
    public static function add_inventory_item($request)
    {
        try {
            $name = $request->params()->query('name', null);
            $description = $request->params()->query('description', null);
            $tags = $request->params()->query('tags', null);
            $location = $request->params()->query('location', null);
            $amount = $request->params()->query('amount', null);
            $group = $request->params()->query('group', null);
            $photo = $request->params()->query('photo', null);
			
			$itemid = InventoryModel::addItem($name, $description, $tags, $location, $amount, $group, $photo, true);

            return json([
                'code' => 200,
                'item' => $itemid
            ]);
        } catch (\Exception $e) {
            return json([
                'code' => 500,
                'msg' => $e->getMessage()
            ]);
        }
    }

    /**
	 * Handles URL: /api/inventory/edit
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
    public static function edit_inventory_item($request)
    {
        try {
            $item = $request->params()->query('item', null);
            $name = $request->params()->query('name', null);
            $description = $request->params()->query('description', null);
            $tags = $request->params()->query('tags', null);
            $location = $request->params()->query('location', null);
            $amount = $request->params()->query('amount', null);
            $group = $request->params()->query('group', null);
            $photo = $request->params()->query('photo', null);
			
			InventoryModel::editItem($item, $name, $description, $tags, $location, $amount, $group, $photo, true);

            return json([
                'code' => 200
            ]);
        } catch (\Exception $e) {
            return json([
                'code' => 500,
                'msg' => $e->getMessage()
            ]);
        }
    }

    /**
	 * Handles URL: /api/inventory/amount/inc
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
    public static function inc_inventory_item($request)
    {
        try {
            $item = $request->params()->query('item', null);
			
			$amount = InventoryModel::incAmount($item, true);

            return json([
                'code' => 200,
                'amount' => $amount
            ]);
        } catch (\Exception $e) {
            return json([
                'code' => 500,
                'msg' => $e->getMessage()
            ]);
        }
    }

    /**
	 * Handles URL: /api/inventory/amount/dec
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
    public static function dec_inventory_item($request)
    {
        try {
            $item = $request->params()->query('item', null);
			
			$amount = InventoryModel::decAmount($item, true);

            return json([
                'code' => 200,
                'amount' => $amount
            ]);
        } catch (\Exception $e) {
            return json([
                'code' => 500,
                'msg' => $e->getMessage()
            ]);
        }
    }

    /**
	 * Handles URL: /api/inventory/remove
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
    public static function remove_inventory_item($request)
    {
        try {
            $item = $request->params()->query('item', null);
			
			InventoryModel::removeItem($item, true);

            return json([
                'code' => 200
            ]);
        } catch (\Exception $e) {
            return json([
                'code' => 500,
                'msg' => $e->getMessage()
            ]);
        }
    }

    /**
	 * Handles URL: /api/calendar/fetch
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
    public static function fetch_calendar_entries($request)
    {
        try {
			$date_from = $request->params()->query('date_from', null);
            $date_till = $request->params()->query('date_till', null);

            if ($date_from === null) {
                $date_from = date('Y-m-d');
            }

            if ($date_till === null) {
                $date_till = date('Y-m-d', strtotime('+30 days'));
            }

            $items = CalendarModel::getItems($date_from, $date_till)->asArray();

            foreach ($items as $key => &$value) {
                if (isset($value['class_name'])) {
                    $value['class_descriptor'] = $value['class_name'];

                    $calendar_class_item = CalendarClassModel::findClass($value['class_name']);
                    if ($calendar_class_item) {
                        $value['class_name'] = __($calendar_class_item->get('name'));
                    } else {
                        $value['class_name'] = __('app.unknown_calendar_class');
                    }
                }
            }

            return json([
                'code' => 200,
                'data' => $items,
                'date_from' => $date_from,
                'date_till' => $date_till
            ]);
        } catch (\Exception $e) {
            return json([
                'code' => 500,
                'msg' => $e->getMessage()
            ]);
        }
    }

    /**
	 * Handles URL: /api/calendar/add
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
    public static function add_calendar_entry($request)
    {
        try {
            $name = $request->params()->query('name', null);
            $date_from = $request->params()->query('date_from', null);
            $date_till = $request->params()->query('date_till', null);
            $class = $request->params()->query('class', null);

            if ($date_till === null) {
                $date_till = date('Y-m-d', strtotime('+1 day', strtotime($date_from)));
            }

            if ($date_from === $date_till) {
                $date_till = date('Y-m-d', strtotime('+1 day', strtotime($date_from)));
            }

            $item = CalendarModel::addItem($name, $date_from, $date_till, $class, true);

            return json([
                'code' => 200,
                'item' => $item
            ]);
        } catch (\Exception $e) {
            return json([
                'code' => 500,
                'msg' => $e->getMessage()
            ]);
        }
    }

    /**
	 * Handles URL: /api/calendar/edit
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
    public static function edit_calendar_entry($request)
    {
        try {
            $ident = $request->params()->query('ident', null);
            $name = $request->params()->query('name', null);
            $date_from = $request->params()->query('date_from', null);
            $date_till = $request->params()->query('date_till', null);
            $class = $request->params()->query('class', null);

            if ($date_till === null) {
                $date_till = date('Y-m-d', strtotime('+1 day', strtotime($date_from)));
            }

            if ($date_from === $date_till) {
                $date_till = date('Y-m-d', strtotime('+1 day', strtotime($date_from)));
            }

            CalendarModel::editItem($ident, $name, $date_from, $date_till, $class, true);

            return json([
                'code' => 200
            ]);
        } catch (\Exception $e) {
            return json([
                'code' => 500,
                'msg' => $e->getMessage()
            ]);
        }
    }

    /**
	 * Handles URL: /api/calendar/remove
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
    public static function remove_calendar_entry($request)
    {
        try {
            $ident = $request->params()->query('ident', null);

            CalendarModel::removeItem($ident);

            return json([
                'code' => 200
            ]);
        } catch (\Exception $e) {
            return json([
                'code' => 500,
                'msg' => $e->getMessage()
            ]);
        }
    }

    /**
	 * Handles URL: /api/chat/fetch
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
    public static function fetch_chat_messages($request)
    {
        try {
            $limit = $request->params()->query('limit', 50);
			
			$data = ChatMsgModel::getChat($limit, true);

            return json([
                'code' => 200,
                'data' => $data?->asArray()
            ]);
        } catch (\Exception $e) {
            return json([
                'code' => 500,
                'msg' => $e->getMessage()
            ]);
        }
    }

    /**
	 * Handles URL: /api/chat/message/add
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
    public static function add_chat_message($request)
    {
        try {
            $message = $request->params()->query('message', null);
			
			TextBlockModule::addToChat($message, 'x1f916', true);

            return json([
                'code' => 200
            ]);
        } catch (\Exception $e) {
            return json([
                'code' => 500,
                'msg' => $e->getMessage()
            ]);
        }
    }
}
