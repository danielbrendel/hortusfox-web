<?php

/*
    Asatru PHP - Example controller

    Add here all your needed routes implementations related to 'index'.
*/

/**
 * Index controller
 */
class IndexController extends BaseController {
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
	 * Handles URL: /
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\ViewHandler
	 */
	public function index($request)
	{
		$user = UserModel::getAuthUser();
		$locs = LocationsModel::getAll();
		$warning_plants = PlantsModel::getWarningPlants();
		$overdue_tasks = TasksModel::getOverdueTasks();
		$log = LogModel::getHistory();
		$stats = UtilsModule::getStats();

		if ($user->get('show_plants_aoru')) {
			$last_plants_list = PlantsModel::getLastAddedPlants();
		} else {
			$last_plants_list = PlantsModel::getLastAuthoredPlants();
		}
		
		return parent::view(['content', 'index'], [
			'user' => $user,
			'warning_plants' => $warning_plants,
			'overdue_tasks' => $overdue_tasks,
			'locations' => $locs,
			'log' => $log,
			'stats' => $stats,
			'last_plants_list' => $last_plants_list
		]);
	}

	/**
	 * Handles URL: /auth
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\ViewHandler
	 */
	public function auth($request)
	{
		$view = new Asatru\View\ViewHandler();
		$view->setLayout('auth');

		return $view;
	}

	/**
	 * Handles URL: /login
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\RedirectHandler
	 */
	public function login($request)
	{
		try {
			$email = $request->params()->query('email', null);
			$password = $request->params()->query('password', null);
			
			UserModel::login($email, $password);

			return redirect('/');
		} catch (\Exception $e) {
			FlashMessage::setMsg('error', $e->getMessage());
			return back();
		}
	}

	/**
	 * Handles URL: /logout
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\RedirectHandler
	 */
	public function logout($request)
	{
		try {
			UserModel::logout();

			return redirect('/');
		} catch (\Exception $e) {
			FlashMessage::setMsg('error', $e->getMessage());
			return back();
		}
	}

	/**
	 * Handles URL: /password/restore
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\RedirectHandler
	 */
	public function restore_password($request)
	{
		try {
			$email = $request->params()->query('email', null);

			UserModel::restorePassword($email);

			FlashMessage::setMsg('success', __('app.restore_password_info'));

			return redirect('/');
		} catch (\Exception $e) {
			FlashMessage::setMsg('error', $e->getMessage());
			return back();
		}
	}

	/**
	 * Handles URL: /password/reset
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\ViewHandler
	 */
	public function view_reset_password($request)
	{
		$token = $request->params()->query('token');

		return view('pwreset', [], ['token' => $token]);
	}

	/**
	 * Handles URL: /password/reset
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\RedirectHandler
	 */
	public function reset_password($request)
	{
		try {
			$token = $request->params()->query('token', null);
			$password = $request->params()->query('password', null);
			$password_confirmation = $request->params()->query('password_confirmation', null);

			if ($password !== $password_confirmation) {
				throw new \Exception(__('app.password_mismatch'));
			}

			UserModel::resetPassword($token, $password);

			return redirect('/');
		} catch (\Exception $e) {
			FlashMessage::setMsg('error', $e->getMessage());
			return back();
		}
	}

	/**
	 * Handles URL: /chat
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\ViewHandler|Asatru\View\RedirectHandler
	 */
	public function view_chat($request)
	{
		if (!env('APP_ENABLECHAT')) {
			return redirect('/');
		}

		$user = UserModel::getAuthUser();

		$messages = ChatMsgModel::getChat();

		return parent::view(['content', 'chat'], [
			'user' => $user,
			'messages' => $messages,
			'_refresh_chat' => true
		]);
	}

	/**
	 * Handles URL: /chat/add
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\RedirectHandler
	 */
	public function add_chat_message($request)
	{
		$validator = new Asatru\Controller\PostValidator([
			'message' => 'required'
		]);

		if (!$validator->isValid()) {
			$errorstr = '';
			foreach ($validator->errorMsgs() as $err) {
				$errorstr .= $err . '<br/>';
			}

			FlashMessage::setMsg('error', 'Invalid data given:<br/>' . $errorstr);
			
			return back();
		}

		$message = $request->params()->query('message', null);

		ChatMsgModel::addMessage($message);

		return redirect('/chat');
	}

	/**
	 * Handles URL: /chat/query
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
	public function query_chat_messages($request)
	{
		try {
			$result = [];

			$messages = ChatMsgModel::getLatestMessages();

			foreach ($messages as $message) {
				$result[] = [
					'id' => $message->get('id'),
					'userId' => $message->get('userId'),
					'userName' => UserModel::getNameById($message->get('userId')),
					'message' => $message->get('message'),
					'chatcolor' => UserModel::getChatColorForUser($message->get('userId')),
					'created_at' => $message->get('created_at'),
					'diffForHumans' => (new Carbon($message->get('created_at')))->diffForHumans(),
				];
			}

			return json([
				'code' => 200,
				'messages' => $result
			]);
		} catch (\Exception $e) {
			return json([
				'code' => 500,
				'msg' => $e->getMessage()
			]);
		}
	}

	/**
	 * Handles URL: /chat/typing/update
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
	public function update_chat_typing($request)
	{
		try {
			UserModel::updateChatTyping();
			
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

	/**
	 * Handles URL: /chat/typing
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
	public function get_chat_typing_status($request)
	{
		try {
			$status = UserModel::isAnyoneTypingInChat();

			return json([
				'code' => 200,
				'status' => $status
			]);
		} catch (\Exception $e) {
			return json([
				'code' => 500,
				'msg' => $e->getMessage()
			]);
		}
	}

	/**
	 * Handles URL: /user/online
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
	public function get_online_users($request)
	{
		try {
			$result = [];

			$users = UserModel::getOnlineUsers();

			foreach ($users as $user) {
				$result[] = [
					'name' => $user->get('name'),
					'typing' => UtilsModule::isTyping($user->get('last_typing'))
				];
			}

			return json([
				'code' => 200,
				'users' => $result
			]);
		} catch (\Exception $e) {
			return json([
				'code' => 500,
				'msg' => $e->getMessage()
			]);
		}
	}

	/**
	 * Handles URL: /history
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\ViewHandler|Asatru\View\RedirectHandler
	 */
	public function view_history($request)
	{
		if (!env('APP_ENABLEHISTORY')) {
			return redirect('/');
		}

		$year = $request->params()->query('year', null);
		$limit = $request->params()->query('limit', null);
		$sorting = $request->params()->query('sorting', null);
		$direction = $request->params()->query('direction', null);

		$user = UserModel::getAuthUser();

		$years = PlantsModel::getHistoryYears();
		$history = PlantsModel::getHistory($year, $limit, $sorting, $direction);

		return parent::view(['content', 'history'], [
			'user' => $user,
			'history' => $history,
			'years' => $years,
			'sorting_types' => PlantsModel::$sorting_list,
			'sorting_dirs' => PlantsModel::$sorting_dir
		]);
	}

	/**
	 * Handles URL: /plants/history/add
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\RedirectHandler
	 */
	public function add_to_history($request)
	{
		try {
			$plant = $request->params()->query('plant', null);

			PlantsModel::markHistorical($plant);

			return redirect('/history');
		} catch (\Exception $e) {
			FlashMessage::setMsg('error', $e->getMessage());
			return back();
		}
	}

	/**
	 * Handles URL: /plants/history/remove
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\RedirectHandler
	 */
	public function remove_from_history($request)
	{
		try {
			$plant = $request->params()->query('plant', null);

			PlantsModel::unmarkHistorical($plant);

			return redirect('/history');
		} catch (\Exception $e) {
			FlashMessage::setMsg('error', $e->getMessage());
			return back();
		}
	}
}
