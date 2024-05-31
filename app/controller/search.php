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

		return parent::view(['content', 'search'], [
			'user' => $user,
			'query' => $query,
			'_action_query' => 'action-search',
			'search_name' => true,
			'search_scientific_name' => true,
			'search_tags' => true,
			'search_notes' => true
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
			
			$search_result = PlantsModel::performSearch($text, $search_name, $search_scientific_name, $search_tags, $search_notes);

			return parent::view(['content', 'search'], [
				'user' => $user,
				'query' => $text,
				'plants' => $search_result,
				'search_name' => $search_name,
				'search_scientific_name' => $search_scientific_name,
				'search_tags' => $search_tags,
				'search_notes' => $search_notes
			]);
		} catch (\Exception $e) {
			FlashMessage::setMsg('error', $e->getMessage());
			return redirect('/search');
		}
	}
}
