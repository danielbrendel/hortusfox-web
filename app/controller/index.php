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
		$locs = LocationsModel::getAll();
		
		return parent::view(['content', 'index'], [
			'locations' => $locs
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
			'plants' => $plants
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
		
		return parent::view(['content', 'details'], [
			'plant' => $plant_data
		]);
	}
}
