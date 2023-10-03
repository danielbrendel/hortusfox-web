<?php

/*
    Asatru PHP - Example controller

    Add here all your needed routes implementations related to 'index'.
*/

/**
 * Example index controller
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
		$log = LogModel::getHistory();
		$stats = UtilsModule::getStats();
		
		return parent::view(['content', 'index'], [
			'user' => $user,
			'warning_plants' => $warning_plants,
			'locations' => $locs,
			'log' => $log,
			'stats' => $stats
		]);
	}

	/**
	 * Handles URL: /plants/location/{id}
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\ViewHandler
	 */
	public function plants_from_location($request)
	{
		$location = $request->arg('id');

		$plants = PlantsModel::getAll($location);
		
		return parent::view(['content', 'plants'], [
			'plants' => $plants,
			'location' => $location,
			'location_name' => LocationsModel::getNameById($location)
		]);
	}

	/**
	 * Handles URL: /plants/details/{id}
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\ViewHandler
	 */
	public function view_plant_details($request)
	{
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
			'query' => $query
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
			$search_tags = $request->params()->query('search_tags', false);
			$search_notes = $request->params()->query('search_notes', false);
			
			$search_result = PlantsModel::performSearch($text, $search_name, $search_tags, $search_notes);

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

		TasksModel::addTask($title, $description);

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

		TasksModel::editTask($task, $title, $description);

		FlashMessage::setMsg('success', __('app.task_edited_successfully'));

		return redirect('/tasks');
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
}
