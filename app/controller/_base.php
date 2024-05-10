<?php 

/**
 * Base controller class
 * 
 * Extend or modify to fit your project needs
 */
class BaseController extends Asatru\Controller\Controller {
	/**
	 * @var string
	 */
	protected $layout = 'layout';

	/**
	 * Perform base initialization
	 * 
	 * @param $layout
	 * @return void
	 */
	public function __construct($layout = '')
	{
		if ($layout !== '') {
			$this->layout = $layout;
		}

		app_mail_config();
		app_set_timezone();

		$auth_user = UserModel::getAuthUser();
		if (!$auth_user) {
			$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

			$allowed_urls = array(
				'/auth', 
				'/login', 
				'/password/restore', 
				'/password/reset',
				'/cronjob/tasks/overdue',
				'/cronjob/tasks/tomorrow'
			);

			if (!in_array($url, $allowed_urls)) {
				header('Location: /auth');
				exit();
			}
		} else {
			UserModel::updateOnlineStatus();

			if ((is_string($auth_user->get('lang'))) && (strlen($auth_user->get('lang')) > 0)) {
				UtilsModule::setLanguage($auth_user->get('lang'));
			} else {
				$lang = app('language', env('APP_LANG', 'en'));
				if (($lang !== null) && (is_string($lang))) {
					UtilsModule::setLanguage($lang);
				}
			}

			$theme = $auth_user->get('theme');

			if (($theme) && (is_dir(public_path() . '/themes/' . $theme))) {
				ThemeModule::load(public_path() . '/themes/' . $theme);
			}
		}
	}

	/**
	 * A more convenient view helper
	 * 
	 * @param array $yields
	 * @param array $attr
	 * @return Asatru\View\ViewHandler
	 */
	public function view($yields, $attr = array())
	{
		return view($this->layout, $yields, $attr);
	}
}