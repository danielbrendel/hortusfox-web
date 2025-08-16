<h1>{{ $task->get('title') }}</h1>

<p>
    <pre>{{ $task->get('description') }}</pre>
</p>

@if (is_object($plant))
<p>
    <a href="{{ url('/plants/details/' . $plant->get('id')) }}">{{ $plant->get('name') }}</a>
</p>

<p>
    <img src="{{ abs_photo($plant->get('photo')) }}" alt="plant-photo"/>
</p>
@endif

<div>
    <div><strong>{{ __('app.recurring_time') }}:&nbsp;</strong>{{ __('app.recurring_time_with_scope', ['time' => $task->get('recurring_time'), 'scope' => __('app.' . $task->get('recurring_scope'))]) }}</div>
    <div><strong>{{ __('app.due') }}:&nbsp;</strong>{{ date('Y-m-d', strtotime($task->get('due_date'))) }}</div>
    <div><a href="{{ workspace_url('/tasks') }}">{{ workspace_url('/tasks') }}</div>
</div>
