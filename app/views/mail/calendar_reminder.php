<h1>{{ __('app.mail_info_calendar_reminder') }}</h1>

<p>
    {!! __('app.mail_info_calendar_reminder_hint', ['name' => $item->get('name'), 'date_from' => date('Y-m-d', strtotime($item->get('date_from'))), 'date_till' => date('Y-m-d', strtotime($item->get('date_till'))), 'url' => workspace_url('/calendar')]) !!}
</p>