<?php

/**
 * Plants controller
 */
class PlantsController extends BaseController {
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
	 * Handles URL: /plants/location/{id}
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\ViewHandler|Asatru\View\RedirectHandler
	 */
	public function plants_from_location($request)
	{
		$user = UserModel::getAuthUser();

		$location = $request->arg('id');

		if (!LocationsModel::isActive($location)) {
			return redirect('/');
		}

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
	 * Handles URL: /plants/location/{id}/repot
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\RedirectHandler
	 */
	public function set_plants_repotted($request)
	{
		try {
			$user = UserModel::getAuthUser();

			$location = $request->arg('id');

			PlantsModel::updateLastRepotted($location);

			FlashMessage::setMsg('success', __('app.all_plants_repotted'));

			return redirect('/plants/location/' . $location);
		} catch (\Exception $e) {
			FlashMessage::setMsg('error', $e->getMessage());
			return back();
		}
	}

	/**
	 * Handles URL: /plants/location/{id}/fertilise
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\RedirectHandler
	 */
	public function set_plants_fertilised($request)
	{
		try {
			$user = UserModel::getAuthUser();

			$location = $request->arg('id');

			PlantsModel::updateLastFertilised($location);

			FlashMessage::setMsg('success', __('app.all_plants_fertilised'));

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

		$plant_ident = '#' . sprintf('%04d', $plant_data->get('id'));
		
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

		$custom_attributes = CustPlantAttrModel::getForPlant($plant_id);
		
		return parent::view(['content', 'details'], [
			'user' => $user,
			'plant' => $plant_data,
			'plant_ident' => $plant_ident,
			'photos' => $photos,
			'tags' => $tags,
			'custom_attributes' => $custom_attributes,
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
			'location' => 'required'
		]);

		if (!$validator->isValid()) {
			$errorstr = '';
			foreach ($validator->errorMsgs() as $err) {
				$errorstr .= $err . '<br/>';
			}

			FlashMessage::setMsg('error', 'Invalid data given:<br/>' . $errorstr);
			
			return redirect('/');
		}

		$name = $request->params()->query('name', null);
		$location = $request->params()->query('location', null);

		$plant_id = PlantsModel::addPlant($name, $location);

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
		PlantsModel::setUpdated($plant);

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
			$plant = $request->params()->query('plant', null);

			PlantPhotoModel::removePhoto($photo);
			PlantsModel::setUpdated($plant);

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
	 * Handles URL: /plants/details/gallery/photo/label/edit
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
	public function edit_gallery_photo_label($request)
	{
		try {
			$photo = $request->params()->query('id', null);
			$label = $request->params()->query('label', null);
			$plant = $request->params()->query('plant', null);

			PlantPhotoModel::editLabel($photo, $label);
			PlantsModel::setUpdated($plant);

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
	 * Handles URL: /plants/attributes/add
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\RedirectHandler
	 */
	public function add_custom_attribute($request)
	{
		try {
			$validator = new Asatru\Controller\PostValidator([
				'plant' => 'required',
				'label' => 'required',
				'datatype' => 'required'
			]);
	
			if (!$validator->isValid()) {
				FlashMessage::setMsg('error', 'Invalid data given');
				return back();
			}
	
			$plant = $request->params()->query('plant', null);
			$label = $request->params()->query('label', null);
			$datatype = $request->params()->query('datatype', null);
			$content = $request->params()->query('content', null);
			$anchor = $request->params()->query('anchor', '');
			
			CustPlantAttrModel::addAttribute($plant, $label, $datatype, $content);
	
			return redirect('/plants/details/' . $plant . ((strlen($anchor) > 0) ? '#' . $anchor : ''));
		} catch (\Exception $e) {
			FlashMessage::setMsg('error', $e->getMessage());
			return back();
		}
	}

	/**
	 * Handles URL: /plants/attributes/edit
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\RedirectHandler
	 */
	public function edit_custom_attribute($request)
	{
		try {
			$validator = new Asatru\Controller\PostValidator([
				'id' => 'required',
				'plant' => 'required',
				'label' => 'required',
				'datatype' => 'required'
			]);
	
			if (!$validator->isValid()) {
				FlashMessage::setMsg('error', 'Invalid data given');
				return back();
			}
	
			$id = $request->params()->query('id', null);
			$plant = $request->params()->query('plant', null);
			$label = $request->params()->query('label', null);
			$datatype = $request->params()->query('datatype', null);
			$content = $request->params()->query('content', null);
			$anchor = $request->params()->query('anchor', '');
			
			CustPlantAttrModel::editAttribute($id, $plant, $label, $datatype, $content);
	
			return redirect('/plants/details/' . $plant . ((strlen($anchor) > 0) ? '#' . $anchor : ''));
		} catch (\Exception $e) {
			FlashMessage::setMsg('error', $e->getMessage());
			return back();
		}
	}

	/**
	 * Handles URL: /plants/attributes/remove
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
	public function remove_custom_attribute($request)
	{
		try {
			$id = $request->params()->query('id', null);
	
			CustPlantAttrModel::removeAttribute($id);
	
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

			if ($location == 0) {
				return back();
			}

			return redirect('/plants/location/' . $location);
		} catch (\Exception $e) {
			FlashMessage::setMsg('error', $e->getMessage());
			return back();
		}
	}

    /**
	 * Handles URL: /plants/history
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\ViewHandler|Asatru\View\RedirectHandler
	 */
	public function view_history($request)
	{
		if (!app('history_enable')) {
			return redirect('/');
		}

		$year = $request->params()->query('year', null);
		$limit = $request->params()->query('limit', null);
		$sorting = $request->params()->query('sorting', null);
		$direction = $request->params()->query('direction', null);

		$user = UserModel::getAuthUser();

		$years = PlantsModel::getHistoryYears();
		$history = PlantsModel::getHistory($year, $limit, $sorting, $direction);

		return parent::view(['content', 'history'], [
			'user' => $user,
			'history' => $history,
			'years' => $years,
			'sorting_types' => PlantsModel::$sorting_list,
			'sorting_dirs' => PlantsModel::$sorting_dir
		]);
	}

	/**
	 * Handles URL: /plants/history/add
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\RedirectHandler
	 */
	public function add_to_history($request)
	{
		try {
			$plant = $request->params()->query('plant', null);

			PlantsModel::markHistorical($plant);

			return redirect('/plants/history');
		} catch (\Exception $e) {
			FlashMessage::setMsg('error', $e->getMessage());
			return back();
		}
	}

	/**
	 * Handles URL: /plants/history/remove
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\RedirectHandler
	 */
	public function remove_from_history($request)
	{
		try {
			$plant = $request->params()->query('plant', null);

			PlantsModel::unmarkHistorical($plant);

			return redirect('/plants/history');
		} catch (\Exception $e) {
			FlashMessage::setMsg('error', $e->getMessage());
			return back();
		}
	}

	/**
	 * Handles URL: /plants/clone
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
	public function clone_plant($request)
	{
		try {
			$id = $request->params()->query('id', null);

			$clone_id = PlantsModel::clonePlant($id);

			return json([
				'code' => 200,
				'clone_id' => $clone_id
			]);
		} catch (\Exception $e) {
			return json([
				'code' => 500,
				'msg' => $e->getMessage()
			]);
		}
	}

	/**
	 * Handles URL: /plants/qrcode
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
	public function generate_qr_code($request)
	{
		try {
			$plant = $request->params()->query('plant', null);

			$qrcode = PlantsModel::generateQRCode($plant);

			return json([
				'code' => 200,
				'qrcode' => $qrcode
			]);
		} catch (\Exception $e) {
			return json([
				'code' => 500,
				'msg' => $e->getMessage()
			]);
		}
	}

	/**
	 * Handles URL: /plants/qrcode/bulk
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
	public function get_bulk_qr_codes($request)
	{
		try {
			$result = [];

			$plants = json_decode($request->params()->query('list', null));
			foreach ($plants as $plant) {
				$code = PlantsModel::generateQRCode($plant[0]);
				if ($code) {
					$result[] = [
						'plantid' => $plant[0],
						'plantname' => $plant[1],
						'qrcode' => $code
					];
				}
			}

			return json([
				'code' => 200,
				'list' => $result
			]);
		} catch (\Exception $e) {
			return json([
				'code' => 500,
				'msg' => $e->getMessage()
			]);
		}
	}
}
