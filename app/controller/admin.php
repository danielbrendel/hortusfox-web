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
		$mail_encryption_types = AppModel::getMailEncryptionTypes();

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
			'mail_encryption_types' => $mail_encryption_types,
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
			$workspace = $request->params()->query('workspace', app('workspace'));
			$lang = $request->params()->query('lang', app('language'));
			$scroller = (bool)$request->params()->query('scroller', 0);
			$enablechat = (bool)$request->params()->query('enablechat', 0);
			$onlinetimelimit = (int)$request->params()->query('onlinetimelimit', app('chat_timelimit'));
			$chatonlineusers = (bool)$request->params()->query('chatonlineusers', 0);
			$chattypingindicator = (bool)$request->params()->query('chattypingindicator', 0);
			$enablehistory = (bool)$request->params()->query('enablehistory', 0);
			$history_name = $request->params()->query('history_name', app('history_name'));
			$enablephotoshare = (bool)$request->params()->query('enablephotoshare', 0);
			$cronpw = $request->params()->query('cronpw', app('cronjob_pw'));
			$enablepwa = (bool)$request->params()->query('enablepwa', 0);

			$set = [
				'workspace' => $workspace,
				'language' => $lang,
				'scroller' => $scroller,
				'chat_enable' => $enablechat,
				'chat_timelimit' => $onlinetimelimit,
				'chat_showusers' => $chatonlineusers,
				'chat_indicator' => $chattypingindicator,
				'history_enable' => $enablehistory,
				'history_name' => $history_name,
				'enable_media_share' => $enablephotoshare,
				'cronjob_pw' => $cronpw,
				'pwa_enable' => $enablepwa
			];

			AppModel::updateSet($set);

			if ($enablepwa) {
				AppModel::writeManifest($workspace);
			}
			
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
			$overlayalpha = $request->params()->query('overlayalpha', app('overlay_alpha'));
			
			AppModel::updateSingle('overlay_alpha', $overlayalpha);

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
			$smtp_fromname = $request->params()->query('smtp_fromname', app('smtp_fromname'));
			$smtp_fromaddress = $request->params()->query('smtp_fromaddress', app('smtp_fromaddress'));
			$smtp_host = $request->params()->query('smtp_host', app('smtp_host'));
			$smtp_port = $request->params()->query('smtp_port', app('smtp_port'));
			$smtp_username = $request->params()->query('smtp_username', app('smtp_username'));
			$smtp_password = $request->params()->query('smtp_password', app('smtp_password'));
			$smtp_encryption = $request->params()->query('smtp_encryption', app('smtp_encryption'));
			
			$set = [
				'smtp_fromname' => $smtp_fromname,
				'smtp_fromaddress' => $smtp_fromaddress,
				'smtp_host' => $smtp_host,
				'smtp_port' => $smtp_port,
				'smtp_username' => $smtp_username,
				'smtp_password' => $smtp_password,
				'smtp_encryption' => $smtp_encryption,
			];

			AppModel::updateSet($set);

			FlashMessage::setMsg('success', __('app.environment_settings_saved'));

			return redirect('/admin?tab=mail');
		} catch (\Exception $e) {
			FlashMessage::setMsg('error', $e->getMessage());
			return back();
		}
	}

	/**
	 * Handles URL: /admin/cronjob/token
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\RedirectHandler
	 */
	public function generate_cronjob_token($request)
	{
		try {
			$token = AppModel::generateCronjobToken();

			return json([
				'code' => 200,
				'token' => $token
			]);
		} catch (\Exception $e) {
			return json([
				'code' => 500,
				'msg' => $e->getMessage()
			]);
		}
	}
}
