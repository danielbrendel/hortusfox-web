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
		
		return parent::view(['content', 'index'], [
			'user' => $user,
			'warning_plants' => $warning_plants,
			'overdue_tasks' => $overdue_tasks,
			'locations' => $locs,
			'log' => $log,
			'stats' => $stats
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
	 * Handles URL: /plants/location/{id}
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\ViewHandler
	 */
	public function plants_from_location($request)
	{
		$user = UserModel::getAuthUser();

		$location = $request->arg('id');

		$sorting = $request->params()->query('sorting', null);
		$direction = $request->params()->query('direction', null);

		$plants = PlantsModel::getAll($location, $sorting, $direction);
		
		return parent::view(['content', 'plants'], [
			'user' => $user,
			'plants' => $plants,
			'sorting_types' => PlantsModel::$sorting_list,
			'sorting_dirs' => PlantsModel::$sorting_dir,
			'location' => $location,
			'location_name' => LocationsModel::getNameById($location)
		]);
	}

	/**
	 * Handles URL: /plants/location/{id}/water
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\RedirectHandler
	 */
	public function set_plants_watered($request)
	{
		try {
			$user = UserModel::getAuthUser();

			$location = $request->arg('id');

			PlantsModel::updateLastWatered($location);

			FlashMessage::setMsg('success', __('app.all_plants_watered'));

			return redirect('/plants/location/' . $location);
		} catch (\Exception $e) {
			FlashMessage::setMsg('error', $e->getMessage());
			return back();
		}
	}

	/**
	 * Handles URL: /plants/details/{id}
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\ViewHandler
	 */
	public function view_plant_details($request)
	{
		$user = UserModel::getAuthUser();

		$plant_id = $request->arg('id');

		$plant_data = PlantsModel::getDetails($plant_id);

		$edit_user_name = '';
		$edit_user_when = '';

		$userdata = UserModel::getUserById($plant_data->get('last_edited_user'));
		if ($userdata) {
			$edit_user_name = $userdata->get('name');
			$edit_user_when = (new Carbon($plant_data->get('last_edited_date')))->diffForHumans();
		}

		$tagstr = $plant_data->get('tags');
		if (substr($tagstr, strlen($tagstr) - 1, 1) !== ' ') {
			$tagstr .= ' ';
		}

		$tags = explode(' ', $tagstr);

		$photos = PlantPhotoModel::getPlantGallery($plant_id);
		
		return parent::view(['content', 'details'], [
			'user' => $user,
			'plant' => $plant_data,
			'photos' => $photos,
			'tags' => $tags,
			'edit_user_name' => $edit_user_name,
			'edit_user_when' => $edit_user_when
		]);
	}

	/**
	 * Handles URL: /plants/add
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\RedirectHandler
	 */
	public function add_plant($request)
	{
		$validator = new Asatru\Controller\PostValidator([
			'name' => 'required',
			'location' => 'required',
			'humidity' => 'required',
			'light_level' => 'required'
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
		$location = $request->params()->query('location', null);
		$perennial = $request->params()->query('perennial', false);
		$humidity = $request->params()->query('humidity', 0);
		$light_level = $request->params()->query('light_level', '');

		$plant_id = PlantsModel::addPlant($name, $location, $perennial, $humidity, $light_level);

		return redirect('/plants/details/' . $plant_id);
	}

	/**
	 * Handles URL: /plants/details/edit
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\RedirectHandler
	 */
	public function edit_plant_details($request)
	{
		$validator = new Asatru\Controller\PostValidator([
			'plant' => 'required',
			'attribute' => 'required'
		]);

		if (!$validator->isValid()) {
			FlashMessage::setMsg('error', 'Invalid data given');
			return back();
		}

		$plant = $request->params()->query('plant', null);
		$attribute = $request->params()->query('attribute', null);
		$value = $request->params()->query('value', false);
		$anchor = $request->params()->query('anchor', '');
		
		PlantsModel::editPlantAttribute($plant, $attribute, $value);

		return redirect('/plants/details/' . $plant . ((strlen($anchor) > 0) ? '#' . $anchor : ''));
	}

	/**
	 * Handles URL: /plants/details/edit/link
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\RedirectHandler
	 */
	public function edit_plant_link($request)
	{
		$validator = new Asatru\Controller\PostValidator([
			'plant' => 'required',
			'text' => 'required'
		]);

		if (!$validator->isValid()) {
			FlashMessage::setMsg('error', 'Invalid data given');
			return back();
		}

		$plant = $request->params()->query('plant', null);
		$text = $request->params()->query('text', null);
		$link = $request->params()->query('link', false);
		$anchor = $request->params()->query('anchor', '');
		
		PlantsModel::editPlantLink($plant, $text, $link);

		return redirect('/plants/details/' . $plant . ((strlen($anchor) > 0) ? '#' . $anchor : ''));
	}

	/**
	 * Handles URL: /plants/details/edit/photo
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\RedirectHandler
	 */
	public function edit_plant_details_photo($request)
	{
		$validator = new Asatru\Controller\PostValidator([
			'plant' => 'required',
			'attribute' => 'required'
		]);

		if (!$validator->isValid()) {
			FlashMessage::setMsg('error', 'Invalid data given');
			return back();
		}

		$plant = $request->params()->query('plant', null);
		$attribute = $request->params()->query('attribute', null);
		
		PlantsModel::editPlantPhoto($plant, $attribute, 'value');

		return redirect('/plants/details/' . $plant);
	}

	/**
	 * Handles URL: /plants/details/gallery/add
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\RedirectHandler
	 */
	public function add_plant_gallery_photo($request)
	{
		$validator = new Asatru\Controller\PostValidator([
			'plant' => 'required',
			'label' => 'required'
		]);

		if (!$validator->isValid()) {
			$errorstr = '';
			foreach ($validator->errorMsgs() as $err) {
				$errorstr .= $err . '<br/>';
			}

			FlashMessage::setMsg('error', 'Invalid data given:<br/>' . $errorstr);
			
			return back();
		}

		$plant = $request->params()->query('plant', null);
		$label = $request->params()->query('label', '');

		PlantPhotoModel::uploadPhoto($plant, $label);

		FlashMessage::setMsg('success', __('app.photo_uploaded_successfully'));

		return redirect('/plants/details/' . $plant . '#plant-gallery-photo-anchor');
	}

	/**
	 * Handles URL: /plants/details/gallery/photo/remove
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
	public function remove_gallery_photo($request)
	{
		try {
			$photo = $request->params()->query('photo', null);

			PlantPhotoModel::removePhoto($photo);

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
	 * Handles URL: /plants/remove
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\RedirectHandler
	 */
	public function remove_plant($request)
	{
		try {
			$plant = $request->params()->query('plant', null);
			$location = $request->params()->query('location', 0);

			PlantsModel::removePlant($plant);

			return redirect('/plants/location/' . $location);
		} catch (\Exception $e) {
			FlashMessage::setMsg('error', $e->getMessage());
			return back();
		}
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
			$chatcolor = $request->params()->query('chatcolor', null);
			$show_log = $request->params()->query('show_log', false);
			$notify_overdue_tasks = $request->params()->query('notify_overdue_tasks', false);

			UserModel::editPreferences($name, $email, $lang, $chatcolor, $show_log, $notify_overdue_tasks);

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
	 * Handles URL: /search
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\ViewHandler
	 */
	public function view_search($request)
	{
		$user = UserModel::getAuthUser();

		$query = $request->params()->query('query', '');

		return parent::view(['content', 'search'], [
			'user' => $user,
			'query' => $query,
			'_action_query' => 'action-search'
		]);
	}

	/**
	 * Handles URL: /search/perform
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\ViewHandler|Asatru\View\RedirectHandler
	 */
	public function perform_search($request)
	{
		try {
			$user = UserModel::getAuthUser();

			$text = $request->params()->query('text', '');
			$search_name = $request->params()->query('search_name', true);
			$search_scientific_name = $request->params()->query('search_scientific_name', true);
			$search_tags = $request->params()->query('search_tags', false);
			$search_notes = $request->params()->query('search_notes', false);
			
			$search_result = PlantsModel::performSearch($text, $search_name, $search_scientific_name, $search_tags, $search_notes);

			return parent::view(['content', 'search'], [
				'user' => $user,
				'query' => $text,
				'plants' => $search_result
			]);
		} catch (\Exception $e) {
			FlashMessage::setMsg('error', $e->getMessage());
			return redirect('/search');
		}
	}

	/**
	 * Handles URL: /tasks
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\ViewHandler
	 */
	public function view_tasks($request)
	{
		$user = UserModel::getAuthUser();

		$done = $request->params()->query('done', false);

		$tasks = TasksModel::getTasks($done);

		return parent::view(['content', 'tasks'], [
			'user' => $user,
			'tasks' => $tasks
		]);
	}

	/**
	 * Handles URL: /tasks/create
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\RedirectHandler
	 */
	public function create_task($request)
	{
		$validator = new Asatru\Controller\PostValidator([
			'title' => 'required',
			'description' => 'required'
		]);

		if (!$validator->isValid()) {
			$errorstr = '';
			foreach ($validator->errorMsgs() as $err) {
				$errorstr .= $err . '<br/>';
			}

			FlashMessage::setMsg('error', 'Invalid data given:<br/>' . $errorstr);
			
			return back();
		}

		$title = $request->params()->query('title', null);
		$description = $request->params()->query('description', '');
		$due_date = $request->params()->query('due_date', '');

		if (strlen($due_date) === 0) {
			$due_date = null;
		}

		TasksModel::addTask($title, $description, $due_date);

		FlashMessage::setMsg('success', __('app.task_created_successfully'));

		return redirect('/tasks');
	}

	/**
	 * Handles URL: /tasks/edit
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\RedirectHandler
	 */
	public function edit_task($request)
	{
		$validator = new Asatru\Controller\PostValidator([
			'task' => 'required',
			'title' => 'required',
			'description' => 'required'
		]);

		if (!$validator->isValid()) {
			$errorstr = '';
			foreach ($validator->errorMsgs() as $err) {
				$errorstr .= $err . '<br/>';
			}

			FlashMessage::setMsg('error', 'Invalid data given:<br/>' . $errorstr);
			
			return back();
		}

		$task = $request->params()->query('task', null);
		$title = $request->params()->query('title', null);
		$description = $request->params()->query('description', '');
		$due_date = $request->params()->query('due_date', '');

		if (strlen($due_date) === 0) {
			$due_date = null;
		}

		TasksModel::editTask($task, $title, $description, $due_date);

		FlashMessage::setMsg('success', __('app.task_edited_successfully'));

		return redirect('/tasks#task-anchor-' . $task);
	}

	/**
	 * Handles URL: /tasks/toggle
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
	public function toggle_task($request)
	{
		try {
			$task = $request->params()->query('task', null);

			TasksModel::toggleTaskStatus($task);

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
	 * Handles URL: /inventory
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\ViewHandler
	 */
	public function view_inventory($request)
	{
		$user = UserModel::getAuthUser();

		$inventory = InventoryModel::getInventory();

		$expand = $request->params()->query('expand', null);
		
		return parent::view(['content', 'inventory'], [
			'user' => $user,
			'inventory' => $inventory,
			'_expand_inventory_item' => $expand
		]);
	}

	/**
	 * Handles URL: /inventory/add
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\RedirectHandler
	 */
	public function add_inventory_item($request)
	{
		$validator = new Asatru\Controller\PostValidator([
			'name' => 'required',
			'group' => 'required'
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
		$group = $request->params()->query('group', null);
		$description = $request->params()->query('description', null);

		$id = InventoryModel::addItem($name, $description, $group);

		return redirect('/inventory?expand=' . $id . '#anchor-item-' . $id);
	}

	/**
	 * Handles URL: /inventory/edit
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\RedirectHandler
	 */
	public function edit_inventory_item($request)
	{
		$validator = new Asatru\Controller\PostValidator([
			'id' => 'required',
			'name' => 'required',
			'group' => 'required'
		]);

		if (!$validator->isValid()) {
			$errorstr = '';
			foreach ($validator->errorMsgs() as $err) {
				$errorstr .= $err . '<br/>';
			}

			FlashMessage::setMsg('error', 'Invalid data given:<br/>' . $errorstr);
			
			return back();
		}

		$id = $request->params()->query('id', null);
		$name = $request->params()->query('name', null);
		$group = $request->params()->query('group', null);
		$description = $request->params()->query('description', null);

		InventoryModel::editItem($id, $name, $description, $group);

		return redirect('/inventory?expand=' . $id . '#anchor-item-' . $id);
	}

	/**
	 * Handles URL: /inventory/amount/increment
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
	public function inc_inventory_item($request)
	{
		try {
			$id = $request->params()->query('id', null);

			$amount = InventoryModel::incAmount($id);

			return json([
				'code' => 200,
				'amount' => $amount
			]);
		} catch (\Exception $e) {
			return json([
				'code' => 500,
				'msg' => $e->getMessage()
			]);
		}
	}

	/**
	 * Handles URL: /inventory/amount/decrement
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
	public function dec_inventory_item($request)
	{
		try {
			$id = $request->params()->query('id', null);

			$amount = InventoryModel::decAmount($id);

			return json([
				'code' => 200,
				'amount' => $amount
			]);
		} catch (\Exception $e) {
			return json([
				'code' => 500,
				'msg' => $e->getMessage()
			]);
		}
	}

	/**
	 * Handles URL: /inventory/remove
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
	public function remove_inventory_item($request)
	{
		try {
			$id = $request->params()->query('id', null);

			InventoryModel::removeItem($id);

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
	 * Handles URL: /inventory/group/add
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\RedirectHandler
	 */
	public function add_inventory_group_item($request)
	{
		$validator = new Asatru\Controller\PostValidator([
			'token' => 'required',
			'label' => 'required'
		]);

		if (!$validator->isValid()) {
			$errorstr = '';
			foreach ($validator->errorMsgs() as $err) {
				$errorstr .= $err . '<br/>';
			}

			FlashMessage::setMsg('error', 'Invalid data given:<br/>' . $errorstr);
			
			return back();
		}

		$token = $request->params()->query('token', null);
		$label = $request->params()->query('label', null);

		try {
			InvGroupModel::addItem($token, $label);
		} catch (\Exception $e) {
			FlashMessage::setMsg('error', $e->getMessage());
			return back();
		}

		return redirect('/inventory');
	}

	/**
	 * Handles URL: /inventory/group/edit
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
	public function edit_inventory_group_item($request)
	{
		try {
			$id = $request->params()->query('id', null);
			$what = $request->params()->query('what', null);
			$value = $request->params()->query('value', null);

			InvGroupModel::editItem($id, $what, $value);

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
	 * Handles URL: /inventory/group/remove
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
	public function remove_inventory_group_item($request)
	{
		try {
			$id = $request->params()->query('id', null);

			InvGroupModel::removeItem($id);

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
	 * Handles URL: /chat
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\ViewHandler
	 */
	public function view_chat($request)
	{
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
}
