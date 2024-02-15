<?php

/**
 * This class represents your controller
 */
class BackupController extends BaseController {
    const INDEX_LAYOUT = 'layout';

    /**
	 * Perform base initialization
	 * 
	 * @return void
	 */
	public function __construct()
	{
        parent::__construct(self::INDEX_LAYOUT);
	}

    /**
	 * Handles URL: /backup/start
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
    public function start($request)
    {
        try {
            $file_name = BackupModule::start([
                'plants' => true,
                'gallery' => true,
                'tasks' => true,
                'inventory' => true
            ]);

            return json([
                'code' => 200,
                'file' => asset('/backup/' . $file_name)
            ]);
        } catch (\Exception $e) {
            return json([
                'code' => 500,
                'msg' => $e->getMessage()
            ]);
        }
    }
}
