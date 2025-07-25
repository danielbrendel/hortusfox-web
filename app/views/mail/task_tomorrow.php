<h1>{{ $task->get('title') }}</h1>

@if (is_object($plant))
<p>
    <a href="{{ url('/plants/details/' . $plant->get('id')) }}">{{ $plant->get('name') }}</a>
</p>

<p>
    <img src="{{ abs_photo($plant->get('photo')) }}" alt="plant-photo"/>
</p>
@endif

<p>
    {!! __('app.mail_info_task_tomorrow_hint', ['name' => $task->get('title'), 'date' => date('Y-m-d', strtotime($task->get('due_date'))), 'url' => workspace_url('/tasks')]) !!}
</p>