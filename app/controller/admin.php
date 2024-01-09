<?php

/**
 * Admin controller
 */
class AdminController extends BaseController {
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
	 * Handles URL: /admin
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\ViewHandler
	 */
	public function index($request)
	{
		$user = UserModel::getAuthUser();
		$locs = LocationsModel::getAll(false);
        $user_accounts = UserModel::getAll();

		$new_version = null;
		$current_version = null;

		$check_version = $request->params()->query('cv', false);

		try {
			if ($check_version) {
				$new_version = VersionModule::getVersion();
				$current_version = safe_config('version', '1');
			}
		} catch (\Exception $e) {
			addLog(ASATRU_LOG_ERROR, $e->getMessage());
		}

		return parent::view(['content', 'admin'], [
			'user' => $user,
			'locations' => $locs,
			'user_accounts' => $user_accounts,
			'new_version' => $new_version,
			'current_version' => $current_version
		]);
	}

	/**
	 * Handles URL: /admin/environment/save
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\RedirectHandler
	 */
	public function save_environment($request)
	{
		try {
			$workspace = $request->params()->query('workspace', env('APP_WORKSPACE'));
			$lang = $request->params()->query('lang', env('APP_LANG'));
			$scroller = (bool)$request->params()->query('scroller', 0);
			$enablechat = (bool)$request->params()->query('enablechat', 0);
			$onlinetimelimit = (int)$request->params()->query('onlinetimelimit', env('APP_ONLINEMINUTELIMIT'));
			$chatonlineusers = (bool)$request->params()->query('chatonlineusers', 0);
			$chattypingindicator = (bool)$request->params()->query('chattypingindicator', 0);
			$enablehistory = (bool)$request->params()->query('enablehistory', 0);
			$history_name = $request->params()->query('history_name', env('APP_HISTORY_NAME'));
			$cronpw = $request->params()->query('cronpw', env('APP_CRONPW'));
			
			UtilsModule::saveEnvironment($workspace, $lang, $scroller, $enablechat, $onlinetimelimit, $chatonlineusers, $chattypingindicator, $enablehistory, $history_name, $cronpw);

			FlashMessage::setMsg('success', __('app.environment_settings_saved'));

			return redirect('/admin?tab=environment');
		} catch (\Exception $e) {
			FlashMessage::setMsg('error', $e->getMessage());
			return back();
		}
	}

	/**
	 * Handles URL: /admin/user/create
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\RedirectHandler
	 */
	public function create_user($request)
	{
		try {
			$name = $request->params()->query('name', null);
			$email = $request->params()->query('email', null);
			
			UserModel::createUser($name, $email);

			FlashMessage::setMsg('success', __('app.user_created_successfully'));

			return redirect('/admin?tab=users');
		} catch (\Exception $e) {
			FlashMessage::setMsg('error', $e->getMessage());
			return back();
		}
	}

	/**
	 * Handles URL: /admin/user/update
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\RedirectHandler
	 */
	public function update_user($request)
	{
		try {
			$id = $request->params()->query('id');
			$name = $request->params()->query('name', null);
			$email = $request->params()->query('email', null);
			$admin = $request->params()->query('admin', 0);
			
			UserModel::updateUser($id, $name, $email, (int)$admin);

			FlashMessage::setMsg('success', __('app.user_updated_successfully'));

			return redirect('/admin?tab=users');
		} catch (\Exception $e) {
			FlashMessage::setMsg('error', $e->getMessage());
			return back();
		}
	}

	/**
	 * Handles URL: /admin/user/remove
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\RedirectHandler
	 */
	public function remove_user($request)
	{
		try {
			$id = $request->params()->query('id');
			
			UserModel::removeUser($id);

			FlashMessage::setMsg('success', __('app.user_removed_successfully'));

			return redirect('/admin?tab=users');
		} catch (\Exception $e) {
			FlashMessage::setMsg('error', $e->getMessage());
			return back();
		}
	}

	/**
	 * Handles URL: /admin/location/add
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\RedirectHandler
	 */
	public function add_location($request)
	{
		try {
			$name = $request->params()->query('name', null);
			$icon = $request->params()->query('icon', null);
			
			LocationsModel::addLocation($name, $icon);

			FlashMessage::setMsg('success', __('app.location_added_successfully'));

			return redirect('/admin?tab=locations');
		} catch (\Exception $e) {
			FlashMessage::setMsg('error', $e->getMessage());
			return back();
		}
	}

	/**
	 * Handles URL: /admin/location/update
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\RedirectHandler
	 */
	public function update_location($request)
	{
		try {
			$id = $request->params()->query('id');
			$name = $request->params()->query('name', null);
			$icon = $request->params()->query('icon', null);
			$active = $request->params()->query('active', 0);
			
			LocationsModel::editLocation($id, $name, $icon, (int)$active);

			FlashMessage::setMsg('success', __('app.location_updated_successfully'));

			return redirect('/admin?tab=locations');
		} catch (\Exception $e) {
			FlashMessage::setMsg('error', $e->getMessage());
			return back();
		}
	}

	/**
	 * Handles URL: /admin/location/remove
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\RedirectHandler
	 */
	public function remove_location($request)
	{
		try {
			$id = $request->params()->query('id');
			$target = $request->params()->query('target');
			
			LocationsModel::removeLocation($id, $target);

			FlashMessage::setMsg('success', __('app.location_removed_successfully'));

			return redirect('/admin?tab=locations');
		} catch (\Exception $e) {
			FlashMessage::setMsg('error', $e->getMessage());
			return back();
		}
	}

	/**
	 * Handles URL: /admin/media/logo
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\RedirectHandler
	 */
	public function upload_media_logo($request)
	{
		try {
			if ((!isset($_FILES['asset'])) || ($_FILES['asset']['error'] !== UPLOAD_ERR_OK) || ($_FILES['asset']['type'] !== 'image/png')) {
				throw new \Exception('Failed to upload file or invalid file uploaded');
			}

			move_uploaded_file($_FILES['asset']['tmp_name'], public_path() . '/logo.png');

			FlashMessage::setMsg('success', __('app.media_saved'));

			return redirect('/admin?tab=media');
		} catch (\Exception $e) {
			FlashMessage::setMsg('error', $e->getMessage());
			return back();
		}
	}

	/**
	 * Handles URL: /admin/media/background
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\RedirectHandler
	 */
	public function upload_media_background($request)
	{
		try {
			if ((!isset($_FILES['asset'])) || ($_FILES['asset']['error'] !== UPLOAD_ERR_OK) || ($_FILES['asset']['type'] !== 'image/jpeg')) {
				throw new \Exception('Failed to upload file or invalid file uploaded');
			}

			move_uploaded_file($_FILES['asset']['tmp_name'], public_path() . '/img/background.jpg');

			FlashMessage::setMsg('success', __('app.media_saved'));

			return redirect('/admin?tab=media');
		} catch (\Exception $e) {
			FlashMessage::setMsg('error', $e->getMessage());
			return back();
		}
	}

	/**
	 * Handles URL: /admin/media/overlay/alpha
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\RedirectHandler
	 */
	public function save_overlay_alpha($request)
	{
		try {
			$overlayalpha = $request->params()->query('overlayalpha', env('APP_OVERLAYALPHA'));
			
			UtilsModule::saveOverlayAlphaValue($overlayalpha);

			FlashMessage::setMsg('success', __('app.environment_settings_saved'));

			return redirect('/admin?tab=media');
		} catch (\Exception $e) {
			FlashMessage::setMsg('error', $e->getMessage());
			return back();
		}
	}

	/**
	 * Handles URL: /admin/mail/save
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\RedirectHandler
	 */
	public function save_mail_settings($request)
	{
		try {
			$smtp_fromname = $request->params()->query('smtp_fromname', env('SMTP_FROMNAME'));
			$smtp_fromaddress = $request->params()->query('smtp_fromaddress', env('SMTP_FROMADDRESS'));
			$smtp_host = $request->params()->query('smtp_host', env('SMTP_HOST'));
			$smtp_port = $request->params()->query('smtp_port', env('SMTP_PORT'));
			$smtp_username = $request->params()->query('smtp_username', env('SMTP_USERNAME'));
			$smtp_password = $request->params()->query('smtp_password', env('SMTP_PASSWORD'));
			$smtp_encryption = $request->params()->query('smtp_encryption', env('SMTP_ENCRYPTION'));
			
			UtilsModule::saveMailSettings($smtp_fromname, $smtp_fromaddress, $smtp_host, $smtp_port, $smtp_username, $smtp_password, $smtp_encryption);

			FlashMessage::setMsg('success', __('app.environment_settings_saved'));

			return redirect('/admin?tab=mail');
		} catch (\Exception $e) {
			FlashMessage::setMsg('error', $e->getMessage());
			return back();
		}
	}
}
