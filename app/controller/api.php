<?php

/**
 * This class represents your controller
 */
class ApiController extends BaseController {
    /**
	 * Perform base initialization
	 * 
	 * @return void
	 */
	public function __construct()
	{
        if (!env('APP_ENABLE_PHOTO_SHARE', false)) {
            http_response_code(403);
            header('Content-Type: application/json');
            exit(json_encode(array('code' => 403, 'msg' => 'Photo sharing is currently deactivated')));
        }
	}

    /**
	 * Handles URL: /api/photo/share
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
    public function share_photo($request)
    {
        try {
            $asset = $request->params()->query('asset', null);
            $title = $request->params()->query('title', null);
            $type = $request->params()->query('type', null);
            
            $result = ApiModule::sharePhoto($asset, $title, $type);

            return json([
                'code' => 200,
                'data' => $result->data
            ]);
        } catch (\Exception $e) {
            return json([
                'code' => 500,
                'msg' => $e->getMessage()
            ]);
        }
    }
}
