<?php

/**
 * This class represents your controller
 */
class ApiController extends BaseController {
    public function __construct()
    {
        parent::__construct();

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
            $token = $request->params()->query('token', null);
            $plantId = $request->params()->query('plant', null);

            ApiModel::validateKey($token);

            $plant = PlantsModel::getDetails($plantId);

            return json([
                'code' => 200,
                'data' => $plant?->asArray()
            ]);
        } catch (\Exception $e) {
            return json([
                'code' => 500,
                'msg' => $e->getMessage()
            ]);
        }
    }
}
