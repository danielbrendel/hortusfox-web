<!doctype html>
<html lang="{{ getLocale() }}">
    <head>
        <meta charset="utf-8"/>

		<title>{{ __('app.info_task_is_overdue') }}</title>
    </head>

    <body>
        <h1>{{ __('app.info_task_is_overdue') }}</h1>

        <p>
            {!! __('app.info_task_is_overdue_hint', ['name' => $task->get('title'), 'date' => date('Y-m-d', strtotime($task->get('due_date'))), 'url' => url('/tasks')]) !!}
        </p>

        <p>
            <small>Powered by {{ env('APP_NAME') }}</small>
        </p>
    </body>
</html>