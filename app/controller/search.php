<?php

/**
 * Class SearchController
 * 
 * Gateway to the search feature
 */
class SearchController extends BaseController {
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
	 * Handles URL: /search
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\ViewHandler
	 */
	public function view_search($request)
	{
		$user = UserModel::getAuthUser();

		$query = $request->params()->query('query', '');

		$tag_list = PlantsModel::getDistinctTags()?->asArray();
		
		return parent::view(['content', 'search'], [
			'user' => $user,
			'query' => $query,
			'tag_list' => $tag_list,
			'_action_query' => 'action-search',
			'search_name' => true,
			'search_scientific_name' => true,
			'search_tags' => true,
			'search_notes' => true,
			'search_custom' => true
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
			$search_name = (bool)$request->params()->query('search_name', 0);
			$search_scientific_name = (bool)$request->params()->query('search_scientific_name', 0);
			$search_tags = (bool)$request->params()->query('search_tags', 0);
			$search_notes = (bool)$request->params()->query('search_notes', 0);
			$search_custom = (bool)$request->params()->query('search_custom', 0);
			
			$search_result = PlantsModel::performSearch($text, $search_name, $search_scientific_name, $search_tags, $search_notes)->asArray();

			if ($search_custom) {
				$cust_attr_plants = CustPlantAttrModel::performSearch($text)->asArray();

				foreach ($cust_attr_plants as $key => $cust_plant) {
					$exists = UtilsModule::array_from_key_value($search_result, 'id', $cust_plant['plant']);
					if ($exists > -1) {
						$search_result[$exists][$cust_plant['label']] = $cust_plant['content'];
					} else {
						$plant_info = PlantsModel::getDetails($cust_plant['plant'])->asArray();

						if ($plant_info) {
							$plant_info[$cust_plant['label']] = $cust_plant['content'];

							$search_result[] = $plant_info;
						}
					}
				}
			}

			$tag_list = PlantsModel::getDistinctTags()?->asArray();
			
			return parent::view(['content', 'search'], [
				'user' => $user,
				'query' => $text,
				'tag_list' => $tag_list,
				'plants' => $search_result,
				'search_name' => $search_name,
				'search_scientific_name' => $search_scientific_name,
				'search_tags' => $search_tags,
				'search_notes' => $search_notes,
				'search_custom' => $search_custom
			]);
		} catch (\Exception $e) {
			FlashMessage::setMsg('error', $e->getMessage());
			return redirect('/search');
		}
	}
}
