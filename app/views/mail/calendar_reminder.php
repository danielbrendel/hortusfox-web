<!doctype html>
<html lang="{{ getLocale() }}">
    <head>
        <meta charset="utf-8"/>

		<title>{{ __('app.mail_info_calendar_reminder') }}</title>
    </head>

    <body>
        <h1>{{ __('app.mail_info_calendar_reminder') }}</h1>

        <p>
            {!! __('app.mail_info_calendar_reminder_hint', ['name' => $item->get('name'), 'date_from' => date('Y-m-d', strtotime($item->get('date_from'))), 'date_till' => date('Y-m-d', strtotime($item->get('date_till'))), 'url' => url('/calendar')]) !!}
        </p>

        <p>
            <small>Powered by {{ env('APP_NAME') }}</small>
        </p>
    </body>
</html>