<?php

/**
 * Cronjobs controller
 */
class CronjobsController extends BaseController {
    /**
	 * Perform base initialization
	 * 
	 * @return void
	 */
    public function __construct()
    {
        if (env('APP_CRONPW') === null) {
            http_response_code(500);
            exit('Please set APP_CRONPW to a proper authentication token');
        }

        if ((!isset($_GET['cronpw'])) || ($_GET['cronpw'] !== env('APP_CRONPW'))) {
            http_response_code(403);
            exit();
        }
    }

    /**
	 * Handles URL: /overduetasks
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
    public function overdue_tasks($request)
    {
        try {
            TasksModel::cronjob();

            return json(['code' => 200]);
        } catch (\Exception $e) {
            return json([
                'code' => 500,
                'msg' => $e->getMessage()
            ]);
        }
    }
}
