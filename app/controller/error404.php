<?php

/**
 * Class Error404Controller
 * 
 * Serve error 404 page
 */
class Error404Controller extends BaseController {
	/**
	 * Handles special case: $404
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\ViewHandler
	 */
	public function index($request)
	{
		$v = new Asatru\View\ViewHandler();
		$v->setLayout('layout')
			->setYield('content', 'error/404')
			->setVars([
				'user' => UserModel::getAuthUser()
			]);
		
		return $v;
	}
}