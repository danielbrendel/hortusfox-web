<?php

/**
 * This class represents your controller
 */
class CalendarController extends BaseController {
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
	 * Handles URL: /calendar
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\ViewHandler
	 */
	public function view_calendar($request)
	{
		$user = UserModel::getAuthUser();
		
		return parent::view(['content', 'calendar'], [
			'user' => $user
		]);
	}

    /**
	 * Handles URL: /calendar/query
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
    public function query_items($request)
    {
        try {
            $date_from = $request->params()->query('date_from', null);
            $date_till = $request->params()->query('date_till', null);

            if ($date_from === null) {
                $date_from = date('Y-m-d');
            }

            if ($date_till === null) {
                $date_till = date('Y-m-d', strtotime('+30 days'));
            }

            $items = CalendarModel::getItems($date_from, $date_till)->asArray();

            foreach ($items as $key => &$value) {
                if (isset($value['class_name'])) {
                    $value['class_descriptor'] = $value['class_name'];

                    $calendar_class_item = CalendarClassModel::findClass($value['class_name']);
                    if ($calendar_class_item) {
                        $value['class_name'] = __($calendar_class_item->get('name'));
                    } else {
                        $value['class_name'] = __('app.unknown_calendar_class');
                    }
                }
            }

            return json([
                'code' => 200,
                'data' => $items,
                'date_from' => $date_from,
                'date_till' => $date_till
            ]);
        } catch (\Exception $e) {
            return json([
                'code' => 500,
                'msg' => $e->getMessage()
            ]);
        }
    }

    /**
	 * Handles URL: /calendar/add
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\RedirectHandler
	 */
    public function add_item($request)
    {
        try {
            $validator = new Asatru\Controller\PostValidator([
                'name' => 'required',
                'date_from' => 'required',
                'date_till' => 'required'
            ]);
    
            if (!$validator->isValid()) {
                $errorstr = '';
                foreach ($validator->errorMsgs() as $err) {
                    $errorstr .= $err . '<br/>';
                }
    
                FlashMessage::setMsg('error', 'Invalid data given:<br/>' . $errorstr);
                
                return back();
            }
    
            $name = $request->params()->query('name', null);
            $date_from = $request->params()->query('date_from', null);
            $date_till = $request->params()->query('date_till', null);
            $class = $request->params()->query('class', null);

            if ($date_till === null) {
                $date_till = date('Y-m-d', strtotime('+1 day', strtotime($date_from)));
            }

            if ($date_from === $date_till) {
                $date_till = date('Y-m-d', strtotime('+1 day', strtotime($date_from)));
            }

            CalendarModel::addItem($name, $date_from, $date_till, $class);
    
            FlashMessage::setMsg('success', __('app.calendar_item_added'));
    
            return redirect('/calendar');
        } catch (\Exception $e) {
            FlashMessage::setMsg('error', $e->getMessage());
    
            return redirect('/calendar');
        }
    }

    /**
	 * Handles URL: /calendar/edit
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\RedirectHandler
	 */
    public function edit_item($request)
    {
        try {    
            $ident = $request->params()->query('ident', null);
            $name = $request->params()->query('name', null);
            $date_from = $request->params()->query('date_from', null);
            $date_till = $request->params()->query('date_till', null);
            $class = $request->params()->query('class', null);

            if ($date_till === null) {
                $date_till = date('Y-m-d', strtotime('+1 day', strtotime($date_from)));
            }

            if ($date_from === $date_till) {
                $date_till = date('Y-m-d', strtotime('+1 day', strtotime($date_from)));
            }
            
            CalendarModel::editItem($ident, $name, $date_from, $date_till, $class);
    
            FlashMessage::setMsg('success', __('app.calendar_item_edited'));
    
            return redirect('/calendar');
        } catch (\Exception $e) {
            FlashMessage::setMsg('error', $e->getMessage());
    
            return redirect('/calendar');
        }
    }

    /**
	 * Handles URL: /calendar/remove
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
    public function remove_item($request)
    {
        try {
            $ident = $request->params()->query('ident', null);

            CalendarModel::removeItem($ident);

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
