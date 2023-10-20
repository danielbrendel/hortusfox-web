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
			http_response_code(403);
			exit('403 - Access Forbidden.');
		}

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