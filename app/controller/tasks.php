<?php

/**
 * Class TasksController
 * 
 * Gateway to task actions
 */
class TasksController extends BaseController {
    const INDEX_LAYOUT = 'layout';

	/**
	 * Perform base initialization
	 * 
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct(self::INDEX_LAYOUT);

		if (!app('tasks_enable')) {
			throw new \Exception(403);
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
		$recurring = (bool)$request->params()->query('recurring', false);
		$recurring_time = (int)$request->params()->query('recurring_time', 0);
		$timescope = $request->params()->query('timescope', 'hours');
		$plant_id = (int)$request->params()->query('plant_id', 0);

		if (strlen($due_date) === 0) {
			$due_date = null;
		}

		if ((!$due_date) || (!$recurring)) {
			$recurring_time = null;
		}

		$task_id = TasksModel::addTask($title, $description, $due_date, $recurring_time, $timescope);

		$redirect_url = '/tasks';

		if ($plant_id) {
			PlantTasksRefModel::addReference($plant_id, $task_id);

			$redirect_url = '/plants/details/' . $plant_id . '#plant-tasks-anchor';
		}

		FlashMessage::setMsg('success', __('app.task_created_successfully'));

		return redirect($redirect_url);
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
		$recurring = (bool)$request->params()->query('recurring', false);
		$recurring_time = (int)$request->params()->query('recurring_time', 0);
		$timescope = $request->params()->query('timescope', 'hours');

		if ((!$due_date) || (!$recurring)) {
			$recurring_time = null;
		}

		TasksModel::editTask($task, $title, $description, $due_date, $recurring_time, $timescope);

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
	 * Handles URL: /tasks/remove
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
	public function remove_task($request)
	{
		try {
			$task = $request->params()->query('task', null);

			TasksModel::removeTask($task);

			if (PlantTasksRefModel::hasPlantReference($task)) {
				PlantTasksRefModel::removeForTask($task);
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
