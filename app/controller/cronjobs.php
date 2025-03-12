<?php

/**
 * Class CronjobsController
 * 
 * Gateway to all cronjobs
 */
class CronjobsController extends BaseController {
    /**
	 * Perform base initialization
	 * 
	 * @return void
	 */
    public function __construct()
    {
        if (app('cronjob_pw') === null) {
            http_response_code(500);
            exit('Please set cronjob_pw to a proper authentication token');
        }

        if ((!isset($_GET['cronpw'])) || ($_GET['cronpw'] !== app('cronjob_pw'))) {
            http_response_code(403);
            exit();
        }

        app_mail_config();
    }

    /**
	 * Handles URL: /cronjob/tasks/overdue
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
    public function overdue_tasks($request)
    {
        try {
            TasksModel::cronjobOverdue();

            return json(['code' => 200]);
        } catch (\Exception $e) {
            return json([
                'code' => 500,
                'msg' => $e->getMessage()
            ]);
        }
    }

    /**
	 * Handles URL: /cronjob/tasks/tomorrow
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
    public function tomorrow_tasks($request)
    {
        try {
            TasksModel::cronjobTomorrow();

            return json(['code' => 200]);
        } catch (\Exception $e) {
            return json([
                'code' => 500,
                'msg' => $e->getMessage()
            ]);
        }
    }

    /**
	 * Handles URL: /cronjob/tasks/recurring
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
    public function recurring_tasks($request)
    {
        try {
            TasksModel::cronjobRecurring();

            return json(['code' => 200]);
        } catch (\Exception $e) {
            return json([
                'code' => 500,
                'msg' => $e->getMessage()
            ]);
        }
    }

    /**
	 * Handles URL: /cronjob/calendar/reminder
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
    public function calendar_reminder($request)
    {
        try {
            CalendarModel::cronjobReminder();

            return json(['code' => 200]);
        } catch (\Exception $e) {
            return json([
                'code' => 500,
                'msg' => $e->getMessage()
            ]);
        }
    }

    /**
	 * Handles URL: /cronjob/backup/auto
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
    public function auto_backup($request)
    {
        try {
            if (!app('auto_backup')) {
                throw new \Exception(__('app.auto_backup_not_active'));
            }

            $file_name = BackupModule::start([
                'locations' => true,
                'plants' => true,
                'gallery' => true,
                'tasks' => true,
                'inventory' => true,
                'calendar' => true
            ]);

            $storage_path = app('backup_path');
            if ((is_string($storage_path)) && (strlen($storage_path) > 0) && (is_dir($storage_path))) {
                if (strpos($storage_path, '/', -1) === false) {
                    $storage_path .= '/';
                }
                
                copy(public_path() . '/backup/' . $file_name, $storage_path . $file_name);
                unlink(public_path() . '/backup/' . $file_name);
            }

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
