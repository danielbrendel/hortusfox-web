<?php

/**
 * Class ApiController
 * 
 * Gateway to the workspace REST API
 */
class ApiController extends BaseController {
    public function __construct()
    {
        //parent::__construct();

        $token = null;
        if (isset($_GET['token'])) {
            $token = $_GET['token'];
        } else if ((isset($_POST)) && (isset($_POST['token']))) {
            $token = $_POST['token'];
        }

        ApiModel::validateKey($token);
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

            if (!$external) {
                PlantPhotoModel::uploadPhoto($plantId, $label);
            } else {
                $photo = $request->params()->query('photo', null);

                PlantPhotoModel::addPhotoURL($plantId, $label, $photo);
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
}
