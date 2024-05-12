<?php

/**
 * Tasks controller
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
