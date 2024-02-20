<?php

/**
 * This class represents your controller
 */
class BackupController extends BaseController {
    /**
	 * Perform base initialization
	 * 
	 * @return void
	 */
	public function __construct()
	{
        parent::__construct();

        if (!UserModel::isCurrentlyAdmin()) {
            header('Location: /');
            exit();
        }
	}

    /**
	 * Handles URL: /backup/export
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
    public function export($request)
    {
        try {
            $file_name = BackupModule::start([
                'plants' => (bool)$request->params()->query('plants', 1),
                'gallery' => (bool)$request->params()->query('gallery', 1),
                'tasks' => (bool)$request->params()->query('tasks', 1),
                'inventory' => (bool)$request->params()->query('inventory', 1)
            ]);

            return json([
                'code' => 200,
                'file' => asset('backup/' . $file_name)
            ]);
        } catch (\Exception $e) {
            return json([
                'code' => 500,
                'msg' => $e->getMessage()
            ]);
        }
    }

    /**
	 * Handles URL: /backup/import
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
    public function import($request)
    {
        try {
            $file_name = ImportModule::start([
                'plants' => true,
                'gallery' => true,
                'tasks' => true,
                'inventory' => true
            ]);

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
