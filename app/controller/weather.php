<?php

/**
 * This class represents your controller
 */
class WeatherController extends BaseController {
    const INDEX_LAYOUT = 'layout';

    /**
	 * Perform base initialization
	 * 
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct(self::INDEX_LAYOUT);

		if (!app('owm_enable')) {
			throw new \Exception(403);
		}
	}

    /**
	 * Handles URL: /weather
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\ViewHandler
	 */
	public function view_forecast($request)
	{
		$user = UserModel::getAuthUser();
        $forecast = WeatherModule::forecast();

        $weekdays = [];
        for ($i = 0; $i < 5; $i++) {
            $curtime = strtotime('+' . strval($i) . ' days');

            $weekdays[] = [
                'date' => date('Y-m-d', $curtime),
                'day' => date('l', $curtime)
            ];
        }
		
		return parent::view(['content', 'weather'], [
			'user' => $user,
            'forecast' => $forecast,
            'weekdays' => $weekdays
		]);
	}
}
