<?php

/**
 * User controller
 */
class UserController extends BaseController {
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
	 * Handles URL: /profile
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\ViewHandler
	 */
	public function view_profile($request)
	{
		$user = UserModel::getAuthUser();
		$plants = PlantsModel::getAuthoredPlants($user->get('id'));
		$log = LogModel::getHistory($user->get('id'));
		
		return parent::view(['content', 'profile'], [
			'user' => $user,
			'plants' => $plants,
			'log' => $log
		]);
	}

	/**
	 * Handles URL: /profile/preferences
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\RedirectHandler
	 */
	public function edit_preferences($request)
	{
		try {
			$validator = new Asatru\Controller\PostValidator([
				'name' => 'required|min:1',
				'email' => 'required|email',
				'lang' => 'required',
				'chatcolor' => 'required'
			]);

			if (!$validator->isValid()) {
				$errorstr = '';
				foreach ($validator->errorMsgs() as $err) {
					$errorstr .= $err . '<br/>';
				}

				FlashMessage::setMsg('error', 'Invalid data given:<br/>' . $errorstr);
				
				return back();
			}

			$name = $request->params()->query('name', null);
			$email = $request->params()->query('email', null);
			$lang = $request->params()->query('lang', 'en');
			$theme = $request->params()->query('theme', 'default');
			$chatcolor = $request->params()->query('chatcolor', null);
			$show_log = $request->params()->query('show_log', false);
			$show_calendar_view = $request->params()->query('show_calendar_view', false);
			$show_plant_id = $request->params()->query('show_plant_id', false);
			$notify_tasks_overdue = $request->params()->query('notify_tasks_overdue', false);
			$notify_tasks_tomorrow = $request->params()->query('notify_tasks_tomorrow', false);
			$notify_calendar_reminder = $request->params()->query('notify_calendar_reminder', false);
			$show_plants_aoru = $request->params()->query('show_plants_aoru', 'added');

			UserModel::editPreferences($name, $email, $lang, $theme, $chatcolor, $show_log, $show_calendar_view, $show_plant_id, $notify_tasks_overdue, $notify_tasks_tomorrow, $notify_calendar_reminder, $show_plants_aoru);

			$password = $request->params()->query('password', null);
			if ($password) {
				$password_confirmation = $request->params()->query('password_confirmation', null);
				if ($password !== $password_confirmation) {
					throw new \Exception(__('app.password_mismatch'));
				}

				UserModel::updatePassword($password);
			}

			FlashMessage::setMsg('success', __('app.preferences_saved_successfully'));
		} catch (\Exception $e) {
			FlashMessage::setMsg('error', $e->getMessage());
		}

		return redirect('/profile');
	}

	/**
	 * Handles URL: /profile/notes/save
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\RedirectHandler
	 */
	public function save_notes($request)
	{
		try {
			$notes = $request->params()->query('notes', null);

			UserModel::saveNotes($notes);

			FlashMessage::setMsg('success', __('app.personal_notes_saved_successfully'));
		} catch (\Exception $e) {
			FlashMessage::setMsg('error', $e->getMessage());
		}

		return redirect('/profile');
	}
}
