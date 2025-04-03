<h1>{{ __('app.mail_info_task_recurring') }}</h1>

<p>
    {!! __('app.mail_info_task_recurring_hint', ['name' => $task->get('title'), 'date' => date('Y-m-d', strtotime($task->get('due_date'))), 'time' => $task->get('recurring_time'), 'url' => workspace_url('/tasks')]) !!}
</p>