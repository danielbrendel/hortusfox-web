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
    <pre>{{ $task->get('description') }}</pre>
</p>

<div>
    <div><strong>{{ __('app.recurring_time') }}:&nbsp;</strong>{{ $task->get('recurring_time') }}</div>
    <div><strong>{{ __('app.due') }}:&nbsp;</strong>{{ date('Y-m-d', strtotime($task->get('due_date'))) }}</div>
    <div><a href="{{ workspace_url('/tasks') }}">{{ workspace_url('/tasks') }}</div>
</div>
