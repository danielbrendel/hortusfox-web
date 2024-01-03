<?php

/**
 * Search controller
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
}
