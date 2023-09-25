<?php

/**
 * Example error 404 controller
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
		//Add a log line
		addLog(ASATRU_LOG_INFO, "Error 404");

		//Generate the 404 view
		$v = new Asatru\View\ViewHandler();
		$v->setLayout('layout') //The layout file. Will be \app\views\layout.php
			->setYield('yield', 'error/404'); //The index yield. Will be \app\views\error\404.php
		
		return $v; //Pass the object to the engine
	}
}