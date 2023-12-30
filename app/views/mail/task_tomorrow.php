<!doctype html>
<html lang="{{ getLocale() }}">
    <head>
        <meta charset="utf-8"/>

		<title>{{ __('app.mail_info_task_tomorrow') }}</title>
    </head>

    <body>
        <h1>{{ __('app.mail_info_task_tomorrow') }}</h1>

        <p>
            {!! __('app.mail_info_task_tomorrow_hint', ['name' => $task->get('title'), 'date' => date('Y-m-d', strtotime($task->get('due_date'))), 'url' => url('/tasks')]) !!}
        </p>

        <p>
            <small>Powered by {{ env('APP_NAME') }}</small>
        </p>
    </body>
</html>