<?php

/**
 * This class represents your controller
 */
class ShareController extends BaseController {
    /**
	 * Perform base initialization
	 * 
	 * @return void
	 */
	public function __construct()
	{
        if (!app('enable_media_share', false)) {
            http_response_code(403);
            header('Content-Type: application/json');
            exit(json_encode(array('code' => 403, 'msg' => 'Photo sharing is currently deactivated')));
        }

        parent::__construct();
	}

    /**
	 * Handles URL: /share/photo/post
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
            
            if ($result->code != 200) {
                throw new \Exception($result->msg);
            }

            $user = UserModel::getAuthUser();
            if (!$user) {
                throw new \Exception('Invalid user');
            }

            $mailobj = new Asatru\SMTPMailer\SMTPMailer();
            $mailobj->setRecipient($user->get('email'));
            $mailobj->setSubject(__('app.mail_share_photo_title'));
            $mailobj->setView('mail/share_photo', [], ['url_photo' => $result->data->url, 'url_removal' => env('APP_SERVICE_URL') . '/api/photo/remove?ident=' . $result->data->ident . '&ret=home']);
            $mailobj->setProperties(mail_properties());
            $mailobj->send();

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

    /**
	 * Handles URL: /share/photo/remove
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
    public function remove_photo($request)
    {
        try {
            $ident = $request->params()->query('ident', null);

            $result = ApiModule::removePhoto($ident);
            
            if ($result->code != 200) {
                throw new \Exception($result->msg);
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
}
