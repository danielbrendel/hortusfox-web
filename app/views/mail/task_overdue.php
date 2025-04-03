<h1>{{ __('app.mail_info_task_overdue') }}</h1>

<p>
    {!! __('app.mail_info_task_overdue_hint', ['name' => $task->get('title'), 'date' => date('Y-m-d', strtotime($task->get('due_date'))), 'url' => workspace_url('/tasks')]) !!}
</p>