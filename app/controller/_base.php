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
				$lang = env('APP_LANG', 'en');
				if (($lang !== null) && (is_string($lang))) {
					UtilsModule::setLanguage($lang);
				}
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